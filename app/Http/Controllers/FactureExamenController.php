<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFactureExamenRequest;
use App\Models\FactureExamen;
use App\Models\FactureLigne;      
use App\Models\User;    
use App\Models\Patient;          
use App\Services\FactureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\HistoriqueFacture;
/**
 * FactureExamenController
 *
 * Gère le cycle de vie complet des factures d'examens (labo + imagerie).
 *
 * POURQUOI CE CONTROLLER EST SIMPLE ?
 *   Toute la logique métier (calculs, paiement, numérotation) est déléguée
 *   au FactureService. Le controller ne fait que :
 *   1. Recevoir la requête HTTP
 *   2. Appeler le service
 *   3. Retourner la réponse (vue ou redirect)
 */
class FactureExamenController extends Controller
{
    public function __construct(
        private readonly FactureService $factureService
    ) {}

    // ── Liste ──────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $startDate = $request->filled('start-date')
            ? \Carbon\Carbon::parse($request->input('start-date'))->startOfDay()
            : \Carbon\Carbon::now()->startOfDay();

        $endDate = $request->filled('end-date')
            ? \Carbon\Carbon::parse($request->input('end-date'))->endOfDay()
            : \Carbon\Carbon::now()->endOfDay();

        $factureExamens = \App\Models\FactureExamen::with(['patient', 'lignes'])
            ->where(function($query) {
                $query->whereHas('patient', function($q) {
                $q->whereRaw("LOWER(motif) = 'examen'");
                })->orWhereHas('lignes', function($q) {
                        $q->where('type_acte', 'like', 'examen%');
                    });
            })
            ->when($request->filled('type_acte'), function($q) use ($request) {
                $q->whereHas('lignes', function($q2) use ($request) {
                    $q2->where('type_acte', $request->type_acte);
                });
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(20);

        $lists = collect();
        $d = \Carbon\Carbon::now()->subDays(90);
        while ($d <= \Carbon\Carbon::now()) {
            $lists->push($d->format('Y-m-d'));
            $d->addDay();
        }
        $lists = $lists->reverse()->values();

        return view('admin.factures.facture_examen', compact(
            'factureExamens', 'startDate', 'endDate', 'lists'
        ));
    }

    

    // ── Création ──────────────────────────────────────────────────────────────

    public function create(Request $request): View
    {
        $patientId = $request->integer('patient_id');

        return view('admin.factures.partials.create_facture_examen', [
            'patient_id'      => $patientId,
            'consultation_id' => $request->integer('consultation_id'),
        ]);
    }

   /**
     * ✅ CORRECTION 3 : méthode store() refactorisée.
     *
     * PROBLÈMES dans l'ancienne version :
     *   a) FactureExamenLigne::create() → ce modèle n'existe pas. C'est FactureLigne.
     *   b) La redirect() pointait vers 'admin.factures.facture_examen' qui n'existe pas.
     *      La bonne route est 'facturation.examens.index'.
     *   c) Les champs $data['lignes'] passaient directement dans FactureLigne::create()
     *      sans injecter 'facture_examen_id' ni 'facture_type' → erreur de colonne.
     */
    public function store(StoreFactureExamenRequest $request): RedirectResponse
    {
        $data = $request->validated();
 
        DB::transaction(function () use ($data) {
 
            // 1. Calcul des montants via le service
            $montants = $this->factureService->preparerMontants(
                montantTotal:  (float) $data['montant_total'],
                priseEnCharge: (float) ($data['prise_en_charge'] ?? 0),
                avance:        (float) ($data['avance'] ?? 0),
            );
 
            // 2. Création de la facture (on retire 'lignes' du tableau pour éviter l'erreur fillable)
            $facture = FactureExamen::create(array_merge(
                collect($data)->except('lignes')->toArray(),
                $montants,
                ['numero' => $this->factureService->genererNumero('EXA', 'factures_examen')]
            ));
 
            // 3. Création des lignes avec les bons champs
            foreach ($data['lignes'] ?? [] as $index => $ligne) {
                FactureLigne::create(array_merge($ligne, [
                    'facture_examen_id' => $facture->id,
                    'facture_type'      => 'examen',   // discriminant
                    'ordre'             => $index + 1,
                ]));
            }
        });
 
     
        return redirect()
            ->route('facturation.examens.index')
            ->with('success', 'Facture examen créée avec succès.');
    }
 

    // ── Affichage ─────────────────────────────────────────────────────────────

    public function show(FactureExamen $factureExamen): View
    {
        $factureExamen->load(['patient', 'lignes', 'user', 'printer']);

        return view('admin.factures.partials.apercu_examen', [
            'facture' => $factureExamen,
            'isProforma' => $factureExamen->isProforma(),
            ]);
    }


    // ── Paiement ──────────────────────────────────────────────────────────────

    public function enregistrerPaiement(Request $request, FactureExamen $factureExamen): RedirectResponse
    {
        $validated = $request->validate([
            'montant'                  => 'required|numeric|min:1|max:' . $factureExamen->reste,
            'mode_paiement'            => 'required|string|max:50',
            'mode_paiement_info_sup'   => 'nullable|string|max:255',
            'reference'                => 'nullable|string|max:100',
        ]);

        $this->factureService->enregistrerPaiement(
            facture:       $factureExamen,
            factureType:   'facture_examen',
            montant:       (float) $validated['montant'],
            modePaiement:  $validated['mode_paiement'],
            extra:         ['mode_paiement_info_sup' => $validated['mode_paiement_info_sup'] ?? null,
                            'reference'              => $validated['reference'] ?? null],
        );

        return back()->with('success', 'Paiement enregistré.');
    }

    
      public function getPatientExamens(Request $request)
    {
        $request->validate(['patient_id' => 'required|integer|exists:patients,id']);
 
        $prescriptions = \App\Models\Prescription::where('patient_id', $request->patient_id)
            ->with('user:id,name,prenom')
            ->select([
                'id', 'user_id', 'hematologie', 'hemostase', 'biochimie',
                'hormonologie', 'marqueurs', 'bacteriologie', 'spermiologie',
                'urines', 'serologie', 'parasitologie', 'examen', 'created_at',
            ])
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($p) {
                $parties = array_filter([
                    $p->hematologie,
                    $p->hemostase,
                    $p->biochimie,
                    $p->hormonologie,
                    $p->parasitologie,
                    $p->bacteriologie,
                    $p->spermiologie,
                    $p->urines,
                    $p->serologie,
                    // ✅ CORRECTION 9 : $p->immunoserologie n'existe pas dans Prescription
                    // → supprimé pour éviter un accès à un attribut null silencieux
                    $p->examen,
                ]);
 
                $libelle = implode(' / ', $parties);
                if (empty($libelle)) {
                    $libelle = 'Examen du ' . $p->created_at->format('d/m/Y');
                }
 
                $medecin = $p->user
                    ? trim($p->user->name . ' ' . ($p->user->prenom ?? ''))
                    : '';
 
                return [
                    'id'      => $p->id,
                    'nom'     => $libelle,
                    'detail'  => $p->created_at->format('d/m/Y'),
                    'libelle' => $libelle,
                    'medecin' => $medecin,
                ];
            });
 
        return response()->json($prescriptions);
    }

