<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationAnesthesiste;
use App\Models\Dossier;
use App\Models\FactureConsultation;
use App\Models\FicheConsommable;
use App\Models\FicheIntervention;
use App\Models\Lettre;
use App\Models\Patient;
use App\Models\Ordonance;
use App\Models\Produit;
use App\Models\SoinsInfirmier;
use App\Models\SurveillancePostAnesthesique;
use App\Models\HistoriqueFacture;
use App\Models\User;
use App\Models\VisitePreanesthesique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use ZanySoft\LaravelPDF\Facades\PDF;
use Illuminate\Support\Facades\Log;

use App\Services\PdfService;

class PatientsController extends Controller
{
    /**
     * Display a listing of patients
     */
    public function index(Request $request)
    {
        $this->authorize('viewList', Patient::class);
        
        $name = $request->input('name');
        $perPage = (int) $request->input('per_page', 50);
        $page = (int) $request->input('page', 1);

        // Create cache key based on search parameters
        $cacheKey = sprintf('patients.index.%s.%s.%s', auth()->id(), $name ?: 'all', $page);

        $patients = Cache::tags(['patients'])
            ->remember($cacheKey, 90, function () use ($name, $perPage) {
                $query = Patient::select([
                    'id', 
                    'numero_dossier', 
                    'name', 
                    'prenom', 
                    'montant', 
                    'reste', 
                    'assurance',
                    'prise_en_charge',
                    'date_insertion',
                    'created_at'
                ]);

                if ($name) {
                    $query->where(function ($q) use ($name) {
                        $q->where('name', 'like', "%{$name}%")
                          ->orWhere('prenom', 'like', "%{$name}%")
                          ->orWhere('numero_dossier', 'like', "%{$name}%");
                    });
                }

                return $query->latest()->paginate($perPage);
            });

        if ($patients instanceof \Illuminate\Contracts\Pagination\Paginator && $name) {
            $patients->appends(['name' => $name]);
        }

        return view('admin.patients.index', compact('patients', 'name', 'perPage'));
    }

    /**
     * Show the form for creating a new patient
   */

    public function create(User $user) 
    {

        $this->authorize('create', Patient::class);

        $users =  User::whereIn('role_id', [2,4])
            ->select('id', 'name', 'prenom', 'specialite', 'role_id')
            ->orderBy('specialite')->orderBy('name')
            ->get()
            ->groupBy(function ($user) {
                return $user->role_id == 4 ? 'Infirmier' : $user->specialite ;
            });

        return view('admin.patients.create', compact('users'));    
    }

    /**
     * Store a newly created patient in storage
     */
    public function store(Request $request)
    {
        $this->authorize('create', Patient::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'mode_paiement' => 'required|string',
            'assurance' => 'nullable|string|max:255',
            'motif' => 'required|string',
            'details_motif' => 'required|string',
            'montant' => 'required|numeric|min:0',
            'avance' => 'required|numeric|min:0',
            'devise' => 'required|string|in:XAF,EUR,DOLLAR,GBP',
            'taux_conversion' => 'required_unless:devise,XAF|nullable|numeric|min:1',
            'montant_devise' => 'required_unless:devise,XAF|nullable|numeric|min:0',
            'demarcheur' => 'nullable|string',
            'numero_assurance' => 'required_with:assurance|nullable|string',
            'prise_en_charge' => 'required_with:assurance|numeric|between:0,100',
            'num_cheque' => 'required_if:mode_paiement,chèque',
            'emetteur_cheque' => 'required_if:mode_paiement,chèque',
            'banque_cheque' => 'required_if:mode_paiement,chèque',
            'emetteur_bpc' => 'required_if:mode_paiement,bon de prise en charge',
            // 'date_insertion' => 'required|date',
            //'medecin_r' => 'nullable|required_without:infirmier|string',
           // 'infirmier' => 'nullable|required_without:medecin_r|string',
            'assigne_a' => 'required|exists:users,id'
            
        ]);

        $modePaiementInfo = $this->buildModePaiementInfo($request);

