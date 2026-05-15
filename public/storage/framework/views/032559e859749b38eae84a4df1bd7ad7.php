
<?php $__env->startSection('title', 'CMCU | Factures Devis'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-2xl tw-mx-auto">

                
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-6">
                    <div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Factures Devis</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-0.5">Liste de toutes les factures générées depuis les devis</p>
                    </div>
                    <a href="<?php echo e(route('devis.index')); ?>"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-primary-700 hover:tw-bg-primary-800 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-rounded-xl tw-transition-all">
                        <i class="fas fa-arrow-left tw-text-xs"></i>
                        Retour aux devis
                    </a>
                </div>

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success tw-mb-4"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('info')): ?>
                    <div class="alert alert-info tw-mb-4"><?php echo e(session('info')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger tw-mb-4"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                        <span class="tw-text-sm tw-font-semibold tw-text-slate-700">Toutes les factures</span>
                        <span class="tw-text-xs tw-text-slate-400"><?php echo e($factures->total()); ?> résultat(s)</span>
                    </div>

                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm">
                            <thead>
                                <tr class="tw-bg-[#1D4ED8]">
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase">N° Facture</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase">Patient</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase">Devis</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-white tw-uppercase">Montant</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-white tw-uppercase">Avance</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-white tw-uppercase">Reste</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase">Statut</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                <?php $__empty_1 = true; $__currentLoopData = $factures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                    <td class="tw-px-5 tw-py-3.5">
                                        <span class="tw-font-mono tw-text-xs tw-font-semibold tw-text-primary-700 tw-bg-primary-50 tw-px-2 tw-py-1 tw-rounded-lg">
                                            <?php echo e($facture->numero ?? 'N/A'); ?>

                                        </span>
                                    </td>
                                    <td class="tw-px-5 tw-py-3.5 tw-font-medium tw-text-slate-800">
                                        <?php echo e($facture->patient_display_name); ?>

                                    </td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-slate-600">
                                        <?php echo e($facture->designation_devis); ?>

                                    </td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-font-semibold tw-text-slate-800 tw-whitespace-nowrap">
                                        <?php echo e(number_format($facture->montant_devis, 0, ',', ' ')); ?> FCFA
                                    </td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-text-slate-600 tw-whitespace-nowrap">
                                        <?php echo e(number_format($facture->avance_devis ?? 0, 0, ',', ' ')); ?> FCFA
                                    </td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-font-semibold tw-whitespace-nowrap
                                        <?php echo e(($facture->reste_devis ?? 0) > 0 ? 'tw-text-red-600' : 'tw-text-emerald-600'); ?>">
                                        <?php echo e(number_format($facture->reste_devis ?? 0, 0, ',', ' ')); ?> FCFA
                                    </td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                        <?php if($facture->statut == 'Soldée'): ?>
                                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-emerald-700 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-px-2.5 tw-py-1 tw-rounded-full">
                                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-emerald-500 tw-inline-block"></span>
                                                Soldée
                                            </span>
                                        <?php else: ?>
                                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-amber-700 tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-2.5 tw-py-1 tw-rounded-full">
                                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-amber-400 tw-inline-block tw-animate-pulse"></span>
                                                Non soldée
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-xs tw-text-slate-500">
                                        <?php echo e($facture->date_creation ? \Carbon\Carbon::parse($facture->date_creation)->format('d/m/Y') : 'N/A'); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="tw-px-5 tw-py-12 tw-text-center tw-text-slate-400">
                                        <i class="fas fa-file-invoice tw-text-3xl tw-mb-3 tw-block tw-opacity-30"></i>
                                        Aucune facture générée pour l'instant.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <?php if($factures->hasPages()): ?>
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                        <?php echo e($factures->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/facture_devis.blade.php ENDPATH**/ ?>