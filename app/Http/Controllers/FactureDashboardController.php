<?php

namespace App\Http\Controllers;

use App\Models\FactureConsultation;
use App\Models\FactureExamen;
use App\Models\FactureActe;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\HistoriqueFacture;

/**
 * FactureDashboardController
 *
 * Tableau de bord unifié de la facturation.
 * Affiche les KPIs du jour et les factures récentes de tous types.
 */
class FactureDashboardController extends Controller
{
        public function overview(\Illuminate\Http\Request $request): \Illuminate\View\View
    {
        $this->authorize('view', \App\Models\User::class);

        $name = $request->input('name');
        $perPage = (int) $request->input('per_page', 20);
        $page = (int) $request->input('page', 1);
    
        $todayStart = \Carbon\Carbon::now()->startOfDay();
        $todayEnd   = \Carbon\Carbon::now()->endOfDay();
    
        // Stats consultations du jour
        $consultationsJour = \App\Models\FactureConsultation::whereBetween('created_at', [$todayStart, $todayEnd])
            ->select(['id', 'montant', 'avance', 'reste', 'assurancec', 'statut'])
            ->get();
    
        // Stats actes du jour
        $actesJour = \App\Models\FactureActe::whereBetween('created_at', [$todayStart, $todayEnd])
            ->select(['id', 'montant_total', 'avance', 'reste', 'assurancec', 'statut'])
            ->get();
    
        // Stats examens du jour
        $examensJour = \App\Models\FactureExamen::whereBetween('created_at', [$todayStart, $todayEnd])
            ->select(['id', 'montant_total', 'avance', 'reste', 'assurancec', 'statut'])
            ->get();
    
        $statsJour = [
            'total_recettes'      => $consultationsJour->sum('avance') + $actesJour->sum('avance') + $examensJour->sum('avance'),
            'total_factures'      => $consultationsJour->count() + $actesJour->count() + $examensJour->count(),
            'soldees'             => $consultationsJour->where('statut', 'Soldée')->count()
                                + $actesJour->where('statut', 'Soldée')->count()
                                + $examensJour->where('statut', 'Soldée')->count(),
            'non_soldees'         => $consultationsJour->where('statut', '!=', 'Soldée')->count()
                                + $actesJour->where('statut', '!=', 'Soldée')->count()
                                + $examensJour->where('statut', '!=', 'Soldée')->count(),
            'total_reste'         => $consultationsJour->sum('reste') + $actesJour->sum('reste') + $examensJour->sum('reste'),
            'total_assurance'     => $consultationsJour->sum('assurancec') + $actesJour->sum('assurancec') + $examensJour->sum('assurancec'),
            'consultations_count' => $consultationsJour->count(),
            'examens_count'       => $examensJour->count(),    // ← CORRIGÉ (était hardcodé à 0)
            'soins_count'         => $actesJour->count(),      // ← CORRIGÉ (était hardcodé à 0)
        ];
    
        // Factures récentes — les 50 dernières toutes catégories confondues
        
        /*$facturesRecentes = \App\Models\FactureConsultation::with('patient:id,name,prenom,numero_dossier')
            ->select(['id', 'numero', 'patient_id', 'patient_name', 'montant',
                    'avance', 'reste', 'statut', 'mode_paiement', 'is_printed', 'printed_at', 'created_at'])
            ->latest()->limit(50)->get();*/


             $facturesConsultation = FactureConsultation::with('patient:id,name,prenom,numero_dossier')
            ->select([
                'id', 'numero', 'patient_id', 'patient_name',
                'montant as montant_affiche',   // alias unifié
                'avance', 'reste', 'statut',
                'mode_paiement', 'is_printed', 'printed_at', 'created_at',
            ])
            ->latest()
            ->limit(7)
            ->get()
            ->each(fn ($f) => $f->type_facture = 'consultation'); // on ajoute le type
 
        $facturesExamen = FactureExamen::with('patient:id,name,prenom,numero_dossier')
            ->select([
                'id', 'numero', 'patient_id', 'patient_name',
                'montant_total as montant_affiche',
                'avance', 'reste', 'statut',
                'mode_paiement', 'is_printed', 'printed_at', 'created_at',
            ])
            ->latest()
            ->limit(7)
            ->get()
            ->each(fn ($f) => $f->type_facture = 'examen');
 
        $facturesActe = FactureActe::with('patient:id,name,prenom,numero_dossier')
            ->select([
                'id', 'numero', 'patient_id', 'patient_name',
                'montant_total as montant_affiche',
                'avance', 'reste', 'statut',
                'mode_paiement', 'is_printed', 'printed_at', 'created_at',
            ])
            ->latest()
            ->limit(7)
            ->get()
            ->each(fn ($f) => $f->type_facture = 'acte');
 
        // Fusion + tri + limite à 50 résultats globaux
        $facturesRecentes = $facturesConsultation
            ->concat($facturesExamen)
            ->concat($facturesActe)
            ->sortByDesc('created_at')
            ->values()
            ->take(7);
 
    
        // Dates pour le sélecteur bilan (3 derniers mois)
        $lists = collect();
        $date  = \Carbon\Carbon::now()->subMonths(3);
        while ($date <= \Carbon\Carbon::now()) {
            $lists->push($date->format('Y-m-d'));
            $date->addDay();
        }
        $lists = $lists->reverse()->values();
    
        return view('admin.factures.acceuil', compact('statsJour', 'facturesRecentes', 'lists','perPage'));
    }
 
}
