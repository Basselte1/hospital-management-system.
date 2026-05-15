<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReceptionItem extends Model
{
    protected $fillable = [
        'stock_reception_id',
        'bon_commande_item_id',
        'produit_id',
        'quantite_commandee',
        'quantite_recue',
        'quantite_acceptee',
        'quantite_refusee',
        'etat_produit',
        'date_peremption',
        'numero_lot',
        'observation'
    ];

    protected $casts = [
        'date_peremption' => 'date'
    ];

    /**
     * Relationships
     */
    public function stockReception()
    {
        return $this->belongsTo(StockReception::class);
    }

    public function bonCommandeItem()
    {
        return $this->belongsTo(BonCommandeItem::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Check if item is fully received
     */
    public function isFullyReceived()
    {
        return $this->quantite_recue >= $this->quantite_commandee;
    }

    /**
     * Check if item has quality issues
     */
    public function hasQualityIssues()
    {
        return in_array($this->etat_produit, ['non_conforme', 'endommage']) || $this->quantite_refusee > 0;
    }

    /**
     * Get remaining quantity to receive
     */
    public function getRemainingQuantity()
    {
        return $this->quantite_commandee - $this->quantite_recue;
    }

    /**
     * Auto-calculate accepted and refused quantities
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // If all received quantity is accepted (default behavior)
            if ($item->quantite_acceptee == 0 && $item->quantite_refusee == 0) {
                $item->quantite_acceptee = $item->quantite_recue;
            }
        });
    }
}