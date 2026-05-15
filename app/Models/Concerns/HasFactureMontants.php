<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Auth;

/**
 * Trait HasFactureMontants — VERSION CORRIGÉE
 *
 * Centralise TOUTE la logique partagée entre les modèles facture :
 * FactureExamen, FactureActe, FactureChambre, FactureConsultation, FactureDevi.
 *   CONSERVÉ — Tout le reste (isSoldee, isImprimable, scopes, accessors).
 */
trait HasFactureMontants
{
    // ── Boot du trait ─────────────────────────────────────────────────────────

    public static function bootHasFactureMontants(): void
    {
        // 'creating' : avant INSERT — on peut modifier les attributs directement.
        static::creating(function ($facture) {
            $facture->snapshotPatientName();
            $facture->computeStatut();
        });

        // 'updating' : avant UPDATE — on recalcule le statut.
        static::updating(function ($facture) {
            $facture->computeStatut();
        });
    }

    // ── Statut ────────────────────────────────────────────────────────────────

    /**
     * Calcule et affecte le statut selon le reste dû.
     *
     * CORRIGÉ : on distingue null (non renseigné) de 0 (soldé).
     *   - null  → on ne touche pas le statut (laisse 'Non soldée' par défaut)
     *   - 0     → 'Soldée'
     *   - > 0   → 'Non soldée'
     *
     * Appelé sur creating et updating (via bootHasFactureMontants).
     */
    public function computeStatut(): void
    {
        // Si reste n'est pas encore défini, on ne force pas le statut.
        if (is_null($this->reste)) {
            return;
        }

        $nouveau = (int) $this->reste === 0 ? 'Soldée' : 'Non soldée';

        if ($this->statut !== $nouveau) {
            $this->statut = $nouveau;
        }
    }

    public function isProforma(): bool
    {
        return !$this->isSoldee();
    }

    public function isModifiable(): bool
    {
        return !$this->isSoldee();
    }


    public function isSoldee(): bool
    {
        return $this->statut === 'Soldée' || (! is_null($this->reste) && (int) $this->reste === 0);
    }

    // ── Impression ────────────────────────────────────────────────────────────

    public function isImprimable(): bool
    {
        return $this->isSoldee();
    }

    /**
     * Marque la facture comme imprimée.
     * Ne fait rien si la facture n'est pas soldée.
     */
    public function marquerCommeImprimee(): void
    {
        if ($this->isImprimable()) {
            $this->update([
                'is_printed' => true,
                'printed_at' => now(),
                'printed_by' => Auth::id(),
            ]);
        }
    }

    // ── Snapshot patient ──────────────────────────────────────────────────────

    /**
     * Sauvegarde le nom du patient AU MOMENT DE LA CRÉATION.
     *
     * La relation $this->patient fonctionne ici car Eloquent peut résoudre
     * une BelongsTo depuis la FK patient_id présente dans les attributs en mémoire.
     */
    public function snapshotPatientName(): void
    {
        if (! empty($this->patient_name)) {
            return; // Déjà renseigné manuellement — on ne l'écrase pas.
        }

        // La relation peut être résolue même avant INSERT (FK en mémoire).
        $patient = $this->patient;

        if (! $patient) {
            return;
        }

        // Affectation directe — incluse dans l'INSERT par Eloquent.
        $this->patient_name = trim(
            ($patient->name ?? '') . ' ' . ($patient->prenom ?? '')
        );

        if (isset($patient->numero_dossier)) {
            $this->patient_numero_dossier = (string) $patient->numero_dossier;
        }
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getPatientDisplayNameAttribute(): string
    {
        if ($this->relationLoaded('patient') && $this->patient) {
            return trim(
                ($this->patient->name ?? '') . ' ' . ($this->patient->prenom ?? '')
            );
        }

        return $this->patient_name ?? '[Patient supprimé]';
    }

    public function getStatutBadgeAttribute(): string
    {
        return $this->isSoldee()
            ? '<span class="badge bg-success">Soldée</span>'
            : '<span class="badge bg-warning text-dark">Non soldée</span>';
    }

    // ── Scopes communs ────────────────────────────────────────────────────────

    public function scopeSoldees($query)
    {
        return $query->where('statut', 'Soldée');
    }

    public function scopeNonSoldees($query)
    {
        return $query->where('statut', 'Non soldée');
    }

    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}