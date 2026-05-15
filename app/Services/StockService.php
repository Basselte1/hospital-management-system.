<?php

namespace App\Services;

use App\Models\Produit;
use App\Models\StockTransaction;
use App\Models\ProduitAuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Stock Management Service
 * 
 * Centralized service for all stock operations to ensure consistency
 * and proper tracking of stock movements
 */
class StockService
{
    /**
     * Add stock to product (Entry)
     * 
     * @param int $produitId
     * @param int $quantite
     * @param string $typeTransaction
     * @param array $additionalData
     * @return StockTransaction
     */
    public static function addStock($produitId, $quantite, $typeTransaction = 'entree_commande', $additionalData = [])
    {
        return DB::transaction(function () use ($produitId, $quantite, $typeTransaction, $additionalData) {
            $produit = Produit::lockForUpdate()->findOrFail($produitId);
            
            $stockAvant = $produit->qte_stock;
            $stockApres = $stockAvant + abs($quantite);
            
            // Update product stock
            $produit->qte_stock = $stockApres;
            $produit->save();
            
            // Create transaction
            $transaction = StockTransaction::create([
                'produit_id' => $produitId,
                'user_id' => $additionalData['user_id'] ?? Auth::id(),
                'type_transaction' => $typeTransaction,
                'quantite' => abs($quantite),
                'stock_avant' => $stockAvant,
                'stock_apres' => $stockApres,
                'reference_type' => $additionalData['reference_type'] ?? null,
                'reference_id' => $additionalData['reference_id'] ?? null,
                'numero_document' => $additionalData['numero_document'] ?? null,
                'motif' => $additionalData['motif'] ?? null,
                'commentaire' => $additionalData['commentaire'] ?? null,
                'date_transaction' => $additionalData['date_transaction'] ?? now(),
            ]);
            
            // Log audit
            ProduitAuditLog::logAction(
                $produitId,
                'updated',
                "Entrée de stock: +{$quantite} - {$typeTransaction}" . 
                ($transaction->numero_document ? " (Doc: {$transaction->numero_document})" : ""),
                ['qte_stock' => $stockAvant],
                ['qte_stock' => $stockApres]
            );
            
            return $transaction;
        });
    }
    
    /**
     * Remove stock from product (Exit)
     * 
     * @param int $produitId
     * @param int $quantite
     * @param string $typeTransaction
     * @param array $additionalData
     * @return StockTransaction
     * @throws \Exception if insufficient stock
     */
    public static function removeStock($produitId, $quantite, $typeTransaction = 'sortie_vente', $additionalData = [])
    {
        return DB::transaction(function () use ($produitId, $quantite, $typeTransaction, $additionalData) {
            $produit = Produit::lockForUpdate()->findOrFail($produitId);
            
            $stockAvant = $produit->qte_stock;
            $quantitePositive = abs($quantite);
            $stockApres = $stockAvant - $quantitePositive;
            
            // Check if sufficient stock
            if ($stockApres < 0) {
                throw new \Exception(
                    "Stock insuffisant pour '{$produit->designation}'. " .
                    "Stock actuel: {$stockAvant}, Demandé: {$quantitePositive}"
                );
            }
            
            // Update product stock
            $produit->qte_stock = $stockApres;
            $produit->save();
            
            // Create transaction (negative quantity)
            $transaction = StockTransaction::create([
                'produit_id' => $produitId,
                'user_id' => $additionalData['user_id'] ?? Auth::id(),
                'type_transaction' => $typeTransaction,
                'quantite' => -$quantitePositive,
                'stock_avant' => $stockAvant,
                'stock_apres' => $stockApres,
                'reference_type' => $additionalData['reference_type'] ?? null,
                'reference_id' => $additionalData['reference_id'] ?? null,
                'numero_document' => $additionalData['numero_document'] ?? null,
                'motif' => $additionalData['motif'] ?? null,
                'commentaire' => $additionalData['commentaire'] ?? null,
                'date_transaction' => $additionalData['date_transaction'] ?? now(),
            ]);
            
            // Log audit
            ProduitAuditLog::logAction(
                $produitId,
                'updated',
                "Sortie de stock: -{$quantitePositive} - {$typeTransaction}" . 
                ($transaction->numero_document ? " (Doc: {$transaction->numero_document})" : ""),
                ['qte_stock' => $stockAvant],
                ['qte_stock' => $stockApres]
            );
            
            return $transaction;
        });
    }
    
