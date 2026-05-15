<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProduitAuditLog extends Model
{
    protected $fillable = [
        'produit_id',
        'user_id',
        'action',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Static method to log an action
     * 
     * @param int $produitId
     * @param string $action (created, updated, deleted, approved, rejected, edit_requested, edit_approved, edit_rejected)
     * @param string $description
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return ProduitAuditLog
     */
    public static function logAction($produitId, $action, $description, $oldValues = null, $newValues = null)
    {
        return self::create([
            'produit_id' => $produitId,
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get action label for display
     */
    public function getActionLabel()
    {
        $labels = [
            'created' => 'Création',
            'updated' => 'Modification',
            'deleted' => 'Suppression',
            'approved' => 'Approbation',
            'rejected' => 'Rejet',
            'edit_requested' => 'Demande de modification',
            'edit_approved' => 'Modification approuvée',
            'edit_rejected' => 'Modification rejetée',
        ];

        return $labels[$this->action] ?? $this->action;
    }

    /**
     * Get action color for badges
     */
    public function getActionColor()
    {
        $colors = [
            'created' => 'success',
            'updated' => 'info',
            'deleted' => 'danger',
            'approved' => 'success',
            'rejected' => 'danger',
            'edit_requested' => 'warning',
            'edit_approved' => 'success',
            'edit_rejected' => 'danger',
        ];

        return $colors[$this->action] ?? 'secondary';
    }

    /**
     * Get action icon
     */
    public function getActionIcon()
    {
        $icons = [
            'created' => 'fas fa-plus-circle',
            'updated' => 'fas fa-edit',
            'deleted' => 'fas fa-trash-alt',
            'approved' => 'fas fa-check-circle',
            'rejected' => 'fas fa-times-circle',
            'edit_requested' => 'fas fa-exclamation-circle',
            'edit_approved' => 'fas fa-check-double',
            'edit_rejected' => 'fas fa-ban',
        ];

        return $icons[$this->action] ?? 'fas fa-circle';
    }

    /**
     * Get formatted changes for display
     */
    public function getFormattedChanges()
    {
        if (!$this->old_values || !$this->new_values) {
            return [];
        }

        $changes = [];
        $fieldLabels = [
            'designation' => 'Désignation',
            'categorie' => 'Catégorie',
            'qte_stock' => 'Quantité en stock',
            'qte_alerte' => 'Seuil d\'alerte',
            'prix_unitaire' => 'Prix unitaire',
            'status' => 'Statut',
        ];

        foreach ($this->new_values as $field => $newValue) {
            if (isset($this->old_values[$field]) && $this->old_values[$field] != $newValue) {
                $changes[] = [
                    'field' => $fieldLabels[$field] ?? $field,
                    'old' => $this->old_values[$field],
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    /**
     * Scopes
     */
    public function scopeByProduct($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeDateRange($query, $dateFrom, $dateTo)
    {
        return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}