<?php

namespace App\Http\Controllers;

use App\Models\Ordonance;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use ZanySoft\LaravelPDF\Facades\PDF;
use Illuminate\Support\Facades\Log;
use App\Services\PdfService;

class OrdonancesController extends Controller
{

    public function store(Request $request)
    {
        try{
            DB::transaction(function () use ($request) {
                $patient = Patient::select('id')->findOrFail($request->input('patient_id'));

                // Strip any ' | ' that a user might have typed into a field —
                // the pipe sequence is our separator and must not appear inside values.
                $sanitize = fn(array $arr) => array_map(
                    fn($v) => str_replace(' | ', ' ', trim($v)),
                    $arr
                );

                Ordonance::create([
                    'user_id'     => auth()->id(),
                    'patient_id'  => $patient->id,
                    'description' => implode(' | ', $sanitize($request->input('description', []))),
                    'medicament'  => implode(' | ', $sanitize($request->input('medicament', []))),
                    'quantite'    => implode(' | ', $sanitize($request->input('quantite', []))),
                ]);
                Cache::forget('ordonances_patient_' . $patient->id);
            });

            return redirect()->back()->with('success', 'La nouvelle ordonance a été crée avec succès !!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la creation  de l\odonnance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la creation de l\'ordonnance.')
            ->withInput();
        }    
    }


    /**
     * Parse a pipe-separated field into an array.
     * Like Ordonance::parsePipeField but accessible statically here.
     * NEVER splits on comma — see Ordonance model for the rationale.
     */
    private function parsePipe(?string $value): array
    {
        $value = trim((string) ($value ?? ''));
        if ($value === '') return [''];
        if (str_contains($value, ' | ')) {
            return array_map('trim', explode(' | ', $value));
        }
        // Legacy single-item (no pipe separator) — keep as one entry
        return [$value];
    }

    public function edit($id, Request $request)
    {
        try {
            $ordonance = Ordonance::findOrFail($id);
            $patient_id = $request->query('patient');
            $patient = Patient::findOrFail($patient_id);

            if ($ordonance->patient_id != $patient->id) {
                return redirect()->back()->with('error', 'Cette ordonnance n\'appartient pas à ce patient.');
            }

            return view('admin.prescriptions.ordonance_edit', compact('ordonance', 'patient'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'édition de l\'ordonnance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du chargement de l\'ordonnance.'. $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id'    => 'required|exists:patients,id',
            'medicament'    => 'required|array|min:1',
            'medicament.*'  => 'required|string|max:255',
            'quantite'      => 'required|array|min:1',
            'quantite.*'    => 'required|string|max:100',
            'description'   => 'required|array|min:1',
            'description.*' => 'required|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $ordonance = Ordonance::findOrFail($id);
                $patient = Patient::select('id')->findOrFail($request->input('patient_id'));

                if ($ordonance->patient_id != $patient->id) {
                    throw new \Exception('Ordonnance non valide pour ce patient.');
                }

                // Strip any ' | ' a user might have typed inside a field value
                $sanitize = fn(array $arr) => array_map(
                    fn($v) => str_replace(' | ', ' ', trim($v)),
                    $arr
                );

                $ordonance->update([
                    'description' => implode(' | ', $sanitize($request->input('description', []))),
                    'medicament'  => implode(' | ', $sanitize($request->input('medicament', []))),
                    'quantite'    => implode(' | ', $sanitize($request->input('quantite', []))),
                ]);

                Cache::forget('ordonances_patient_' . $patient->id);
            });

            return redirect()->back()->with('success', 'L\'ordonnance a été modifiée avec succès !!');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'ordonnance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de l\'ordonnance.')
            ->withInput();
        }
    }


    public function export_pdf($id)
    {
        set_time_limit(120);
        ini_set('memory_limit', '256M');

        try {
            // Fetch model with relationships
            $ordonance = Ordonance::with([
                    'patient:id,name,prenom,numero_dossier',
                    'user:id,name'
                ])
                ->select([
                    'id', 'patient_id', 'user_id', 'description',
                    'medicament', 'quantite', 'created_at'
                ])
                ->findOrFail($id);

            // Clear any existing output
            if (ob_get_length()) {
                ob_end_clean();
            }

            // Safe filename generation
            $numeroDossier = $ordonance->patient->numero_dossier ?? 'unknown';
            $nomPatient = preg_replace('/[^a-zA-Z0-9_-]/', '_', $ordonance->patient->name ?? 'unknown');
           

            return redirect()->route('print.preview', [
                'type' => 'ordonance',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Ordonance PDF Error: ' . $e->getMessage());

            if (ob_get_length()) {
                ob_end_clean();
            }

            return redirect()->back()->with('error', 'Erreur PDF ordonance');
        }
    }




    public function ordonance_create(Patient $patient)
    {
        return view('admin.prescriptions.ordonance_create', compact('patient'));
    }

}