<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ExamenLaboratoire;
use App\Models\Patient;
use App\Models\User;
use App\Models\Prescription;
use App\Models\TarifLaboratoire;
use App\Models\SectionLaboratoire;

/**
 * LaboratoireController
 *
 * UPDATED: SECTIONS and TESTS_PAR_SECTION are no longer hardcoded PHP constants.
 * They are loaded from `sections_laboratoire` and `tarifs_laboratoire` via
 * SectionLaboratoire::getAllActive() and SectionLaboratoire::getTestsParSection().
 *
 * This means an Admin can add/remove/rename disciplines and their tests at any time
 * without touching PHP code.
 */
class LaboratoireController extends Controller
{
    // ── Section helpers (replaces hardcoded constants) ────────────────────────

    /**
     * Returns active sections as slug → label array.
     * Cached — see SectionLaboratoire::getAllActive().
     */
    private function getSections(): array
    {
        return SectionLaboratoire::asKeyValueArray();
    }

    /**
     * Returns the full SectionLaboratoire collection (with icon, color, etc.)
     * for use in views that need more than just slug/label.
     */
    private function getSectionObjects(): \Illuminate\Support\Collection
    {
        return SectionLaboratoire::getAllActive();
    }

    /**
     * Returns tests grouped by section slug.
     * Cached — see SectionLaboratoire::getTestsParSection().
     */
    private function getTestsParSection(): array
    {
        return SectionLaboratoire::getTestsParSection();
    }

    // ── Index ──────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $this->authorize('laboratoire', Patient::class);

        $search  = $request->input('search');
        $statut  = $request->input('statut');
        $perPage = (int) $request->input('per_page', 20);
        $user    = Auth::user();

        $query = ExamenLaboratoire::with([
                'patient:id,name,prenom,numero_dossier',
                'laborantin:id,name,prenom',
            ])
            ->select([
                'id', 'patient_id', 'user_id', 'numero_bon',
                'statut', 'is_en_cours', 'is_valide', 'is_remis', 'is_archive',
                'date_prelevement', 'date_validation', 'created_at',
                'montant_paye', 'mode_paiement',
            ]);

        if ($user->role_id === 10) {
            $query->where(function ($q) use ($user) {
                $q->where(function ($q2) {
                        $q2->where('is_en_cours', false)
                           ->where('is_valide', false)
                           ->where('is_remis', false)
                           ->where('is_archive', false);
                    })
                  ->orWhere('is_en_cours', true)
                  ->orWhere('user_id', $user->id);
            });
        }

        if ($statut) {
            match ($statut) {
                'en_attente' => $query->where('is_en_cours', false)
                                      ->where('is_valide', false)
                                      ->where('is_remis', false)
                                      ->where('is_archive', false),
                'en_cours'   => $query->where('is_en_cours', true)
                                      ->where('is_valide', false),
                'valide'     => $query->where('is_valide', true)
                                      ->where('is_remis', false),
                'remis'      => $query->where('is_remis', true)
                                      ->where('is_archive', false),
                'archive'    => $query->where('is_archive', true),
                default      => null,
            };
        }

