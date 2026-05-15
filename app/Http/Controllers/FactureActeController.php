<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFactureActeRequest;
use App\Models\FactureActe;
use App\Models\FactureLigne;
use App\Services\FactureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\HistoriqueFacture;
use App\Models\User;
use App\Models\Patient;

/**
 * FactureActeController
 *
 * Gère les factures d'actes médicaux lourds (chimio, dialyse, petite chirurgie…).
 * Même structure que FactureExamenController — intentionnellement similaire.
 */
class FactureActeController extends Controller
{
    public function __construct(
        private readonly FactureService $factureService
    ) {}

 /*public function index(Request $request): View
{
    $startDate = $request->filled('start-date')
        ? \Carbon\Carbon::parse($request->input('start-date'))->startOfDay()
        : \Carbon\Carbon::now()->startOfDay();

    $endDate = $request->filled('end-date')
        ? \Carbon\Carbon::parse($request->input('end-date'))->endOfDay()
        : \Carbon\Carbon::now()->endOfDay();

    $factureSoins = \App\Models\FactureConsultation::with(['patient', 'lignes'])
        ->where(function ($query) {
            // Couvre : 'acte', 'Acte', 'soin_infirmier', 'Soin', 'soin'...
            $query->whereRaw("LOWER(motif) IN ('acte', 'soin_infirmier', 'soin')")
                  ->orWhereHas('lignes', function ($q) {
                      $q->where('type_acte', 'soin_infirmier');
                  });
        })
        ->when($request->filled('infirmier'), function ($q) use ($request) {
            // Filtre sur l'infirmière enregistrée dans les lignes
            $q->whereHas('lignes', function ($q2) use ($request) {
                $q2->where('infirmiere', $request->infirmier);
            });
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->latest()
        ->paginate(20);

    $infirmiers = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'Infirmier'))
        ->select('id', 'name', 'prenom')->get();

    $lists = collect();
    $d = \Carbon\Carbon::now()->subDays(90);
    while ($d <= \Carbon\Carbon::now()) {
        $lists->push($d->format('Y-m-d'));
        $d->addDay();
    }
    $lists = $lists->reverse()->values();

    return view('admin.factures.facture_actes', compact(
        'factureSoins', 'startDate', 'endDate', 'users', 'lists'
    ));
}*/


