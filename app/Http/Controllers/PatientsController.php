<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationAnesthesiste;
use App\Models\Dossier;
use App\Models\FactureConsultation;
use App\Models\FactureActe;
use App\Models\FactureExamen;
use App\Models\FactureLigne;
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
use App\Services\DossierNumberService;
use App\Services\FactureService;


use App\Services\PdfService;

class PatientsController extends Controller
{
    protected FactureService $factureService;

    public function __construct(FactureService $factureService) {
        $this->factureService = $factureService ;
    }

    /**
     * Display a listing of patients
     */
    public function index(Request $request)
    {
        $this->authorize('viewList', Patient::class);
        
        $name = $request->input('name');
        $perPage = (int) $request->input('per_page', 10);
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
                    'created_at',
                    'motif',
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for creating a new patient
     */


    public function create(User $user)
    {
        $this->authorize('create', Patient::class);
        
   
        $users = cache::tags(['users'])->remember('users.roles.2_4', 30, function () {
            return User::whereIn('role_id', [2,4])
                ->select('id', 'name', 'prenom', 'specialite','role_id')
                ->orderBy('specialite')
                ->orderBy('name')
                ->get()
                ->groupBy(function ($user) {
                    return $user->role_id == 4 ? 'Infirmier' : $user->specialite;
                });
});

        
        return view('admin.patients.create', compact('users'));
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created patient in storage
     */
    public function store(Request $request)
    {
        $this->authorize('create', Patient::class);

        $request->validate([
            'name'             => 'required|string|max:255',
            'prenom'           => 'nullable|string|max:255',
            'mode_paiement'    => 'required|string',
            'assurance'        => 'nullable|string|max:255',
            'motif'            => 'required|string',
            'details_motif'    => 'required|string',
            'montant'          => 'required|numeric|min:0',
            'avance'           => 'required|numeric|min:0',
            'devise'           => 'required|string|in:XAF,EUR,DOLLAR,GBP',
            'taux_conversion'  => 'required_unless:devise,XAF|nullable|numeric|min:1',
            'montant_devise'   => 'required_unless:devise,XAF|nullable|numeric|min:0',
            'demarcheur'       => 'nullable|string',
            'numero_assurance' => 'required_with:assurance|nullable|string',
            'prise_en_charge'  => 'required_with:assurance|numeric|between:0,100',
            'num_cheque'       => 'required_if:mode_paiement,chèque',
            'emetteur_cheque'  => 'required_if:mode_paiement,chèque',
            'banque_cheque'    => 'required_if:mode_paiement,chèque',
            'emetteur_bpc'     => 'required_if:mode_paiement,bon de prise en charge',
            'assigne_a'        => 'required|exists:users,id', // l'ID du medecin ou infirmiere
        ]);

        $modePaiementInfo = $this->buildModePaiementInfo($request);

        try {
            $patient = DB::transaction(function () use ($request, $modePaiementInfo) {
                $montant = $request->input('montant');
                $priseEnCharge = $request->input('prise_en_charge', 0);
                $avance = $request->input('avance');
                //$devise  = $request->input('devise', 'XAF');
               // $taux    = $request->input('taux_conversion', 1);
                //$remis   = $request->input('montant_devise', 0);
                $avance = $request->input('avance');

                // Sécurité côté serveur : l'avance ne peut pas dépasser la dette
                $avance = min($avance, $montant);

                $assurec    = $this->factureService->calculAssurec($montant, $priseEnCharge);
                $assurancec = $this->factureService->calculAssurancec($montant, $priseEnCharge);
                $reste      = $this->factureService->calculReste($assurec, $avance);

               
                $assigneId   = (int) $request->input('assigne_a');
                $assigneUser = User::select('id', 'role_id')->find($assigneId);

                
                $medecinId  = ($assigneUser && $assigneUser->role_id == 2) ? $assigneId : null;
                $infirmierId = ($assigneUser && $assigneUser->role_id == 4) ? $assigneId : null;

                $patient = Patient::create([
                   'numero_dossier'        => app(DossierNumberService::class)->generate(),
                    'name'                  => $request->input('name'),
                    'prenom'                => $request->input('prenom'),
                    'montant'               => $montant,
                    'assurance'             => $request->input('assurance'),
                    'avance'                => $avance,
                    'motif'                 => $request->input('motif'),
                    'mode_paiement'         => $request->input('mode_paiement'),
                    'mode_paiement_info_sup'=> $modePaiementInfo,
                    'details_motif'         => $request->input('details_motif'),
                    'numero_assurance'      => $request->input('numero_assurance'),
                    'prise_en_charge'       => $priseEnCharge,
                    'assurec'               => $assurec,
                    'assurancec'            => $assurancec,
                    'reste'                 => $reste,
                    'demarcheur'            => $request->input('demarcheur'),
                    'date_insertion'        => now()->toDateString(),
                    'user_id'               => Auth::id(),
                    'medecin_r'             => $medecinId,
                    'infirmier'             => $infirmierId,
                   
                   
                ]);

                Log::info('Patient créé et assigné a', [
                    'patient_id' => $patient->id,
                    'numero_dossier'  => $patient->numero_dossier,
                    'medecin_assigne' => getNomUser(is_numeric($patient->medecin_r)),
                    'infirmier_assigne' =>$patient->infirmier,
                    'motif'           => $patient->motif,  
                    
                    'created_by' => Auth::user()->name . ' ' . Auth::user()->prenom,
                ]);

                return $patient;
            });

            switch ($patient->motif) {
                case 'Consultation':
                    Cache::tags(['patients', 'consultations'])->flush();
                    break;
                case 'Examen':
                    Cache::tags(['patients', 'examen'])->flush();
                    break;
                case 'Acte':
                    Cache::tags(['patients', 'acte'])->flush();
                    break;
                default:
                    Cache::tags(['patients'])->flush();
                    break;
            }


            return redirect()->route('patients.index')
                ->with('success', "Le patient {$patient->name} a été ajouté avec succès !");

        } catch (\Exception $e) {
            Log::error('Patient Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du patient');
        }
    }
    //////////////////////////////////////////////////////////////////////////////////////////

    // =========================================================================
    // GÉNÉRATION DE FACTURE
    // =========================================================================

    /**
     * Génère la bonne facture selon le motif du patient.
     *
     * POURQUOI cette architecture ?
     *   - generateFacture() = chef d'orchestre UNIQUEMENT (dispatch + redirect)
     *   - Chaque type de facture a sa propre méthode privée → lisibilité + maintenabilité
     *   - dispatchFacture() du FactureService centralise la logique de redirection
     *   - La table paiements est alimentée si avance > 0 (évite les doublons d'historique)
     *
     * Route : GET /patients/{id}/generer-facture
     */
    public function generateFacture(int $id): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('print', Patient::class);

        $patient = Patient::select([
            'id', 'numero_dossier', 'name', 'prenom', 'montant',
            'avance', 'reste', 'assurec', 'assurancec',
            'mode_paiement', 'mode_paiement_info_sup',
            'motif', 'details_motif', 'demarcheur', 'medecin_r',
            'infirmier', 'prise_en_charge', 'assurance', 'numero_assurance',
        ])->findOrFail($id);

        $motif = trim($patient->motif ?? '');

        try {
            // Délégation à la bonne méthode privée selon le motif
            $facture = match (true) {
                in_array($motif, ['Acte', 'Autre acte'], true)     => $this->creerFactureActe($patient),
                in_array($motif, ['Examen', 'Autre examen'], true) => $this->creerFactureExamen($patient),
                default                                             => $this->creerFactureConsultation($patient),
            };

            // dispatchFacture() centralise la logique de redirection — on ne la duplique pas ici
            $dispatch = $this->factureService->dispatchFacture($patient);

            return redirect()
                ->route($dispatch['route'], $dispatch['params'])
                ->with('success', 'Facture n° ' . $facture->numero . ' générée avec succès !');

        } catch (\Exception $e) {
            Log::error('generateFacture Error', [
                'patient_id' => $id,
                'motif'      => $motif,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la génération de la facture. Veuillez réessayer.');
        }
    }

    // =========================================================================
    // MÉTHODES PRIVÉES DE CRÉATION (une par type de facture)
    // =========================================================================

    /**
     * Crée une FactureActe avec sa ligne initiale et son paiement initial.
     *
     * POURQUOI méthode privée ?
     *   Si la logique de création d'un acte évolue (champ supplémentaire, règle métier),
     *   on ne touche qu'ici sans risquer de casser les deux autres types.
     *
     * POURQUOI enregistrerPaiement() et pas HistoriqueFacture::create() ?
     *   La table `paiements` est la source de vérité pour tous les encaissements.
     *   HistoriqueFacture est un legacy consultation — on ne l'étend pas aux nouveaux types.
     */
    private function creerFactureActe(Patient $patient): FactureActe
    {
        return DB::transaction(function () use ($patient): FactureActe {

            /** @var FactureActe $facture */
            $facture = FactureActe::create([
                'numero'                 => $this->factureService->genererNumero('ACT', 'factures_acte'),
                'patient_id'             => $patient->id,
                'user_id'                => auth()->id(),
                // patient_name et patient_numero_dossier sont remplis auto par snapshotPatientName() dans le trait HasFactureMontants
                
                'montant_total'          => $patient->montant,
                //'avance'                 => $patient->avance,
                'assurec'                => $patient->assurec,
                'assurancec'             => $patient->assurancec,
                'reste'                  => $patient->montant,
                // statut calculé auto par computeStatut() dans HasFactureMontants
                'assurance'              => (bool) $patient->assurance,
                'numero_assurance'       => $patient->numero_assurance,
                'prise_en_charge'        => $patient->prise_en_charge ?? 0,
                'mode_paiement'          => $patient->mode_paiement,
                'mode_paiement_info_sup' => $patient->mode_paiement_info_sup,
                'notes'                  => $patient->details_motif,
            ]);

            // Ligne initiale — représente l'acte de base du patient
            FactureLigne::create([
                //'facture_consultation_id' => null,
                //'facture_examen_id'       => null,
                'facture_acte_id'         => $facture->id,
                //'facture_chambre_id'      => null,
                'facture_type'            => 'acte',
                'type_acte'               => 'soin_infirmier',
                'libelle'                 => $patient->details_motif ?? 'Acte médical',
                'montant'                 => $patient->montant,
                'quantite'                => 1,
                'infirmiere'              => $this->getNomUser(is_numeric($patient->infirmier ?? null) ? (int) $patient->infirmier : null),// $this->resolveInfirmier($patient->infirmier),
                'medecin'                 => $this->getNomUser(is_numeric($patient->medecin_r ?? null) ? (int) $patient->medecin_r : null),//$this->resolveMedecin($patient->medecin_r),
                'ordre'                   => 1,
            ]);

           
            // Pourquoi ? Un paiement de 0 FCFA n'a pas de sens métier et pollue l'historique.
            if ($patient->avance > 0) {
                $this->factureService->enregistrerPaiement(
                    facture:      $facture,
                    factureType:  'facture_acte',
                    montant:      (float) $patient->avance,
                    modePaiement: $patient->mode_paiement ?? 'espèces',
                    extra: [
                        'mode_paiement_info_sup' => $patient->mode_paiement_info_sup,
                        'notes'                  => 'Avance initiale à la création',
                    ]
                );
            }

            return $facture;
        });
    }

    /**
     * Crée une FactureExamen avec sa ligne initiale et son paiement initial.
     */
    private function creerFactureExamen(Patient $patient): FactureExamen
    {
        
        $this->authorize('view', User::class);

        return DB::transaction(function () use ($patient): FactureExamen {

            /** @var FactureExamen $facture */
            $facture = FactureExamen::create([
                'numero'                 => $this->factureService->genererNumero('EXA', 'factures_examen'),
                'patient_id'             => $patient->id,
                'user_id'                => auth()->id(),
                'montant_total'          => $patient->montant,
                //'avance'                 => $patient->avance,
                'assurec'                => $patient->assurec,
                'assurancec'             => $patient->assurancec,
                'reste'                  => $patient->montant, //$patient->reste,
                'assurance'              => (bool) $patient->assurance,
                'numero_assurance'       => $patient->numero_assurance,
                'prise_en_charge'        => $patient->prise_en_charge ?? 0,
                'mode_paiement'          => $patient->mode_paiement,
                'mode_paiement_info_sup' => $patient->mode_paiement_info_sup,
                'notes'                  => $patient->details_motif,
            ]);

            // Ligne initiale — représente l'examen de base du patient
            FactureLigne::create([
                'facture_examen_id' => $facture->id,
                'facture_type'      => 'examen',
                'type_sous'         => 'laboratoire',
                'libelle'           => $patient->details_motif ?? 'Examen médical',
                'montant'           => $patient->montant,
                'quantite'          => 1,
                'technicien'        => $this->getNomUser(is_numeric($patient->medecin_r ?? null) ? (int) $patient->medecin_r : null),
                'ordre'             => 1,
            ]);

            // Enregistrement de l'avance dans paiements si > 0
            if ($patient->avance > 0) {
                $this->factureService->enregistrerPaiement(
                    facture:      $facture,
                    factureType:  'facture_examen',
                    montant:      (float) $patient->avance,
                    modePaiement: $patient->mode_paiement ?? 'espèces',
                    extra: [
                        'mode_paiement_info_sup' => $patient->mode_paiement_info_sup,
                        'notes'                  => 'Avance initiale à la création',
                    ]
                );
            }

            return $facture;
        });
    }

    /**
     * Crée une FactureConsultation avec son historique legacy.
     *
     * POURQUOI HistoriqueFacture ici et pas Paiement ?
     *   FactureConsultation est un modèle LEGACY — la relation historiques()
     *   existe déjà et est utilisée partout dans les vues et rapports.
     *   On ne migre pas ce comportement pour ne pas casser l'existant.
     *   Les nouvelles factures (acte, examen) utilisent directement la table paiements.
     */
    private function creerFactureConsultation(Patient $patient): FactureConsultation
    {
        return DB::transaction(function () use ($patient): FactureConsultation {

            // Anti-doublon : si une facture consultation existe déjà pour ce patient/numero
            // (cas double-click / refresh), on la renvoie.
            $factureExistante = FactureConsultation::where('patient_id', $patient->id)
                ->where('numero', $patient->numero_dossier)
                ->first();

            if ($factureExistante) {
                return $factureExistante;
            }

            $statutFacture = ((float) ($patient->reste ?? 0)) == 0 ? 'Soldée' : 'Non soldée';

            /** @var FactureConsultation $facture */
            $facture = FactureConsultation::create([
                'numero'                 => $patient->numero_dossier,
                'patient_id'             => $patient->id,
                'patient_name'          => trim(($patient->name ?? '') . ' ' . ($patient->prenom ?? '')),
                'user_id'                => auth()->id(),

                // Version “generate_consultation” (champs legacy)
                'montant'                => (float) ($patient->montant ?? 0),
                'avance'                 => (float) ($patient->avance ?? 0),
                'assurec'                => (float) ($patient->assurec ?? 0),
                'assurancec'             => (float) ($patient->assurancec ?? 0),
                'reste'                  => (float) ($patient->reste ?? 0),

                'assurance'              => (bool) ($patient->assurance ?? false),

                // Paiement / snapshot
                'mode_paiement'          => $patient->mode_paiement ?? 'espèce',
                'mode_paiement_info_sup' => $patient->mode_paiement_info_sup ?? '',

                // Champs spécifiques à FactureConsultation
                'motif'                  => $patient->motif ?? 'Consultation',
                'details_motif'          => $patient->details_motif ?? '',
                'demarcheur'             => $patient->demarcheur,

                // Robustesse : convertir medecin_r (ID ou texte) vers un snapshot texte
                'medecin_r'              => $this->resolveMedecin($patient->medecin_r, 'Dr. '),

                'date_insertion'         => now()->toDateString(),
                'statut'                  => $statutFacture,
            ]);

            // Legacy : historique lié via relation Eloquent
            $facture->historiques()->create([
                'reste'         => (float) $facture->reste,
                'montant'       => (float) $facture->montant,
                'percu'         => (float) $facture->avance,
                'assurec'       => (float) $facture->assurec,
                'mode_paiement' => $facture->mode_paiement ?? 'espèces',
            ]);

            return $facture;
        });
    }

    // =========================================================================
    // HELPER PRIVÉ
    // =========================================================================

    /**
     * Récupère le nom complet d'un utilisateur par son ID.
     *
     * POURQUOI ?
     *   On stocke un snapshot du nom au moment de la création de la facture.
     *   Si l'utilisateur est renommé ou supprimé plus tard, la facture reste correcte.
     *
     * @param  int|null  $userId   ID de l'utilisateur (peut être null)
     * @param  string    $prefix   Préfixe à ajouter (ex: 'Dr. ')
     */
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
    
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * But : convertir un ID infirmier en nom complet.

        * Logique : 
            *cherche par ID dans users, 
            *retourne name + prenom.

        * Retour : une chaîne ou null.
        */
    private function getNomInfirmier(?int $userId): ?string
    {
        if (!$userId) return null;
        $user = User::select('name', 'prenom')->find($userId);
        return $user ? trim($user->name . ' ' . $user->prenom) : null;
    }

///////////////////////////////////////////////////////

        /**    But : gérer IDs et chaînes.

        *Logique :

            *Si valeur numérique → cherche par ID.

            *Si valeur texte → retourne directement la chaîne.

        *Retour : une chaîne (nom complet).
            **/

        private function resolveInfirmier($infirmierValue): ?string
        {
            if (!$infirmierValue) {
                return null;
            }

            if (is_numeric($infirmierValue)) {
                $user = \App\Models\User::select('name', 'prenom')->find((int)$infirmierValue);
                return $user ? trim($user->name . ' ' . $user->prenom) : null;
            }

            // Si c’est déjà un nom texte
            return $infirmierValue;
        }


        /**
        * But : retrouver un médecin à partir d’un nom en texte libre (ex. "Dr. BOGNY Patrick").

        *Logique : 
            *nettoie le texte,
            * découpe nom/prénom, 
            *puis cherche dans la table users (role_id = 2).
        *Retour : un objet User ou null.
    
        */

    private function findMedecinByName($medecinName)
    {
        // Nettoyer le nom (enlever "Dr.", espaces multiples, etc.)
        $cleanName = preg_replace('/^(Dr\.?|Docteur)\s*/i', '', trim($medecinName));
        $parts = preg_split('/\s+/', $cleanName, -1, PREG_SPLIT_NO_EMPTY);

        if (empty($parts)) {
            return null;
        }

        // Stratégie de recherche progressive
        $query = User::where('role_id', 2);

        if (count($parts) === 1) {
            // Un seul mot : chercher dans nom OU prénom
            $query->where(function($q) use ($parts) {
                $q->where('name', 'LIKE', "%{$parts[0]}%")
                ->orWhere('prenom', 'LIKE', "%{$parts[0]}%");
            });
        } else if (count($parts) >= 2) {
            // Deux mots ou plus : nom ET prénom
            $nom = $parts[0];
            $prenom = implode(' ', array_slice($parts, 1));
            
            $query->where(function($q) use ($nom, $prenom) {
                // Essayer nom/prénom dans l'ordre
                $q->where(function($subQ) use ($nom, $prenom) {
                    $subQ->where('name', 'LIKE', "%{$nom}%")
                        ->where('prenom', 'LIKE', "%{$prenom}%");
                })
                // Ou prénom/nom inversé
                ->orWhere(function($subQ) use ($nom, $prenom) {
                    $subQ->where('name', 'LIKE', "%{$prenom}%")
                        ->where('prenom', 'LIKE', "%{$nom}%");
                });
            });
        }

        $medecin = $query->first();

        // Log pour debug si nécessaire
        if (!$medecin) {
            Log::debug('Médecin non trouvé', [
                'recherche' => $medecinName,
                'parts' => $parts,
                'medecins_disponibles' => User::where('role_id', 2)->pluck('name', 'id'),
            ]);
        }

        return $medecin;
    }

        /***
         
        But : gérer deux cas : soit medecin_r est un ID, soit c’est un nom.

        Logique :

            Si valeur numérique → cherche par ID dans users.

            Si valeur texte → appelle findMedecinByName() pour tenter de retrouver l’ID, sinon retourne le texte tel quel.

        Retour : une chaîne formatée ("Dr. Nom Prénom") ou la valeur brute.
        
        **/

    private function resolveMedecin($medecinValue, string $prefix = 'Dr. '): ?string
    {
        if (!$medecinValue) {
            return null;
        }

        // Cas 1 : valeur numérique → chercher par ID
        if (is_numeric($medecinValue)) {
            $user = \App\Models\User::select('name', 'prenom')->find((int)$medecinValue);
            return $user ? $prefix . trim($user->name . ' ' . $user->prenom) : null;
        }

        // Cas 2 : valeur texte → chercher par nom
        $medecin = $this->findMedecinByName($medecinValue);
        return $medecin ? $prefix . trim($medecin->name . ' ' . $medecin->prenom) : $medecinValue;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
        $labExamensCount = $patient->examens_laboratoire()->count(); // bust cache when new bon created

        $latestConsultationTs = $latestConsultation ? strtotime($latestConsultation) : 'none';
        $latestConsultationAnesTs = $latestConsultationAnes ? strtotime($latestConsultationAnes) : 'none';
        $latestParametreTs = $latestParametre ? strtotime($latestParametre) : 'none';
        $latestCrboTs = $latestCrbo ? strtotime($latestCrbo) : 'none';

        // Create a cache key including compte rendu bloc operatoire timestamp
        $cacheKey = sprintf(
            "patient_show_%s_%s_%s_%s_%s_%s_%s",
            $patient->id,
            $latestConsultationTs,
            $latestConsultationAnesTs,
            $latestParametreTs,
            $latestCrboTs,
            $examensCount,
            $labExamensCount
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
                'examens_laboratoire' => $patient->examens_laboratoire()
                    ->with('laborantin:id,name,prenom')
                    ->select([
                        'id', 'patient_id', 'user_id', 'numero_bon', 'statut',
                        'date_prelevement', 'date_validation', 'created_at',
                    ])
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
            'examens_laboratoire' => $data['examens_laboratoire'],
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
            'infirmier' => 'nullable|string',
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
                    'infirmier' => $request->input('infirmier'),
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
     * Update patient informations 
     */
    public function motifMontantUpdate(Request $request, $id)
    {
        $this->authorize('update', Patient::class);
        
        $request->validate([
            'motif' => 'required|string',
            'name' => 'required|string',
            'prenom' => 'required|string',
            'medecin_r' => 'required|string',
            'infirmier'  =>  'nullable|string',
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
                    'assurancec' => FactureConsultation::calculAssurancec($montant, $priseEnCharge),
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
                'demarcheur', 'medecin_r'
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
                ]);

                return $facture;
            });

            Cache::tags(['factures', 'patients'])->flush();

             //  On délègue la redirection au service
            $route = $factureService->dispatchFacture($patient);
            return redirect($route)->with('success', 'Facture générée avec succès !');

            //return redirect()->route('factures.consultation')
              //  ->with('success', 'Facture n° ' . $facture->id . ' générée avec succès!');

        } catch (\Exception $e) {
            Log::error('Generate  Error: ' . $e->getMessage(), [
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

    public function searchPatientsExamen(Request $request): \Illuminate\Http\JsonResponse
{
    $this->authorize('view', User::class);
    
    $search = trim((string) $request->input('q', ''));
    
    if (mb_strlen($search) < 2) {
        return response()->json(['results' => []]);
    }
    
    $patients = Patient::select('id', 'numero_dossier', 'name', 'prenom')
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('numero_dossier', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->limit(30)
        ->get()
        ->map(fn($p) => [
            'id' => $p->id,
            'text' => "CMCU-{$p->numero_dossier} | {$p->name} {$p->prenom}",
            'numero_dossier' => $p->numero_dossier,
            'name' => $p->name,
            'prenom' => $p->prenom,
        ]);
    
    return response()->json(['results' => $patients]);
}


}
