<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFactureChambreRequest;
use App\Models\FactureChambre;
use App\Models\FactureLigne;
use App\Services\FactureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * FactureChambreController
 *
 * CORRECTION :
 *   L'ancienne version importait et utilisait FactureChambreLigne,
 *   qui n'existe plus dans l'architecture unifiée (table facture_lignes).
 *   → Remplacé par FactureLigne avec facture_type = 'chambre'.
 *
 *   Le numéro de facture utilise `numero` (STRING) uniformément.
 *   Migration 2026_04_24_... a changé la colonne de INTEGER → VARCHAR(30).
 */
class FactureChambreController extends Controller
{
    public function __construct(
        private readonly FactureService $factureService
    ) {}

    public function index(Request $request): View
    {
        $factures = FactureChambre::with('patient')
            ->when($request->filled('statut'),     fn ($q) => $q->where('statut', $request->statut))
            ->when($request->filled('patient_id'), fn ($q) => $q->forPatient((int) $request->patient_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('numero', 'like', '%' . $request->search . '%')
                        ->orWhere('patient_name',  'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(20);

        return view('facturation.chambres.index', compact('factures'));
    }

    public function create(Request $request): View
    {
        return view('facturation.chambres.create', [
            'patient_id' => $request->integer('patient_id'),
        ]);
    }

    public function store(StoreFactureChambreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {

            // Calcul du montant total depuis les lignes soumises
            $montantTotal = collect($data['lignes'] ?? [])
                ->sum(fn ($l) => (float) $l['montant'] * (int) ($l['quantite'] ?? 1));

            $montants = $this->factureService->preparerMontants(
                montantTotal:  $montantTotal,
                priseEnCharge: (float) ($data['prise_en_charge'] ?? 0),
                avance:        (float) ($data['avance'] ?? 0),
            );

            // Exclure 'lignes' du tableau avant de créer la facture
            $donneesFacure = array_merge(
                array_diff_key($data, ['lignes' => null]),
                $montants,
                // Numéro de facture alphanumérique (STRING, ex: CHB-2026-000001)
                ['numero' => $this->factureService->genererNumero('CHB', 'facture_chambres')]
            );

            $facture = FactureChambre::create($donneesFacure);

            // CORRIGÉ : FactureLigne (table unifiée) au lieu de FactureChambreLigne
            foreach ($data['lignes'] ?? [] as $index => $ligne) {
                FactureLigne::create([
                    'facture_chambre_id' => $facture->id,
                    'facture_type'       => 'chambre',        // discriminant obligatoire
                    'type_sous'          => $ligne['type_ligne'],
                    'libelle'            => $ligne['libelle'],
                    'montant'            => $ligne['montant'],
                    'quantite'           => $ligne['quantite'] ?? 1,
                    'date_acte'          => $ligne['date_soin'] ?? null,
                    'reference_id'       => $ligne['reference_id'] ?? null,
                    'reference_type'     => $ligne['reference_type'] ?? null,
                    'ordre'              => $index + 1,
                ]);
            }

            // Recalcul des sous-totaux hébergement / soins / traitements
            $facture->recalculerMontants();
        });

        return redirect()->route('facturation.chambres.index')
                         ->with('success', 'Facture séjour créée avec succès.');
    }

    /* public function FactureChambre(Patient $patient)
    {
        $this->authorize('view', User::class);

        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $start_date = "01-" . $month . "-" . $year;
        $start_time = strtotime($start_date);
        $end_time = strtotime("+1 month", $start_time);

        $lists = [];
        for ($i = $start_time; $i < $end_time; $i += 86400) {
            $lists[] = date('Y-m-d', $i);
        }

        $factureChambres = FactureChambre::with('patient')->get();

        return view('admin.factures.chambre', compact('factureChambres', 'lists'));
    }*/


    public function show(FactureChambre $factureChambre): View
    {
        $factureChambre->load(['patient', 'lignes', 'chambre', 'user']);

        return view('facturation.chambres.show', ['facture' => $factureChambre]);
    }

    public function enregistrerPaiement(Request $request, FactureChambre $factureChambre): RedirectResponse
    {
        $validated = $request->validate([
            'montant'                => 'required|numeric|min:1|max:' . $factureChambre->reste,
            'mode_paiement'          => 'required|string|max:50',
            'mode_paiement_info_sup' => 'nullable|string|max:255',
            'reference'              => 'nullable|string|max:100',
        ]);

        $this->factureService->enregistrerPaiement(
            facture:      $factureChambre,
            factureType:  'chambre',
            montant:      (float) $validated['montant'],
            modePaiement: $validated['mode_paiement'],
            extra: [
                'mode_paiement_info_sup' => $validated['mode_paiement_info_sup'] ?? null,
                'reference'              => $validated['reference'] ?? null,
            ],
        );

        return back()->with('success', 'Paiement enregistré.');
    }

    public function imprimer(FactureChambre $factureChambre): View|RedirectResponse
    {
        if (! $factureChambre->isImprimable()) {
            return back()->with('error', 'La facture doit être soldée avant impression.');
        }

        $factureChambre->marquerCommeImprimee();
        $factureChambre->load(['patient', 'lignes', 'chambre', 'user']);

        return view('facturation.chambres.print', ['facture' => $factureChambre]);
    }
}