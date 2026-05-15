<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Paiement
 *
 * Table centrale de paiement.
 * Un paiement est toujours lié à UNE facture (peu importe son type)
 * via le couple (facture_type, facture_id).
 *
 * POURQUOI POLYMORPHISME MANUEL plutôt que morphTo() ?
 *   morphTo() de Laravel utilise le nom de classe complet comme clé
 *   (ex: "App\Models\FactureExamen"), ce qui rend les requêtes SQL
 *   moins lisibles et peut poser problème si vous renommez le namespace.
 *   Ici on utilise un nom court explicite ("facture_examen", "facture_acte"…)
 *   défini dans FACTURE_TYPES — plus clair, plus maintenable.
 *
 * MODES DE PAIEMENT :
 *   Les modes sont chargés dynamiquement depuis la table `modes_paiement`
 *   (ou depuis une config). Plus de constante ici.
 */
class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'patient_id',
        'user_id',
        'facture_type',
        'facture_id',
        'montant',
        'mode_paiement',
        'mode_paiement_info_sup',
        'reference',
        'notes',
        'paye_le',
    ];

    protected $casts = [
        'montant'  => 'decimal:0',
        'paye_le'  => 'datetime',
    ];

    /**
     * Map des types de factures reconnus par ce système.
     * Clé = valeur stockée en base | Valeur = classe du modèle.
     *
     * Pourquoi ici et pas en constante ?
     *   Une constante de classe ne peut pas contenir d'expressions.
     *   On utilise une méthode statique pour que la liste soit calculable
     *   et potentiellement enrichie sans modifier ce modèle.
     */
    public static function factureTypes(): array
    {
        return [
            'facture_consultation' => \App\Models\FactureConsultation::class,
            'facture_examen'       => \App\Models\FactureExamen::class,
            'facture_acte'         => \App\Models\FactureActe::class,
            'facture_chambre'      => \App\Models\FactureChambre::class,
            'facture_devis'        => \App\Models\FactureDevi::class,
        ];
    }

    // ── Relations ─────────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function caissier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Retourne la facture source quel que soit son type.
     * Utilisation : $paiement->facture()
     */
    public function facture(): ?Model
    {
        $types = self::factureTypes();

        if (! isset($types[$this->facture_type])) {
            return null;
        }

        $class = $types[$this->facture_type];

        return $class::find($this->facture_id);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeForFacture($query, string $type, int $id)
    {
        return $query->where('facture_type', $type)->where('facture_id', $id);
    }

    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeOnPeriode($query, string $debut, string $fin)
    {
        return $query->whereBetween('paye_le', [$debut, $fin]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getMontantFormatteAttribute(): string
    {
        return number_format((float) $this->montant, 0, ',', ' ') . ' FCFA';
    }
}