<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Patient;
use App\Models\FactureConsultation;
use App\Models\FactureExamen;
use App\Models\FactureActe;
use App\Models\FactureLigne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\HistoriqueFacture;
use App\Http\Controllers\Pdf;


/**
 * FactureFinaleController
 *
 * Rôle : Agréger TOUTES les factures d'un patient (consultation + examen + acte)
 *        sur une période, pour produire une vue "facture finale" consolidée.
 *
 * POURQUOI CE CONTROLLER ET PAS LE PRÉCÉDENT ?
 *   L'ancien controller envoyait $patients à la vue qui attendait $facturesGroupees.
 *   La logique de groupement n'était pas faite → rien ne s'affichait.
 *   Ce controller corrige : il construit $facturesGroupees correctement.
 *
 * 4 méthodes :
 *   - index()               → liste des patients avec leurs totaux agrégés
 *   - apercuFactureFinale() → aperçu HTML avant impression (1 patient)
 *   - exportPdf()           → PDF facture finale (1 patient)
 *   - exportBilanPdf()      → PDF bilan journalier (tous patients du jour)
 * 
 */
class FactureFinaleController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════════
    //  1. INDEX — Liste des patients avec leurs totaux agrégés
    // ══════════════════════════════════════════════════════════════════════════

    public function index(Request $request): View
    {
        // ── Période de filtrage ───────────────────────────────────────────────
        $startDate = $request->filled('start-date')
            ? Carbon::parse($request->input('start-date'))->startOfDay()
            : Carbon::now()->startOfDay();

        $endDate = $request->filled('end-date')
            ? Carbon::parse($request->input('end-date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $statutFilter = $request->input('statut', '');

        // ── Patients ayant au moins une facture sur la période ────────────────
        // On utilise orWhereHas pour couvrir tous les types de factures.
        $query = Patient::query()
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereHas('facture_consultations', fn($sq) =>
                        $sq->whereBetween('created_at', [$startDate, $endDate]))
                  ->orWhereHas('facture_examens', fn($sq) =>
                        $sq->whereBetween('created_at', [$startDate, $endDate]))
                  ->orWhereHas('facture_actes', fn($sq) =>
                        $sq->whereBetween('created_at', [$startDate, $endDate]));
            })
            ->with([
                'facture_consultations' => fn($q) =>
                    $q->whereBetween('created_at', [$startDate, $endDate]),
                'facture_examens' => fn($q) =>
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->with('lignes'),
                'facture_actes' => fn($q) =>
                    $q->whereBetween('created_at', [$startDate, $endDate]),
            ]);

        // Récupérer tous (pas de pagination ici car on group en PHP)
        $patients = $query->get();

        // ── Construction de $facturesGroupees ────────────────────────────────
        // Pour chaque patient, on agrège les montants de tous ses types de facture.
        $facturesGroupees = $patients->map(function (Patient $patient) {
            // Totaux par type
            $consultations = (float) $patient->facture_consultations->sum('montant');
            $examens       = (float) $patient->facture_examens->sum('montant_total');
            $soins         = (float) $patient->facture_actes->sum('montant_total');
            $pharmacie     = 0; // module à venir
            $autres        = 0;

            $total      = $consultations + $examens + $soins + $pharmacie + $autres;
            $assurancec = (float) ($patient->facture_consultations->sum('assurancec')
                                 + $patient->facture_examens->sum('assurancec')
                                 + $patient->facture_actes->sum('assurancec'));
            $assurec    = (float) ($patient->facture_consultations->sum('assurec')
                                 + $patient->facture_examens->sum('assurec')
                                 + $patient->facture_actes->sum('assurec'));
            $avance     = (float) ($patient->facture_consultations->sum('avance')
                                 + $patient->facture_examens->sum('avance')
                                 + $patient->facture_actes->sum('avance'));
            $reste      = (float) ($patient->facture_consultations->sum('reste')
                                 + $patient->facture_examens->sum('reste')
                                 + $patient->facture_actes->sum('reste'));

            return [
                'patient'       => $patient,
                'consultations' => $consultations,
                'examens'       => $examens,
                'soins'         => $soins,
                'pharmacie'     => $pharmacie,
                'autres'        => $autres,
                'total'         => $total,
                'assurancec'    => $assurancec,
                'assurec'       => $assurec,
                'avance'        => $avance,
                'reste'         => $reste,
                'is_solde'      => $reste == 0,
                'lignes_examens'=> $patient->facture_examens
                    ->flatmap(fn($fe) => $fe->lignes ?? collect())->values(),
               
            ];
        });

        // ── Filtre statut (soldée / non soldée) ───────────────────────────────
        if ($statutFilter === 'soldee') {
            $facturesGroupees = $facturesGroupees->filter(fn($d) => $d['is_solde']);
        } elseif ($statutFilter === 'proforma') {
            $facturesGroupees = $facturesGroupees->filter(fn($d) => ! $d['is_solde']);
        }

        // ── KPIs globaux ──────────────────────────────────────────────────────
        $totalPatients    = $facturesGroupees->count();
        $totalConsultations = $facturesGroupees->sum('consultations');
        $totalExamens       = $facturesGroupees->sum('examens');
        $totalSoins         = $facturesGroupees->sum('soins');
        $totalPharmacie     = $facturesGroupees->sum('pharmacie');
        $totalAutres        = $facturesGroupees->sum('autres');
        $totalGeneral       = $facturesGroupees->sum('total');
        $totalAssurance     = $facturesGroupees->sum('assurancec');
        $totalPartPatient   = $facturesGroupees->sum('assurec');
        $totalEncaisse      = $facturesGroupees->sum('avance');
        $totalReste         = $facturesGroupees->sum('reste');

        // ── Liste de dates pour le bilan PDF ─────────────────────────────────
        $lists = collect();
        $d = Carbon::now()->subDays(90);
        while ($d <= Carbon::now()) {
            $lists->push($d->format('Y-m-d'));
            $d->addDay();
        }
        $lists = $lists->reverse()->values();

        return view('admin.factures.facture_finale', compact(
            'facturesGroupees', 'startDate', 'endDate', 'statutFilter',
            'totalPatients', 'totalGeneral', 'totalEncaisse', 'totalReste',
            'totalAssurance', 'totalPartPatient', 'totalConsultations',
            'totalExamens', 'totalSoins', 'totalPharmacie', 'totalAutres', 'lists'
        ));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  2. APERÇU — Affiche la facture finale d'un patient avant impression
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Aperçu HTML de la facture finale d'un patient.
     * Route : GET /admin/facturation/finale/{patient}/apercu
     *
     * POURQUOI UNE MÉTHODE APERÇU SÉPARÉE ?
     *   L'aperçu permet de vérifier les données avant impression.
     *   Le PDF est généré séparément pour ne pas bloquer la navigation.
     */
    public function apercuFactureFinale(Request $request, Patient $patient): View
    {
        // Période : depuis le début du jour ou paramètre GET
        $startDate = $request->filled('start')
            ? Carbon::parse($request->input('start'))->startOfDay()
            : Carbon::now()->startOfDay();

        $endDate = $request->filled('end')
            ? Carbon::parse($request->input('end'))->endOfDay()
            : Carbon::now()->endOfDay();

        // Charger toutes les factures du patient sur la période
        $patient->load([
            'facture_consultations' => fn($q) =>
                $q->with('lignes')->whereBetween('created_at', [$startDate, $endDate]),
            'facture_examens' => fn($q) =>
                $q->with('lignes')->whereBetween('created_at', [$startDate, $endDate]),
            'facture_actes' => fn($q) =>
                $q->with('lignes')->whereBetween('created_at', [$startDate, $endDate]),
        ]);

        $donnees = $this->aggregerPatient($patient);

        return view('admin.factures.apercu_facture_finale', compact(
            'patient', 'donnees', 'startDate', 'endDate'
        ));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  3. EXPORT PDF — Facture finale d'un patient (impression)
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Génère le PDF de la facture finale d'un patient.
     * Route : GET /admin/facturation/finale/{patient}/pdf
     */
    public function exportPdf(Request $request, Patient $patient)
    {
        $startDate = $request->filled('start')
            ? Carbon::parse($request->input('start'))->startOfDay()
            : Carbon::now()->startOfDay();

        $endDate = $request->filled('end')
            ? Carbon::parse($request->input('end'))->endOfDay()
            : Carbon::now()->endOfDay();

        $patient->load([
            'facture_consultations' => fn($q) =>
                $q->with('lignes')->whereBetween('created_at', [$startDate, $endDate]),
            'facture_examens' => fn($q) =>
                $q->with('lignes')->whereBetween('created_at', [$startDate, $endDate]),
            'facture_actes' => fn($q) =>
                $q->with('lignes')->whereBetween('created_at', [$startDate, $endDate]),
        ]);

        $donnees = $this->aggregerPatient($patient);
        $isProforma = $donnees['reste'] > 0;

        $pdf = Pdf::loadView('admin.etats.facture_finale', compact(
            'patient', 'donnees', 'startDate', 'endDate', 'isProforma'
        ))->setPaper('a4', 'portrait');

        $nom = 'facture-finale-' . str_replace(' ', '-', strtolower($patient->name)) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->stream($nom);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  4. EXPORT BILAN PDF — Bilan journalier de tous les patients
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Génère le PDF du bilan journalier (tous patients d'une journée donnée).
     * Route : POST /admin/facturation/finale/bilan-pdf
     */
    public function exportBilanPdf(Request $request)
    {
        $request->validate([
            'day' => 'required|date',
        ]);

        $date      = Carbon::parse($request->input('day'));
        $startDate = $date->copy()->startOfDay();
        $endDate   = $date->copy()->endOfDay();
        $service   = $request->input('service', '');

        $patients = Patient::query()
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereHas('facture_consultations', fn($sq) =>
                        $sq->whereBetween('created_at', [$startDate, $endDate]))
                  ->orWhereHas('facture_examens', fn($sq) =>
                        $sq->whereBetween('created_at', [$startDate, $endDate]))
                  ->orWhereHas('facture_actes', fn($sq) =>
                        $sq->whereBetween('created_at', [$startDate, $endDate]));
            })
            ->with([
                'facture_consultations' => fn($q) =>
                    $q->whereBetween('created_at', [$startDate, $endDate]),
                'facture_examens' => fn($q) =>
                    $q->whereBetween('created_at', [$startDate, $endDate]),
                'facture_actes' => fn($q) =>
                    $q->whereBetween('created_at', [$startDate, $endDate]),
            ])
            ->get();

        $lignesBilan = $patients->map(fn($p) => $this->aggregerPatient($p));

        // Filtrage par service si demandé
        if ($service) {
            $lignesBilan = $lignesBilan->filter(function ($d) use ($service) {
                return match ($service) {
                    'consultation' => $d['consultations'] > 0,
                    'examen'       => $d['examens'] > 0,
                    'soins'        => $d['soins'] > 0,
                    default        => true,
                };
            });
        }

        $totaux = [
            'total'         => $lignesBilan->sum('total'),
            'avance'        => $lignesBilan->sum('avance'),
            'reste'         => $lignesBilan->sum('reste'),
            'assurancec'    => $lignesBilan->sum('assurancec'),
            'consultations' => $lignesBilan->sum('consultations'),
            'examens'       => $lignesBilan->sum('examens'),
            'soins'         => $lignesBilan->sum('soins'),
        ];

        $pdf = Pdf::loadView('admin.factures.pdf.bilan_finale_pdf', compact(
            'lignesBilan', 'totaux', 'date', 'service'
        ))->setPaper('a4', 'portrait');

        $nom = 'bilan-journalier-' . $date->format('Y-m-d') . '.pdf';

        return $pdf->stream($nom);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  HELPER PRIVÉ — Agrège les données d'un patient
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Construit le tableau de données agrégées pour un patient.
     * Réutilisé par index(), apercuFactureFinale(), exportPdf().
     *
     * POURQUOI UN HELPER PRIVÉ ?
     *   La même logique est utilisée dans 3 méthodes.
     *   La centraliser ici évite la duplication et facilite la maintenance.
     */
    private function aggregerPatient(Patient $patient): array
    {
        $consultations = (float) $patient->facture_consultations->sum('montant');
        $examens       = (float) $patient->facture_examens->sum('montant_total');
        $soins         = (float) $patient->facture_actes->sum('montant_total');
        $pharmacie     = 0;
        $autres        = 0;

        $total      = $consultations + $examens + $soins + $pharmacie + $autres;
        $assurancec = (float) ($patient->facture_consultations->sum('assurancec')
                             + $patient->facture_examens->sum('assurancec')
                             + $patient->facture_actes->sum('assurancec'));
        $assurec    = $total - $assurancec;
        $avance     = (float) ($patient->facture_consultations->sum('avance')
                             + $patient->facture_examens->sum('avance')
                             + $patient->facture_actes->sum('avance'));
        $reste      = (float) ($patient->facture_consultations->sum('reste')
                             + $patient->facture_examens->sum('reste')
                             + $patient->facture_actes->sum('reste'));

        // Lignes de détail pour l'aperçu/PDF
        $lignesDetail = collect();

        foreach ($patient->facture_consultations as $fc) {
            if ($fc->lignes && $fc->lignes->isNotEmpty()) {
                foreach ($fc->lignes as $l) {
                    $lignesDetail->push([
                        'type'    => 'consultation',
                        'libelle' => $l->libelle,
                        'montant' => $l->montant,
                        'source'  => $fc->numero ?? 'FC',
                    ]);
                }
            } else {
                $lignesDetail->push([
                    'type'    => 'consultation',
                    'libelle' => $fc->motif ?? 'Consultation médicale',
                    'montant' => $fc->montant ?? 0,
                    'source'  => $fc->numero ?? 'FC',
                ]);
            }
        }

        foreach ($patient->facture_examens as $fe) {
            if ($fe->lignes && $fe->lignes->isNotEmpty()) {
                foreach ($fe->lignes as $l) {
                    $lignesDetail->push([
                        'type'    => 'examen',
                        'libelle' => $l->libelle,
                        'montant' => $l->montant,
                        'source'  => $fe->numero ?? 'FE',
                    ]);
                }
            } else {
                $lignesDetail->push([
                    'type'    => 'examen',
                    'libelle' => 'Examen biologique / radiologique',
                    'montant' => $fe->montant_total ?? 0,
                    'source'  => $fe->numero ?? 'FE',
                ]);
            }
        }

        foreach ($patient->facture_actes as $fa) {
            if ($fa->lignes && $fa->lignes->isNotEmpty()) {
                foreach ($fa->lignes as $l) {
                    $lignesDetail->push([
                        'type'    => 'soin',
                        'libelle' => $l->libelle,
                        'montant' => $l->montant,
                        'source'  => $fa->numero ?? 'FA',
                    ]);
                }
            } else {
                $lignesDetail->push([
                    'type'    => 'soin',
                    'libelle' => 'Soin infirmier / Acte paramédical',
                    'montant' => $fa->montant_total ?? 0,
                    'source'  => $fa->numero ?? 'FA',
                ]);
            }
        }

        return compact(
            'consultations', 'examens', 'soins', 'pharmacie', 'autres',
            'total', 'assurancec', 'assurec', 'avance', 'reste', 'lignesDetail'
        ) + ['is_solde' => $reste == 0];
    }
}