<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUsage extends Model
{
    protected $fillable = [
        'produit_id',
        'quantite',
        'patient_id',
        'type_utilisation',
        'service',
        'medecin_id',
        'infirmier_id',
        'date_utilisation',
        'heure_utilisation',
        'motif',
        'observations',
        'quantite_retournable',
        'quantite_perdue',
        'statut_retour',
        'enregistre_par',
        'collecte_par',
        'collecte_at'
    ];

    protected $casts = [
        'date_utilisation' => 'date',
        'collecte_at' => 'datetime'
    ];

    /**
     * Relationships
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function infirmier()
    {
        return $this->belongsTo(User::class, 'infirmier_id');
    }

    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }

    public function collectePar()
    {
        return $this->belongsTo(User::class, 'collecte_par');
    }

    /**
     * Status helpers
     */
    public function isEnAttente()
    {
        return $this->statut_retour === 'en_attente';
    }

    public function isCollecte()
    {
        return $this->statut_retour === 'collecte';
    }

    public function isSterilise()
    {
        return $this->statut_retour === 'sterilise';
    }

    public function isRetourne()
    {
        return $this->statut_retour === 'retourne';
    }

    public function isNonRetournable()
    {
        return $this->statut_retour === 'non_retournable';
    }

    public function canBeCollected()
    {
        return $this->statut_retour === 'en_attente' && $this->quantite_retournable > 0;
    }

    public function hasReturnableItems()
    {
        return $this->quantite_retournable > 0;
    }

    /**
     * Get usage type label
     */
    public function getTypeUtilisationLabel()
    {
        $labels = [
            'intervention_chirurgicale' => 'Intervention Chirurgicale',
            'consultation' => 'Consultation',
            'hospitalisation' => 'Hospitalisation',
            'urgence' => 'Urgence',
            'autre' => 'Autre'
        ];

        return $labels[$this->type_utilisation] ?? $this->type_utilisation;
    }

    /**
     * Get status label
     */
    public function getStatutRetourLabel()
    {
        $labels = [
            'en_attente' => 'En Attente',
            'collecte' => 'Collecté',
            'sterilise' => 'En Stérilisation',
            'retourne' => 'Retourné',
            'non_retournable' => 'Non Retournable'
        ];

        return $labels[$this->statut_retour] ?? $this->statut_retour;
    }

    /**
     * Auto-set quantite_retournable when saving
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($usage) {
            // If not set, default retournable = quantite - perdue
            if ($usage->quantite_retournable === 0 && $usage->quantite_perdue === 0) {
                $usage->quantite_retournable = $usage->quantite;
            }
        });
    }

    /**
     * Scopes
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut_retour', 'en_attente');
    }

    public function scopeRetournable($query)
    {
        return $query->where('quantite_retournable', '>', 0);
    }

    public function scopeByService($query, $service)
    {
        return $query->where('service', $service);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type_utilisation', $type);
    }
}