    /**
     * Adjust stock (for inventory corrections)
     * 
     * @param int $produitId
     * @param int $newStock
     * @param string $motif
     * @return StockTransaction
     */
    public static function adjustStock($produitId, $newStock, $motif = 'Ajustement inventaire')
    {
        return DB::transaction(function () use ($produitId, $newStock, $motif) {
            $produit = Produit::lockForUpdate()->findOrFail($produitId);
            
            $stockAvant = $produit->qte_stock;
            $difference = $newStock - $stockAvant;
            
            if ($difference == 0) {
                throw new \Exception("Le nouveau stock est identique au stock actuel");
            }
            
            // Update product stock
            $produit->qte_stock = $newStock;
            $produit->save();
            
            // Create transaction
            $transaction = StockTransaction::create([
                'produit_id' => $produitId,
                'user_id' => Auth::id(),
                'type_transaction' => 'ajustement_inventaire',
                'quantite' => $difference,
                'stock_avant' => $stockAvant,
                'stock_apres' => $newStock,
                'motif' => $motif,
                'date_transaction' => now(),
            ]);
            
            // Log audit
            $differenceSign = $difference > 0 ? '+' : '';
            ProduitAuditLog::logAction(
                $produitId,
                'updated',
                "Ajustement de stock: {$stockAvant} → {$newStock} ({$differenceSign}{$difference}) - {$motif}",
                ['qte_stock' => $stockAvant],
                ['qte_stock' => $newStock]
            );
            
            return $transaction;
        });
    }
    
    /**
     * Process stock reception (called when validating reception)
     * 
     * @param \App\Models\StockReception $reception
     * @return array Array of created transactions
     */
    public static function processReception($reception)
    {
        $transactions = [];
        
        DB::transaction(function () use ($reception, &$transactions) {
            foreach ($reception->items as $item) {
                if ($item->produit_id && $item->quantite_acceptee > 0) {
                    $transaction = self::addStock(
                        $item->produit_id,
                        $item->quantite_acceptee,
                        'entree_commande',
                        [
                            'reference_type' => 'StockReception',
                            'reference_id' => $reception->id,
                            'numero_document' => $reception->numero_reception,
                            'motif' => "Réception bon de commande {$reception->bonCommande->numero_bon}",
                            'commentaire' => $item->observation,
                            'date_transaction' => $reception->date_reception,
                        ]
                    );
                    
                    $transactions[] = $transaction;
                }
            }
        });
        
        return $transactions;
    }
    
    /**
     * Process sale/vente (deduct stock)
     * 
     * @param \App\Models\VentePharmacieItem $venteItem
     * @return StockTransaction
     */
    public static function processVente($venteItem)
    {
        return self::removeStock(
            $venteItem->produit_id,
            $venteItem->quantite,
            'sortie_vente',
            [
                'reference_type' => 'VentePharmacieItem',
                'reference_id' => $venteItem->id,
                'numero_document' => $venteItem->ventePharmacier->numero_vente ?? null,
                'motif' => 'Vente pharmacie',
            ]
        );
    }
    
    /**
     * Check if product has sufficient stock
     * 
     * @param int $produitId
     * @param int $quantiteRequise
     * @return bool
     */
    public static function hasStockSuffisant($produitId, $quantiteRequise)
    {
        $produit = Produit::find($produitId);
        
        if (!$produit) {
            return false;
        }
        
        return $produit->qte_stock >= $quantiteRequise;
    }
    
    /**
     * Get available stock for a product
     * 
     * @param int $produitId
     * @return int
     */
    public static function getStockDisponible($produitId)
    {
        $produit = Produit::find($produitId);
        
        if (!$produit) {
            return 0;
        }
        
        return $produit->qte_stock;
    }
    
    /**
     * Get stock movement history for a product
     * 
     * @param int $produitId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getStockHistory($produitId, $limit = 50)
    {
        return StockTransaction::where('produit_id', $produitId)
            ->with('user')
            ->orderBy('date_transaction', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}