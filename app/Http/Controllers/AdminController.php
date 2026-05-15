<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Consultation;
use App\Models\Event;
use App\Http\Requests\LicenceActiveRequest;
use App\Models\Fiche;
use App\Models\Licence;
use App\Models\Patient;
use App\Models\Produit;
use App\Models\User;
use Carbon\Carbon;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $userId = $user->id;

        // Cache dashboard statistics for 5 minutes
        $stats = Cache::remember("dashboard_stats_{$userId}", 300, function () use ($user, $userId) {
            $patients_suivis = 0;
            $nouveaux_patients = 0;

            if ($user->role_id == 2) { // Medecin
                $doctorName = $user->name . ' ' . $user->prenom;

                // Patients suivis (consulted OR assigned)
                $consultedPatientIds = DB::table('consultations')
                    ->select('patient_id')
                    ->where('user_id', $user->id)
                    ->union(
                        DB::table('consultation_anesthesistes')
                            ->select('patient_id')
                            ->where('user_id', $user->id)
                    )
                    ->distinct()
                    ->pluck('patient_id');

                $assignedPatientIds = Patient::where('medecin_r', $doctorName)
                    ->pluck('id');

                $patients_suivis = $consultedPatientIds->merge($assignedPatientIds)->unique()->count();

                // Nouveaux patients (assigned, created in last 48h, not yet consulted)
                $nouveaux_patients = Patient::where('medecin_r', $doctorName)
                    ->where('created_at', '>=', now()->subHours(48))
                    ->whereDoesntHave('consultations', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->whereDoesntHave('consultation_anesthesistes', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->count();
            }

            return [
                'produits' => Produit::count(),
                'users' => User::count(),
                'patients' => Patient::count(),
                'events' => Event::where('user_id', $userId)->count(),
                'chambres' => Chambre::count(),
                'fiches' => Fiche::count(),
                'patients_suivis' => $patients_suivis,
                'nouveaux_patients' => $nouveaux_patients,
            ];
        });

        // Optimize consultation query with eager loading and limit
        $consultation = Cache::remember("dashboard_consultations_{$userId}", 300, function () use ($userId) {
            return Consultation::with(['user:id,name', 'patient:id,name,prenom'])
                ->where('user_id', $userId)
                ->select('id', 'user_id', 'patient_id', 'date_consultation', 'created_at')
                ->latest()
                ->limit(10)
                ->get();
        });

        return view('admin.dashboard', array_merge($stats, compact('consultation')));
    }

    /**
     * Activate license key
     */
    public function ActiveLicence(LicenceActiveRequest $request)
    {
        $licence = Licence::where('client', 'cmcuapp')->first();

        $licence->update([
            'license_key' => $request->input('license_key'),
            'expire_date' => Carbon::parse('+1 month')
        ]);
        
        // Clear license cache
        Cache::forget('license_cmcuapp');

        // Flash::info('Votre licence a bien été activée');
        // return back();
        return redirect()
        ->back()
        ->with('info', 'Votre licence a bien été activée');

    }

    /**
     * Redirect to dashboard
     */
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Clear dashboard cache for a specific user
     * Call this after major data changes
     * 
     * @param int|null $userId
     */
    public static function clearDashboardCache($userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        Cache::forget("dashboard_stats_{$userId}");
        Cache::forget("dashboard_consultations_{$userId}");
        
        // Also clear patients suivis count cache
        Cache::tags(['patients', 'consultations'])->forget("patients_suivis_count_{$userId}");
    }
}


