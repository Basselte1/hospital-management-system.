<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneDevi extends Model
{
    protected $fillable = [
        'devi_id',
        'type',
        'element',
        'quantite',
        'prix_u',
        'produit_id',
        'stock_deducted'
    ];

    protected $casts = [
        'stock_deducted' => 'boolean',
        'quantite' => 'integer',
        'prix_u' => 'integer'
    ];

    /**
     * Get the devis this line belongs to
     */
    public function devi()
    {
        return $this->belongsTo(Devi::class, 'devi_id');
    }

    /**
     * Get the product if this line is a product
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    /**
     * Calculate line total
     */
    public function getTotal()
    {
        return $this->quantite * $this->prix_u;
    }

    /**
     * Check if this is a procedure line
     */
    public function isProcedure()
    {
        return $this->type === 'procedure';
    }

    /**
     * Check if this is a product line
     */
    public function isProduct()
    {
        return in_array($this->type, ['medication', 'material', 'anesthesie']);
    }

    /**
     * Get product category label
     */
    public function getTypeLabel()
    {
        $labels = [
            'procedure' => 'Procédure',
            'medication' => 'Médicament',
            'material' => 'Matériel',
            'anesthesie' => 'Anesthésie'
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Scope: Only procedures
     */
    public function scopeProcedures($query)
    {
        return $query->where('type', 'procedure');
    }

    /**
     * Scope: Only products
     */
    public function scopeProducts($query)
    {
        return $query->whereIn('type', ['medication', 'material', 'anesthesie']);
    }

    /**
     * Scope: By type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}