    public function index(Request $request): View
    {
        $startDate = $request->filled('start-date')
            ? \Carbon\Carbon::parse($request->input('start-date'))->startOfDay()
            : \Carbon\Carbon::now()->startOfDay();

        $endDate = $request->filled('end-date')
            ? \Carbon\Carbon::parse($request->input('end-date'))->endOfDay()
            : \Carbon\Carbon::now()->endOfDay();

        
        $factureSoins = FactureActe::with(['patient', 'lignes'])
            ->when($request->filled('patient_id'), 
            function ($q) use ($request) {
                
                $q->where('patient_id', $request->integer('patient_id'));
            })
            ->when($request->filled('infirmier'), function ($q) use ($request) {
                $q->whereHas('lignes', function ($q2) use ($request) {
                    $q2->where('infirmiere', $request->infirmier);
                });
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(20);
////////////////////////////////////////////////////////////////////////////////////////////////
        $users = User::whereIn('role_id',[2,4])
            ->select('id', 'name', 'prenom', 'specialite', 'role_id')->orderBy('specialite')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($user) {
                return $user->role_id == 4 ? 'infirmier' : $user->specialite;
            });
/////////////////////////////////////////////////////////////////////////
        $lists = collect();
        $d = \Carbon\Carbon::now()->subDays(90);
        while ($d <= \Carbon\Carbon::now()) {
            $lists->push($d->format('Y-m-d'));
            $d->addDay();
        }
        $lists = $lists->reverse()->values();

        
        $patientSelectionne = $request->filled('patient_id')
            ? Patient::select('id', 'name', 'prenom', 'numero_dossier')
                                ->find($request->integer('patient_id'))
            : null;

        $medecinInfirmier = $request->filled('patient_id')
            ? FactureLigne::select('medecin_r', ' infirmiere')
                                ->find($request->integer('patient_id'))
            : null;


        return view('admin.factures.facture_actes', compact(
            'factureSoins', 'startDate', 'endDate', 'users', 'lists', 'patientSelectionne', 'medecinInfirmier'
        ));
    }

    /*public function create(Request $request): View
    {
        return view('facturation.actes.create', [
            'patient_id'      => $request->integer('patient_id'),
            'consultation_id' => $request->integer('consultation_id'),
        ]);
    }

    public function store(StoreFactureActeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $montants = $this->factureService->preparerMontants(
                montantTotal:   (float) $data['montant_total'],
                priseEnCharge:  (float) ($data['prise_en_charge'] ?? 0),
                avance:         (float) ($data['avance'] ?? 0),
            );

            $facture = FactureActe::create(array_merge($data, $montants, [
                'numero' => $this->factureService->genererNumero('ACT', 'factures_acte'),
            ]));

            foreach ($data['lignes'] ?? [] as $index => $ligne) {
                \App\Models\FactureLigne::create(array_merge($ligne, [
                    'facture_acte_id' => $facture->id,
                    'facture_type'    => 'acte',   // ← indispensable pour le discriminant
                    'ordre'           => $index + 1,
                ]));
            }
        });

        return redirect()->route('facturation.actes.index')
                         ->with('success', 'Facture acte créée avec succès.');
    }*/

    //Afficher les details de  la facture

    public function show(FactureActe $factureActe): View
    {

        $factureActe->load(['patient', 'lignes', 'user', 'printer']);

        return view('admin.factures.partials.apercu_acte', [
            'facture' => $factureActe ,
            'isProforma' => $factureActe->isProforma(),
            ]);
    }

    public function enregistrerPaiement(Request $request, FactureActe $factureActe): RedirectResponse
    {
        $validated = $request->validate([
            'montant'                => 'required|numeric|min:1|max:' . $factureActe->reste,
            'mode_paiement'          => 'required|string|max:50',
            'mode_paiement_info_sup' => 'nullable|string|max:255',
            'reference'              => 'nullable|string|max:100',
        ]);

        $this->factureService->enregistrerPaiement(
            facture:      $factureActe,
            factureType:  'facture_acte',
            montant:      (float) $validated['montant'],
            modePaiement: $validated['mode_paiement'],
            extra:        ['mode_paiement_info_sup' => $validated['mode_paiement_info_sup'] ?? null,
                           'reference'              => $validated['reference'] ?? null],
        );

        return back()->with('success', 'Paiement enregistré.');
    }

    public function getPatientSoins(Request $request)
    {
        $request->validate(['patient_id' => 'required|integer|exists:patients,id']);
 
        $observations = \App\Models\ObservationMedicale::where('patient_id', $request->patient_id)
            ->with('user:id,name,prenom')
            ->select(['id', 'patient_id', 'user_id', 'observation', 'date'])
            ->latest('date')
            ->take(20)
            ->get()
            ->map(function ($obs) {
                // L'utilisateur lié à ObservationMedicale est l'infirmier(ère) qui a fait les soins
                $infirmiere = $obs->user
                    ? trim($obs->user->name . ' ' . ($obs->user->prenom ?? ''))
                    : '';
 
                // "nom" = ce qui s'affiche dans le <select> du modal
                $nomCourt = mb_strlen($obs->observation) > 80
                    ? mb_substr($obs->observation, 0, 80) . '…'
                    : $obs->observation;
 
                return [
                    'id'         => $obs->id,
                    'nom'        => $nomCourt,           // affiché dans le <select>
                    'detail'     => $obs->date,          // date affichée entre parenthèses
                    'libelle'    => $obs->observation,   // pré-remplit "Description"
                    'medecin'    => '',                  // pas de médecin prescripteur pour les soins
                    'infirmiere' => $infirmiere,         // ← NOM de l'infirmière qui a fait les soins
                ];
            });
 
        return response()->json($observations);
    }


     /**
     * Met à jour une facture de consultation
     * Bloque la modification si la facture est soldée
     */
    public function FactureActeUpdate(Request $request, $id)
    {
        // Récupérer la facture avec la relation patient
        $facture = FactureActe::with('patient:id,prise_en_charge')->findOrFail($id);

        $this->authorize('update',$facture);
      
       if ($facture->isSoldee() && $facture->is_printed) {
            return redirect()
                ->back()
                ->with('error', 'Modification interdite : La facture n°' . $facture->numero . ' est déjà soldée et imprimée.');
        }

        // Vérification des autorisations
        $this->authorize('update', $facture);

        // Validation des champs
        $request->validate([
            'mode_paiement' => 'required',
            'num_cheque' => 'required_if:mode_paiement,chèque',
            'emetteur_cheque' => 'required_if:mode_paiement,chèque',
            'banque_cheque' => 'required_if:mode_paiement,chèque',
            'emetteur_bpc' => 'required_if:mode_paiement,bon de prise en charge',
            'reste' => 'required|numeric|min:0',
            'percu' => 'required|numeric|min:0',
            'montant' => 'required|numeric|min:0',
            'devise'         => 'nullable|string|in:XAF,EUR,DOLLAR,GBP',
            'taux_conversion'=> 'nullable|numeric|min:0',
            'montant_devise' => 'nullable|numeric|min:0',
        ]);

        // Validation personnalisée: le montant perçu ne peut pas dépasser le reste
        if ($request->input('percu') > $request->input('reste')) {
            return redirect()->back()
                ->withErrors(['percu' => 'Le montant perçu ne peut pas dépasser le reste à payer.'])
                ->withInput();
        }

        // Préparer les informations supplémentaires du mode de paiement
        $modePaiementInfo = $this->prepareModePaiementInfo($request);

        try {
            DB::transaction(function () use ($facture, $request, $modePaiementInfo) {
                // Créer un enregistrement d'historique
                $historiqueFacture = new HistoriqueFacture([
                    'reste' => $facture->reste - $request->input('percu'),
                    'montant' => $facture->montant,
                    'percu' => $request->input('percu'),
                    'assurec' => $facture->assurec,
                    'mode_paiement' => $request->input('mode_paiement'),
                    'mode_paiement_info_sup' => $modePaiementInfo,
                    'devise'               => $request->input('devise', $facture->devise ?? 'XAF'),
                    'taux_conversion'      => $request->input('taux_conversion', $facture->taux_conversion),
                    'montant_devise'       => $request->input('montant_devise', $facture->montant_devise),
                ]);

                // Mettre à jour la facture
                $facture->montant = $request->input('montant');
                $facture->mode_paiement = $request->input('mode_paiement');
                $facture->mode_paiement_info_sup = $modePaiementInfo;
                
                // Recalculer les parts assurance et patient
                $priseEnCharge = $facture->patient->prise_en_charge ?? 0;

               $montants = $this->factureService->preparerMontants(
                    montantTotal:  (float) $request->input('montant'),
                    priseEnCharge: (float) ($priseEnCharge),
                    avance:        (float) $request->input('percu'),
                );
                $facture->fill($montants);
                
                // Le statut sera mis à jour automatiquement par l'événement du modèle
                
                // Sauvegarder la facture - ceci déclenchera automatiquement syncPatientData()
                $facture->save();

                // Sauvegarder l'historique
                $facture->historiques()->save($historiqueFacture);
                
                // Journaliser la mise à jour pour l'audit
                Log::info('Facture acte mise à jour', [
                    'facture_id' => $facture->id,
                    'patient_id' => $facture->patient_id,
                    'montant' => $facture->montant,
                    'avance' => $facture->avance,
                    'reste' => $facture->reste,
                    'devise' => $facture->devise,
                    'statut' => $facture->statut,
                    'updated_by' => Auth::id()
                ]);
            });

            // Vider le cache
            Cache::tags(['factures'])->flush();

            $message = $facture->isSoldee() 
                ? 'Facture soldée avec succès ! Elle peut maintenant être imprimée.'
                : 'Facture mise à jour avec succès. Reste à payer: ' . number_format($facture->reste, 0, ',', ' ') . ' FCFA';

            return redirect()
                ->route('facturation.actes.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Échec de la mise à jour de la facture', [
                'facture_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la mise à jour de la facture: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function imprimer(FactureActe $factureActe): View|RedirectResponse
    {
        if (! $factureActe->isImprimable()) {
            return back()->with('error', 'La facture doit être soldée avant impression.');
        }

        $factureActe->marquerCommeImprimee();
        $factureActe->load(['patient', 'lignes', 'user']);

        return view('facturation.actes.print', ['facture' => $factureActe]);
    }

        public function export_bilan_acte(\Illuminate\Http\Request $request)
    {
        $this->authorize('view', \App\Models\User::class);
    
        $request->validate([
            'start-date' => 'required|date',
            'end-date'   => 'required|date|after_or_equal:start-date',
        ]);
    
        $startDate = \Carbon\Carbon::parse($request->input('start-date'))->startOfDay();
        $endDate   = \Carbon\Carbon::parse($request->input('end-date'))->endOfDay();
    
        $factures = \App\Models\FactureActe::with(['patient:id,name,prenom,numero_dossier', 'lignes'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();
    
        $totaux = [
            'montant'    => $factures->sum('montant_total'),
            'avance'     => $factures->sum('avance'),
            'reste'      => $factures->sum('reste'),
            'assurancec' => $factures->sum('assurancec'),
        ];
    
        $pdf = \PDF::loadView('admin.factures.bilan_actes_pdf', compact(
            'factures', 'totaux', 'startDate', 'endDate'
        ));
    
        return $pdf->download('bilan_actes_' . $startDate->format('d-m-Y') . '_' . $endDate->format('d-m-Y') . '.pdf');
    }

    public function exportPdf(FactureActe $factureActe)
    {
        $this->authorize('view', $factureActe);
        
        if (! $factureActe->isImprimable()) {
            return back()->with('error', 'Facture non soldée.');
        }
        
        $factureActe->load(['patient', 'lignes', 'user']);
        $factureActe->marquerCommeImprimee();
        
       $pdf = \PDF::loadView('admin.etats.acte', ['facture' => $factureActe]);
        return $pdf->download('facture_acte_' . $factureActe->numero . '.pdf');
    }

    public function destroy($id)
    {
        $this->authorize('view', \App\Models\User::class);
        $facture = \App\Models\FactureActe::findOrFail($id);
    
        if ($facture->isSoldee()) {
            return redirect()->back()
                ->with('error', 'Suppression interdite : La facture n°' . $facture->numero . ' est soldée.');
        }
    
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($facture) {
                $facture->delete();
            });
    
            return redirect()->route('facturation.actes.index')
                ->with('success', 'La facture n°' . $id . ' a été supprimée avec succès');
    
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la facture');
        }
    }
       /**
     * Normalise le mode de paiement pour le regroupement
     */
    private function getModePaiementKey($modePaiement): string
    {
        $normalizedMap = [
            'espèce' => 'espece',
            'chèque' => 'cheque',
            'orange money' => 'om',
            'mtn mobile money' => 'momo',
            'virement' => 'virement',
            'bon de prise en charge' => 'bondepriseencharge'
        ];

        return $normalizedMap[strtolower($modePaiement)] ?? 'autre';
    }

     /**
     * Prépare les informations supplémentaires du mode de paiement
     */
    private function prepareModePaiementInfo(Request $request): string
    {
        if ($request->input('mode_paiement') === 'chèque') {
            return collect([
                $request->input('num_cheque'),
                $request->input('emetteur_cheque'),
                $request->input('banque_cheque')
            ])->filter()->implode(' // ');
        }
        
        if ($request->input('mode_paiement') === 'bon de prise en charge') {
            return $request->input('emetteur_bpc') ?? '';
        }
        
        return '';
    }

}