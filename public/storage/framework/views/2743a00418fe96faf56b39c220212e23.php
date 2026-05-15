<?php $__env->startSection('link'); ?>

<?php $__env->stopSection(); ?>

<div id="examens_scannes_form" style="display: none;">

    
    <?php if($examens_scannes->count() > 0): ?>
    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-4 tw-mb-4">
        <?php $__currentLoopData = $examens_scannes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-100 tw-shadow-sm tw-overflow-hidden tw-group">

            
            <div class="tw-relative tw-bg-slate-50 tw-overflow-hidden"
                 data-bs-toggle="popover"
                 title="Description"
                 data-bs-content="<?php echo e($examen->description); ?>"
                 style="cursor: pointer;">
                <img src="<?php echo e(asset('storage/' . $examen->image)); ?>"
                     alt="Examen Scanné"
                     class="tw-w-full tw-object-contain tw-max-h-48 tw-transition-transform group-hover:tw-scale-105">
            </div>

            
            <div class="tw-px-3 tw-py-2.5 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-justify-between tw-gap-2">
                <p class="tw-text-xs tw-font-medium tw-text-slate-600 tw-truncate tw-mb-0"><?php echo e($examen->nom); ?></p>
                <div class="tw-flex tw-items-center tw-gap-1 tw-shrink-0">
                    <a href="<?php echo e(asset('storage/' . $examen->image)); ?>" target="_blank"
                       title="Voir en plein écran"
                       class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-[#BFDBFE] tw-text-slate-500 hover:tw-text-[#1D4ED8] tw-transition-colors tw-no-underline">
                        <i class="fas fa-eye tw-text-xs"></i>
                    </a>
                    <form action="<?php echo e(route('examens.destroy', $examen->id)); ?>" method="POST"
                          onsubmit="return confirm('Supprimer cette image ?')" class="tw-inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">
                        <button type="submit"
                            class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-red-100 tw-text-slate-500 hover:tw-text-red-500 tw-border-0 tw-transition-colors">
                            <i class="fas fa-trash tw-text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <div class="tw-py-8 tw-text-center tw-mb-4">
        <div class="tw-w-12 tw-h-12 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-3">
            <i class="fas fa-images tw-text-slate-300 tw-text-xl"></i>
        </div>
        <p class="tw-text-slate-400 tw-text-sm tw-mb-0">Aucune image scannée pour ce patient</p>
    </div>
    <?php endif; ?>

    
    <div class="tw-flex tw-items-center tw-justify-between tw-pt-3 tw-border-t tw-border-slate-100">
        <button type="button"
            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-4 tw-py-2 tw-border-0 tw-transition-colors"
            data-bs-toggle="modal" data-bs-target="#modal_examens_scannes"
            title="Ajouter une image">
            <i class="fas fa-plus tw-text-xs"></i> Ajouter une image
        </button>
        <div><?php echo e($examens_scannes->links()); ?></div>
    </div>

    
    <div class="modal fade" id="modal_examens_scannes">
        <div class="modal-dialog modal-lg">
            <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">

                <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                    <h4 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">
                        <i class="fas fa-image tw-mr-2"></i> Ajouter une nouvelle image
                    </h4>
                    <button type="button" class="btn-close btn-close-white tw-text-xs" data-bs-dismiss="modal"></button>
                </div>

                <form action="<?php echo e(route('examens.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="tw-p-6">
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-6">

                            
                            <div class="sm:tw-col-span-2 tw-space-y-4">
                                <?php echo Html::input('hidden', 'patient_id', $patient->id); ?>


                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                        Choisir une image <span class="tw-text-red-500">*</span>
                                    </label>
                                    <input type="file" name="image" id="customFile" accept="image/*" required
                                           onchange="handleFiles(this.files)"
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-600 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                </div>

                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                        Libellé <span class="tw-text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nom" id="libelle" required
                                           placeholder="Ex: Radio thorax, Echo abdominale..."
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                </div>

                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Description</label>
                                    <textarea name="description" id="description" rows="4"
                                              placeholder="Observations, résultats..."
                                              class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none"></textarea>
                                </div>
                            </div>

                            
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Aperçu</label>
                                <div class="tw-rounded-xl tw-border-2 tw-border-dashed tw-border-slate-200 tw-bg-slate-50 tw-flex tw-items-center tw-justify-center tw-overflow-hidden" style="min-height: 160px;">
                                    <span id="preview">
                                        <img id="img1"
                                             src="<?php echo e(asset('admin/images/default.png')); ?>"
                                             alt="Aperçu"
                                             class="tw-w-full tw-max-h-48 tw-object-contain" />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-flex tw-justify-end tw-gap-3">
                        <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-text-sm tw-transition-colors" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        <button type="submit"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-500 hover:tw-bg-teal-600 tw-text-white tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-text-sm tw-transition-colors">
                            <i class="fas fa-plus tw-text-xs"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function handleFiles(files) {
    if (files.length > 0) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('img1').src = e.target.result;
        };
        reader.readAsDataURL(files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(function (el) {
        new bootstrap.Popover(el);
    });
});
</script><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/patients/partials/examens_scannes.blade.php ENDPATH**/ ?>