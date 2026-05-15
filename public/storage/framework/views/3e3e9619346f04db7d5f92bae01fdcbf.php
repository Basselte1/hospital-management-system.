
<?php $__env->startSection('title', 'CMCU | Détail Vente'); ?>
<?php $__env->startSection('breadcrumb', 'Pharmacie / Détail Vente'); ?>
<?php $__env->startSection('page_title', 'Détail Vente'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-file-invoice tw-text-[#1D4ED8]"></i> Détail de la Vente
                    </h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1"><?php echo e($vente->numero_vente); ?></p>
                </div>
                <a href="<?php echo e(route('pharmacie.sales.list')); ?>"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour à la Liste
                </a>
            </div>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-6">

                
                <div class="lg:tw-col-span-2 tw-space-y-6">

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                        <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-info-circle tw-text-[#1D4ED8]"></i>
                            <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Informations de la Vente</h2>
                        </div>
                        <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-1">Numéro de Vente</p>
                                <p class="tw-text-base tw-font-semibold tw-text-[#1D4ED8] tw-mb-0"><?php echo e($vente->numero_vente); ?></p>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-1">Date</p>
                                <p class="tw-text-base tw-text-slate-700 tw-mb-0"><?php echo e($vente->created_at->format('d/m/Y H:i')); ?></p>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-1">Type de Vente</p>
                                <?php if($vente->isPatientSale()): ?>
                                    <span class="tw-inline-flex tw-rounded-full tw-px-3 tw-py-1 tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">Vente Patient</span>
                                <?php else: ?>
                                    <span class="tw-inline-flex tw-rounded-full tw-px-3 tw-py-1 tw-text-xs tw-font-medium tw-bg-teal-100 tw-text-teal-700">Vente Externe</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-1">Pharmacien</p>
                                <p class="tw-text-base tw-text-slate-700 tw-mb-0"><?php echo e($vente->pharmacien->name ?? 'N/A'); ?></p>
                            </div>
                            <div class="sm:tw-col-span-2">
                                <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-1">Client</p>
                                <?php if($vente->isPatientSale()): ?>
                                    <p class="tw-text-base tw-text-slate-700 tw-mb-0">
                                        <i class="fas fa-user-injured tw-text-[#1D4ED8] tw-mr-1"></i>
                                        <?php echo e($vente->patient->name); ?> <?php echo e($vente->patient->prenom); ?>

                                        <span class="tw-text-slate-400 tw-text-sm tw-ml-2">Dossier N°: <?php echo e($vente->patient->numero_dossier); ?></span>
                                    </p>
                                    <?php if($vente->ordonance): ?>
                                    <p class="tw-text-sm tw-text-[#14B8A6] tw-mt-1 tw-mb-0">
                                        <i class="fas fa-file-prescription tw-mr-1"></i> Ordonnance du <?php echo e($vente->ordonance->created_at->format('d/m/Y')); ?>

                                    </p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="tw-text-base tw-text-slate-700 tw-mb-0">
                                        <i class="fas fa-hospital tw-text-[#14B8A6] tw-mr-1"></i>
                                        <?php echo e($vente->client->nom ?? 'N/A'); ?> <?php echo e($vente->client->prenom ?? ''); ?>

                                    </p>
                                    <?php if($vente->client && $vente->client->motif): ?>
                                    <p class="tw-text-sm tw-text-slate-400 tw-mt-0.5 tw-mb-0"><?php echo e($vente->client->motif); ?></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php if($vente->notes): ?>
                            <div class="sm:tw-col-span-2">
                                <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-1">Notes</p>
                                <p class="tw-text-sm tw-text-slate-600 tw-mb-0"><?php echo e($vente->notes); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                        <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-pills tw-text-[#14B8A6]"></i>
                            <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Produits</h2>
                        </div>
                        <div class="tw-overflow-x-auto">
                            <table class="tw-w-full tw-text-sm">
                                <thead>
                                    <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Produit</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Quantité</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Prix Unit.</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="tw-divide-y tw-divide-slate-100">
                                    <?php $__currentLoopData = $vente->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="tw-px-4 tw-py-3 tw-text-slate-700">
                                            <?php echo e($item->designation); ?>

                                            <?php if($item->stock_deducted): ?>
                                                <span class="tw-ml-2 tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-px-2 tw-py-0.5 tw-text-xs tw-bg-green-100 tw-text-green-700">
                                                    <i class="fas fa-check tw-text-xs"></i> Stock Déduit
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-center tw-text-slate-700"><?php echo e($item->quantite); ?></td>
                                        <td class="tw-px-4 tw-py-3 tw-text-right tw-text-slate-600"><?php echo e(number_format($item->prix_unitaire)); ?> FCFA</td>
                                        <td class="tw-px-4 tw-py-3 tw-text-right tw-font-semibold tw-text-slate-800"><?php echo e(number_format($item->montant_ligne)); ?> FCFA</td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr class="tw-bg-slate-50 tw-border-t tw-border-slate-200">
                                        <td colspan="3" class="tw-px-4 tw-py-3 tw-text-right tw-font-semibold tw-text-slate-700">TOTAL :</td>
                                        <td class="tw-px-4 tw-py-3 tw-text-right tw-font-bold tw-text-[#1D4ED8] tw-text-base"><?php echo e(number_format($vente->montant_total)); ?> FCFA</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                
                <div class="tw-space-y-6">

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-px-6 tw-py-4 <?php echo e($vente->isSoldee() ? 'tw-bg-green-500' : 'tw-bg-amber-400'); ?>">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <i class="fas <?php echo e($vente->isSoldee() ? 'fa-check-circle' : 'fa-clock'); ?> tw-text-white"></i>
                                <h2 class="tw-text-base tw-font-semibold tw-text-white tw-mb-0">Statut Paiement</h2>
                            </div>
                        </div>
                        <div class="tw-p-6">
                            <?php if($vente->isSoldee()): ?>
                                <div class="tw-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-green-50 tw-border tw-border-green-200 tw-px-4 tw-py-3 tw-mb-4">
                                    <i class="fas fa-check-circle tw-text-green-500"></i>
                                    <span class="tw-font-semibold tw-text-green-700">SOLDÉE</span>
                                </div>
                                <div class="tw-space-y-2 tw-text-sm">
                                    <p class="tw-mb-0"><span class="tw-text-slate-500">Montant Payé :</span> <span class="tw-font-semibold tw-text-slate-800"><?php echo e(number_format($vente->montant_paye)); ?> FCFA</span></p>
                                    <p class="tw-mb-0"><span class="tw-text-slate-500">Date Paiement :</span> <span class="tw-text-slate-800"><?php echo e($vente->date_paiement->format('d/m/Y H:i')); ?></span></p>
                                    <p class="tw-mb-0"><span class="tw-text-slate-500">Reçu par :</span> <span class="tw-text-slate-800"><?php echo e($vente->caissier->name ?? 'N/A'); ?></span></p>
                                    <?php if($vente->mode_paiement): ?>
                                    <p class="tw-mb-0"><span class="tw-text-slate-500">Mode :</span> <span class="tw-text-slate-800"><?php echo e($vente->mode_paiement); ?></span></p>
                                    <?php endif; ?>
                                    <?php if($vente->reference_paiement): ?>
                                    <p class="tw-mb-0"><span class="tw-text-slate-500">Réf. :</span> <span class="tw-text-slate-800"><?php echo e($vente->reference_paiement); ?></span></p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="tw-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-4 tw-py-3 tw-mb-4">
                                    <i class="fas fa-clock tw-text-amber-500"></i>
                                    <span class="tw-font-semibold tw-text-amber-700">EN ATTENTE DE PAIEMENT</span>
                                </div>
                                <p class="tw-text-sm tw-text-slate-500 tw-mb-1">Montant à Payer</p>
                                <p class="tw-text-2xl tw-font-bold tw-text-red-500 tw-mb-4"><?php echo e(number_format($vente->montant_total)); ?> FCFA</p>

                                <?php if(in_array(auth()->user()->role_id, [1, 6, 7])): ?>
                                <hr class="tw-border-slate-100 tw-my-4">
                                <form action="<?php echo e(route('pharmacie.sales.soldee', $vente->id)); ?>" method="POST" class="tw-space-y-3">
                                    <?php echo csrf_field(); ?>
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Mode de Paiement *</label>
                                        <select name="mode_paiement" required
                                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                            <option value="">Sélectionner...</option>
                                            <option value="Espèces">Espèces</option>
                                            <option value="Mobile Money">Mobile Money</option>
                                            <option value="Carte Bancaire">Carte Bancaire</option>
                                            <option value="Chèque">Chèque</option>
                                            <option value="Virement">Virement</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Référence / N° Transaction</label>
                                        <input type="text" name="reference_paiement"
                                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                    </div>
                                    <button type="submit"
                                        class="tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-green-600 hover:tw-bg-green-700 tw-text-white tw-font-medium tw-py-2.5 tw-transition-colors tw-border-0">
                                        <i class="fas fa-check"></i> Marquer comme Soldée
                                    </button>
                                </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                        <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-cog tw-text-slate-500"></i>
                            <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Actions</h2>
                        </div>
                        <div class="tw-p-5 tw-space-y-2">
                            <a href="<?php echo e(route('pharmacie.sales.invoice', $vente->id)); ?>" target="_blank"
                                class="tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-font-medium tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-file-pdf"></i> Imprimer Facture (x2)
                            </a>
                            <?php if($vente->isSoldee()): ?>
                            <a href="<?php echo e(route('pharmacie.sales.receipt', $vente->id)); ?>" target="_blank"
                                class="tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-green-600 hover:tw-bg-green-700 tw-text-white tw-font-medium tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-receipt"></i> Imprimer Reçu
                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('pharmacie.index')); ?>"
                                class="tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-home"></i> Retour Accueil
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/pharmacie/show.blade.php ENDPATH**/ ?>