
<?php $__env->startSection('title', 'CMCU | Liste des Ventes'); ?>
<?php $__env->startSection('breadcrumb', 'Pharmacie / Liste des Ventes'); ?>
<?php $__env->startSection('page_title', 'Liste des Ventes'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-list tw-text-[#1D4ED8]"></i> Liste des Ventes
                    </h1>
                </div>
                <a href="<?php echo e(route('pharmacie.index')); ?>"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-6">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-filter tw-text-[#1D4ED8]"></i>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Filtres</h2>
                </div>
                <div class="tw-p-6">
                    <form method="GET" action="<?php echo e(route('pharmacie.sales.list')); ?>">
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-4">
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Type de Vente</label>
                                <select name="type_vente" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                    <option value="">Tous</option>
                                    <option value="patient" <?php echo e(request('type_vente') == 'patient' ? 'selected' : ''); ?>>Patient</option>
                                    <option value="client_externe" <?php echo e(request('type_vente') == 'client_externe' ? 'selected' : ''); ?>>Externe</option>
                                </select>
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Statut Paiement</label>
                                <select name="statut_paiement" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                    <option value="">Tous</option>
                                    <option value="en_attente" <?php echo e(request('statut_paiement') == 'en_attente' ? 'selected' : ''); ?>>En Attente</option>
                                    <option value="soldee" <?php echo e(request('statut_paiement') == 'soldee' ? 'selected' : ''); ?>>Soldée</option>
                                </select>
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date Début</label>
                                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date Fin</label>
                                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                            </div>
                            <div class="tw-flex tw-items-end">
                                <button type="submit"
                                    class="tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-border-0">
                                    <i class="fas fa-search tw-text-xs"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">N° Vente</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Date</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Type</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Client</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Pharmacien</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Montant</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Statut</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $ventes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3 tw-font-semibold tw-text-slate-700"><?php echo e($vente->numero_vente); ?></td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-500"><?php echo e($vente->created_at->format('d/m/Y H:i')); ?></td>
                                <td class="tw-px-4 tw-py-3">
                                    <?php if($vente->isPatientSale()): ?>
                                        <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">Patient</span>
                                    <?php else: ?>
                                        <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-teal-100 tw-text-teal-700">Externe</span>
                                    <?php endif; ?>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-700"><?php echo e($vente->customer_name); ?></td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-500"><?php echo e($vente->pharmacien->name ?? 'N/A'); ?></td>
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
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                        <a href="<?php echo e(route('pharmacie.sales.show', $vente->id)); ?>"
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline" title="Voir détails">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>
                                        <a href="<?php echo e(route('pharmacie.sales.invoice', $vente->id)); ?>"
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-transition-colors tw-no-underline" title="Télécharger facture" target="_blank">
                                            <i class="fas fa-file-pdf tw-text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="tw-px-4 tw-py-12 tw-text-center tw-text-slate-400">
                                    <i class="fas fa-inbox tw-text-3xl tw-mb-2 tw-block"></i>
                                    Aucune vente trouvée
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($ventes->hasPages()): ?>
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    <?php echo e($ventes->appends(request()->query())->links()); ?>

                </div>
                <?php endif; ?>
            </div>

        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/pharmacie/list.blade.php ENDPATH**/ ?>