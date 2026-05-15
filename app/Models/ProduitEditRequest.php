<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ProduitEditRequest Model
 * 
 * Handles edit permissions workflow where Logistique/Pharmacien request
 * permission to edit products, which must be approved by Admin/Gestionnaire
 * Once approved, they can make multiple edits
 * 
 * Location: app/Models/ProduitEditRequest.php
 */
class ProduitEditRequest extends Model
{
    protected $fillable = [
        'produit_id',
        'requested_by',
        'reviewed_by',
        'revoked_by',
        'reason',
        'status',
        'can_edit',
        'review_comment',
        'reviewed_at',
        'revoked_at',
    ];

    protected $casts = [
        'can_edit' => 'boolean',
        'reviewed_at' => 'datetime',
        'revoked_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function revokedBy()
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    /**
     * Boot method to add event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Log when edit permission is requested
        static::created(function ($editRequest) {
            ProduitAuditLog::logAction(
                $editRequest->produit_id,
                'edit_permission_requested',
                "Permission de modification demandée par " . $editRequest->requestedBy->name . ": " . $editRequest->reason,
                null,
                ['permission_id' => $editRequest->id]
            );
        });

        // Log when edit permission is approved/rejected/revoked
        static::updated(function ($editRequest) {
            // Permission approved
            if ($editRequest->status === 'approved' && $editRequest->getOriginal('status') === 'pending') {
                ProduitAuditLog::logAction(
                    $editRequest->produit_id,
                    'edit_permission_granted',
                    "Permission de modification accordée à " . $editRequest->requestedBy->name . " par " . ($editRequest->reviewedBy->name ?? 'N/A'),
                    null,
                    ['permission_id' => $editRequest->id]
                );
            }

            // Permission rejected
            if ($editRequest->status === 'rejected' && $editRequest->getOriginal('status') === 'pending') {
                ProduitAuditLog::logAction(
                    $editRequest->produit_id,
                    'edit_permission_denied',
                    "Permission de modification refusée à " . $editRequest->requestedBy->name . " par " . ($editRequest->reviewedBy->name ?? 'N/A') . 
                    ": " . $editRequest->review_comment,
                    null,
                    ['permission_id' => $editRequest->id]
                );
            }

            // Permission revoked
            if ($editRequest->can_edit === false && $editRequest->getOriginal('can_edit') === true) {
                ProduitAuditLog::logAction(
                    $editRequest->produit_id,
                    'edit_permission_revoked',
                    "Permission de modification révoquée pour " . $editRequest->requestedBy->name . " par " . ($editRequest->revokedBy->name ?? 'N/A'),
                    null,
                    ['permission_id' => $editRequest->id]
                );
            }
        });
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
                     ->where('can_edit', true);
    }

    public function scopeByProduct($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    public function scopeByRequester($query, $userId)
    {
        return $query->where('requested_by', $userId);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Status helpers
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isActive()
    {
        return $this->status === 'approved' && $this->can_edit;
    }

    public function isRevoked()
    {
        return $this->status === 'approved' && !$this->can_edit;
    }

    /**
     * Get status badge color
     */
    public function getStatusColor()
    {
        if ($this->isActive()) {
            return 'success';
        }

        if ($this->isRevoked()) {
            return 'secondary';
        }

        $colors = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get status icon
     */
    public function getStatusIcon()
    {
        if ($this->isActive()) {
            return 'fas fa-check-circle';
        }

        if ($this->isRevoked()) {
            return 'fas fa-ban';
        }

        $icons = [
            'pending' => 'fas fa-clock',
            'approved' => 'fas fa-check-circle',
            'rejected' => 'fas fa-times-circle',
        ];

        return $icons[$this->status] ?? 'fas fa-circle';
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        if ($this->isActive()) {
            return 'Active';
        }

        if ($this->isRevoked()) {
            return 'Révoquée';
        }

        $labels = [
            'pending' => 'En attente',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get time since request
     */
    public function getTimeSinceRequest()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get time since review
     */
    public function getTimeSinceReview()
    {
        return $this->reviewed_at ? $this->reviewed_at->diffForHumans() : null;
    }
}