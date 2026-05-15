

<?php
$sectionHeaderCls = 'tw-text-xs tw-font-bold tw-text-white tw-uppercase tw-tracking-wider tw-text-center tw-py-2.5 tw-px-4 tw-rounded-t-xl tw-bg-[#1D4ED8] tw--mx-px tw--mt-px';
$checkItemCls     = 'tw-flex tw-items-center tw-gap-2.5 tw-py-1.5 tw-text-sm tw-text-slate-700 tw-cursor-pointer hover:tw-text-[#1D4ED8] tw-transition-colors tw-group';
$checkboxCls      = 'tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-accent-[#1D4ED8] tw-cursor-pointer tw-shrink-0';
$inputCls         = 'tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-800 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10 tw-transition-colors tw-mt-1';
$labelCls         = 'tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mt-4 tw-mb-0.5';
?>

<div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-3 2xl:tw-grid-cols-4 tw-gap-4 tw-p-4">

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Hématologie</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = ['NFS','Groupe rhésus','Vitesse de sédimentation','CRP']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="hematologie[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="hematologie[]" id="hematologie_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Hémostase</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = [
                'Temps de coagulation',
                'Temps de céphaline activé',
                'TKC',
                'Temps de saignement',
                'Temps de thrombine',
                'Taux de prothrombine (INR)',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="hemostase[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="hemostase[]" id="hemostase_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Biochimie</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = [
                'Glycémie',
                'Ionogramme Na+/K+/Cl-/Ca+',
                'Acide urique',
                'Créatinine',
                'Clairance de la créatinine',
                'Amylases',
                'Lipases',
                'Gamma GT',
                'Transaminases (GOT-GPT)',
                'Cholestérol (HDL-LDL)',
                'LDH',
                'Phosphatases alcalines',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="biochimie[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="biochimie[]" id="biochimie_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Hormonologie / Sérologie</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = [
                'FSH','LH','Prolactine','TSH','PTH','AMH','Inhibine B',
                'Testostérone total et libre',
                'Sérologie chlamydia',
                'Sérologie syphilis',
                'Sérologie Hépatite C',
                'Sérologie Hépatite B',
                'Caryotype',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="hormonologie[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="hormonologie[]" id="hormonologie_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Marqueurs Tumoraux</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = ['PSA Total','PSA Libre','AFP','BHCG','CEA','CA 15.3','CA 125','CA 19.9']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="marqueurs[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="marqueurs[]" id="marqueurs_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Bactériologie / Parasitologie</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = [
                'Prélèvement urétral',
                'Prélèvement pus',
                'Recherche chlamydia',
                'Goutte épaisse',
                'Hémoculture',
                'Recherche BK crachats',
                'Coproculture',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="bacteriologie[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="bacteriologie[]" id="bacteriologie_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Spermiologie</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = [
                'Spermogramme',
                'Spermoculture',
                'Contrôle Post Vasectomie',
                'Fructose',
                'Alpha Glucosidase',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="spermiologie[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="spermiologie[]" id="spermiologie_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

    
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="<?php echo e($sectionHeaderCls); ?>">Urines</div>
        <div class="tw-p-4 tw-flex-1">
            <?php $__currentLoopData = [
                'Anatomo-Cytopathologie',
                'Recherche BK',
                'Examen Cytobactériologique',
                'Recherche Schistosomiase',
                'Recherche Bilharziose',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="<?php echo e($checkItemCls); ?>">
                <input type="checkbox" name="urines[]" value="<?php echo e($item); ?>" class="<?php echo e($checkboxCls); ?>">
                <?php echo e($item); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span class="<?php echo e($labelCls); ?>">Autres :</span>
            <input type="text" name="urines[]" id="urines_autres" class="<?php echo e($inputCls); ?>" placeholder="Préciser…">
        </div>
    </div>

</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/partials/feuille_examen_biologie.blade.php ENDPATH**/ ?>