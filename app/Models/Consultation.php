<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Consultation
 *
 * @property int $id
 * @property int $patient_id
 * @property int $user_id
 * @property string $diagnostic
 * @property string $interrogatoire
 * @property string|null $antecedent_m
 * @property string|null $antecedent_c
 * @property string $medecin_r
 * @property string|null $allergie
 * @property string|null $groupe
 * @property string $proposition_therapeutique
 * @property string $proposition
 * @property string|null $examen_p
 * @property string|null $examen_c
 * @property string|null $motif_c
 * @property \Illuminate\Support\Carbon|null $date_intervention
 * @property \Illuminate\Support\Carbon|null $date_consultation
 * @property \Illuminate\Support\Carbon|null $date_consultation_anesthesiste
 * @property string|null $acte
 * @property string|null $type_intervention
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chambre $chambres
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\User $user
 * @mixin \Eloquent
 */
class Consultation extends Model
{
    
    /**
     * Utilisation de $guarded au lieu de $fillable pour plus de flexibilité
     * Mais ajout explicite des champs pour la documentation
     */
    protected $guarded = ['id']; // Protège seulement l'ID

    /**
     * Les attributs qui doivent être castés
     * IMPORTANT: Utiliser 'datetime' au lieu de 'date' pour préserver l'heure
     */
    protected $casts = [
        'date_intervention' => 'datetime',
        'date_consultation' => 'datetime',
        'date_consultation_anesthesiste' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Les attributs qui doivent être mutés en dates.
     * Cette propriété est optionnelle avec les casts ci-dessus mais peut aider
     */
    protected $dates = [
        'date_intervention',
        'date_consultation',
        'date_consultation_anesthesiste',
        'created_at',
        'updated_at',
    ];

    /**
     * Relations
     */

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class);
    }

    public function chambres()
    {
        return $this->belongsTo(\App\Models\Chambre::class);
    }

    // public function devis()
    // {
    //     return $this->belongsTo(\App\Models\Devis::class);
    // }
}