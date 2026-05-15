<div class="modal fade" id="SurveillancePre" tabindex="-1" role="dialog" aria-labelledby="SurveillancePre" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-chart-line tw-mr-2"></i>Surveillance Rapprochée Pré-Opératoire
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6 tw-max-h-[80vh] tw-overflow-y-auto">
                <form action="<?php echo e(route('surveillance_rapproche_param')); ?>" method="post" class="tw-space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" value="<?php echo e($patient->id); ?>" name="patient_id">

                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">

                        
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Patient</label>
                            <input type="text" name="nom_patient"
                                   value="<?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?>" disabled
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500">
                        </div>

                        
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Période <span class="tw-text-red-500">*</span>
                            </label>
                            <select name="periode" required
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                <option value="">Sélectionner...</option>
                                <option value="preoperatoire">Pré-opératoire</option>
                                <option value="postoperatoire">Post-opératoire</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date</label>
                            <input type="date" name="date" value="<?php echo e(old('date', \Carbon\Carbon::now()->toDateString())); ?>"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>

                        
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Heure</label>
                            <input type="time" name="heure" value="<?php echo e(old('heure')); ?>"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>

                        <?php $__currentLoopData = [
                            ['label' => 'T.A', 'name' => 'ta', 'type' => 'text', 'required' => true],
                            ['label' => 'F.R', 'name' => 'fr', 'type' => 'number', 'required' => true],
                            ['label' => 'SPO2', 'name' => 'spo2', 'type' => 'number', 'required' => true],
                            ['label' => 'T°', 'name' => 'temperature', 'type' => 'number', 'required' => true, 'extra' => 'min="0" step="any"'],
                            ['label' => 'Diurèse', 'name' => 'diurese', 'type' => 'text', 'required' => true],
                            ['label' => 'Pouls', 'name' => 'pouls', 'type' => 'number', 'required' => true],
                            ['label' => 'Conscience', 'name' => 'conscience', 'type' => 'text', 'required' => true],
                            ['label' => 'Douleur', 'name' => 'douleur', 'type' => 'text', 'required' => true],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                <?php echo e($field['label']); ?> <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="<?php echo e($field['type']); ?>" name="<?php echo e($field['name']); ?>"
                                   value="<?php echo e(old($field['name'])); ?>" required
                                   <?php echo e($field['extra'] ?? ''); ?>

                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <div class="sm:tw-col-span-2">
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Observations / Plaintes <span class="tw-text-red-500">*</span>
                            </label>
                            <textarea name="observation_plainte" rows="3" required
                                      class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none"><?php echo e(old('observation_plainte')); ?></textarea>
                        </div>

                    </div>

                    <div class="tw-flex tw-justify-end tw-pt-2">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-border-0 tw-cursor-pointer tw-transition-colors">
                            <i class="fas fa-save tw-text-xs"></i> Ajouter au dossier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/modal/surveillance_rapproche_param.blade.php ENDPATH**/ ?>