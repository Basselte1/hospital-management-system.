<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\ProductUsage;
use App\Models\ProductSterilization;
use App\Models\StockTransaction;
use App\Models\Patient;
use App\Models\User;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReusableProductController extends Controller
{
    /**
     * Dashboard for reusable products management
     */
    public function index()
    {
        // Accessible by Pharmacien, Infirmière, Admin
        if (!in_array(Auth::user()->role_id, [1, 4, 5, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $reusableProducts = Produit::where('is_reusable', true)
                                   ->get();

        $stats = [
            'total_reusable' => $reusableProducts->count(),
            'en_utilisation' => $reusableProducts->sum('qte_en_utilisation'),
            'en_sterilisation' => $reusableProducts->sum('qte_en_sterilisation'),
            'usages_en_attente' => ProductUsage::enAttente()->retournable()->count(),
            'sterilisations_en_cours' => ProductSterilization::enCours()->count(),
            'sterilisations_en_attente' => ProductSterilization::enAttente()->count(),
        ];

        return view('admin.reusable_products.index', compact('reusableProducts', 'stats'));
    }

    /**
     * Record product usage (when used in hospital)
     */
    public function recordUsage(Request $request)
    {
        // Infirmière, Médecin, Admin can record usage
        if (!in_array(Auth::user()->role_id, [1, 2,5,7, 4])) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'patient_id' => 'nullable|exists:patients,id',
            'type_utilisation' => 'required|in:intervention_chirurgicale,consultation,hospitalisation,urgence,autre',
            'service' => 'nullable|string',
            'medecin_id' => 'nullable|exists:users,id',
            'infirmier_id' => 'nullable|exists:users,id',
            'date_utilisation' => 'required|date',
            'heure_utilisation' => 'nullable',
            'motif' => 'nullable|string',
            'observations' => 'nullable|string',
            'quantite_retournable' => 'nullable|integer|min:0',
            'quantite_perdue' => 'nullable|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $produit = Produit::findOrFail($request->produit_id);

            if (!$produit->is_reusable) {
                return back()->with('error', 'Ce produit n\'est pas réutilisable');
            }

            // Check stock availability
            $disponible = $produit->qte_stock - $produit->qte_en_utilisation - $produit->qte_en_sterilisation;
            if ($request->quantite > $disponible) {
                return back()->with('error', "Stock insuffisant. Disponible: $disponible");
            }

            // Create usage record
            $usage = ProductUsage::create([
                'produit_id' => $request->produit_id,
                'quantite' => $request->quantite,
                'patient_id' => $request->patient_id,
                'type_utilisation' => $request->type_utilisation,
                'service' => $request->service,
                'medecin_id' => $request->medecin_id,
                'infirmier_id' => $request->infirmier_id,
                'date_utilisation' => $request->date_utilisation,
                'heure_utilisation' => $request->heure_utilisation,
                'motif' => $request->motif,
                'observations' => $request->observations,
                'quantite_retournable' => $request->quantite_retournable ?? $request->quantite,
                'quantite_perdue' => $request->quantite_perdue ?? 0,
                'statut_retour' => 'en_attente',
                'enregistre_par' => Auth::id()
            ]);

            // Update product quantities
            $produit->qte_en_utilisation += $request->quantite;
            $produit->save();

            // Create stock transaction
            StockTransaction::createTransaction([
                'produit_id' => $produit->id,
                'user_id' => Auth::id(),
                'type_transaction' => 'sortie_utilisation',
                'quantite' => -$request->quantite, // Negative for exit
                'reference_type' => 'ProductUsage',
                'reference_id' => $usage->id,
                'numero_document' => 'USAGE-' . $usage->id,
                'motif' => 'Utilisation ' . $request->type_utilisation,
                'commentaire' => $request->observations,
                'date_transaction' => $request->date_utilisation
            ]);

            DB::commit();

            return redirect()
                ->route('reusable-products.usages.pending')
                ->with('success', 'Utilisation enregistrée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * View pending usages (products waiting to be collected)
     */
    public function pendingUsages()
    {
        if (!in_array(Auth::user()->role_id, [1, 4, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $pendingUsages = ProductUsage::with(['produit', 'patient', 'medecin', 'enregistrePar'])
                                     ->enAttente()
                                     ->retournable()
                                     ->latest()
                                     ->paginate(20);

        return view('admin.reusable_products.pending_usages', compact('pendingUsages'));
    }

    /**
     * Collect used products for sterilization
     */
    public function collectForSterilization(Request $request, $usageId)
    {
        // Pharmacien, Infirmière, Admin
        if (!in_array(Auth::user()->role_id, [1, 4, 7])) {
            abort(403, 'Accès non autorisé');
        }

        DB::beginTransaction();
        try {
            $usage = ProductUsage::findOrFail($usageId);

            if (!$usage->canBeCollected()) {
                return back()->with('error', 'Cet usage ne peut pas être collecté');
            }

            // Update usage status
            $usage->update([
                'statut_retour' => 'collecte',
                'collecte_par' => Auth::id(),
                'collecte_at' => now()
            ]);

            // Update product quantities
            $produit = $usage->produit;
            $produit->qte_en_utilisation -= $usage->quantite_retournable;
            $produit->qte_en_sterilisation += $usage->quantite_retournable;
            $produit->save();

            DB::commit();

            return back()->with('success', 'Produit collecté pour stérilisation');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Create sterilization batch
     */
    public function createSterilization(Request $request)
    {
        // Pharmacien, Infirmière spécialisée, Admin
        if (!in_array(Auth::user()->role_id, [1, 4, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'methode_sterilisation' => 'required|in:autoclave,chaleur_seche,gaz_eto,plasma,chimique,autre',
            'date_sterilisation' => 'required|date',
            'heure_debut' => 'nullable',
            'temperature' => 'nullable|integer',
            'duree_minutes' => 'nullable|integer',
            'type_indicateur' => 'nullable|string',
            'observations' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $produit = Produit::findOrFail($request->produit_id);

            // Check if enough in sterilization queue
            if ($request->quantite > $produit->qte_en_sterilisation) {
                return back()->with('error', 'Quantité insuffisante en attente de stérilisation');
            }

            // Create sterilization record
            $sterilization = ProductSterilization::create([
                'produit_id' => $request->produit_id,
                'quantite' => $request->quantite,
                'numero_lot' => ProductSterilization::generateNumeroLot(),
                'methode_sterilisation' => $request->methode_sterilisation,
                'date_sterilisation' => $request->date_sterilisation,
                'heure_debut' => $request->heure_debut,
                'temperature' => $request->temperature,
                'duree_minutes' => $request->duree_minutes,
                'sterilise_par' => Auth::id(),
                'type_indicateur' => $request->type_indicateur,
                'observations' => $request->observations,
                'statut' => 'en_cours'
            ]);

            DB::commit();

            return redirect()
                ->route('reusable-products.sterilizations.show', $sterilization->id)
                ->with('success', 'Stérilisation lancée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Complete sterilization (mark as finished)
     */
    public function completeSterilization(Request $request, $id)
    {
        if (!in_array(Auth::user()->role_id, [1, 4, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'heure_fin' => 'nullable',
            'resultat_test' => 'required|in:conforme,non_conforme,en_attente',
            'observations' => 'nullable|string'
        ]);

        $sterilization = ProductSterilization::findOrFail($id);

        if (!$sterilization->isEnCours()) {
            return back()->with('error', 'Cette stérilisation n\'est plus en cours');
        }

        $sterilization->update([
            'heure_fin' => $request->heure_fin,
            'resultat_test' => $request->resultat_test,
            'observations' => $request->observations,
            'statut' => 'termine_en_attente'
        ]);

        return back()->with('success', 'Stérilisation terminée. En attente de validation');
    }

    /**
     * Validate sterilization (Pharmacien validates quality)
     */
    public function validateSterilization(Request $request, $id)
    {
        // Only Pharmacien or Admin can validate
        if (!in_array(Auth::user()->role_id, [1, 7])) {
            abort(403, 'Seul le pharmacien peut valider la stérilisation');
        }

        $request->validate([
            'action' => 'required|in:valider,rejeter',
            'raison_rejet' => 'required_if:action,rejeter'
        ]);

        DB::beginTransaction();
        try {
            $sterilization = ProductSterilization::findOrFail($id);

            if (!$sterilization->isTermine()) {
                return back()->with('error', 'Cette stérilisation ne peut pas être validée');
            }

            if ($request->action === 'valider') {
                $sterilization->update([
                    'statut' => 'valide',
                    'verifie_par' => Auth::id()
                ]);

                DB::commit();
                return back()->with('success', 'Stérilisation validée. Prêt pour retour au stock');

            } else {
                // Rejected
                $sterilization->update([
                    'statut' => 'rejete',
                    'verifie_par' => Auth::id(),
                    'raison_rejet' => $request->raison_rejet
                ]);

                // Return quantity back to stock (products lost/damaged)
                $produit = $sterilization->produit;
                $produit->qte_en_sterilisation -= $sterilization->quantite;
                // Don't add back to stock - it's lost
                $produit->save();

                // Create transaction for loss
                StockTransaction::createTransaction([
                    'produit_id' => $produit->id,
                    'user_id' => Auth::id(),
                    'type_transaction' => 'perte',
                    'quantite' => -$sterilization->quantite,
                    'reference_type' => 'ProductSterilization',
                    'reference_id' => $sterilization->id,
                    'numero_document' => $sterilization->numero_lot,
                    'motif' => 'Stérilisation échouée',
                    'commentaire' => $request->raison_rejet,
                    'date_transaction' => now()
                ]);

                DB::commit();
                return back()->with('warning', 'Stérilisation rejetée. Produits marqués comme perdus');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Return sterilized products to stock
     */
    public function returnToStock($id)
    {
        // Pharmacien or Admin
        if (!in_array(Auth::user()->role_id, [1, 7])) {
            abort(403, 'Seul le pharmacien peut retourner au stock');
        }

        DB::beginTransaction();
        try {
            $sterilization = ProductSterilization::findOrFail($id);

            if (!$sterilization->canBeReturned()) {
                return back()->with('error', 'Cette stérilisation ne peut pas être retournée');
            }

            $produit = $sterilization->produit;

            // Update sterilization status
            $sterilization->update([
                'statut' => 'retourne',
                'retourne_par' => Auth::id(),
                'retourne_at' => now()
            ]);

            // Update product quantities
            $produit->qte_en_sterilisation -= $sterilization->quantite;
            $produit->qte_stock += $sterilization->quantite; // Add back to stock!
            $produit->save();

            // Create stock transaction for return
            StockTransaction::createTransaction([
                'produit_id' => $produit->id,
                'user_id' => Auth::id(),
                'type_transaction' => 'retour_reutilisable',
                'quantite' => $sterilization->quantite, // Positive - adding to stock
                'reference_type' => 'ProductSterilization',
                'reference_id' => $sterilization->id,
                'numero_document' => $sterilization->numero_lot,
                'motif' => 'Retour après stérilisation',
                'commentaire' => 'Produit réutilisable stérilisé et retourné au stock',
                'date_transaction' => now()
            ]);

            DB::commit();

            return back()->with('success', 'Produit retourné au stock avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * View all sterilizations
     */
    public function sterilizations(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 4, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $query = ProductSterilization::with(['produit', 'sterilisePar', 'verifiePar']);

        // Filter by status
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        // Filter by date
        if ($request->has('date_debut')) {
            $query->where('date_sterilisation', '>=', $request->date_debut);
        }
        if ($request->has('date_fin')) {
            $query->where('date_sterilisation', '<=', $request->date_fin);
        }

        $sterilizations = $query->latest('date_sterilisation')->paginate(20);

        return view('admin.reusable_products.sterilizations', compact('sterilizations'));
    }

    /**
     * Show sterilization details
     */
    public function showSterilization($id)
    {
        if (!in_array(Auth::user()->role_id, [1, 4, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $sterilization = ProductSterilization::with([
            'produit',
            'sterilisePar',
            'verifiePar',
            'retournePar'
        ])->findOrFail($id);

        return view('admin.reusable_products.sterilization_show', compact('sterilization'));
    }

    /**
     * Generate sterilization certificate PDF
     */
    public function sterilizationCertificate($id)
    {
        if (!in_array(Auth::user()->role_id, [1, 4, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $sterilization = ProductSterilization::with([
            'produit',
            'sterilisePar',
            'verifiePar'
        ])->findOrFail($id);

        $filename = "certificat_sterilisation_{$sterilization->numero_lot}.pdf";

        return PdfService::generate(
            'admin.reusable_products.certificate_pdf',
            compact('sterilization'),
            $filename,
            'portrait',
            'A4',
            'download'
        );
    }





    public function recordUsageForm()
    {
        // Get all products ordered: reusable first, then non-reusable
        $products = Produit::orderByRaw('is_reusable DESC, designation ASC')->get();
        
        // Get patients for selection
        $patients = Patient::orderBy('name')->get();
        
        // Get medecins (doctors) - role_id 2
        $medecins = User::where('role_id', 2)->orderBy('name')->get();
        
        // Get infirmiers (nurses) - role_id 4
        $infirmiers = User::where('role_id', 4)->orderBy('name')->get();
        
        return view('admin.reusable_products.record_usage_form', compact('products', 'patients', 'medecins', 'infirmiers'));
    }

    public function createSterilizationForm()
    {
        $products = Produit::where('is_reusable', true)
                        ->where('qte_en_sterilisation', '>', 0)
                        ->get();
        return view('admin.reusable_products.create_sterilization', compact('products'));
    }



}