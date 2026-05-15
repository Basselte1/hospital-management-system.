<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TarifLaboratoire;
use App\Models\SectionLaboratoire;

/**
 * TarifLaboratoireController
 *
 * Full CRUD:  Admin (1) only
 * Read-only:  Laborantin (10), Secrétaire (6), Admin (1)
 *
 * Sections are now loaded from `sections_laboratoire` (DB), NOT from
 * a hardcoded constant.
 *
 * Routes in web.php (add these):
 *
 *   Route::get ('tarifs-laboratoire',               'TarifLaboratoireController@index')  ->name('tarifs_labo.index');
 *   Route::get ('tarifs-laboratoire/create',         'TarifLaboratoireController@create') ->name('tarifs_labo.create');
 *   Route::post('tarifs-laboratoire',                'TarifLaboratoireController@store')  ->name('tarifs_labo.store');
 *   Route::get ('tarifs-laboratoire/{tarif}/edit',   'TarifLaboratoireController@edit')   ->name('tarifs_labo.edit');
 *   Route::patch('tarifs-laboratoire/{tarif}',       'TarifLaboratoireController@update') ->name('tarifs_labo.update');
 *   Route::delete('tarifs-laboratoire/{tarif}',      'TarifLaboratoireController@destroy')->name('tarifs_labo.destroy');
 *   Route::post('tarifs-laboratoire/{tarif}/toggle', 'TarifLaboratoireController@toggle') ->name('tarifs_labo.toggle');
 */
class TarifLaboratoireController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 6, 10])) {
            abort(403);
        }

        $section  = $request->input('section', '');   // renamed: blade uses $section

        // Load sections as slug => label array (blade iterates: $sKey => $sLabel)
        $sections = SectionLaboratoire::ordered()->get()->pluck('label', 'slug')->all();

        $search = $request->input('search', '');
        $showOnly = $request->input('show', 'actif'); // form sends 'show', default to actif

        $query = TarifLaboratoire::with('section:id,slug,label')
            ->with('createdBy:id,name,prenom')
            ->orderBy('section')
            ->orderBy('nom_test');

        if ($section) {
            $query->where('section', $section);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom_test',      'like', "%{$search}%")
                  ->orWhere('section_label','like', "%{$search}%");
            });
        }

        if ($showOnly === 'actif') {
            $query->actif();
        }

        $tarifs = $query->get()->groupBy('section');

        return view('admin.laboratoire.tarifs.index', compact(
            'tarifs', 'sections', 'section', 'search', 'showOnly'
        ));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create()
    {
        $this->adminOnly();
        $sections = SectionLaboratoire::actif()->ordered()->get();
        return view('admin.laboratoire.tarifs.create', compact('sections'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $this->adminOnly();

        $validated = $request->validate([
            'section_id'    => 'required|integer|exists:sections_laboratoire,id',
            'nom_test'      => 'required|string|max:255',
            'prix_unitaire' => 'required|integer|min:0',
            'description'   => 'nullable|string|max:500',
            'actif'         => 'boolean',
        ], [
            'section_id.required' => 'Veuillez choisir une section.',
            'section_id.exists'   => 'Section invalide.',
            'prix_unitaire.min'   => 'Le prix ne peut pas être négatif.',
        ]);

        $section = SectionLaboratoire::findOrFail($validated['section_id']);

        // Prevent duplicates within the same section
        $exists = TarifLaboratoire::where('section_id', $section->id)
            ->where('nom_test', $validated['nom_test'])
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', "Le test « {$validated['nom_test']} » existe déjà dans la section « {$section->label} ».");
        }

        TarifLaboratoire::create([
            'section_id'    => $section->id,
            'section'       => $section->slug,      // keep legacy column in sync
            'section_label' => $section->label,
            'nom_test'      => $validated['nom_test'],
            'prix_unitaire' => $validated['prix_unitaire'],
            'description'   => $validated['description'] ?? null,
            'actif'         => $request->boolean('actif', true),
            'created_by'    => Auth::id(),
            'updated_by'    => Auth::id(),
        ]);

        SectionLaboratoire::flushCache();

        return redirect()->route('tarifs_labo.index')
            ->with('success', "Tarif « {$validated['nom_test']} » ajouté avec succès.");
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(TarifLaboratoire $tarif)
    {
        $this->adminOnly();
        $sections = SectionLaboratoire::actif()->ordered()->get();
        return view('admin.laboratoire.tarifs.edit', compact('tarif', 'sections'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, TarifLaboratoire $tarif)
    {
        $this->adminOnly();

        $validated = $request->validate([
            'section_id'    => 'required|integer|exists:sections_laboratoire,id',
            'nom_test'      => 'required|string|max:255',
            'prix_unitaire' => 'required|integer|min:0',
            'description'   => 'nullable|string|max:500',
            'actif'         => 'boolean',
        ]);

        $section = SectionLaboratoire::findOrFail($validated['section_id']);

        // Prevent duplicate (exclude self)
        $exists = TarifLaboratoire::where('section_id', $section->id)
            ->where('nom_test', $validated['nom_test'])
            ->where('id', '!=', $tarif->id)
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', "Un autre test portant ce nom existe déjà dans « {$section->label} ».");
        }

        $tarif->update([
            'section_id'    => $section->id,
            'section'       => $section->slug,
            'section_label' => $section->label,
            'nom_test'      => $validated['nom_test'],
            'prix_unitaire' => $validated['prix_unitaire'],
            'description'   => $validated['description'] ?? null,
            'actif'         => $request->boolean('actif', true),
            'updated_by'    => Auth::id(),
        ]);

        SectionLaboratoire::flushCache();

        return redirect()->route('tarifs_labo.index')
            ->with('success', "Tarif mis à jour avec succès.");
    }

    // ── Toggle ────────────────────────────────────────────────────────────────

    public function toggle(TarifLaboratoire $tarif)
    {
        if (!in_array(Auth::user()->role_id, [1, 10])) {
            abort(403);
        }

        $tarif->update([
            'actif'      => !$tarif->actif,
            'updated_by' => Auth::id(),
        ]);

        SectionLaboratoire::flushCache();

        return back()->with('success', $tarif->actif
            ? "Test « {$tarif->nom_test} » réactivé."
            : "Test « {$tarif->nom_test} » désactivé.");
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    public function destroy(TarifLaboratoire $tarif)
    {
        $this->adminOnly();

        $nom = $tarif->nom_test;
        $tarif->delete();

        SectionLaboratoire::flushCache();

        return redirect()->route('tarifs_labo.index')
            ->with('success', "Tarif « {$nom} » supprimé.");
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    private function adminOnly(): void
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, "Accès réservé à l'administrateur.");
        }
    }
}