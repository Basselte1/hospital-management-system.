<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\SectionLaboratoire
 *
 * Represents a laboratory discipline category (e.g. "Hématologie").
 * Previously these were hardcoded as PHP constants; now fully managed
 * by the Admin.
 *
 * Managed by: Admin (role 1) via SectionLaboratoireController.
 * Readable by: Laborantin (10), Secrétaire (6), Médecin (2), Admin (1).
 */
class SectionLaboratoire extends Model
{
    protected $table = 'sections_laboratoire';

    protected $fillable = [
        'slug',
        'label',
        'icon',
        'color_classes',
        'ordre',
        'actif',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'actif'      => 'boolean',
        'ordre'      => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function tarifs()
    {
        return $this->hasMany(TarifLaboratoire::class, 'section_id')
                    ->orderBy('nom_test');
    }

    public function tarifsActifs()
    {
        return $this->tarifs()->where('actif', true);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre')->orderBy('label');
    }

    // ── Static helpers ───────────────────────────────────────────────────────

    /**
     * Returns all active sections as a keyed collection, cached for 1 hour.
     * Shape: slug → SectionLaboratoire instance.
     *
     * Use this instead of the old SECTIONS constant.
     */
    public static function getAllActive(): \Illuminate\Support\Collection
    {
        return Cache::remember('sections_laboratoire_actives', 3600, function () {
            return static::actif()
                ->ordered()
                ->get()
                ->keyBy('slug');
        });
    }

    /**
     * Returns a simple slug → label array (mirrors the old SECTIONS constant).
     */
    public static function asKeyValueArray(): array
    {
        return static::getAllActive()
            ->map(fn($s) => $s->label)
            ->all();
    }

    /**
     * Returns all active tests grouped by section slug.
     * Shape: ['biochimie' => ['Glycémie', 'Créatinine', ...], ...]
     *
     * Replaces the old TESTS_PAR_SECTION constant.
     * Cached for 1 hour — cleared by TarifLaboratoireController on write.
     */
    public static function getTestsParSection(): array
    {
        return Cache::remember('tests_par_section', 3600, function () {
            return TarifLaboratoire::actif()
                ->with('section:id,slug')
                ->get(['section_id', 'nom_test'])
                ->groupBy(fn($t) => $t->section->slug ?? $t->section_id)
                ->map(fn($group) => $group->pluck('nom_test')->all())
                ->all();
        });
    }

    /**
     * Returns tariffs grouped by section slug → test name → price.
     * Shape: ['biochimie' => ['Glycémie' => 3000, ...], ...]
     *
     * Used for the JS price calculator on the create-bon form.
     */
    public static function getTarifsParSection(): array
    {
        return Cache::remember('tarifs_par_section', 3600, function () {
            return TarifLaboratoire::actif()
                ->with('section:id,slug')
                ->get(['section_id', 'nom_test', 'prix_unitaire'])
                ->groupBy(fn($t) => $t->section->slug ?? $t->section_id)
                ->map(fn($group) => $group->pluck('prix_unitaire', 'nom_test')->all())
                ->all();
        });
    }

    /**
     * Flush all section/tariff caches. Call after any write.
     */
    public static function flushCache(): void
    {
        Cache::forget('sections_laboratoire_actives');
        Cache::forget('tests_par_section');
        Cache::forget('tarifs_par_section');
        Cache::forget('tarifs_laboratoire_all');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getIconClassAttribute(): string
    {
        return 'fas ' . ltrim($this->icon, 'fas ');
    }
}