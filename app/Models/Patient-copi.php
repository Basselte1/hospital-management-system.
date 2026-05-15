<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Patient extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'numero_dossier',
        'name',                    
        'prenom',
        'mode_paiement',
        'mode_paiement_info_sup',  
        'assurance',
        'assurancec',
        'assurec',
        'numero_assurance',
        'prise_en_charge',
        'user_id',
        'telephone',
        'motif',
        'details_motif',
        'montant',
        'avance',
        'devise',
        'taux_conversion',
        'montant_devise',
        'reste',
        'reste1',
        'demarcheur',
        'date_insertion',
        'medecin_r',
        'image',
    ];

    protected $appends = ['telephone'];

    // public function devisimage()
    // {
    //     return $this->hasMany(\App\Models\DevisImage::class);
    // }
    public function facture_consultations()
    {
        return $this->hasMany(\App\Models\FactureConsultation::class);
    }

    public function facture_chambres()
    {
        return $this->hasMany(\App\Models\FactureChambre::class);
    }

    // public function devis()
    // {
    //     return $this->hasMany(\App\Models\Devis::class);
    // }
    // public function devisd()
    // {
    //     return $this->hasMany(\App\Models\Devisd::class, 'patient_id');
    // }

    // public function soins()
    // {
    //     return $this->hasMany(\App\Models\Soin::class);
    // }

    public function examens()
    {
        return $this->hasMany(\App\Models\Examen::class);
    }

    public function consultations()
    {
        return $this->hasMany(\App\Models\Consultation::class);
    }

    public function consultation_anesthesistes()
    {
        return $this->hasMany(\App\Models\ConsultationAnesthesiste::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(\App\Models\Prescription::class);
    }

    public function imageries()
    {
        return $this->hasMany(\App\Models\Imagerie::class);
    }

    public function compte_rendu_bloc_operatoires()
    {
        return $this->hasMany(\App\Models\CompteRenduBlocOperatoire::class);
    }

    public function interventions()
    {
        return $this->hasMany(\App\Models\Intervention::class);
    }

    public function ordonances()
    {
        return $this->hasMany(\App\Models\Ordonance::class);
    }

    public function fiche_interventions()
    {
        return $this->hasMany(\App\Models\FicheIntervention::class);
    }

    // public function facture_devis()
    // {
    //     return $this->hasMany(\App\Models\FactureDevi::class);
    // }

    public function fiche_prescription_medicale()
    {
        return $this->hasOne(FichePrescriptionMedicale::class);
    }

    public function visite_preanesthesiques()
    {
        return $this->hasMany(\App\Models\VisitePreanesthesique::class);
    }

    public function premedications()
    {
        return $this->hasMany(\App\Models\Premedication::class);
    }

    public function traitement_hospitalisations()
    {
        return $this->hasMany(\App\Models\TraitementHospitalisation::class);
    }
    public function adaptation_traitements()
    {
        return $this->hasMany(\App\Models\AdaptationTraitement::class);
    }

    public function parametres()
    {
        return $this->hasMany(\App\Models\Parametre::class);
    }

    public function dossiers()
    {
        return $this->hasMany(\App\Models\Dossier::class);
    }

    public function fiche_consommables()
    {
        return $this->hasMany(\App\Models\FicheConsommable::class);
    }

    public function observation_medicales()
    {
        return $this->hasMany(\App\Models\ObservationMedicale::class);
    }

    public function soins_infirmiers()
    {
        return $this->hasMany(\App\Models\SoinsInfirmier::class);
    }

    public function surveillance_post_anesthesiques()
    {
        return $this->hasMany(\App\Models\SurveillancePostAnesthesique::class);
    }

    /**
     * Factures journalières agrégées
     */
    public function daily_factures()
    {
        return $this->hasMany(\App\Models\DailyFacture::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }


    public function consultationdesuivi()
    {
        return $this->hasMany(\App\Models\ConsultationSuivi::class);
    }

    public function event()
    {
        return $this->hasMany(\App\Models\Event::class);
    }

    public function surveillance_rapproche_parametres()
    {
        return $this->hasMany(\App\Models\SurveillanceRapprocheParametre::class);
    }
    /**
     * Get all visits for this patient
     */
    public function visits()
    {
        return $this->hasMany(\App\Models\PatientVisit::class)->orderBy('visit_date', 'desc');
    }

    public function surveillance_scores()
    {
        return $this->hasMany(\App\Models\SurveillanceScore::class);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans;
    }
    public function isMedecin()
    {
        return Auth::user()->role_id === 2;

    }

    /**
     * Check if the patient is new (created within the last 48 hours)
     */
    public function isNew()
    {
        return $this->created_at->diffInHours(now()) <= 48;
    }
    public function getEmailAttribute()
    {
        return $this->dossiers()->latest()->first()->email ?? null;
    }

    public function getTelephoneAttribute()
    {
        // Use the loaded relation if available to avoid N+1 queries
        if ($this->relationLoaded('dossiers')) {
            $dossier = $this->dossiers->sortByDesc('created_at')->first();
        } else {
            $dossier = $this->dossiers()->latest()->first();
        }
        
        if ($dossier) {
            return $dossier->portable_1 ?? $dossier->portable_2 ?? $this->attributes['telephone'] ?? null;
        }

        return $this->attributes['telephone'] ?? null;
    }

            /**
     * Retourne une description lisible de la transaction en devise étrangère.
     * Exemple : "200 EUR × 655 = 131 000 FCFA"
     * Retourne null si le paiement était en FCFA.
     */
     public function getDeviseInfoAttribute(): ?string
    {
        if (!$this->devise || $this->devise === 'XAF') {
            return null;
        }
 
        if ($this->montant_devise && $this->taux_conversion) {
            return number_format($this->montant_devise, 2, ',', ' ')
                . ' ' . $this->devise
                . ' × ' . $this->taux_conversion
                . ' = ' . number_format($this->montant_devise * $this->taux_conversion, 0, ',', ' ')
                . ' FCFA';
        }
 
        return $this->devise;
    }



}