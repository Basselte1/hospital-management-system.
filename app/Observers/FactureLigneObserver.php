<?php

namespace App\Observers;

use App\Models\FactureLigne;
use App\Models\PatientVisit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * App\Observers\FactureLigneObserver
 *
 * CORRECTIONS PAR RAPPORT À LA VERSION PRÉCÉDENTE :
 *
 *   BUG 1 — $ligne->facture() n'existait pas sur FactureLigne :
 *     L'Observer appelait $ligne->facture()->with(...)->first() mais FactureLigne
 *     n'avait pas de méthode facture(). Cela causait une erreur "Call to undefined method".
 *     → Corrigé : on utilise maintenant $ligne->factureParente() (définie dans FactureLigne)
 *       avec un chargement explicite des relations nécessaires.
 *
 *   BUG 2 — FactureLigne::labelsTypes() n'existait pas :
 *     L'Observer appelait cette méthode statique pour construire les notes de soins,
 *     mais elle n'était pas définie dans FactureLigne.
 *     → Corrigé : la méthode construireNotesSoins() n'utilise plus labelsTypes().
 *       Elle utilise type_sous (valeur déjà lisible) directement, sans mapping.
 *
 *   BUG 3 — $ligne->infirmniere (faute d'orthographe) :
 *     La colonne s'appelle `infirmiere` en BDD mais le code utilisait `infirmniere`.
 *     → Corrigé.
 *
 *   COMPORTEMENT INCHANGÉ :
 *     - syncVisiteDuJour() : logique conservée intégralement.
 *     - created() / updated() : déclencheurs conservés.
 *     - L'Observer ne s'applique qu'aux lignes de type 'consultation'.
 */
class FactureLigneObserver
{
    /**
     * Déclenché après la création d'une ligne de facture.
     */
    public function created(FactureLigne $ligne): void
    {
        // On ignore la ligne initiale de snapshot (ordre = 0)
        if ((int) $ligne->ordre === 0) {
            return;
        }

        // L'Observer ne synchronise que les lignes de consultation.
        // Les lignes examen, acte, chambre ont leur propre logique.
        if ($ligne->facture_type !== 'consultation') {
            return;
        }

        $this->syncVisiteDuJour($ligne);
    }

    /**
     * Déclenché après la mise à jour d'une ligne.
     */
    public function updated(FactureLigne $ligne): void
    {
        if ($ligne->facture_type !== 'consultation') {
            return;
        }

        if ($ligne->isDirty(['montant', 'libelle', 'type_sous'])) {
            $this->syncVisiteDuJour($ligne);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Trouve la PatientVisit du patient pour la journée de la facture,
     * puis met à jour ses informations financières et ses notes.
     */
    protected function syncVisiteDuJour(FactureLigne $ligne): void
    {
        try {
            // CORRIGÉ : on charge la facture via factureParente() (méthode définie
            // dans FactureLigne) au lieu de l'inexistante facture().
            // On recharge la facture avec les relations nécessaires.
            $facture = $ligne->factureConsultation()
                             ->with([
                                 'patient:id,name,prenom,numero_dossier,prise_en_charge,assurance,numero_assurance,medecin_r,mode_paiement,demarcheur',
                                 'lignes',
                             ])
                             ->first();

            if (! $facture || ! $facture->patient) {
                Log::warning('FactureLigneObserver: facture ou patient introuvable', [
                    'ligne_id'   => $ligne->id,
                    'facture_id' => $ligne->facture_consultation_id,
                ]);
                return;
            }

            $patient = $facture->patient;

            // Déterminer la date de référence
            $dateVisite = $facture->date_insertion
                ? \Carbon\Carbon::parse($facture->date_insertion)->toDateString()
                : \Carbon\Carbon::parse($facture->created_at)->toDateString();

            // Retrouver ou créer la PatientVisit du jour
            $visit = PatientVisit::where('patient_id', $patient->id)
                ->whereDate('visit_date', $dateVisite)
                ->latest('created_at')
                ->first();

            if (! $visit) {
                $visit = PatientVisit::create([
                    'patient_id'       => $patient->id,
                    'user_id'          => $facture->user_id,
                    'visit_date'       => $dateVisite,
                    'motif'            => $facture->motif,
                    'details_motif'    => $facture->details_motif,
                    'montant'          => 0,
                    'avance'           => 0,
                    'assurance'        => $patient->assurance,
                    'numero_assurance' => $patient->numero_assurance,
                    'prise_en_charge'  => $patient->prise_en_charge,
                    'medecin_r'        => $facture->medecin_r,
                    'mode_paiement'    => $facture->mode_paiement ?? 'espèce',
                    'demarcheur'       => $facture->demarcheur,
                    'status'           => 'en_cours',
                    'notes'            => 'Visite créée automatiquement via facturation.',
                ]);

                Log::info('FactureLigneObserver: nouvelle visite créée', [
                    'patient_id'  => $patient->id,
                    'date_visite' => $dateVisite,
                ]);
            }

            // Construire les notes de soins
            $notesSoins = $this->construireNotesSoins($facture->lignes);

            $visit->update([
                'montant'   => (int) $facture->montant,
                'avance'    => (int) $facture->avance,
                'assurancec'=> (int) ($facture->assurancec ?? 0),
                'assurec'   => (int) ($facture->assurec ?? 0),
                'medecin_r' => $facture->medecin_r ?? $visit->medecin_r,
                'notes'     => $notesSoins,
            ]);

            Log::info('FactureLigneObserver: visite synchronisée', [
                'visit_id'   => $visit->id,
                'patient_id' => $patient->id,
                'nb_lignes'  => $facture->lignes->count(),
                'montant'    => $facture->montant,
            ]);

            Cache::tags(['patients', 'visits'])->flush();

        } catch (\Exception $e) {
            Log::error('FactureLigneObserver: erreur synchronisation', [
                'ligne_id' => $ligne->id,
                'error'    => $e->getMessage(),
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
            ]);
        }
    }

    /**
     * Construit un texte résumant tous les soins d'une facture.
     *
     * CORRIGÉ :
     *   - N'utilise plus FactureLigne::labelsTypes() (méthode inexistante).
     *   - Utilise $ligne->type_sous directement (valeur déjà stockée en base).
     *   - Corrige la faute $ligne->infirmniere → $ligne->infirmiere.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $lignes
     */
    protected function construireNotesSoins($lignes): string
    {
        if ($lignes->isEmpty()) {
            return '';
        }

        $lignesTexte = $lignes
            ->sortBy('ordre')
            ->map(function ($ligne) {
                // type_sous contient la valeur lisible (ex: "consultation", "examen_labo")
                $typeLabel = $ligne->type_sous ?? 'Acte';

                // CORRIGÉ : infirmiere (sans 'n' en trop)
                $praticien = $ligne->medecin
                    ? 'Dr ' . $ligne->medecin
                    : ($ligne->infirmiere ? 'Inf. ' . $ligne->infirmiere : '');

                $texte = "- [{$typeLabel}] {$ligne->libelle}";
                if ($praticien) {
                    $texte .= " — {$praticien}";
                }

                return $texte;
            })
            ->implode("\n");

        return "Soins du jour :\n" . $lignesTexte;
    }
}