        try {
            DB::transaction(function () use ($request, $modePaiementInfo) {
                $montant = $request->input('montant');
                $priseEnCharge = $request->input('prise_en_charge', 0);
                $avance = $request->input('avance');
                $devise  = $request->input('devise', 'XAF');
                $taux    = $request->input('taux_conversion', 1);
                $remis   = $request->input('montant_devise', 0);

                // Si devise étrangère, le JS a déjà calculé l'avance en FCFA dans le champ "avance"
                // On la lit directement — le JS a fait min(remis*taux, montant)
                $avance = $request->input('avance');

                // Sécurité côté serveur : l'avance ne peut pas dépasser la dette
                $avance = min((float)$avance, (float)$montant);


                 // Simply create the patient - NO consultation
                $patient = Patient::create([
                    'numero_dossier' => mt_rand(1000000, 9999999) - 1,
                    'name' => $request->input('name'),
                    'prenom' => $request->input('prenom'),
                    'montant' => $montant,
                    'assurance' => $request->input('assurance'),
                    'avance' => $avance,
                    'devise'                => $devise,
                    'taux_conversion'       => $devise !== 'XAF' ? $taux : null,
                    'montant_devise'  => $devise !== 'XAF' ? $remis : null,
                   
                    'motif' => $request->input('motif'),
                    'mode_paiement' => $request->input('mode_paiement'),
                    'mode_paiement_info_sup' => $modePaiementInfo,
                    'details_motif' => $request->input('details_motif'),
                    'numero_assurance' => $request->input('numero_assurance'),
                    'prise_en_charge' => $priseEnCharge,
                    'assurec' => FactureConsultation::calculAssurec($montant, $priseEnCharge),
                    'assurancec' => FactureConsultation::calculAssuranceC($montant, $priseEnCharge),
                    'reste'      => FactureConsultation::calculReste(
                                FactureConsultation::calculAssurec($montant, $priseEnCharge),
                                $avance
                    ),
                    'demarcheur' => $request->input('demarcheur'),
                    'date_insertion' => now()->toDateString(),
                    //'medecin_r' => $request->input('medecin_r'), // Store assigned doctor name
                    //'infirmier' => $request->input('infirmier'), // Store assigned doctor name
                    'assigne_a' => $request->input('assigne_a'),
                    'user_id' => Auth::id(),
                ]);

                Log::info('Patient créé et assigné au médecin', [
                    'patient_id' => $patient->id,
                    'medecin_assigne' => $patient->medecin_r,
                    'infirmier' => $patient->infirmier,
                    'created_by' => Auth::id(),
                ]);

                return $patient;
            });

            // Clear cache so assigned doctor sees patient in "patients suivis" immediately
            Cache::tags(['patients', 'consultations'])->flush();

            return redirect()->route('patients.index')
                ->with('success', 'Le patient a été ajouté avec succès !');

        } catch (\Exception $e) {
            Log::error('Patient Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du patient');
        }
    }
    

    private function findUserByName($name, $roleId)
{
    $cleanName = preg_replace('/^(Dr\.?|Docteur)\s*/i', '', trim($name));
    $parts = preg_split('/\s+/', $cleanName, -1, PREG_SPLIT_NO_EMPTY);

    $query = User::where('role_id', $roleId);

    if (count($parts) === 1) {
        $query->where(function($q) use ($parts) {
            $q->where('name', 'LIKE', "%{$parts[0]}%")
              ->orWhere('prenom', 'LIKE', "%{$parts[0]}%");
        });
    } else {
        $nom = $parts[0];
        $prenom = implode(' ', array_slice($parts, 1));
        $query->where(function($q) use ($nom, $prenom) {
            $q->where('name', 'LIKE', "%{$nom}%")
              ->where('prenom', 'LIKE', "%{$prenom}%");
        })->orWhere(function($q) use ($nom, $prenom) {
            $q->where('name', 'LIKE', "%{$prenom}%")
              ->where('prenom', 'LIKE', "%{$nom}%");
        });
    }

    return $query->first();
}

    /**
 * Display the specified patient
 */
    public function show(Patient $patient, Consultation $consultation)
    {
        $this->authorize('consulter', Patient::class);
        
        // Get latest timestamps for cache busting (use strtotime on raw values)
        $latestConsultation = $patient->consultations()->latest()->value('updated_at');
        $latestConsultationAnes = $patient->consultation_anesthesistes()->latest()->value('updated_at');
        $latestParametre = $patient->parametres()->latest()->value('updated_at');
        $latestCrbo = $patient->compte_rendu_bloc_operatoires()->latest()->value('updated_at');
        $examensCount = $patient->examens()->count();

        $latestConsultationTs = $latestConsultation ? strtotime($latestConsultation) : 'none';
        $latestConsultationAnesTs = $latestConsultationAnes ? strtotime($latestConsultationAnes) : 'none';
        $latestParametreTs = $latestParametre ? strtotime($latestParametre) : 'none';
        $latestCrboTs = $latestCrbo ? strtotime($latestCrbo) : 'none';

        // Create a cache key including compte rendu bloc operatoire timestamp
        $cacheKey = sprintf(
            "patient_show_%s_%s_%s_%s_%s_%s",
            $patient->id,
            $latestConsultationTs,
            $latestConsultationAnesTs,
            $latestParametreTs,
            $latestCrboTs,
            $examensCount
        );

        // Shorter cache time (5 minutes). Use 'patients' tag so other controllers can flush it.
        $data = Cache::tags(['patients'])->remember($cacheKey, 30, function () use ($patient) {
            $examens_scannes = $patient->examens()
                ->select(['id', 'patient_id', 'nom', 'description', 'image', 'created_at'])
                ->latest()
                ->paginate(4);
            
            // Eager load relationships
            $patient->load([
                'consultations' => function ($query) {
                    $query->with(['user:id,name'])
                        ->latest()
                        ->limit(5);
                },
                'consultation_anesthesistes' => function ($query) {
                    $query->with(['user:id,name'])
                        ->latest()
                        ->limit(5);
                },
                'dossiers' => function ($query) {
                    $query->latest()
                        ->limit(1);
                }
            ]);
            
            return [
                'examens_scannes' => $examens_scannes,
                'consultations' => $patient->consultations->first(),
                'consultation_anesthesistes' => $patient->consultation_anesthesistes->first(),
                'dossiers' => $patient->dossiers->first(),
                'parametres' => $patient->parametres()->latest()->first(),
                'premedications' => $patient->premedications()->latest()->limit(5)->get(),
                'ordonances' => $patient->ordonances()
                    ->with(['user:id,name'])
                    ->latest()
                    ->paginate(5),
                'prescriptions' => $patient->prescriptions()
                    ->with(['user:id,name'])
                    ->latest()
                    ->paginate(5),
                'fiche_interventions' => $patient->fiche_interventions()
                    ->with(['user:id,name'])
                    ->latest()
                    ->limit(5)
                    ->get(),
                'visite_anesthesistes' => $patient->visite_preanesthesiques()
                    ->with(['patient:id,name,prenom', 'user:id,name'])
                    ->latest()
                    ->first(),
                'compte_rendu_bloc_operatoires' => $patient->compte_rendu_bloc_operatoires()
                    ->latest()
                    ->first()
            ];
        });

        $medecin = Cache::remember('medecins_list', 3600, function () {
            return User::where('role_id', 2)
                ->select('id', 'name', 'prenom')
                ->orderBy('name')
                ->get();
        });

        return view('admin.patients.show', array_merge([
            'patient' => $patient,
            'medecin' => $medecin,
            'consultation' => $consultation,
        ], $data));
    }

    /**
     * Update the specified patient in storage
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', Patient::class);
        
        $request->validate([
            'name' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'assurance' => 'nullable|string',
            'numero_assurance' => 'nullable|string',
            'montant' => 'nullable|numeric',
            'motif' => 'nullable|string',
            'details_motif' => 'required|string',
            'avance' => 'nullable|numeric',
            'reste' => 'nullable|numeric',
            'demarcheur' => 'nullable|string',
            'prise_en_charge' => 'nullable|numeric|between:0,100',
            'date_insertion' => 'nullable|date',
            'medecin_r' => 'nullable|string',
        ]);

        try {
            $patient = Patient::findOrFail($id);

            DB::transaction(function () use ($patient, $request) {
                $patient->fill([
                    'assurance' => $request->input('assurance'),
                    'numero_assurance' => $request->input('numero_assurance'),
                    'name' => $request->input('name'),
                    'prenom' => $request->input('prenom'),
                    'montant' => $request->input('montant'),
                    'motif' => $request->input('motif'),
                    'details_motif' => $request->input('details_motif'),
                    'avance' => $request->input('avance'),
                    'reste' => $request->input('reste'),
                    'reste1' => $request->input('reste1'),
                    'assurancec' => $request->input('assurancec'),
                    'assurec' => $request->input('assurec'),
                    'demarcheur' => $request->input('demarcheur'),
                    'prise_en_charge' => $request->input('prise_en_charge'),
                    'date_insertion' => $request->input('date_insertion'),
                    'medecin_r' => $request->input('medecin_r'),
                    'user_id' => Auth::id(),
                ]);

                $patient->save();
            });

            Cache::tags(['patients'])->flush();

            return redirect()->route('patients.show', $patient->id)
                ->with('success', 'Les informations du patient ont été mis à jour avec succès !');

        } catch (\Exception $e) {
            Log::error('Patient Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour');
        }
    }

    /**
     * Update patient motif and montant
     */
    public function motifMontantUpdate(Request $request, $id)
    {
        $this->authorize('update', Patient::class);
        
        $request->validate([
            'motif' => 'required|string',
            'name' => 'required|string',
            'prenom' => 'required|string',
            'medecin_r' => 'required|string',
            'mode_paiement' => 'required|string',
            'num_cheque' => 'required_if:mode_paiement,chèque',
            'emetteur_cheque' => 'required_if:mode_paiement,chèque',
            'banque_cheque' => 'required_if:mode_paiement,chèque',
            'emetteur_bpc' => 'required_if:mode_paiement,bon de prise en charge',
            'details_motif' => 'required|string',
            'montant' => 'required|numeric',
            'avance' => 'required|numeric',
            'prise_en_charge' => 'required|numeric|between:0,100',
        ]);

        try {
            $patient = Patient::findOrFail($id);
            $modePaiementInfo = $this->buildModePaiementInfo($request);

            DB::transaction(function () use ($patient, $request, $modePaiementInfo) {
                $montant = $request->input('montant');
                $priseEnCharge = $request->input('prise_en_charge');
                $avance = $request->input('avance');

                $assurec = FactureConsultation::calculAssurec($montant, $priseEnCharge);

                $patient->fill([
                    'name' => $request->input('name'),
                    'prenom' => $request->input('prenom'),
                    'medecin_r' => $request->input('medecin_r'),
                    'mode_paiement_info_sup' => $modePaiementInfo,
                    'montant' => $montant,
                    'details_motif' => $request->input('details_motif'),
                    'assurance' => $request->input('assurance'),
                    'avance' => $avance,
                    'mode_paiement' => $request->input('mode_paiement'),
                    'prise_en_charge' => $priseEnCharge,
                    'assurec' => $assurec,
                    'assurancec' => FactureConsultation::calculAssuranceC($montant, $priseEnCharge),
                    'reste' => FactureConsultation::calculReste($assurec, $avance),
                    'numero_assurance' => $request->input('numero_assurance'),
                    'user_id' => Auth::id(),
                ]);

                $patient->save();
            });

            Cache::tags(['patients'])->flush();

            return redirect()->route('patients.show', $patient->id)
                ->with('success', 'Le motif et le montant ont été mis à jour avec succès !');

        } catch (\Exception $e) {
            Log::error('Motif Montant Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour');
        }
    }

    /**
     * Generate consultation invoice for patient
     */
    public function generate_consultation(Request $request, $id)
    {
        try {
            $this->authorize('update', Patient::class);
            $this->authorize('print', Patient::class);

            $patient = Patient::select([
                'id', 'numero_dossier', 'name', 'prenom', 'montant',
                'avance', 'reste', 'assurec', 'assurancec', 'mode_paiement',
                'mode_paiement_info_sup', 'motif', 'details_motif',
                'demarcheur', 'medecin_r', 'devise', 'taux_conversion', 'montant_devise'

            ])->findOrFail($id);

            $statutFacture = $patient->reste == 0 ? 'Soldée' : 'Non soldée';

            $facture = DB::transaction(function () use ($patient, $statutFacture) {
                $facture = FactureConsultation::create([
                    'numero' => $patient->numero_dossier,
                    'patient_id' => $patient->id,
                    'patient_name' => trim($patient->name . ' ' . ($patient->prenom ?? '')),
                    'assurancec' => $patient->assurancec ?? 0,
                    'assurec' => $patient->assurec ?? 0,
                    'mode_paiement' => $patient->mode_paiement ?? 'espèce',
                    'mode_paiement_info_sup' => $patient->mode_paiement_info_sup ?? '',
                    'motif' => $patient->motif ?? 'Consultation',   // NOT NULL — fallback required
                    'details_motif' => $patient->details_motif ?? '',
                    'montant' => $patient->montant ?? 0,
                    'demarcheur' => $patient->demarcheur,
                    'avance' => $patient->avance ?? 0,
                    'devise'          => $patient->devise          ?? 'XAF',  
                    'taux_conversion' => $patient->taux_conversion ?? null,   
                    'montant_devise'  => $patient->montant_devise  ?? null,
                    'reste' => $patient->reste ?? 0,
                    'prenom' => $patient->prenom,
                    'medecin_r' => $patient->medecin_r,
                    'date_insertion' => now()->toDateString(),
                    'user_id' => auth()->id(),
                    'statut' => $statutFacture,
                ]);

                $facture->historiques()->create([
                    'reste' => $facture->reste,
                    'montant' => $facture->montant,
                    'percu' => $facture->avance,
                    'assurec' => $facture->assurec,
                    'mode_paiement' => $facture->mode_paiement,
                    'devise'         => $facture->devise ?? 'XAF',   
                    'taux_conversion'=> $facture->taux_conversion,   
                    'montant_devise' => $facture->montant_devise,  
                ]);

                return $facture;
            });

            Cache::tags(['factures', 'patients'])->flush();

            return redirect()->route('factures.consultation')
                ->with('success', 'Facture n° ' . $facture->id . ' générée avec succès!');

        } catch (\Exception $e) {
            Log::error('Generate Consultation Error: ' . $e->getMessage(), [
                'patient_id' => $id
            ]);

            return redirect()->back()->with('error', 'Erreur lors de la génération de la facture');
        }
    }

    /**
     * Print patient discharge letter
     */
    public function print_sortie(Patient $patient)
    {
        set_time_limit(120);
        ini_set('memory_limit', '256M');

        try {
            $dossier = Dossier::where('patient_id', $patient->id)
                ->select('id', 'patient_id', 'sexe', 'date_naissance')
                ->first();

            $consultation = Consultation::with('user')
                ->where('patient_id', $patient->id)
                ->select([
                    'id', 'patient_id', 'user_id', 'diagnostic',
                    'created_at', 'motif_c', 'antecedent_m', 'examen_c',
                    'proposition_therapeutique', 'proposition',
                    'date_consultation', 'acte', 'medecin'
                ])
                ->latest()
                ->first();

            if (!$consultation) {
                Log::error('Lettre Sortie PDF: Aucune consultation trouvée', [
                    'patient_id' => $patient->id
                ]);
                return redirect()->back()
                    ->with('error', 'Aucune consultation enregistrée pour ce patient.');
            }

            // Clear any existing output
            if (ob_get_length()) {
                ob_end_clean();
            }

            // PdfService options
            $orientation = request()->input('orientation', 'portrait');
            $format = request()->input('format', 'A4');
            $delivery = request()->input('delivery', 'stream');

            // $filename = sprintf(
            //     'lettre_sortie_%s_%s.pdf',
            //     $patient->numero_dossier,
            //     preg_replace('/[^a-zA-Z0-9_\-]/', '_', $patient->name ?? 'patient')
            // );


            return redirect()->route('print.preview', [
                'type' => 'lettre',
                'id' => $patient->id,
                'patient_id' => $patient->id
            ]);

        } catch (\Exception $e) {
            Log::error('Lettre Sortie PDF Error: ' . $e->getMessage(), [
                'patient_id' => $patient->id
            ]);

            if (ob_get_length()) {
                ob_end_clean();
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la génération du document.');
        }
    }

    /**
     * Export prescription to PDF
     */
    public function export_ordonance($id)
    {
        set_time_limit(120);
        ini_set('memory_limit', '256M');

        try {
            $ordonance = Ordonance::with([
                    'patient:id,name,prenom,numero_dossier',
                    'user:id,name,prenom,specialite,onmc'
                ])
                ->select('id', 'patient_id', 'user_id', 'description', 'medicament', 'quantite', 'created_at')
                ->findOrFail($id);

            // Clear any existing output
            if (ob_get_length()) {
                ob_end_clean();
            }

            // PdfService options
            $orientation = request()->input('orientation', 'portrait');
            $format = request()->input('format', 'A4');
            $delivery = request()->input('delivery', 'stream');

            $filename = sprintf(
                'ordonance_%s_%s.pdf',
                $ordonance->patient->numero_dossier ?? 'unknown',
                preg_replace('/[^a-zA-Z0-9_\-]/', '_', $ordonance->patient->name ?? 'patient')
            );

            return redirect()->route('print.preview', [
                'type' => 'ordonance',
                'id' => $id
            ]);
        } catch (\Exception $e) {
            Log::error('Export Ordonance PDF Error: ' . $e->getMessage(), [
                'ordonance_id' => $id
            ]);

            if (ob_get_length()) {
                ob_end_clean();
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la génération de l\'ordonnance');
        }
    }

    /**
     * Search for patients
     */
    public function search(Request $request)
    {
        $this->authorize('update', Patient::class);
        
        $name = $request->input('name');
        
        $patients = Patient::select([
                'id', 'numero_dossier', 'name', 'prenom', 
                'montant', 'reste', 'assurance', 'prise_en_charge',
                'date_insertion', 'created_at'
            ])
            ->where(function ($query) use ($name) {
                $query->where('prenom', 'like', "%{$name}%")
                    ->orWhere('name', 'like', "%{$name}%")
                    ->orWhere('numero_dossier', 'like', "%{$name}%");
            })
            ->latest()
            ->paginate(25);
        
        return view('admin.patients.index', compact('patients', 'name'));
    }

    /**
     * Manage fiche consommable
     */
    public function FicheConsommableCreate(FicheConsommable $consommable, Patient $patient)
    {
        $consommables = $patient->fiche_consommables()
            ->with(['patient:id,name'])
            ->latest()
            ->paginate(20);

        return view('admin.patients.fiche_consommable', [
            'produits' => Produit::select(['id', 'designation', 'qte_stock', 'user-id'])
                ->orderBy('designation')
                ->get(),
            'consommable' => $consommable,
            'consommables' => $consommables,
            'patient' => $patient,
            'user_id' => auth()->user()->id
        ]);
    }

    /**
     * Autocomplete for products
     */
    public function Autocomplete(Request $request)
    {
        $query = $request->input('query');

        $datas = Produit::select('designation')
            ->where('designation', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        $results = $datas->pluck('designation');
        return response()->json($results);
    }

    /**
     * Store fiche consommable
     */
    public function FicheConsommableStore(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'numeric'],
            'patient_id' => ['required', 'numeric', 'exists:patients,id'],
            'consommable' => ['required', 'string'],
            'jour' => ['nullable', 'numeric', 'min:0'],
            'nuit' => ['nullable', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
        ]); 

        // Validation personnalisée : au moins une quantité doit être >= 1
        $jour = (int) $request->input('jour', 0);
        $nuit = (int) $request->input('nuit', 0);
        if ($jour + $nuit < 1) {
            return back()
                ->withInput()
                ->with('error', 'Veuillez saisir au moins une quantité (jour ou nuit) supérieure ou égale à 1.');
        }

        try {
            // Créer la fiche consommable
            FicheConsommable::create([
                'user_id' => auth()->id(),
                'patient_id' => $request->input('patient_id'),
                'consommable' => $request->input('consommable'),
                'jour' => $request->input('jour'),
                'nuit' => $request->input('nuit'),
                'date' => $request->input('date'),
            ]);

            Cache::tags(['patients'])->flush();

            return back()->with('success', 'La fiche consommable a été enregistrée avec succès.');

        } catch (\Exception $e) {
            Log::error('Fiche Consommable Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement de la fiche consommable.');
        }

    }

     /**
     * Update fiche consommable
     */
    public function FicheConsommableUpdate(Request $request, FicheConsommable $consommable)
    {
        $request->validate([
            'consommable' => ['required', 'string'],
            'jour' => ['nullable', 'numeric', 'min:0'],
            'nuit' => ['nullable', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
        ]);

        // Validation personnalisée : au moins une quantité doit être >= 1
        $jour = (int) $request->input('jour', 0);
        $nuit = (int) $request->input('nuit', 0);
        if ($jour + $nuit < 1) {
            return back()->with('error', 'Veuillez saisir au moins une quantité (jour ou nuit) supérieure ou égale à 1.');
        }

        try {
            $consommable->update([
                'consommable' => $request->input('consommable'),
                'jour' => $request->input('jour'),
                'nuit' => $request->input('nuit'),
                'date' => $request->input('date'),
            ]);

            Cache::tags(['patients'])->flush();

            return back()->with('success', 'La fiche consommable a été modifiée avec succès.');

        } catch (\Exception $e) {
            Log::error('Fiche Consommable Update Error: ' . $e->getMessage());

            return back()->with('error', 'Erreur lors de la modification de la fiche consommable.');
        }
    }

    /**
     * Delete fiche consommable
     */
    public function FicheConsommableDestroy(FicheConsommable $consommable)
    {
        try {
            $consommable->delete();

            Cache::tags(['patients'])->flush();

            return back()->with('success', 'La fiche consommable a été supprimée avec succès.');

        } catch (\Exception $e) {
            Log::error('Fiche Consommable Delete Error: ' . $e->getMessage());

            return back()->with('error', 'Erreur lors de la suppression de la fiche consommable.');
        }
    }

    /**
     * Store soins infirmier
     */
    public function SoinsInfirmierStore(Request $request)
    {
        try {
            SoinsInfirmier::create([
                'user_id' => auth()->id(),
                'patient_id' => $request->input('patient_id'),
                'date' => $request->input('date'),
                'observation' => $request->input('observation'),
                'patient_externe' => $request->input('patient_externe'),
            ]);

            Cache::tags(['patients'])->flush();

            // Flash::info('Votre enregistrement a bien été pris en compte');
            // return back();

            return redirect()
            ->back()
            ->with('info', 'Votre enregistrement a bien été pris en compte');


        } catch (\Exception $e) {
            Log::error('Soins Infirmier Store Error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'enregistrement');
        }
    }

    /**
     * Display lettres index
     */
    public function index_sortie()
    {
        $lettres = Lettre::all();
        return view('admin.lettres.index', compact('lettres'));
    }

    /**
     * Soft-delete a patient.
     * Blocked if the patient has unsettled (non soldée) invoices.
     */
    public function destroy(Patient $patient)
    {
        $this->authorize('delete', $patient);

        // Block deletion if there are outstanding invoices
        $unpaidCount = $patient->facture_consultations()->where('reste', '>', 0)->count();
        if ($unpaidCount > 0) {
            return redirect()->back()
                ->with('error', "Impossible de supprimer ce patient : il a {$unpaidCount} facture(s) non soldée(s). Soldez toutes les factures avant de supprimer.");
        }

        try {
            DB::transaction(function () use ($patient) {
                // Soft delete — the record stays in the DB with deleted_at set
                $patient->delete();
            });

            Cache::tags(['patients'])->flush();

            return redirect()->route('patients.index')
                ->with('success', "Le dossier du patient a été archivé avec succès.");

        } catch (\Exception $e) {
            Log::error('Patient Delete Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Permanently delete a patient (admin only).
     * This is irreversible — use only when you are certain all related data can be discarded.
     */
    public function forceDestroy(int $id)
    {
        $this->authorize('forceDelete', Patient::class);

        // withTrashed() so we can force-delete already soft-deleted patients too
        $patient = Patient::withTrashed()->findOrFail($id);

        try {
            DB::transaction(function () use ($patient) {
                // Delete all related invoices first to avoid orphan records
                $patient->facture_consultations()->forceDelete();
                $patient->forceDelete();
            });

            Cache::tags(['patients'])->flush();

            return redirect()->route('patients.index')
                ->with('success', "Le dossier du patient a été définitivement supprimé.");

        } catch (\Exception $e) {
            Log::error('Patient Force Delete Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression définitive.');
        }
    }

    /**
     * Restore a soft-deleted patient (admin only).
     */
    public function restore(int $id)
    {
        $this->authorize('restore', Patient::class);

        $patient = Patient::withTrashed()->findOrFail($id);

        $patient->restore();

        Cache::tags(['patients'])->flush();

        return redirect()->route('patients.index')
            ->with('success', "Le dossier du patient a été restauré avec succès.");
    }

    /**
     * Helper: Build mode paiement info
     */
    private function buildModePaiementInfo(Request $request)
    {
        if ($request->input('mode_paiement') === 'chèque') {
            return collect([
                $request->input('num_cheque'),
                $request->input('emetteur_cheque'),
                $request->input('banque_cheque')
            ])->filter()->implode(' // ');
        }
        
        if ($request->input('mode_paiement') === 'bon de prise en charge') {
            return $request->input('emetteur_bpc');
        }
        
        return '';
    }
}