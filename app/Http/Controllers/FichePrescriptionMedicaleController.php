<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use App\Models\PrescriptionMedicale;
use App\Models\AdminPrescriptionMedicale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class FichePrescriptionMedicaleController extends Controller
{
    /**
     * Display prescriptions for a patient.
     * 
     * @param int $patient_id
     * @return \Illuminate\View\View
     */
    public function index($patient_id)
    {
        $this->authorize('infirmier_chirurgien', Patient::class);
        
        $patient = Patient::select(['id', 'name', 'prenom'])->findOrFail($patient_id);
        
        $cacheKey = "prescription_medicales_patient_{$patient_id}";
        
        $prescription_medicales = Cache::remember($cacheKey, 600, function () use ($patient_id) {
            return PrescriptionMedicale::with([
                    'patient:id,name,prenom',
                    'user:id,name,prenom',
                    'adminPrescriptionMedicales.user:id,name'
                ])
                ->where('patient_id', $patient_id)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(20);
        });

        // Get the most recent prescription to display patient-level info
        $latest_prescription = PrescriptionMedicale::where('patient_id', $patient_id)
            ->orderBy('date', 'desc')
            ->first();

        // NEW: Get allergie from latest consultation if not in prescription
        $latest_consultation = \App\Models\Consultation::where('patient_id', $patient_id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Priority: Use prescription allergie if exists, otherwise use consultation allergie
        $allergie = $latest_prescription->allergie ?? $latest_consultation->allergie ?? '';

        // Create a virtual "fiche" object for blade compatibility
        $fiche_prescription_medicale = (object) [
            'patient_id' => $patient_id,
            'allergie' => $allergie,  // Updated to use merged allergie from consultation or prescription
            'regime' => $latest_prescription->regime ?? '',
            'consultation_specialise' => $latest_prescription->consultation_specialise ?? '',
            'protocole' => $latest_prescription->protocole ?? '',
            'prescription_medicales' => $prescription_medicales
        ];

        $infirmieres = Cache::tags(['users'])->remember('infirmieres_role_4', 30, function () {
            return User::where('role_id', 4)
                ->select(['id', 'name'])
                ->get();
        });

        return view('admin.consultations.infirmiers.index_prescription_medicale', [
            'patient' => $patient,
            'infirmieres' => $infirmieres,
            'fiche_prescription_medicale' => $fiche_prescription_medicale,
            'prescription_medicales' => $prescription_medicales,
        ]);
    }

    /**
     * Store patient-level information (allergie, regime, etc.).
     * This updates the latest prescription or creates a placeholder.
     * 
     * @param Request $request
     * @param int $patient_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $patient_id)
    {
        $this->authorize('medecin', Patient::class);
        
        $validated = $request->validate([
            'allergie' => 'required|string',
            'regime' => 'required|string',
            'consultation_specialise' => 'required|string',
            'protocole' => 'nullable|string',
        ]);
        
        try {
            DB::transaction(function () use ($patient_id, $validated) {
                // Update the most recent prescription or create a new one
                $latestPrescription = PrescriptionMedicale::where('patient_id', $patient_id)
                    ->orderBy('date', 'desc')
                    ->first();

                if ($latestPrescription) {
                    // Update existing prescription
                    $latestPrescription->update($validated);
                } else {
                    // Create a placeholder prescription with patient info
                    PrescriptionMedicale::create(array_merge($validated, [
                        'patient_id' => $patient_id,
                        'user_id' => Auth::id(),
                        'date' => Carbon::today(),
                        'medicament' => 'Information patient mise à jour',
                        'posologie' => '-',
                        'voie' => '-',
                        'heure' => 0,
                    ]));
                }
                
                Cache::forget("prescription_medicales_patient_{$patient_id}");
            });
            
            return redirect()
                ->back()
                ->with('success', 'Informations enregistrées avec succès');

        } catch (\Exception $e) {
            Log::error('Error storing fiche info: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Store a new prescription medicale.
     * Now includes auto-sync of allergie from consultation.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function prescriptionMedicaleStore(Request $request)
    {
        $this->authorize('medecin', Patient::class);
        
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicament' => 'required|string',
            'posologie' => 'required|string',
            'horaire' => 'required|array',
            'voie' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $patient_id = $request->input('patient_id');
                
                // Get the latest prescription to copy patient-level info
                $latestPrescription = PrescriptionMedicale::where('patient_id', $patient_id)
                    ->orderBy('date', 'desc')
                    ->first();

                // NEW: Get allergie from latest consultation
                $latestConsultation = \App\Models\Consultation::where('patient_id', $patient_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $prescriptionData = [
                    'user_id' => Auth::id(),
                    'patient_id' => $patient_id,
                    'medicament' => $request->input('medicament'),
                    'posologie' => $request->input('posologie'),
                    'voie' => $request->input('voie'),
                    'date' => Carbon::today(),
                    'heure' => 8, // Default value
                ];

                // Copy patient-level info with priority: existing prescription > consultation
                if ($latestPrescription) {
                    $prescriptionData['allergie'] = $latestPrescription->allergie ?? $latestConsultation->allergie ?? '';
                    $prescriptionData['regime'] = $latestPrescription->regime;
                    $prescriptionData['consultation_specialise'] = $latestPrescription->consultation_specialise;
                    $prescriptionData['protocole'] = $latestPrescription->protocole;
                } else if ($latestConsultation) {
                    // If no prescription exists, use consultation data
                    $prescriptionData['allergie'] = $latestConsultation->allergie ?? '';
                    $prescriptionData['regime'] = '';
                    $prescriptionData['consultation_specialise'] = '';
                    $prescriptionData['protocole'] = '';
                }

                // Create new prescription
                $prescription = new PrescriptionMedicale($prescriptionData);
                
                // Use the accessor to convert horaire array to time slot fields
                $prescription->horaire = $request->input('horaire');
                
                $prescription->save();
                
                Cache::forget("prescription_medicales_patient_{$patient_id}");
            });

            return redirect()
                ->back()
                ->with('success', 'Prescription enregistrée avec succès');

        } catch (\Exception $e) {
            Log::error('Error storing prescription: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Store administration record (when nurse gives medication).
     * 
     * @param Request $request
     * @param int $prescription_medicale_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function AdminPMStore(Request $request, $prescription_medicale_id)
    {
        $this->authorize('infirmier', Patient::class);
        
        $request->validate([
            'matin' => 'required_without_all:apre_midi,soir,nuit',
            'apre_midi' => 'required_without_all:matin,soir,nuit',
            'soir' => 'required_without_all:matin,apre_midi,nuit',
            'nuit' => 'required_without_all:matin,apre_midi,soir',
        ]);

        try {
            $prescription_medicale = PrescriptionMedicale::findOrFail($prescription_medicale_id);
            
            $adminPrescriptionMedicale = new AdminPrescriptionMedicale([
                'prescription_medicale_id' => $prescription_medicale_id,
                'user_id' => Auth::id(),
                'matin' => $request->input('matin'),
                'apre_midi' => $request->input('apre_midi'),
                'soir' => $request->input('soir'),
                'nuit' => $request->input('nuit'),
            ]);

            if ($request->filled('date')) {
                $adminPrescriptionMedicale->created_at = $request->input('date') . ' ' . now()->format('H:i:s');
            }
            
            $prescription_medicale->adminPrescriptionMedicales()->save($adminPrescriptionMedicale);
            
            $patient_id = $prescription_medicale->patient_id;
            Cache::forget("prescription_medicales_patient_{$patient_id}");

            return redirect()
                ->back()
                ->with('success', 'Administration enregistrée avec succès');

        } catch (\Exception $e) {
            Log::error('Error storing admin prescription: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form for a prescription.
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $prescription_medicale = PrescriptionMedicale::findOrFail($id);
            $patient_id = request('patient');
            $patient = Patient::findOrFail($patient_id);

            // Verify the prescription belongs to this patient
            if ($prescription_medicale->patient_id != $patient->id) {
                return redirect()->back()
                    ->with('error', 'Cette prescription n\'appartient pas à ce patient.');
            }

            return view('admin.consultations.infirmiers.form.prescription_medicale_edit',
                       compact('prescription_medicale', 'patient'));

        } catch (\Exception $e) {
            Log::error('Error editing prescription: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Erreur lors du chargement: ' . $e->getMessage());
        }
    }

    /**
     * Update a prescription.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicament' => 'required|string|max:255',
            'posologie' => 'required|string|max:255',
            'horaire' => 'required|array|min:1',
            'horaire.*' => 'required|string',
            'voie' => 'required|string|max:100',
        ], [
            'horaire.required' => 'Veuillez sélectionner au moins un horaire d\'administration.',
            'horaire.min' => 'Veuillez sélectionner au moins un horaire d\'administration.',
        ]);

        try {
            $patient_id = $request->input('patient_id');

            DB::transaction(function () use ($request, $id, $patient_id) {
                $prescription_medicale = PrescriptionMedicale::findOrFail($id);
                $patient = Patient::findOrFail($request->input('patient_id'));
                
                // Verify prescription belongs to patient
                if ($prescription_medicale->patient_id != $patient->id) {
                    throw new \Exception('Prescription non valide pour ce patient.');
                }
                
                // Update prescription
                $prescription_medicale->medicament = $request->input('medicament');
                $prescription_medicale->posologie = $request->input('posologie');
                $prescription_medicale->voie = $request->input('voie');
                
                // Use accessor to convert horaire array to time slots
                $prescription_medicale->horaire = $request->input('horaire');
                
                $prescription_medicale->save();
                
                // Clear cache
                Cache::forget("prescription_medicales_patient_{$patient_id}");
            });

            return redirect()
                ->route('fiche.prescription_medicale.index', $request->input('patient_id'))
                ->with('success', 'La prescription médicale a été modifiée avec succès !!');

        } catch (\Exception $e) {
            Log::error('Error updating prescription: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la mise à jour.')
                ->withInput();
        }
    }
}