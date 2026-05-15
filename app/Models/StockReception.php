<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReception extends Model
{
    protected $fillable = [
        'bon_commande_id',
        'numero_reception',
        'received_by',
        'date_reception',
        'numero_bl',
        'livreur_nom',
        'livreur_telephone',
        'condition_livraison',
        'statut_reception',
        'commentaire',
        'problemes_constates',
        'validated_by',
        'validated_at',
        'validation_notes'
    ];

    protected $casts = [
        'date_reception' => 'date',
        'validated_at' => 'datetime'
    ];

    /**
     * Generate unique reception number
     */
    public static function generateNumeroReception()
    {
        $year = date('Y');
        $lastReception = self::whereYear('created_at', $year)
                            ->orderBy('id', 'desc')
                            ->first();
        
        $sequence = $lastReception ? intval(substr($lastReception->numero_reception, -4)) + 1 : 1;
        
        return sprintf('REC-%s-%04d', $year, $sequence);
    }

    /**
     * Relationships
     */
    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class);
    }

    public function items()
    {
        return $this->hasMany(StockReceptionItem::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Status helpers
     */
    public function isComplete()
    {
        return $this->statut_reception === 'complete';
    }

    public function isPartial()
    {
        return $this->statut_reception === 'partielle';
    }

    public function hasProblems()
    {
        return $this->statut_reception === 'avec_probleme';
    }

    public function isValidated()
    {
        return !is_null($this->validated_at);
    }

    /**
     * Calculate total received quantity
     */
    public function getTotalQuantiteRecue()
    {
        return $this->items()->sum('quantite_recue');
    }

    /**
     * Calculate total accepted quantity
     */
    public function getTotalQuantiteAcceptee()
    {
        return $this->items()->sum('quantite_acceptee');
    }

    /**
     * Calculate total refused quantity
     */
    public function getTotalQuantiteRefusee()
    {
        return $this->items()->sum('quantite_refusee');
    }

    /**
     * Check if reception is complete (all items fully received)
     */
    public function checkIfComplete()
    {
        $allItemsComplete = true;
        
        foreach ($this->items as $item) {
            if ($item->quantite_recue < $item->quantite_commandee) {
                $allItemsComplete = false;
                break;
            }
        }
        
        return $allItemsComplete;
    }
}