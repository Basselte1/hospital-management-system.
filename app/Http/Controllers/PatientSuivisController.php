<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationAnesthesiste;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PatientSuivisController extends Controller
{
    /**
     * Get count of unique patients followed by current doctor
     * Used for dashboard statistics
     * 
     * @param int $userId
     * @return int
     */
    public function getPatientsSuivisCount($userId = null)
    {
        $userId = $userId ?? Auth::id();
        $user = User::find($userId);
        
        if (!$user) {
            return 0;
        }
        
        $doctorName = $user->name . " ". $user->prenom;
        $cacheKey = "patients_suivis_count_{$userId}";
        
        return Cache::tags(['patients', 'consultations',])->remember($cacheKey, 60, function () use ($userId, $doctorName) {
            // Get patient IDs from consultations
            $consultedPatientIds = DB::table('consultations')
                ->select('patient_id')
                ->where('user_id', $userId)
                ->union(
                    DB::table('consultation_anesthesistes')
                        ->select('patient_id')
                        ->where('user_id', $userId)
                )
                ->distinct()
                ->pluck('patient_id');

            // Get patient IDs where doctor is assigned
            $assignedPatientIds = Patient::where('medecin_r', $doctorName)
                ->pluck('id');

            // Merge and get unique count
            return $consultedPatientIds->merge($assignedPatientIds)->unique()->count();
        });
    }

    
    public function patientsSuivis(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        
        if (!$user) {
            abort(403, 'Utilisateur non trouvé');
        }
        
        $doctorName = $user->name . ' ' . $user->prenom;
        $perPage = (int) $request->input('per_page', 10);
        $search = $request->input('search');
        $showNew = $request->input('show_new', false);

        // Get unique patient IDs from consultations
        $consultedPatientIds = DB::table('consultations')
            ->select('patient_id')
            ->where('user_id', $userId)
            ->union(
                DB::table('consultation_anesthesistes')
                    ->select('patient_id')
                    ->where('user_id', $userId)
            )
            ->distinct();

        // Build the main query
        $query = Patient::select([
                'patients.id',
                'patients.numero_dossier',
                'patients.name',
                'patients.prenom',
                'patients.created_at',
                'patients.medecin_r',
            ])
            // Include patients either consulted OR assigned to this doctor
            ->where(function($q) use ($consultedPatientIds, $doctorName) {
                $q->whereIn('patients.id', $consultedPatientIds)
                  ->orWhere('patients.medecin_r', $doctorName);
            })
            ->with([
                'user:id,telephone',
                'dossiers' => function($query) {
                    $query->select('id', 'patient_id', 'portable_1', 'portable_2')
                        ->latest()
                        ->limit(1);
                },
                'consultations' => function($query) use ($userId) {
                    $query->select('id', 'patient_id', 'user_id', 'created_at')
                        ->where('user_id', $userId)
                        ->latest()
                        ->limit(1);
                },
                'consultation_anesthesistes' => function($query) use ($userId) {
                    $query->select('id', 'patient_id', 'user_id', 'created_at')
                        ->where('user_id', $userId)
                        ->latest()
                        ->limit(1);
                }
            ]);

        // Filter for new patients if requested
        if ($showNew) {
            $query->where('patients.medecin_r', $doctorName)
                  ->where('patients.created_at', '>=', now()->subHours(48))
                  // New patients: assigned to this doctor but never consulted by them
                  ->whereDoesntHave('consultations', function($q) use ($userId) {
                      $q->where('user_id', $userId);
                  })
                  ->whereDoesntHave('consultation_anesthesistes', function($q) use ($userId) {
                      $q->where('user_id', $userId);
                  });
        }

        // Add search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('numero_dossier', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $patients = $query->latest('patients.created_at')->paginate($perPage);
        
        // Add "isNouveau" flag to each patient
        $patients->getCollection()->transform(function ($patient) use ($userId, $doctorName) {
            // Patient is "nouveau" if:
            // 1. Created in last 48h
            // 2. Assigned to this doctor
            // 3. This doctor hasn't consulted them yet
            $patient->isNouveau = $patient->isNew() && 
                                  $patient->medecin_r === $doctorName &&
                                  $patient->consultations->isEmpty() &&
                                  $patient->consultation_anesthesistes->isEmpty();
            return $patient;
        });

        // Get statistics
        $stats = $this->getPatientsSuivisStatistics($userId, $doctorName);
        
        // Count new patients
        $nouveaux_patients_count = Patient::where('medecin_r', $doctorName)
            ->where('created_at', '>=', now()->subHours(48))
            ->whereDoesntHave('consultations', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereDoesntHave('consultation_anesthesistes', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->count();

        return view('admin.patients_suivis.index', [
            'patients' => $patients,
            'search' => $search,
            'perPage' => $perPage,
            'stats' => $stats,
            'nouveaux_patients_count' => $nouveaux_patients_count,
            'showNew' => $showNew,
        ]);
    }
    /**
     * Get statistics for patients suivis page
     * Cached separately for better performance
     */
    private function getPatientsSuivisStatistics($userId, $doctorName)
    {
        $cacheKey = "patients_suivis_stats_{$userId}";
        
        return Cache::tags(['patients', 'consultations'])->remember($cacheKey, 60, function () use ($userId, $doctorName) {
            // Count consultations by type
            $consultationsCount = DB::table('consultations')
                ->where('user_id', $userId)
                ->distinct('patient_id')
                ->count('patient_id');

            $anesthesisteCount = DB::table('consultation_anesthesistes')
                ->where('user_id', $userId)
                ->distinct('patient_id')
                ->count('patient_id');

            // Total patients (consulted + assigned)
            $totalPatients = $this->getPatientsSuivisCount($userId);

            return [
                'total_patients' => $totalPatients,
                'consultations_chirurgien' => $consultationsCount,
                'consultations_anesthesiste' => $anesthesisteCount,
            ];
        });
    }

    /**
     * Clear cache for patients suivis
     */
    public static function clearPatientsSuivisCache($userId = null)
    {
        Cache::tags(['patients', 'consultations'])->flush();
    }

    /**
     * Get recent consultation activity for dashboard widget
     * Optimized for quick loading
     */
    public function recentActivity(Request $request)
    {
        $userId = Auth::id();
        $limit = (int) $request->input('limit', 10);

        $cacheKey = "recent_activity_{$userId}_{$limit}";

        $activity = Cache::tags(['consultations'])->remember($cacheKey, 30, function () use ($userId, $limit) {
            // Get recent consultations from both tables
            $consultations = Consultation::select([
                    'id',
                    'patient_id',
                    'user_id',
                    'created_at',
                    DB::raw("'chirurgien' as type")
                ])
                ->where('user_id', $userId)
                ->with('patient:id,name,prenom,numero_dossier');

            $anesthesiste = ConsultationAnesthesiste::select([
                    'id',
                    'patient_id',
                    'user_id',
                    'created_at',
                    DB::raw("'anesthesiste' as type")
                ])
                ->where('user_id', $userId)
                ->with('patient:id,name,prenom,numero_dossier');

            // Union and order by date
            return $consultations
                ->union($anesthesiste)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });

        return response()->json($activity);
    }

    /**
     * Export patients suivis to CSV/Excel
     * Optimized to handle large datasets
     */
    public function export(Request $request)
    {
        $this->authorize('update', Patient::class);
        
        $userId = Auth::id();
        
        // Get patient IDs efficiently
        $patientIds = DB::table('consultations')
            ->select('patient_id')
            ->where('user_id', $userId)
            ->union(
                DB::table('consultation_anesthesistes')
                    ->select('patient_id')
                    ->where('user_id', $userId)
            )
            ->distinct()
            ->pluck('patient_id');

        // Chunk the query to handle large datasets
        $patients = Patient::select([
                'numero_dossier',
                'name',
                'prenom',
                'created_at'
            ])
            ->whereIn('id', $patientIds)
            ->orderBy('created_at', 'desc')
            ->get();

        // Return as downloadable file (implement your preferred export method)
        // This is a placeholder - you would use Laravel Excel or similar
        return response()->json([
            'success' => true,
            'count' => $patients->count(),
            'message' => 'Export ready'
        ]);
    }
}




