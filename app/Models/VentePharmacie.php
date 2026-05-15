<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VentePharmacie extends Model
{
    use SoftDeletes;

    protected $table = 'ventes_pharmacie';

    protected $fillable = [
        'numero_vente',
        'pharmacien_id',
        'patient_id',
        'client_id',
        'ordonance_id',
        'type_vente',
        'montant_total',
        'montant_paye',
        'montant_reste',
        'statut_paiement',
        'caissier_id',
        'date_paiement',
        'mode_paiement',
        'reference_paiement',
        'notes'
    ];

    protected $casts = [
        'date_paiement' => 'datetime'
    ];

    /**
     * Generate unique sale number
     */
    public static function generateNumeroVente()
    {
        $year = date('Y');
        $lastVente = self::whereYear('created_at', $year)
                        ->orderBy('id', 'desc')
                        ->first();
        
        $nextNumber = $lastVente ? (int) substr($lastVente->numero_vente, -4) + 1 : 1;
        
        return 'VP-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relationships
     */
    public function pharmacien()
    {
        return $this->belongsTo(User::class, 'pharmacien_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function ordonance()
    {
        return $this->belongsTo(Ordonance::class, 'ordonance_id');
    }

    public function caissier()
    {
        return $this->belongsTo(User::class, 'caissier_id');
    }

    public function items()
    {
        return $this->hasMany(VentePharmacieItem::class);
    }

    /**
     * Helper methods
     */
    public function isPatientSale()
    {
        return $this->type_vente === 'patient';
    }

    public function isExternalSale()
    {
        return $this->type_vente === 'client_externe';
    }

    public function isSoldee()
    {
        return $this->statut_paiement === 'soldee';
    }

    public function isEnAttente()
    {
        return $this->statut_paiement === 'en_attente';
    }

    public function canBeMarkedAsSoldee()
    {
        return $this->statut_paiement !== 'soldee';
    }

    /**
     * Calculate total from items
     */
    public function calculateTotal()
    {
        return $this->items()->sum('montant_ligne');
    }

    /**
     * Mark as paid (soldée) and deduct stock
     */
    public function markAsSoldee($caissier_id, $mode_paiement = null, $reference_paiement = null)
    {
        \DB::beginTransaction();
        try {
            // Update payment status
            $this->update([
                'statut_paiement' => 'soldee',
                'montant_paye' => $this->montant_total,
                'montant_reste' => 0,
                'caissier_id' => $caissier_id,
                'date_paiement' => now(),
                'mode_paiement' => $mode_paiement,
                'reference_paiement' => $reference_paiement,
            ]);

            // Deduct stock for each item
            foreach ($this->items as $item) {
                if (!$item->stock_deducted) {
                    $produit = Produit::find($item->produit_id);
                    
                    if ($produit) {
                        // Create stock transaction
                        StockTransaction::createTransaction([
                            'produit_id' => $produit->id,
                            'user_id' => $caissier_id,
                            'type_transaction' => $this->isPatientSale() ? 'sortie_vente_patient' : 'sortie_vente',
                            'quantite' => -$item->quantite, // Negative for exit
                            'reference_type' => 'VentePharmacie',
                            'reference_id' => $this->id,
                            'numero_document' => $this->numero_vente,
                            'motif' => $this->isPatientSale() 
                                ? 'Vente patient: ' . ($this->patient->name ?? 'N/A')
                                : 'Vente externe: ' . ($this->client->nom ?? 'N/A'),
                            'commentaire' => $item->notes,
                            'date_transaction' => now(),
                        ]);

                        // Update product stock
                        $produit->qte_stock -= $item->quantite;
                        $produit->save();

                        // Mark item as stock deducted
                        $item->update(['stock_deducted' => true]);
                    }
                }
            }

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get customer name (patient or client)
     */
    public function getCustomerNameAttribute()
    {
        if ($this->isPatientSale() && $this->patient) {
            return $this->patient->name . ' ' . ($this->patient->prenom ?? '');
        } elseif ($this->isExternalSale() && $this->client) {
            return $this->client->nom . ' ' . ($this->client->prenom ?? '');
        }
        return 'N/A';
    }
}