     /**
     * Met à jour une facture d'examen
     * Bloque la modification si la facture est soldée
     **/
     
    /*public function FactureExamenUpdate(Request $request, $id)
    {
        // Récupérer la facture avec la relation patient
        $facture = FactureExamen::with('patient:id,prise_en_charge')->findOrFail($id);

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
                $facture->avance += $request->input('percu');
                $facture->mode_paiement = $request->input('mode_paiement');
                $facture->mode_paiement_info_sup = $modePaiementInfo;
                
                // Recalculer les parts assurance et patient
                $priseEnCharge = $facture->patient->prise_en_charge ?? 0;
                $facture->assurec = FactureExamen::calculAssurec($request->input('montant'), $priseEnCharge);
                $facture->assurancec = FactureExamen::calculAssurancec($request->input('montant'), $priseEnCharge);
                $facture->reste = FactureExamen::calculReste($facture->assurec, $facture->avance);
                
                // Le statut sera mis à jour automatiquement par l'événement du modèle
                
                // Sauvegarder la facture - ceci déclenchera automatiquement syncPatientData()
                $facture->save();

                // Sauvegarder l'historique
                $facture->historiques()->save($historiqueFacture);
                
                // Journaliser la mise à jour pour l'audit
                Log::info('Facture examen mise à jour', [
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
                ->route('facturation.examens.index')
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
    }*/

