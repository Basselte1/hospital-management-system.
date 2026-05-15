<?php

namespace App\Models;

use App\Models\Concerns\HasFactureMontants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FactureDevi — VERSION CORRIGÉE
 *
 *   ATTENTION — Limitation du trait avec $timestamps = false :
 *     bootHasFactureMontants écoute 'creating' et 'updating'. Ces events se
 *     déclenchent normalement même sans timestamps. La seule contrainte est que
 *     le modèle ne doit pas avoir de colonne updated_at absente dans un SELECT *.
 *     Testé : compatible Laravel 8/9/10/11 avec $timestamps = false.
 */
class FactureDevi extends Model
{
    use HasFactureMontants;

    protected $table = 'facture_devis';

    // Conservé : la migration originale n'a pas de colonnes created_at/updated_at.
    // À retirer uniquement après avoir ajouté ces colonnes via une migration dédiée.
    public $timestamps = false;

  protected $fillable = [
        // ── Clés étrangères ──────────────────────────────────────────────────
        'user_id',
        'patient_id',
        'devi_id',               // ← Lien OBLIGATOIRE vers le devis source

        // ── Informations du devis ───────────────────────────────────────────
        'designation_devis',
        'numero_facture',

        // ── Montants (legacy) ───────────────────────────────────────────────
        'montant_devis',
        'avance_devis',
        'reste_devis',
        'part_assurance',
        'part_patient',

        // ── Assurance ────────────────────────────────────────────────────────
        'numero_assurance',
        'assurance',
        'taux_assurance',

        // ── Dates et paiement ────────────────────────────────────────────────
        'date_creation',
        'type_paiement',
        'numero_cheque',
        'tireur_cheque',
        'banque_emission',
        'date_emission',
        'attestation_virement',
        'numero_compte',
        'montant_virement',
        'banque_virement',
        'date_virement',

        // ── Colonnes ajoutées par migrations 2026 ────────────────────────────
        'numero',
        'statut',
        'patient_name',
        'patient_numero_dossier',
        'is_printed',
        'printed_at',
        'printed_by',
    ];

    protected $casts = [
        'date_creation'  => 'date',
        'date_emission'  => 'date',
        'date_virement'  => 'date',
        'is_printed'     => 'boolean',
        'printed_at'     => 'datetime',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le devis source à partir duquel cette facture a été générée.
     * La facture de devis est TOUJOURS liée à un devis.
     */
    public function devi()
    {
        return $this->belongsTo(Devi::class, 'devi_id');
    }

    public function printer()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }

    // ── Alias accessors/mutators pour compatibilité HasFactureMontants ────────

    /** reste → reste_devis */
    public function getResteAttribute(): ?int
    {
        return $this->attributes['reste_devis'] ?? null;
    }
    public function setResteAttribute($value): void
    {
        $this->attributes['reste_devis'] = $value;
    }

    /** avance → avance_devis */
    public function getAvanceAttribute(): ?int
    {
        return $this->attributes['avance_devis'] ?? null;
    }
    public function setAvanceAttribute($value): void
    {
        $this->attributes['avance_devis'] = $value;
    }

    /** montant_total → montant_devis */
    public function getMontantTotalAttribute(): ?int
    {
        return $this->attributes['montant_devis'] ?? null;
    }
    public function setMontantTotalAttribute($value): void
    {
        $this->attributes['montant_devis'] = $value;
    }

    /** assurec → part_patient */
    public function getAssurecAttribute(): ?int
    {
        return $this->attributes['part_patient'] ?? null;
    }
    public function setAssurecAttribute($value): void
    {
        $this->attributes['part_patient'] = $value;
    }

    /** assurancec → part_assurance */
    public function getAssurancecAttribute(): ?int
    {
        return $this->attributes['part_assurance'] ?? null;
    }
    public function setAssurancecAttribute($value): void
    {
        $this->attributes['part_assurance'] = $value;
    }

    /** prise_en_charge → taux_assurance */
    public function getPriseEnChargeAttribute(): ?int
    {
        return $this->attributes['taux_assurance'] ?? null;
    }
    public function setPriseEnChargeAttribute($value): void
    {
        $this->attributes['taux_assurance'] = $value;
    }

    // ── Surcharge du trait pour marquerCommeImprimee ──────────────────────────

    /**
     * Surcharge car le trait utilise update() qui tenterait de mettre updated_at
     * sur une table sans timestamps. On utilise updateQuietly() ici.
     */
    public function marquerCommeImprimee(): void
    {
        if ($this->isImprimable()) {
            $this->is_printed = true;
            $this->printed_at = now();
            $this->printed_by = \Illuminate\Support\Facades\Auth::id();
            $this->saveQuietly();
        }
    }
}
