<?php

require __DIR__.'/facturation.php';


Route::get('/', 'AdminController@index');

//Auth::routes();

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');



Route::group(['prefix' => 'admin', 'middleware' => ['auth'] ], function () {


    Route::get('/', 'AdminController@index');
    Route::get('dashboard', 'AdminController@dashboard')->name('admin.dashboard');
    


    Route::get('users', 'UsersController@index')->name('users.index');
    Route::get('users/create', 'UsersController@create')->name('users.create');
    Route::post('users', 'UsersController@store')->name('users.store');
    Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit');
    Route::patch('users/{user}', 'UsersController@update')->name('users.update');
    Route::get('users/{user}/profile', 'UsersController@profile')->name('users.profile');
    Route::patch('users/{user}/changePassword', 'UsersController@changePassword')->name('users.changePassword');
    Route::delete('users/{user}', 'UsersController@destroy')->name('users.destroy');


    Route::get('roles', 'RolesController@index')->name('roles.index');
    Route::get('roles/create', 'RolesController@create')->name('roles.create');
    Route::post('roles', 'RolesController@store')->name('roles.store');
    Route::get('roles/{role}/edit', 'RolesController@edit')->name('roles.edit');
    Route::patch('roles/{role}', 'RolesController@update')->name('roles.update');
    Route::delete('roles/{role}', 'RolesController@destroy')->name('roles.destroy');



    // === PRODUITS ROUTES ===

    // Basic CRUD
    Route::get('/produits', 'ProduitsController@index')->name('produits.index');
    Route::get('/produits/create', 'ProduitsController@create')->name('produits.create');
    Route::post('/produits', 'ProduitsController@store')->name('produits.store');
    Route::get('/produits/{produit}/edit', 'ProduitsController@edit')->name('produits.edit');
    Route::patch('/produits/{produit}', 'ProduitsController@update')->name('produits.update');
    Route::delete('/produits/{produit}', 'ProduitsController@destroy')->name('produits.destroy');

    // Category filters (can redirect to index with filter or use separate views)
    Route::get('/produits/pharmaceutique', 'ProduitsController@pharmaceutique')
        ->name('produits.pharmaceutique');
    Route::get('/produits/materiel', 'ProduitsController@materiel')
        ->name('produits.materiel');
    Route::get('/produits/anesthesiste', 'ProduitsController@anesthesiste')
        ->name('produits.anesthesiste');

    // Stock verification (Comptable)
    Route::get('/produits/stock/verification', 'ProduitsController@stockVerification')
        ->name('produits.stock.verification');

    // === EDIT PERMISSIONS ===

    // Request edit permission
    Route::post('/produits/{produit}/edit-permission/request', 'ProduitsController@requestEditPermission')
        ->name('produits.edit-permissions.request');

    // Pending edit permissions (for Admin/Gestionnaire)
    Route::get('/produits/edit-permissions/pending', 'ProduitsController@pendingEditPermissions')
        ->name('produits.edit-permissions.pending');

    // Approve/Reject edit permissions
    Route::post('/produits/edit-permissions/{editRequest}/approve', 'ProduitsController@approveEditPermission')
        ->name('produits.edit-permissions.approve');

    Route::post('/produits/edit-permissions/{editRequest}/reject', 'ProduitsController@rejectEditPermission')
        ->name('produits.edit-permissions.reject');

    // Revoke edit permission
    Route::post('/produits/edit-permissions/{editRequest}/revoke', 'ProduitsController@revokeEditPermission')
        ->name('produits.edit-permissions.revoke');

    // Batch operations
    Route::post('/produits/edit-permissions/batch/approve', 'ProduitsController@batchApproveEditPermissions')
        ->name('produits.edit-permissions.batch.approve');

    Route::post('/produits/edit-permissions/batch/reject', 'ProduitsController@batchRejectEditPermissions')
        ->name('produits.edit-permissions.batch.reject');

    // View edit permissions history (Admin/Gestionnaire)
    Route::get('/produits/edit-permissions/history', 'ProduitsController@editPermissionsHistory')
        ->name('produits.edit-permissions.history');

    // View my edit permissions (Users)
    Route::get('/produits/my-edit-permissions', 'ProduitsController@myEditPermissions')
        ->name('produits.my-edit-permissions');


    // Toggle product reusable status (AJAX)
    Route::post('/produits/{id}/toggle-reusable','ProduitsController@toggleReusable')
        ->name('produits.toggle-reusable');
    
    // View list of reusable products
    Route::get('/produits/reusable-list','ProduitsController@reusableList')
        ->name('produits.reusable-list');
    
    // Edit reusable product settings
    Route::get('/produits/{id}/edit-reusable-settings','ProduitsController@editReusableSettings')
        ->name('produits.edit-reusable-settings');
    
    Route::put('/produits/{id}/update-reusable-settings','ProduitsController@updateReusableSettings')
        ->name('produits.update-reusable-settings');


    // === AUDIT LOGS ===

    // View all audit logs (Admin only)
    Route::get('/produits/audit-logs', 'ProduitsController@auditLogs')
        ->name('produits.audit-logs');

    // View audit logs for specific product
    Route::get('/produits/{produit}/audit-logs', 'ProduitsController@productAuditLogs')
        ->name('produits.audit-logs.show');

    // === CART & BILLING ===

    // Add to cart (used in pharmaceutique, anesthesiste views)
    Route::get('pharmaceutiques/{id}', 'ProduitsController@getAddToCart')
        ->name('pharmaceutique.cart');

    // Add item (increase quantity)
    Route::get('pharmaceutiques/add/{id}', 'ProduitsController@getAddItem')
        ->name('pharmaceutique.add');

    // Facturation page (shopping cart view)
    Route::get('facturation', 'ProduitsController@facturation')
        ->name('pharmaceutique.facturation');

    // Reduce quantity by one
    Route::get('reduire/{id}', 'ProduitsController@getReduceByOne')
        ->name('facturation.reduire');

    // Remove item from cart
    Route::get('supprimer/{id}', 'ProduitsController@getRemoveItem')
        ->name('facturation.supprimer');

    // Print invoice/receipt
    Route::post('imprimer', 'ProduitsController@export_pdf')
        ->name('pharmacie.pdf');

    // Save invoice (if needed)
    Route::post('produit/save-invoice/{produit}', 'ProduitsController@saveInvoice')
        ->name('produit.invoice');

    // === API ENDPOINTS ===

    // Search products (AJAX)
    Route::get('api/produits/search', 'ProduitsController@searchJson')
        ->name('produits.search.json');

    // Autocomplete (AJAX)
    Route::get('api/produits/autocomplete', 'ProduitsController@autocomplete')
        ->name('produits.autocomplete');

    Route::get('/produits/search-json', 'ProduitsController@searchJson')->name('produits.search-json');


    // === BON DE COMMANDE ROUTES (CORRECTED ORDER) ===
    
    // List all purchase orders
    Route::get('/bon-commandes', 'BonCommandeController@index')
        ->name('bon-commandes.index');

    // Create form (MUST BE BEFORE {id})
    Route::get('/bon-commandes/create', 'BonCommandeController@create')
        ->name('bon-commandes.create');
    
    // Validation page (MUST BE BEFORE {id})
    Route::get('/bon-commandes/validation', 'BonCommandeValidationController@index')
        ->name('bon-commandes.validation');

    // Store new purchase order
    Route::post('/bon-commandes', 'BonCommandeController@store')
        ->name('bon-commandes.store');

    // Show specific purchase order (AFTER specific routes)
    Route::get('/bon-commandes/{id}', 'BonCommandeController@show')
        ->name('bon-commandes.show');

    // Edit form (parameter makes it clear it's not a conflict)
    Route::get('/bon-commandes/{id}/edit', 'BonCommandeController@edit')
        ->name('bon-commandes.edit');
    
    // PDF generation (parameter makes it clear)
    Route::get('/bon-commandes/{id}/pdf', 'BonCommandeController@generatePdf')
        ->name('bon-commandes.pdf');

    // Update purchase order
    Route::put('/bon-commandes/{id}', 'BonCommandeController@update')
        ->name('bon-commandes.update');

    // Delete purchase order
    Route::delete('/bon-commandes/{id}', 'BonCommandeController@destroy')
        ->name('bon-commandes.destroy');

    // Send for validation
    Route::post('/bon-commandes/{id}/send-for-validation', 'BonCommandeController@sendForValidation')
        ->name('bon-commandes.send-for-validation');
        
    Route::post('/bon-commandes/{id}/send', 'BonCommandeController@send')
        ->name('bon-commandes.send');

    // === VALIDATION ACTIONS ===

    // Validate a purchase order
    Route::post('/bon-commandes/{id}/validate', 'BonCommandeValidationController@validateCommande')
        ->name('bon-commandes.validate');

    // Reject a purchase order
    Route::post('/bon-commandes/{id}/reject', 'BonCommandeValidationController@reject')
        ->name('bon-commandes.reject');

    // Batch validate multiple orders
    Route::post('/bon-commandes/batch-validate', 'BonCommandeValidationController@batchValidate')
        ->name('bon-commandes.batch-validate');


    // === STOCK RECEPTION ROUTES  ===
    // List all receptions
    Route::get('/stock-receptions', 'StockReceptionController@index')
        ->name('stock-receptions.index');
    
    // Show orders ready for reception
    Route::get('/stock-receptions/ready', 'StockReceptionController@readyForReception')
        ->name('stock-receptions.ready');
    
    // Create reception form (UPDATED to handle product matching)
    Route::get('/stock-receptions/create/{bonCommandeId}', 'StockReceptionController@create')
        ->name('stock-receptions.create');
    
    // Store reception (UPDATED to handle automatic product creation/matching)
    Route::post('/stock-receptions', 'StockReceptionController@store')
        ->name('stock-receptions.store');
    
    // Show reception details
    Route::get('/stock-receptions/{id}', 'StockReceptionController@show')
        ->name('stock-receptions.show');
    
    // Validate reception (Gestionnaire only)
    Route::post('/stock-receptions/{id}/validate', 'StockReceptionController@validateReception')
        ->name('stock-receptions.validate');
    
    // Generate PDF
    Route::get('/stock-receptions/{id}/pdf', 'StockReceptionController@generatePdf')
        ->name('stock-receptions.pdf');
    
    // Stock Transactions History (for Phase 5 preview)
    Route::get('/stock-transactions', 'StockTransactionController@index')
        ->name('stock-transactions.index');
    
    Route::get('/stock-transactions/produit/{id}', 'StockTransactionController@byProduit')
        ->name('stock-transactions.produit');


    // Pharmacie Dashboard
    Route::get('/pharmacie', 'PharmacieController@index')
        ->name('pharmacie.index');

    // Patient Search & Prescriptions
    Route::get('/pharmacie/search-patient', 'PharmacieController@searchPatient')
        ->name('pharmacie.search-patient');
    
    Route::get('/pharmacie/patient/{id}/prescriptions', 'PharmacieController@getPatientPrescriptions')
        ->name('pharmacie.patient.prescriptions');

    Route::get('/pharmacie/patients-with-prescriptions', 'PharmacieController@getPatientsWithPrescriptions')
    ->name('pharmacie.patients-with-prescriptions');

    Route::get('/pharmacie/prescription-details/{id}', 'PharmacieController@getPrescriptionDetails')
        ->name('pharmacie.prescription-details');

    Route::post('/pharmacie/create-sale', 'PharmacieController@createSale')
        ->name('pharmacie.create-sale');

    // Create Sales
    Route::get('/pharmacie/vente/patient/create', 'PharmacieController@createPatientSale')
        ->name('pharmacie.sales.patient.create');
    
    Route::post('/pharmacie/vente/patient', 'PharmacieController@storePatientSale')
        ->name('pharmacie.sales.patient.store');
    
    Route::get('/pharmacie/vente/externe/create', 'PharmacieController@createExternalSale')
        ->name('pharmacie.sales.external.create');
    
    Route::post('/pharmacie/vente/externe', 'PharmacieController@storeExternalSale')
        ->name('pharmacie.sales.external.store');

    // Patient search ordered by ordonnance
    Route::get('/pharmacie/ordonnance/{ordonanceId}/details', 'PharmacieController@getOrdonanceDetails')
        ->name('pharmacie.ordonnance.details');

    // View & Manage Sales
    Route::get('/pharmacie/ventes', 'PharmacieController@listSales')
        ->name('pharmacie.sales.list');
    
    Route::get('/pharmacie/vente/{id}', 'PharmacieController@show')
        ->name('pharmacie.sales.show');
    
    Route::post('/pharmacie/vente/{id}/soldee', 'PharmacieController@markAsSoldee')
        ->name('pharmacie.sales.soldee');

    // PDF Generation
    Route::get('/pharmacie/vente/{id}/invoice', 'PharmacieController@generateInvoice')
        ->name('pharmacie.sales.invoice');
    
    Route::get('/pharmacie/vente/{id}/receipt', 'PharmacieController@generateReceipt')
        ->name('pharmacie.sales.receipt');

    // Reports & History
    Route::get('/pharmacie/historique', 'PharmacieController@salesHistory')
        ->name('pharmacie.history');


    // Dashboard
    Route::get('reusable-products', 'ReusableProductController@index')
        ->name('reusable-products.index');

    Route::get('reusable-products/search', 'ReusableProductController@searchProducts')->name('reuse-products.search');

    // Record Usage
    Route::get('reusable-products/record-usage', 'ReusableProductController@recordUsageForm')
        ->name('reusable-products.record-usage.form');
    Route::post('reusable-products/record-usage', 'ReusableProductController@recordUsage')
        ->name('reusable-products.record-usage.store');

    // Pending Usages
    Route::get('reusable-products/usages/pending', 'ReusableProductController@pendingUsages')
        ->name('reusable-products.usages.pending');
    Route::post('reusable-products/usages/{id}/collect', 'ReusableProductController@collectForSterilization')
        ->name('reusable-products.usages.collect');

    // Sterilizations
    Route::get('reusable-products/sterilizations', 'ReusableProductController@sterilizations')
        ->name('reusable-products.sterilizations.index');
    Route::get('reusable-products/sterilizations/create', 'ReusableProductController@createSterilizationForm')
        ->name('reusable-products.sterilizations.create.form');
    Route::post('reusable-products/sterilizations', 'ReusableProductController@createSterilization')
        ->name('reusable-products.sterilizations.store');
    Route::get('reusable-products/sterilizations/{id}', 'ReusableProductController@showSterilization')
        ->name('reusable-products.sterilizations.show');
    Route::post('reusable-products/sterilizations/{id}/complete', 'ReusableProductController@completeSterilization')
        ->name('reusable-products.sterilizations.complete');
    Route::post('reusable-products/sterilizations/{id}/validate', 'ReusableProductController@validateSterilization')
        ->name('reusable-products.sterilizations.validate');
    Route::post('reusable-products/sterilizations/{id}/return-to-stock', 'ReusableProductController@returnToStock')
        ->name('reusable-products.sterilizations.return-to-stock');
    Route::get('reusable-products/sterilizations/{id}/certificate', 'ReusableProductController@sterilizationCertificate')
        ->name('reusable-products.sterilizations.certificate');


    Route::get('patients', 'PatientsController@index')->name('search.results');
    
    // Events routes 
    Route::get('events', 'EventsController@index')->name('events.index');

    // API routes for dropdowns (must come before {event} route)
    Route::get('api/patients', 'EventsController@getPatients')->name('api.patients');
    Route::get('api/medecins', 'EventsController@getMedecins')->name('api.medecins');
    
    // Events routes
    Route::get('events/all', 'EventsController@allMedecinsEvents')->name('events.all');
    Route::get('events/medecin/{id_medecin}', 'EventsController@medecinEvents')->name('events.medecinEvents');

    Route::post('events', 'EventsController@store')->name('events.store');
    Route::put('events', 'EventsController@update')->name('events.update');
    Route::put('events/{event}', 'EventsController@updateSingle')->name('events.updateSingle');
    Route::delete('events/{event}', 'EventsController@destroy')->name('events.destroy');

    // Liste et recherche
    Route::get('patient-visits', 'PatientVisitsController@index')->name('patient-visits.index');
    Route::get('patient-visits/create', 'PatientVisitsController@create')->name('patient-visits.create');
    Route::post('patient-visits', 'PatientVisitsController@store')->name('patient-visits.store');

    // Recherche AJAX de patients (pour autocomplétion)
    Route::get('patient-visits/search-patients', 'PatientVisitsController@searchPatients')->name('patient-visits.search-patients');

    // CRUD
    Route::get('patient-visits/{visit}', 'PatientVisitsController@show')->name('patient-visits.show');
    Route::get('patient-visits/{visit}/edit', 'PatientVisitsController@edit')->name('patient-visits.edit');
    Route::patch('patient-visits/{visit}', 'PatientVisitsController@update')->name('patient-visits.update');
    Route::delete('patient-visits/{visit}', 'PatientVisitsController@destroy')->name('patient-visits.destroy');

    Route::post('patient-visits/{visit}/motifs','PatientVisitsController@addMotif')->name('patient-visits.motifs.store');
 
    Route::delete('patient-visits/{visit}/motifs/{motif}','PatientVisitsController@deleteMotif')->name('patient-visits.motifs.destroy');
 
    // Route::patch('patient-visits/{visit}/motif', 'PatientVisitsController@updateMotif')->name('patient-visits.update-motif');


    // Historique par patient
    Route::get('patients/{patient}/visits', 'PatientVisitsController@showPatientVisits')->name('patient-visits.patient-history');

    // API
    Route::get('api/patients/search', 'PatientVisitsController@searchPatients')->name('api.patients.search');
    Route::patch('patient-visits/{visit}/status', 'PatientVisitsController@updateStatus')->name('patient-visits.update-status');

    // Patients Suivis
    Route::get('patients/suivis', 'PatientSuivisController@patientsSuivis')->name('patients.suivis');

    Route::get('patients/index', 'PatientsController@index')->name('patients.index');
    Route::get('patients/create', 'PatientsController@create')->name('patients.create');
    Route::post('patients', 'PatientsController@store')->name('patients.store');
    Route::get('patients/{patient}', 'PatientsController@show')->name('patients.show');
    Route::patch('patients/{patient}', 'PatientsController@update')->name('patients.update');
    Route::put('patients/{patient}', 'PatientsController@motifMontantUpdate')->name('patients.motif_montant.update');
    Route::delete('patients/{patient}', 'PatientsController@destroy')->name('patients.destroy');
    Route::delete('patients/{id}/force-delete', 'PatientsController@forceDestroy')->name('patients.force-destroy');
    Route::post('patients/{id}/restore', 'PatientsController@restore')->name('patients.restore');
    Route::get('patient/{id}','PatientsController@generate_consultation')->name('consultation.pdf');

    Route::get('patient/{id}/generer-facture', 'PatientsController@generateFacture')->name('patient.generer.facture');


    Route::get('ordonance/{ordonance}','PatientsController@export_ordonance')->name('ordonance.pdf');
    Route::post('bilan-consultation','FactureConsultationController@export_bilan_consultation')->name('bilan_consultation.pdf');

    Route::get('examens/', 'PatientimageController@index')->name('examens.index');
    Route::get('examens/create/{patient}', 'PatientimageController@create')->name('examens.create');
    Route::post('examen', 'PatientimageController@store')->name('examens.store');
    Route::get('examens/show/{patient}', 'PatientimageController@show')->name('examens.show');
    Route::get('examensf/{patient}', 'PatientimageController@showall')->name('examens.showall');
    Route::delete('examens/{id}', 'PatientimageController@destroy')->name('examens.destroy');


    Route::get('lettre-de-sortie','PatientsController@index_sortie')->name('index.sortie');
    Route::get('lettre-de-sortie/{patient}','PatientsController@print_sortie')->name('print.sortie');
    Route::delete('lettre-de-sortie/{id}', 'PatientsController@destroy_sortie')->name('destroy.sortie');

    Route::get('prescriptions/create/{patient}', 'PrescriptionController@create')->name('prescriptions.create');
    Route::post('examens', 'PrescriptionController@store')->name('prescriptions.store');
    Route::get('prescription_examens/{id}','PrescriptionController@export_prescription')->name('prescription_examens.pdf');
    Route::get('prescriptions/{id}', 'PrescriptionController@show')->name('prescriptions.show');





    
    Route::get('dossiers/create/{patient}', 'DossiersController@create')->name('dossiers.create');
    Route::post('dossiers', 'DossiersController@store')->name('dossiers.store');
    Route::patch('dossiers/{dossier}', 'DossiersController@update')->name('dossiers.update');

    Route::get('consultations/create/{patient}', 'ConsultationsController@create')->name('consultations.create');
    Route::get('consultations/edit/{patient}', 'ConsultationsController@edit')->name('consultations.edit');
    Route::get('consultations/{patient}', 'ConsultationsController@show')->name('consultations.show');

    Route::get('detatils-consultations/{patient}', 'ConsultationsController@IndexConsultationChirurgien')->name('consultations.index');
    Route::get('consultations-anesthesique/{patient}', 'ConsultationsController@IndexConsultationAnesthesiste')->name('consultations.index_anesthesiste');

    Route::put('consultation-chirurgien/{consultation}', 'ConsultationsController@update_consultation_chirurgien')->name('consultation_chirurgien.update');
    Route::put('consultation-anesthesiste/{consultationAnesthesiste}', 'ConsultationsController@update_consultation_anesthesiste')->name('consultation_anesthesiste.update');
    Route::post('consultation-chirurgien', 'ConsultationsController@store_consultation_chirurgien')->name('consultation_chirurgien.store');
    Route::post('consultation-anesthesiste', 'ConsultationsController@Astore')->name('consultation_anesthesiste.store');

    Route::get('consentement-eclaire/{patient}', 'ConsultationsController@Export_consentement_eclaire')->name('consentement_eclaire.pdf');
    Route::get('consultations/{id}','ConsultationsController@export')->name('consulatations.pdf');

    Route::post('fiche-intervention', 'CompteRenduBlocOperatoireController@StoreFicheIntervention')->name('fiche_intervention.store');
    Route::get('fiche-intervention-preview/{id}', 'CompteRenduBlocOperatoireController@Print_ficheIntervention')->name('fiche_intervention.pdf');

    Route::get('compte-rendu-bloc-global/{patient}', 'CompteRenduBlocOperatoireController@index')->name('compte_rendu_bloc.index');
    Route::get('compte-rendu-bloc/create/{patient}', 'CompteRenduBlocOperatoireController@create')->name('compte_rendu_bloc.create');
    Route::get('compte-rendu-bloc/edit/{patient}', 'CompteRenduBlocOperatoireController@edit')->name('compte_rendu_bloc.edit');
    Route::post('compte-rendu-bloc', 'CompteRenduBlocOperatoireController@store')->name('compte_rendu_bloc.store');
    Route::put('compte-rendu-bloc/{compteRenduBlocOperatoire}', 'CompteRenduBlocOperatoireController@update')->name('compte_rendu_bloc.update');
    Route::get('compte-rendu-bloc/{id}', 'CompteRenduBlocOperatoireController@compte_rendu_bloc_pdf')->name('compte_rendu_bloc_pdf.pdf');

    Route::post('ordonances', 'OrdonancesController@store')->name('ordonances.store');
    Route::get('ordonance-creation/create/{patient}','OrdonancesController@ordonance_create')->name('ordonance.create');
    Route::get('ordonances/{id}/edit', 'OrdonancesController@edit')->name('ordonances.edit');
    Route::put('ordonances/{id}', 'OrdonancesController@update')->name('ordonances.update');
    Route::get('ordonances/{id}/export', 'OrdonancesController@export_pdf')->name('ordonances.export_pdf');

    Route::post('parametres', 'ParametresController@fiche_parametre_store')->name('fiche_parametres.store');
    Route::put('parametres/{parametre}', 'ParametresController@fiche_parametre_update')->name('fiche_parametres.update');

    Route::get('/chambres', 'ChambresController@index')->name('chambres.index');
    Route::post('/chambres', 'ChambresController@store')->name('chambres.store');
    Route::get('/chambres/create', 'ChambresController@create')->name('chambres.create');
    Route::get('/chambres/{chambre}/edit', 'ChambresController@edit')->name('chambres.edit');
    Route::patch('/chambres-update/{chambre}', 'ChambresController@update')->name('chambres.update');
    Route::patch('/chambres-attribute/{chambre}', 'ChambresController@updateStatus')->name('chambres_status.update');
    Route::patch('/chambres-liberer/{chambre}', 'ChambresController@updateMinus')->name('chambres_minus.update');
    Route::get('/chambres/{chambre}/attribute', 'ChambresController@attribute')->name('chambres.attribute');

    //Route::get('factures', 'FactureConsultationController@index')->name('factures.index');
    
    Route::get('factures-devis', 'FactureDevisController@FactureDevis')->name('facture_devis.index');
    Route::get('factures-devis/create', 'FactureDevisController@FactureDevisCreate')->name('facture_devis.create');
    Route::post('factures-devis', 'FactureDevisController@FactureDevisStore')->name('facture_devis.store');
    Route::get('factures-devis/{id}', 'FactureDevisController@export_facture_devis')->name('facture_devis.pdf');
    Route::get('factures/{facture}', 'FactureConsultationController@show')->name('factures.show');
    Route::delete('facture/{id}', 'FactureConsultationController@destroy')->name('factures.destroy');

    Route::post('devis/{id}/generer-facture', [DevisController::class, 'generateDevi'])->name('devis.generer_facture');

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //les routes de factures consultations

    Route::get('factures/consultation', 'FactureConsultationController@index')->name('factures.consultation');

    Route::get('factures-consultation', 'FactureConsultationController@FactureConsultation')->name('factures.consultation');
    Route::post('factures-consultation', 'FactureConsultationController@FactureConsultation')->name('search.date');
    Route::put('factures-consultation/{id}', 'FactureConsultationController@FactureConsultationUpdate')->name('factures.consultation.update');


    Route::get('factures/apercu_consultation/{facture}', 'FactureConsultationController@apercuConsultation')->name('factures.apercu_consultation');    
 
    

    Route::get('patient-facture/{id}','FactureConsultationController@exportConsultationPdf')->name('factures.consultation_pdf');

    Route::get('rapports/activite', 'RapportController@index')->name('rapports.activite');
    Route::get('rapports/activite/export', 'RapportController@exportPdf')->name('rapports.activite.export');

    Route::resource('/fiches', 'FichesController');
    Route::get('fiche/{id}','FichesController@export_pdf')->name('fiche.pdf');

    // Devis Routes
    // Devis Elements Management (Admin only)
    Route::get('devis-elements', 'DevisElementsController@index')->name('devis_elements.index');
    Route::post('devis-elements', 'DevisElementsController@store')->name('devis_elements.store');
    Route::put('devis-elements/{id}', 'DevisElementsController@update')->name('devis_elements.update');
    Route::delete('devis-elements/{id}', 'DevisElementsController@destroy')->name('devis_elements.destroy');
    Route::get('devis-elements/actifs', 'DevisElementsController@getActifs')->name('devis_elements.actifs');

    // Devis Management
    Route::get('devis', 'DevisController@index')->name('devis.index');
    Route::post('devis', 'DevisController@store')->name('devis.store');

    Route::get('/devis/{id}/edit',   'DevisController@edit')->name('devis.edit');
    Route::put('/devis/{id}',        'DevisController@update')->name('devis.update');
    Route::get('/devis/{id}/show', 'DevisController@show')->name('devis.show');
    
    Route::delete('devis/{id}', 'DevisController@destroy')->name('devis.destroy');
   
    Route::post('devis/envoyer-validation/{id}', 'DevisController@envoyerValidation')->name('devis.envoyer_validation');
    Route::post('devis/annuler-envoi/{id}', 'DevisController@annulerEnvoi')->name('devis.annuler_envoi');
    Route::post('devis/annuler-refus/{id}', 'DevisController@annulerRefus')->name('devis.annuler_refus');
    Route::post('devis/annuler-validation/{id}', 'DevisController@annulerValidation')->name('devis.annuler_validation');
    Route::post('devis/export/{montant}', 'DevisController@export_devis')->name('devis.pdf');
    Route::get('devis/print/{id}', 'DevisController@printExisting')->name('devis.print');
    
    // Get patient consumed products (for import to devis)
    Route::get('devis/patient-products/{patientId}', 'DevisController@getPatientProducts')->name('devis.patient_products');
    // Get products by category (AJAX endpoint for product search)
    Route::get('products/search', 'DevisController@searchProducts')->name('products.search');
    // Get product details by ID (AJAX)
    Route::get('products/{id}/details', 'DevisController@getProductDetails')->name('products.details');
    
    // Doctor actions on devis
    Route::post('devis/appliquer-reduction/{id}', 'DevisController@appliquerReduction')->name('devis.appliquer_reduction');
    Route::post('devis/valider/{id}', 'DevisController@valider')->name('devis.valider');
    Route::post('devis/refuser/{id}', 'DevisController@refuser')->name('devis.refuser');

    // Preview routing
    Route::get('print-preview/{type}/{id}', 'PrintPreviewController@show')->name('print.preview');
    Route::post('print-preview/{type}/{id}', 'PrintPreviewController@save')->name('print.preview.save');
    Route::get('print-preview/{type}/{id}/print', 'PrintPreviewController@print')->name('print.preview.print');

    // --- Dossiers clients (CRUD) ---
    Route::get('clients',                        'ClientController@index')           ->name('clients.index');
    Route::get('clients/create',                 'ClientController@create')          ->name('clients.create');
    Route::post('clients',                       'ClientController@store')           ->name('clients.store');
    Route::patch('clients/{client}',             'ClientController@update')          ->name('clients.update');
    Route::delete('clients/{client}',            'ClientController@destroy')         ->name('clients.destroy');

    // --- Générer une facture depuis un dossier client ---
    Route::get('clients/{id}/generer-facture',   'ClientController@generate_client') ->name('clientP.pdf');

    // --- Factures clients ---
    Route::get('factures-client',                'ClientController@indexFactures')   ->name('factures.client');
    // --- PDF facture individuelle ---
    Route::get('facture-client/{id}/pdf',        'ClientController@exportPdf')       ->name('factures.client_pdf');
    // --- Bilan journalier PDF ---
    Route::post('bilan-clientexterne',           'ClientController@exportBilan')     ->name('bilan_clientexterne.pdf');

    Route::get('premedication-adaptation-traitement/{patient}', 'AnesthesisteController@Premdication_Traitement')->name('premedication_adaptation.index');
    Route::post('visite-pre-anesthesique', 'AnesthesisteController@VisitePreanesthesiqueStore')->name('visite_preanesthesique.store');
    Route::post('premedication-consigne-preparation', 'AnesthesisteController@PremedicationConsignePreparationStore')->name('premedication_consigne_preparation.store');
    Route::post('traitement-hospitalisation', 'AnesthesisteController@TraitementHospitalisationStore')->name('traitement_hospitalisation.store');
    Route::post('adaptation-traitement', 'AnesthesisteController@AdaptationTraitementPersoStore')->name('adaptation_traitement.store');

    // Observations médicales
    Route::get('observations-medicales/{patient}', 'ChirurgienController@AbservationMedicaleCreate')->name('observations_medicales.index');
    Route::post('observations-medicales', 'ChirurgienController@AbservationMedicaleStore')->name('observations_medicales.store');
    Route::get('observations-medicales/{id}/edit', 'ChirurgienController@AbservationMedicaleEdit')->name('observations_medicales.edit');
    Route::put('observations-medicales/{id}', 'ChirurgienController@AbservationMedicaleUpdate')->name('observations_medicales.update');
    Route::delete('observations-medicales/{id}', 'ChirurgienController@AbservationMedicaleDestroy')->name('observations_medicales.destroy');

    Route::get('fiches-consommables/{patient}', 'PatientsController@FicheConsommableCreate')->name('fiche_consommable.index');
    Route::post('fiches-consommables', 'PatientsController@FicheConsommableStore')->name('fiche_consommable.store');
    Route::put('fiches-consommables/{consommable}', 'PatientsController@FicheConsommableUpdate')->name('fiche_consommable.update');
    Route::delete('fiches-consommables/{consommable}', 'PatientsController@FicheConsommableDestroy')->name('fiche_consommable.destroy');
   
    Route::get('autocomplete', 'PatientsController@Autocomplete')->name('autocomplete');
    Route::get('surveillance-rapproche/{patient}', 'ParametresController@SurveillanceRapprocheParametre')->name('surveillance_rapproche.index');
    Route::get('surveillance-rapproche-details/{patient}', 'ParametresController@IndexSurveillanceRapprocheParametre')->name('surveillance_rapproche');
    Route::get('parametres-patients/{patient}', 'ParametresController@IndexParametrePatient')->name('fiche_parametre.index');

    Route::get('fiche-prescriptions-medicales/patient/{patient}', 'FichePrescriptionMedicaleController@index')->name('fiche.prescription_medicale.index');
    // Store patient-level information (allergie, regime, etc.)
    Route::post('fiche-prescriptions-medicales/patient/{patient}', 'FichePrescriptionMedicaleController@store')->name('fiche.prescription_medicale.store');
    // Store new prescription (CHANGED: removed fiche/{fiche} parameter)
    Route::post('prescriptions-medicales', 'FichePrescriptionMedicaleController@prescriptionMedicaleStore')->name('prescription_medicale.store');  
    // Edit prescription
    Route::get('prescriptions-medicales/{prescription}/edit', 'FichePrescriptionMedicaleController@edit')->name('prescription_medicale.edit');  
    // Update prescription
    Route::put('prescriptions-medicales/{prescription}', 'FichePrescriptionMedicaleController@update')->name('prescription_medicale.update'); 
    // Store administration record (nurse giving medication)
    Route::post('prescriptions-medicales/{id}/Admin-PM', 'FichePrescriptionMedicaleController@AdminPMStore')->name('admin.prescription_medicale.store');
    // Optional: Delete prescription
    Route::delete('prescriptions-medicales/{prescription}', 'FichePrescriptionMedicaleController@destroy')->name('prescription_medicale.destroy');
    
    Route::get('surveillance-details/{patient}', 'ParametresController@IndexSurveillanceScore')->name('surveillance_score.index');
    Route::post('surveillance-score', 'ParametresController@SurveillanceScoreStore')->name('surveillance_score.store');

    Route::post('surveillance-rapproche-parametres', 'ParametresController@SurveillanceRapprocheStore')->name('surveillance_rapproche_param');

    Route::get('surveillance-post-anesthesique/{patient}', 'AnesthesisteController@IndexSurveillancePostAnesthesise')->name('surveillance_post_anesthesise.index');
    Route::post('surveillance-post-anesthesique', 'AnesthesisteController@SurveillancePostAnesthesiseStore')->name('surveillance_post_anesthesise.store');
    Route::put('surveillance-post-anesthesique/{surveillancePostAnesthesique}', 'AnesthesisteController@SurveillancePostAnesthesiseUpdate')->name('surveillance_post_anesthesise.update');

    Route::post('soins-infirmier', 'PatientsController@SoinsInfirmierStore')->name('soins_infirmiers.store');

    Route::get('imagerie/create/{patient}', 'ImagerieController@create')->name('imageries.create');
    Route::post('imagerie', 'ImagerieController@store')->name('imageries.store');
    Route::get('imagerie_examens/{id}','ImagerieController@export_imageries')->name('imageries_examens.pdf');

    Route::post('active-licence', 'AdminController@ActiveLicence')->name('active_licence_key');

    Route::get('consultationsdesuivi/create/{patient}', 'ConsultationSuiviController@create')->name('consultationsdesuivi.create');
    Route::get('consultationsdesuivi/edit/{patient}', 'ConsultationSuiviController@edit')->name('consultationsdesuivi.edit');
    Route::get('consultationsdesuivi/{patient}', 'ConsultationSuiviController@show')->name('consultationsdesuivi.show');
    Route::post('consultationsdesuivi', 'ConsultationSuiviController@store')->name('consultationsdesuivi.store');

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  rapport des activites des utilisateurs

    // Liste de tous les utilisateurs actifs sur la période
    Route::get('rapports', 'RapportController@index')->name('rapports.index');
    
    // Détail d'un utilisateur (vue web)
    Route::get('rapports/{user}', 'RapportController@show')->name('rapports.show');
    
    // PDF imprimable pour un utilisateur
    Route::get('rapports/{user}/pdf', 'RapportController@pdf')->name('rapports.show.pdf');
    
    // Export PDF global (liste complète)
    Route::get('rapports-export', 'RapportController@exportPdf')->name('rapports.export');


    /**
     * ══════════════════════════════════════════════════════════════════════
     *  LABORATOIRE MODULE (Bons d'examens biologiques)
     * ══════════════════════════════════════════════════════════════════════
     *
     * Role access summary:
     *   Laborantin (10)           → full CRUD + export
     *   Médecin (2) / Gestionnaire (3) → read-only (index, show, patient_history)
     *   Admin (1)                 → everything (via policy before())
     *
     * All authorisation is enforced inside LaboratoireController via
     * $this->authorize('laboratoire', ...) and $this->authorize('laboratoireWrite', ...)
     * defined in PatientPolicy.
     */

    // ── Core CRUD ──────────────────────────────────────────────────────────────

     // ── Results by discipline ─────────────────────────────────────
    // Place BEFORE the generic 'laboratoire/{laboratoire}' show route
    // so the 'resultats-discipline' segment isn't captured as an ID.

    Route::get('laboratoire/resultats-discipline',
        'LaboratoireController@resultsByDiscipline')
        ->name('laboratoire.results_by_discipline');

    // List all lab exams (filtered by role)
    Route::get('laboratoire', 'LaboratoireController@index')
        ->name('laboratoire.index');

    // Registration form — Phase 1 Pré-analytique (no patient pre-selected)
    Route::get('laboratoire/create', 'LaboratoireController@create')
        ->name('laboratoire.create');

    // Registration form — Phase 1 with a patient already selected
    Route::get('laboratoire/create/{patient}', 'LaboratoireController@create')
        ->name('laboratoire.create.patient');

    // Store the new lab exam (Phase 1)
    Route::post('laboratoire', 'LaboratoireController@store')
        ->name('laboratoire.store');

    // Results entry form — Phase 2 Analytique + Phase 3 validation
    Route::get('laboratoire/{laboratoire}/edit', 'LaboratoireController@edit')
        ->name('laboratoire.edit');

    // Save / validate / mark as remis (Phase 2 + 3)
    Route::patch('laboratoire/{laboratoire}', 'LaboratoireController@update')
        ->name('laboratoire.update');

    // Read-only detail view
    Route::get('laboratoire/{laboratoire}', 'LaboratoireController@show')
        ->name('laboratoire.show');

    // Archive (soft-delete) — Admin only
    Route::delete('laboratoire/{laboratoire}', 'LaboratoireController@destroy')
        ->name('laboratoire.destroy');

    // ── Patient history ────────────────────────────────────────────────────────
    // All lab results for one patient (timeline)
    Route::get('laboratoire/patient/{patient}/historique', 'LaboratoireController@patientHistory')
        ->name('laboratoire.patient.history');


    Route::get(
    'laboratoire/patient/{patient}/prescription-tests',
    'LaboratoireController@prescriptionTestsForPatient'
)->name('laboratoire.patient.prescription_tests');

    // ── Report / Export ────────────────────────────────────────────────────────
    // Redirect to print.preview (uses your existing print infrastructure)
    Route::get('laboratoire/{laboratoire}/export', 'LaboratoireController@exportReport')
        ->name('laboratoire.export');

    // Direct printable/PDF blade (called by print.preview with type=laboratoire)
    Route::get('laboratoire/{laboratoire}/rapport', 'LaboratoireController@printReport')
        ->name('laboratoire.rapport');

    // Patient quick-info for laboratoire create form (auto-fill prescripteur)
    Route::get('api/patients/{patient}/info', 'LaboratoireController@patientInfo')
        ->name('api.patient.info');

    // Bon receipt / request slip
    Route::get('laboratoire/{laboratoire}/bon', 'LaboratoireController@bonReceipt')
        ->name('laboratoire.bon');

    // Additional validation/remittance endpoints
    Route::post('laboratoire/{id}/valider',  'LaboratoireController@valider')->name('laboratoire.valider');
    Route::post('laboratoire/{id}/remettre', 'LaboratoireController@remettre')->name('laboratoire.remettre');


    // ── Tariff catalog (Admin CRUD + read-only for Lab/Sec) ───────
    // Route::get ('tarifs-laboratoire',
    //     'TarifLaboratoireController@index')   ->name('tarifs_labo.index');

    // Route::get ('tarifs-laboratoire/create',
    //     'TarifLaboratoireController@create')  ->name('tarifs_labo.create');

    // Route::post('tarifs-laboratoire',
    //     'TarifLaboratoireController@store')   ->name('tarifs_labo.store');

    // Route::get ('tarifs-laboratoire/{tarif}/edit',
    //     'TarifLaboratoireController@edit')    ->name('tarifs_labo.edit');

    // Route::patch('tarifs-laboratoire/{tarif}',
    //     'TarifLaboratoireController@update')  ->name('tarifs_labo.update');

    // Route::post('tarifs-laboratoire/{tarif}/toggle',
    //     'TarifLaboratoireController@toggle')  ->name('tarifs_labo.toggle');

    // Route::delete('tarifs-laboratoire/{tarif}',
    //     'TarifLaboratoireController@destroy') ->name('tarifs_labo.destroy');


    Route::get ('tarifs-laboratoire', 'TarifLaboratoireController@index')    ->name('tarifs_labo.index');
    Route::get ('tarifs-laboratoire/create', 'TarifLaboratoireController@create')   ->name('tarifs_labo.create');
    Route::post('tarifs-laboratoire', 'TarifLaboratoireController@store')    ->name('tarifs_labo.store');
    Route::get ('tarifs-laboratoire/{tarif}/edit', 'TarifLaboratoireController@edit')     ->name('tarifs_labo.edit');
    Route::patch('tarifs-laboratoire/{tarif}', 'TarifLaboratoireController@update')   ->name('tarifs_labo.update');
    Route::delete('tarifs-laboratoire/{tarif}', 'TarifLaboratoireController@destroy')  ->name('tarifs_labo.destroy');
    Route::post('tarifs-laboratoire/{tarif}/toggle', 'TarifLaboratoireController@toggle')   ->name('tarifs_labo.toggle');




    // ── Sections (admin manages discipline categories) ─────────────────────────
    Route::get ('sections-laboratoire',                    'SectionLaboratoireController@index')  ->name('sections_labo.index');
    Route::get ('sections-laboratoire/create',             'SectionLaboratoireController@create') ->name('sections_labo.create');
    Route::post('sections-laboratoire',                    'SectionLaboratoireController@store')  ->name('sections_labo.store');
    Route::get ('sections-laboratoire/{section}/edit',     'SectionLaboratoireController@edit')   ->name('sections_labo.edit');
    Route::patch('sections-laboratoire/{section}',         'SectionLaboratoireController@update') ->name('sections_labo.update');
    Route::delete('sections-laboratoire/{section}',        'SectionLaboratoireController@destroy')->name('sections_labo.destroy');
    Route::post('sections-laboratoire/{section}/toggle',   'SectionLaboratoireController@toggle') ->name('sections_labo.toggle');
    Route::post('sections-laboratoire/reorder',            'SectionLaboratoireController@reorder')->name('sections_labo.reorder');




});
Route::get('/debug/examens-liste', function () {
    $examens = \App\Models\FactureConsultation::with(['patient', 'lignes'])
        ->where(function($q){
            $q->where('motif', 'examen')
              ->orWhereHas('lignes', fn($q2)=>$q2->where('type_acte', 'like', '%examen%'));
        })
        ->latest()
        ->paginate(50);
    return view('admin.factures.liste_examens', compact('examens'));
})->name('debug.examens.liste');

