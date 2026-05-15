<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PrescriptionMedicale Model
 * 
 * This model represents individual medication prescriptions.
 * Uses the 2019 migration structure with direct patient_id relationship.
 * 
 * Note: This table does NOT have timestamps (created_at/updated_at are not managed by Laravel)
 */
class PrescriptionMedicale extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prescription_medicales';

    /**
     * Indicates if the model should be timestamped.
     * Set to false because the 2019 migration doesn't have created_at/updated_at columns.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'patient_id',
        'allergie',
        'date',
        'medicament',
        'posologie',
        'voie',
        'heure',
        'matin',
        'apre_midi',
        'soir',
        'nuit',
        'regime',
        'consultation_specialise',
        'protocole',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'user_id' => 'integer',
        'patient_id' => 'integer',
        'heure' => 'integer',
        'horaire' => 'array',
    ];

    /**
     * Get the patient that owns the prescription.
     */
    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class, 'patient_id');
    }

    /**
     * Get the user (doctor) that created the prescription.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the administration records for this prescription.
     * 
     * Note: AdminPrescriptionMedicale should reference this prescription's ID
     */
    public function adminPrescriptionMedicales()
    {
        return $this->hasMany(\App\Models\AdminPrescriptionMedicale::class, 'prescription_medicale_id');
    }

    /**
     * Get the fiche prescription medicale (if you're migrating from the old system).
     * This is for backward compatibility during transition.
     * 
     * @deprecated This will be removed once fully migrated to 2019 structure
     */
    public function fichePrescriptionMedicale()
    {
        // This is a temporary relationship for migration purposes
        // The 2019 structure doesn't use fiches - each prescription is independent
        return null;
    }

    /**
     * Get formatted time slots for display.
     * Converts the individual time slot fields (matin, apre_midi, soir, nuit)
     * into a readable format.
     *
     * @return string
     */
    public function getFormattedTimeSlotsAttribute()
    {
        $slots = [];
        
        if (!empty($this->matin)) {
            $slots[] = "Matin: {$this->matin}";
        }
        if (!empty($this->apre_midi)) {
            $slots[] = "Après-midi: {$this->apre_midi}";
        }
        if (!empty($this->soir)) {
            $slots[] = "Soir: {$this->soir}";
        }
        if (!empty($this->nuit)) {
            $slots[] = "Nuit: {$this->nuit}";
        }
        
        return implode(' | ', $slots);
    }

    /**
     * Get horaire as array (for compatibility with your current blade files).
     * This converts the time slots into an array format similar to the JSON horaire.
     *
     * @return array
     */
    public function getHoraireAttribute()
    {
        $horaires = [];
        
        if (!empty($this->matin)) {
            $horaires[] = 'Matin';
        }
        if (!empty($this->apre_midi)) {
            $horaires[] = 'Après-midi';
        }
        if (!empty($this->soir)) {
            $horaires[] = 'Soir';
        }
        if (!empty($this->nuit)) {
            $horaires[] = 'Nuit';
        }
        
        return $horaires;
    }

    /**
     * Set horaire from array (for compatibility with your forms).
     * This allows you to pass ['08H', '12H', '20H'] and it will map them to time slots.
     *
     * @param array $value
     */
    public function setHoraireAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true) ?? [];
        }

        if (!is_array($value)) {
            return;
        }

        // Map hour ranges to time slots
        // Morning: 06H-12H, Afternoon: 12H-18H, Evening: 18H-00H, Night: 00H-06H
        $this->attributes['matin'] = '';
        $this->attributes['apre_midi'] = '';
        $this->attributes['soir'] = '';
        $this->attributes['nuit'] = '';

        foreach ($value as $hour) {
            $hourNum = (int) filter_var($hour, FILTER_SANITIZE_NUMBER_INT);
            
            if ($hourNum >= 6 && $hourNum < 12) {
                $this->attributes['matin'] = ($this->attributes['matin'] ? $this->attributes['matin'] . ', ' : '') . $hour;
            } elseif ($hourNum >= 12 && $hourNum < 18) {
                $this->attributes['apre_midi'] = ($this->attributes['apre_midi'] ? $this->attributes['apre_midi'] . ', ' : '') . $hour;
            } elseif ($hourNum >= 18 && $hourNum < 24) {
                $this->attributes['soir'] = ($this->attributes['soir'] ? $this->attributes['soir'] . ', ' : '') . $hour;
            } else {
                $this->attributes['nuit'] = ($this->attributes['nuit'] ? $this->attributes['nuit'] . ', ' : '') . $hour;
            }
        }
    }

    /**
     * Scope to filter by patient.
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope to filter by doctor.
     */
    public function scopeByDoctor($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get formatted date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('d/m/Y') : '';
    }

    /**
     * Get the creation date (using 'date' field instead of 'created_at').
     * This provides compatibility with code expecting 'created_at'.
     */
    public function getCreatedAtAttribute()
    {
        // Since we don't have created_at, use the 'date' field
        return $this->date;
    }
}