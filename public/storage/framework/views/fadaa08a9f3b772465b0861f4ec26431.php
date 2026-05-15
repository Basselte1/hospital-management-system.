<div class="modal fade" id="SoinsInfirmier" tabindex="-1" role="dialog" aria-labelledby="SoinsInfirmier" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#14B8A6]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-heartbeat tw-mr-2"></i>Soins Infirmier
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6">
                <div class="tw-flex tw-items-center tw-gap-2 tw-bg-[#BFDBFE]/20 tw-border tw-border-[#BFDBFE] tw-rounded-xl tw-px-4 tw-py-2.5 tw-mb-5 tw-text-sm tw-text-[#1D4ED8]">
                    <i class="fas fa-user tw-text-xs tw-shrink-0"></i>
                    <span>Patient : <strong><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></strong></span>
                </div>
                <form action="<?php echo e(route('soins_infirmiers.store')); ?>" method="post" class="tw-space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">

                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                        <div>
                            <label for="soin_date" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Date <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="date" name="date" id="soin_date" required
                                   value="<?php echo e(old('date', \Carbon\Carbon::now()->toDateString())); ?>"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>
                        <div>
                            <label for="soin_obs" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Observations <span class="tw-text-red-500">*</span>
                            </label>
                            <textarea name="observation" id="soin_obs" rows="3" required
                                      class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none"></textarea>
                        </div>
                    </div>

                    <div class="tw-flex tw-justify-end tw-pt-2">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-[#14B8A6] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-teal-600 tw-border-0 tw-cursor-pointer tw-transition-colors">
                            <i class="fas fa-save tw-text-xs"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/modal/soins_infirmier.blade.php ENDPATH**/ ?>