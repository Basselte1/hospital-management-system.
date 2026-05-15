<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Chambre;
use App\Models\FactureChambre;
use App\Services\PdfService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChambresController extends Controller
{

    public function index()
    {
        $query = Chambre::query();

        if (request()->has('categorie')) {
            $query->where('categorie', request('categorie'));
        }

        if (request()->has('order')) {
            $query->orderBy('id', request('order'));
        }

        // Use select to reduce data retrieval
        $chambres = $query->select('id', 'numero', 'categorie', 'prix', 'statut', 'patient', 'jour')
            ->paginate(50)
            ->appends([
                'categorie' => request('categorie'),
                'order' => request('order'),
            ]);

        return view('admin.chambres.index', compact('chambres'));
    }

    public function create()
    {
        return view('admin.chambres.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'numero'=>'required|integer',
            'categorie'=> 'required|string',
            'prix'=>'required|integer'
        ]);

        $chambre = new Chambre();

            $chambre->numero = $request->get('numero');
            $chambre->categorie = $request->get('categorie');
            $chambre->prix = $request->get('prix');
            $chambre->user_id = Auth::id();
            $chambre->save();
        return  redirect()->route('chambres.index')->with('success', 'chambre ajoutée avec succes');
    }


    public function attribute($id)
    {
        $chambre = Chambre::select(['id', 'numero', 'categorie', 'prix', 'statut'])
            ->findOrFail($id);
        
        // Cache patient list
        $patients = Cache::remember('patients_for_chambers', 600, function () {
            return Patient::select(['id', 'name', 'prenom', 'numero_dossier'])
                ->latest()
                ->get();
        });

        return view('admin.chambres.attribute', compact('chambre', 'patients'));
    }


    public function edit($id)
    {
        $chambre = Chambre::findOrFail($id);

        return view('admin.chambres.edit', compact('chambre'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'numero'=> ['required'],
            'categorie'=> ['required'],
            'prix'=> ['required', 'integer', 'numeric'],

        ]);

        $chambre = Chambre::findOrFail($id);

        $chambre->numero = $request->get('numero');
        $chambre->categorie = $request->get('categorie');
        $chambre->prix = $request->get('prix');

        $chambre->save();

        return redirect()->route('chambres.index')->with('success', 'La mise à jour a bien été éffectuer');
    }

  
    public function updateStatus(Request $request, Chambre $chambre)
    {
        $chambre->update($request->only(['patient', 'statut', 'jour']));
        
        // Clear cache
        Cache::forget('chambres_list');

        return redirect()->route('chambres.index')
            ->with('success', 'La chambre a bien été attribuée');
    }

    public function updateMinus(Request $request, Chambre $chambre, Patient $patient)
    {
        $chambre->update($request->only(
            [
                'patient',
                'statut',
                'jour'
            ]));

        return redirect()->route('chambres.index')->with('success', 'La chambre a bien été liberer');
    }

    public function generateFacture(Request $request, $id)
    {
        $this->authorize('generateFacture', Chambre::class);

        try {
            $chambre = Chambre::findOrFail($id);

            // Validation : patient_id obligatoire
            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'date_entre' => 'nullable|date',
                'date_sortie' => 'nullable|date|after_or_equal:date_entre',
            ]);

            $patient = Patient::findOrFail($request->patient_id);

            // Générer le numéro de facture via le service
            $numero = app(\App\Services\FactureService::class)
                ->genererNumero('CHB', 'facture_chambres');

            // Calculer le montant de l'hébergement
            $nbJours = 1;
            if ($request->date_entre && $request->date_sortie) {
                $nbJours = max(1, \Carbon\Carbon::parse($request->date_entre)
                    ->diffInDays(\Carbon\Carbon::parse($request->date_sortie)));
            }
            $montantHebergement = $chambre->prix * $nbJours;

            // Créer la facture
            $facture = FactureChambre::create([
                'patient_id'      => $patient->id,
                'chambre_id'      => $chambre->id,
                'user_id'         => Auth::id(),
                'numero'          => $numero,
                'date_entre'      => $request->date_entre,
                'date_sortie'     => $request->date_sortie,
                'montant_total'   => $montantHebergement,
                'montant_hebergement' => $montantHebergement,
                'statut'          => 'Non soldée',
            ]);

            // Créer la ligne d'hébergement
            \App\Models\FactureLigne::create([
                'facture_chambre_id' => $facture->id,
                'facture_type'       => 'chambre',
                'type_sous'          => 'hebergement',
                'libelle'            => "Séjour chambre {$chambre->categorie} n°{$chambre->numero} ({$nbJours} jour(s))",
                'montant'            => $chambre->prix,
                'quantite'           => $nbJours,
                'ordre'              => 1,
            ]);

            return redirect()->route('facturation.chambres.show', $facture)
                            ->with('success', 'La facture de chambre n°' . $numero . ' a été générée.');

        } catch (\Exception $e) {
            Log::error('Erreur génération facture chambre', [
                'chambre_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()
                             ->with('error', 'Erreur lors de la génération: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {

        set_time_limit(120);
        ini_set('memory_limit', '256M');

        try {
            $chambre = FactureChambre::with([
                'patient: id, name, prenom,numero_dossier',
                'user: id , name, prenom'
            ])->findOrFail($id);


            Log::info('export pdf facture chambre',[
                'chambre_id'    =>  $chambre->id,
                'reste'         =>  $chambre->reste,
            ]);

            if (!$chambre->isImprimable()) {
                return redirect()
                    ->back()
                    ->with('error', 'Impression interdite : Seules les factures soldées peuvent être imprimées. Reste à payer: ' . number_format($chambre->reste, 0, ',', ' ') . ' FCFA');
            }

            $patientdata = $chambre->patient->toArray();
            $patientdata['user'] = $chambre->user ? $chambre->user->toArray() : null;

            return PdfService::generate(
                'admin.etats.facturechambre',
                [
                'chambre'  => $chambre->toArray(),
                'patient'  => $patientdata,

                ],
                "facture_chambre_{$chambre->numero}.pdf"
            );


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                             ->with('error', 'la facture n°{$id} est introuvable.');
        }catch (\Exception $e) {
             Log::error('Erreur de génération PDF', [
                'chambre_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Erreur lors de la generation du PDF :  ' .$e->getMessage());
        }
    }

}





