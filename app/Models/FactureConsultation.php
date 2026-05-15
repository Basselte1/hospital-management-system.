<?php

namespace App\Models;

use App\Models\Concerns\HasFactureMontants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\FactureConsultation — VERSION REFACTORISÉE
 *

 *
 * NOTE PRODUCTION : aucune modification de schéma requise. Ce fichier remplace
 * uniquement la logique PHP — la base de données n'est pas touchée.
 */
class FactureConsultation extends Model
{
    use SoftDeletes, HasFactureMontants;

    // ── Champs temporels ──────────────────────────────────────────────────────

    protected $dates = ['deleted_at', 'printed_at'];

    // ── Accès ouvert (legacy) ─────────────────────────────────────────────────

    protected $guarded = [];

    // ── Casts ─────────────────────────────────────────────────────────────────

    protected $casts = [
        'is_printed' => 'boolean',
        'printed_at' => 'datetime',
        // AJOUT : cast explicite pour éviter null == 0 sur les champs monétaires.
        // Le trait computeStatut() utilise ($this->reste ?? 0) == 0 — les casts
        // n'y changent rien, mais ils sécurisent les calculs dans les controllers.
        'montant'    => 'integer',
        'avance'     => 'integer',
        'assurancec' => 'integer',
        'assurec'    => 'integer',
        'reste'      => 'integer',
    ];

    // ── Boot spécifique ───────────────────────────────────────────────────────

    /**
     * Le trait bootHasFactureMontants() gère déjà :
     *   - creating : snapshotPatientName() + computeStatut()
     *   - updating : computeStatut()
     *
     * On surcharge ici uniquement pour ajouter syncPatientData(),
     * qui est spécifique à FactureConsultation.
     *
     * IMPORTANT : on n'appelle PAS parent::boot() ou Model::boot()
     * manuellement — Laravel le fait lui-même via bootTraits().
     */
    protected static function booted(): void
    {
        static::created(function (self $facture) {
            // snapshotPatientName() + computeStatut() sont déjà appelés
            // par bootHasFactureMontants() sur l'event 'creating'.
            // On ajoute seulement syncPatientData(), spécifique ici.
            $facture->syncPatientData();
        });

        static::updated(function (self $facture) {
            // computeStatut() est déjà appelé par bootHasFactureMontants()
            // sur l'event 'updating'. On ajoute seulement syncPatientData().
            $facture->syncPatientData();
        });
    }

    // ── Méthodes spécifiques à FactureConsultation ────────────────────────────

    /**
     * Synchronise les totaux agrégés de toutes les factures de consultation
     * du patient vers la table patients.
     *
     * POURQUOI ICI et pas dans le trait ?
     *   La logique d'agrégation vers patients est propre à FactureConsultation.
     *   Les autres types de factures (examen, acte) ne touchent pas la table patients.
     */
    public function syncPatientData(): void
    {
        $patient = $this->patient;

        if (! $patient) {
            return;
        }

        // Calcule les totaux sur TOUTES les factures de consultation du patient.
        // On utilise withoutTrashed() implicitement (soft-delete actif sur ce modèle).
        $totals = static::where('patient_id', $patient->id)->selectRaw(
            'SUM(montant)    as total_montant,
             SUM(avance)     as total_avance,
             SUM(assurancec) as total_assurancec,
             SUM(assurec)    as total_assurec,
             SUM(reste)      as total_reste'
        )->first();

        $patient->update([
            'montant'    => (int) ($totals->total_montant    ?? 0),
            'avance'     => (int) ($totals->total_avance     ?? 0),
            'assurancec' => (int) ($totals->total_assurancec ?? 0),
            'assurec'    => (int) ($totals->total_assurec    ?? 0),
            'reste'      => (int) ($totals->total_reste      ?? 0),
            // On ne surcharge assurance/demarcheur que si la facture les fournit.
            'assurance'  => $this->assurance  ?? $patient->assurance,
            'demarcheur' => $this->demarcheur ?? $patient->demarcheur,
        ]);
    }

    // ── Calculs statiques (helpers controllers — à déplacer dans FactureService) ──

    /**
     * Calcule le reste à payer.
     *
     * @param  float  $assurec  Part patient (après déduction assurance)
     * @param  float  $avance   Montant déjà encaissé
     */
    public static function calculReste(float $assurec, float $avance): float
    {
        return max(0, $assurec - $avance);
    }

    /**
     * Calcule la part patient (assurec).
     *
     * @param  float  $montant          Montant total de la facture
     * @param  float  $prise_en_charge  Taux de couverture assurance (0-100)
     */
    public static function calculAssurec(float $montant, float $prise_en_charge): float
    {
        return $montant * ((100 - $prise_en_charge) / 100);
    }

    /**
     * Calcule la part prise en charge par l'assurance (assurancec).
     *
     * @param  float  $montant          Montant total de la facture
     * @param  float  $prise_en_charge  Taux de couverture assurance (0-100)
     */
    public static function calculAssurancec(float $montant, float $prise_en_charge): float
    {
        return $montant * ($prise_en_charge / 100);
    }

    // ── Relations ─────────────────────────────────────────────────────────────

    /**
     * Relation patient.
     * withTrashed() garantit la résolution même après soft-delete du patient.
     * Pour les patients hard-supprimés (données legacy), patient_name sert de fallback.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function printer()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }

    public function historiques()
    {
        return $this->hasMany(HistoriqueFacture::class);
    }

    public function lignes()
    {
        return $this->hasMany(FactureLigne::class, 'facture_consultation_id')->orderBy('ordre');
    }

    // ── Scopes spécifiques ────────────────────────────────────────────────────
    // Les scopes scopeSoldees, scopeNonSoldees et scopeForPatient viennent du trait.
    // On ajoute ici uniquement les scopes propres à FactureConsultation.

    public function scopeImprimees($query)
    {
        return $query->where('is_printed', true);
    }

    public function scopeNonImprimees($query)
    {
        return $query->where('is_printed', false);
    }
}