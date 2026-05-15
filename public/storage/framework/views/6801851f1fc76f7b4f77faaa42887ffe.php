
<?php $__env->startSection('title', 'CMCU | Pharmacie'); ?>
<?php $__env->startSection('breadcrumb', 'Pharmacie'); ?>
<?php $__env->startSection('page_title', 'Pharmacie'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-prescription-bottle-alt tw-text-[#1D4ED8]"></i> Pharmacie
                    </h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Gestion des ventes et du stock pharmaceutique</p>
                </div>
            </div>

            
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-shopping-cart tw-text-[#1D4ED8] tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Ventes Aujourd'hui</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1"><?php echo e($stats['ventes_today']); ?></p>
                    </div>
                </div>

                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-clock tw-text-amber-500 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">En Attente Paiement</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1"><?php echo e($stats['ventes_en_attente']); ?></p>
                    </div>
                </div>

                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-red-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-exclamation-triangle tw-text-red-500 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Alertes Stock</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1"><?php echo e($stats['stock_alerts']); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-6">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-bolt tw-text-[#14B8A6]"></i>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Actions Rapides</h2>
                </div>
                <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">
                    <a href="<?php echo e(route('pharmacie.sales.patient.create')); ?>"
                        class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-border-2 tw-border-[#BFDBFE] tw-bg-[#BFDBFE]/20 hover:tw-bg-[#BFDBFE]/40 tw-p-5 tw-text-[#1D4ED8] tw-transition-colors tw-duration-150 tw-no-underline tw-text-center">
                        <i class="fas fa-user-injured tw-text-2xl"></i>
                        <div>
                            <p class="tw-font-semibold tw-text-sm tw-mb-0">Vente Patient</p>
                            <p class="tw-text-xs tw-text-[#1D4ED8]/70 tw-mb-0">Avec ordonnance</p>
                        </div>
                    </a>

                    <a href="<?php echo e(route('pharmacie.sales.external.create')); ?>"
                        class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-border-2 tw-border-teal-200 tw-bg-teal-50 hover:tw-bg-teal-100 tw-p-5 tw-text-[#14B8A6] tw-transition-colors tw-duration-150 tw-no-underline tw-text-center">
                        <i class="fas fa-hospital tw-text-2xl"></i>
                        <div>
                            <p class="tw-font-semibold tw-text-sm tw-mb-0">Vente Externe</p>
                            <p class="tw-text-xs tw-text-teal-600/70 tw-mb-0">Autre hôpital / Client</p>
                        </div>
                    </a>

                    <a href="<?php echo e(route('pharmacie.sales.list')); ?>"
                        class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-border-2 tw-border-slate-200 tw-bg-slate-50 hover:tw-bg-slate-100 tw-p-5 tw-text-slate-600 tw-transition-colors tw-duration-150 tw-no-underline tw-text-center">
                        <i class="fas fa-list tw-text-2xl"></i>
                        <div>
                            <p class="tw-font-semibold tw-text-sm tw-mb-0">Liste Ventes</p>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Toutes les ventes</p>
                        </div>
                    </a>
                </div>
            </div>

            
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-history tw-text-[#1D4ED8]"></i>
                        <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Ventes Récentes</h2>
                    </div>
                    <a href="<?php echo e(route('pharmacie.history')); ?>"
                        class="tw-text-xs tw-font-medium tw-text-[#1D4ED8] hover:tw-underline tw-no-underline">
                        Voir l'historique complet →
                    </a>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">N° Vente</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Date</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Type</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Client</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Montant</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Statut</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3 tw-font-semibold tw-text-slate-700"><?php echo e($vente->numero_vente); ?></td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-500"><?php echo e($vente->created_at->format('d/m/Y H:i')); ?></td>
                                <td class="tw-px-4 tw-py-3">
                                    <?php if($vente->isPatientSale()): ?>
                                        <span class="tw-inline-flex tw-items-center tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">Patient</span>
                                    <?php else: ?>
                                        <span class="tw-inline-flex tw-items-center tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-teal-100 tw-text-[#14B8A6]">Externe</span>
                                    <?php endif; ?>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-700"><?php echo e($vente->customer_name); ?></td>
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-font-semibold tw-text-slate-800"><?php echo e(number_format($vente->montant_total)); ?> FCFA</td>
                                <td class="tw-px-4 tw-py-3">
                                    <?php if($vente->isSoldee()): ?>
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-700">
                                            <i class="fas fa-check tw-text-xs"></i> Soldée
                                        </span>
                                    <?php else: ?>
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-amber-100 tw-text-amber-700">
                                            <i class="fas fa-clock tw-text-xs"></i> En attente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <a href="<?php echo e(route('pharmacie.sales.show', $vente->id)); ?>"
                                        class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline">
                                        <i class="fas fa-eye tw-text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="tw-px-4 tw-py-10 tw-text-center tw-text-slate-400">
                                    <i class="fas fa-inbox tw-text-3xl tw-mb-2 tw-block"></i>
                                    Aucune vente récente
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
    setInterval(function() { location.reload(); }, 30000);
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/pharmacie/index.blade.php ENDPATH**/ ?>