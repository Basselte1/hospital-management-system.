<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Examen;
use App\Http\Requests\ImagRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PatientimageController extends Controller
{
    /**
     * Display a paginated list of patients with their scanned exams.
     * (Matches the structure expected by index.blade.php)
     */
    public function index(Request $request)
    {
         // Adjust policy as needed

        $perPage = (int) $request->input('per_page', 20);
        $search = $request->input('search');

        // Eager load examens for each patient, paginate patients
        $patients = Patient::with(['examens' => function ($q) {
                $q->latest();
            }])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('numero_dossier', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage);

        // For pagination links – the view expects $examens->links()
        // We reuse the same paginator instance so the links work correctly.
        $examens = $patients;

        return view('admin.examens.index', compact('patients', 'examens', 'search', 'perPage'));
    }

    /**
     * Show form to upload a new scanned exam for a specific patient
     */
    public function create(Patient $patient)
    {
        $this->authorize('create', Examen::class);
        return view('admin.examens.create', compact('patient'));
    }

    /**
     * Store a newly uploaded scanned exam
     */
    public function store(ImagRequest $request)
    {
        $this->authorize('create', Examen::class);

        $request->validate([
            'type' => 'required|string|max:255', // matches create.blade.php
            'image' => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        DB::transaction(function () use ($request) {
            $patient = Patient::select('id')->findOrFail($request->input('patient_id'));
            $path = $request->file('image')->store('examens_scannes', 'public');

            // Copy to public/storage for Windows compatibility
            $source = storage_path('app/public/' . $path);
            $destinationDir = public_path('storage/examens_scannes');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }
            $destination = $destinationDir . '/' . basename($path);
            copy($source, $destination);

            $examen = new Examen([
                'nom'         => $request->input('type'), // map 'type' to 'nom'
                'description' => $request->input('description') ?? '',
                'image'       => $path,
            ]);

            $patient->examens()->save($examen);
        });

        Cache::tags(['examens', 'patients'])->flush();

        return redirect()->route('patients.show', $request->input('patient_id'))
            ->with('success', 'Examen scanné ajouté avec succès !');
    }

    /**
     * Display a single scanned exam (show.blade.php)
     */
    public function show($id)
    {
        $examen = Examen::with('patient')->findOrFail($id);
        $this->authorize('view', $examen); // or use policy

        return view('admin.examens.show', compact('examen'));
    }

    /**
     * Display all scanned exams of a specific patient (unpaginated, for modals)
     */
    public function showall(Patient $patient)
    {
        $this->authorize('view', $patient);
        $examens = $patient->examens()->latest()->get();
        return view('admin.examens.showall', compact('patient', 'examens'));
    }

    /**
     * Remove the specified scanned exam
     */
    public function destroy($id, Request $request)
    {
        $this->authorize('delete', Examen::class);

        $image = Examen::select(['id', 'image', 'patient_id'])->findOrFail($id);
        Storage::disk('public')->delete($image->image);

        // Also delete from public/storage for Windows compatibility
        $publicPath = public_path('storage/' . $image->image);
        if (file_exists($publicPath)) {
            unlink($publicPath);
        }

        $image->delete();

        Cache::tags(['examens', 'patients'])->flush();

        return redirect()->route('patients.show', $request->get('patient_id'))
            ->with('success', 'Examen scanné supprimé avec succès !');
    }
}