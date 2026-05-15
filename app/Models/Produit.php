<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Produit
 */
class Produit extends Model
{
    protected $fillable = [
        'designation',
        'categorie',
        'qte_stock',
        'qte_alerte',
        'prix_unitaire',
        'user_id',
        'is_reusable',
        'nombre_utilisations_max',
        'notes_utilisation',
        'methode_sterilisation_recommandee',
        'duree_sterilisation_recommandee',
        'temperature_sterilisation',
        'qte_en_utilisation',
        'qte_en_sterilisation'
    ];

    protected $casts = [
        'is_reusable' => 'boolean'
    ];

    /**
     * Boot method to add event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Log when product is created
        static::created(function ($produit) {
            ProduitAuditLog::logAction(
                $produit->id,
                'created',
                "Produit '{$produit->designation}' créé par " . auth()->user()->name,
                null,
                $produit->toArray()
            );
        });

        // Log when product is updated
        static::updated(function ($produit) {
            $changes = $produit->getChanges();
            $original = $produit->getOriginal();
            
            // Filter only changed fields
            $oldValues = [];
            $newValues = [];
            foreach ($changes as $field => $newValue) {
                if (in_array($field, ['designation', 'categorie', 'qte_stock', 'qte_alerte', 'prix_unitaire'])) {
                    $oldValues[$field] = $original[$field] ?? null;
                    $newValues[$field] = $newValue;
                }
            }

            if (!empty($newValues)) {
                ProduitAuditLog::logAction(
                    $produit->id,
                    'updated',
                    "Produit '{$produit->designation}' modifié par " . auth()->user()->name,
                    $oldValues,
                    $newValues
                );
            }
        });

        // Log when product is deleted
        static::deleted(function ($produit) {
            ProduitAuditLog::logAction(
                $produit->id,
                'deleted',
                "Produit '{$produit->designation}' supprimé par " . auth()->user()->name,
                $produit->toArray(),
                null
            );
        });
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function factures()
    {
        return $this->belongsToMany(\App\Models\Facture::class)
            ->withPivot('item', 'prix_total')
            ->withTimestamps();
    }

    public function fiche_consommables()
    {
        return $this->hasMany(\App\Models\FicheConsommable::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function editRequests()
    {
        return $this->hasMany(ProduitEditRequest::class);
    }

    public function activeEditPermissions()
    {
        return $this->hasMany(ProduitEditRequest::class)
            ->where('status', 'approved')
            ->where('can_edit', true);
    }

    public function pendingEditRequests()
    {
        return $this->hasMany(ProduitEditRequest::class)->where('status', 'pending');
    }

    public function auditLogs()
    {
        return $this->hasMany(ProduitAuditLog::class)->orderBy('created_at', 'desc');
    }

    public function sterilizations()
    {
        return $this->hasMany(ProductSterilization::class, 'produit_id');
    }

    public function usages()
    {
        return $this->hasMany(ProductUsage::class, 'produit_id');
    }

    // Helper method
    public function getQuantiteDisponible()
    {
        return $this->qte_stock - $this->qte_en_utilisation - $this->qte_en_sterilisation;
    }

    /**
     * Check if user has active edit permission for this product
     */
    public function userHasEditPermission($userId)
    {
        return $this->editRequests()
            ->where('requested_by', $userId)
            ->where('status', 'approved')
            ->where('can_edit', true)
            ->exists();
    }

    /**
     * Get active edit permission for user
     */
    public function getActiveEditPermission($userId)
    {
        return $this->editRequests()
            ->where('requested_by', $userId)
            ->where('status', 'approved')
            ->where('can_edit', true)
            ->first();
    }
}















