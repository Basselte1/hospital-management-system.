<?php
// routes/facturation.php

use App\Http\Controllers\FactureActeController;
use App\Http\Controllers\FactureChambreController;
use App\Http\Controllers\FactureExamenController;
use App\Http\Controllers\FactureDashboardController;
use App\Http\Controllers\FactureDevisController;
use App\Http\Controllers\FactureFinaleController;
use Illuminate\Support\Facades\Route;

// ── DASHBOARD ────────────────────────────────────────────────
Route::get('dashboard', [FactureDashboardController::class, 'overview'])->name('dashboard');
Route::post('dashboard/bilan-pdf', [FactureDashboardController::class, 'bilanPdf'])->name('dashboard.bilan_pdf');

// ── ACTES / SOINS → ouvre facture_actes.blade.php ────────────
Route::get('factures/{factureActe}/apercu-acte', [FactureActeController::class, 'show'])->name('factures.apercu_acte');
Route::get('actes',  [FactureActeController::class, 'index'])->name('actes.index');
Route::post('actes', [FactureActeController::class, 'index'])->name('actes.search');
Route::get('actes/api/patient-soins', [FactureActeController::class, 'getPatientSoins'])->name('actes.api.patient_soins');
Route::post('actes/bilan-pdf', [FactureActeController::class, 'export_bilan_acte'])->name('actes.bilan_pdf');
Route::get('factures/{facture}/pdf', [FactureActeController::class, 'exportPdf'])->name('facturation.acte_pdf');
Route::put('actes/{factureActe}', [FactureActeController::class, 'FactureActeUpdate'])->name('actes.update');
Route::delete('actes/{factureActe}', [FactureActeController::class, 'destroy'])->name('actes.destroy');
Route::post('actes/{factureActe}/paiement', [FactureActeController::class, 'enregistrerPaiement'])->name('actes.paiement');


// ── EXAMENS → ouvre facture_examen.blade.php ─────────────────
// Routes statiques (sans paramètre) — à déclarer EN PREMIER
Route::get('examens', [FactureExamenController::class, 'index'])->name('examens.index');
Route::post('examens', [FactureExamenController::class, 'index'])->name('examens.search');
Route::post('examens/bilan-pdf', [FactureExamenController::class, 'export_bilan_examen'])->name('examens.bilan_pdf');
Route::post('examens/store-direct', [FactureExamenController::class, 'storeFactureExamenDirect'])->name('examens.store_direct');
Route::get('factures/create-facture-examen', [FactureExamenController::class, 'create'])->name('factures.create_facture_examen');
Route::get('examens/api/patient-examens', [FactureExamenController::class, 'getPatientExamens'])->name('examens.api.patient_examens');
Route::get('examens/api/search-patients', [FactureExamenController::class, 'searchPatientsExamen'])->name('examens.api.search_patients');
 
// Routes dynamiques (avec paramètre) — à déclarer APRÈS les statiques
Route::get('factures/{factureExamen}/apercu-examen', [FactureExamenController::class, 'show'])->name('factures.apercu_examen');
Route::get('facture-examen/{factureExamen}/pdf', [FactureExamenController::class, 'exportPdf'])->name('examen_pdf');
 
Route::put('examens/{factureExamen}', [FactureExamenController::class, 'FactureExamenUpdate'])->name('examens.update');
Route::delete('examens/{factureExamen}', [FactureExamenController::class, 'destroy'])->name('examens.destroy');
Route::post('examens/{factureExamen}/paiement', [FactureExamenController::class, 'enregistrerPaiement'])->name('examens.paiement');
 

Route::post('examens/{factureExamen}/ajouter-element', [FactureExamenController::class, 'ajouterElement'])->name('examens.ajouter_element');

// ── CHAMBRES → ouvre facture_finale.blade.php ────────────────
Route::get('chambres',  [FactureChambreController::class, 'index'])->name('chambres.index');
Route::post('chambres/creer', [FactureChambreController::class, 'store'])->name('chambres.store');
Route::post('chambres/{factureChambre}/paiement', [FactureChambreController::class, 'enregistrerPaiement'])->name('chambres.paiement');

// ── DEVIS → ouvre facture_devis.blade.php ────────────────────
Route::get('devis',        [FactureDevisController::class, 'FactureDevis'])->name('devis.index');
Route::get('devis/creer',  [FactureDevisController::class, 'FactureDevisCreate'])->name('devis.create');
Route::post('devis/creer', [FactureDevisController::class, 'FactureDevisStore'])->name('devis.store');
Route::get('devis/{id}/pdf', [FactureDevisController::class, 'export_facture_devis'])->name('devis.pdf');


// ── FACTURE FINALE → ouvre facture_finale.blade.php ──────────
Route::get('finale', [FactureFinaleController::class, 'index'])->name('finale.index');
Route::get('finale/bilan-pdf', [FactureFinaleController::class, 'bilanPdf'])->name('finale.bilan_pdf');
Route::get('final/pdf', [FactureFinaleController::class, 'exportPdf'])->name('final.pdf');