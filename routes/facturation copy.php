<?php

use App\Http\Controllers\FactureActeController;
use App\Http\Controllers\FactureChambreController;
use App\Http\Controllers\FactureExamenController;
use Illuminate\Support\Facades\Route;

/**
 * Routes du module Facturation
 *
 * À inclure dans routes/web.php :
 *   require base_path('routes/facturation.php');
 *
 * Ou via un groupe dans AppServiceProvider / RouteServiceProvider.
 *
 * CONVENTION :
 *   - Toutes les routes sont sous le préfixe /facturation
 *   - Nommées facturation.{type}.{action}
 *   - Protégées par le middleware 'auth' (ajoutez vos rôles si besoin)
 */
Route::middleware(['auth'])->prefix('facturation')->name('facturation.')->group(function () {


 /**
     * ══════════════════════════════════════════════════════════════════════
     *  FACTURE DASHBOARD ROUTES
     * ══════════════════════════════════════════════════════════════════════
     */

    // ── Main dashboard view ──────────────────────────────────────────────────────
    Route::get('factures-dashboard', 'FactureDashboardController@overview')
        ->name('factures.dashboard');

    // ── AJAX JSON stats endpoint (consumed by the blade JS) ──────────────────────
    // ?module=consultation|laboratoire|pharmacie|chambre|devis|bilan
    // &period=today|week|month|year|custom
    // &start_date=YYYY-MM-DD  (when period=custom)
    // &end_date=YYYY-MM-DD    (when period=custom)
    Route::get('factures-dashboard/stats', 'FactureDashboardController@apiStats')
        ->name('factures.dashboard.stats');

    // ── PDF export endpoints ─────────────────────────────────────────────────────
    // Global cross-module bilan PDF
    Route::post('factures-dashboard/bilan-pdf', 'FactureDashboardController@bilanPdf')
        ->name('factures.dashboard.bilan_pdf');

    // Consultation bilan PDF (delegates to existing FactureController logic)
    Route::post('factures-dashboard/bilan-consultation-pdf', 'FactureDashboardController@bilanConsultationPdf')
        ->name('factures.dashboard.bilan_consultation_pdf');

    // ── Factures Examens ──────────────────────────────────────────────────────
    Route::prefix('examens')->name('examens.')->group(function () {
        Route::get('/',                      [FactureExamenController::class, 'index'])   ->name('index');
        Route::get('/creer',                 [FactureExamenController::class, 'create'])  ->name('create');

        Route::post('factures/ajouter-element',[ FactureExamenController::class],'ajouterElement')->name('factures.ajouter-element');
        
        Route::post('/',                     [FactureExamenController::class, 'store'])   ->name('store');
        Route::get('/{factureExamen}',       [FactureExamenController::class, 'show'])    ->name('show');
        Route::get('/{factureExamen}/imprimer', [FactureExamenController::class, 'imprimer'])->name('print');
        Route::post('/{factureExamen}/paiement', [FactureExamenController::class, 'enregistrerPaiement'])->name('paiement');
        Route::post('factures-examen/creer-direct', [FactureExamenController::class],'storeFactureExamenDirect')->name('factures.examen.store_direct');
        Route::get('api/factures/patients/search-examen', [FactureExamenController::class], 'searchPatientsExamen')
        ->name('api.factures.patients.search-examen');

        /////////////////////////////////////////////
         Route::get('api/factures/patient-examens', 'FactureExamenController@getPatientExamens')
        ->name('api.factures.patient-examens');
        Route::get('api/factures/patient-soins', 'FactureExamenController@getPatientSoins')
        ->name('api.factures.patient-soins');

    });

    // ── Factures Actes ────────────────────────────────────────────────────────
    Route::prefix('actes')->name('actes.')->group(function () {
        Route::get('/',                      [FactureActeController::class, 'index'])     ->name('index');
        Route::get('/creer',                 [FactureActeController::class, 'create'])    ->name('create');
        Route::post('/',                     [FactureActeController::class, 'store'])     ->name('store');
        Route::get('/{factureActe}',         [FactureActeController::class, 'show'])      ->name('show');
        Route::get('/{factureActe}/imprimer',[FactureActeController::class, 'imprimer'])  ->name('print');
        Route::post('/{factureActe}/paiement', [FactureActeController::class, 'enregistrerPaiement'])->name('paiement');
    });

    // ── Factures Chambres ─────────────────────────────────────────────────────
    Route::prefix('chambres')->name('chambres.')->group(function () {
        Route::get('/',                        [FactureChambreController::class, 'index'])   ->name('index');
        Route::get('/creer',                   [FactureChambreController::class, 'create'])  ->name('create');
        Route::post('/',                       [FactureChambreController::class, 'store'])   ->name('store');
        //Route::get('factures-chambre', 'FactureController@FactureChambre')->name('factures.chambre');
        Route::get('/{factureChambre}',        [FactureChambreController::class, 'show'])    ->name('show');
        Route::get('/{factureChambre}/imprimer', [FactureChambreController::class, 'imprimer'])->name('print');
        Route::post('/{factureChambre}/paiement', [FactureChambreController::class, 'enregistrerPaiement'])->name('paiement');
    });
});