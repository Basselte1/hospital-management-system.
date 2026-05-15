<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Paiement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Services\FactureService
 *
 * Service unique qui centralise toute la logique métier commune
 * à tous les types de factures.
 *
 * POURQUOI UN SERVICE ?
 *   Les calculs (reste, assurec, assurancec) étaient dupliqués dans
 *   FactureExamen et FactureActe. Si la formule change (ex: TVA, arrondi),
 *   on modifie ici UNE SEULE FOIS. Les controllers restent légers.
 *
 * UTILISATION dans un controller :
 *   $service = app(FactureService::class);  // injection
 *   $service->enregistrerPaiement($facture, $montant, 'especes');
 */
class FactureService
{
    // ── Calculs financiers ────────────────────────────────────────────────────

    /**
     * Part restant à payer par l'assuré.
     *
     * @param  float  $assurec   Montant à la charge de l'assuré
     * @param  float  $avance    Montant déjà versé
     */
    public function calculReste(float $assurec, float $avance): float
    {
        return max(0, $assurec - $avance);
    }

    /**
     * Part à la charge de l'assuré après déduction de l'assurance.
     *
     * @param  float  $montant          Montant total de la facture
     * @param  float  $prise_en_charge  Pourcentage pris en charge par l'assurance (0–100)
     */
    public function calculAssurec(float $montant, float $prise_en_charge): float
    {
        return $montant * ((100 - $prise_en_charge) / 100);
    }

    /**
     * Part prise en charge par l'assurance.
     */
    public function calculAssurancec(float $montant, float $prise_en_charge): float
    {
        return $montant * ($prise_en_charge / 100);
    }

    /**
     * Calcule tous les montants dérivés d'un coup et les retourne
     * sous forme de tableau prêt à être passé à update() ou fill().
     *
     * Exemple :
     *   $montants = $service->preparerMontants(50000, 30, 20000);
     *   $facture->update($montants);
     *
     * @param  float  $montantTotal
     * @param  float  $priseEnCharge   % assurance (0 si pas d'assurance)
     * @param  float  $avance          Acompte versé
     */
    public function preparerMontants(float $montantTotal, float $priseEnCharge, float $avance): array
    {
        $assurancec = $this->calculAssurancec($montantTotal, $priseEnCharge);
        $assurec    = $this->calculAssurec($montantTotal, $priseEnCharge);
        $reste      = $this->calculReste($assurec, $avance);

        return [
            'montant_total' => $montantTotal,
            'prise_en_charge' => $priseEnCharge,
            'assurancec'    => $assurancec,
            'assurec'       => $assurec,
            'avance'        => $avance,
            'reste'         => $reste,
        ];
    }

    // ── Paiement ──────────────────────────────────────────────────────────────

    /**
     * Enregistre un paiement sur une facture et met à jour son reste/statut.
     *
     * POURQUOI une transaction DB ?
     *   Si la mise à jour de la facture échoue après la création du paiement,
     *   on se retrouverait avec un paiement sans facture mise à jour.
     *   La transaction garantit que les deux opérations réussissent ou échouent ensemble.
     *
     * @param  Model   $facture       Instance de n'importe quel modèle facture
     * @param  string  $factureType   Clé du type (ex: 'facture_examen') — voir Paiement::factureTypes()
     * @param  float   $montant       Montant versé
     * @param  string  $modePaiement  ex: especes, mobile_money, virement
     * @param  array   $extra         Champs supplémentaires optionnels (reference, notes…)
     */
    public function enregistrerPaiement(
        Model $facture,
        string $factureType,
        float $montant,
        string $modePaiement,
        array $extra = []
    ): Paiement {
        return DB::transaction(function () use ($facture, $factureType, $montant, $modePaiement, $extra) {

            $paiement = Paiement::create(array_merge([
                'patient_id'    => $facture->patient_id,
                'user_id'       => auth()->id(),
                'facture_type'  => $factureType,
                'facture_id'    => $facture->id,
                'montant'       => $montant,
                'mode_paiement' => $modePaiement,
                'paye_le'       => now(),
            ], $extra));

            // Mise à jour de l'avance et recalcul du reste sur la facture
            $nouvelleAvance = (float) $facture->avance + $montant;

            // Si pas d'assurance, assurec = montant_total entier
            $assurec = (float) ($facture->assurec ?? $facture->montant_total);

            $nouveauReste   = $this->calculReste((float) $facture->assurec, $nouvelleAvance);

            $facture->update([
                'avance' => $nouvelleAvance,
                'reste'  => $nouveauReste,
                // statut recalculé automatiquement via le trait HasFactureMontants::computeStatut()
            ]);

            return $paiement;
        });
    }

    // ── Numérotation ─────────────────────────────────────────────────────────

    /**
     * Génère un numéro unique pour une facture.
     *
     * Format : PREFIXE-ANNEE-NUMERO_SEQUENTIEL
     * Exemple : EXA-2025-000042
     *
     * POURQUOI ici et pas dans le modèle ?
     *   Un modèle ne doit pas faire de logique séquentielle complexe.
     *   Le service est l'endroit naturel pour les règles métier.
     *
     * @param  string  $prefix  ex: 'EXA' pour examens, 'ACT' pour actes
     * @param  string  $table   Table de la facture pour calculer le max
     */
    public function genererNumero(string $prefix, string $table): string
    {
        $annee = now()->year;

        //SQLite compatible : on récupère tous les numéros et on extrait en PHP
        // SUBSTRING_INDEX est MySQL uniquement — SQLite ne le supporte pas.
        $numeros = DB::table($table)
            ->whereYear('created_at', $annee)
            ->whereNotNull('numero')
            ->pluck('numero');

        $max = $numeros->map(function ($numero) {
            // Extraire la dernière partie après le dernier tiret : "EXA-2025-000042" → "42"
            $parts = explode('-', $numero);
            return (int) end($parts);
        })->max() ?? 0;

        $sequence = str_pad($max + 1, 6, '0', STR_PAD_LEFT);

        return strtoupper($prefix) . '-' . $annee . '-' . $sequence;
    }


     /**
     * Redirige vers la bonne liste de factures selon le motif du patient.
     * si motif = examen => facture_examen.blade.php 
     * si motif = consultation => facture_consultation.blade.php
     * si motif = acte => facture_acte.blade.php
     * Route : GET patient/{id}/generer-facture
     */

 public function dispatchFacture(Patient $patient): array
    {
        // On normalise pour éviter les bugs liés aux espaces ou à la casse
        $motif = trim($patient->motif ?? '');
 
        return match (true) {
 
            // ── Actes & soins infirmiers ──────────────────────────────────────
            in_array($motif, ['Acte', 'Autre acte'], true) => [
                'route'  => 'facturation.actes.index',
                'params' => ['patient_id' => $patient->id],
            ],
 
            // ── Examens biologiques / radiologiques ───────────────────────────
            in_array($motif, ['Examen', 'Autre examen'], true) => [
                'route'  => 'facturation.examens.index',
                'params' => ['patient_id' => $patient->id],
            ],
 
            // ── Consultation + tout motif non reconnu (ancien comportement) ───
            // Les anciennes données restent dans FactureConsultation.
            default => [
                'route'  => 'factures.consultation',
                'params' => [
                    'patient_id'   => $patient->id,
                    'patient_name' => trim($patient->name . ' ' . $patient->prenom),
                ],
            ],
        };
    }
 
}