<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SectionLaboratoire;
use App\Models\TarifLaboratoire;

/**
 * SectionLaboratoireController
 *
 * Manages laboratory discipline categories (sections).
 * Access: Admin (role 1) only for write; Admin + Laborantin (10) + Secrétaire (6) for read.
 *
 * Add these routes to web.php inside your auth middleware group:
 *
 *   Route::get ('sections-laboratoire',                    'SectionLaboratoireController@index')  ->name('sections_labo.index');
 *   Route::get ('sections-laboratoire/create',             'SectionLaboratoireController@create') ->name('sections_labo.create');
 *   Route::post('sections-laboratoire',                    'SectionLaboratoireController@store')  ->name('sections_labo.store');
 *   Route::get ('sections-laboratoire/{section}/edit',     'SectionLaboratoireController@edit')   ->name('sections_labo.edit');
 *   Route::patch('sections-laboratoire/{section}',         'SectionLaboratoireController@update') ->name('sections_labo.update');
 *   Route::delete('sections-laboratoire/{section}',        'SectionLaboratoireController@destroy')->name('sections_labo.destroy');
 *   Route::post('sections-laboratoire/reorder',            'SectionLaboratoireController@reorder')->name('sections_labo.reorder');
 *   Route::post('sections-laboratoire/{section}/toggle',   'SectionLaboratoireController@toggle') ->name('sections_labo.toggle');
 */
class SectionLaboratoireController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    public function index()
    {
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 6, 10])) {
            abort(403);
        }

        $sections = SectionLaboratoire::withCount('tarifs')
            ->withCount(['tarifs as tarifs_actifs_count' => fn($q) => $q->where('actif', true)])
            ->ordered()
            ->get();

        return view('admin.laboratoire.sections.index', compact('sections'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create()
    {
        $this->adminOnly();

        // Suggest next ordre value
        $nextOrdre = (SectionLaboratoire::max('ordre') ?? 0) + 1;

        return view('admin.laboratoire.sections.create', compact('nextOrdre'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $this->adminOnly();

        $validated = $request->validate([
            'label'         => 'required|string|max:100',
            'slug'          => 'nullable|string|max:50|alpha_dash',
            'icon'          => 'nullable|string|max:60',
            'color_classes' => 'nullable|string|max:120',
            'ordre'         => 'required|integer|min:0',
            'actif'         => 'boolean',
        ]);

        // Auto-generate slug from label if not provided
        $slug = $validated['slug']
            ?? Str::slug($validated['label'], '_');

        // Ensure uniqueness
        $base  = $slug;
        $count = 1;
        while (SectionLaboratoire::where('slug', $slug)->exists()) {
            $slug = $base . '_' . $count++;
        }

        SectionLaboratoire::create([
            'slug'          => $slug,
            'label'         => $validated['label'],
            'icon'          => $validated['icon'] ?? 'fa-flask',
            'color_classes' => $validated['color_classes'] ?? 'tw-bg-slate-50 tw-border-slate-300',
            'ordre'         => $validated['ordre'],
            'actif'         => $request->boolean('actif', true),
            'created_by'    => Auth::id(),
            'updated_by'    => Auth::id(),
        ]);

        SectionLaboratoire::flushCache();

        return redirect()->route('sections_labo.index')
            ->with('success', "Section « {$validated['label']} » créée avec succès.");
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(SectionLaboratoire $section)
    {
        $this->adminOnly();
        return view('admin.laboratoire.sections.edit', compact('section'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, SectionLaboratoire $section)
    {
        $this->adminOnly();

        $validated = $request->validate([
            'label'         => 'required|string|max:100',
            'icon'          => 'nullable|string|max:60',
            'color_classes' => 'nullable|string|max:120',
            'ordre'         => 'required|integer|min:0',
            'actif'         => 'boolean',
        ]);

        // NOTE: slug is intentionally NOT updatable after creation
        // because it is used as a key in JSON columns and prescription fields.

        $section->update([
            'label'         => $validated['label'],
            'icon'          => $validated['icon'] ?? $section->icon,
            'color_classes' => $validated['color_classes'] ?? $section->color_classes,
            'ordre'         => $validated['ordre'],
            'actif'         => $request->boolean('actif', true),
            'updated_by'    => Auth::id(),
        ]);

        // Also update the denormalised section_label in tarifs
        TarifLaboratoire::where('section_id', $section->id)
            ->update(['section_label' => $validated['label']]);

        SectionLaboratoire::flushCache();

        return redirect()->route('sections_labo.index')
            ->with('success', "Section « {$section->label} » mise à jour.");
    }

    // ── Toggle active ─────────────────────────────────────────────────────────

    public function toggle(SectionLaboratoire $section)
    {
        $this->adminOnly();

        $section->update([
            'actif'      => !$section->actif,
            'updated_by' => Auth::id(),
        ]);

        SectionLaboratoire::flushCache();

        $msg = $section->actif
            ? "Section « {$section->label} » réactivée."
            : "Section « {$section->label} » désactivée.";

        return back()->with('success', $msg);
    }

    // ── Reorder (drag-and-drop via AJAX) ─────────────────────────────────────

    public function reorder(Request $request)
    {
        $this->adminOnly();

        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:sections_laboratoire,id',
        ]);

        foreach ($request->input('order') as $position => $id) {
            SectionLaboratoire::where('id', $id)
                ->update(['ordre' => $position + 1, 'updated_by' => Auth::id()]);
        }

        SectionLaboratoire::flushCache();

        return response()->json(['success' => true]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    public function destroy(SectionLaboratoire $section)
    {
        $this->adminOnly();

        // Refuse if there are active tariffs in this section
        $testsCount = TarifLaboratoire::where('section_id', $section->id)->count();
        if ($testsCount > 0) {
            return back()->with('error',
                "Impossible de supprimer « {$section->label} » : {$testsCount} test(s) y sont rattachés. "
                . "Supprimez d'abord les tests ou déplacez-les vers une autre section."
            );
        }

        $label = $section->label;
        $section->delete();

        SectionLaboratoire::flushCache();

        return redirect()->route('sections_labo.index')
            ->with('success', "Section « {$label} » supprimée.");
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    private function adminOnly(): void
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, "Accès réservé à l'administrateur.");
        }
    }
}