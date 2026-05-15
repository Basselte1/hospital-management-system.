
<?php $__env->startSection('title', 'CMCU | Mes Patients Suivis'); ?>
<?php $__env->startSection('content'); ?>

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <div class="tw-mb-6 tw-rounded-2xl tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-shadow-sm">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-user-check tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Mes Patients Suivis</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">Liste des patients que vous avez consultés</p>
                    </div>
                </div>
                <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-white/20 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2 tw-border tw-border-white/30">
                    <i class="fas fa-list tw-text-xs"></i> <?php echo e($patients->total()); ?> patient(s)
                </span>
            </div>

            
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-users tw-text-[#1D4ED8] tw-text-lg"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Total Patients</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1"><?php echo e($patients->total()); ?></p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-user-md tw-text-[#14B8A6] tw-text-lg"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Consultations Chirurgien</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1"><?php echo e($patients->filter(fn($p) => $p->consultations->count() > 0)->count()); ?></p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-stethoscope tw-text-amber-500 tw-text-lg"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Consultations Anesthésiste</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1"><?php echo e($patients->filter(fn($p) => $p->consultation_anesthesistes->count() > 0)->count()); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                
                <div class="tw-px-5 tw-py-4 tw-border-b tw-border-slate-100 tw-bg-slate-50">
                    <form method="GET" action="<?php echo e(route('patients.suivis')); ?>" id="searchForm">
                        <div class="tw-flex tw-flex-col sm:tw-flex-row tw-gap-3">
                            <div class="tw-flex-1 tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE] focus-within:tw-border-[#1D4ED8] tw-transition-all tw-bg-white">
                                <span class="tw-px-3 tw-flex tw-items-center tw-text-slate-400">
                                    <i class="fas fa-search tw-text-sm"></i>
                                </span>
                                <input type="text" name="search" id="searchInput"
                                       class="tw-flex-1 tw-bg-transparent tw-px-2 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-border-0 focus:tw-outline-none"
                                       placeholder="Rechercher par nom, prénom ou n° dossier..."
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="tw-flex tw-gap-2 tw-shrink-0">
                                <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors">
                                    <i class="fas fa-search tw-text-xs"></i> Rechercher
                                </button>
                                <?php if(request('search')): ?>
                                <a href="<?php echo e(route('patients.suivis')); ?>"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors">
                                    <i class="fas fa-times tw-text-xs"></i> Effacer
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>

                
                <?php if($patients->count() > 0): ?>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-[#1D4ED8]">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">N° Dossier</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Nom & Prénom</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Téléphone</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Dernière Consultation</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Type</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3">
                                    <span class="tw-inline-flex tw-items-center tw-rounded-lg tw-bg-slate-600 tw-text-white tw-px-2.5 tw-py-1 tw-text-xs tw-font-mono tw-font-semibold">
                                        <?php echo e($patient->numero_dossier); ?>

                                    </span>
                                    <?php if($patient->isNew()): ?>
                                    <span class="tw-ml-1.5 tw-inline-flex tw-items-center tw-rounded-full tw-bg-teal-100 tw-text-teal-700 tw-px-2 tw-py-0.5 tw-text-[10px] tw-font-bold tw-uppercase tw-animate-pulse">
                                        Nouveau
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-font-semibold tw-text-slate-700"><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600">
                                    <?php
                                        $telephone = $patient->user->telephone ?? null;
                                        if (!$telephone && $patient->dossiers->first()) {
                                            $telephone = $patient->dossiers->first()->portable_1 ?: $patient->dossiers->first()->portable_2;
                                        }
                                    ?>
                                    <?php if($telephone): ?>
                                        <span class="tw-flex tw-items-center tw-gap-1.5">
                                            <i class="fas fa-phone tw-text-[#14B8A6] tw-text-xs"></i>
                                            <?php echo e($telephone); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="tw-text-slate-400 tw-text-xs tw-italic">Non renseigné</span>
                                    <?php endif; ?>
                                </td>
                                <td class="tw-px-4 tw-py-3">
                                    <?php
                                        $lastConsultation = $patient->consultations->first();
                                        $lastConsultationAnes = $patient->consultation_anesthesistes->first();
                                        $mostRecent = null;
                                        if ($lastConsultation && $lastConsultationAnes) {
                                            $mostRecent = $lastConsultation->created_at > $lastConsultationAnes->created_at ? $lastConsultation : $lastConsultationAnes;
                                        } else {
                                            $mostRecent = $lastConsultation ?: $lastConsultationAnes;
                                        }
                                    ?>
                                    <?php if($mostRecent): ?>
                                        <p class="tw-text-xs tw-text-slate-500 tw-mb-0"><i class="far fa-clock tw-mr-1"></i><?php echo e($mostRecent->created_at->diffForHumans()); ?></p>
                                        <p class="tw-text-[11px] tw-text-slate-400 tw-mb-0"><?php echo e($mostRecent->created_at->format('d/m/Y')); ?></p>
                                    <?php else: ?>
                                        <span class="tw-text-amber-500 tw-text-xs tw-italic">Aucune consultation</span>
                                    <?php endif; ?>
                                </td>
                                <td class="tw-px-4 tw-py-3">
                                    <div class="tw-flex tw-flex-wrap tw-gap-1">
                                        <?php if($patient->consultations->count() > 0): ?>
                                        <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-teal-100 tw-text-teal-700 tw-px-2 tw-py-0.5 tw-text-[10px] tw-font-semibold">Chirurgien</span>
                                        <?php endif; ?>
                                        <?php if($patient->consultation_anesthesistes->count() > 0): ?>
                                        <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-amber-100 tw-text-amber-700 tw-px-2 tw-py-0.5 tw-text-[10px] tw-font-semibold">Anesthésiste</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="tw-px-4 tw-py-3">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                        <a href="<?php echo e(route('patients.show', $patient->id)); ?>" title="Voir le dossier"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                                            <?php if($patient->consultations->count() > 0): ?>
                                            <a href="<?php echo e(route('consultations.edit', $patient->id)); ?>" title="Modifier consultation"
                                               class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-600 tw-transition-colors tw-no-underline">
                                                <i class="fas fa-edit tw-text-xs"></i>
                                            </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                                            <?php if($patient->consultation_anesthesistes->count() > 0): ?>
                                            <a href="<?php echo e(route('consultations.edit', $patient->id)); ?>" title="Modifier consultation anesthésiste"
                                               class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-sky-50 hover:tw-bg-sky-100 tw-text-sky-600 tw-transition-colors tw-no-underline">
                                                <i class="fas fa-stethoscope tw-text-xs"></i>
                                            </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="tw-flex tw-items-center tw-justify-between tw-px-5 tw-py-3 tw-border-t tw-border-slate-100 tw-bg-slate-50">
                    <div>
                        <?php echo e($patients->appends(request()->query())->links()); ?>

                    </div>
                </div>

                <?php else: ?>
                <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-py-16 tw-px-6 tw-text-center">
                    <div class="tw-w-14 tw-h-14 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mb-4">
                        <i class="fas fa-info-circle tw-text-slate-400 tw-text-2xl"></i>
                    </div>
                    <h3 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-1">
                        <?php if(request('search')): ?>
                            Aucun résultat pour "<?php echo e(request('search')); ?>"
                        <?php else: ?>
                            Aucun patient suivi
                        <?php endif; ?>
                    </h3>
                    <p class="tw-text-sm tw-text-slate-400 tw-mb-4">
                        <?php if(request('search')): ?>
                            Essayez avec d'autres critères de recherche.
                        <?php else: ?>
                            Vous n'avez pas encore de patients en suivi.
                        <?php endif; ?>
                    </p>
                    <?php if(request('search')): ?>
                    <a href="<?php echo e(route('patients.suivis')); ?>"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] tw-text-white tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-no-underline hover:tw-bg-[#1a46c5] tw-transition-colors">
                        <i class="fas fa-arrow-left tw-text-xs"></i> Voir tous les patients
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/patients_suivis/index.blade.php ENDPATH**/ ?>