        // Dans FactureExamenController.php
    // REMPLACE toute la méthode FactureExamenUpdate() par ceci :

    public function FactureExamenUpdate(Request $request, $id): RedirectResponse
    {
        $facture = FactureExamen::with('patient:id,prise_en_charge')->findOrFail($id);

        $this->authorize('update', $facture);

        if ($facture->isSoldee() && $facture->is_printed) {
            return redirect()->back()
                ->with('error', 'Modification interdite : La facture n°' . $facture->numero . ' est déjà soldée et imprimée.');
        }

        $validated = $request->validate([
            'mode_paiement'          => 'required|string|max:50',
            'percu'                  => 'required|numeric|min:0',
            'num_cheque'             => 'required_if:mode_paiement,chèque',
            'emetteur_cheque'        => 'required_if:mode_paiement,chèque',
            'banque_cheque'          => 'required_if:mode_paiement,chèque',
            'emetteur_bpc'           => 'required_if:mode_paiement,bon de prise en charge',
        ]);

        // Protection : le perçu ne peut pas dépasser le reste
        if ((float) $validated['percu'] > (float) $facture->reste) {
            return redirect()->back()
                ->withErrors(['percu' => 'Le montant perçu ne peut pas dépasser le reste à payer.'])
                ->withInput();
        }

        $modePaiementInfo = $this->prepareModePaiementInfo($request);

        // ✅ UNE SEULE SOURCE DE VÉRITÉ — le service gère tout
        $this->factureService->enregistrerPaiement(
            facture:      $facture,
            factureType:  'facture_examen',
            montant:      (float) $validated['percu'],
            modePaiement: $validated['mode_paiement'],
            extra: [
                'mode_paiement_info_sup' => $modePaiementInfo ?: null,
            ],
        );

        $message = $facture->fresh()->isSoldee()
            ? 'Facture soldée avec succès !'
            : 'Paiement enregistré. Reste : ' . number_format($facture->fresh()->reste, 0, ',', ' ') . ' FCFA';

        return redirect()->route('facturation.examens.index')->with('success', $message);
    }

                
   public function ajouterElement(Request $request, FactureExamen $facture): RedirectResponse
    {
        $this->authorize('view', User::class);
 
        $validated = $request->validate([
            'type_sous' => 'required|string|in:laboratoire,imagerie',
            'libelle'   => 'required|string|max:500',
            'montant'   => 'required|numeric|min:1',
            'medecin'   => 'nullable|string|max:255',
        ]);
 
        DB::transaction(function () use ($facture, $validated) {
 
            // 1. Créer la ligne avec les bons champs (type_sous + type_acte)
            FactureLigne::create([
                'facture_examen_id' => $facture->id,
                'facture_type'      => 'examen',
                'type_sous'         => $validated['type_sous'],
                'type_acte'         => 'examen_' . $validated['type_sous'],  // rétrocompatibilité
                'libelle'           => $validated['libelle'],
                'montant'           => (int) $validated['montant'],
                'medecin'           => $validated['medecin'] ?? null,
                'ordre'             => $facture->lignes()->count() + 1,
            ]);
 
            // 2. Recalculer le montant total depuis toutes les lignes
            $facture->recalculerMontant();
 
            // 3. Recalculer assurance + reste via le service
            $montants = $this->factureService->preparerMontants(
                montantTotal:  (float) $facture->fresh()->montant_total,
                priseEnCharge: (float) ($facture->patient->prise_en_charge ?? 0),
                avance:        (float) $facture->avance,
            );
 
            $facture->update($montants);
        });
 
        return back()->with('success', 'Examen ajouté à la facture N°' . $facture->numero . ' avec succès.');
    }

    // ── Impression ────────────────────────────────────────────────────────────

    /*public function imprimer(FactureExamen $factureExamen): View|RedirectResponse
    {
        if (! $factureExamen->isImprimable()) {
            return back()->with('error', 'La facture doit être soldée avant impression.');
        }

        $factureExamen->marquerCommeImprimee();
        $factureExamen->load(['patient', 'lignes', 'user']);

        return view('facturation.examens.print', ['facture' => $factureExamen]);
    }*/


