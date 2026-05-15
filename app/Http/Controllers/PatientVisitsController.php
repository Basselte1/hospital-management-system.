<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PatientVisitsController extends Controller
{
    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Returns true if the logged-in user is a médecin (role_id = 2).
     * Admins (role_id = 1) are handled by the policy before() bypass
     * so we never need to special-case them here.
     */
    private function isMedecin(): bool
    {
        return Auth::user()->role_id === 2;
    }

    /**
     * Returns the full display name of the logged-in médecin,
     * formatted the same way medecin_r is stored on visits/patients
     * (i.e.  "NOM Prénom", produced by PatientSuivisController).
     */
    private function currentDoctorName(): string
    {
        $user = Auth::user();
        return trim($user->name . ' ' . ($user->prenom ?? ''));
    }

    // ─── Index ────────────────────────────────────────────────────────────────

    /**
     * Display visit list.
     * - Admins / secrétaires / infirmiers → see ALL visits.
     * - Médecins                          → see ONLY their own visits
     *   (those where medecin_r matches their full name).
     */
    public function index(Request $request)
    {
        $this->authorize('viewVisitsList', Patient::class);

        $perPage  = (int) $request->input('per_page', 15);
        $search   = $request->input('search');
        $status   = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        $query = PatientVisit::with([
            'patient:id,name,prenom,numero_dossier',
            'user:id,name,prenom',
        ])
        ->orderBy('visit_date', 'desc')
        ->orderBy('created_at', 'desc');

        // ── Médecin scope ──────────────────────────────────────────────────────
        if ($this->isMedecin()) {
            $query->where('medecin_r', $this->currentDoctorName());
        }

        // ── Filters ────────────────────────────────────────────────────────────
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($q2) use ($search) {
                    $q2->withTrashed()
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('numero_dossier', 'like', "%{$search}%");
                })
                ->orWhere('patient_name', 'like', "%{$search}%")
                ->orWhere('patient_numero_dossier', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }
        if ($dateFrom) {
            $query->whereDate('visit_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('visit_date', '<=', $dateTo);
        }

        $visits = $query->paginate($perPage);

        // ── Stats scoped to the same médecin constraint ────────────────────────
        $statsBase = PatientVisit::query();
        if ($this->isMedecin()) {
            $statsBase->where('medecin_r', $this->currentDoctorName());
        }

        $stats = [
            'total_visits'   => (clone $statsBase)->count(),
            'today_visits'   => (clone $statsBase)->whereDate('visit_date', today())->count(),
            'pending_visits' => (clone $statsBase)->where('status', 'en_attente')->count(),
            'total_revenue'  => (clone $statsBase)->sum('montant'),
        ];

        return view('admin.patient_visits.index', compact(
            'visits', 'stats', 'search', 'status', 'dateFrom', 'dateTo', 'perPage'
        ));
    }

    /**
     * Show form to create a new visit for an existing patient
     */
    public function create(Request $request)
    {
        $this->authorize('createVisit', Patient::class);

        // Récupérer tous les patients actifs (sans doublons idéalement)
        $patients = Patient::select( 'name', 'prenom')
            ->orderBy('name')
            ->orderBy('prenom')
            ->get();

        // Récupérer les médecins groupés par spécialité
        $users = User::whereIn('role_id',[2,4])
            ->select('id', 'name', 'prenom', 'specialite', 'role_id')
            ->orderBy('specialite')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($user) {
                return $user->role_id == 4 ? 'infirmiers' : $user->specialite;
            });
            
        // ✅ NOUVEAU : Vérifier si un patient est pré-sélectionné via l'URL
        $preselectedPatient = null;
        if ($request->has('patient_id')) {
            $preselectedPatient = Patient::select('id', 'numero_dossier', 'name', 'prenom')
                ->find($request->input('patient_id'));
        }

        return view('admin.patient_visits.create', compact('patients', 'users', 'preselectedPatient'));
    }

    /**
     * Store a new patient visit
     */
    public function store(Request $request)
    {
        $this->authorize('createVisit', Patient::class);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date',
            'motif' => 'nullable|string|',//max:255
            'details_motif' => 'nullable|string',
            'montant' => 'nullable|numeric|min:0',
            'avance' => 'nullable|numeric|min:0',
            'assurance' => 'nullable|string|max:255',
            'numero_assurance' => 'nullable|string|max:255',
            'prise_en_charge' => 'nullable|string|max:255',
            'medecin_r' => 'nullable|string|max:255',
            'mode_paiement' => 'nullable|string|max:255',
            'mode_paiement_info_sup' => 'nullable|string|max:255',
            'demarcheur' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::transaction(function () use ($validated) {
                $visit = PatientVisit::create([
                    'patient_id' => $validated['patient_id'],
                    'user_id' => Auth::id(),
                    'visit_date' => $validated['visit_date'],
                    'motif' => $validated['motif'] ?? null,
                    'details_motif' => $validated['details_motif'] ?? null,
                    'montant' => $validated['montant'] ?? 0,
                    'avance' => $validated['avance'] ?? 0,
                    'assurance' => $validated['assurance'] ?? null,
                    'numero_assurance' => $validated['numero_assurance'] ?? null,
                    'prise_en_charge' => $validated['prise_en_charge'] ?? null,
                    'medecin_r' => $validated['medecin_r'] ?? null,
                    'mode_paiement' => $validated['mode_paiement'] ?? 'espèce',
                    'mode_paiement_info_sup' => $validated['mode_paiement_info_sup'] ?? null,
                    'demarcheur' => $validated['demarcheur'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'status' => 'en_attente',
                ]);

                // Clear cache
                Cache::tags(['patients', 'visits'])->flush();
            });

            return redirect()
                ->route('patient-visits.index')
                ->with('success', 'La visite du patient a été enregistrée avec succès !');
                
        } catch(\Illuminate\Database\QueryException $e) {
            Log::error('Database error in patient visit creation', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'data' => $validated
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erreur de base de données. Contactez l\'administrateur.');
                
        } catch (\Exception $e) {
            Log::error('Unexpected error in patient visit creation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur inattendue s\'est produite.');
        }
    }

    /**
     * Display visit history for a specific patient.
     * Médecins may only view patients assigned to them via medecin_r.
     */
    public function showPatientVisits(Patient $patient)
    {
        $this->authorize('consulter', Patient::class);

        // Block médecins from accessing patients not assigned to them
        if ($this->isMedecin() && $patient->medecin_r !== $this->currentDoctorName()) {
            abort(403, 'Accès refusé : ce patient ne vous est pas assigné.');
        }

        $visits = $patient->visits()
            ->with('user:id,name,prenom')
            ->paginate(10);

        $stats = [
            'total_visits'    => $patient->visits()->count(),
            'total_spent'     => $patient->visits()->sum('montant'),
            'total_paid'      => $patient->visits()->sum('avance'),
            'total_remaining' => $patient->visits()->sum('reste'),
        ];

        return view('admin.patient_visits.patient_history', compact('patient', 'visits', 'stats'));
    }

    /**
     * Show details of a specific visit.
     * Médecins may only view visits assigned to them.
     */
    public function show(PatientVisit $visit)
    {
        $this->authorize('consulter', Patient::class);

        // Block médecins from viewing visits not assigned to them
        if ($this->isMedecin() && $visit->medecin_r !== $this->currentDoctorName()) {
            abort(403, 'Accès refusé : cette visite ne vous est pas assignée.');
        }

        $visit->load(['patient', 'user']);

        return view('admin.patient_visits.show', compact('visit'));
    }

    /**
     * Show form to edit a visit
     */
    public function edit(PatientVisit $visit)
    {
        $this->authorize('update', Patient::class);

        $medecins = User::where('role_id', 2)
            ->select('id', 'name', 'prenom')
            ->orderBy('name')
            ->get();

        return view('admin.patient_visits.edit', compact('visit', 'medecins'));
    }

    /**
     * Delete a visit. Médecins cannot delete (covered by policy role_id=1 only).
     */
    public function destroy(PatientVisit $visit)
    {
        $this->authorize('delete', Patient::class);

        $patientId = $visit->patient_id;

        DB::transaction(function () use ($visit) {
            $visit->delete();
            Cache::tags(['patients', 'visits'])->flush();
        });

        return redirect()
            ->route('patient-visits.patient-history', $patientId)
            ->with('success', 'La visite a été supprimée avec succès.');
    }

    /**
     * API: Search patients for autocomplete (JSON response) - UTILISÉ PAR SELECT2
     */
    public function searchPatients(Request $request)
    {
        $search = $request->input('q');

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
    }

    /**
     * Update visit status.
     * Médecins may only update status on their own visits.
     */
    public function updateStatus(Request $request, PatientVisit $visit)
    {
        $this->authorize('updateVisitStatus', Patient::class);

        // Médecins can only update status of their own visits
        if ($this->isMedecin() && $visit->medecin_r !== $this->currentDoctorName()) {
            abort(403, 'Accès refusé : cette visite ne vous est pas assignée.');
        }

        $request->validate([
            'status' => 'required|in:en_attente,en_cours,terminee,annulee'
        ]);

        $visit->update(['status' => $request->status]);

        Cache::tags(['patients', 'visits'])->flush();

        return back()->with('success', 'Le statut de la visite a été mis à jour.');
    }
}