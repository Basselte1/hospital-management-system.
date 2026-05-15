
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('adminOnly', \App\Models\Patient::class)): ?>
    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2">

        
        <div class="tw-relative tw-inline-block" x-data="{ open: false }">
            <button type="button"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-text-sm tw-px-4 tw-py-2.5 tw-border-0 tw-transition-colors dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bars tw-text-xs"></i> Menu
            </button>
            <ul class="dropdown-menu tw-rounded-xl tw-border-0 tw-shadow-xl tw-p-2 tw-min-w-56">
                <li>
                    <a href="<?php echo e(route('premedication_adaptation.index', $patient->id)); ?>"
                       title="Traitement à l'hospitalisation / adaptation au traitement personnel"
                       class="tw-flex tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                        <i class="fas fa-eye tw-text-xs tw-text-teal-500 tw-w-4 tw-shrink-0"></i> Prémédications
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('consultations.index', $patient->id)); ?>"
                       class="tw-flex tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                        <i class="fas fa-eye tw-text-xs tw-text-teal-500 tw-w-4 tw-shrink-0"></i> Consultations Chirurgicales
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('observations_medicales.index', $patient->id)); ?>"
                       class="tw-flex tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                        <i class="far fa-plus-square tw-text-xs tw-text-[#1D4ED8] tw-w-4 tw-shrink-0"></i> Observations Médicales
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('surveillance_score.index', $patient->id)); ?>"
                       class="tw-flex tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                        <i class="far fa-plus-square tw-text-xs tw-text-teal-500 tw-w-4 tw-shrink-0"></i> Surveillance D'Aptitude &gt;= 9/10
                    </a>
                </li>
            </ul>
        </div>

        
        <button type="button"
            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-3.5 tw-py-2.5 tw-border-0 tw-transition-colors"
            data-bs-toggle="modal" data-bs-target="#SpostAnesth"
            title="Surveillance post anesthésique">
            <i class="far fa-plus-square tw-text-xs"></i> Surveillance Post Anesthésique
        </button>

        <a href="<?php echo e(route('fiche_consommable.index', $patient->id)); ?>"
           class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-3.5 tw-py-2.5 tw-no-underline tw-transition-colors">
            <i class="far fa-plus-square tw-text-xs"></i> Fiches de Consommables
        </a>
    </div>
    <?php endif; ?>
<?php endif; ?>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('adminOnly', \App\Models\Patient::class)): ?>
    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2">

        
        <div class="tw-relative tw-inline-block">
            <button type="button"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-text-sm tw-px-4 tw-py-2.5 tw-border-0 tw-transition-colors dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bars tw-text-xs"></i> Menu
            </button>
            <ul class="dropdown-menu tw-rounded-xl tw-border-0 tw-shadow-xl tw-p-2 tw-min-w-56">
                <li>
                    <button type="button"
                        class="tw-flex tw-w-full tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-bg-transparent tw-border-0 tw-transition-colors"
                        data-bs-toggle="modal" data-bs-target="#FicheInterventionAnesthesiste"
                        title="Ajouter une fiche d'intervention">
                        <i class="far fa-plus-square tw-text-xs tw-text-[#1D4ED8] tw-w-4 tw-shrink-0"></i> Fiche d'Intervention
                    </button>
                </li>
                <li>
                    <button type="button"
                        class="tw-flex tw-w-full tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-bg-transparent tw-border-0 tw-transition-colors"
                        data-bs-toggle="modal" data-bs-target="#SpostAnesth"
                        title="Surveillance post anesthésique">
                        <i class="far fa-plus-square tw-text-xs tw-text-[#1D4ED8] tw-w-4 tw-shrink-0"></i> Surveillance Post Anesthésique
                    </button>
                </li>
                <li>
                    <a href="<?php echo e(route('surveillance_score.index', $patient->id)); ?>"
                       class="tw-flex tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                        <i class="far fa-plus-square tw-text-xs tw-text-teal-500 tw-w-4 tw-shrink-0"></i> Surveillance d'Aptitude &gt;= 9/10
                    </a>
                </li>
            </ul>
        </div>

        
        <a href="<?php echo e(route('ordonance.create', $patient->id)); ?>"
           title="Nouvelle ordonnance médicale"
           class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-3.5 tw-py-2.5 tw-no-underline tw-transition-colors">
            <i class="far fa-plus-square tw-text-xs"></i> Ordonnances
        </a>

        <button type="button"
            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-3.5 tw-py-2.5 tw-border-0 tw-transition-colors"
            data-bs-toggle="modal" data-bs-target="#ordonanceModal"
            title="Prescrire un examen complémentaire">
            <i class="far fa-plus-square tw-text-xs"></i> Examens Complémentaires
        </button>

        <a href="<?php echo e(route('observations_medicales.index', $patient->id)); ?>"
           class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-3.5 tw-py-2.5 tw-no-underline tw-transition-colors">
            <i class="far fa-plus-square tw-text-xs"></i> Observations Médicales
        </a>

        
        
    </div>
    <?php endif; ?>
<?php endif; ?>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('adminOnly', \App\Models\Patient::class)): ?>
    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2">

        
        <div class="tw-relative tw-inline-block">
            <button type="button"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-text-sm tw-px-4 tw-py-2.5 tw-border-0 tw-transition-colors dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bars tw-text-xs"></i> Menu
            </button>
            <ul class="dropdown-menu tw-rounded-xl tw-border-0 tw-shadow-xl tw-p-2 tw-min-w-56">
                <li>
                    <a href="<?php echo e(route('premedication_adaptation.index', $patient->id)); ?>"
                       title="Traitement à l'hospitalisation / adaptation au traitement personnel"
                       class="tw-flex tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                        <i class="fas fa-eye tw-text-xs tw-text-teal-500 tw-w-4 tw-shrink-0"></i> Prémédications
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('consultations.index', $patient->id)); ?>"
                       class="tw-flex tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                        <i class="fas fa-eye tw-text-xs tw-text-teal-500 tw-w-4 tw-shrink-0"></i> Consultations chirurgicales
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('surveillance_score.index', $patient->id)); ?>"
                       class="tw-flex tw-items-center tw-gap-2.5 tw-rounded-lg tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-[#BFDBFE]/40 hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                        <i class="far fa-plus-square tw-text-xs tw-text-teal-500 tw-w-4 tw-shrink-0"></i> Surveillance d'aptitude &gt;= 9/10
                    </a>
                </li>
            </ul>
        </div>

        
        <a href="<?php echo e(route('observations_medicales.index', $patient->id)); ?>"
           class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-3.5 tw-py-2.5 tw-no-underline tw-transition-colors">
            <i class="far fa-plus-square tw-text-xs"></i> Observations médicales
        </a>

        <a href="<?php echo e(route('fiche_consommable.index', $patient->id)); ?>"
           class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-3.5 tw-py-2.5 tw-no-underline tw-transition-colors">
            <i class="far fa-plus-square tw-text-xs"></i> Fiches de Consommables
        </a>

        <button type="button"
            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-3.5 tw-py-2.5 tw-border-0 tw-transition-colors"
            title="Surveillance pré-opératoire"
            data-bs-toggle="modal" data-bs-target="#SurveillancePre">
            <i class="far fa-plus-square tw-text-xs"></i> Prise de Paramètres Opératoire
        </button>
    </div>
    <?php endif; ?>
<?php endif; ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/patients/partials/menu.blade.php ENDPATH**/ ?>