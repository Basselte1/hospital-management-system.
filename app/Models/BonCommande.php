<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonCommande extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero_bon',
        'created_by',
        'statut',
        'fournisseur_nom',
        'fournisseur_email',
        'fournisseur_telephone',
        'fournisseur_adresse',
        'date_commande',
        'date_livraison_souhaitee',
        'montant_total',
        'notes',
        'validated_by',
        'validated_at',
        'validation_comment',
        'received_by',
        'received_at',
        'reception_comment'
    ];

    protected $casts = [
        'date_commande' => 'date',
        'date_livraison_souhaitee' => 'date',
        'validated_at' => 'datetime',
        'received_at' => 'datetime'
    ];

    /**
     * Generate unique bon number
     */
    public static function generateNumeroBon()
    {
        $year = date('Y');
        $lastBon = self::whereYear('created_at', $year)
                       ->orderBy('id', 'desc')
                       ->first();
        
        $sequence = $lastBon ? intval(substr($lastBon->numero_bon, -4)) + 1 : 1;
        
        return sprintf('BC-%s-%04d', $year, $sequence);
    }

    /**
     * Relationships
     */
    public function items()
    {
        return $this->hasMany(BonCommandeItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Scopes
     */
    public function scopeBrouillon($query)
    {
        return $query->where('statut', 'brouillon');
    }

    public function scopeEnvoye($query)
    {
        return $query->where('statut', 'envoye');
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeEnAttenteValidation($query)
    {
        return $query->where('statut', 'envoye');
    }

    /**
     * Status helpers
     */
    public function isBrouillon()
    {
        return $this->statut === 'brouillon';
    }

    public function isEnvoye()
    {
        return $this->statut === 'envoye';
    }

    public function isValide()
    {
        return $this->statut === 'valide';
    }

    public function isReceptionne()
    {
        return $this->statut === 'receptionne';
    }

    public function canBeEdited()
    {
        return in_array($this->statut, ['brouillon']);
    }

    public function canBeValidated()
    {
        return $this->statut === 'envoye';
    }

    public function canBeReceived()
    {
        return $this->statut === 'valide';
    }

    /**
     * Calculate total
     */
    public function calculateTotal()
    {
        return $this->items()->sum('montant_ligne');
    }

    /**
     * Update total amount
     */
    public function updateTotal()
    {
        $this->montant_total = $this->calculateTotal();
        $this->save();
    }
}

