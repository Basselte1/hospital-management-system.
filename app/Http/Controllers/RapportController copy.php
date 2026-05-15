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
 * ─────────────────────────────────────────────────────────────────────────────
 * PRINCIPE DIRECTEUR
 * ─────────────────────────────────────────────────────────────────────────────
 * Chaque rôle a une réalité métier différente :
 *   • Médecin / Anesthésiste  → actes cliniques (consultations, ordonnances…)
 *   • Infirmier(e)            → soins, prémédi, paramètres, surveillance
 *   • Secrétaire / Réception  → visites, dossiers, agenda
 *   • Gestionnaire / Caisse   → finances, factures, recettes
 *   • Admin                   → vue croisée complète
 *
 * La clé : ROLE_REPORT_CONFIG
 *   Tableau statique [role_id => config] qui déclare les métriques visibles
 *   pour chaque rôle. Ajouter un nouveau rôle = ajouter une ligne ici.
 *   Aucune modification du reste du contrôleur n'est nécessaire.
 * ─────────────────────────────────────────────────────────────────────────────
 */
class RapportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION DES RÔLES
    |--------------------------------------------------------------------------
    | 'metrics'  : méthodes de calcul à appeler (cf. collectMetrics())
    | 'label'    : libellé affiché dans l'interface
    | 'color'    : classe CSS Bootstrap pour les badges/icônes
    | 'icon'     : classe Font Awesome
    |
    | Pour ajouter un rôle :
    |   1. Ajouter son entry ici
    |   2. Implémenter sa méthode metrics_XXX() si ses métriques sont nouvelles
    |   3. C'est tout.
    */
    private const ROLE_REPORT_CONFIG = [
        // ── Admin (voit tout) ──────────────────────────────────────────────
        1 => [
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

        // ── Médecin / chirurgien / généraliste ────────────────────────────
        2 => [
            'label'   => 'Médecin',
            'color'   => 'primary',
            'icon'    => 'fa-user-md',
            'metrics' => [
                'consultations',
                'suivis',
                'consultations_anesthesiste',
                'prescriptions',
                'prescriptions_medicales',
                'comptes_rendus',
                'nouveaux_patients',
            ],
        ],

        // ── Gestionnaire ──────────────────────────────────────────────────
        3 => [
            'label'   => 'Gestionnaire',
            'color'   => 'warning',
            'icon'    => 'fa-chart-line',
            'metrics' => [
                'visites',
                'nouveaux_patients',
                'factures',
                'montant_facture',
                'encaisse',
                'reste',
                'assurance',
            ],
        ],

        // ── Infirmier(e) ──────────────────────────────────────────────────
        4 => [
            'label'   => 'Infirmier(e)',
            'color'   => 'info',
            'icon'    => 'fa-syringe',
            'metrics' => [
                'soins',
                'premedications',
                'consommables',
                'surveillances',
                'nouveaux_patients',
            ],
        ],

        // ── Logistique / Pharmacien ───────────────────────────────────────
        5 => [
            'label'   => 'Logistique',
            'color'   => 'dark',
            'icon'    => 'fa-boxes',
            'metrics' => [
                'consommables',
                'visites',
            ],
        ],

        // ── Secrétaire / Réception ────────────────────────────────────────
        6 => [
            'label'   => 'Secrétaire',
            'color'   => 'success',
            'icon'    => 'fa-calendar-check',
            'metrics' => [
                'visites',
                'nouveaux_patients',
                'dossiers_crees',
                'events',
            ],
        ],

        // ── Pharmacien ────────────────────────────────────────────────────
        7 => [
            'label'   => 'Pharmacien',
            'color'   => 'success',
            'icon'    => 'fa-pills',
            'metrics' => [
                'prescriptions_medicales',
                'consommables',
            ],
        ],

        // ── Caisse ────────────────────────────────────────────────────────
        9 => [
            'label'   => 'Caisse',
            'color'   => 'danger',
            'icon'    => 'fa-cash-register',
            'metrics' => [
                'factures',
                'montant_facture',
                'encaisse',
                'reste',
                'assurance',
                'visites',
            ],
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | LIBELLÉS & UNITÉS DES MÉTRIQUES
    |--------------------------------------------------------------------------
    | Centralise l'affichage. 'money' = formaté en FCFA. 'count' = entier.
    */
    private const METRIC_LABELS = [
        'consultations'              => ['label' => 'Consultations',             'icon' => 'fa-stethoscope',    'type' => 'count',  'color' => 'primary'],
        'suivis'                     => ['label' => 'Consultations de suivi',    'icon' => 'fa-notes-medical',  'type' => 'count',  'color' => 'primary'],
        'consultations_anesthesiste' => ['label' => 'Consultations anesthésie',  'icon' => 'fa-procedures',     'type' => 'count',  'color' => 'info'],
        'prescriptions'              => ['label' => 'Prescriptions d\'examens',  'icon' => 'fa-flask',          'type' => 'count',  'color' => 'secondary'],
        'prescriptions_medicales'    => ['label' => 'Prescriptions médicales',   'icon' => 'fa-pills',          'type' => 'count',  'color' => 'secondary'],
        'comptes_rendus'             => ['label' => 'CR blocs opératoires',      'icon' => 'fa-file-medical',   'type' => 'count',  'color' => 'warning'],
        'soins'                      => ['label' => 'Soins infirmiers',          'icon' => 'fa-heart-pulse',    'type' => 'count',  'color' => 'info'],
        'premedications'             => ['label' => 'Prémédicatons',             'icon' => 'fa-syringe',        'type' => 'count',  'color' => 'info'],
        'consommables'               => ['label' => 'Fiches consommables',       'icon' => 'fa-boxes',          'type' => 'count',  'color' => 'secondary'],
        'surveillances'              => ['label' => 'Surveillances post-op.',    'icon' => 'fa-heart',          'type' => 'count',  'color' => 'danger'],
        'visites'                    => ['label' => 'Visites enregistrées',      'icon' => 'fa-hospital-user',  'type' => 'count',  'color' => 'success'],
        'nouveaux_patients'          => ['label' => 'Nouveaux patients',         'icon' => 'fa-user-plus',      'type' => 'count',  'color' => 'success'],
        'dossiers_crees'             => ['label' => 'Dossiers créés',            'icon' => 'fa-folder-plus',    'type' => 'count',  'color' => 'success'],
        'events'                     => ['label' => 'RDV / Événements',          'icon' => 'fa-calendar',       'type' => 'count',  'color' => 'warning'],
        'factures'                   => ['label' => 'Factures émises',           'icon' => 'fa-file-invoice',   'type' => 'count',  'color' => 'warning'],
        'montant_facture'            => ['label' => 'Total facturé',             'icon' => 'fa-money-bill',     'type' => 'money',  'color' => 'warning'],
        'encaisse'                   => ['label' => 'Encaissé',                  'icon' => 'fa-cash-register',  'type' => 'money',  'color' => 'success'],
        'reste'                      => ['label' => 'Reste à recouvrer',         'icon' => 'fa-clock',          'type' => 'money',  'color' => 'danger'],
        'assurance'                  => ['label' => 'Part assurance',            'icon' => 'fa-shield-alt',     'type' => 'money',  'color' => 'info'],
    ];

    /*=========================================================================
    | PAGE PRINCIPALE — /admin/rapports
    =========================================================================*/

    public function index(Request $request)
    {
        // ── 1. Période ────────────────────────────────────────────────────
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->input('date_fin',   now()->format('Y-m-d'));
        $periode   = $request->input('periode', 'mois'); // jour | semaine | mois | libre

        // Raccourcis de période
        if ($periode === 'jour') {
            $dateDebut = $dateFin = now()->format('Y-m-d');
        } elseif ($periode === 'semaine') {
            $dateDebut = now()->startOfWeek()->format('Y-m-d');
            $dateFin   = now()->format('Y-m-d');
        } elseif ($periode === 'mois') {
            $dateDebut = now()->startOfMonth()->format('Y-m-d');
            $dateFin   = now()->format('Y-m-d');
        }

        if ($dateFin < $dateDebut) $dateFin = $dateDebut;

        $debut = Carbon::parse($dateDebut)->startOfDay();
        $fin   = Carbon::parse($dateFin)->endOfDay();

        // ── 2. Filtres ────────────────────────────────────────────────────
        $userId = $request->input('user_id');   // null = tous
        $roleId = $request->input('role_id');    // null = tous les rôles

        // ── 3. Utilisateurs (pour le sélecteur) ──────────────────────────
        $users = User::select('id', 'name', 'prenom', 'role_id')
            ->orderBy('role_id')
            ->orderBy('name')
            ->get();

        // ── 4. Rapport de chaque utilisateur actif sur la période ─────────
        $rapportUsers = $users
            ->when($userId, fn($c) => $c->where('id', $userId))
            ->when($roleId, fn($c) => $c->where('role_id', $roleId))
            ->map(fn($u) => $this->buildUserRapport($u, $debut, $fin))
            ->filter(fn($r) => $r['total_actes'] > 0 || $userId); // afficher si filtre explicite

        // ── 5. Résumé global (vue admin seulement) ────────────────────────
        $resumeGlobal = null;
        if (auth()->user()->role_id == 1) {
            $resumeGlobal = $this->buildResumeGlobal($debut, $fin);
        }

        $usersJson = $users->map(fn(User $u) => [
            'id'      => $u->id,
            'role_id' => $u->role_id,
            'label'   => trim($u->name . ' ' . ($u->prenom ?? '')),
        ])->values();

        return view('admin.rapports.index', compact(
            'users',
            'rapportUsers',
            'resumeGlobal',
            'dateDebut',
            'dateFin',
            'periode',
            'userId',
            'roleId',
            'usersJson',
        ));
    }

    /*=========================================================================
    | PAGE DÉTAIL D'UN UTILISATEUR — /admin/rapports/{user}
    =========================================================================*/

    public function show(Request $request, User $user)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->input('date_fin',   now()->format('Y-m-d'));

        $debut = Carbon::parse($dateDebut)->startOfDay();
        $fin   = Carbon::parse($dateFin)->endOfDay();

        $rapport   = $this->buildUserRapport($user, $debut, $fin);
        $config    = self::ROLE_REPORT_CONFIG[$user->role_id] ?? null;
        $labels    = self::METRIC_LABELS;

        // Évolution jour par jour (mini-graphe, max 31 jours)
        $evolution = [];
        if ($debut->diffInDays($fin) <= 31) {
            $evolution = $this->buildEvolutionJournaliere($user, $debut, $fin);
        }

        return view('admin.rapports.show', compact(
            'user',
            'rapport',
            'config',
            'labels',
            'evolution',
            'dateDebut',
            'dateFin',
        ));
    }

    /*=========================================================================
    | CONSTRUCTION DU RAPPORT D'UN UTILISATEUR
    =========================================================================*/

    /**
     * Construit le rapport complet pour un utilisateur donné.
     * Seules les métriques déclarées dans ROLE_REPORT_CONFIG sont calculées.
     */
    private function buildUserRapport(User $user, Carbon $debut, Carbon $fin): array
    {
        $config = self::ROLE_REPORT_CONFIG[$user->role_id] ?? null;

        if (! $config) {
            return [
                'user'        => $user,
                'config'      => null,
                'metrics'     => [],
                'total_actes' => 0,
            ];
        }

        $metrics    = [];
        $totalActes = 0;

        foreach ($config['metrics'] as $metricKey) {
            $value = $this->computeMetric($metricKey, $user->id, $debut, $fin);
            $metrics[$metricKey] = $value;

            // Les métriques financières ne comptent pas dans "total actes"
            $meta = self::METRIC_LABELS[$metricKey] ?? [];
            if (($meta['type'] ?? 'count') === 'count') {
                $totalActes += $value;
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

    /*=========================================================================
    | CALCUL D'UNE MÉTRIQUE
    |--------------------------------------------------------------------------
    | Chaque case = une requête SQL simple, isolée.
    | Ajouter une nouvelle métrique = ajouter un case ici + la déclarer
    | dans METRIC_LABELS + la référencer dans le ROLE_REPORT_CONFIG du rôle.
    =========================================================================*/

    private function computeMetric(string $key, int $userId, Carbon $debut, Carbon $fin): int|float
    {
        return match ($key) {

            'consultations' => Consultation
                ::where('user_id', $userId)
                ->whereBetween('created_at', [$debut, $fin])
                ->count(),

            'suivis' => ConsultationSuivi
                ::where('user_id', $userId)
                ->whereBetween('created_at', [$debut, $fin])
                ->count(),

            'consultations_anesthesiste' => ConsultationAnesthesiste
                ::where('user_id', $userId)
                ->whereBetween('created_at', [$debut, $fin])
                ->count(),

            'prescriptions' => Prescription
                ::where('user_id', $userId)
                ->whereBetween('created_at', [$debut, $fin])
                ->count(),

            'prescriptions_medicales' => PrescriptionMedicale
                ::where('user_id', $userId)
                ->whereBetween('date', [$debut->toDateString(), $fin->toDateString()])
                ->count(),

            'comptes_rendus' => CompteRenduBlocOperatoire
                ::where('user_id', $userId)
                ->whereBetween('created_at', [$debut, $fin])
                ->count(),

            'soins' => $this->countModel('soins_infirmiers', $userId, $debut, $fin),

            'premedications' => Premedication
                ::where('user_id', $userId)
                ->whereBetween('created_at', [$debut, $fin])
                ->count(),

            'consommables' => $this->countModel('fiche_consommables', $userId, $debut, $fin),

            'surveillances' => $this->countModel('surveillance_post_anesthesiques', $userId, $debut, $fin),

            'visites' => PatientVisit
                ::where('user_id', $userId)
                ->whereBetween('created_at', [$debut, $fin])
                ->count(),

            'nouveaux_patients' => Patient
                ::where('user_id', $userId)
                ->whereBetween('created_at', [$debut, $fin])
                ->count(),

            'dossiers_crees' => $this->countModel('dossiers', $userId, $debut, $fin),

            'events' => $this->countModel('events', $userId, $debut, $fin),

            // Métriques financières — retournent un montant (float)
            'factures' => $this->countFactures($userId, $debut, $fin, 'nb'),

            'montant_facture' => $this->countFactures($userId, $debut, $fin, 'montant'),

            'encaisse' => $this->countFactures($userId, $debut, $fin, 'avance'),

            'reste' => $this->countFactures($userId, $debut, $fin, 'reste'),

            'assurance' => $this->countFactures($userId, $debut, $fin, 'assurance'),

            default => 0,
        };
    }

    /*=========================================================================
    | HELPERS SQL
    =========================================================================*/

    /** Compte générique via Query Builder (évite de charger le modèle Eloquent) */
    private function countModel(string $table, int $userId, Carbon $debut, Carbon $fin): int
    {
        return DB::table($table)
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$debut, $fin])
            ->count();
    }

    /** Agrégats factures de consultation */
    private function countFactures(int $userId, Carbon $debut, Carbon $fin, string $field): int|float
    {
        $row = DB::table('facture_consultations')
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$debut, $fin])
            ->selectRaw('COUNT(*) as nb, SUM(montant) as montant, SUM(avance) as avance, SUM(reste) as reste, SUM(assurancec) as assurance')
            ->first();

        return $row?->{$field} ?? 0;
    }

    /*=========================================================================
    | RÉSUMÉ GLOBAL (Admin seulement)
    =========================================================================*/

    private function buildResumeGlobal(Carbon $debut, Carbon $fin): array
    {
        return [
            'total_consultations'  => Consultation::whereBetween('created_at', [$debut, $fin])->count(),
            'total_visites'        => PatientVisit::whereBetween('created_at', [$debut, $fin])->count(),
            'total_patients'       => Patient::whereBetween('created_at', [$debut, $fin])->count(),
            'total_soins'          => DB::table('soins_infirmiers')->whereBetween('created_at', [$debut, $fin])->count(),
            'total_facture'        => DB::table('facture_consultations')->whereBetween('created_at', [$debut, $fin])->sum('montant'),
            'total_encaisse'       => DB::table('facture_consultations')->whereBetween('created_at', [$debut, $fin])->sum('avance'),
            'total_reste'          => DB::table('facture_consultations')->whereBetween('created_at', [$debut, $fin])->sum('reste'),
        ];
    }

    /*=========================================================================
    | ÉVOLUTION JOURNALIÈRE (mini-graphe)
    =========================================================================*/

    private function buildEvolutionJournaliere(User $user, Carbon $debut, Carbon $fin): array
    {
        // On choisit la métrique principale selon le rôle
        $config = self::ROLE_REPORT_CONFIG[$user->role_id] ?? null;
        if (! $config) return [];

        $mainMetric = $config['metrics'][0] ?? null;

        // Pour les rôles cliniques, on compte les consultations ou visites jour/jour
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

    /*=========================================================================
    | ACCESSEUR DE CONFIG (utile pour les vues)
    =========================================================================*/

    public static function getRoleConfig(int $roleId): ?array
    {
        return self::ROLE_REPORT_CONFIG[$roleId] ?? null;
    }

    public static function getAllMetricLabels(): array
    {
        return self::METRIC_LABELS;
    }

    public function pdf(Request $request, User $user)
   {
       $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
       $dateFin   = $request->input('date_fin',   now()->format('Y-m-d'));

       $debut = Carbon::parse($dateDebut)->startOfDay();
       $fin   = Carbon::parse($dateFin)->endOfDay();

       $rapport = $this->buildUserRapport($user, $debut, $fin);
       $config  = self::ROLE_REPORT_CONFIG[$user->role_id] ?? null;
       $labels  = self::METRIC_LABELS;

       // Option 1 : Vue HTML simple (impression navigateur)
       return view('admin.rapports.pdf', compact('user', 'rapport', 'config', 'labels', 'dateDebut', 'dateFin'));

       // Option 2 : Si tu utilises ton PdfService existant :
       // return PdfService::generate('admin.rapports.pdf', compact(...), "rapport_{$user->id}.pdf");
   }
    
}