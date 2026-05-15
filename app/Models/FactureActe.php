<?php

namespace App\Models;

use App\Models\Concerns\HasFactureMontants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FactureActe — VERSION CORRIGÉE
 */
class FactureActe extends Model
{
    use SoftDeletes, HasFactureMontants;

    protected $table = 'factures_acte';

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
        'montant_total'   => 'decimal:0',
        'avance'          => 'decimal:0',
        'assurancec'      => 'decimal:0',
        'assurec'         => 'decimal:0',
        'reste'           => 'decimal:0',
        'prise_en_charge' => 'decimal:2',
        'assurance'       => 'boolean',
        'is_printed'      => 'boolean',
        'printed_at'      => 'datetime',
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

    /**
     * CORRIGÉ : une seule définition, vers FactureActeLigne uniquement.
     * L'ancienne version avait par erreur une seconde méthode lignes()
     * pointant vers FactureExamenLigne — PHP utilisait silencieusement
     * la dernière déclarée.
     */
    public function lignes()
    {
        return $this->hasMany(FactureLigne::class, 'facture_acte_id')->orderBy('ordre');
    }

    // ── Helpers spécifiques ───────────────────────────────────────────────────

    /**
     * Recalcule montant_total depuis les lignes (montant × quantite) et enregistre.
     * Spécifique à FactureActe — les lignes ont une quantité.
     */
    public function recalculerMontant(): void
    {
        $total = $this->lignes()
                      ->selectRaw('SUM(montant * quantite) as total')
                      ->value('total') ?? 0;

        $this->update(['montant_total' => $total]);
    }

    public function scopeForConsultation($query, int $consultationId)
    {
        return $query->where('consultation_id', $consultationId);
    }
}