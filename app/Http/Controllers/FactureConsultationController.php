<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\FactureChambre;
use App\Models\FactureDevi;
use ZanySoft\LaravelPDF\Facades\PDF;
use App\Models\FactureConsultation;
use App\Models\HistoriqueFacture;
use App\Models\Patient;
use App\Models\Produit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\PdfService;
use App\Models\DailyFacture;
use App\Models\DailyFactureLigne;
use App\Models\Examen;
use App\Models\SoinsInfirmier;

class FactureConsultationController extends Controller
{
    /**
     * Affiche la liste des factures avec pagination
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('view', User::class);
            
            $perPage = (int) $request->input('per_page', 10);
            $page = (int) $request->input('page', 1);

            // Validation des paramètres
            if ($perPage < 1 || $perPage > 200) {
                $perPage = 20;
            }

            $cacheKey = "factures.index.{$page}.{$perPage}";

            $factures = Cache::tags(['factures'])->remember($cacheKey, 60, function () use ($perPage) {
                return Facture::select(['id', 'numero', 'patient', 'prix_total', 'created_at'])
                    ->latest()
                    ->paginate($perPage);
            });

            return view('admin.factures.index', compact('factures'));

        } catch (\Exception $e) {
            return $this->handleError($e, "l'affichage des factures", 'admin.dashboard');
        }
    }


    /**
     * GET: Affiche la liste des factures de consultation (page principale)
     * POST: Traite la recherche par dates (search.date)
     */
    public function FactureConsultation(Request $request)
    {
        $this->authorize('view', User::class);

      
        if ($request->isMethod('post')) {
            $request->validate([
                'start-date' => 'required|date',
                'end-date' => 'required|date|after_or_equal:start-date'
            ]);

            // Rediriger avec les dates en query params pour éviter re-soumission
            return redirect()->route('factures.consultation', [
                'start-date' => $request->input('start-date'),
                'end-date' => $request->input('end-date'),
                'per_page' => $request->input('per_page', 20)
            ])->with('success', 'Recherche appliquée avec succès');
        }

        // GET ou après redirection POST → afficher la vue
        $startDate = $request->input('start-date') 
            ? Carbon::parse($request->input('start-date'))->startOfDay() 
            : Carbon::now()->startOfMonth();
        
        $endDate = $request->input('end-date') 
            ? Carbon::parse($request->input('end-date'))->endOfDay() 
            : Carbon::now()->endOfMonth();

        // Générer la liste des dates pour le sélecteur
        $lists = [];
        $currentDate = Carbon::now()->subMonths(3);
        while ($currentDate <= Carbon::now()) {
            $lists[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }
        $lists = array_reverse($lists);

        $perPage = (int) $request->input('per_page', 20);

        $factureConsultations = FactureConsultation::with([
                'patient:id,name,prenom,numero_dossier,prise_en_charge',
                'user:id,name'
            ])
            ->select([
                'id', 'numero', 'patient_id', 'patient_name', 'user_id', 'montant', 
                'avance', 'reste', 'statut', 'motif', 'created_at',
                'assurec', 'assurancec', 'mode_paiement', 'mode_paiement_info_sup',
                'details_motif', 'medecin_r', 'demarcheur', 'is_printed', 'printed_at',
                'devise', 'taux_conversion', 'montant_devise',
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate($perPage);

        $users = Cache::remember('medecins_list', 3600, function () {
            return User::where('role_id', 2)->select('id', 'name')->get();
        });

        return view('admin.factures.facture_consultation', compact(
            'factureConsultations', 
            'startDate', 
            'endDate',
            'users',
            'lists'
        ));
    }


     /**
     * Met à jour une facture de consultation
     * Bloque la modification si la facture est soldée
     */
    public function FactureConsultationUpdate(Request $request, $id)
    {
        // Récupérer la facture avec la relation patient
        $facture = FactureConsultation::with('patient:id,prise_en_charge')->findOrFail($id);

        $this->authorize('update',$facture);
        
        // ✅ VALIDATION CRITIQUE: Vérifier si la facture est soldée
       if ($facture->isSoldee() && $facture->is_printed) {
            return redirect()
                ->back()
                ->with('error', 'Modification interdite : La facture n°' . $facture->numero . ' est déjà soldée et imprimée.');
        }

        // Vérification des autorisations
        $this->authorize('update', $facture);

        // Validation des champs
        $request->validate([
            'mode_paiement' => 'required',
            'num_cheque' => 'required_if:mode_paiement,chèque',
            'emetteur_cheque' => 'required_if:mode_paiement,chèque',
            'banque_cheque' => 'required_if:mode_paiement,chèque',
            'emetteur_bpc' => 'required_if:mode_paiement,bon de prise en charge',
            'reste' => 'required|numeric|min:0',
            'percu' => 'required|numeric|min:0',
            'montant' => 'required|numeric|min:0',
            'devise'         => 'nullable|string|in:XAF,EUR,DOLLAR,GBP',
            'taux_conversion'=> 'nullable|numeric|min:0',
            'montant_devise' => 'nullable|numeric|min:0',
        ]);

        // Validation personnalisée: le montant perçu ne peut pas dépasser le reste
        if ($request->input('percu') > $request->input('reste')) {
            return redirect()->back()
                ->withErrors(['percu' => 'Le montant perçu ne peut pas dépasser le reste à payer.'])
                ->withInput();
        }

        // Préparer les informations supplémentaires du mode de paiement
        $modePaiementInfo = $this->prepareModePaiementInfo($request);

        try {
            DB::transaction(function () use ($facture, $request, $modePaiementInfo) {
                // Créer un enregistrement d'historique
                $historiqueFacture = new HistoriqueFacture([
                    'reste' => $facture->reste - $request->input('percu'),
                    'montant' => $facture->montant,
                    'percu' => $request->input('percu'),
                    'assurec' => $facture->assurec,
                    'mode_paiement' => $request->input('mode_paiement'),
                    'mode_paiement_info_sup' => $modePaiementInfo,
                    'devise'               => $request->input('devise', $facture->devise ?? 'XAF'),
                    'taux_conversion'      => $request->input('taux_conversion', $facture->taux_conversion),
                    'montant_devise'       => $request->input('montant_devise', $facture->montant_devise),
                ]);

                // Mettre à jour la facture
                $facture->montant = $request->input('montant');
                $facture->avance += $request->input('percu');
                $facture->mode_paiement = $request->input('mode_paiement');
                $facture->mode_paiement_info_sup = $modePaiementInfo;
                
                // Recalculer les parts assurance et patient
                $priseEnCharge = $facture->patient->prise_en_charge ?? 0;
                $facture->assurec = FactureConsultation::calculAssurec($request->input('montant'), $priseEnCharge);
                $facture->assurancec = FactureConsultation::calculAssurancec($request->input('montant'), $priseEnCharge);
                $facture->reste = FactureConsultation::calculReste($facture->assurec, $facture->avance);
                
                // Le statut sera mis à jour automatiquement par l'événement du modèle
                
                // Sauvegarder la facture - ceci déclenchera automatiquement syncPatientData()
                $facture->save();

                // Sauvegarder l'historique
                $facture->historiques()->save($historiqueFacture);
                
                // Journaliser la mise à jour pour l'audit
                Log::info('Facture consultation mise à jour', [
                    'facture_id' => $facture->id,
                    'patient_id' => $facture->patient_id,
                    'montant' => $facture->montant,
                    'avance' => $facture->avance,
                    'reste' => $facture->reste,
                    'devise' => $facture->devise,
                    'statut' => $facture->statut,
                    'updated_by' => Auth::id()
                ]);
            });

            // Vider le cache
            Cache::tags(['factures'])->flush();

            $message = $facture->isSoldee() 
                ? 'Facture soldée avec succès ! Elle peut maintenant être imprimée.'
                : 'Facture mise à jour avec succès. Reste à payer: ' . number_format($facture->reste, 0, ',', ' ') . ' FCFA';

            return redirect()
                ->route('factures.consultation')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Échec de la mise à jour de la facture', [
                'facture_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la mise à jour de la facture: ' . $e->getMessage())
                ->withInput();
        }
    }
       

    /**
     * Supprime une facture de consultation
     * Bloque la suppression si la facture est soldée
     */
    public function destroy($id)
    {
        $this->authorize('view', User::class);
        $facture = FactureConsultation::findOrFail($id);

        // VALIDATION: Empêcher la suppression des factures soldées
        if ($facture->isSoldee()) {
            return redirect()
                ->back()
                ->with('error', 'Suppression interdite : La facture n°' . $facture->numero . ' est soldée.');
        }

        try {
            DB::transaction(function () use ($facture) {
                $facture->delete();
            });

            Cache::tags(['factures'])->flush();
            
            return redirect()
                ->route('factures.Consultation')
                ->with('success', 'La facture n°' . $id . ' a été supprimée avec succès');
                
        } catch (\Exception $e) {
            Log::error('Échec de la suppression de la facture', [
                'facture_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression de la facture');
        }
    }

    /**
     * Recalcule les totaux du patient
     */
    private function recalculatePatientTotals($patientId)
    {
        try {
            $patient = Patient::find($patientId);
            
            if (!$patient) {
                Log::warning("Patient introuvable lors du recalcul des totaux", ['patient_id' => $patientId]);
                return;
            }

            $totalMontant = $patient->facture_consultations()->sum('montant');
            $totalAvance = $patient->facture_consultations()->sum('avance');
            $totalAssurancec = $patient->facture_consultations()->sum('assurancec');
            $totalAssurec = $patient->facture_consultations()->sum('assurec');
            $totalReste = $patient->facture_consultations()->sum('reste');

            $patient->update([
                'montant' => $totalMontant,
                'avance' => $totalAvance,
                'assurancec' => $totalAssurancec,
                'assurec' => $totalAssurec,
                'reste' => $totalReste,
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur lors du recalcul des totaux du patient", [
                'patient_id' => $patientId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Affiche les détails d'une facture
     */
    public function show(Facture $facture, Produit $produit)
    {
        try {
            return view('admin.factures.show', [
                'facture' => $facture
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e, "l'affichage de la facture");
        }
    }

    

  
    /**
     * Exporte le bilan journalier des consultations en PDF
     */
    public function export_bilan_consultation(Request $request)
    {
        set_time_limit(180);
        ini_set('memory_limit', '512M');

        try {
            $service = $request->input('service') === 'Tout' ? "" : $request->input('service');
            $day = $request->input('day');

            // Récupérer uniquement les historiques des factures soldées
            $factures = HistoriqueFacture::with([
                'facture_consultation:id,numero,patient_id,montant,details_motif,medecin_r,demarcheur,assurancec,assurec,statut,devise, taux_conversion, montant_devise',

                'facture_consultation.patient:id,name',
            ])
            ->where('created_at', 'LIKE', $day.'%')
            ->whereHas('facture_consultation', function ($query) use ($service) {
                $query->where('motif', 'LIKE', '%'.$service)
                    ->whereNull('deleted_at');
            })
            ->select([
                'id', 'facture_consultation_id', 'montant', 'percu',
                'reste', 'mode_paiement', 'created_at',
                'devise', 'taux_conversion', 'montant_devise'
            ])
            ->get()
            ->groupBy('facture_consultation_id');

            $totalPercu = 0;
            $totalMontant = 0;
            $totalReste = 0;
            $totalPartAssurance = 0;
            $totalPartPatient = 0;
            $tFactures = collect();
            $mode_paiement = collect();

            $caissier = trim(Auth::user()->name . " " . (Auth::user()->prenom ?? ""));
             

            foreach ($factures as $key => $historique_factures) {
                $factureData = (object)[
                    'numero' => '',
                    'name' => '',
                    'montant' => 0,
                    'percu' => 0,
                    'reste' => 0,
                    'partAssurance' => 0,
                    'partPatient' => 0,
                    'medecin' => '',
                    'demarcheur' => '',
                    'statut' => '',
                    'devise'  => 'XAF',
                    'montant_devise'=> null,
                    'taux_conversion' => null
                ];

                foreach ($historique_factures as $historique_facture) {
                    $factureData->numero = $historique_facture->facture_consultation->numero;
                    $factureData->name = optional($historique_facture->facture_consultation->patient)->name
                        ?? $historique_facture->facture_consultation->patient_name
                        ?? '[Patient supprimé]';
                    $factureData->montant = $historique_facture->facture_consultation->montant;
                    $factureData->percu += $historique_facture->percu;
                    $factureData->reste = $historique_facture->reste;
                    $factureData->partAssurance = $historique_facture->facture_consultation->assurancec ?? 0;
                    $factureData->partPatient = $historique_facture->facture_consultation->assurec ?? 0;
                    $factureData->medecin = $historique_facture->facture_consultation->medecin_r ?? '';
                    $factureData->demarcheur = $historique_facture->facture_consultation->demarcheur ?? '';
                    $factureData->statut = $historique_facture->facture_consultation->statut ?? '';
                    $factureData->details_motif = $historique_facture->facture_consultation->details_motif ?? '';
                    $factureData->heure = $historique_facture->created_at
                    ? $historique_facture->created_at->format('H:i') : '-';

                    $modePaiementKey = $this->getModePaiementKey($historique_facture->mode_paiement);

                    $existingMode = $mode_paiement->firstWhere('key', $modePaiementKey);
                    if ($existingMode) {
                        $existingMode->val += $historique_facture->percu;
                    } else {
                        $mode_paiement->push((object)[
                            'key' => $modePaiementKey,
                            'val' => $historique_facture->percu,
                            'name' => $historique_facture->mode_paiement
                        ]);
                    }

                    $totalPercu += $historique_facture->percu;
                }

                $tFactures->push($factureData);
                $totalMontant += $factureData->montant;
                $totalReste += $factureData->reste;
                $totalPartAssurance += $factureData->partAssurance;
                $totalPartPatient += $factureData->partPatient;
            }

            return PdfService::generate(
                'admin.etats.bilan_consultation',
                [
                    'mode_paiement' => $mode_paiement,
                    'service' => $service,
                    'tFactures' => $tFactures,
                    'totalPercu' => $totalPercu,
                    'totalMontant' => $totalMontant,
                    'totalReste' => $totalReste,
                    'totalPartAssurance' => $totalPartAssurance,
                    'totalPartPatient' => $totalPartPatient,
                    'date' => $day,
                    'caissier' => $caissier
                ],
                "bilan_{$day}.pdf",
                'landscape'
            );

        } catch (\Exception $e) {
            Log::error('Erreur Bilan PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la génération du bilan PDF');
        }
    }



    /**
     * Exporte une facture de consultation en PDF
     */
       public function exportConsultationPdf($id)
    {
        set_time_limit(120);
        ini_set('memory_limit', '256M');
 
        try {
            $facture = FactureConsultation::with([
             
                'patient:id,name,prenom,numero_dossier,telephone,devise,taux_conversion,montant_devise,prise_en_charge,assurance,numero_assurance',
                'user:id,name,prenom',
                'lignes' => fn($q) => $q->orderBy('ordre'),
            ])->findOrFail($id);
 
            $isProforma = $facture->isProforma();

            if (!$isProforma) {
                $facture->marquerCommeImprimee();
            }

            if (!$facture->isImprimable() && !$isProforma) {
                return redirect()
                    ->back()
                    ->with('error', 'Impression interdite. Reste à payer: ' . number_format($facture->reste, 0, ',', ' ') . ' FCFA');
            }
 
            // ── Préparer le tableau de la facture ──
            $factureArray = $facture->toArray();
 
            // Ajouter les lignes au tableau (ordonnées)
            $factureArray['lignes'] = $facture->lignes()
                ->select(['type_acte', 'libelle', 'montant', 'medecin', 'infirmiere', 'ordre'])
                ->orderBy('ordre')
                ->get()
                ->toArray();
 
         
            if (empty($factureArray['devise']) || $factureArray['devise'] === 'XAF') {
                $p = $facture->patient;
                if ($p && !empty($p->devise) && $p->devise !== 'XAF'
                    && !empty($p->taux_conversion) && !empty($p->montant_devise)
                ) {
                    $factureArray['devise']          = $p->devise;
                    $factureArray['taux_conversion'] = $p->taux_conversion;
                    $factureArray['montant_devise']  = $p->montant_devise;
 
                    Log::info('Devise récupérée depuis le patient (fallback)', [
                        'facture_id' => $facture->id,
                        'patient_id' => $p->id,
                        'devise'     => $p->devise,
                    ]);
                }
            }
 
            $patientdata        = $facture->patient->toArray();
            $patientdata['user'] = $facture->user ? $facture->user->toArray() : null;
 
            $printer = Auth::user();
       
         
 
            Log::info('export pdf facture consultation', [
                'facture_id'  => $facture->id,
                'is_proforma' => $isProforma,
                'reste'       => $facture->reste,
                'printed_by'  => Auth::id(),
            ]);
 
            // ── Générer le PDF ──
            return PdfService::generate(
                'admin.etats.consultation',
                [
                    'facture'     => $factureArray,
                    'patient'     => $patientdata,
                    'printer'     => $printer ? $printer->toArray() : null,
                    'is_proforma' => $isProforma,
                ],
                "facture_consultation_{$facture->numero}.pdf"
            );
 
        } catch (\Exception $e) {
            Log::error('Erreur de génération PDF', [
                'facture_id' => $id,
                'error'      => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
            ]);
 
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Prépare les informations supplémentaires du mode de paiement
     */
    private function prepareModePaiementInfo(Request $request): string
    {
        if ($request->input('mode_paiement') === 'chèque') {
            return collect([
                $request->input('num_cheque'),
                $request->input('emetteur_cheque'),
                $request->input('banque_cheque')
            ])->filter()->implode(' // ');
        }
        
        if ($request->input('mode_paiement') === 'bon de prise en charge') {
            return $request->input('emetteur_bpc') ?? '';
        }
        
        return '';
    }

    /**
     * Normalise le mode de paiement pour le regroupement
     */
    private function getModePaiementKey($modePaiement): string
    {
        $normalizedMap = [
            'espèce' => 'espece',
            'chèque' => 'cheque',
            'orange money' => 'om',
            'mtn mobile money' => 'momo',
            'virement' => 'virement',
            'bon de prise en charge' => 'bondepriseencharge'
        ];

        return $normalizedMap[strtolower($modePaiement)] ?? 'autre';
    }



    public function apercuConsultation( $id) 
    {
        $this->authorize('view', User::class);

        $facture = FactureConsultation::with([
            'patient:id,name,prenom,numero_dossier,telephone,prise_en_charge,assurec,assurancec',
            'user:id,name,prenom',
            'lignes' => fn($q) => $q->orderBy('ordre'),
        ])->findOrFail($id);

        $totalLignes = $facture->lignes->sum('montant');
        $isProforma = $facture->isProforma();

        return view('admin.factures.apercu_consultation',  compact('facture', 'totalLignes','isProforma'));
    }

    /**
     * Génère une facture de consultation depuis un patient.
     * Appelé automatiquement lors de la création d'un patient avec motif = Consultation.
     */
    public function generateFactureFromPatient(Patient $patient): FactureConsultation
    {
        $statutFacture = ($patient->reste ?? 0) == 0 ? 'Soldée' : 'Non soldée';

        return DB::transaction(function () use ($patient, $statutFacture) {
            $facture = FactureConsultation::create([
                'numero' => $patient->numero_dossier,
                'patient_id' => $patient->id,
                'patient_name' => trim($patient->name . ' ' . ($patient->prenom ?? '')),
                'assurancec' => $patient->assurancec ?? 0,
                'assurec' => $patient->assurec ?? 0,
                'mode_paiement' => $patient->mode_paiement ?? 'espèce',
                'mode_paiement_info_sup' => $patient->mode_paiement_info_sup ?? '',
                'motif' => $patient->motif ?? 'Consultation',
                'details_motif' => $patient->details_motif ?? '',
                'montant' => $patient->montant ?? 0,
                'demarcheur' => $patient->demarcheur,
                'avance' => $patient->avance ?? 0,
                'reste' => $patient->reste ?? 0,
                'prenom' => $patient->prenom,
                'medecin_r' => $patient->medecin_r,
                'date_insertion' => now()->toDateString(),
                'user_id' => auth()->id(),
                'statut' => $statutFacture,
            ]);

            $facture->historiques()->create([
                'reste' => $facture->reste,
                'montant' => $facture->montant,
                'percu' => $facture->avance,
                'assurec' => $facture->assurec,
                'mode_paiement' => $facture->mode_paiement,
            ]);

            return $facture;
        });
    }
  
}


