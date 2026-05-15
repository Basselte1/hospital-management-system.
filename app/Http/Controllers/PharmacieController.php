<?php

namespace App\Http\Controllers;

use App\Models\VentePharmacie;
use App\Models\VentePharmacieItem;
use App\Models\Patient;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Ordonance;
use App\Models\StockTransaction;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PharmacieController extends Controller
{


    // Cache times
    private const CACHE_PATIENTS = 1800; // 30 minutes
    
    /**
     * Display pharmacy dashboard
     */
    public function index()
    {
        // Admin (1), Gestionnaire (3, read-only), Pharmacien (7)
        if (!in_array(Auth::user()->role_id, [1, 3, 7])) {
            abort(403, 'Accès non autorisé');
        }

        $today = now()->toDateString();
        
        // Statistics
        $stats = [
            'ventes_today' => VentePharmacie::whereDate('created_at', $today)->count(),
            'montant_today' => VentePharmacie::whereDate('created_at', $today)->sum('montant_total'),
            'ventes_en_attente' => VentePharmacie::where('statut_paiement', 'en_attente')->count(),
            'stock_alerts' => Produit::whereColumn('qte_stock', '<=', 'qte_alerte')->count(),
        ];

        // Recent sales
        $recentSales = VentePharmacie::with(['patient', 'client', 'pharmacien'])
                                     ->latest()
                                     ->take(10)
                                     ->get();

        return view('admin.pharmacie.index', compact('stats', 'recentSales'));
    }

    


    /**
     * Search patient by name, prenom, or numero_dossier
     * ORDERED BY LAST ORDONNANCE (most recent first)
     */
    public function searchPatient(Request $request)
    {
        try {
            $q = trim($request->get('q', ''));
            $page = $request->get('page', 1);
            $perPage = 50;
            
            // If searching, don't cache
            if ($q !== '') {
                // Validate minimum length
                if (strlen($q) < 2) {
                    return response()->json([
                        'error' => 'Query too short',
                        'message' => 'Veuillez entrer au moins 2 caractères'
                    ], 400);
                }
                
                $patients = Patient::select(['id', 'name', 'prenom', 'numero_dossier'])
                    ->where(function($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%")
                            ->orWhere('prenom', 'like', "%{$q}%")
                            ->orWhere('numero_dossier', 'like', "%{$q}%");
                    })
                    ->with(['ordonances' => function($query) {
                        $query->select('id', 'patient_id', 'created_at', 'description', 'medicament', 'quantite')
                              ->latest()
                              ->limit(5);
                    }])
                    ->withCount('ordonances')
                    ->get()
                    // Sort by last ordonnance date (most recent first), then by patient creation
                    ->sortByDesc(function($patient) {
                        return $patient->ordonances->first()?->created_at ?? $patient->created_at;
                    })
                    ->values()
                    ->take($perPage)
                    ->map(function($patient) {
                        $lastOrdonance = $patient->ordonances->first();
                        return [
                            'id' => $patient->id,
                            'name' => $patient->name,
                            'prenom' => $patient->prenom,
                            'numero_dossier' => $patient->numero_dossier,
                            'has_ordonnance' => $patient->ordonances_count > 0,
                            'last_ordonnance_date' => $lastOrdonance ? $lastOrdonance->created_at->format('d/m/Y H:i') : null,
                            'ordonances' => $patient->ordonances->map(function($ord) {
                                return [
                                    'id' => $ord->id,
                                    'created_at' => $ord->created_at->format('d/m/Y H:i'),
                                    'description' => $ord->description,
                                    'medicament' => $ord->medicament,
                                    'quantite' => $ord->quantite
                                ];
                            })
                        ];
                    });
                    
                Log::info('Patient search results count: ' . $patients->count());
                return response()->json($patients);
            }
            
            // Cache full list - ordered by last ordonnance or creation date
            $cacheKey = "pharmacie_patients_dropdown_ordonnance_v4_page_{$page}";
            $patients = Cache::tags(['patients'])->remember($cacheKey, self::CACHE_PATIENTS, function () use ($perPage, $page) {
                return Patient::select(['id', 'name', 'prenom', 'numero_dossier', 'created_at'])
                    ->with(['ordonances' => function($query) {
                        $query->select('id', 'patient_id', 'created_at', 'description', 'medicament', 'quantite')
                              ->latest()
                              ->limit(5);
                    }])
                    ->withCount('ordonances')
                    ->get()
                    // Sort by most recent activity (last ordonnance or patient creation)
                    ->sortByDesc(function($patient) {
                        $lastOrdDate = $patient->ordonances->first()?->created_at;
                        return $lastOrdDate ? max($lastOrdDate, $patient->created_at) : $patient->created_at;
                    })
                    ->values()
                    ->skip(($page - 1) * $perPage)
                    ->take($perPage)
                    ->map(function($patient) {
                        $lastOrdonance = $patient->ordonances->first();
                        return [
                            'id' => $patient->id,
                            'name' => $patient->name,
                            'prenom' => $patient->prenom,
                            'numero_dossier' => $patient->numero_dossier,
                            'has_ordonnance' => $patient->ordonances_count > 0,
                            'last_ordonnance_date' => $lastOrdonance ? $lastOrdonance->created_at->format('d/m/Y H:i') : null,
                            'ordonances' => $patient->ordonances->map(function($ord) {
                                return [
                                    'id' => $ord->id,
                                    'created_at' => $ord->created_at->format('d/m/Y H:i'),
                                    'description' => $ord->description,
                                    'medicament' => $ord->medicament,
                                    'quantite' => $ord->quantite
                                ];
                            })
                        ];
                    });
            });

            return response()->json($patients);
            
        } catch (\Exception $e) {
            Log::error('Patient search error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Search failed',
                'message' => 'Une erreur est survenue lors de la recherche',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    /**
     * Get ordonnance details with product availability check
     */
    public function getOrdonanceDetails($ordonanceId)
    {
        try {
            $ordonance = Ordonance::with('patient')->findOrFail($ordonanceId);
            
            $parsePipe = function(?string $value): array {
                $value = trim((string) ($value ?? ''));
                if ($value === '') return [];
                if (str_contains($value, ' | ')) {
                    return array_map('trim', explode(' | ', $value));
                }
                return [$value];
            };

            $medicaments  = $parsePipe($ordonance->getRawOriginal('medicament') ?? $ordonance->medicament);
            $quantites    = $parsePipe($ordonance->getRawOriginal('quantite')   ?? $ordonance->quantite);
            $descriptions = $parsePipe($ordonance->getRawOriginal('description') ?? $ordonance->description);
            
            $items = [];
            foreach ($medicaments as $index => $medicament) {
                $medicamentName = trim($medicament);
                $quantite = isset($quantites[$index]) ? trim($quantites[$index]) : '1';
                $description = isset($descriptions[$index]) ? trim($descriptions[$index]) : '';
                
                // Try to find matching product — no 'status' column on Produit model
                $produit = Produit::where(function($q) use ($medicamentName) {
                        $q->where('designation', 'LIKE', "%{$medicamentName}%")
                          ->orWhere('designation', 'LIKE', str_replace(' ', '%', $medicamentName));
                    })
                    ->first();
                
                $quantiteNumerique = preg_match('/\d+/', $quantite, $matches) ? (int)$matches[0] : 1;
                
                $status = 'not_found';
                $stockInfo = null;
                
                if ($produit) {
                    if ($produit->qte_stock >= $quantiteNumerique) {
                        $status = 'available';
                    } else if ($produit->qte_stock > 0) {
                        $status = 'low_stock';
                    } else {
                        $status = 'out_of_stock';
                    }
                    
                    $stockInfo = [
                        'produit_id' => $produit->id,
                        'designation' => $produit->designation,
                        'stock_available' => $produit->qte_stock,
                        'prix_unitaire' => $produit->prix_unitaire
                    ];
                }
                
                $items[] = [
                    'medicament' => $medicamentName,
                    'quantite' => $quantite,
                    'quantite_numerique' => $quantiteNumerique,
                    'description' => $description,
                    'status' => $status,
                    'stock_info' => $stockInfo
                ];
            }
            
            return response()->json([
                'success' => true,
                'ordonance' => [
                    'id' => $ordonance->id,
                    'patient' => [
                        'name' => $ordonance->patient->name,
                        'prenom' => $ordonance->patient->prenom,
                        'numero_dossier' => $ordonance->patient->numero_dossier
                    ],
                    'created_at' => $ordonance->created_at->format('d/m/Y H:i'),
                    'items' => $items
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Ordonnance details error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement de l\'ordonnance'
            ], 500);
        }
    }


    /**
     * Show create sale form for patient
     * IMPROVED VERSION
     */
    public function createPatientSale(Request $request)
    {
        try {
            // Check authorization
            if (!in_array(Auth::user()->role_id, [1, 7])) {
                abort(403, 'Accès non autorisé');
            }

            $patientId = $request->get('patient_id');
            $ordonanceId = $request->get('ordonance_id');

            $patient = null;
            $ordonance = null;
            $suggestedProducts = [];

            // Load patient if specified
            if ($patientId) {
                $patient = Patient::with(['ordonances' => function($q) {
                    $q->latest()->limit(10);
                }])->findOrFail($patientId);
                
                // Load specific ordonance if specified
                if ($ordonanceId) {
                    $ordonance = Ordonance::where('id', $ordonanceId)
                                         ->where('patient_id', $patient->id)
                                         ->firstOrFail();

                    // Use the model's own parsed_medications accessor which handles
                    // pipe-splitting correctly (including legacy records without pipes)
                    foreach ($ordonance->parsed_medications as $item) {
                        $medicamentName = trim($item['medicament']);
                        $quantiteStr    = trim($item['quantite']);
                        $description    = trim($item['description']);

                        // Extract the first integer found in the quantity string (e.g. "2 cp" → 2)
                        $suggestedQty = preg_match('/\d+/', $quantiteStr, $m) ? (int) $m[0] : 1;
                        if ($suggestedQty < 1) $suggestedQty = 1;

                        // Find matching product — fuzzy name match, no status filter
                        $product = Produit::where('designation', 'LIKE', "%{$medicamentName}%")
                                         ->first();

                        if ($product) {
                            $availableQty = max(1, min($suggestedQty, $product->qte_stock));
                            $status = 'available';
                            if ($product->qte_stock === 0) {
                                $status = 'out_of_stock';
                            } elseif ($product->qte_stock < $suggestedQty) {
                                $status = 'low_stock';
                            }

                            $suggestedProducts[] = [
                                'product'       => $product,
                                'suggested_qty' => $availableQty,
                                'prescribed_qty'=> $suggestedQty,
                                'status'        => $status,
                                'description'   => $description,
                                'medicament_name' => $medicamentName,
                            ];
                        } else {
                            // Product not in catalogue — still add a row so the
                            // pharmacist can manually pick the closest match
                            $suggestedProducts[] = [
                                'product'        => null,
                                'medicament_name'=> $medicamentName,
                                'suggested_qty'  => $suggestedQty,
                                'prescribed_qty' => $suggestedQty,
                                'status'         => 'not_found',
                                'description'    => $description,
                            ];
                        }
                    }
                }
            }

            // Get all products for the dropdown.
            // Include out-of-stock items so prescribed products always appear.
            // The blade and JS will warn the user if stock is 0.
            $produits = Produit::orderBy('designation', 'asc')->get();

            return view('admin.pharmacie.create_patient_sale', compact(
                'produits', 
                'patient', 
                'ordonance', 
                'suggestedProducts'
            ));

        } catch (\Exception $e) {
            Log::error('Create patient sale error: ' . $e->getMessage());
            return redirect()
                ->route('pharmacie.index')
                ->with('error', 'Erreur lors du chargement du formulaire: ' . $e->getMessage());
        }
    }


    /**
     * Get patients with prescriptions (for autocomplete)
     */
    public function getPatientsWithPrescriptions(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $page = $request->get('page', 1);
            $perPage = 50;
            
            $query = Patient::select('patients.id', 'patients.name', 'patients.prenom', 'patients.numero_dossier')
                ->with(['dossiers' => function($query) {
                    $query->select('id', 'patient_id', 'portable_1', 'portable_2', 'created_at')
                          ->orderBy('created_at', 'desc');
                }])
                ->join('ordonances', 'patients.id', '=', 'ordonances.patient_id')
                ->selectRaw('MAX(ordonances.created_at) as last_ordonance_date')
                ->selectRaw('MAX(ordonances.id) as last_ordonance_id')
                ->when($search, function($query) use ($search) {
                    return $query->where(function($q) use ($search) {
                        $q->where('patients.name', 'like', '%' . $search . '%')
                          ->orWhere('patients.prenom', 'like', '%' . $search . '%')
                          ->orWhere('patients.numero_dossier', 'like', '%' . $search . '%');
                    });
                })
                ->groupBy('patients.id', 'patients.name', 'patients.prenom', 'patients.numero_dossier')
                ->orderByDesc('last_ordonance_date');
            
            // Apply pagination
            $patients = $query->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get()
                ->map(function($patient) {
                    return [
                        'id' => $patient->id,
                        'name' => $patient->name,
                        'prenom' => $patient->prenom,
                        'numero_dossier' => $patient->numero_dossier,
                        'telephone' => $patient->telephone,
                        'last_ordonance_id' => $patient->last_ordonance_id,
                        'ordonance_date' => Carbon::parse($patient->last_ordonance_date)->format('d/m/Y H:i')
                    ];
                });
            
            return response()->json($patients);
            
        } catch (\Exception $e) {
            Log::error('Error getting patients with prescriptions: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed'], 500);
        }
    }

    /**
     * Get prescription details with stock verification
     */
    public function getPrescriptionDetails($ordonanceId)
    {
        try {
            $ordonance = Ordonance::with('patient')->findOrFail($ordonanceId);
            
            // Parse medications from prescription
            $medicaments = is_array($ordonance->medicament) 
                ? $ordonance->medicament 
                : explode(',', $ordonance->medicament);
                
            $descriptions = is_array($ordonance->description)
                ? $ordonance->description
                : explode(',', $ordonance->description);
                
            $quantites = is_array($ordonance->quantite)
                ? $ordonance->quantite
                : explode(',', $ordonance->quantite);
            
            $medications = [];
            
            foreach ($medicaments as $index => $medicament) {
                $medicament = trim($medicament);
                $description = trim($descriptions[$index] ?? '');
                $quantite = (int) trim($quantites[$index] ?? 1);
                
                // Search for product in database — no 'status' column on Produit model
                $produit = Produit::where('designation', 'like', '%' . $medicament . '%')
                    ->first();
                
                $medData = [
                    'medicament' => $medicament,
                    'description' => $description,
                    'quantite' => $quantite,
                    'produit_found' => false,
                    'produit_id' => null,
                    'stock_disponible' => 0,
                    'stock_insuffisant' => false,
                    'prix_unitaire' => 0
                ];
                
                if ($produit) {
                    $stockDisponible = method_exists($produit, 'getQuantiteDisponible') 
                        ? $produit->getQuantiteDisponible() 
                        : $produit->qte_stock;
                    
                    $medData['produit_found'] = true;
                    $medData['produit_id'] = $produit->id;
                    $medData['stock_disponible'] = $stockDisponible;
                    $medData['stock_insuffisant'] = $stockDisponible < $quantite;
                    $medData['prix_unitaire'] = $produit->prix_unitaire;
                }
                
                $medications[] = $medData;
            }
            
            return response()->json([
                'ordonance_id' => $ordonance->id,
                'ordonance_date' => $ordonance->created_at->format('d/m/Y'),
                'patient' => [
                    'id' => $ordonance->patient->id,
                    'name' => $ordonance->patient->name . ' ' . $ordonance->patient->prenom,
                    'numero_dossier' => $ordonance->patient->numero_dossier
                ],
                'medications' => $medications
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting prescription details: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors du chargement de l\'ordonnance'], 500);
        }
    }
    /**
     * Get patient prescriptions
     */
    // public function getPatientPrescriptions($patientId)
    // {
    //     if (!in_array(Auth::user()->role_id, [1, 7])) {
    //         abort(403);
    //     }

    //     $patient = Patient::with(['ordonances' => function($query) {
    //         $query->latest()->take(5);
    //     }])->findOrFail($patientId);

    //     return response()->json([
    //         'patient' => $patient,
    //         'ordonances' => $patient->ordonances
    //     ]);
    // }


    /**
     * Create a pharmacy sale from prescription
     */
    public function createSale(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ordonance_id' => 'required|exists:ordonances,id',
            'items' => 'required|array|min:1',
            'items.*.produit_id' => 'required|exists:produits,id',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.designation' => 'required|string',
            'items.*.prix_unitaire' => 'required|numeric|min:0'
        ]);
        
        DB::beginTransaction();
        try {
            // Create sale
            $vente = VentePharmacie::create([
                'numero_vente' => VentePharmacie::generateNumeroVente(),
                'pharmacien_id' => Auth::id(),
                'patient_id' => $request->patient_id,
                'ordonance_id' => $request->ordonance_id,
                'type_vente' => 'patient',
                'montant_total' => 0,
                'montant_paye' => 0,
                'montant_reste' => 0,
                'statut_paiement' => 'en_attente'
            ]);
            
            $totalAmount = 0;
            
            // Add items
            foreach ($request->items as $item) {
                // Verify stock availability
                $produit = Produit::find($item['produit_id']);
                if (!$produit) {
                    throw new \Exception("Produit non trouvé: " . $item['designation']);
                }
                
                $stockDisponible = $produit->getQuantiteDisponible();
                if ($stockDisponible < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour: " . $item['designation'] . " (Disponible: $stockDisponible)");
                }
                
                $montantLigne = $item['quantite'] * $item['prix_unitaire'];
                $totalAmount += $montantLigne;
                
                VentePharmacieItem::create([
                    'vente_pharmacie_id' => $vente->id,
                    'produit_id' => $item['produit_id'],
                    'designation' => $item['designation'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'],
                    'montant_ligne' => $montantLigne,
                    'stock_deducted' => false
                ]);
            }
            
            // Update total amount
            $vente->update([
                'montant_total' => $totalAmount,
                'montant_reste' => $totalAmount
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Vente créée avec succès',
                'numero_vente' => $vente->numero_vente,
                'vente_id' => $vente->id,
                'montant_total' => $totalAmount
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating pharmacy sale: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Show create sale form for external client
     */
    public function createExternalSale()
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 7])) {
            abort(403);
        }

        $produits = Produit::where('qte_stock', '>', 0)
                          ->orderBy('designation')
                          ->get();

        return view('admin.pharmacie.create_external_sale', compact('produits'));
    }

    /**
     * Store patient sale
     */
    public function storePatientSale(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) {
            abort(403);
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ordonance_id' => 'nullable|exists:ordonances,id',
            'items' => 'required|array|min:1',
            'items.*.produit_id' => 'required|exists:produits,id',
            'items.*.quantite' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Validate stock availability
            foreach ($request->items as $item) {
                $produit = Produit::findOrFail($item['produit_id']);
                if ($produit->qte_stock < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$produit->designation}. Disponible: {$produit->qte_stock}");
                }
            }

            // Create sale
            $vente = VentePharmacie::create([
                'numero_vente' => VentePharmacie::generateNumeroVente(),
                'pharmacien_id' => Auth::id(),
                'patient_id' => $request->patient_id,
                'ordonance_id' => $request->ordonance_id,
                'type_vente' => 'patient',
                'statut_paiement' => 'en_attente',
                'notes' => $request->notes
            ]);

            // Create items
            $montantTotal = 0;
            foreach ($request->items as $itemData) {
                $produit = Produit::findOrFail($itemData['produit_id']);
                
                $item = VentePharmacieItem::create([
                    'vente_pharmacie_id' => $vente->id,
                    'produit_id' => $produit->id,
                    'designation' => $produit->designation,
                    'quantite' => $itemData['quantite'],
                    'prix_unitaire' => $produit->prix_unitaire,
                    'notes' => $itemData['notes'] ?? null
                ]);

                $montantTotal += $item->montant_ligne;
            }

            // Update total
            $vente->update([
                'montant_total' => $montantTotal,
                'montant_reste' => $montantTotal
            ]);

            DB::commit();

            return redirect()
                ->route('pharmacie.sales.show', $vente->id)
                ->with('success', 'Vente créée avec succès! Facture en attente de paiement.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Store external sale
     */
    public function storeExternalSale(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 7])) {
            abort(403);
        }

        $request->validate([
            'client_nom' => 'required|string',
            'client_prenom' => 'nullable|string',
            'client_institution' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produit_id' => 'required|exists:produits,id',
            'items.*.quantite' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Validate stock
            foreach ($request->items as $item) {
                $produit = Produit::findOrFail($item['produit_id']);
                if ($produit->qte_stock < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$produit->designation}");
                }
            }

            // Create or find client
            $client = Client::create([
                'user_id' => Auth::id(),
                'nom' => $request->client_nom,
                'prenom' => $request->client_prenom,
                'motif' => $request->client_institution ?? 'Vente externe pharmacie',
                'date_insertion' => now()->toDateString()
            ]);

            // Create sale
            $vente = VentePharmacie::create([
                'numero_vente' => VentePharmacie::generateNumeroVente(),
                'pharmacien_id' => Auth::id(),
                'client_id' => $client->id,
                'type_vente' => 'client_externe',
                'statut_paiement' => 'en_attente',
                'notes' => $request->notes
            ]);

            // Create items
            $montantTotal = 0;
            foreach ($request->items as $itemData) {
                $produit = Produit::findOrFail($itemData['produit_id']);
                
                $item = VentePharmacieItem::create([
                    'vente_pharmacie_id' => $vente->id,
                    'produit_id' => $produit->id,
                    'designation' => $produit->designation,
                    'quantite' => $itemData['quantite'],
                    'prix_unitaire' => $produit->prix_unitaire,
                    'notes' => $itemData['notes'] ?? null
                ]);

                $montantTotal += $item->montant_ligne;
            }

            // Update total
            $vente->update([
                'montant_total' => $montantTotal,
                'montant_reste' => $montantTotal
            ]);

            // Update client amounts
            $client->update([
                'montant' => $montantTotal,
                'reste' => $montantTotal
            ]);

            DB::commit();

            return redirect()
                ->route('pharmacie.sales.show', $vente->id)
                ->with('success', 'Vente externe créée avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Show sale details
     */
    public function show($id)
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 6, 7, 9])) {
            abort(403);
        }

        $vente = VentePharmacie::with([
            'items.produit',
            'patient',
            'client',
            'ordonance',
            'pharmacien',
            'caissier'
        ])->findOrFail($id);

        return view('admin.pharmacie.show', compact('vente'));
    }




    /**
     * Mark sale as paid (soldée) - For Secretaire/Caissier
     */
    public function markAsSoldee(Request $request, $id)
    {
        // Secretaire (6), Admin (1), Pharmacien (7)
        if (!in_array(Auth::user()->role_id, [1, 6, 7])) {
            abort(403, 'Seule la secrétaire/caissier peut encaisser les paiements');
        }

        $request->validate([
            'mode_paiement' => 'required|string',
            'reference_paiement' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $vente = VentePharmacie::with('items.produit')->findOrFail($id);

            if ($vente->isSoldee()) {
                return back()->with('error', 'Cette vente est déjà soldée');
            }

            // Mark as paid and deduct stock
            $vente->markAsSoldee(
                Auth::id(),
                $request->mode_paiement,
                $request->reference_paiement
            );

            DB::commit();

            return redirect()
                ->route('pharmacie.sales.show', $vente->id)
                ->with('success', 'Paiement enregistré et stock mis à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }


    /**
     * Generate invoice PDF using PdfService
     */
    public function generateInvoice($id)
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 6, 7])) {
            abort(403);
        }

        $vente = VentePharmacie::with([
            'items.produit',
            'patient',
            'client',
            'pharmacien'
        ])->findOrFail($id);

        $filename = "facture_{$vente->numero_vente}.pdf";

        return PdfService::generate(
            'admin.pharmacie.invoice_pdf',
            compact('vente'),
            $filename,
            'portrait',
            'A4',
            'download'
        );
    }

    /**
     * Generate receipt PDF (after payment) using PdfService
     */
    public function generateReceipt($id)
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 6, 7])) {
            abort(403);
        }

        $vente = VentePharmacie::with([
            'items.produit',
            'patient',
            'client',
            'pharmacien',
            'caissier'
        ])->findOrFail($id);

        if (!$vente->isSoldee()) {
            return back()->with('error', 'Impossible de générer un reçu pour une vente non soldée');
        }

        $filename = "recu_{$vente->numero_vente}.pdf";

        return PdfService::generate(
            'admin.pharmacie.receipt_pdf',
            compact('vente'),
            $filename,
            'portrait',
            'A4',
            'download'
        );
    }

    /**
     * List all sales (with filters)
     */
    public function listSales(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 3, 7, 9])) {
            abort(403);
        }

        $query = VentePharmacie::with(['patient', 'client', 'pharmacien', 'caissier']);

        // Filters
        if ($request->has('statut_paiement')) {
            $query->where('statut_paiement', $request->statut_paiement);
        }

        if ($request->has('type_vente')) {
            $query->where('type_vente', $request->type_vente);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $ventes = $query->latest()->paginate(20);

        return view('admin.pharmacie.list', compact('ventes'));
    }

    /**
     * Sales history/report
     */
    public function salesHistory(Request $request)
    {
        // Pharmacien (7), Gestionnaire (3), Admin (1), Comptable (9)
        if (!in_array(Auth::user()->role_id, [1, 3, 7, 9])) {
            abort(403);
        }

        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $ventes = VentePharmacie::with(['patient', 'client', 'items', 'pharmacien'])
                                ->whereBetween('created_at', [$dateFrom, $dateTo])
                                ->latest()
                                ->get();

        $stats = [
            'total_ventes' => $ventes->count(),
            'total_montant' => $ventes->sum('montant_total'),
            'total_soldees' => $ventes->where('statut_paiement', 'soldee')->count(),
            'total_en_attente' => $ventes->where('statut_paiement', 'en_attente')->count(),
            'ventes_patients' => $ventes->where('type_vente', 'patient')->count(),
            'ventes_externes' => $ventes->where('type_vente', 'client_externe')->count(),
        ];

        return view('admin.pharmacie.history', compact('ventes', 'stats', 'dateFrom', 'dateTo'));
    }
}