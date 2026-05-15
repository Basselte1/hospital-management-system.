<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\ConsultationAnesthesiste;
use App\Models\ConsultationSuivi;
use App\Models\PatientVisit;
use App\Models\Premedication;
use App\Models\Prescription;
use App\Models\PrescriptionMedicale;
use App\Models\CompteRenduBlocOperatoire;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * RapportController — Rapports d'activité par rôle
 *
 * PRINCIPE :
 *   Chaque rôle a ses métriques déclarées dans ROLE_REPORT_CONFIG.
 *   Ajouter un rôle = ajouter une entrée ici, rien d'autre.
 */
class RapportController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────
    // Configuration des rôles : métriques visibles par rôle
    // ──────────────────────────────────────────────────────────────────────

    private const ROLE_REPORT_CONFIG = [
        1 => [ // Administrateur
            'label'   => 'Administrateur',
            'color'   => 'secondary',
            'icon'    => 'fa-shield-alt',
            'metrics' => [
                'consultations', 'suivis', 'consultations_anesthesiste',
                'prescriptions', 'comptes_rendus', 'soins', 'premedications',
                'visites', 'nouveaux_patients',
                'factures', 'montant_facture', 'encaisse', 'reste',
            ],
        ],
        2 => [ // Médecin
            'label'   => 'Médecin',
            'color'   => 'primary',
            'icon'    => 'fa-user-md',
            'metrics' => [
                'consultations', 'suivis', 'consultations_anesthesiste',
                'prescriptions', 'prescriptions_medicales', 'comptes_rendus',
                'nouveaux_patients',
            ],
        ],
        3 => [ // Gestionnaire
            'label'   => 'Gestionnaire',
            'color'   => 'warning',
            'icon'    => 'fa-chart-line',
            'metrics' => [
                'visites', 'nouveaux_patients',
                'factures', 'montant_facture', 'encaisse', 'reste', 'assurance',
            ],
        ],
        4 => [ // Infirmier(e)
            'label'   => 'Infirmier(e)',
            'color'   => 'info',
            'icon'    => 'fa-syringe',
            'metrics' => ['soins', 'premedications', 'consommables', 'surveillances', 'nouveaux_patients'],
        ],
        5 => [ // Logistique
            'label'   => 'Logistique',
            'color'   => 'dark',
            'icon'    => 'fa-boxes',
            'metrics' => ['consommables', 'visites'],
        ],
        6 => [ // Secrétaire
            'label'   => 'Secrétaire',
            'color'   => 'success',
            'icon'    => 'fa-calendar-check',
            'metrics' => ['visites', 'nouveaux_patients', 'dossiers_crees', 'events'],
        ],
        7 => [ // Pharmacien
            'label'   => 'Pharmacien',
            'color'   => 'success',
            'icon'    => 'fa-pills',
            'metrics' => ['prescriptions_medicales', 'consommables'],
        ],
        9 => [ // Caisse
            'label'   => 'Caisse',
            'color'   => 'danger',
            'icon'    => 'fa-cash-register',
            'metrics' => ['factures', 'montant_facture', 'encaisse', 'reste', 'assurance', 'visites'],
        ],
    ];

    // ──────────────────────────────────────────────────────────────────────
    // Labels et types des métriques (source unique pour les vues)
    // ──────────────────────────────────────────────────────────────────────

    private const METRIC_LABELS = [
        'consultations'              => ['label' => 'Consultations',            'icon' => 'fa-stethoscope',   'type' => 'count', 'color' => 'primary'],
        'suivis'                     => ['label' => 'Consultations de suivi',   'icon' => 'fa-notes-medical', 'type' => 'count', 'color' => 'primary'],
        'consultations_anesthesiste' => ['label' => 'Consultations anesthésie', 'icon' => 'fa-procedures',    'type' => 'count', 'color' => 'info'],
        'prescriptions'              => ['label' => "Prescriptions d'examens",  'icon' => 'fa-flask',         'type' => 'count', 'color' => 'secondary'],
        'prescriptions_medicales'    => ['label' => 'Prescriptions médicales',  'icon' => 'fa-pills',         'type' => 'count', 'color' => 'secondary'],
        'comptes_rendus'             => ['label' => 'CR blocs opératoires',     'icon' => 'fa-file-medical',  'type' => 'count', 'color' => 'warning'],
        'soins'                      => ['label' => 'Soins infirmiers',         'icon' => 'fa-heart-pulse',   'type' => 'count', 'color' => 'info'],
        'premedications'             => ['label' => 'Prémédications',           'icon' => 'fa-syringe',       'type' => 'count', 'color' => 'info'],
        'consommables'               => ['label' => 'Fiches consommables',      'icon' => 'fa-boxes',         'type' => 'count', 'color' => 'secondary'],
        'surveillances'              => ['label' => 'Surveillances post-op.',   'icon' => 'fa-heart',         'type' => 'count', 'color' => 'danger'],
        'visites'                    => ['label' => 'Visites enregistrées',     'icon' => 'fa-hospital-user', 'type' => 'count', 'color' => 'success'],
        'nouveaux_patients'          => ['label' => 'Nouveaux patients',        'icon' => 'fa-user-plus',     'type' => 'count', 'color' => 'success'],
        'dossiers_crees'             => ['label' => 'Dossiers créés',           'icon' => 'fa-folder-plus',   'type' => 'count', 'color' => 'success'],
        'events'                     => ['label' => 'RDV / Événements',         'icon' => 'fa-calendar',      'type' => 'count', 'color' => 'warning'],
        'factures'                   => ['label' => 'Factures émises',          'icon' => 'fa-file-invoice',  'type' => 'count', 'color' => 'warning'],
        'montant_facture'            => ['label' => 'Total facturé',            'icon' => 'fa-money-bill',    'type' => 'money', 'color' => 'warning'],
        'encaisse'                   => ['label' => 'Encaissé',                 'icon' => 'fa-cash-register', 'type' => 'money', 'color' => 'success'],
        'reste'                      => ['label' => 'Reste à recouvrer',        'icon' => 'fa-clock',         'type' => 'money', 'color' => 'danger'],
        'assurance'                  => ['label' => 'Part assurance',           'icon' => 'fa-shield-alt',    'type' => 'money', 'color' => 'info'],
    ];

    // ──────────────────────────────────────────────────────────────────────
    // Page principale — liste des rapports par utilisateur
    // ──────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        // 1. Période
        $periode   = $request->input('periode', 'mois');
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->input('date_fin',   now()->format('Y-m-d'));

        // Raccourcis de période (remplacent Du/Au)
        if ($periode === 'jour') {
            $dateDebut = $dateFin = now()->format('Y-m-d');
        } elseif ($periode === 'semaine') {
            $dateDebut = now()->startOfWeek()->format('Y-m-d');
            $dateFin   = now()->format('Y-m-d');
        } elseif ($periode === 'mois') {
            $dateDebut = now()->startOfMonth()->format('Y-m-d');
            $dateFin   = now()->format('Y-m-d');
        }

        // Sécurité : la fin ne peut pas être avant le début
        if ($dateFin < $dateDebut) {
            $dateFin = $dateDebut;
        }

        $debut = Carbon::parse($dateDebut)->startOfDay();
        $fin   = Carbon::parse($dateFin)->endOfDay();

        // 2. Filtres utilisateur / rôle
        $userId = $request->input('user_id');
        $roleId = $request->input('role_id');

        // 3. Liste des utilisateurs pour les selects du formulaire
        $users = User::select('id', 'name', 'prenom', 'role_id')
            ->orderBy('role_id')
            ->orderBy('name')
            ->get();

        // 4. Préparer les données JSON pour le JS (filtre dynamique des selects)
        // IMPORTANT : on prépare ici, jamais dans @json() avec une closure
        $usersJson = $users->map(fn(User $u) => [
            'id'      => $u->id,
            'role_id' => $u->role_id,
            'label'   => trim($u->name . ' ' . ($u->prenom ?? '')),
        ])->values();

        // 5. Calcul des rapports filtrés
        $rapportUsers = $users
            ->when($userId, fn($c) => $c->where('id', $userId))
            ->when($roleId, fn($c) => $c->where('role_id', $roleId))
            ->map(fn($u) => $this->buildUserRapport($u, $debut, $fin))
            ->filter(fn($r) => $r['total_actes'] > 0 || $userId);

        // 6. Résumé global (admin uniquement)
        $resumeGlobal = null;
        if (auth()->user()->role_id == 1) {
            $resumeGlobal = $this->buildResumeGlobal($debut, $fin);
        }

        return view('admin.rapports.index', compact(
            'users',
            'usersJson',
            'rapportUsers',
            'resumeGlobal',
            'dateDebut',
            'dateFin',
            'periode',
            'userId',
            'roleId',
        ));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Page détail d'un utilisateur
    // ──────────────────────────────────────────────────────────────────────

    public function show(Request $request, User $user)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->input('date_fin',   now()->format('Y-m-d'));

        $debut = Carbon::parse($dateDebut)->startOfDay();
        $fin   = Carbon::parse($dateFin)->endOfDay();

        $rapport   = $this->buildUserRapport($user, $debut, $fin);
        $config    = self::ROLE_REPORT_CONFIG[$user->role_id] ?? null;
        $labels    = self::METRIC_LABELS;

        // Graphe jour par jour uniquement si la période <= 31 jours
        $evolution = [];
        if ($debut->diffInDays($fin) <= 31) {
            $evolution = $this->buildEvolutionJournaliere($user, $debut, $fin);
        }

        return view('admin.rapports.show', compact(
            'user', 'rapport', 'config', 'labels', 'evolution', 'dateDebut', 'dateFin'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Vue PDF / impression
    // ──────────────────────────────────────────────────────────────────────

    public function pdf(Request $request, User $user)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->input('date_fin',   now()->format('Y-m-d'));

        $debut = Carbon::parse($dateDebut)->startOfDay();
        $fin   = Carbon::parse($dateFin)->endOfDay();

        $rapport = $this->buildUserRapport($user, $debut, $fin);
        $config  = self::ROLE_REPORT_CONFIG[$user->role_id] ?? null;
        $labels  = self::METRIC_LABELS;

        // Vue HTML simple → impression navigateur (Ctrl+P ou bouton Imprimer)
        // Pour un vrai PDF via PdfService, remplacer le return par :
        // return PdfService::generate('admin.rapports.pdf', compact(...), "rapport_{$user->id}.pdf");
        return view('admin.rapports.pdf', compact('user', 'rapport', 'config', 'labels', 'dateDebut', 'dateFin'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Accesseurs statiques (utilisés dans les vues Blade via ::)
    // ──────────────────────────────────────────────────────────────────────

    public static function getRoleConfig(int $roleId): ?array
    {
        return self::ROLE_REPORT_CONFIG[$roleId] ?? null;
    }

    public static function getAllMetricLabels(): array
    {
        return self::METRIC_LABELS;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Construction du rapport d'un utilisateur
    // ──────────────────────────────────────────────────────────────────────

    private function buildUserRapport(User $user, Carbon $debut, Carbon $fin): array
    {
        $config = self::ROLE_REPORT_CONFIG[$user->role_id] ?? null;

        // Rôle non configuré → rapport vide
        if (! $config) {
            return ['user' => $user, 'config' => null, 'metrics' => [], 'total_actes' => 0, 'labels' => []];
        }

        $metrics    = [];
        $totalActes = 0;

        foreach ($config['metrics'] as $metricKey) {
            $valeur = $this->computeMetric($metricKey, $user->id, $debut, $fin);
            $metrics[$metricKey] = $valeur;

            // Les métriques financières (type = 'money') ne comptent pas dans le total actes
            $meta = self::METRIC_LABELS[$metricKey] ?? [];
            if (($meta['type'] ?? 'count') === 'count') {
                $totalActes += $valeur;
            }
        }

        return [
            'user'        => $user,
            'config'      => $config,
            'metrics'     => $metrics,
            'total_actes' => $totalActes,
            'labels'      => self::METRIC_LABELS,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    // Calcul d'une métrique individuelle
    // Ajouter une métrique = ajouter un case ici + dans METRIC_LABELS
    // ──────────────────────────────────────────────────────────────────────

    private function computeMetric(string $key, int $userId, Carbon $debut, Carbon $fin): int|float
    {
        return match ($key) {
            'consultations'              => Consultation::where('user_id', $userId)->whereBetween('created_at', [$debut, $fin])->count(),
            'suivis'                     => ConsultationSuivi::where('user_id', $userId)->whereBetween('created_at', [$debut, $fin])->count(),
            'consultations_anesthesiste' => ConsultationAnesthesiste::where('user_id', $userId)->whereBetween('created_at', [$debut, $fin])->count(),
            'prescriptions'              => Prescription::where('user_id', $userId)->whereBetween('created_at', [$debut, $fin])->count(),
            'prescriptions_medicales'    => PrescriptionMedicale::where('user_id', $userId)->whereBetween('date', [$debut->toDateString(), $fin->toDateString()])->count(),
            'comptes_rendus'             => CompteRenduBlocOperatoire::where('user_id', $userId)->whereBetween('created_at', [$debut, $fin])->count(),
            'soins'                      => $this->countTable('soins_infirmiers',               $userId, $debut, $fin),
            'premedications'             => Premedication::where('user_id', $userId)->whereBetween('created_at', [$debut, $fin])->count(),
            'consommables'               => $this->countTable('fiche_consommables',             $userId, $debut, $fin),
            'surveillances'              => $this->countTable('surveillance_post_anesthesiques', $userId, $debut, $fin),
            'visites'                    => PatientVisit::where('user_id', $userId)->whereBetween('created_at', [$debut, $fin])->count(),
            'nouveaux_patients'          => Patient::where('user_id', $userId)->whereBetween('created_at', [$debut, $fin])->count(),
            'dossiers_crees'             => $this->countTable('dossiers', $userId, $debut, $fin),
            'events'                     => $this->countTable('events',   $userId, $debut, $fin),
            'factures'                   => $this->aggregatFactures($userId, $debut, $fin, 'nb'),
            'montant_facture'            => $this->aggregatFactures($userId, $debut, $fin, 'montant'),
            'encaisse'                   => $this->aggregatFactures($userId, $debut, $fin, 'avance'),
            'reste'                      => $this->aggregatFactures($userId, $debut, $fin, 'reste'),
            'assurance'                  => $this->aggregatFactures($userId, $debut, $fin, 'assurance'),
            default                      => 0,
        };
    }

    // ──────────────────────────────────────────────────────────────────────
    // Helpers SQL
    // ──────────────────────────────────────────────────────────────────────

    /** Compte générique sur n'importe quelle table avec user_id + created_at */
    private function countTable(string $table, int $userId, Carbon $debut, Carbon $fin): int
    {
        return DB::table($table)
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$debut, $fin])
            ->count();
    }

    /** Agrégats financiers sur la table facture_consultations */
    private function aggregatFactures(int $userId, Carbon $debut, Carbon $fin, string $champ): int|float
    {
        $row = DB::table('facture_consultations')
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$debut, $fin])
            ->selectRaw('COUNT(*) as nb, SUM(montant) as montant, SUM(avance) as avance, SUM(reste) as reste, SUM(assurancec) as assurance')
            ->first();

        return $row?->{$champ} ?? 0;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Résumé global (admin uniquement)
    // ──────────────────────────────────────────────────────────────────────

    private function buildResumeGlobal(Carbon $debut, Carbon $fin): array
    {
        return [
            'total_consultations' => Consultation::whereBetween('created_at', [$debut, $fin])->count(),
            'total_visites'       => PatientVisit::whereBetween('created_at', [$debut, $fin])->count(),
            'total_patients'      => Patient::whereBetween('created_at', [$debut, $fin])->count(),
            'total_soins'         => DB::table('soins_infirmiers')->whereBetween('created_at', [$debut, $fin])->count(),
            'total_facture'       => DB::table('facture_consultations')->whereBetween('created_at', [$debut, $fin])->sum('montant'),
            'total_encaisse'      => DB::table('facture_consultations')->whereBetween('created_at', [$debut, $fin])->sum('avance'),
            'total_reste'         => DB::table('facture_consultations')->whereBetween('created_at', [$debut, $fin])->sum('reste'),
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    // Évolution journalière (données pour le mini-graphe Chart.js)
    // ──────────────────────────────────────────────────────────────────────

    private function buildEvolutionJournaliere(User $user, Carbon $debut, Carbon $fin): array
    {
        $config = self::ROLE_REPORT_CONFIG[$user->role_id] ?? null;
        if (! $config) return [];

        // On prend la table correspondant à la première métrique du rôle
        $mainMetric = $config['metrics'][0] ?? 'visites';
        $table = match ($mainMetric) {
            'consultations' => 'consultations',
            'soins'         => 'soins_infirmiers',
            'visites'       => 'patient_visits',
            'factures'      => 'facture_consultations',
            default         => 'patient_visits',
        };

        return DB::table($table)
            ->selectRaw('DATE(created_at) as jour, COUNT(*) as total')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$debut, $fin])
            ->groupBy('jour')
            ->orderBy('jour')
            ->get()
            ->toArray();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


     /**
     * Rapport par médecin
     */
    public function rapportMedecin($medecin_id, Request $request)
    {
        $startDate = $request->input('start-date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end-date', now()->format('Y-m-d'));

        $medecin = User::findOrFail($medecin_id);
        $rapports = DailyFacture::whereHas('medecin_principal', fn($q) => $q->where('id', $medecin_id))
            ->whereBetween('date_facture', [$startDate, $endDate])
            ->withSum('lignes', 'montant_total')
            ->get()
            ->groupBy(fn($r) => $r->date_facture->format('Y-m'));

        $totalCA = DailyFacture::whereHas('medecin_principal', fn($q) => $q->where('id', $medecin_id))
            ->whereBetween('date_facture', [$startDate, $endDate])
            ->sum('total_montant');

        return view('admin.rapports.medecin', compact('medecin', 'rapports', 'totalCA', 'startDate', 'endDate'));
    }

    /**
     * Rapport par patient
     */
    public function rapportPatient($patient_id, Request $request)
    {
        $patient = Patient::findOrFail($patient_id);
        $startDate = $request->input('start-date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end-date', now()->format('Y-m-d'));

        $factures = $patient->daily_factures()
            ->whereBetween('date_facture', [$startDate, $endDate])
            ->with('lignes')
            ->latest()
            ->get();

        $totalCumule = $factures->sum('total_montant');

        return view('admin.rapports.patient', compact('patient', 'factures', 'totalCumule', 'startDate', 'endDate'));
    }

    /**
     * Index des rapports - accueil avec 4 boutons
     */
    public function rapportsIndex()
    {
        $this->authorize('view', User::class);
        $medecins = User::where('role_id', 2)->select('id', 'name')->get(); 
        $infirmiers = User::whereIn('role_id', [4,6,7])->select('id', 'name')->get(); 
        $patients = Patient::select('id', 'name', 'prenom')->limit(50)->get();
        $laboratins = User::whereIn('role_id', [8,9])->select('id', 'name')->get(); 

        return view('admin.rapports.index', compact('medecins', 'infirmiers', 'patients', 'laboratins'));
    }

    /**
     * Rapport Infirmier - activités soins, administrations PM, etc.
     */
    public function rapportInfirmier($infirmier_id, Request $request)
    {
        $infirmier = User::findOrFail($infirmier_id);
        $startDate = $request->input('start-date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end-date', now()->format('Y-m-d'));

        $soins = \App\Models\SoinsInfirmier::where('user_id', $infirmier_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('patient')
            ->get();

        $adminPM = \App\Models\AdminPrescriptionMedicale::where('infirmier_id', $infirmier_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['prescription_medicale.patient'])
            ->get();

        $totalSoins = $soins->count();
        $totalAdminPM = $adminPM->count();

        return view('admin.rapports.infirmier', compact('infirmier', 'soins', 'adminPM', 'totalSoins', 'totalAdminPM', 'startDate', 'endDate'));
    }

    /**
     * Rapport Laboratin - examens, imageries, préscriptions biologics
     */
    public function rapportLaboratin($laboratin_id, Request $request)
    {
        $laboratin = User::findOrFail($laboratin_id);
        $startDate = $request->input('start-date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end-date', now()->format('Y-m-d'));

        $examens = \App\Models\Examen::whereHas('prescription', fn($q) => $q->where('user_id', $laboratin_id))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['prescription.patient'])
            ->get();

        $imageries = \App\Models\Imagerie::where('user_id', $laboratin_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('patient')
            ->get();

        $totalExamens = $examens->count();
        $totalImageries = $imageries->count();

        return view('admin.rapports.laboratin', compact('laboratin', 'examens', 'imageries', 'totalExamens', 'totalImageries', 'startDate', 'endDate'));
    }
}