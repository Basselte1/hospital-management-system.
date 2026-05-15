<div class="modal fade" id="DetailPremedication" tabindex="-1" role="dialog" aria-labelledby="DetailPremedication" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-amber-500">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-pills tw-mr-2"></i>Prémédication
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6">
                <?php if(count($patient->premedications)): ?>
                    <?php if(count($premedications)): ?>
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm">
                            <thead>
                                <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Médicament</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Consignes IDE</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Préparation</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                <?php $__currentLoopData = $patient->premedications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $premedication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                    <td class="tw-px-4 tw-py-3 tw-font-medium tw-text-slate-700"><?php echo e($premedication->medicament); ?></td>
                                    <td class="tw-px-4 tw-py-3 tw-text-slate-600"><?php echo e($premedication->consigne_ide); ?></td>
                                    <td class="tw-px-4 tw-py-3 tw-text-slate-600"><?php echo e($premedication->preparation); ?></td>
                                    <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-400"><?php echo e($premedication->created_at); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-12">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-pills tw-text-slate-400"></i>
                    </div>
                    <p class="tw-text-slate-400 tw-text-sm tw-mb-0">Aucune prémédication enregistrée</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/modal/detail_premedication_preparation.blade.php ENDPATH**/ ?>