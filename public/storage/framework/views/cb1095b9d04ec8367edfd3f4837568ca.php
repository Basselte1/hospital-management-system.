

<?php $__env->startSection('title', 'CMCU | Nouvelle visite patient'); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* Style pour le champ de recherche patient */
    .select2-container--default .select2-selection--single {
        height: 45px !important;
        padding: 6px 12px !important;
        border: 2px solid #ced4da !important;
        border-radius: 0.375rem !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 30px !important;
        color: #495057 !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #28a745 !important;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
    }

    /* Style des résultats */
    .select2-results__option {
        padding: 8px 12px !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #28a745 !important;
        color: white !important;
    }

    /* Badge pour numéro de dossier */
    .badge-dossier {
        background-color: #007bff;
        color: white;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 0.85em;
        font-weight: 600;
        margin-right: 8px;
    }

    .patient-name {
        font-weight: 500;
        color: #333;
    }
    
    .patient-info-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #e7f5e9;
        border: 1px solid #28a745;
        border-radius: 0.375rem;
        margin-top: 0.5rem;
        font-weight: 500;
        color: #155724;
    }
    
    .patient-info-badge i {
        color: #28a745;
        margin-right: 0.5rem;
    }
</style>

<body>
    <div class="se-pre-con"></div>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="container-fluid px-4 py-4">
            <!-- En-tête -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">
                                <i class="fas fa-plus-circle text-success me-2"></i>
                                Nouvelle visite patient
                            </h2>
                            <p class="text-muted mb-0">
                                Enregistrer une nouvelle visite pour un patient existant
                            </p>
                        </div>
                        <a href="<?php echo e(route('patient-visits.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alert info -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Important :</strong> Ce formulaire est destiné aux patients <strong>déjà enregistrés</strong> dans le système. 
                        Pour un nouveau patient, veuillez utiliser le formulaire d'ajout de patient classique.
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-file-medical-alt me-2"></i>Informations de la visite
                            </h5>
                            <small><i class="fas fa-info-circle me-1"></i>Les champs marqués d'une étoile (<span class="text-warning">*</span>) sont obligatoires</small>
                        </div>

                        <div class="card-body p-4">
                            <form method="POST" action="<?php echo e(route('patient-visits.store')); ?>" id="visitForm">
                                <?php echo csrf_field(); ?>

                                  
                                <!-- Section 1: Recherche du patient -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-user-search me-2"></i>Sélection du patient
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="patient_id" class="form-label fw-semibold">
                                                Rechercher un patient <span class="text-danger">*</span>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-lightbulb me-1"></i>
                                                    Tapez le nom, prénom ou numéro de dossier du patient
                                                </small>
                                            </label>
                                            
                                            <!-- Select2 pour la recherche de patients -->
                                            <select 
                                                name="patient_id" 
                                                id="patient_id" 
                                                class="form-control" 
                                                required
                                                style="width: 100%;"
                                            >
                                                <option value="">-- Sélectionnez un patient --</option>
                                                <?php if(isset($preselectedPatient) && $preselectedPatient): ?>
                                                    <option value="<?php echo e($preselectedPatient->id); ?>" selected>
                                                        CMCU-<?php echo e($preselectedPatient->numero_dossier); ?> | <?php echo e($preselectedPatient->name); ?> <?php echo e($preselectedPatient->prenom); ?>

                                                    </option>
                                                <?php endif; ?>
                                            </select>

                                            <!-- Affichage des infos du patient sélectionné -->
                                            <div id="selected_patient_info" style="display: none;">
                                                <div class="patient-info-badge">
                                                    <i class="fas fa-check-circle"></i>
                                                    Patient sélectionné : <strong id="patient_display_name"></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="medecin_r" class="form-label fw-semibold">
                                                Médecin traitant <span class="text-danger">*</span>
                                            </label>
                                             <select class="form-select" name="medecin_r" id="medecin_r" required>
                                                <option value="">Sélectionnez un médecin</option>
                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specialite => $medecins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <optgroup label="<?php echo e($specialite); ?>">
                                                    <?php $__currentLoopData = $medecins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(is_object($user)): ?>
                                                    <option value="<?php echo e($user->name); ?> <?php echo e($user->prenom); ?>" <?php echo e(old("medecin_r") == "$user->name $user->prenom" ? "selected" : ""); ?>>
                                                        Dr. <?php echo e($user->name); ?> <?php echo e($user->prenom); ?>

                                                    </option>
                                                    <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2: Motif de consultation - AMÉLIORÉ -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-stethoscope me-2"></i>Motif Medical
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="motif" class="form-label fw-semibold">
                                                Type de motif <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" name="motif" id="motif" required>
                                                <option value="">-- Sélectionnez un type --</option>
                                                <option value="Consultation" <?php echo e(old('motif') == 'Consultation' ? 'selected' : ''); ?>>Consultation</option>
                                                <option value="Acte" <?php echo e(old('motif') == 'Acte' ? 'selected' : ''); ?>>Acte</option>
                                                <option value="Examen" <?php echo e(old('motif') == 'Examen' ? 'selected' : ''); ?>>Examen</option>
                                                <option value="Autres" <?php echo e(old('motif') == 'Autres' ? 'selected' : ''); ?>>Autres</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="details_motif" id="label_details_motif" class="form-label fw-semibold">
                                                Détails motif <span class="text-danger">*</span>
                                            </label>
                                            
                                            <!-- Select pour Consultation -->
                                            <select class="form-select motif-field" id="details_motif_consultation" name="details_motif_consultation" style="display:none;">
                                                <option value="">-- Sélectionnez une consultation --</option>
                                                <option value="Consultation générale" <?php echo e(old('details_motif') == 'Consultation générale' ? 'selected' : ''); ?>>Consultation générale</option>
                                                <option value="Consultation chirurgicale" <?php echo e(old('details_motif') == 'Consultation chirurgicale' ? 'selected' : ''); ?>>Consultation chirurgicale</option>
                                                <option value="Consultation anesthésiste" <?php echo e(old('details_motif') == 'Consultation anesthésiste' ? 'selected' : ''); ?>>Consultation anesthésiste</option>
                                                <option value="Consultation de suivi" <?php echo e(old('details_motif') == 'Consultation de suivi' ? 'selected' : ''); ?>>Consultation de suivi</option>
                                                <option value="Consultation d'urgence" <?php echo e(old('details_motif') == 'Consultation d\'urgence' ? 'selected' : ''); ?>>Consultation d'urgence</option>
                                            </select>

                                            <!-- Select pour Acte -->
                                            <select class="form-select motif-field" id="details_motif_acte" name="details_motif_acte" style="display:none;">
                                                <option value="">-- Sélectionnez un acte --</option>
                                                <optgroup label="Actes Urologiques">
                                                    <option value="Cystoscopie" <?php echo e(old('details_motif') == 'Cystoscopie' ? 'selected' : ''); ?>>Cystoscopie</option>
                                                    <option value="Biopsie prostatique" <?php echo e(old('details_motif') == 'Biopsie prostatique' ? 'selected' : ''); ?>>Biopsie prostatique</option>
                                                    <option value="Circoncision" <?php echo e(old('details_motif') == 'Circoncision' ? 'selected' : ''); ?>>Circoncision</option>
                                                    <option value="Vasectomie" <?php echo e(old('details_motif') == 'Vasectomie' ? 'selected' : ''); ?>>Vasectomie</option>
                                                    <option value="Lithotripsie" <?php echo e(old('details_motif') == 'Lithotripsie' ? 'selected' : ''); ?>>Lithotripsie</option>
                                                    <option value="Urétéroscopie" <?php echo e(old('details_motif') == 'Urétéroscopie' ? 'selected' : ''); ?>>Urétéroscopie</option>
                                                    <option value="Néphrectomie" <?php echo e(old('details_motif') == 'Néphrectomie' ? 'selected' : ''); ?>>Néphrectomie</option>
                                                    <option value="Prostatectomie" <?php echo e(old('details_motif') == 'Prostatectomie' ? 'selected' : ''); ?>>Prostatectomie</option>
                                                    <option value="Cure d'hydrocèle" <?php echo e(old('details_motif') == 'Cure d\'hydrocèle' ? 'selected' : ''); ?>>Cure d'hydrocèle</option>
                                                    <option value="Pose de sonde JJ" <?php echo e(old('details_motif') == 'Pose de sonde JJ' ? 'selected' : ''); ?>>Pose de sonde JJ</option>
                                                </optgroup>
                                                <optgroup label="Actes Chirurgicaux Généraux">
                                                    <option value="Laparotomie exploratrice" <?php echo e(old('details_motif') == 'Laparotomie exploratrice' ? 'selected' : ''); ?>>Laparotomie exploratrice</option>
                                                    <option value="Appendicectomie" <?php echo e(old('details_motif') == 'Appendicectomie' ? 'selected' : ''); ?>>Appendicectomie</option>
                                                    <option value="Cholécystectomie" <?php echo e(old('details_motif') == 'Cholécystectomie' ? 'selected' : ''); ?>>Cholécystectomie</option>
                                                    <option value="Hernioplastie" <?php echo e(old('details_motif') == 'Hernioplastie' ? 'selected' : ''); ?>>Hernioplastie</option>
                                                    <option value="Cure de varicocèle" <?php echo e(old('details_motif') == 'Cure de varicocèle' ? 'selected' : ''); ?>>Cure de varicocèle</option>
                                                </optgroup>
                                                <optgroup label="Actes Endoscopiques">
                                                    <option value="Endoscopie digestive" <?php echo e(old('details_motif') == 'Endoscopie digestive' ? 'selected' : ''); ?>>Endoscopie digestive</option>
                                                    <option value="Coloscopie" <?php echo e(old('details_motif') == 'Coloscopie' ? 'selected' : ''); ?>>Coloscopie</option>
                                                    <option value="Gastroscopie" <?php echo e(old('details_motif') == 'Gastroscopie' ? 'selected' : ''); ?>>Gastroscopie</option>
                                                </optgroup>
                                                <option value="Autre acte" <?php echo e(old('details_motif') == 'Autre acte' ? 'selected' : ''); ?>>Autre acte (à préciser)</option>
                                            </select>

                                            <!-- Select pour Examen -->
                                            <select class="form-select motif-field" id="details_motif_examen" name="details_motif_examen" style="display:none;">
                                                <option value="">-- Sélectionnez un examen --</option>
                                                
                                                <optgroup label="Examens de Biologie">
                                                    <option value="Hématologie" <?php echo e(old('details_motif') == 'Hématologie' ? 'selected' : ''); ?>>Hématologie (NFS, Hémogramme)</option>
                                                    <option value="Biochimie" <?php echo e(old('details_motif') == 'Biochimie' ? 'selected' : ''); ?>>Biochimie (Glycémie, Créatinine, Urée)</option>
                                                    <option value="Hémostase" <?php echo e(old('details_motif') == 'Hémostase' ? 'selected' : ''); ?>>Hémostase (TP, TCA, INR)</option>
                                                    <option value="Hormonologie" <?php echo e(old('details_motif') == 'Hormonologie' ? 'selected' : ''); ?>>Hormonologie (PSA, Testostérone)</option>
                                                    <option value="Marqueurs tumoraux" <?php echo e(old('details_motif') == 'Marqueurs tumoraux' ? 'selected' : ''); ?>>Marqueurs tumoraux</option>
                                                    <option value="Bactériologie" <?php echo e(old('details_motif') == 'Bactériologie' ? 'selected' : ''); ?>>Bactériologie (ECBU, Hémoculture)</option>
                                                    <option value="Sérologie" <?php echo e(old('details_motif') == 'Sérologie' ? 'selected' : ''); ?>>Sérologie (VIH, Hépatites)</option>
                                                    <option value="Analyse d'urines" <?php echo e(old('details_motif') == 'Analyse d\'urines' ? 'selected' : ''); ?>>Analyse d'urines</option>
                                                    <option value="Spermiologie" <?php echo e(old('details_motif') == 'Spermiologie' ? 'selected' : ''); ?>>Spermiologie (Spermogramme)</option>
                                                </optgroup>
                                                
                                                <optgroup label="Examens d'Imagerie">
                                                    <option value="Radiographie" <?php echo e(old('details_motif') == 'Radiographie' ? 'selected' : ''); ?>>Radiographie (Radio standard)</option>
                                                    <option value="Échographie" <?php echo e(old('details_motif') == 'Échographie' ? 'selected' : ''); ?>>Échographie (Abdominale, Pelvienne, Rénale)</option>
                                                    <option value="Scanner" <?php echo e(old('details_motif') == 'Scanner' ? 'selected' : ''); ?>>Scanner (TDM, CT-Scan)</option>
                                                    <option value="IRM" <?php echo e(old('details_motif') == 'IRM' ? 'selected' : ''); ?>>IRM (Imagerie par Résonance Magnétique)</option>
                                                    <option value="Scintigraphie" <?php echo e(old('details_motif') == 'Scintigraphie' ? 'selected' : ''); ?>>Scintigraphie</option>
                                                    <option value="Échographie Doppler" <?php echo e(old('details_motif') == 'Échographie Doppler' ? 'selected' : ''); ?>>Échographie Doppler</option>
                                                </optgroup>
                                                
                                                <optgroup label="Examens Radiologiques spécifiques">
                                                    <option value="UIV" <?php echo e(old('details_motif') == 'UIV' ? 'selected' : ''); ?>>UIV (Urographie Intraveineuse)</option>
                                                    <option value="Urétrographie" <?php echo e(old('details_motif') == 'Urétrographie' ? 'selected' : ''); ?>>Urétrographie</option>
                                                    <option value="Cystographie" <?php echo e(old('details_motif') == 'Cystographie' ? 'selected' : ''); ?>>Cystographie</option>
                                                </optgroup>
                                                
                                                <option value="Autre examen" <?php echo e(old('details_motif') == 'Autre examen' ? 'selected' : ''); ?>>Autre examen (à préciser)</option>
                                            </select>

                                            <!-- Input texte pour Autres -->
                                            <input type="text" class="form-control motif-field" id="details_motif_autres" name="details_motif_autres" placeholder="Précisez le motif" value="<?php echo e(old('details_motif')); ?>" style="display:none;">

                                            <!-- Champ caché pour stocker la valeur finale -->
                                            <input type="hidden" name="details_motif" id="details_motif_hidden" value="<?php echo e(old('details_motif')); ?>">
                                        </div>

                                        <div class="col-md-6">
                                           <label for="visit_date" class="form-label fw-semibold">
                                                Date de la visite <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="visit_date" name="visit_date" 
                                                value="<?php echo e(old('visit_date', date('Y-m-d'))); ?>"  readonly required>
                                            <?php $__errorArgs = ['visit_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="text-danger mt-1"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="demarcheur" class="form-label fw-semibold">Démarcheur</label>
                                            <select class="form-select" name="demarcheur" id="demarcheur" >
                                                <option value=""  >Aucun</option>
                                                <option <?php echo e(old('demarcheur') == 'DMH' ? 'selected' : 'Aucun'); ?>>DMH</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 3: Informations financières -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-money-bill-wave me-2"></i>Informations financières
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">

                                             <label for="mode_paiement" class="form-label fw-semibold">
                                                Mode de paiement <span class="text-danger">*</span>
                                            </label>
                                            <select name="mode_paiement" id="mode_paiement" class="form-select">
                                                <optgroup label="Monnaie électronique">
                                                    <option value="orange money" <?php echo e(old('mode_paiement') == 'orange money' ? 'selected' : ''); ?>>Orange Money</option>
                                                    <option value="mtn mobile money" <?php echo e(old('mode_paiement') == 'mtn mobile money' ? 'selected' : ''); ?>>MTN Mobile Money</option>
                                                </optgroup>
                                                <optgroup label="Autres moyens">
                                                    <option selected value="espèce">Espèce</option>
                                                    <option value="chèque" <?php echo e(old('mode_paiement') == 'chèque' ? 'selected' : ''); ?>>Chèque</option>
                                                    <option value="virement" <?php echo e(old('mode_paiement') == 'virement' ? 'selected' : ''); ?>>Virement</option>
                                                    <option value="bon de prise en charge" <?php echo e(old('mode_paiement') == 'bon de prise en charge' ? 'selected' : ''); ?>>Bon de prise en charge</option>
                                                    <option value="autre" <?php echo e(old('mode_paiement') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                                                </optgroup>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="montant" class="form-label fw-semibold">
                                                Montant total <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input name="montant" id="montant" class="form-control" value="<?php echo e(old('montant', 0)); ?>" type="number" min="0" step="1" required>
                                                <span class="input-group-text">FCFA</span>
                                            </div>
                                        </div>

                                        <div class="col-md-7 mx-auto">
                                            <label for="avance" class="form-label fw-semibold">
                                                Avance <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input name="avance" id="avance" class="form-control" value="<?php echo e(old('avance', 0)); ?>" type="number" min="0" step="1" required>
                                                <span class="input-group-text">FCFA</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Champs conditionnels pour chèque -->
                                        <div id="cheque_fields" class="col-md-12" style="display: none;">
                                            <div class="card bg-light border-0 mt-2">
                                                <div class="card-body">
                                                    <h6 class="card-title text-secondary mb-3">
                                                        <i class="fas fa-money-check me-2"></i>Informations du chèque
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label for="num_cheque" class="form-label">Numéro du chèque <span class="text-danger">*</span></label>
                                                            <input name="num_cheque" id="num_cheque" class="form-control" value="<?php echo e(old('num_cheque')); ?>" type="text" placeholder="N° du chèque">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="emetteur_cheque" class="form-label">Émetteur <span class="text-danger">*</span></label>
                                                            <input name="emetteur_cheque" id="emetteur_cheque" class="form-control" value="<?php echo e(old('emetteur_cheque')); ?>" type="text" placeholder="Nom de l'émetteur">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="banque_cheque" class="form-label">Banque <span class="text-danger">*</span></label>
                                                            <input name="banque_cheque" id="banque_cheque" class="form-control" value="<?php echo e(old('banque_cheque')); ?>" type="text" placeholder="Nom de la banque">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Champs conditionnels pour bon de prise en charge -->
                                        <div id="bpc_field" class="col-md-12" style="display: none;">
                                            <div class="card bg-light border-0 mt-2">
                                                <div class="card-body">
                                                    <h6 class="card-title text-secondary mb-3">
                                                        <i class="fas fa-file-invoice me-2"></i>Bon de prise en charge
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <label for="emetteur_bpc" class="form-label">Émetteur du bon <span class="text-danger">*</span></label>
                                                            <input name="emetteur_bpc" id="emetteur_bpc" class="form-control" value="<?php echo e(old('emetteur_bpc')); ?>" type="text" placeholder="Nom de l'organisme émetteur">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 4: Assurance -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-shield-alt me-2"></i>Informations d'assurance
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="assurance" class="form-label fw-semibold">Assurance</label>
                                            <input name="assurance" id="assurance" class="form-control" value="<?php echo e(old('assurance')); ?>" type="text" placeholder="Nom de l'assurance (si assuré)">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="numero_assurance" class="form-label fw-semibold">Numéro d'assurance</label>
                                            <input name="numero_assurance" id="numero_assurance" class="form-control" value="<?php echo e(old('numero_assurance')); ?>" type="text" placeholder="N° d'assurance (si assuré)">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="prise_en_charge" class="form-label fw-semibold">Taux de prise en charge</label>
                                            <div class="input-group">
                                                <select class="form-select" name="prise_en_charge" id="prise_en_charge" required>
                                                    <?php $__currentLoopData = range(0, 100); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taux): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php echo e(old('prise_en_charge') == $taux ? 'selected' : ''); ?>><?php echo e($taux); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-percent"></i>
                                                </span>
                                            </div>
                                            <small class="text-muted">Pourcentage de prise en charge par l'assurance</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="row mt-4 pt-3 border-top">
                                    <div class="col-12">
                                        <div class="d-flex gap-1 justify-content-between">
                                            <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-lg btn-outline-secondary px-4">
                                                <i class="fas fa-times me-2"></i>Annuler
                                            </a>
                                            <button type="submit" class="btn btn-lg btn-primary px-5">
                                                <i class="fas fa-save me-2"></i>Enregistrer la visite
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

   
    </div>
</body>

<script>
    /**
     * ✅ INITIALISATION SELECT2 SIMPLE AVEC AJAX
     * Recherche de patients avec autocomplétion
     */
   waitForjQuery ( function() {
    $(document).ready(function() {
        // Initialiser Select2 sur le champ patient_id
        $('#patient_id').select2({
           
            placeholder: 'Tapez pour rechercher un patient...',
            allowClear: true,
            minimumInputLength: 2, // Recherche après 2 caractères
            ajax: {
                url: '<?php echo e(route("patient-visits.search-patients")); ?>', // Route de recherche
                dataType: 'json',
                delay: 300, // Délai avant requête (évite trop de requêtes)
                data: function (params) {
                    return {
                        q: params.term // Le texte tapé par l'utilisateur
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            templateResult: formatPatient, // Format d'affichage dans la liste
            templateSelection: formatPatientSelection, // Format d'affichage une fois sélectionné
            escapeMarkup: function(markup) { 
                return markup; // Permet d'utiliser du HTML
            }
        });

        /**
         * Format d'affichage dans la liste déroulante
         */
        function formatPatient(patient) {
            if (patient.loading) {
                return 'Recherche en cours...';
            }

            if (!patient.id) {
                return patient.text;
            }

            // Affichage avec badge pour numéro de dossier
            var markup = '<div class="select2-result-patient">' +
                '<span class="badge-dossier">CMCU-' + patient.numero_dossier + '</span>' +
                '<span class="patient-name">' + patient.name + ' ' + patient.prenom + '</span>' +
                '</div>';

            return markup;
        }

        /**
         * Format d'affichage une fois le patient sélectionné
         */
        function formatPatientSelection(patient) {
            if (!patient.id) {
                return patient.text;
            }
            
            // Afficher les infos du patient sélectionné
            if (patient.numero_dossier) {
                $('#patient_display_name').text('CMCU-' + patient.numero_dossier + ' | ' + patient.name + ' ' + patient.prenom);
                $('#selected_patient_info').show();
            }

            return 'CMCU-' + patient.numero_dossier + ' | ' + patient.name + ' ' + patient.prenom;
        }

        /**
         * Gestion du changement de sélection
         */
        $('#patient_id').on('select2:select', function (e) {
            var data = e.params.data;
            $('#patient_display_name').text('CMCU-' + data.numero_dossier + ' | ' + data.name + ' ' + data.prenom);
            $('#selected_patient_info').show();
        });

        /**
         * Gestion de la désélection
         */
        $('#patient_id').on('select2:clear', function (e) {
            $('#selected_patient_info').hide();
        });

        /**
         * ✅ PRÉ-SÉLECTION AUTOMATIQUE SI PATIENT PASSÉ EN PARAMÈTRE
         * Vérifie si un patient est déjà pré-rempli (depuis une autre page)
         */
        <?php if(isset($preselectedPatient) && $preselectedPatient): ?>
            // Si un patient est pré-sélectionné, afficher son badge
            $('#patient_display_name').text('CMCU-<?php echo e($preselectedPatient->numero_dossier); ?> | <?php echo e($preselectedPatient->name); ?> <?php echo e($preselectedPatient->prenom); ?>');
            $('#selected_patient_info').show();
        <?php endif; ?>
    });

    /**
     * Système de sélection en cascade pour les motifs
     * Gère l'affichage dynamique des champs selon le type de motif sélectionné
     */
    document.addEventListener('DOMContentLoaded', function() {
        const motifSelect = document.getElementById('motif');
        const labelDetailsMotif = document.getElementById('label_details_motif');
        
        // Tous les champs de détails motif
        const detailsConsultation = document.getElementById('details_motif_consultation');
        const detailsActe = document.getElementById('details_motif_acte');
        const detailsExamen = document.getElementById('details_motif_examen');
        const detailsAutres = document.getElementById('details_motif_autres');
        const detailsHidden = document.getElementById('details_motif_hidden');

        /**
         * Fonction pour cacher tous les champs de détails
         */
        function hideAllDetailsFields() {
            detailsConsultation.style.display = 'none';
            detailsActe.style.display = 'none';
            detailsExamen.style.display = 'none';
            detailsAutres.style.display = 'none';
            
            // Retirer l'attribut required de tous
            detailsConsultation.removeAttribute('required');
            detailsActe.removeAttribute('required');
            detailsExamen.removeAttribute('required');
            detailsAutres.removeAttribute('required');
        }

        /**
         * Fonction pour gérer le changement de type de motif
         */
        function handleMotifChange() {
            const motifValue = motifSelect.value;
            hideAllDetailsFields();

            switch(motifValue) {
                case 'Consultation':
                    labelDetailsMotif.innerHTML = 'Type de consultation <span class="text-danger">*</span>';
                    detailsConsultation.style.display = 'block';
                    detailsConsultation.setAttribute('required', 'required');
                    break;

                case 'Acte':
                    labelDetailsMotif.innerHTML = 'Type d\'acte <span class="text-danger">*</span>';
                    detailsActe.style.display = 'block';
                    detailsActe.setAttribute('required', 'required');
                    break;

                case 'Examen':
                    labelDetailsMotif.innerHTML = 'Type d\'examen <span class="text-danger">*</span>';
                    detailsExamen.style.display = 'block';
                    detailsExamen.setAttribute('required', 'required');
                    break;

                case 'Autres':
                    labelDetailsMotif.innerHTML = 'Détails motif <span class="text-danger">*</span>';
                    detailsAutres.style.display = 'block';
                    detailsAutres.setAttribute('required', 'required');
                    break;

                default:
                    labelDetailsMotif.innerHTML = 'Détails motif <span class="text-danger">*</span>';
            }
        }

        /**
         * Synchroniser la valeur sélectionnée avec le champ caché avant soumission
         */
        function syncDetailsMotif() {
            const motifValue = motifSelect.value;
            let finalValue = '';

            switch(motifValue) {
                case 'Consultation':
                    finalValue = detailsConsultation.value;
                    break;
                case 'Acte':
                    finalValue = detailsActe.value;
                    break;
                case 'Examen':
                    finalValue = detailsExamen.value;
                    break;
                case 'Autres':
                    finalValue = detailsAutres.value;
                    break;
            }

            detailsHidden.value = finalValue;
        }

        // Event listener sur le changement de motif
        motifSelect.addEventListener('change', handleMotifChange);

        // Event listeners pour synchroniser les valeurs
        detailsConsultation.addEventListener('change', syncDetailsMotif);
        detailsActe.addEventListener('change', syncDetailsMotif);
        detailsExamen.addEventListener('change', syncDetailsMotif);
        detailsAutres.addEventListener('input', syncDetailsMotif);

        // Synchroniser avant la soumission du formulaire
        document.getElementById('visitForm').addEventListener('submit', function(e) {
            syncDetailsMotif();
            
            // Validation finale
            if (!detailsHidden.value) {
                e.preventDefault();
                alert('Veuillez sélectionner ou saisir les détails du motif.');
                return false;
            }
        });

        // Initialiser l'affichage au chargement de la page (pour gérer old() values)
        <?php if(old('motif')): ?>
            handleMotifChange();
            syncDetailsMotif();
        <?php endif; ?>
    });

    /**
     * Gérer l'affichage des champs selon le mode de paiement
     */
    document.addEventListener('DOMContentLoaded', function() {
        const modePaiementSelect = document.getElementById('mode_paiement');
        const chequeFields = document.getElementById('cheque_fields');
        const bpcField = document.getElementById('bpc_field');

        function togglePaymentFields() {
            const modePaiement = modePaiementSelect.value;

            // Cacher tous les champs
            chequeFields.style.display = 'none';
            bpcField.style.display = 'none';

            // Retirer les valeurs si le mode n'est pas sélectionné
            if (modePaiement !== 'chèque') {
                document.getElementById('num_cheque').value = '';
                document.getElementById('emetteur_cheque').value = '';
                document.getElementById('banque_cheque').value = '';
                document.getElementById('num_cheque').removeAttribute('required');
                document.getElementById('emetteur_cheque').removeAttribute('required');
                document.getElementById('banque_cheque').removeAttribute('required');
            }

            if (modePaiement !== 'bon de prise en charge') {
                document.getElementById('emetteur_bpc').value = '';
                document.getElementById('emetteur_bpc').removeAttribute('required');
            }

            // Afficher les champs correspondants
            if (modePaiement === 'chèque') {
                chequeFields.style.display = 'block';
                document.getElementById('num_cheque').setAttribute('required', 'required');
                document.getElementById('emetteur_cheque').setAttribute('required', 'required');
                document.getElementById('banque_cheque').setAttribute('required', 'required');
            } else if (modePaiement === 'bon de prise en charge') {
                bpcField.style.display = 'block';
                document.getElementById('emetteur_bpc').setAttribute('required', 'required');
            }
        }

        modePaiementSelect.addEventListener('change', togglePaymentFields);
        
        // Initialiser au chargement
        togglePaymentFields();
    });

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/patient_visits/create.blade.php ENDPATH**/ ?>