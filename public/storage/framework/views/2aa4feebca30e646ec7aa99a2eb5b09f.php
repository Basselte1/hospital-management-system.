

<?php
$sectionHeaderCls = 'tw-text-xs tw-font-bold tw-text-white tw-uppercase tw-tracking-wider tw-text-center tw-py-2.5 tw-px-4 tw-rounded-t-xl tw-bg-[#14B8A6] tw--mx-px tw--mt-px';
$checkItemCls     = 'tw-flex tw-items-center tw-gap-2.5 tw-py-1.5 tw-text-sm tw-text-slate-700 tw-cursor-pointer hover:tw-text-[#14B8A6] tw-transition-colors tw-group';
$checkboxCls      = 'tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-accent-teal-500 tw-cursor-pointer tw-shrink-0';
$inputCls         = 'tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-800 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#14B8A6] focus:tw-ring-2 focus:tw-ring-teal-500/10 tw-transition-colors tw-mt-1';
$labelCls         = 'tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mt-4 tw-mb-0.5';
?>

<div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-4 tw-p-4">

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Radiographie</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = ['Thorax', 'Abdomen sans préparation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="radiographie[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="radiographie[]" id="radiographie_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Échographie</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = ['Reins et vessie', 'Scrotum']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="echographie[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="echographie[]" id="echographie_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Scanner</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = [
                'Abdomen-pelvis',
                'Cérébral',
                'Rachis Cervical',
                'Rachis dorso-lombaire',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="scanner[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="scanner[]" id="scanner_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">IRM</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = ['Abdomen-pelvis', 'Prostate', 'Moelle osseuse']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="irm[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="irm[]" id="irm_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Scintigraphie</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = [
                'Rénale Mag3 lasix',
                'Rénale DTPA',
                'Rénale DMSA',
                'Osseuse',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="scintigraphie[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="scintigraphie[]" id="scintigraphie_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Autres examens</div>
        <div class="tw-p-4 tw-flex-1">
            <span class="<?php echo e($labelCls); ?>">Précisez :</span>
            <textarea name="autre" id="autre" rows="6"
                      placeholder="Spécifiez d'autres examens d'imagerie..."
                      class="<?php echo e($inputCls); ?> tw-resize-none tw-mt-2"></textarea>
        </div>
    </div>

</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/partials/feuille_examen_imagerie.blade.php ENDPATH**/ ?>