<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Prescription
 *
 * Stores a doctor's lab-test prescription for a patient.
 * Each discipline column holds a comma-separated list of test names
 * (matching exactly the `nom_test` values in `tarifs_laboratoire`).
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $patient_id
 * @property string|null $hematologie
 * @property string|null $hemostase
 * @property string|null $biochimie
 * @property string|null $hormonologie
 * @property string|null $marqueurs
 * @property string|null $bacteriologie
 * @property string|null $spermiologie
 * @property string|null $urines
 * @property string|null $serologie
 * @property string|null $parasitologie
 * @property string|null $examen        (legacy free-text / imaging notes)
 */
class Prescription extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'hematologie',
        'hemostase',
        'biochimie',
        'hormonologie',
        'marqueurs',
        'bacteriologie',
        'spermiologie',
        'urines',
        'serologie',
        'parasitologie',   // ← added to match ExamenLaboratoire sections
        'examen',          // legacy / imaging free-text
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Return the prescribed tests for one discipline as an array.
     * Handles empty / null fields gracefully.
     *
     * Usage:  $prescription->testsForSection('hematologie')
     *         // => ['NFS', 'Hémoglobine (Hb)']
     */
    public function testsForSection(string $section): array
    {
        $raw = $this->getAttribute($section) ?? '';
        if ($raw === '') {
            return [];
        }
        return array_filter(array_map('trim', explode(',', $raw)));
    }

    /**
     * Return ALL prescribed tests, keyed by section slug.
     * Sections with no tests are omitted.
     *
     * Usage:  $prescription->allTestsBySection()
     *         // => ['hematologie' => ['NFS', 'Hémoglobine (Hb)'], ...]
     */
    public function allTestsBySection(): array
    {
        $disciplines = [
            'hematologie', 'hemostase', 'biochimie', 'hormonologie',
            'marqueurs', 'bacteriologie', 'spermiologie', 'urines',
            'serologie', 'parasitologie',
        ];

        $result = [];
        foreach ($disciplines as $section) {
            $tests = $this->testsForSection($section);
            if (!empty($tests)) {
                $result[$section] = $tests;
            }
        }
        return $result;
    }
}