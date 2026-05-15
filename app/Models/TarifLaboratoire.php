<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TarifLaboratoire
 *
 * Tariff catalog for laboratory tests.
 * Each row = one named test inside a discipline section, with its unit price in FCFA.
 *
 * Managed by Admin (role 1) only via TarifLaboratoireController.
 * Read-only access for Laborantin (10) and Secrétaire (6).
 *
 * UPDATED: `section_id` FK added; `section` slug column kept for backward compatibility.
 */
class TarifLaboratoire extends Model
{
    protected $table = 'tarifs_laboratoire';

    protected $fillable = [
        'section',          // legacy slug — kept in sync with section_id
        'section_id',       // FK → sections_laboratoire.id  (NEW)
        'section_label',    // denormalised label for display without join
        'nom_test',
        'prix_unitaire',
        'description',
        'actif',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'prix_unitaire' => 'integer',
        'actif'         => 'boolean',
        'section_id'    => 'integer',
        'created_by'    => 'integer',
        'updated_by'    => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function section()
    {
        return $this->belongsTo(SectionLaboratoire::class, 'section_id');
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

    public function scopeForSection($query, string $sectionSlugOrId)
    {
        if (is_numeric($sectionSlugOrId)) {
            return $query->where('section_id', $sectionSlugOrId);
        }
        return $query->where('section', $sectionSlugOrId);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function getPrixFormatteAttribute(): string
    {
        return number_format($this->prix_unitaire, 0, ',', ' ') . ' FCFA';
    }

    /**
     * All valid section slugs, loaded from the DB.
     * Falls back to static list during seeding / before sections exist.
     *
     * @deprecated Use SectionLaboratoire::asKeyValueArray() instead.
     */
    public static function sections(): array
    {
        try {
            return SectionLaboratoire::asKeyValueArray();
        } catch (\Throwable $e) {
            // Fallback for seeders running before the sections table exists
            return [
                'hematologie'   => 'Hématologie',
                'hemostase'     => 'Hémostase',
                'biochimie'     => 'Biochimie',
                'hormonologie'  => 'Hormonologie',
                'marqueurs'     => 'Marqueurs tumoraux',
                'bacteriologie' => 'Bactériologie',
                'spermiologie'  => 'Spermiologie',
                'urines'        => 'Urines / ECBU',
                'serologie'     => 'Sérologie',
                'parasitologie' => 'Parasitologie',
            ];
        }
    }
}