<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamenLaboratoire extends Model
{
    protected $table = 'examens_laboratoire';

    protected $fillable = [
        // ── Identité ──────────────────────────────────────────
        'patient_id',
        'user_id',
        'prescripteur_id',
        'numero_bon',

        // ── Phase 1 : Pré-analytique ──────────────────────────
        'prescription_source',
        'date_prescription',
        'preparation_requise',
        'date_prelevement',
        'heure_prelevement',
        'technicien_prelevement',
        'tube_type',
        'site_prelevement',
        'statut_specimen',
        'motif_rejet',

        // ── Phase 2 : Analytique ─────────────────────────────
        'hematologie', 'hematologie_resultats',
        'hemostase',   'hemostase_resultats',
        'biochimie',   'biochimie_resultats',
        'hormonologie','hormonologie_resultats',
        'marqueurs',   'marqueurs_resultats',
        'bacteriologie','bacteriologie_resultats',
        'antibiogramme',
        'spermiologie','spermiologie_resultats',
        'urines',      'urines_resultats',
        'serologie',   'serologie_resultats',
        'parasitologie','parasitologie_resultats',
        'instrument_utilise',
        'lot_reactif',
        'cqi_status',
        'cqi_note',

        // ── Phase 3 : Post-analytique ────────────────────────
        'observations',
        'valeurs_critiques',
        'clinicien_notifie',
        'date_notification',
        'valide_par',
        'date_validation',
        'date_remise_resultat',

        // ── Statut principal (display) + flags granulaires ────
        'statut',        // en_attente | en_cours | valide | remis | archive  (display/legacy)
        'is_en_cours',   // boolean flag
        'is_valide',     // boolean flag — can be true at the same time as is_remis
        'is_remis',      // boolean flag — can be true at the same time as is_valide
        'is_archive',    // boolean flag

        // ── Paiement ─────────────────────────────────────────
        'montant_paye',
        'mode_paiement',
        'reference_paiement',
        'paiement_confirme',
    ];

    protected $casts = [
        'date_prescription'       => 'date',
        'date_prelevement'        => 'datetime',
        'date_validation'         => 'datetime',
        'date_notification'       => 'datetime',
        'date_remise_resultat'    => 'datetime',
        'montant_paye'            => 'decimal:0',
        'paiement_confirme'       => 'boolean',

        // Workflow flags
        'is_en_cours'             => 'boolean',
        'is_valide'               => 'boolean',
        'is_remis'                => 'boolean',
        'is_archive'              => 'boolean',

        // JSON result columns
        'hematologie_resultats'   => 'array',
        'hemostase_resultats'     => 'array',
        'biochimie_resultats'     => 'array',
        'hormonologie_resultats'  => 'array',
        'marqueurs_resultats'     => 'array',
        'bacteriologie_resultats' => 'array',
        'spermiologie_resultats'  => 'array',
        'urines_resultats'        => 'array',
        'serologie_resultats'     => 'array',
        'parasitologie_resultats' => 'array',
        'valeurs_critiques'       => 'array',

        'patient_id'              => 'integer',
        'user_id'                 => 'integer',
        'prescripteur_id'         => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class, 'patient_id');
    }

    public function laborantin()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function prescripteur()
    {
        return $this->belongsTo(\App\Models\User::class, 'prescripteur_id');
    }

    // ─── Workflow helpers ─────────────────────────────────────

    /**
     * Mark results as validated.
     * Does NOT clear is_remis — both can be true simultaneously.
     */
    public function marquerValide(string $validePar): void
    {
        $this->update([
            'is_valide'       => true,
            'is_en_cours'     => false,
            'valide_par'      => $validePar,
            'date_validation' => now(),
            // Keep legacy statut in sync (use highest-priority state)
            'statut'          => $this->is_remis ? 'remis' : 'valide',
        ]);
    }

    /**
     * Mark results as remis (handed to patient / transmitted).
     * Does NOT clear is_valide — both can be true simultaneously.
     */
    public function marquerRemis(): void
    {
        $this->update([
            'is_remis'              => true,
            'date_remise_resultat'  => now(),
            'statut'                => 'remis',   // highest-priority display state
        ]);
    }

    /**
     * Archive the record (sets both is_valide + is_remis to true as a
     * precondition — you cannot archive without validation + remise).
     */
    public function marquerArchive(): void
    {
        $this->update([
            'is_archive' => true,
            'is_valide'  => true,
            'is_remis'   => true,
            'statut'     => 'archive',
        ]);
    }

    /**
     * Convenience: is the record both validated AND remis?
     */
    public function isValideEtRemis(): bool
    {
        return $this->is_valide && $this->is_remis;
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /** Records that have been validated (regardless of remise state). */
    public function scopeValide($query)
    {
        return $query->where('is_valide', true);
    }

    /** Records that have been remis (regardless of validation state). */
    public function scopeRemis($query)
    {
        return $query->where('is_remis', true);
    }

    /** Records that are BOTH validated AND remis. */
    public function scopeValideEtRemis($query)
    {
        return $query->where('is_valide', true)->where('is_remis', true);
    }

    /** Records validated but NOT yet remis — the most useful dashboard filter. */
    public function scopeValideNonRemis($query)
    {
        return $query->where('is_valide', true)->where('is_remis', false);
    }

    public function scopeByLaborantin($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePending($query)
    {
        return $query->where('is_valide', false)->where('is_archive', false);
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getSectionsActives(): array
    {
        $sections = [
            'hematologie', 'hemostase', 'biochimie', 'hormonologie',
            'marqueurs', 'bacteriologie', 'spermiologie', 'urines',
            'serologie', 'parasitologie',
        ];

        return array_filter($sections, function ($section) {
            return !empty($this->getAttribute("{$section}_resultats"));
        });
    }

    public function hasCriticalValues(): bool
    {
        return !empty($this->valeurs_critiques);
    }

    /**
     * Human-readable composite status label in French.
     * Reflects that valide + remis can both be true.
     */
    public function getStatutLabelAttribute(): string
    {
        if ($this->is_archive)                        return 'Archivé';
        if ($this->is_valide && $this->is_remis)      return 'Validé & Remis';
        if ($this->is_valide)                         return 'Validé';
        if ($this->is_remis)                          return 'Remis';
        if ($this->is_en_cours)                       return "En cours d'analyse";

        return 'En attente';
    }

    /**
     * Tailwind badge colour — composite states get their own colour.
     */
    public function getStatutColorAttribute(): string
    {
        if ($this->is_archive)                   return 'tw-bg-slate-100 tw-text-slate-600';
        if ($this->is_valide && $this->is_remis) return 'tw-bg-purple-100 tw-text-purple-700';
        if ($this->is_valide)                    return 'tw-bg-green-100 tw-text-green-700';
        if ($this->is_remis)                     return 'tw-bg-teal-100 tw-text-teal-700';
        if ($this->is_en_cours)                  return 'tw-bg-blue-100 tw-text-blue-700';

        return 'tw-bg-yellow-100 tw-text-yellow-700';
    }

    public function getTatMinutesAttribute(): ?int
    {
        if (!$this->date_prelevement || !$this->date_validation) {
            return null;
        }
        return (int) $this->date_prelevement->diffInMinutes($this->date_validation);
    }
}