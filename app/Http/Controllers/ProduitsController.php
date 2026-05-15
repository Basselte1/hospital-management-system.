<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Facture;
use App\Http\Requests\ProduitRequest;
use App\Models\Patient;
use App\Models\Produit;
use App\Models\ProduitEditRequest;
use App\Models\ProduitAuditLog;
use ZanySoft\LaravelPDF\Facades\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\PdfService;

class ProduitsController extends Controller
{
    /**
     * Display products list with optional category filter
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Produit::class);

        $query = Produit::query();

        // Category filter
        $category = $request->get('categorie');
        if ($category) {
            $query->where('categorie', $category);
        }

        // Reusable filter
        if ($request->has('reusable')) {
            $query->where('is_reusable', $request->get('reusable') === '1');
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where('designation', 'LIKE', '%' . $request->search . '%');
        }

        $produits = $query->orderBy('designation')->paginate(20);
        
        $produitCount = $query->count();
        
        $categoryCounts = [
            'PHARMACEUTIQUE' => Produit::where('categorie', 'PHARMACEUTIQUE')->count(),
            'MATERIEL' => Produit::where('categorie', 'MATERIEL')->count(),
            'ANESTHESISTE' => Produit::where('categorie', 'ANESTHESISTE')->count(),
        ];

        return view('admin.produits.index', compact('produits', 'produitCount', 'category', 'categoryCounts'));
    }


    /**
     * Toggle product reusable status
     * Accessible by Pharmacien (7), Logistique (5), Admin (1)
     */
    public function toggleReusable(Request $request, $id)
    {
        // Only Pharmacien, Logistique, and Admin can toggle
        if (!in_array(Auth::user()->role_id, [1, 5, 7])) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $produit = Produit::findOrFail($id);
            
            // Get current status (cast to boolean to ensure consistency)
            $oldStatus = (bool)$produit->is_reusable;
            $newStatus = !$oldStatus;
            
            // Update the reusable status
            $produit->is_reusable = $newStatus;
            
            // If marking as reusable, initialize tracking fields
            if ($newStatus) {
                // Initialize counters if null
                if (is_null($produit->qte_en_utilisation)) {
                    $produit->qte_en_utilisation = 0;
                }
                if (is_null($produit->qte_en_sterilisation)) {
                    $produit->qte_en_sterilisation = 0;
                }
                
                // Set default sterilization parameters based on category
                if ($produit->categorie === 'MATERIEL' && empty($produit->methode_sterilisation_recommandee)) {
                    $produit->methode_sterilisation_recommandee = 'autoclave';
                    $produit->duree_sterilisation_recommandee = 20;
                    $produit->temperature_sterilisation = 121;
                }
            }
            
            // Force timestamps update
            $produit->updated_at = now();
            
            // Save and verify
            $saved = $produit->save();
            
            if (!$saved) {
                throw new \Exception('Échec de la sauvegarde du produit');
            }
            
            // Verify the save was successful by refreshing from database
            $produit->refresh();
            
            if ($produit->is_reusable !== $newStatus) {
                throw new \Exception('La modification n\'a pas été persistée correctement');
            }
            
            // Log the action
            ProduitAuditLog::logAction(
                $produit->id,
                'updated',
                $newStatus 
                    ? "Produit '{$produit->designation}' marqué comme réutilisable par " . Auth::user()->name
                    : "Produit '{$produit->designation}' retiré de la liste réutilisable par " . Auth::user()->name,
                ['is_reusable' => $oldStatus],
                ['is_reusable' => $newStatus]
            );
            
            DB::commit();
            
            $message = $newStatus 
                ? "Produit marqué comme réutilisable avec succès!" 
                : "Produit retiré de la liste réutilisable avec succès!";
            
            // Log for debugging
            Log::info("Product {$id} reusable status changed", [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'user_id' => Auth::id(),
                'verified' => true
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_reusable' => $newStatus
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Failed to toggle reusable status for product {$id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }



     /**
     * Show form to edit reusable product settings
     */
    public function editReusableSettings($id)
    {
        // Only Pharmacien, Logistique, and Admin can access
        if (!in_array(Auth::user()->role_id, [1, 5, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $produit = Produit::findOrFail($id);
        
        if (!$produit->is_reusable) {
            return redirect()
                ->back()
                ->with('error', 'Ce produit n\'est pas marqué comme réutilisable');
        }

        return view('admin.produits.edit_reusable_settings', compact('produit'));
    }

    /**
     * Update reusable product settings
     */
    public function updateReusableSettings(Request $request, $id)
    {
        // Only Pharmacien, Logistique, and Admin can update
        if (!in_array(Auth::user()->role_id, [1, 5, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'nombre_utilisations_max' => 'nullable|integer|min:1',
            'notes_utilisation' => 'nullable|string',
            'methode_sterilisation_recommandee' => 'required|in:autoclave,chaleur_seche,gaz_eto,plasma,chimique,autre',
            'duree_sterilisation_recommandee' => 'nullable|integer|min:1',
            'temperature_sterilisation' => 'nullable|integer|min:50|max:200',
        ]);

        DB::beginTransaction();
        try {
            $produit = Produit::findOrFail($id);
            
            if (!$produit->is_reusable) {
                return redirect()
                    ->back()
                    ->with('error', 'Ce produit n\'est pas marqué comme réutilisable');
            }

            $oldValues = [
                'nombre_utilisations_max' => $produit->nombre_utilisations_max,
                'methode_sterilisation_recommandee' => $produit->methode_sterilisation_recommandee,
                'duree_sterilisation_recommandee' => $produit->duree_sterilisation_recommandee,
                'temperature_sterilisation' => $produit->temperature_sterilisation,
            ];

            $produit->update([
                'nombre_utilisations_max' => $request->nombre_utilisations_max,
                'notes_utilisation' => $request->notes_utilisation,
                'methode_sterilisation_recommandee' => $request->methode_sterilisation_recommandee,
                'duree_sterilisation_recommandee' => $request->duree_sterilisation_recommandee,
                'temperature_sterilisation' => $request->temperature_sterilisation,
            ]);

            $newValues = [
                'nombre_utilisations_max' => $produit->nombre_utilisations_max,
                'methode_sterilisation_recommandee' => $produit->methode_sterilisation_recommandee,
                'duree_sterilisation_recommandee' => $produit->duree_sterilisation_recommandee,
                'temperature_sterilisation' => $produit->temperature_sterilisation,
            ];

            // Log the action
            ProduitAuditLog::logAction(
                $produit->id,
                'updated',
                "Paramètres de réutilisation mis à jour pour '{$produit->designation}' par " . Auth::user()->name,
                $oldValues,
                $newValues
            );

            DB::commit();

            return redirect()
                ->route('produits.index')
                ->with('success', 'Paramètres de réutilisation mis à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * View list of reusable products
     */
    public function reusableList()
    {
        // Only Pharmacien, Logistique, Admin, Infirmière can view
        if (!in_array(Auth::user()->role_id, [1, 4, 5, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $reusableProducts = Produit::where('is_reusable', true)
                                   ->orderBy('designation')
                                   ->paginate(50);

        $stats = [
            'total' => $reusableProducts->total(),
            'en_utilisation' => Produit::where('is_reusable', true)->sum('qte_en_utilisation'),
            'en_sterilisation' => Produit::where('is_reusable', true)->sum('qte_en_sterilisation'),
            'disponible' => Produit::where('is_reusable', true)->get()->sum(function($p) {
                return $p->qte_stock - $p->qte_en_utilisation - $p->qte_en_sterilisation;
            })
        ];

        return view('admin.produits.reusable_list', compact('reusableProducts', 'stats'));
    }








    public function create()
    {
        $this->authorize('create', Produit::class);
        return view('admin.produits.create');
    }

    /**
     * Store new product - AUTO-APPROVED
     * No approval needed for product creation
     */
    public function store(Request $request)
    {
        $this->authorize('create', Produit::class);

        $request->validate([
            'designation' => ['required', 'unique:produits'],
            'categorie' => 'required',
            'qte_alerte' => 'required|integer|min:0',
            'qte_stock' => 'required|integer|min:0',
            'prix_unitaire' => 'required|integer|min:0'
        ]);

        DB::transaction(function () use ($request) {
            Produit::create([
                'designation' => $request->get('designation'),
                'categorie' => $request->get('categorie'),
                'qte_stock' => $request->get('qte_stock'),
                'qte_alerte' => $request->get('qte_alerte'),
                'prix_unitaire' => $request->get('prix_unitaire'),
                'user_id' => Auth::id(),
            ]);
       
            // Clear caches
            Cache::forget('produits_page_1');
            Cache::forget('produits_count_all');
        });

        return redirect()->route('produits.index')
            ->with('success', 'Le produit a été ajouté avec succès !');
    }

    /**
     * Show edit form or request edit permission
     */
    public function edit(Produit $produit)
    {
        $this->authorize('update', $produit);
        
        // Check if user has an active edit permission for this product
        $activeEditPermission = ProduitEditRequest::where('produit_id', $produit->id)
            ->where('requested_by', auth()->id())
            ->where('status', 'approved')
            ->where('can_edit', true)
            ->first();
        
        // Admin/Gestionnaire can edit directly, others need permission
        $canEditDirectly = in_array(auth()->user()->role_id, [1, 3]) || $activeEditPermission;
        
        return view('admin.produits.edit', compact('produit', 'canEditDirectly', 'activeEditPermission'));
    }

    /**
     * Update product
     * Admin/Gestionnaire: Updates directly
     * Logistique/Pharmacien: Requires approved edit permission
     */
    public function update(ProduitRequest $request, Produit $produit)
    {
        $this->authorize('update', $produit);

        // Admin & Gestionnaire can update directly
        if (in_array(auth()->user()->role_id, [1, 3])) {
            DB::transaction(function () use ($request, $produit) {
                $produit->update(array_merge(
                    $request->validated(),
                    ['user_id' => Auth::id()]
                ));

                Cache::forget('produits_page_1');
                Cache::forget('produits_count_all');
                Cache::forget('produits_count_' . $produit->categorie);
            });

            return redirect()->route('produits.index')
                ->with('success', 'Le produit a été mis à jour avec succès !');
        }

        // Logistique & Pharmacien need approved edit permission
        $activeEditPermission = ProduitEditRequest::where('produit_id', $produit->id)
            ->where('requested_by', auth()->id())
            ->where('status', 'approved')
            ->where('can_edit', true)
            ->first();

        if (!$activeEditPermission) {
            return redirect()->route('produits.index')
                ->with('error', 'Vous devez d\'abord obtenir la permission de modifier ce produit.');
        }

        // User has permission, perform the update
        DB::transaction(function () use ($request, $produit, $activeEditPermission) {
            // Store old values for audit
            $oldValues = $produit->only(['designation', 'categorie', 'qte_stock', 'qte_alerte', 'prix_unitaire']);
            
            $produit->update(array_merge(
                $request->validated(),
                ['user_id' => Auth::id()]
            ));

            // Log the modification
            ProduitAuditLog::logAction(
                $produit->id,
                'updated',
                "Produit modifié par " . auth()->user()->name . " avec permission #" . $activeEditPermission->id,
                $oldValues,
                $request->validated()
            );

            Cache::forget('produits_page_1');
            Cache::forget('produits_count_all');
            Cache::forget('produits_count_' . $produit->categorie);
        });

        return redirect()->route('produits.index')
            ->with('success', 'Le produit a été mis à jour avec succès !');
    }

    /**
     * Request permission to edit a product
     */
    public function requestEditPermission(Request $request, Produit $produit)
    {
        $this->authorize('update', $produit);

        // Check if user already has a pending or approved request
        $existingRequest = ProduitEditRequest::where('produit_id', $produit->id)
            ->where('requested_by', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            if ($existingRequest->status === 'pending') {
                return redirect()->back()
                    ->with('info', 'Vous avez déjà une demande de permission en attente pour ce produit.');
            } else {
                return redirect()->back()
                    ->with('info', 'Vous avez déjà la permission de modifier ce produit.');
            }
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        DB::transaction(function () use ($request, $produit) {
            ProduitEditRequest::create([
                'produit_id' => $produit->id,
                'requested_by' => auth()->id(),
                'reason' => $request->reason,
                'status' => 'pending',
                'can_edit' => false,
            ]);
        });

        return redirect()->route('produits.index')
            ->with('success', 'Votre demande de permission a été soumise. En attente d\'approbation.');
    }

    /**
     * Show pending edit permission requests (for Admin/Gestionnaire)
     */
    public function pendingEditPermissions()
    {
        $this->authorize('approveEditRequests', Produit::class);

        $editRequests = ProduitEditRequest::where('status', 'pending')
            ->with(['produit', 'requestedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.produits.edit_permissions_pending', compact('editRequests'));
    }

    /**
     * Approve edit permission request
     */
    public function approveEditPermission(ProduitEditRequest $editRequest)
    {
        $this->authorize('approveEditRequests', Produit::class);

        DB::transaction(function () use ($editRequest) {
            $editRequest->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'can_edit' => true,
            ]);

            // Log the approval
            ProduitAuditLog::logAction(
                $editRequest->produit_id,
                'edit_permission_granted',
                "Permission de modification accordée à " . $editRequest->requestedBy->name . " par " . auth()->user()->name,
                null,
                ['permission_id' => $editRequest->id]
            );
        });

        return redirect()->back()
            ->with('success', 'Permission de modification accordée avec succès !');
    }

    /**
     * Reject edit permission request
     */
    public function rejectEditPermission(Request $request, ProduitEditRequest $editRequest)
    {
        $this->authorize('approveEditRequests', Produit::class);

        $request->validate([
            'review_comment' => 'required|string|max:500'
        ]);

        DB::transaction(function () use ($request, $editRequest) {
            $editRequest->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_comment' => $request->review_comment,
                'can_edit' => false,
            ]);

            // Log the rejection
            ProduitAuditLog::logAction(
                $editRequest->produit_id,
                'edit_permission_denied',
                "Permission de modification refusée à " . $editRequest->requestedBy->name . " par " . auth()->user()->name . ": " . $request->review_comment,
                null,
                ['permission_id' => $editRequest->id]
            );
        });

        return redirect()->back()
            ->with('success', 'Permission de modification refusée.');
    }

    /**
     * Revoke edit permission
     */
    public function revokeEditPermission(ProduitEditRequest $editRequest)
    {
        $this->authorize('approveEditRequests', Produit::class);

        DB::transaction(function () use ($editRequest) {
            $editRequest->update([
                'can_edit' => false,
                'revoked_at' => now(),
                'revoked_by' => auth()->id(),
            ]);

            // Log the revocation
            ProduitAuditLog::logAction(
                $editRequest->produit_id,
                'edit_permission_revoked',
                "Permission de modification révoquée pour " . $editRequest->requestedBy->name . " par " . auth()->user()->name,
                null,
                ['permission_id' => $editRequest->id]
            );
        });

        return redirect()->back()
            ->with('success', 'Permission révoquée avec succès.');
    }

    /**
     * Batch approve edit permissions
     */
    public function batchApproveEditPermissions(Request $request)
    {
        $this->authorize('approveEditRequests', Produit::class);

        $count = 0;
        
        DB::transaction(function () use (&$count) {
            $pendingRequests = ProduitEditRequest::where('status', 'pending')->get();
            
            foreach ($pendingRequests as $editRequest) {
                $editRequest->update([
                    'status' => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'can_edit' => true,
                ]);

                // Log each approval
                ProduitAuditLog::logAction(
                    $editRequest->produit_id,
                    'edit_permission_granted',
                    "Permission de modification accordée (approbation groupée) à " . $editRequest->requestedBy->name . " par " . auth()->user()->name,
                    null,
                    ['permission_id' => $editRequest->id, 'batch' => true]
                );

                $count++;
            }
        });

        return redirect()->back()
            ->with('success', "{$count} permission(s) accordée(s) avec succès !");
    }

    /**
     * Batch reject edit permissions
     */
    public function batchRejectEditPermissions(Request $request)
    {
        $this->authorize('approveEditRequests', Produit::class);

        $request->validate([
            'batch_comment' => 'required|string|max:500'
        ]);

        $count = 0;
        
        DB::transaction(function () use ($request, &$count) {
            $pendingRequests = ProduitEditRequest::where('status', 'pending')->get();
            
            foreach ($pendingRequests as $editRequest) {
                $editRequest->update([
                    'status' => 'rejected',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'review_comment' => $request->batch_comment,
                    'can_edit' => false,
                ]);

                // Log each rejection
                ProduitAuditLog::logAction(
                    $editRequest->produit_id,
                    'edit_permission_denied',
                    "Permission de modification refusée (rejet groupé) à " . $editRequest->requestedBy->name . " par " . auth()->user()->name . ": " . $request->batch_comment,
                    null,
                    ['permission_id' => $editRequest->id, 'batch' => true]
                );

                $count++;
            }
        });

        return redirect()->back()
            ->with('success', "{$count} permission(s) refusée(s).");
    }

    /**
     * View edit permissions history
     */
    public function editPermissionsHistory()
    {
        $this->authorize('approveEditRequests', Produit::class);

        $editRequests = ProduitEditRequest::whereIn('status', ['approved', 'rejected'])
            ->with(['produit', 'requestedBy', 'reviewedBy'])
            ->orderBy('reviewed_at', 'desc')
            ->paginate(20);

        return view('admin.produits.edit_permissions_history', compact('editRequests'));
    }

    /**
     * View my edit permissions (for users to see their own permissions)
     */
    public function myEditPermissions()
    {
        $editRequests = ProduitEditRequest::where('requested_by', auth()->id())
            ->with(['produit', 'reviewedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.produits.my_edit_permissions', compact('editRequests'));
    }

    public function destroy(Produit $produit)
    {
        $this->authorize('delete', $produit);

        DB::transaction(function () use ($produit) {
            $produit->delete();
            Cache::forget('produit_page_1');
            Cache::forget('produits_count_all');
            Cache::forget('produits_count_' . $produit->categorie);
        });

        return redirect()->route('produits.index')
            ->with('success', 'Le produit a bien été supprimé');
    }

    /**
     * Stock verification (for Comptable)
     */
    public function stockVerification()
    {
        $this->authorize('verifyStock', Produit::class);

        $recentProduits = Produit::with('createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.produits.stock_verification', compact('recentProduits'));
    }

    /**
     * Audit logs (for Admin)
     */
    public function auditLogs(Request $request)
    {
        $this->authorize('viewAuditLogs', Produit::class);

        $query = ProduitAuditLog::with(['produit', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $auditLogs = $query->paginate(50);

        // Get unique actions for filter dropdown
        $actions = ProduitAuditLog::select('action')
            ->distinct()
            ->pluck('action')
            ->toArray();

        return view('admin.produits.audit_logs', compact('auditLogs', 'actions'));
    }

    /**
     * View audit logs for a specific product
     */
    public function productAuditLogs(Produit $produit)
    {
        $this->authorize('viewAuditLogs', Produit::class);

        $auditLogs = ProduitAuditLog::where('produit_id', $produit->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.produits.product_audit_logs', compact('produit', 'auditLogs'));
    }

    // ==================== CART & BILLING METHODS ====================
    // (Keep all existing cart and billing methods unchanged)

    public function pharmaceutique()
    {
        $this->authorize('viewAny', Produit::class);
        
        $produits = Produit::where('categorie', 'PHARMACEUTIQUE')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.produits.pharmaceutique', compact('produits'));
    }

    public function materiel()
    {
        $this->authorize('viewAny', Produit::class);
        
        $produits = Produit::where('categorie', 'MATERIEL')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.produits.materiel', compact('produits'));
    }

    public function anesthesiste()
    {
        $this->authorize('anesthesiste', Produit::class);
        
        $produits = Produit::where('categorie', 'ANESTHESISTE')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.produits.anesthesiste', compact('produits'));
    }

    public function getAddToCart(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);

        if ($produit->qte_stock < 1) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuffisant pour ce produit'
                ], 400);
            }
            
            flash()->error('Stock insuffisant pour ce produit');
            return redirect()->back();
        }

        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($produit, $produit->id);

        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'items' => $cart->items,
                'totalPrix' => $cart->totalPrix,
                'totalQte' => $cart->totalQte
            ]);
        }

        flash()->success('Le produit a bien été ajouté à la facture');
        return redirect()->back();
    }

    public function getAddItem(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        if (isset($cart->items[$id]) && $cart->items[$id]['qty'] >= $produit->qte_stock) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuffisant'
                ], 400);
            }
            
            flash()->error('Stock insuffisant');
            return redirect()->back();
        }

        $cart->add($produit, $produit->id);

        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'items' => $cart->items,
                'totalPrix' => $cart->totalPrix,
                'totalQte' => $cart->totalQte
            ]);
        }

        flash()->success("La facture vient d'être mise à jour");
        return redirect()->route('pharmaceutique.facturation');
    }

