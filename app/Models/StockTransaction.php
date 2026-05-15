<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockTransaction extends Model
{
    protected $fillable = [
        'produit_id',
        'user_id',
        'type_transaction',
        'quantite',
        'stock_avant',
        'stock_apres',
        'reference_type',
        'reference_id',
        'numero_document',
        'motif',
        'commentaire',
        'date_transaction'
    ];

    protected $casts = [
        'date_transaction' => 'datetime'
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
     * Get reference document polymorphically
     */
    public function reference()
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        $modelClass = 'App\\Models\\' . $this->reference_type;
        
        if (class_exists($modelClass)) {
            return $modelClass::find($this->reference_id);
        }

        return null;
    }

    /**
     * Create transaction record and update product stock
     * 
     * @param array $data - Transaction data
     * @param bool $updateStock - Whether to automatically update product stock (default: true)
     * @return StockTransaction
     */
    public static function createTransaction($data, $updateStock = true)
    {
        return DB::transaction(function () use ($data, $updateStock) {
            $produit = Produit::findOrFail($data['produit_id']);
            
            $stockAvant = $produit->qte_stock;
            $quantite = $data['quantite'];
            
            // For entries, quantite is positive
            // For exits, quantite should be negative
            $stockApres = $stockAvant + $quantite;
            
            // Prevent negative stock
            if ($stockApres < 0) {
                throw new \Exception("Stock insuffisant pour {$produit->designation}. Stock actuel: {$stockAvant}, Demandé: " . abs($quantite));
            }
            
            // Create transaction record
            $transaction = self::create([
                'produit_id' => $data['produit_id'],
                'user_id' => $data['user_id'],
                'type_transaction' => $data['type_transaction'],
                'quantite' => $quantite,
                'stock_avant' => $stockAvant,
                'stock_apres' => $stockApres,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'numero_document' => $data['numero_document'] ?? null,
                'motif' => $data['motif'] ?? null,
                'commentaire' => $data['commentaire'] ?? null,
                'date_transaction' => $data['date_transaction'] ?? now(),
            ]);
            
            // Update product stock if requested
            if ($updateStock) {
                $produit->qte_stock = $stockApres;
                $produit->save();
                
                // Log the change if ProduitAuditLog exists
                if (class_exists('App\Models\ProduitAuditLog')) {
                    \App\Models\ProduitAuditLog::logAction(
                        $produit->id,
                        'updated',
                        "Transaction stock: {$transaction->getTypeLabel()} - Document: {$transaction->numero_document}",
                        ['qte_stock' => $stockAvant],
                        ['qte_stock' => $stockApres]
                    );
                }
            }
            
            return $transaction;
        });
    }

    /**
     * Helper: Create ENTRY transaction (stock increase)
     */
    public static function createEntree($produitId, $quantite, $data = [])
    {
        return self::createTransaction(array_merge([
            'produit_id' => $produitId,
            'user_id' => auth()->id(),
            'type_transaction' => $data['type_transaction'] ?? 'entree_commande',
            'quantite' => abs($quantite), // Force positive
        ], $data));
    }

    /**
     * Helper: Create EXIT transaction (stock decrease)
     */
    public static function createSortie($produitId, $quantite, $data = [])
    {
        return self::createTransaction(array_merge([
            'produit_id' => $produitId,
            'user_id' => auth()->id(),
            'type_transaction' => $data['type_transaction'] ?? 'sortie_vente',
            'quantite' => -abs($quantite), // Force negative
        ], $data));
    }

    /**
     * Scopes
     */
    public function scopeEntrees($query)
    {
        return $query->where('quantite', '>', 0);
    }

    public function scopeSorties($query)
    {
        return $query->where('quantite', '<', 0);
    }

    public function scopeByProduit($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    public function scopeDateRange($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_transaction', [$dateDebut, $dateFin]);
    }

    /**
     * Get transaction type label
     */
    public function getTypeLabel()
    {
        $labels = [
            'entree_commande' => 'Entrée (Commande)',
            'sortie_vente' => 'Sortie (Vente)',
            'sortie_vente_patient' => 'Sortie (Vente Patient)',
            'sortie_utilisation' => 'Sortie (Utilisation)',
            'retour_reutilisable' => 'Retour Réutilisable',
            'ajustement_inventaire' => 'Ajustement',
            'transfert' => 'Transfert',
            'perte' => 'Perte',
            'perime' => 'Périmé'
        ];

        return $labels[$this->type_transaction] ?? $this->type_transaction;
    }

    /**
     * Get transaction type color for badges
     */
    public function getTypeColor()
    {
        if ($this->isEntree()) {
            return 'success';
        } elseif ($this->isSortie()) {
            return 'danger';
        }
        return 'secondary';
    }

    /**
     * Check if transaction is entry or exit
     */
    public function isEntree()
    {
        return $this->quantite > 0;
    }

    public function isSortie()
    {
        return $this->quantite < 0;
    }
}