
<div class="modal fade" id="biologieAll" tabindex="-1" role="dialog" aria-labelledby="biologieAll" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-vial tw-mr-2"></i>Examens Biologie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6">
                <?php if(count($patient->prescriptions)): ?>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Description</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Date</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Imprimer</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            <?php $__currentLoopData = $patient->prescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600 tw-text-xs">
                                    <?php echo e(implode(' ', array_filter([
                                        $prescription->hematologie, $prescription->hemostase,
                                        $prescription->biochimie, $prescription->hormonologie,
                                        $prescription->marqeurs, $prescription->bacteriologie,
                                        $prescription->spermiologie, $prescription->serologie,
                                        $prescription->urines, $prescription->examen
                                    ]))); ?>

                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-400 tw-whitespace-nowrap"><?php echo e($prescription->created_at->toFormattedDateString()); ?></td>
                                <td class="tw-px-4 tw-py-3">
                                    <a href="<?php echo e(route('prescription_examens.pdf', $prescription->id)); ?>" title="Imprimer"
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-emerald-50 hover:tw-bg-emerald-100 tw-text-emerald-600 tw-no-underline tw-transition-colors">
                                        <i class="fas fa-print tw-text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="tw-mt-4"><?php echo e($ordonances->links()); ?></div>
                <?php else: ?>
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-12">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-vial tw-text-slate-400"></i>
                    </div>
                    <p class="tw-text-slate-400 tw-text-sm tw-mb-0">Aucun examen de biologie enregistré</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/modal/index_examen_biologie.blade.php ENDPATH**/ ?>