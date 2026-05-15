<?php

namespace App\Http\Controllers;

use App\Models\BonCommande;
use App\Models\StockReception;
use App\Models\StockReceptionItem;
use App\Models\Produit;
use App\Models\ProduitAuditLog;
use App\Models\StockTransaction;
use App\Services\PdfService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockReceptionController extends Controller
{
    /**
     * Display list of receptions
     */
    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 5])) {
            abort(403, 'Accès non autorisé');
        }

        $receptions = StockReception::with(['bonCommande', 'receivedBy', 'validatedBy'])
                                    ->latest()
                                    ->paginate(20);

        return view('admin.stock_receptions.index', compact('receptions'));
    }

    /**
     * Show validated orders ready for reception
     */
    public function readyForReception()
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 5])) {
            abort(403, 'Accès non autorisé');
        }

        $ordersReady = BonCommande::where('statut', 'valide')
                                  ->with(['items', 'createdBy', 'validatedBy'])
                                  ->latest()
                                  ->paginate(20);

        return view('admin.stock_receptions.ready', compact('ordersReady'));
    }

    /**
     * Show reception form for a specific order
     */
    public function create($bonCommandeId)
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 5])) {
            abort(403, 'Accès non autorisé');
        }

        $bonCommande = BonCommande::with(['items.produit'])->findOrFail($bonCommandeId);

        if (!$bonCommande->canBeReceived()) {
            return back()->with('error', 'Ce bon de commande ne peut pas être réceptionné');
        }

        // Check which products exist in database
        $productsStatus = [];
        foreach ($bonCommande->items as $item) {
            $existingProduct = Produit::where('designation', 'LIKE', '%' . $item->designation . '%')
                                     ->orWhere('designation', $item->designation)
                                     ->first();
            
            $productsStatus[$item->id] = [
                'exists' => $existingProduct ? true : false,
                'product' => $existingProduct,
                'item' => $item
            ];
        }

        return view('admin.stock_receptions.create', compact('bonCommande', 'productsStatus'));
    }

    /**
     * Store reception with automatic product matching/creation
     * FIXED: Better handling of product IDs and ensuring they're saved
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 5])) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'bon_commande_id' => 'required|exists:bon_commandes,id',
            'date_reception' => 'required|date',
            'numero_bl' => 'nullable|string',
            'livreur_nom' => 'nullable|string',
            'livreur_telephone' => 'nullable|string',
            'condition_livraison' => 'nullable|string',
            'commentaire' => 'nullable|string',
            'items' => 'required|array',
            'items.*.bon_commande_item_id' => 'required|exists:bon_commande_items,id',
            'items.*.quantite_recue' => 'required|integer|min:0',
            'items.*.quantite_acceptee' => 'nullable|integer|min:0',
            'items.*.quantite_refusee' => 'nullable|integer|min:0',
            'items.*.etat_produit' => 'required|in:conforme,non_conforme,endommage',
            'items.*.date_peremption' => 'nullable|date',
            'items.*.numero_lot' => 'nullable|string',
            'items.*.observation' => 'nullable|string',
            // New product creation fields
            'items.*.create_new_product' => 'nullable|boolean',
            'items.*.new_product_categorie' => 'nullable|required_if:items.*.create_new_product,true|string',
            'items.*.new_product_prix_unitaire' => 'nullable|required_if:items.*.create_new_product,true|integer',
            'items.*.new_product_qte_alerte' => 'nullable|required_if:items.*.create_new_product,true|integer',
            'items.*.existing_product_id' => 'nullable|exists:produits,id',
        ]);

        DB::beginTransaction();
        try {
            $bonCommande = BonCommande::findOrFail($request->bon_commande_id);

            // Determine reception status
            $totalCommande = $bonCommande->items->sum('quantite_commandee');
            $totalRecu = collect($request->items)->sum('quantite_recue');
            $hasProblems = collect($request->items)->contains(function($item) {
                return $item['etat_produit'] !== 'conforme' || 
                       (isset($item['quantite_refusee']) && $item['quantite_refusee'] > 0);
            });

            $statutReception = $hasProblems ? 'avec_probleme' : 
                              ($totalRecu < $totalCommande ? 'partielle' : 'complete');

            // Create reception
            $reception = StockReception::create([
                'bon_commande_id' => $request->bon_commande_id,
                'numero_reception' => StockReception::generateNumeroReception(),
                'received_by' => Auth::id(),
                'date_reception' => $request->date_reception,
                'numero_bl' => $request->numero_bl,
                'livreur_nom' => $request->livreur_nom,
                'livreur_telephone' => $request->livreur_telephone,
                'condition_livraison' => $request->condition_livraison,
                'statut_reception' => $statutReception,
                'commentaire' => $request->commentaire,
                'problemes_constates' => $hasProblems ? $request->problemes_constates : null,
            ]);

            // Process each item
            foreach ($request->items as $itemData) {
                $bonCommandeItem = \App\Models\BonCommandeItem::findOrFail($itemData['bon_commande_item_id']);
                
                $quantiteAcceptee = $itemData['quantite_acceptee'] ?? $itemData['quantite_recue'];
                $quantiteRefusee = $itemData['quantite_refusee'] ?? 0;

                // FIXED: Determine product ID - create new or use existing
                $produitId = null;
                
                // Priority 1: Use explicitly selected existing product
                if (!empty($itemData['existing_product_id'])) {
                    $produitId = $itemData['existing_product_id'];
                    
                    // Update bon_commande_item with the product ID
                    $bonCommandeItem->produit_id = $produitId;
                    $bonCommandeItem->save();
                }
                // Priority 2: Check if bon_commande_item already has a product_id
                elseif ($bonCommandeItem->produit_id) {
                    $produitId = $bonCommandeItem->produit_id;
                }
                // Priority 3: Create new product if requested
                elseif (!empty($itemData['create_new_product']) && $itemData['create_new_product'] == true) {
                    $newProduct = Produit::create([
                        'designation' => $bonCommandeItem->designation,
                        'categorie' => $itemData['new_product_categorie'],
                        'qte_stock' => 0, // Will be updated during validation
                        'qte_alerte' => $itemData['new_product_qte_alerte'],
                        'prix_unitaire' => $itemData['new_product_prix_unitaire'],
                        'user_id' => Auth::id(),
                        // FIXED: Set is_reusable for MATERIEL category
                        'is_reusable' => ($itemData['new_product_categorie'] === 'MATERIEL') ? true : false,
                    ]);
                    
                    $produitId = $newProduct->id;
                    
                    // Update bon_commande_item with the new product ID
                    $bonCommandeItem->produit_id = $produitId;
                    $bonCommandeItem->save();
                    
                    ProduitAuditLog::logAction(
                        $produitId,
                        'created',
                        "Produit créé automatiquement lors de la réception {$reception->numero_reception}",
                        null,
                        $newProduct->toArray()
                    );
                }
                // Priority 4: Try to find existing product by designation
                else {
                    $existingProduct = Produit::where('designation', $bonCommandeItem->designation)->first();
                    
                    if ($existingProduct) {
                        $produitId = $existingProduct->id;
                        
                        // Update bon_commande_item with found product ID
                        $bonCommandeItem->produit_id = $produitId;
                        $bonCommandeItem->save();
                    }
                }

                // CRITICAL FIX: Create reception item with proper produit_id
                StockReceptionItem::create([
                    'stock_reception_id' => $reception->id,
                    'bon_commande_item_id' => $bonCommandeItem->id,
                    'produit_id' => $produitId, // This must NOT be null for stock to update!
                    'quantite_commandee' => $bonCommandeItem->quantite_commandee,
                    'quantite_recue' => $itemData['quantite_recue'],
                    'quantite_acceptee' => $quantiteAcceptee,
                    'quantite_refusee' => $quantiteRefusee,
                    'etat_produit' => $itemData['etat_produit'],
                    'date_peremption' => $itemData['date_peremption'] ?? null,
                    'numero_lot' => $itemData['numero_lot'] ?? null,
                    'observation' => $itemData['observation'] ?? null,
                ]);

                // Update bon commande item quantities
                $bonCommandeItem->quantite_recue += $itemData['quantite_recue'];
                $bonCommandeItem->save();
            }

            // Update bon commande status if complete
            if ($reception->checkIfComplete()) {
                $bonCommande->update([
                    'statut' => 'receptionne',
                    'received_by' => Auth::id(),
                    'received_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('stock-receptions.show', $reception->id)
                ->with('success', 'Réception enregistrée avec succès! En attente de validation par le gestionnaire.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Show reception details
     */
    public function show($id)
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 5])) {
            abort(403, 'Accès non autorisé');
        }

        $reception = StockReception::with([
            'bonCommande.items', 
            'items.bonCommandeItem', 
            'items.produit',
            'receivedBy',
            'validatedBy'
        ])->findOrFail($id);

        return view('admin.stock_receptions.show', compact('reception'));
    }

    /**
     * Validate reception (Gestionnaire only)
     * This updates the actual product stock
     */
    public function validateReception(Request $request, $id)
    {
        // Only Gestionnaire and Admin can validate
        if (!in_array(Auth::user()->role_id, [1, 3])) {
            abort(403, 'Seul le gestionnaire peut valider les réceptions');
        }

        $request->validate([
            'validation_notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $reception = StockReception::with(['items.produit', 'bonCommande'])->findOrFail($id);

            if ($reception->isValidated()) {
                return back()->with('error', 'Cette réception a déjà été validée');
            }

            // Update reception
            $reception->update([
                'validated_by' => Auth::id(),
                'validated_at' => now(),
                'validation_notes' => $request->validation_notes,
            ]);

            // FIXED: Use StockService to update stock
            $transactions = StockService::processReception($reception);

            DB::commit();

            $totalItems = count($transactions);
            $message = "Réception validée avec succès! {$totalItems} produit(s) ajouté(s) au stock.";
            
            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la validation: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF
     */
    public function generatePdf($id)
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 5])) {
            abort(403, 'Accès non autorisé');
        }

        $reception = StockReception::with([
            'bonCommande',
            'items.bonCommandeItem',
            'items.produit',
            'receivedBy',
            'validatedBy'
        ])->findOrFail($id);

        $filename = "reception_{$reception->numero_reception}.pdf";

        return PdfService::generate(
            'admin.stock_receptions.pdf',
            compact('reception'),
            $filename,
            'portrait',
            'A4',
            'download'
        );
    }
}