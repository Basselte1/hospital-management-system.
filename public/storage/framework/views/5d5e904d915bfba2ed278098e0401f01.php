
<?php $__env->startSection('title', 'CMCU | dossier patient'); ?>
<?php $__env->startSection('content'); ?>

<style>
    .grid-container {
        display: grid;
        grid-gap: 30px 60px;
        grid-template-columns: auto auto auto;
        padding: 10px;
    }

    .grid-item {
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.8);
        padding: 10px;
        font-size: 12px;
        margin-right: 1px;
    }

    .table-sortable tbody tr {
        cursor: move;
    }

    /* Ensure main content doesn't overflow */
    .main-content-area {
        overflow-x: hidden;
        padding: 15px;
        width: 100%;
    }

    /* === Uniform Action Button Styling === */
    .btn-action-fixed {
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.95rem;
        padding: 0 12px;
        border-radius: 8px;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .action-sidebar-mobile {
            margin-top: 20px;
        }
    }

    @media (max-width: 767.98px) {
        .sidebar {
            position: static !important;
            min-height: auto;
            width: 100% !important;
        }

        .btn-action-fixed {
            font-size: 0.85rem;
            height: auto;
            white-space: normal;
        }
    }

    @media (max-width: 576px) {
        .btn-action-fixed {
            font-size: 0.85rem;
            height: auto;
            white-space: normal;
        }
    }
</style>

