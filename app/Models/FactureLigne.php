<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\FactureLigne — VERSION CORRIGÉE
 *
 * NOTE PRODUCTION : aucun changement de schéma. Ce fichier met à jour
 * uniquement la couche PHP (fillable + nouvelle méthode de relation).
 */
class FactureLigne extends Model
{
protected $fillable = [
        // ── Clés étrangères (une seule active à la fois selon facture_type) ──
        'facture_consultation_id',
        'facture_examen_id', 
        'facture_acte_id',
        'facture_chambre_id',

        // ── Discriminant : quel type de facture possède cette ligne ───────────
        // Valeurs : consultation | examen | acte | chambre | devis
        'facture_type',

        // ── Champs communs ────────────────────────────────────────────────────
        'type_acte',     // legacy (consultation uniquement)
        'type_sous',     // sous-type dynamique (ex: laboratoire, imagerie, dialyse…)
        'libelle',
        'montant',
        'quantite',      // défaut 1 — utilisé par actes et chambre
        'ordre',

        // ── Champs examen ─────────────────────────────────────────────────────
        'technicien',
        'examen_laboratoire_id',
        'imagerie_id',

        // ── Champs acte médical ───────────────────────────────────────────────
        'medecin',
        'infirmiere',
        'date_acte',

        // ── Lien source optionnel (acte_type / acte_id — legacy) ─────────────
        'acte_type',
        'acte_id',

        // ── Champs chambre ────────────────────────────────────────────────────
        'reference_id',
        'reference_type',
    ];

protected $casts = [
    'facture_consultation_id' => 'integer',
    'facture_examen_id'       => 'integer',
    'facture_acte_id'         => 'integer',
    'facture_chambre_id'      => 'integer',
    'montant'                 => 'integer',
    'quantite'                => 'integer',
    'ordre'                   => 'integer',
    'date_acte'               => 'date',
];

    // ──────────────────────────────────────────────────────────────────────────
    // Labels de types d'actes
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Retourne le libellé lisible d'un type d'acte.
     * Utilisé dans les vues et les bilans.
     */
    public static function labelsTypes(): array
    {
        return [
            'consultation'        => 'Consultation médicale',
            'consultation_suivi'  => 'Consultation de suivi',
            'examen_labo'         => 'Examen de laboratoire',
            'examen_radio'        => 'Imagerie / Radiologie',
            'soin_infirmier'      => 'Soin infirmier',
            'chambre'             => 'Hébergement / Chambre',
            'pharmacie'           => 'Médicaments',
            'autre'               => 'Autre acte',
        ];
    }

    /**
     * Retourne le libellé lisible de ce type d'acte.
     */
    public function getLabelTypeAttribute(): string
    {
        return self::labelsTypes()[$this->type_acte] ?? $this->type_acte;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * La facture de consultation à laquelle appartient cette ligne.
     *
     * MÉTHODE PRINCIPALE — conservée pour rétrocompatibilité.
     */
    public function facture(): BelongsTo
    {
        return $this->belongsTo(FactureConsultation::class, 'facture_consultation_id');
    }

    /**
     * Alias explicite de facture() — utilisé par FactureLigneObserver.
     *
     * CORRIGÉ : FactureLigneObserver appelait $ligne->factureConsultation()
     * qui n'existait pas, causant une BadMethodCallException.
     * Cet alias résout le problème sans modifier l'Observer.
     */
    public function factureConsultation(): BelongsTo
    {
        return $this->facture();
    }

    /**
     * Relation vers FactureExamen si la ligne appartient à une facture d'examen.
     */
    public function factureExamen(): BelongsTo
    {
        return $this->belongsTo(FactureExamen::class, 'facture_examen_id');
    }

    /**
     * Relation vers FactureActe si la ligne appartient à une facture d'acte.
     */
    public function factureActe(): BelongsTo
    {
        return $this->belongsTo(FactureActe::class, 'facture_acte_id');
    }

    /**
     * Relation vers ExamenLaboratoire si la ligne concerne un examen de labo.
     */
    public function examenLaboratoire(): BelongsTo
    {
        return $this->belongsTo(ExamenLaboratoire::class, 'examen_laboratoire_id');
    }

    /**
     * Relation vers Imagerie si la ligne concerne un examen d'imagerie.
     */
    public function imagerie(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Examen::class, 'imagerie_id');
    }

    /**
     * Retourne l'acte source (Examen, Imagerie, SoinsInfirmier…) si le lien existe.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function acteSource(): ?Model
    {
        if (! $this->acte_type || ! $this->acte_id) {
            return null;
        }

        $class = 'App\\Models\\' . $this->acte_type;

        if (! class_exists($class)) {
            return null;
        }

        return $class::find($this->acte_id);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Montant unitaire × quantité = sous-total de la ligne.
     */
    public function getSousTotalAttribute(): int
    {
        return $this->montant * ($this->quantite ?? 1);
    }

    /**
     * Formate le montant en FCFA pour l'affichage.
     */
    public function getMontantFormatteAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }
}