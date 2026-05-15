<?php

namespace App\Models;

use App\Models\Concerns\HasFactureMontants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FactureExamen — VERSION FINALE
 *
 * CHANGEMENTS par rapport à la version initiale :
 *   - use HasFactureMontants : supprime la duplication de updateStatut,
 *     isSoldee, isImprimable, marquerCommeImprimee, snapshotPatientName,
 *     getPatientDisplayNameAttribute, getStatutBadgeAttribute, scopes communs.
 *   - Les calculs statiques (calculReste, calculAssurec, calculAssurancec)
 *     sont supprimés ici → déplacés dans FactureService (un seul endroit).
 *   - Le boot() est supprimé → géré par bootHasFactureMontants() dans le trait.
 *   - recalculerMontant() est conservé car spécifique à ce type de facture.
 */
class FactureExamen extends Model
{
    use SoftDeletes, HasFactureMontants;

    protected $table = 'factures_examen';

    protected $fillable = [
        'patient_id',
        'consultation_id',
        'user_id',
        'numero',
        'patient_name',
        'patient_numero_dossier',

        'montant_total',
        'avance',
        'assurancec',
        'assurec',
        'reste',

        'statut',
        'assurance',
        'numero_assurance',
        'prise_en_charge',
        'mode_paiement',
        'mode_paiement_info_sup',

        'is_printed',
        'printed_at',
        'printed_by',
        'notes',
    ];

    protected $casts = [
        'montant_total'    => 'decimal:0',
        'avance'           => 'decimal:0',
        'assurancec'       => 'decimal:0',
        'assurec'          => 'decimal:0',
        'reste'            => 'decimal:0',
        'prise_en_charge'  => 'decimal:2',
        'assurance'        => 'boolean',
        'is_printed'       => 'boolean',
        'printed_at'       => 'datetime',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function printer()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }

    public function lignes()
    {
        return $this->hasMany(FactureLigne::class, 'facture_examen_id')->orderBy('ordre');
    }

    public function historiques()
    {
        return $this->hasMany(HistoriqueFacture::class);
    }

    /**
     * Retourne la collection des examens de laboratoire facturés dans cette facture.
     * Cas d'usage : le patient a choisi 1 ou plusieurs examens parmi ceux prescrits.
     */
    public function examensLaboratoire()
    {
        return $this->hasManyThrough(
            ExamenLaboratoire::class,
            FactureLigne::class,
            'facture_examen_id',      // clé sur FactureLigne
            'id',                     // clé sur ExamenLaboratoire
            'id',                     // clé locale sur FactureExamen
            'examen_laboratoire_id'   // clé sur FactureLigne
        );
    }

    // ── Helpers spécifiques ───────────────────────────────────────────────────

    /**
     * Recalcule montant_total depuis les lignes et enregistre.
     * Spécifique à FactureExamen (les lignes n'ont pas de quantité ici).
     */
    public function recalculerMontant(): void
    {
        $total = $this->lignes()->sum('montant');
        $this->update(['montant_total' => $total]);
    }

    public function scopeForConsultation($query, int $consultationId)
    {
        return $query->where('consultation_id', $consultationId);
    }
}