<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <div class="main-content-area">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
        <div class="container-fluid">
            <!--  -->

            <div class="row mb-3">
                
                <div class="col-12">
                    <?php echo $__env->make('admin.patients.partials.menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-success float-end" title="Retour à la liste des patients">
                        <i class="fas fa-arrow-left"></i> Retour à la liste des patients
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Main Patient Dossier Section - Dynamic width based on sidebar visibility -->
                <div class="col-lg-<?php echo e(auth()->user()->can('med_inf_anes', \App\Models\Patient::class) ? '9' : '12'); ?> col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title text-danger text-center mb-4">
                                DOSSIER PATIENT <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?>

                            </h2>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                                <button class="btn btn-primary" title="Cacher / Afficher les données personnelles du patient" onclick="ShowDetailsPatient()">
                                    <i class="fas fa-id-card"></i> Détails Personnels
                                </button>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier_secretaire', \App\Models\Patient::class)): ?>
                                <a href="<?php echo e(route('dossiers.create', $patient->id)); ?>" class="btn btn-success">
                                    <i class="fas fa-folder-plus"></i> Compléter le dossier
                                </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('secretaire', \App\Models\Patient::class)): ?>
                                <button class="btn btn-primary" title="Modifier le motif et le montant" onclick="ShoweditMotif_montant()">
                                    <i class="fas fa-edit"></i> Editer
                                </button>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
                                <a class="btn btn-primary" href="<?php echo e(route('fiche.prescription_medicale.index', $patient)); ?>" title="Prescriptions médicales">
                                    <i class="fa-solid fa-prescription-bottle-medical"></i> Prescriptions Médicales
                                </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                                
                                    <?php if($dossiers): ?>
                                        <a class="btn btn-primary" href="<?php echo e(route('consultations.create', $patient->id)); ?>" title="Nouvelle consultation du patient pour la prise des paramètres">
                                            <i class="fa-solid fa-notes-medical"></i>Prise De Paramètres
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-primary" href="#" data-bs-placement="top" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-content="Vous devez d'abord compléter le dossier patient !" title="Fiche de prise des paramètres">
                                           <i class="fa-solid fa-notes-medical"></i>Prise De Paramètres
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin_secretaire', \App\Models\Patient::class)): ?>
                                <button class="btn btn-primary" title="Gérer les images scannés des examens" onclick="Showexamen_scannes()">
                                    <i class="fa-solid fa-file-image"></i>Images Scannées
                                </button>
                                <?php endif; ?>
                            </div>

                            <!-- Patient Data Tables -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <?php echo $__env->make('admin.consultations.partials.detail_patient', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php echo $__env->make('admin.consultations.show_consultation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php echo $__env->make('admin.consultations.partials.motif_et_montant', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </table>
                            </div>

                            <?php echo $__env->make('admin.patients.partials.examens_scannes', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            <?php echo $__env->make('admin.patients.patient_visits_widget', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                    
                    <!-- Action Sidebar for secretaire users (shown below main content on mobile/tablet) -->
                    <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('secretaire', \App\Models\Patient::class)): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('med_inf_anes', \App\Models\Patient::class)): ?>
                        <div class="card shadow-sm mt-4">
                            <div class="card-header fw-bold py-3">
                                <small>ACTIONS DISPONIBLES</small>
                            </div>
                            <div class="card-body d-flex flex-wrap gap-2">
                                <a href="<?php echo e(route('dossiers.create', $patient->id)); ?>" class="btn btn-success flex-fill">
                                    <i class="fas fa-folder-plus"></i> Compléter Le Dossier
                                </a>
                                <button class="btn btn-primary flex-fill" title="Modifier le motif et le montant" onclick="ShoweditMotif_montant()">
                                    <i class="fas fa-edit"></i> Editer Motif/Montant
                                </button>
                                <button class="btn btn-primary flex-fill" title="Gérer les images scannés des examens" onclick="Showexamen_scannes()">
                                    <i class="fa-solid fa-file-image"></i> Images Scannées
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?> -->
                </div>

                <!-- Action Sidebar (Right Column) - Only visible for med_inf_anes users -->
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
                <div class="col-lg-3 col-md-12 mb-4 action-sidebar-mobile">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold py-3">
                            <small>DÉTAILS ACTION</small>
                        </div>
                        <div class="card-body p-2">
                            <button type="button" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Liste des ordonnances pour ce patient" data-bs-toggle="modal" data-bs-target="#ordonanceAll">
                                <i class="fas fa-eye"></i> Ordonnances
                            </button>

                            <button type="button" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Liste des examens pour ce patient" data-bs-toggle="modal" data-bs-target="#biologieAll">
                                <i class="fas fa-eye"></i> Examens Biologiques
                            </button>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                            <a href="<?php echo e(route('surveillance_rapproche.index', $patient->id)); ?>" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Surveillance rapprochée des paramètres">
                                <i class="fas fa-eye"></i> Surveillance Rapprochée
                            </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                            <a href="<?php echo e(route('consultations.index_anesthesiste', $patient->id)); ?>" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed">
                                <i class="fas fa-eye"></i> Consultations Anesthésistes
                            </a>
                            <a href="<?php echo e(route('surveillance_rapproche.index', $patient->id)); ?>" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Surveillance rapprochée des paramètres">
                                <i class="fas fa-eye"></i> Surveillance Rapprochée
                            </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                            <a href="<?php echo e(route('surveillance_rapproche.index', $patient->id)); ?>" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Surveillance rapprochée des paramètres">
                                <i class="fas fa-eye"></i> Surveillance Rapprochée
                            </a>
                            <a href="<?php echo e(route('consultations.index_anesthesiste', $patient->id)); ?>" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed">
                                <i class="fas fa-eye"></i> Consultations Anesthésistes
                            </a>
                            <?php endif; ?>
                            
                            <!-- // Added for imagerie exams -->
                            <!-- <button type="button" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Liste des examens pour ce patient" data-bs-toggle="modal" data-bs-target="#imagerieAll" data-bs-whatever="@mdo">
                                <i class="fas fa-eye"></i> Examens Imageries
                            </button>
                            <a href="<?php echo e(route('examens.index')); ?>" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Détails surveillance post-aneshésiste">
                                <i class="fas fa-eye"></i> Résultats d'Examens
                            </a> -->

                            <!-- // End of Imagerie exams addition -->

                            <a href="<?php echo e(route('surveillance_post_anesthesise.index', $patient->id)); ?>" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Détails surveillance post-aneshésiste">
                                <i class="fas fa-eye"></i> Surveillance Post-Anesthésique
                            </a>

                            <button type="button" class="btn btn-primary w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Fiches d'intervention" data-bs-toggle="modal" data-bs-target="#FicheInterventionAll">
                                <i class="fas fa-eye"></i> Fiche d'Intervention
                            </button>

                            <a href="<?php echo e(route('dossiers.create', $patient->id)); ?>" class="btn btn-success w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed">
                                <i class="fas fa-folder-plus"></i>  Compléter Le Dossier
                            </a>

                        
                            <?php if($patient->consultations && $patient->consultations->isNotEmpty()): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                                <a class="btn btn-success w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Imprimer la lettre de sortie" href="<?php echo e(route('print.sortie', $patient->id)); ?>">
                                    <i class="fas fa-print"></i> Lettre De Consultation
                                </a>
                                <button type="button" class="btn btn-success w-100 mb-2 py-3 gap-2 rounded-3 btn-action-fixed" title="Liste de fiches pour ce patient" data-bs-toggle="modal" data-bs-target="#ficheSuiviAll">
                                    <i class="fas fa-eye"></i> Consultation De Suivi
                                </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- MODALS -->
        <?php echo $__env->make('admin.modal.feuille_precription_examen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.detail_premedication_preparation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.ordonance_show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.consultation_show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.index_examen_biologie', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.index_examen_imagerie', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.fiche_intervention_show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
        <?php echo $__env->make('admin.modal.fiche_intervention', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.fiche_intervention_anesthesiste', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.visite_preanesthesique', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.surveillance_post_a', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.fichede_suivi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.modal.surveillance_rapproche_param', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<script>
    function ShowDetailsPatient() {
        var x = document.getElementById("myDIV");
        var y = document.getElementById("editMotifMontform");
        var z = document.getElementById("examens_scannes_form");
        if (y?.style.display === "contents") y.style.display = "none";
        if (z?.style.display === "contents") z.style.display = "none";
        x.style.display = x.style.display === "none" ? "contents" : "none";
    }

    function ShoweditMotif_montant() {
        var x = document.getElementById("editMotifMontform");
        var y = document.getElementById("myDIV");
        var z = document.getElementById("examens_scannes_form");
        if (y?.style.display === "contents") y.style.display = "none";
        if (z?.style.display === "contents") z.style.display = "none";
        x.style.display = x.style.display === "none" ? "contents" : "none";
    }

    function Showexamen_scannes() {
        var x = document.getElementById("editMotifMontform");
        var y = document.getElementById("myDIV");
        var z = document.getElementById("examens_scannes_form");
        var t = document.getElementById("show_consultation");
        if (y?.style.display === "contents") y.style.display = "none";
        if (x?.style.display === "contents") x.style.display = "none";
        if (t?.style.display === "contents") t.style.display = "none";
        z.style.display = z.style.display === "none" ? "contents" : "none";
    }

    document.querySelectorAll('.form-control[type="file"]').forEach(input => {
        input.addEventListener('change', e => {
            const fileName = e.target.files[0]?.name || 'Choose file';
            const label = e.target.nextElementSibling;
            if (label?.classList.contains('form-label')) label.textContent = fileName;
        });
    });

    function handleFiles(files) {
            var imageType = /^image\//;
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (!imageType.test(file.type)) {
                    alert("Veuillez sélectionner une image");
                } else {
                    let form_parent = document.getElementById('preview');
                    let img1 = document.getElementById("img1");
                    let clone_img = img1.cloneNode(false);
                    clone_img.file = file;
                    clone_img.classList.add("obj");
                    form_parent.replaceChild(clone_img, img1);
                    var reader = new FileReader();
                    reader.onload = (function(aImg) {
                        return function(e) {
                            aImg.src = e.target.result;
                        };
                    })(clone_img);
                    reader.readAsDataURL(file);
                }
            }
        }

    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => new bootstrap.Popover(el));
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcu\resources\views/admin/patients/show.blade.php ENDPATH**/ ?>