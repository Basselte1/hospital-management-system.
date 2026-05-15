<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSterilization extends Model
{
    protected $fillable = [
        'produit_id',
        'quantite',
        'numero_lot',
        'methode_sterilisation',
        'date_sterilisation',
        'heure_debut',
        'heure_fin',
        'temperature',
        'duree_minutes',
        'sterilise_par',
        'verifie_par',
        'resultat_test',
        'type_indicateur',
        'observations',
        'statut',
        'retourne_par',
        'retourne_at',
        'raison_rejet'
    ];

    protected $casts = [
        'date_sterilisation' => 'date',
        'retourne_at' => 'datetime'
    ];

    /**
     * Generate unique lot number
     */
    public static function generateNumeroLot()
    {
        $date = date('Ymd');
        $lastLot = self::whereDate('created_at', today())
                       ->orderBy('id', 'desc')
                       ->first();
        
        $sequence = $lastLot ? intval(substr($lastLot->numero_lot, -3)) + 1 : 1;
        
        return sprintf('LOT-%s-%03d', $date, $sequence);
    }

    /**
     * Relationships
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function sterilisePar()
    {
        return $this->belongsTo(User::class, 'sterilise_par');
    }

    public function verifiePar()
    {
        return $this->belongsTo(User::class, 'verifie_par');
    }

    public function retournePar()
    {
        return $this->belongsTo(User::class, 'retourne_par');
    }

    /**
     * Status helpers
     */
    public function isEnCours()
    {
        return $this->statut === 'en_cours';
    }

    public function isTermine()
    {
        return $this->statut === 'termine_en_attente';
    }

    public function isValide()
    {
        return $this->statut === 'valide';
    }

    public function isRetourne()
    {
        return $this->statut === 'retourne';
    }

    public function isRejete()
    {
        return $this->statut === 'rejete';
    }

    public function canBeValidated()
    {
        return $this->statut === 'termine_en_attente' && $this->resultat_test === 'conforme';
    }

    public function canBeReturned()
    {
        return $this->statut === 'valide';
    }

    /**
     * Get sterilization method label
     */
    public function getMethodeLabel()
    {
        $labels = [
            'autoclave' => 'Autoclave (Vapeur)',
            'chaleur_seche' => 'Chaleur Sèche',
            'gaz_eto' => 'Gaz ETO',
            'plasma' => 'Plasma',
            'chimique' => 'Chimique',
            'autre' => 'Autre'
        ];

        return $labels[$this->methode_sterilisation] ?? $this->methode_sterilisation;
    }

    /**
     * Get status label
     */
    public function getStatutLabel()
    {
        $labels = [
            'en_cours' => 'En Cours',
            'termine_en_attente' => 'Terminé - En Attente',
            'valide' => 'Validé',
            'retourne' => 'Retourné au Stock',
            'rejete' => 'Rejeté'
        ];

        return $labels[$this->statut] ?? $this->statut;
    }

    /**
     * Get status badge color
     */
    public function getStatutBadgeColor()
    {
        $colors = [
            'en_cours' => 'info',
            'termine_en_attente' => 'warning',
            'valide' => 'success',
            'retourne' => 'primary',
            'rejete' => 'danger'
        ];

        return $colors[$this->statut] ?? 'secondary';
    }

    /**
     * Scopes
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'termine_en_attente');
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeRetourne($query)
    {
        return $query->where('statut', 'retourne');
    }
}