        if ($search) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('numero_dossier', 'like', "%{$search}%");
            });
        }

        $examens = $query->latest()->paginate($perPage);

        $stats = [
            'en_attente' => ExamenLaboratoire::where('is_en_cours', false)
                                ->where('is_valide', false)
                                ->where('is_remis', false)
                                ->where('is_archive', false)->count(),
            'en_cours'   => ExamenLaboratoire::where('is_en_cours', true)
                                ->where('is_valide', false)->count(),
            'valide'     => ExamenLaboratoire::where('is_valide', true)
                                ->where('is_remis', false)->count(),
            'remis'      => ExamenLaboratoire::where('is_remis', true)
                                ->where('is_archive', false)->count(),
            'archive'    => ExamenLaboratoire::where('is_archive', true)->count(),
        ];

        return view('admin.laboratoire.index', compact('examens', 'search', 'statut', 'perPage', 'stats'));
    }

    // ── Create ─────────────────────────────────────────────────────────────────

    public function create(?Patient $patient = null)
    {
        $this->authorize('laboratoireCreate', Patient::class);

        $medecins = Cache::remember('medecins_list', 3600, function () {
            return User::where('role_id', 2)
                ->select('id', 'name', 'prenom', 'specialite')
                ->orderBy('name')
                ->get();
        });

        // Load sections from DB (slug → SectionLaboratoire object, with icon/color)
        $sectionObjects = $this->getSectionObjects(); // Collection keyed by slug
        $sectionSlugs   = $sectionObjects->keys()->all();

        $latestPrescription       = null;
        $prescribedTestsBySection = [];
        $doneTestsBySection       = [];

        if ($patient) {
            $latestPrescription = Prescription::where('patient_id', $patient->id)
                ->latest()
                ->first($sectionSlugs);    // only fetch the section columns that exist

            if ($latestPrescription) {
                foreach ($sectionSlugs as $field) {
                    $raw = trim($latestPrescription->$field ?? '');
                    if ($raw === '') continue;
                    $tests = array_values(array_filter(array_map('trim', explode(',', $raw))));
                    if (!empty($tests)) {
                        $prescribedTestsBySection[$field] = $tests;
                    }
                }
            }

            // Collect tests already validated for this patient
            $resultColumns = array_map(fn($s) => "{$s}_resultats", $sectionSlugs);
            $previousBons  = ExamenLaboratoire::where('patient_id', $patient->id)
                ->where('is_valide', true)
                ->get(array_merge($sectionSlugs, $resultColumns));

            foreach ($previousBons as $bon) {
                foreach ($sectionSlugs as $section) {
                    $results = $bon->{"{$section}_resultats"} ?? [];
                    if (!empty($results)) {
                        foreach (array_keys($results) as $t) {
                            $doneTestsBySection[$section][] = $t;
                        }
                    }
                }
            }
            foreach ($doneTestsBySection as $s => $tests) {
                $doneTestsBySection[$s] = array_values(array_unique($tests));
            }
        }

        // Tariffs for JS price calculator — from DB (cached)
        $tarifsBySection = SectionLaboratoire::getTarifsParSection();

        return view('admin.laboratoire.create', compact(
            'patient', 'medecins', 'sectionObjects', 'latestPrescription',
            'prescribedTestsBySection', 'doneTestsBySection', 'tarifsBySection'
        ));
    }

    // ── Store ──────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $this->authorize('laboratoireCreate', Patient::class);

        // Validate section slugs dynamically from DB
        $validSlugs = SectionLaboratoire::actif()->pluck('slug')->implode(',');

        $request->validate([
            'patient_id'          => 'required|integer|exists:patients,id',
            'prescripteur_id'     => 'nullable|integer|exists:users,id',
            'prescription_source' => 'nullable|string|max:255',
            'date_prescription'   => 'nullable|date',
            'preparation_requise' => 'nullable|string|max:500',
            'tests'               => 'required|array|min:1',
            'montant_paye'        => 'required|numeric|min:0',
            'mode_paiement'       => 'required|string|max:50',
            'reference_paiement'  => 'nullable|string|max:100',
            'paiement_confirme'   => 'required|accepted',
        ], [
            'tests.required'             => 'Veuillez sélectionner au moins un test.',
            'montant_paye.required'      => 'Le montant encaissé est obligatoire.',
            'paiement_confirme.accepted' => 'Vous devez confirmer avoir encaissé le paiement.',
        ]);

        try {
            $examen = DB::transaction(function () use ($request) {

                $data = $request->only([
                    'patient_id', 'prescripteur_id',
                    'prescription_source', 'date_prescription', 'preparation_requise',
                    'montant_paye', 'mode_paiement', 'reference_paiement',
                ]);

                $data['user_id']           = Auth::id();
                $data['statut']            = 'en_attente';
                $data['paiement_confirme'] = true;
                $data['numero_bon']        = $this->generateNumeroBon();

                // Build per-section data from granular test selections
                $testsInput = $request->input('tests', []);
                foreach ($testsInput as $section => $selectedTests) {
                    $selectedTests = array_filter(array_map('trim', (array) $selectedTests));
                    if (!empty($selectedTests)) {
                        $data[$section] = implode(',', $selectedTests);
                    }
                }

                return ExamenLaboratoire::create($data);
            });

            Cache::tags(['examens_laboratoire', 'patients'])->flush();

            return redirect()
                ->route('laboratoire.bon', $examen->id)
                ->with('success', "Bon n° {$examen->numero_bon} enregistré.");

        } catch (\Exception $e) {
            Log::error('LaboratoireController@store: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erreur lors de l\'enregistrement.');
        }
    }

    // ── Edit ───────────────────────────────────────────────────────────────────

    public function edit(ExamenLaboratoire $laboratoire)
    {
        $this->authorize('laboratoireWrite', Patient::class);

        if ($laboratoire->statut === 'en_attente') {
            $laboratoire->update([
                'user_id'     => Auth::id(),
                'statut'      => 'en_cours',
                'is_en_cours' => true,
            ]);
        }

        $laboratoire->load('patient:id,name,prenom,numero_dossier');

        // Dynamic sections from DB
        $sections       = $this->getSections();
        $sectionObjects = $this->getSectionObjects();
        $testsBySection = $this->getTestsParSection();

        $prescribedTestsBySection = [];
        $latestPrescription = $laboratoire->patient
            ->prescriptions()->latest()->first();

        if ($latestPrescription) {
            foreach (array_keys($sections) as $field) {
                $raw = trim($latestPrescription->$field ?? '');
                if ($raw === '') continue;
                $tests = array_values(array_filter(array_map('trim', explode(',', $raw))));
                if (!empty($tests)) {
                    $prescribedTestsBySection[$field] = $tests;
                }
            }
        }

        return view('admin.laboratoire.edit', compact(
            'laboratoire', 'sections', 'sectionObjects',
            'testsBySection', 'prescribedTestsBySection'
        ));
    }

    // ── Update ─────────────────────────────────────────────────────────────────

    public function update(Request $request, ExamenLaboratoire $laboratoire)
    {
        $this->authorize('laboratoireWrite', Patient::class);

        $request->validate([
            'date_prelevement'       => 'nullable|date',
            'technicien_prelevement' => 'nullable|string|max:255',
            'tube_type'              => 'nullable|string|max:255',
            'site_prelevement'       => 'nullable|string|max:255',
            'statut_specimen'        => 'nullable|in:accepté,rejeté',
            'motif_rejet'            => 'nullable|string|max:500',
            'instrument_utilise'     => 'nullable|string|max:255',
            'lot_reactif'            => 'nullable|string|max:255',
            'cqi_status'             => 'nullable|in:conforme,non_conforme,non_effectue',
            'cqi_note'               => 'nullable|string|max:500',
            'observations'           => 'nullable|string',
            'valide_par'             => 'nullable|string|max:255',
            'clinicien_notifie'      => 'nullable|string|max:255',
            'date_notification'      => 'nullable|date',
            'date_remise_resultat'   => 'nullable|date',
            'action'                 => 'required|in:save,validate,remise',
        ]);

        try {
            DB::transaction(function () use ($request, $laboratoire) {

                $data = $request->only([
                    'date_prelevement', 'technicien_prelevement',
                    'tube_type', 'site_prelevement',
                    'statut_specimen', 'motif_rejet',
                    'instrument_utilise', 'lot_reactif',
                    'cqi_status', 'cqi_note', 'observations',
                    'valide_par', 'clinicien_notifie',
                    'date_notification', 'date_remise_resultat',
                ]);

                // Build structured results per active section (from DB)
                $sections = $this->getSections();
                foreach (array_keys($sections) as $section) {
                    $rawResults = $request->input("resultats_{$section}", []);
                    if (!empty($rawResults)) {
                        $data["{$section}_resultats"] = $rawResults;
                        $data[$section] = implode(', ', array_column($rawResults, 'test'));
                    }
                }

                // Flag critical values
                $critiques = [];
                foreach (array_keys($sections) as $section) {
                    foreach ($request->input("resultats_{$section}", []) as $row) {
                        if (($row['flag'] ?? '') === 'critique') {
                            $critiques[] = [
                                'section' => $sections[$section],
                                'test'    => $row['test'] ?? '',
                                'valeur'  => $row['valeur'] ?? '',
                                'unite'   => $row['unite'] ?? '',
                            ];
                        }
                    }
                }
                if (!empty($critiques)) {
                    $data['valeurs_critiques'] = $critiques;
                }

                $action = $request->input('action');

                if ($action === 'validate') {
                    $laboratoire->update(array_merge($data, [
                        'is_valide'       => true,
                        'is_en_cours'     => false,
                        'valide_par'      => $data['valide_par'] ?? Auth::user()->name,
                        'date_validation' => now(),
                        'statut'          => $laboratoire->is_remis ? 'remis' : 'valide',
                    ]));

                } elseif ($action === 'remise') {
                    $laboratoire->update(array_merge($data, [
                        'is_remis'              => true,
                        'date_remise_resultat'  => $data['date_remise_resultat'] ?? now(),
                        'statut'                => 'remis',
                    ]));

                } else {
                    $laboratoire->update(array_merge($data, [
                        'statut'      => 'en_cours',
                        'is_en_cours' => true,
                    ]));
                }
            });

            Cache::tags(['examens_laboratoire', 'patients'])->flush();

            $msg = match ($request->input('action')) {
                'validate' => 'Résultats validés avec succès.',
                'remise'   => 'Résultats marqués comme remis au patient.',
                default    => 'Résultats enregistrés.',
            };

            return redirect()
                ->route('laboratoire.show', $laboratoire->id)
                ->with('success', $msg);

        } catch (\Exception $e) {
            Log::error('LaboratoireController@update: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    // ── Show ───────────────────────────────────────────────────────────────────

    public function show(ExamenLaboratoire $laboratoire)
    {
        if (Auth::user()->role_id === 6) {
            abort(403, 'Les secrétaires ne sont pas autorisés à consulter les résultats de laboratoire.');
        }
        $this->authorize('laboratoire', Patient::class);

        $laboratoire->load([
            'patient:id,name,prenom,numero_dossier',
            'laborantin:id,name,prenom',
            'prescripteur:id,name,prenom,specialite',
        ]);

        $sections = $this->getSections();   // from DB

        return view('admin.laboratoire.show', compact('laboratoire', 'sections'));
    }

    // ── Patient history ────────────────────────────────────────────────────────

    public function patientHistory(Patient $patient)
    {
        $this->authorize('laboratoire', Patient::class);

        $examens = ExamenLaboratoire::forPatient($patient->id)
            ->with('laborantin:id,name,prenom')
            ->select([
                'id', 'patient_id', 'user_id', 'numero_bon', 'statut',
                'is_en_cours', 'is_valide', 'is_remis', 'is_archive',
                'date_prelevement', 'date_validation', 'created_at',
                'montant_paye', 'mode_paiement',
            ])
            ->latest()
            ->paginate(15);

        return view('admin.laboratoire.patient_history', compact('patient', 'examens'));
    }

    // ── Results by discipline ──────────────────────────────────────────────────

    public function resultsByDiscipline(Request $request)
    {
        if (Auth::user()->role_id === 6) {
            abort(403);
        }
        $this->authorize('laboratoire', Patient::class);

        $sections  = $this->getSections();    // from DB
        $section   = $request->input('section', array_key_first($sections) ?? 'biochimie');
        $search    = $request->input('search');
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');
        $perPage   = (int) $request->input('per_page', 25);

        if (!array_key_exists($section, $sections)) {
            $section = array_key_first($sections) ?? 'biochimie';
        }

        $query = ExamenLaboratoire::with('patient:id,name,prenom,numero_dossier')
            ->select([
                'id', 'patient_id', 'user_id', 'numero_bon',
                'statut', 'is_en_cours', 'is_valide', 'is_remis', 'is_archive',
                'date_prelevement', 'date_validation', 'created_at',
                "{$section}", "{$section}_resultats",
            ])
            ->whereNotNull("{$section}_resultats")
            ->where('is_valide', true);

        if ($search) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('numero_dossier', 'like', "%{$search}%");
            });
        }

        if ($dateFrom) {
            $query->whereDate('date_prelevement', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date_prelevement', '<=', $dateTo);
        }

        $examens = $query->latest('date_prelevement')->paginate($perPage);
        $tarifs  = TarifLaboratoire::actif()
                        ->forSection($section)
                        ->orderBy('nom_test')
                        ->get(['nom_test', 'prix_unitaire']);

        return view('admin.laboratoire.results_by_discipline', compact(
            'examens', 'section', 'sections', 'search',
            'dateFrom', 'dateTo', 'perPage', 'tarifs'
        ));
    }

    // ── Export / Report ────────────────────────────────────────────────────────

    public function exportReport(ExamenLaboratoire $laboratoire)
    {
        if (Auth::user()->role_id === 6) {
            abort(403);
        }

        $this->authorize('laboratoire', Patient::class);

        if (!$laboratoire->is_valide && !$laboratoire->is_remis) {
            return back()->with('error', 'Le rapport ne peut être exporté qu\'après validation.');
        }

        return redirect()->route('print.preview', [
            'type' => 'laboratoire',
            'id'   => $laboratoire->id,
        ]);
    }

    public function printReport(ExamenLaboratoire $laboratoire)
    {
        if (Auth::user()->role_id === 6) {
            abort(403);
        }

        $this->authorize('laboratoire', Patient::class);

        $laboratoire->load([
            'patient:id,name,prenom,numero_dossier',
            'laborantin:id,name,prenom',
            'prescripteur:id,name,prenom,specialite,onmc',
        ]);

        $sections        = $this->getSections();
        $sectionsActives = $laboratoire->getSectionsActives();

        return view('admin.laboratoire.rapport', compact('laboratoire', 'sections', 'sectionsActives'));
    }

    // ── Bon receipt ────────────────────────────────────────────────────────────

    public function bonReceipt(ExamenLaboratoire $laboratoire)
    {
        $this->authorize('laboratoire', Patient::class);

        $laboratoire->load([
            'patient:id,name,prenom,numero_dossier',
            'laborantin:id,name,prenom',
            'prescripteur:id,name,prenom,specialite',
        ]);

        return view('admin.laboratoire.bon_receipt', compact('laboratoire'));
    }

    // ── Destroy (archive) ──────────────────────────────────────────────────────

    public function destroy(ExamenLaboratoire $laboratoire)
    {
        $this->authorize('laboratoireWrite', Patient::class);

        try {
            $laboratoire->marquerArchive();
            Cache::tags(['examens_laboratoire', 'patients'])->flush();
            return redirect()->route('laboratoire.index')->with('success', 'Examen archivé.');
        } catch (\Exception $e) {
            Log::error('LaboratoireController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'archivage.');
        }
    }

    // ── JSON endpoint ──────────────────────────────────────────────────────────

    public function patientInfo(Patient $patient): \Illuminate\Http\JsonResponse
    {
        $this->authorize('laboratoireCreate', Patient::class);

        $prescripteurId = null;
        $medecinLabel   = null;

        if ($patient->medecin_r) {
            $parts   = preg_split('/\s+/', trim($patient->medecin_r), 2);
            $medecin = User::where('role_id', 2)
                ->where(function ($q) use ($parts) {
                    $q->where('name',   'like', '%' . ($parts[0] ?? '') . '%')
                      ->orWhere('prenom', 'like', '%' . ($parts[0] ?? '') . '%');
                })
                ->select('id', 'name', 'prenom', 'specialite')
                ->first();

            $prescripteurId = $medecin?->id;
            $medecinLabel   = $medecin
                ? 'Dr ' . $medecin->prenom . ' ' . $medecin->name . ($medecin->specialite ? ' — ' . $medecin->specialite : '')
                : $patient->medecin_r;
        }

        $prescribedSections = [];
        $latestPrescription = $patient->prescriptions()->latest()->first();

        // Section slugs from DB
        $sectionSlugs = SectionLaboratoire::actif()->pluck('slug')->all();

        if ($latestPrescription) {
            foreach ($sectionSlugs as $field) {
                if (!empty($latestPrescription->$field)) {
                    $prescribedSections[] = $field;
                }
            }
            $prescribedSections = array_unique($prescribedSections);
        }

        $prescriptionSource = $patient->dossiers()->latest()->value('service')
            ?? $patient->motif
            ?? null;

        return response()->json([
            'prescripteur_id'     => $prescripteurId,
            'medecin_label'       => $medecinLabel,
            'medecin_r'           => $patient->medecin_r,
            'prescription_source' => $prescriptionSource,
            'prescribed_sections' => array_values($prescribedSections),
            'patient_name'        => $patient->prenom . ' ' . strtoupper($patient->name),
            'numero_dossier'      => $patient->numero_dossier,
        ]);
    }

    // ── Standalone validate / remise endpoints ────────────────────────────────

    public function valider(Request $request, int $id)
    {
        $exam = ExamenLaboratoire::findOrFail($id);
        $this->authorize('laboratoireWrite', Patient::class);

        $exam->marquerValide($request->user()->name);

        return redirect()
            ->route('laboratoire.show', $exam)
            ->with('success', 'Résultats validés.' .
                ($exam->is_remis ? ' (déjà remis au patient)' : ''));
    }

    public function remettre(Request $request, int $id)
    {
        $exam = ExamenLaboratoire::findOrFail($id);
        $this->authorize('laboratoireWrite', Patient::class);

        $exam->marquerRemis();

        return redirect()
            ->route('laboratoire.show', $exam)
            ->with('success', 'Résultats marqués comme remis.' .
                ($exam->is_valide ? ' (déjà validés)' : ''));
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    private function generateNumeroBon(): string
    {
        $prefix = 'LAB-' . now()->format('Ymd') . '-';
        $last   = ExamenLaboratoire::where('numero_bon', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->value('numero_bon');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}