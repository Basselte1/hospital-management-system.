<?php

namespace App\Models;

use App\Models\Concerns\HasFactureMontants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FactureChambre
 *
 * Facture de séjour hospitalier.
 * Regroupe : hébergement + soins infirmiers + traitements administrés.
 */
class FactureChambre extends Model
{
    use SoftDeletes, HasFactureMontants;

    protected $table = 'facture_chambres';

    protected $fillable = [
        'patient_id',
        'chambre_id',
        'user_id',

        // Numéro alphanumérique (format CHB-2026-000001)
        // Changé de INTEGER (2019) à VARCHAR(30) via migration 2026_04_24_...
        'numero',

        'patient_name',
        'patient_numero_dossier',

        // Montants détaillés
        'montant_hebergement',
        'montant_soins',
        'montant_traitements',
        'montant_total',

        // Montants financiers (manquaient dans la version précédente)
        'avance',
        'assurancec',
        'assurec',
        'reste',

        // Statut & assurance
        'statut',
        'assurance',
        'numero_assurance',
        'prise_en_charge',
        'mode_paiement',
        'mode_paiement_info_sup',

        // Dates séjour — 'date_entre' est le nom BDD historique (migration 2019)
        'date_entre',
        'date_sortie',

        // Impression
        'is_printed',
        'printed_at',
        'printed_by',
        'notes',
    ];

    protected $casts = [
        'montant_hebergement'  => 'decimal:0',
        'montant_soins'        => 'decimal:0',
        'montant_traitements'  => 'decimal:0',
        'montant_total'        => 'decimal:0',
        'avance'               => 'decimal:0',
        'assurancec'           => 'decimal:0',
        'assurec'              => 'decimal:0',
        'reste'                => 'decimal:0',
        'prise_en_charge'      => 'decimal:2',
        'assurance'            => 'boolean',
        'is_printed'           => 'boolean',
        'printed_at'           => 'datetime',
      
        'date_entre'           => 'date',
        'date_sortie'          => 'date',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class);
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
     * Toutes les lignes de cette facture.
     *
     * CORRIGÉ : pointe vers FactureLigne (table unifiée facture_lignes)
     * et non plus vers FactureChambreLigne (table séparée qui n'existe plus
     * dans l'architecture unifiée).
     */
    public function lignes()
    {
        return $this->hasMany(FactureLigne::class, 'facture_chambre_id')
                    ->where('facture_type', 'chambre')
                    ->orderBy('ordre');
    }

    // Sous-ensembles de lignes par catégorie
    public function lignesHebergement()
    {
        return $this->lignes()->where('type_sous', 'hebergement');
    }

    public function lignesSoins()
    {
        return $this->lignes()->where('type_sous', 'soin_infirmier');
    }

    public function lignesTraitements()
    {
        return $this->lignes()->where('type_sous', 'traitement');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Recalcule les sous-totaux par catégorie et le montant global depuis les lignes.
     * À appeler après chaque ajout, modification ou suppression d'une ligne.
     */
    public function recalculerMontants(): void
    {
        $this->update([
            'montant_hebergement' => $this->lignesHebergement()
                                         ->selectRaw('COALESCE(SUM(montant * quantite), 0) as t')
                                         ->value('t'),
            'montant_soins'       => $this->lignesSoins()
                                         ->selectRaw('COALESCE(SUM(montant * quantite), 0) as t')
                                         ->value('t'),
            'montant_traitements' => $this->lignesTraitements()
                                         ->selectRaw('COALESCE(SUM(montant * quantite), 0) as t')
                                         ->value('t'),
            'montant_total'       => $this->lignes()
                                         ->selectRaw('COALESCE(SUM(montant * quantite), 0) as t')
                                         ->value('t'),
        ]);
    }

    /**
     * Nombre de jours de séjour.
     * Compte les deux bornes inclusivement (entrée ET sortie comptent).
     *
     * Exemples :
     *   01/01 → 01/01 = 1 jour (day-use)
     *   01/01 → 02/01 = 2 jours
     *   01/01 → 05/01 = 5 jours
     */
    public function getNbJoursAttribute(): ?int
    {
        if ($this->date_entre && $this->date_sortie) {
            // +1 pour compter inclusivement (le jour d'entrée compte)
            return max(1, $this->date_entre->diffInDays($this->date_sortie) + 1);
        }

        return null;
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeForConsultation($query, int $consultationId)
    {
        // FactureChambre n'est pas liée à une consultation.
        // Scope vide pour compatibilité avec le code générique du module.
        return $query;
    }
}