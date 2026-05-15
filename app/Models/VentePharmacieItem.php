<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentePharmacieItem extends Model
{
    protected $fillable = [
        'vente_pharmacie_id',
        'produit_id',
        'designation',
        'quantite',
        'prix_unitaire',
        'montant_ligne',
        'stock_deducted',
        'notes'
    ];

    protected $casts = [
        'stock_deducted' => 'boolean'
    ];

    /**
     * Relationships
     */
    public function vente()
    {
        return $this->belongsTo(VentePharmacie::class, 'vente_pharmacie_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    /**
     * Auto-calculate line amount on save
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->montant_ligne = $item->quantite * $item->prix_unitaire;
        });
    }
}