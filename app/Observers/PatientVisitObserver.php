<?php

namespace App\Observers;

use App\Models\PatientVisit;
use Illuminate\Support\Facades\Cache;

/**
 * Observer pour le modèle PatientVisit
 * 
 * Gère la synchronisation automatique des données de visite vers le patient
 * Quand une nouvelle visite est créée, on met à jour le dossier patient avec les dernières infos
 */
class PatientVisitObserver
{
    /**
     * Handle the PatientVisit "created" event.
     * 
     * Quand une nouvelle visite est créée, on met à jour automatiquement
     * le dossier patient avec les DERNIÈRES informations variables
     * 
     * @param  \App\Models\PatientVisit  $visit
     * @return void
     */
    public function created(PatientVisit $visit)
    {
        // Mettre à jour le patient avec les données de la visite
        $this->syncPatientData($visit);
    }

    /**
     * Handle the PatientVisit "updated" event.
     * 
     * Si une visite est modifiée ET que c'est la dernière visite,
     * on met à jour le patient
     *
     * @param  \App\Models\PatientVisit  $visit
     * @return void
     */
    public function updated(PatientVisit $visit)
    {
        if (!$visit->patient) {
            Cache::tags(['patients', 'visits'])->flush();
            return;
        }

        $latestVisit = $visit->patient->visits()
            ->latest('visit_date')
            ->latest('created_at')
            ->first();

        if ($latestVisit && $latestVisit->id === $visit->id) {
            $this->syncPatientData($visit);
        }

        Cache::tags(['patients', 'visits'])->flush();
        Cache::forget("patient_{$visit->patient_id}_premedications");
    }

    /**
     * Handle the PatientVisit "deleted" event.
     * 
     * Si une visite est supprimée, on met à jour le patient avec
     * les données de la nouvelle dernière visite
     *
     * @param  \App\Models\PatientVisit  $visit
     * @return void
     */
    public function deleted(PatientVisit $visit)
    {
        if (!$visit->patient) {
            Cache::tags(['patients', 'visits'])->flush();
            return;
        }

        $latestVisit = $visit->patient->visits()
            ->latest('visit_date')
            ->latest('created_at')
            ->first();

        if ($latestVisit) {
            $this->syncPatientData($latestVisit);
        } else {
            $visit->patient->update([
                'motif' => null,
                'details_motif' => null,
                'montant' => 0,
                'avance' => 0,
                'reste' => 0,
                'assurance' => null,
                'numero_assurance' => null,
                'prise_en_charge' => null,
                'assurancec' => 0,
                'assurec' => 0,
                'medecin_r' => null,
                'mode_paiement' => 'espèce',
                'mode_paiement_info_sup' => null,
                'demarcheur' => null,
            ]);
        }

        Cache::tags(['patients', 'visits'])->flush();
        Cache::forget("patient_{$visit->patient_id}_premedications");
    }

    /**
     * Synchroniser les données de la visite vers le patient
     * 
     * Cette méthode met à jour UNIQUEMENT les données VARIABLES du patient
     * Les données FIXES (name, prenom, numero_dossier) ne sont JAMAIS touchées
     * 
     * @param  \App\Models\PatientVisit  $visit
     * @return void
     */
    protected function syncPatientData(PatientVisit $visit)
    {
        if (!$visit->patient) {
            return;
        }

        $visit->patient->update([
            // Informations médicales
            'motif' => $visit->motif,
            'details_motif' => $visit->details_motif,
            
            // Informations financières
            'montant' => $visit->montant ?? 0,
            'avance' => $visit->avance ?? 0,
            'reste' => $visit->reste ?? 0,
            
            // Informations d'assurance
            'assurance' => $visit->assurance,
            'numero_assurance' => $visit->numero_assurance,
            'prise_en_charge' => $visit->prise_en_charge,
            'assurancec' => $visit->assurancec ?? 0,
            'assurec' => $visit->assurec ?? 0,
            
            // Médecin traitant
            'medecin_r' => $visit->medecin_r,
            
            // Mode de paiement
            'mode_paiement' => $visit->mode_paiement ?? 'espèce',
            'mode_paiement_info_sup' => $visit->mode_paiement_info_sup,
            
            // Démarcheur
            'demarcheur' => $visit->demarcheur,
        ]);

        // Nettoyer le cache
        Cache::tags(['patients', 'visits'])->flush();
        Cache::forget("patient_{$visit->patient_id}_premedications");
    }
}