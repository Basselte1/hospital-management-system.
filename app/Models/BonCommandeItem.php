<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonCommandeItem extends Model
{
    protected $fillable = [
        'bon_commande_id',
        'produit_id',
        'designation',
        'categorie',
        'quantite_commandee',
        'quantite_recue',
        'prix_unitaire',
        'montant_ligne',
        'notes'
    ];

    /**
     * Relationships
     */
    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Auto-calculate line amount on save
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->montant_ligne = $item->quantite_commandee * $item->prix_unitaire;
        });

        static::saved(function ($item) {
            // Update bon commande total
            $item->bonCommande->updateTotal();
        });

        static::deleted(function ($item) {
            // Update bon commande total after deletion
            $item->bonCommande->updateTotal();
        });
    }

    /**
     * Check if fully received
     */
    public function isFullyReceived()
    {
        return $this->quantite_recue >= $this->quantite_commandee;
    }

    /**
     * Get remaining quantity to receive
     */
    public function getRemainingQuantity()
    {
        return $this->quantite_commandee - $this->quantite_recue;
    }
}