    public function exportPdf(FactureExamen $factureExamen)
    {
        $this->authorize('view', $factureExamen);
        
        if (! $factureExamen->isImprimable()) {
            return back()->with('error', 'Facture non soldée.');
        }
        
        $factureExamen->load(['patient', 'lignes', 'user']);
        $factureExamen->marquerCommeImprimee();
        
       $pdf = \PDF::loadView('admin.etats.examen', ['facture' => $factureExamen]);
        return $pdf->download('facture_examen_' . $factureExamen->numero . '.pdf');
    }


    
     public function storeFactureExamenDirect(Request $request): RedirectResponse
    {
        $this->authorize('view', User::class);
 
        $validated = $request->validate([
            'patient_id'      => 'required|exists:patients,id',
            'libelle'         => 'required|string|max:500',
            'montant'         => 'required|numeric|min:1',
            'avance'          => 'nullable|numeric|min:0',
            'medecin_r'       => 'nullable|string|max:255',
            'mode_paiement'   => 'nullable|string|max:100',
            'prescription_id' => 'nullable|exists:prescriptions,id',
        ], [
            'patient_id.required' => 'Veuillez sélectionner un patient.',
            'patient_id.exists'   => 'Ce patient est introuvable.',
            'libelle.required'    => 'La description des examens est obligatoire.',
            'montant.required'    => 'Le montant est obligatoire.',
            'montant.min'         => 'Le montant doit être supérieur à 0.',
        ]);
 
        try {
            $facture = DB::transaction(function () use ($validated) {
 
                // 1. Charger le patient avec les champs utiles
                $patient = Patient::select([
                    'id', 'name', 'prenom', 'numero_dossier',
                    'prise_en_charge',
                    'assurance',
                    'numero_assurance',
                    'mode_paiement',
                ])->findOrFail($validated['patient_id']);
 
                $montant = (float) $validated['montant'];
                $avance  = (float) ($validated['avance'] ?? 0);
 
                // Calcul des montants via le service (une seule source de vérité)
                $montants = $this->factureService->preparerMontants(
                    montantTotal:  $montant,
                    priseEnCharge: (float) ($patient->prise_en_charge ?? 0),
                    avance:        $avance,
                );
 
                // 2. Créer la FactureExamen avec UNIQUEMENT les champs du $fillable
                $facture = FactureExamen::create([
                    'numero'          => $this->factureService->genererNumero('EXA', 'factures_examen'),
                    'patient_id'      => $patient->id,
                    'user_id'         => Auth::id(),
                    //'patient_name'    => trim($patient->name . ' ' . ($patient->prenom ?? '')),
                    'consultation_id' => null,
 
                    'montant_total'   => $montants['montant_total'],
                    'avance'          => $montants['avance'],
                    'assurec'         => $montants['assurec'],
                    'assurancec'      => $montants['assurancec'],
                    'reste'           => $montants['reste'],
                    // statut calculé automatiquement par HasFactureMontants::computeStatut()
 
                    'assurance'       => (bool) ($patient->assurance ?? false),
                    'numero_assurance'=> $patient->numero_assurance ?? null,
                    'prise_en_charge' => $patient->prise_en_charge ?? 0,
                    //'medecin_r'       => $validated['medecin_r'] ?? null,
                    'mode_paiement'   => $validated['mode_paiement'] ?? 'espèce',
                    'notes'           => $validated['libelle'],   // description stockée dans notes
                ]);
 
                // 3. Créer la ligne avec 'facture_examen_id' (et non facture_consultation_id)
                FactureLigne::create([
                    'facture_examen_id' => $facture->id,  
                    'facture_type'      => 'examen',
                    'type_acte'         => 'examen_labo',
                    'type_sous'         => 'laboratoire',
                    'libelle'           => $validated['libelle'],
                    'montant'           => (int) $montant,
                    'quantite'          => 1,
                    'technicien'           => $this->getNomUser(is_numeric($validated['medecin_r'] ?? null) ?  (int) $validated->medecin_r : null),
                    'acte_type'         => 'Prescription',
                    'acte_id'           => $validated['prescription_id'] ?? null,
                    'ordre'             => 1,
                ]);
 
                Log::info('Facture examen directe créée', [
                    'facture_id' => $facture->id,
                    'numero'     => $facture->numero,
                    'patient_id' => $patient->id,
                    'montant'    => $montant,
                    'assurec'    => $montants['assurec'],
                    'assurancec' => $montants['assurancec'],
                    'user_id'    => Auth::id(),
                ]);
 
                return $facture;
            });
 
            return redirect()
                ->route('facturation.examens.index')  
                ->with('success', 'Facture d\'examen N°' . $facture->numero . ' créée avec succès !');
 
        } catch (\Exception $e) {
            Log::error('Erreur création facture examen direct', [
                'error'   => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
 
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }
 

    /**
     * API: Search patients for facture examen Select2 (100% INDEPENDANT)
     * Exact copy de PatientVisitsController@searchPatients - format identique pour Select2
    */

    public function searchPatientsExamen(Request $request): \Illuminate\Http\JsonResponse
    {
        // Autorisation minimale : être connecté et avoir le droit "view"
        $this->authorize('view', \App\Models\User::class);
 
        $search = trim((string) $request->input('q', ''));
 
        // Requête défensive : on n'interroge la BDD que si au moins 2 caractères
        if (mb_strlen($search) < 2) {
            return response()->json(['results' => []]);
        }
 
        $patients = \App\Models\Patient::select('id', 'numero_dossier', 'name', 'prenom')
            ->where(function ($query) use ($search) {
                $query->where('name',            'like', "%{$search}%")
                      ->orWhere('prenom',         'like', "%{$search}%")
                      ->orWhere('numero_dossier', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(30)
            ->get()
            ->map(fn ($p) => [
                'id'             => $p->id,
                // Format attendu par processResults dans le JS
                'text'           => "CMCU-{$p->numero_dossier} | {$p->name} {$p->prenom}",
                'numero_dossier' => $p->numero_dossier,
                'name'           => $p->name,
                'prenom'         => $p->prenom,
            ]);
 
        return response()->json(['results' => $patients]);
    } 


    /*public function searchPatientsExamen(Request $request)

    {
 
        $this->authorize('view', User::class);

        $search = $request->input('q');

        if (mb_strlen($search) < 2) {
            return response()->json(['results' => []]);
        }

        $patients = Patient::select('id', 'numero_dossier', 'name', 'prenom')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('numero_dossier', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'text' => "CMCU-{$patient->numero_dossier} | {$patient->name} {$patient->prenom}",
                    'numero_dossier' => $patient->numero_dossier,
                    'name' => $patient->name,
                    'prenom' => $patient->prenom,
                ];
            });

        return response()->json(['results' => $patients]);
    }*/
     /**
     * ─────────────────────────────────────────────────────────────
     * BILAN JOURNALIER DES EXAMENS (PDF)
     * Même logique que export_bilan_consultation
     * mais filtré sur les lignes examen_labo
     * ─────────────────────────────────────────────────────────────
     */

     public function export_bilan_examen(Request $request)
    {
        set_time_limit(180);
        ini_set('memory_limit', '512M');
 
        try {
            $day = $request->input('day');
 
            // Historiques des factures qui contiennent au moins un examen_labo
            $factures = HistoriqueFacture::with([
                'facture_consultation:id,numero,patient_id,patient_name,montant,motif,medecin_r,demarcheur,assurancec,assurec,statut,devise,taux_conversion,montant_devise',
                'facture_consultation.patient:id,name',
                'facture_consultation.lignes',
            ])
            ->where('created_at', 'LIKE', $day . '%')
            ->whereHas('facture_consultation', function ($query) {
                $query->whereNull('deleted_at')
                      ->whereHas('lignes', fn($q) => $q->where('type_acte', 'examen_labo'));
            })
            ->select([
                'id', 'facture_consultation_id', 'montant', 'percu',
                'reste', 'mode_paiement', 'created_at',
                'devise', 'taux_conversion', 'montant_devise'
            ])
            ->get()
            ->groupBy('facture_consultation_id');
 
            $totalPercu       = 0;
            $totalMontant     = 0;
            $totalReste       = 0;
            $totalPartAssurance = 0;
            $totalPartPatient   = 0;
            $tFactures        = collect();
            $mode_paiement    = collect();
 
            foreach ($factures as $key => $historique_factures) {
                $factureData = (object)[
                    'numero'          => '',
                    'name'            => '',
                    'montant'         => 0,
                    'percu'           => 0,
                    'reste'           => 0,
                    'partAssurance'   => 0,
                    'partPatient'     => 0,
                    'medecin'         => '',
                    'demarcheur'      => '',
                    'statut'          => '',
                    'examens'         => [],   // ← lignes détail examen
                    'devise'          => 'XAF',
                    'montant_devise'  => null,
                    'taux_conversion' => null,
                ];
 
                foreach ($historique_factures as $hf) {
                    $fc = $hf->facture_consultation;
                    $factureData->numero        = $fc->numero;
                    $factureData->name          = optional($fc->patient)->name ?? $fc->patient_name ?? '[Patient supprimé]';
                    $factureData->montant       = $fc->montant;
                    $factureData->percu        += $hf->percu;
                    $factureData->reste         = $hf->reste;
                    $factureData->partAssurance = $fc->assurancec ?? 0;
                    $factureData->partPatient   = $fc->assurec   ?? 0;
                    $factureData->medecin       = $fc->medecin_r  ?? '';
                    $factureData->demarcheur    = $fc->demarcheur ?? '';
                    $factureData->statut        = $fc->statut     ?? '';
 
                    // Récupérer uniquement les lignes examen_labo pour le détail
                    $factureData->examens = $fc->lignes
                        ? $fc->lignes->where('type_acte', 'examen_labo')->values()->toArray()
                        : [];
 
                    $modePaiementKey = $this->getModePaiementKey($hf->mode_paiement);
                    $existingMode    = $mode_paiement->firstWhere('key', $modePaiementKey);
                    if ($existingMode) {
                        $existingMode->val += $hf->percu;
                    } else {
                        $mode_paiement->push((object)[
                            'key'  => $modePaiementKey,
                            'val'  => $hf->percu,
                            'name' => $hf->mode_paiement,
                        ]);
                    }
 
                    $totalPercu += $hf->percu;
                }
 
                $tFactures->push($factureData);
                $totalMontant       += $factureData->montant;
                $totalReste         += $factureData->reste;
                $totalPartAssurance += $factureData->partAssurance;
                $totalPartPatient   += $factureData->partPatient;
            }
 
            return \App\Services\PdfService::generate(
                'admin.etats.bilan_examen',   // ← vue bilan (à créer, voir fichier joint)
                [
                    'mode_paiement'      => $mode_paiement,
                    'tFactures'          => $tFactures,
                    'totalPercu'         => $totalPercu,
                    'totalMontant'       => $totalMontant,
                    'totalReste'         => $totalReste,
                    'totalPartAssurance' => $totalPartAssurance,
                    'totalPartPatient'   => $totalPartPatient,
                    'date'               => $day,
                ],
                "bilan_examens_{$day}.pdf",
                'landscape'
            );
 
        } catch (\Exception $e) {
            Log::error('Erreur Bilan Examen PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la génération du bilan PDF');
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


     private function getNomUser(?int $userId, string $prefix = ''): ?string
    {
        if (! $userId) {
            return null;
        }

        $user = User::select('name', 'prenom')->find($userId);

        return $user
            ? $prefix . trim($user->name . ' ' . $user->prenom)
            : null;
    }

     // ── Suppression ───────────────────────────────────────────────────────────
 
    /**
   
     */
    public function destroy($id): RedirectResponse
    {
        $this->authorize('view', User::class);
 
        $facture = FactureExamen::findOrFail($id);
 
        if ($facture->isSoldee()) {
            return redirect()->back()
                ->with('error', 'Suppression interdite : La facture n°' . $facture->numero . ' est soldée.');
        }
 
        try {
            DB::transaction(function () use ($facture) {
                $facture->delete();  // SoftDelete grâce au trait
            });
 
            return redirect()
                ->route('facturation.examens.index')  
                ->with('success', 'La facture n°' . $facture->numero . ' a été supprimée avec succès.');
 
        } catch (\Exception $e) {
            Log::error('Échec de la suppression de la facture examen', [
                'facture_id' => $id,
                'error'      => $e->getMessage(),
            ]);
 
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la facture.');
        }
    }

}