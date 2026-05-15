
<?php $__env->startSection('title', 'CMCU | Dossier Patient'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('consulter', \App\Models\Patient::class)): ?>
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <div class="tw-mb-5 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div class="tw-flex-1 tw-min-w-0">
                    <?php echo $__env->make('admin.patients.partials.menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <a href="<?php echo e(route('patients.index')); ?>"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour 
                </a>
            </div>

            <?php
                $showSidebar = auth()->user()->can('med_inf_anes', \App\Models\Patient::class)
                            && !auth()->user()->can('adminOnly', \App\Models\Patient::class);
            ?>

            <div class="tw-flex tw-gap-6 tw-flex-col lg:tw-flex-row">

                
                <div class="<?php echo e($showSidebar ? 'lg:tw-flex-1' : 'tw-w-full'); ?>">
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                        
                        <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-folder-open tw-text-white"></i>
                            </div>
                            <div>
                                <h1 class="tw-text-white tw-font-bold tw-text-base tw-mb-0 tw-uppercase">
                                    Dossier Patient <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?>

                                </h1>
                            </div>
                        </div>

                        <div class="tw-p-5">
                            
                            <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-5">

                                <button onclick="ShowDetailsPatient()"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2 tw-border-0 tw-transition-colors">
                                    <i class="fas fa-id-card tw-text-xs"></i> Détails Personnels
                                </button>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier_secretaire', \App\Models\Patient::class)): ?>
                                <a href="<?php echo e(route('dossiers.create', $patient->id)); ?>"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-text-sm tw-font-medium tw-px-3.5 tw-py-2 tw-transition-colors tw-no-underline">
                                    <i class="fas fa-folder-plus tw-text-xs"></i> Compléter le dossier
                                </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('secretaire', \App\Models\Patient::class)): ?>
                                <button onclick="ShoweditMotif_montant()"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-text-sm tw-font-medium tw-px-3.5 tw-py-2 tw-border-0 tw-transition-colors">
                                    <i class="fas fa-edit tw-text-xs"></i> Éditer
                                </button>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('adminOnly', \App\Models\Patient::class)): ?>
                                    <a href="<?php echo e(route('fiche.prescription_medicale.index', $patient)); ?>"
                                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2 tw-transition-colors tw-no-underline">
                                        <i class="fa-solid fa-prescription-bottle-medical tw-text-xs"></i> Prescriptions Médicales
                                    </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                                <?php if($dossiers): ?>
                                    <a href="<?php echo e(route('consultations.create', $patient->id)); ?>"
                                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2 tw-transition-colors tw-no-underline">
                                        <i class="fa-solid fa-notes-medical tw-text-xs"></i> Prise de Paramètres
                                    </a>
                                <?php else: ?>
                                    <a href="#"
                                       data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top"
                                       data-bs-content="Vous devez d'abord compléter le dossier patient !"
                                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 tw-text-slate-400 tw-cursor-not-allowed tw-text-sm tw-font-medium tw-px-3.5 tw-py-2 tw-no-underline">
                                        <i class="fa-solid fa-notes-medical tw-text-xs"></i> Prise de Paramètres
                                    </a>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin_secretaire', \App\Models\Patient::class)): ?>
                                <button onclick="Showexamen_scannes()"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2 tw-border-0 tw-transition-colors">
                                    <i class="fa-solid fa-file-image tw-text-xs"></i> Images Scannées
                                </button>
                                <?php endif; ?>
                            </div>

                            
                            <div class="tw-overflow-x-auto">
                                <table class="table table-hover">
                                    <?php echo $__env->make('admin.consultations.partials.detail_patient', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('adminOnly', \App\Models\Patient::class)): ?>
                                        <?php echo $__env->make('admin.consultations.show_consultation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?>
                                    <?php echo $__env->make('admin.consultations.partials.motif_et_montant', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </table>
                            </div>

                            <?php echo $__env->make('admin.patients.partials.examens_scannes', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php echo $__env->make('admin.patients.patient_visits_widget', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                </div>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('adminOnly', \App\Models\Patient::class)): ?>
                <div class="tw-w-full lg:tw-w-64 tw-shrink-0">
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-sticky tw-top-4">
                        <div class="tw-px-5 tw-py-3.5 tw-border-b tw-border-slate-100 tw-bg-slate-50">
                            <p class="tw-text-xs tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-400 tw-mb-0">
                                <i class="fas fa-th-large tw-mr-1.5"></i> Détails Action
                            </p>
                        </div>
                        <div class="tw-p-3 tw-space-y-2">

                            
                            <button type="button" class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-border-0 tw-transition-colors tw-text-left"
                                data-bs-toggle="modal" data-bs-target="#ordonanceAll">
                                <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Ordonnances
                            </button>

                            
                            <button type="button" class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-border-0 tw-transition-colors tw-text-left"
                                data-bs-toggle="modal" data-bs-target="#biologieAll">
                                <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Examens Biologiques
                            </button>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                            <a href="<?php echo e(route('surveillance_rapproche.index', $patient->id)); ?>"
                               class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Surveillance Rapprochée
                            </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                            <a href="<?php echo e(route('consultations.index_anesthesiste', $patient->id)); ?>"
                               class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Consultations Anesthésistes
                            </a>
                            <a href="<?php echo e(route('surveillance_rapproche.index', $patient->id)); ?>"
                               class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Surveillance Rapprochée
                            </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                            <a href="<?php echo e(route('surveillance_rapproche.index', $patient->id)); ?>"
                               class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Surveillance Rapprochée
                            </a>
                            <a href="<?php echo e(route('consultations.index_anesthesiste', $patient->id)); ?>"
                               class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Consultations Anesthésistes
                            </a>
                            <?php endif; ?>

                            <a href="<?php echo e(route('surveillance_post_anesthesise.index', $patient->id)); ?>"
                               class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Surveillance Post-Anesthésique
                            </a>

                            <button type="button" class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-border-0 tw-transition-colors tw-text-left"
                                data-bs-toggle="modal" data-bs-target="#FicheInterventionAll">
                                <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Fiche d'Intervention
                            </button>

                            <div class="tw-h-px tw-bg-slate-100"></div>

                            <a href="<?php echo e(route('dossiers.create', $patient->id)); ?>"
                               class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-folder-plus tw-text-xs tw-shrink-0"></i> Compléter Le Dossier
                            </a>

                            <?php if($patient->consultations && $patient->consultations->isNotEmpty()): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                                <a href="<?php echo e(route('print.sortie', $patient->id)); ?>"
                                   class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-transition-colors tw-no-underline">
                                    <i class="fas fa-print tw-text-xs tw-shrink-0"></i> Lettre De Consultation
                                </a>
                                <button type="button" class="tw-w-full tw-flex tw-items-center tw-gap-2.5 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-text-sm tw-font-medium tw-px-3.5 tw-py-2.5 tw-border-0 tw-transition-colors tw-text-left"
                                    data-bs-toggle="modal" data-bs-target="#ficheSuiviAll">
                                    <i class="fas fa-eye tw-text-xs tw-shrink-0"></i> Consultation De Suivi
                                </button>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

            </div>
        </main>
        <?php endif; ?>

        
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

document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => new bootstrap.Popover(el));
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/patients/show.blade.php ENDPATH**/ ?>