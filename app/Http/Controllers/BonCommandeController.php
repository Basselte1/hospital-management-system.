<?php

namespace App\Http\Controllers;

use App\Models\BonCommande;
use App\Models\BonCommandeItem;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PdfService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BonCommandeController extends Controller
{
    // -------------------------------------------------------------------------
    // Role groups — edit here once, applies everywhere
    // -------------------------------------------------------------------------

    /** Can view the list, details, download PDF */
    private $viewRoles = [1, 3, 5];      // Admin, Gestionnaire, Logistique

    /** Can create, edit, delete, submit for validation */
    private $createRoles = [1, 5];        // Admin, Logistique

    /** Can send the email to the fournisseur once validated */
    private $sendRoles = [1, 3, 5];       // Admin, Gestionnaire, Logistique

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    public function index()
    {
        if (!in_array(Auth::user()->role_id, $this->viewRoles)) {
            abort(403, 'Accès non autorisé');
        }

        $bonCommandes = BonCommande::with(['items', 'createdBy', 'validatedBy'])
                                   ->latest()
                                   ->paginate(20);

        return view('admin.bon_commandes.index', compact('bonCommandes'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, $this->createRoles)) {
            abort(403, 'Seul le logistique (Gestionaire Magasin) peut créer des bons de commande');
        }

        $produits = Produit::orderBy('designation')->get();

        return view('admin.bon_commandes.create', compact('produits'));
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, $this->createRoles)) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'fournisseur_nom'            => 'required|string|max:255',
            'fournisseur_email'          => 'nullable|email',
            'fournisseur_telephone'      => 'nullable|string',
            'fournisseur_adresse'        => 'nullable|string',
            'date_commande'              => 'required|date',
            'date_livraison_souhaitee'   => 'nullable|date|after_or_equal:date_commande',
            'notes'                      => 'nullable|string',
            'items'                      => 'required|array|min:1',
            'items.*.produit_id'         => 'nullable|exists:produits,id',
            'items.*.designation'        => 'required|string',
            'items.*.categorie'          => 'required|string',
            'items.*.quantite'           => 'required|integer|min:1',
            'items.*.prix_unitaire'      => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $bonCommande = BonCommande::create([
                'numero_bon'               => BonCommande::generateNumeroBon(),
                'created_by'               => Auth::id(),
                'statut'                   => 'brouillon',
                'fournisseur_nom'          => $request->fournisseur_nom,
                'fournisseur_email'        => $request->fournisseur_email,
                'fournisseur_telephone'    => $request->fournisseur_telephone,
                'fournisseur_adresse'      => $request->fournisseur_adresse,
                'date_commande'            => $request->date_commande,
                'date_livraison_souhaitee' => $request->date_livraison_souhaitee,
                'notes'                    => $request->notes,
            ]);

            foreach ($request->items as $item) {
                BonCommandeItem::create([
                    'bon_commande_id'    => $bonCommande->id,
                    'produit_id'         => $item['produit_id'] ?? null,
                    'designation'        => $item['designation'],
                    'categorie'          => $item['categorie'],
                    'quantite_commandee' => $item['quantite'],
                    'prix_unitaire'      => $item['prix_unitaire'],
                    'notes'              => $item['notes'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('bon-commandes.show', $bonCommande->id)
                ->with('success', 'Bon de commande créé avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BC creation failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Show details – accessible by Admin, Gestionnaire AND Logistique
     */
    public function show($id)
    {
        if (!in_array(Auth::user()->role_id, $this->viewRoles)) {
            abort(403, 'Accès non autorisé');
        }

        $bonCommande = BonCommande::with(['items.produit', 'createdBy', 'validatedBy', 'receivedBy'])
                                  ->findOrFail($id);

        return view('admin.bon_commandes.show', compact('bonCommande'));
    }

    public function edit($id)
    {
        if (!in_array(Auth::user()->role_id, $this->createRoles)) {
            abort(403, 'Accès non autorisé');
        }

        $bonCommande = BonCommande::with('items')->findOrFail($id);

        if (!$bonCommande->canBeEdited()) {
            return back()->with('error', 'Ce bon de commande ne peut plus être modifié');
        }

        $produits = Produit::orderBy('designation')->get();

        return view('admin.bon_commandes.edit', compact('bonCommande', 'produits'));
    }

    public function update(Request $request, $id)
    {
        if (!in_array(Auth::user()->role_id, $this->createRoles)) {
            abort(403, 'Accès non autorisé');
        }

        $bonCommande = BonCommande::findOrFail($id);

        if (!$bonCommande->canBeEdited()) {
            return back()->with('error', 'Ce bon de commande ne peut plus être modifié');
        }

        $request->validate([
            'fournisseur_nom'          => 'required|string|max:255',
            'fournisseur_email'        => 'nullable|email',
            'fournisseur_telephone'    => 'nullable|string',
            'fournisseur_adresse'      => 'nullable|string',
            'date_commande'            => 'required|date',
            'date_livraison_souhaitee' => 'nullable|date|after_or_equal:date_commande',
            'notes'                    => 'nullable|string',
            'items'                    => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $bonCommande->update([
                'fournisseur_nom'          => $request->fournisseur_nom,
                'fournisseur_email'        => $request->fournisseur_email,
                'fournisseur_telephone'    => $request->fournisseur_telephone,
                'fournisseur_adresse'      => $request->fournisseur_adresse,
                'date_commande'            => $request->date_commande,
                'date_livraison_souhaitee' => $request->date_livraison_souhaitee,
                'notes'                    => $request->notes,
            ]);

            $bonCommande->items()->delete();

            foreach ($request->items as $item) {
                BonCommandeItem::create([
                    'bon_commande_id'    => $bonCommande->id,
                    'produit_id'         => $item['produit_id'] ?? null,
                    'designation'        => $item['designation'],
                    'categorie'          => $item['categorie'],
                    'quantite_commandee' => $item['quantite'],
                    'prix_unitaire'      => $item['prix_unitaire'],
                    'notes'              => $item['notes'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('bon-commandes.show', $bonCommande->id)
                ->with('success', 'Bon de commande modifié avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!in_array(Auth::user()->role_id, $this->createRoles)) {
            abort(403, 'Accès non autorisé');
        }

        $bonCommande = BonCommande::findOrFail($id);

        if (!$bonCommande->canBeEdited()) {
            return back()->with('error', 'Ce bon de commande ne peut pas être supprimé');
        }

        try {
            $bonCommande->delete();
            return redirect()->route('bon-commandes.index')
                ->with('success', 'Bon de commande supprimé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * STEP 2 — Submit for validation: brouillon → envoye
     */
    public function sendForValidation($id)
    {
        if (!in_array(Auth::user()->role_id, $this->createRoles)) {
            abort(403, 'Accès non autorisé');
        }

        $bonCommande = BonCommande::with('items')->findOrFail($id);

        if (!$bonCommande->isBrouillon()) {
            return back()->with('error', 'Seuls les bons de commande en brouillon peuvent être soumis pour validation.');
        }

        if ($bonCommande->items->isEmpty()) {
            return back()->with('error', 'Le bon de commande doit contenir au moins un article.');
        }

        DB::beginTransaction();
        try {
            $bonCommande->update(['statut' => 'envoye']);
            DB::commit();

            return back()->with('success', 'Bon de commande soumis pour validation au gestionnaire!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * STEP 3 — Send email to fournisseur after Gestionnaire has validated.
     *
     * FIXED VERSION: Email-only, NO PDF generation
     * 
     * Key changes:
     *  - Only works on statut = 'valide' or 'receptionne'
     *  - Does NOT change the statut
     *  - Does NOT generate PDF (user can download PDF separately)
     *  - Sends clean HTML email only
     *  - Allowed by Admin(1), Gestionnaire(3) and Logistique(5)
     */
    public function send($id)
    {
        if (!in_array(Auth::user()->role_id, $this->sendRoles)) {
            abort(403, 'Accès non autorisé');
        }

        $bonCommande = BonCommande::with('items')->findOrFail($id);

        // Validation checks
        if (!in_array($bonCommande->statut, ['valide', 'receptionne'])) {
            return back()->with('error',
                'Ce bon de commande doit d\'abord être validé par le gestionnaire avant d\'être envoyé au fournisseur.'
            );
        }

        if ($bonCommande->items->isEmpty()) {
            return back()->with('error', 'Le bon de commande ne contient aucun article.');
        }

        if (!$bonCommande->fournisseur_email) {
            return back()->with('error',
                'Aucune adresse email fournisseur renseignée. Veuillez modifier le bon de commande.'
            );
        }

        try {
            // Send email WITHOUT PDF attachment
            $this->sendEmailOnly($bonCommande);

            return back()->with('success',
                'Email envoyé avec succès à ' . $bonCommande->fournisseur_email . '!'
            );

        } catch (\Exception $e) {
            Log::error('BC email send failed', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // PDF
    // -------------------------------------------------------------------------

    public function generatePdf($id)
    {
        set_time_limit(90);
        ini_set('memory_limit', '512M');

        try {
            if (!in_array(Auth::user()->role_id, $this->viewRoles)) {
                abort(403, 'Accès non autorisé');
            }

            $bonCommande = BonCommande::with(['items', 'createdBy', 'validatedBy'])->findOrFail($id);

            if ($bonCommande->items->isEmpty()) {
                return back()->with('error', 'Le bon de commande ne contient aucun article');
            }

            if (ob_get_length()) {
                ob_end_clean();
            }

            $filename = 'bon_commande_' . $bonCommande->numero_bon . '.pdf';

            return PdfService::generate(
                'admin.bon_commandes.pdf',
                compact('bonCommande'),
                $filename,
                'portrait',
                'A4',
                'download'
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Bon de commande introuvable');

        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'id'      => $id,
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            if (ob_get_length()) {
                ob_end_clean();
            }

            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());

        } finally {
            gc_collect_cycles();
        }
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    /**
     * Send email to fournisseur - EMAIL ONLY, NO PDF
     * 
     * This is the FIXED version that sends only HTML email.
     * PDF can be downloaded separately using the generatePdf() method.
     * 
     * Benefits:
     * - No PDF generation errors blocking email sending
     * - Faster email delivery
     * - Cleaner separation of concerns
     * - User can download PDF anytime from the system
     */
    private function sendEmailOnly(BonCommande $bonCommande): void
    {
        // Ensure relations are loaded
        if (!$bonCommande->relationLoaded('items')) {
            $bonCommande->load(['items', 'createdBy']);
        }

        // Send the email (HTML only, no PDF attachment)
        Mail::send(
            'admin.bon_commandes.email',
            compact('bonCommande'),
            function ($message) use ($bonCommande) {
                $message->to($bonCommande->fournisseur_email)
                        ->subject('Bon de Commande ' . $bonCommande->numero_bon . ' — CMCU');
            }
        );

        Log::info('BC email sent successfully (HTML only, no PDF)', [
            'bon_commande_id' => $bonCommande->id,
            'numero_bon'      => $bonCommande->numero_bon,
            'to'              => $bonCommande->fournisseur_email,
        ]);
    }


}