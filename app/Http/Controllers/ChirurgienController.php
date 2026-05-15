<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ObservationMedicale;
use App\Models\Patient;
use App\Models\SoinsInfirmier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChirurgienController extends Controller
{

    public function AbservationMedicaleCreate(Patient $patient)
    {
        // Cache only medical observations - DO NOT cache paginated results directly
        $cacheKey = "patient_{$patient->id}_medical_obs";
        
        $observation_medicales = Cache::remember($cacheKey, 600, function () use ($patient) {
            return ObservationMedicale::with('patient:id,name,prenom', 'user:id,name,prenom')
                ->where('patient_id', $patient->id)
                ->get(); // Changed from paginate to get() for caching
        });

        // Paginate the cached collection
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $observation_medicales = new \Illuminate\Pagination\LengthAwarePaginator(
            $observation_medicales->forPage($currentPage, $perPage),
            $observation_medicales->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Always fetch soins infirmiers fresh (no cache)
        $soins_infirmiers = SoinsInfirmier::with('patient:id,name,prenom', 'user:id,name,prenom')
            ->where('patient_id', $patient->id)
            ->paginate(15);

        $anesthesistes = Cache::tags(['users'])->remember('anesthesistes_cache', 30, function () {
            return User::whereIn('name', ['TENKE', 'SANDJON'])
                ->select(['id', 'name', 'prenom'])
                ->get();
        });

        $users = Cache::tags(['users'])->remember('users_role_2_obs', 30, function () {
            return User::where('role_id', 2)
                ->select(['id', 'name', 'prenom'])
                ->get();
        });

        $patients_externes = Cache::tags(['clients'])->remember('clients_all', 60, function () {
            return Client::orderBy('nom', 'asc')
                ->select(['id', 'nom'])
                ->get();
        });

        return view('admin.consultations.observation_medicale', [
            'anesthesistes' => $anesthesistes,
            'users' => $users,
            'patient' => $patient,
            'patient_externes' => $patients_externes,
            'observation_medicales' => $observation_medicales,
            'soins_infirmiers' => $soins_infirmiers,
        ]);
    }

    
    public function AbservationMedicaleStore(Request $request)
    {
        $observationMedicale = new ObservationMedicale();
        $observationMedicale->fill($request->only([
            'user_id', 'patient_id', 'observation', 'date', 'anesthesiste'
        ]));
        $observationMedicale->save();

        // Clear cache so new data shows immediately
        $cacheKey = "patient_{$observationMedicale->patient_id}_medical_obs";
        Cache::forget($cacheKey);

        return back()->with('success', 'Votre enregistrement a bien été pris en compte');
    }

    public function AbservationMedicaleEdit($id)
    {
        $observation = ObservationMedicale::findOrFail($id);

        return view('admin.consultations.observation_medicale_edit', [
            'observation' => $observation,
            'patient' => $observation->patient,
        ]);
    }

    public function AbservationMedicaleUpdate(Request $request, $id)
    {
        $observation = ObservationMedicale::findOrFail($id);

        $observation->update([
            'observation' => $request->input('observation'),
            'date' => $request->input('date'),
            'anesthesiste' => $request->input('anesthesiste'),
        ]);

        // Clear cache so updated data shows immediately
        Cache::forget("patient_{$observation->patient_id}_medical_obs");

        return redirect()
            ->back()
            ->with('success', 'Observation médicale mise à jour avec succès');
    }

    public function AbservationMedicaleDestroy($id)
    {
        $observation = ObservationMedicale::findOrFail($id);
        $patientId = $observation->patient_id;

        $observation->delete();

        // Clear cache so deletion reflects immediately
        Cache::forget("patient_{$patientId}_medical_obs");

        return redirect()
            ->back()
            ->with('success', 'Observation médicale supprimée avec succès');
    }

}