    public function facturation()
    {
        if(!Session::has('cart')){
            return view('admin.produits.facturation');
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $produit = Produit::whereIn('id', array_keys($cart->items))->get();
        $patient = Patient::all();

        return view('admin.produits.facturation',
            [
                'produit' => $produit,
                'produits' => $cart->items,
                'totalPrix' => $cart->totalPrix,
                'patient' => $patient
            ]);
    }

    public function getReduceByOne(Request $request, $id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->reduceByOne($id);

        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'items' => $cart->items,
                'totalPrix' => $cart->totalPrix,
                'totalQte' => $cart->totalQte
            ]);
        }

        flash()->success("La facture vient d'être mise à jour");
        return redirect()->route('pharmaceutique.facturation');
    }

    public function getRemoveItem(Request $request, $id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);

        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'items' => $cart->items,
                    'totalPrix' => $cart->totalPrix,
                    'totalQte' => $cart->totalQte
                ]);
            }
        } else {
            Session::forget('cart');
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'items' => [],
                    'totalPrix' => 0,
                    'totalQte' => 0,
                    'cartEmpty' => true
                ]);
            }
        }

        flash()->info("Le produit a bien été supprimé de la facture");
        return redirect()->route('pharmaceutique.facturation');
    }

    public function export_pdf(Request $request, Produit $produit, Patient $patient)
    {
        set_time_limit(120);
        ini_set('memory_limit', '256M');

        try {
            if (!Session::has('cart')) {
                return redirect()->route('pharmaceutique.facturation')
                    ->with('error', 'Votre panier est vide');
            }

            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);

            $patientName = $request->input('patient');

            $facture = DB::transaction(function () use ($cart, $patientName) {
                $facture = Facture::create([
                    'numero' => mt_rand(10000, 999999),
                    'quantite_total' => $cart->totalQte,
                    'prix_total' => $cart->totalPrix,
                    'patient' => $patientName,
                    'user_id' => auth()->user()->id,
                ]);

                $facture->produits()->attach($cart->items);

                return $facture;
            });

            $produits = collect();
            foreach ($cart->items as $item) {
                $produits->push((object)[
                    'designation' => $item['item']['designation'] ?? 'N/A',
                    'prix_unitaire' => $item['item']['prix_unitaire'] ?? 0,
                    'qty' => $item['qty'] ?? 0,
                    'price' => $item['price'] ?? 0,
                ]);
            }

            if (ob_get_length()) {
                ob_end_clean();
            }

            $orientation = request()->input('orientation', 'portrait');
            $format = request()->input('format', 'A4');
            $delivery = request()->input('delivery', 'stream');

            $filename = 'pharmacie_' . $facture->numero . '.pdf';

            Session::forget('cart');

            return PdfService::generate('admin.etats.pharmacie', [
                'patient' => $patientName,
                'produits' => $produits,
                'totalPrix' => $cart->totalPrix,
                'totalQte' => $cart->totalQte,
                'facture' => $facture,
            ], $filename, $orientation, $format, $delivery);

        } catch (\Exception $e) {
            Log::error('Pharmacie PDF Error: ' . $e->getMessage());

            if (ob_get_length()) {
                ob_end_clean();
            }

            return redirect()->back()->with('error', 'Erreur lors de la génération de la facture pharmacie');
        }
    }

    /**
     * Return products as JSON for autocomplete
     */
    public function searchJson(Request $request)
    {
        $search = $request->get('search', '');
        
        $produits = Produit::select('id', 'designation', 'qte_stock', 'prix_unitaire', 'categorie')
            ->where('qte_stock', '>', 0)
            ->when($search, function($query) use ($search) {
                return $query->where('designation', 'like', '%' . $search . '%');
            })
            ->orderBy('designation', 'asc')
            ->limit(50)
            ->get()
            ->map(function($produit) {
                return [
                    'id' => $produit->id,
                    'designation' => $produit->designation,
                    'stock' => $produit->qte_stock,
                    'prix' => $produit->prix_unitaire,
                    'categorie' => $produit->categorie
                ];
            });
        
        return response()->json($produits);
    }
    
    /**
     * Search products by designation (for autocomplete)
     */
    public function autocomplete(Request $request)
    {
        $term = $request->get('term', '');
        
        $produits = Produit::select('id', 'designation')
            ->where('designation', 'like', '%' . $term . '%')
            ->orderBy('designation', 'asc')
            ->limit(20)
            ->get()
            ->map(function($produit) {
                return [
                    'id' => $produit->id,
                    'value' => $produit->designation,
                    'label' => $produit->designation
                ];
            });
        
        return response()->json($produits);
    }
}