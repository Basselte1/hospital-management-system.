<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class PatientVisit
 * 
 * Représente une visite d'un patient à l'hôpital
 * Permet de tracer l'historique sans créer de doublons dans la table patients
 * 
 * @package App\Models
 */
class PatientVisit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id',
        'patient_name',           // snapshot — survives patient deletion
        'patient_numero_dossier', // snapshot — survives patient deletion
        'user_id',
        'visit_date',
        'motif',
        'details_motif',
        'montant',
        'avance',
        'reste',
        'assurance',
        'numero_assurance',
        'prise_en_charge',
        'assurancec',
        'assurec',
        'medecin_r',
        'mode_paiement',
        'mode_paiement_info_sup',
        'demarcheur',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'visit_date' => 'date',
        'montant' => 'integer',
        'avance' => 'integer',
        'reste' => 'integer',
        'assurancec' => 'integer',
        'assurec' => 'integer',
    ];

    /**
     * Get the patient that owns this visit.
     * withTrashed() ensures soft-deleted patients are still found.
     * For hard-deleted patients (legacy data), patient_name snapshot is the fallback.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    /**
     * Returns the patient's display name safely.
     * Uses the live relation if available, falls back to the snapshot.
     */
    public function getPatientDisplayNameAttribute(): string
    {
        if ($this->patient) {
            return trim($this->patient->name . ' ' . ($this->patient->prenom ?? ''));
        }
        return $this->patient_name ?? '[Patient supprimé]';
    }

    /**
     * Returns the patient's dossier number safely.
     */
    public function getPatientNumeroDossierAttribute(): ?string
    {
        return $this->patient->numero_dossier ?? $this->patient_numero_dossier ?? null;
    }

    /**
     * Get the user (secretary/staff) who registered this visit.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les visites d'aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('visit_date', Carbon::today());
    }

    /**
     * Scope pour les visites en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'en_attente');
    }

    /**
     * Scope pour les visites terminées
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'terminee');
    }

    /**
     * Scope pour un médecin spécifique
     */
    public function scopeByDoctor($query, $doctorName)
    {
        return $query->where('medecin_r', $doctorName);
    }

    /**
     * Scope pour une période
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('visit_date', [$startDate, $endDate]);
    }

    /**
     * Get formatted visit date
     */
    public function getFormattedVisitDateAttribute()
    {
        return $this->visit_date ? $this->visit_date->format('d/m/Y') : null;
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedMontantAttribute()
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Check if visit is today
     */
    public function isToday()
    {
        return $this->visit_date && $this->visit_date->isToday();
    }

    /**
     * Check if visit is recent (last 7 days)
     */
    public function isRecent()
    {
        return $this->visit_date && $this->visit_date->diffInDays(now()) <= 7;
    }

    /**
     * Get the status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return [
            'en_attente' => 'warning',
            'en_cours' => 'info',
            'terminee' => 'success',
            'annulee' => 'danger',
        ][$this->status] ?? 'secondary';
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute()
    {
        return [
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'terminee' => 'Terminée',
            'annulee' => 'Annulée',
        ][$this->status] ?? 'Inconnu';
    }

    /**
     * Calculate automatic reste (montant - avance)
     */
    public function calculateReste()
    {
        return $this->montant - $this->avance;
    }

    /**
     * Boot method to handle events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate reste before saving
        static::saving(function ($visit) {
            if ($visit->montant !== null && $visit->avance !== null) {
                $visit->reste = $visit->montant - $visit->avance;
            }
        });

        // Snapshot patient name+dossier at creation so the visit is self-contained
        static::creating(function ($visit) {
            if (empty($visit->patient_name)) {
                $patient = \App\Models\Patient::withTrashed()->find($visit->patient_id);
                if ($patient) {
                    $visit->patient_name = trim($patient->name . ' ' . ($patient->prenom ?? ''));
                    $visit->patient_numero_dossier = (string) $patient->numero_dossier;
                }
            }
        });
    }
}