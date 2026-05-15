<?php

namespace App\Http\Controllers;

use App\Models\ConsultationSuivi;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class ConsultationSuiviController extends Controller
{
    public function create(Patient $patient)
    {
        $this->authorize('chirurgien', Patient::class);
        
        $cacheKey = "consultation_suivi_patient_{$patient->id}";
        
        $consultationdesuivi = Cache::remember($cacheKey, 600, function () use ($patient) {
            return ConsultationSuivi::with('patient')
                ->where('patient_id', $patient->id)
                ->select('id','interrogatoire','commentaire','date_creation','patient_id')
                ->paginate(15); // Added pagination
        });
        
        return view('admin.suivi_consultation.create', compact('patient','consultationdesuivi'));
    }

    public function store(Request $request)
    {
        $this->authorize('chirurgien', Patient::class);

        $request->validate([
            'interrogatoire' => 'required|string',
            'commentaire' => 'required|string',
            'date_creation' => 'required|date',
            'patient_id' => 'required|integer|exists:patients,id',
        ]);

        DB::transaction(function () use ($request) {
            $consultationsuivi = new ConsultationSuivi();
            $consultationsuivi->interrogatoire = $request->interrogatoire;
            $consultationsuivi->commentaire = $request->commentaire;
            $consultationsuivi->date_creation = $request->date_creation;
            $consultationsuivi->patient_id = $request->patient_id;
            $consultationsuivi->user_id = Auth::id();
            $consultationsuivi->save();

            Cache::forget("consultation_suivi_patient_{$consultationsuivi->patient_id}");
        });

        return back()->with('success', 'La consultation de suivi a été créée avec succès.');
    }


    public function show(Request $request, $id)
    {

        $consultationdesuivi = ConsultationSuivi::find( $id);

        return view('admin.suivi_consultation.show', compact('consultationdesuivi'));
    }

   
}

