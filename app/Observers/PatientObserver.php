<?php

namespace App\Observers;

use App\Models\Patient;
use App\Models\PatientVisit;
use Illuminate\Support\Facades\Cache;

/**
 * Observer pour le modèle Patient
 * 
 * Gère la création automatique de la première visite lors de la création d'un patient
 * Cela permet de conserver l'historique COMPLET des visites depuis la création
 */
class PatientObserver
{
    /**
     * Handle the Patient "created" event.
     * 
     * Quand un nouveau patient est créé, on crée automatiquement sa PREMIÈRE visite
     * avec toutes les données variables (motif, montant, assurance, etc.)
     * 
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function created(Patient $patient)
    {
        // Créer la première visite avec les données du patient
        PatientVisit::create([
            'patient_id' => $patient->id,
            'user_id' => $patient->user_id,
            'visit_date' => $patient->date_insertion ?? $patient->created_at,
            
            // Informations médicales
            'motif' => $patient->motif,
            'details_motif' => $patient->details_motif,
            
            // Informations financières
            'montant' => $patient->montant ?? 0,
            'avance' => $patient->avance ?? 0,
            'reste' => $patient->reste ?? 0,
            
            // Informations d'assurance
            'assurance' => $patient->assurance,
            'numero_assurance' => $patient->numero_assurance,
            'prise_en_charge' => $patient->prise_en_charge,
            'assurancec' => $patient->assurancec ?? 0,
            'assurec' => $patient->assurec ?? 0,
            
            // Médecin traitant
            'medecin_r' => $patient->medecin_r,
            
            // Mode de paiement
            'mode_paiement' => $patient->mode_paiement ?? 'espèce',
            'mode_paiement_info_sup' => $patient->mode_paiement_info_sup,
            
            // Démarcheur
            'demarcheur' => $patient->demarcheur,
            
            // Statut initial
            'status' => 'terminee', // La première visite est considérée comme terminée
            
            // Notes
            'notes' => 'Première visite - Création du dossier patient',
        ]);

        // Nettoyer le cache
        Cache::tags(['patients', 'visits'])->flush();
    }

    /**
     * Handle the Patient "updated" event.
     * 
     * On n'a PAS besoin de gérer les mises à jour ici car c'est l'inverse :
     * C'est PatientVisitObserver qui va mettre à jour Patient
     *
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function updated(Patient $patient)
    {
        // Nettoyer le cache en cas de modification
        Cache::tags(['patients', 'visits'])->flush();
        Cache::forget("patient_{$patient->id}_premedications");
    }

    /**
     * Handle the Patient "deleted" event.
     *
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function deleted(Patient $patient)
    {
        // Les visites sont supprimées automatiquement via onDelete('cascade')
        // On nettoie juste le cache
        Cache::tags(['patients', 'visits'])->flush();
        Cache::forget("patient_{$patient->id}_premedications");
    }
}