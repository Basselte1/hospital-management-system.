<?php if($paginator->hasPages()): ?>
<nav role="navigation" aria-label="Pagination Navigation"
     class="tw-flex tw-flex-col sm:tw-flex-row tw-items-center tw-justify-between tw-gap-3 tw-flex-wrap">

    
    <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-shrink-0 tw-order-2 sm:tw-order-1">
        Affichage de
        <strong class="tw-font-semibold tw-text-slate-600"><?php echo e($paginator->firstItem()); ?></strong>
        à
        <strong class="tw-font-semibold tw-text-slate-600"><?php echo e($paginator->lastItem()); ?></strong>
        sur
        <strong class="tw-font-semibold tw-text-slate-600"><?php echo e($paginator->total()); ?></strong>
        résultat<?php echo e($paginator->total() > 1 ? 's' : ''); ?>

    </p>

    
    <div class="tw-flex tw-items-center tw-gap-1 tw-flex-wrap tw-order-1 sm:tw-order-2">

        
        <?php if($paginator->onFirstPage()): ?>
            <span aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>"
                  class="tw-inline-flex tw-items-center tw-justify-center
                         tw-h-8 tw-w-8 tw-rounded-lg
                         tw-bg-slate-50 tw-border tw-border-slate-100
                         tw-text-slate-300 tw-cursor-not-allowed tw-select-none">
                <i class="fas fa-chevron-left tw-text-[10px]"></i>
            </span>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev"
               aria-label="<?php echo app('translator')->get('pagination.previous'); ?>"
               class="tw-inline-flex tw-items-center tw-justify-center
                      tw-h-8 tw-w-8 tw-rounded-lg
                      tw-bg-white tw-border tw-border-slate-200 tw-text-slate-600
                      hover:tw-bg-[#1D4ED8] hover:tw-text-white hover:tw-border-[#1D4ED8]
                      tw-transition-colors tw-duration-150 tw-no-underline">
                <i class="fas fa-chevron-left tw-text-[10px]"></i>
            </a>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            
            <?php if(is_string($element)): ?>
                <span aria-disabled="true"
                      class="tw-inline-flex tw-items-center tw-justify-center
                             tw-h-8 tw-w-8 tw-text-xs tw-font-semibold
                             tw-text-slate-400 tw-select-none">
                    &hellip;
                </span>
            <?php endif; ?>

            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        
                        <span aria-current="page"
                              class="tw-inline-flex tw-items-center tw-justify-center
                                     tw-h-8 tw-min-w-[2rem] tw-px-2.5 tw-rounded-lg
                                     tw-bg-[#1D4ED8] tw-text-white tw-border tw-border-[#1D4ED8]
                                     tw-text-xs tw-font-bold tw-select-none tw-shadow-sm">
                            <?php echo e($page); ?>

                        </span>
                    <?php else: ?>
                        
                        <a href="<?php echo e($url); ?>" aria-label="Page <?php echo e($page); ?>"
                           class="tw-inline-flex tw-items-center tw-justify-center
                                  tw-h-8 tw-min-w-[2rem] tw-px-2.5 tw-rounded-lg
                                  tw-bg-white tw-border tw-border-slate-200 tw-text-slate-600
                                  hover:tw-bg-[#1D4ED8] hover:tw-text-white hover:tw-border-[#1D4ED8]
                                  tw-text-xs tw-font-semibold tw-transition-colors tw-duration-150
                                  tw-no-underline">
                            <?php echo e($page); ?>

                        </a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next"
               aria-label="<?php echo app('translator')->get('pagination.next'); ?>"
               class="tw-inline-flex tw-items-center tw-justify-center
                      tw-h-8 tw-w-8 tw-rounded-lg
                      tw-bg-white tw-border tw-border-slate-200 tw-text-slate-600
                      hover:tw-bg-[#1D4ED8] hover:tw-text-white hover:tw-border-[#1D4ED8]
                      tw-transition-colors tw-duration-150 tw-no-underline">
                <i class="fas fa-chevron-right tw-text-[10px]"></i>
            </a>
        <?php else: ?>
            <span aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.next'); ?>"
                  class="tw-inline-flex tw-items-center tw-justify-center
                         tw-h-8 tw-w-8 tw-rounded-lg
                         tw-bg-slate-50 tw-border tw-border-slate-100
                         tw-text-slate-300 tw-cursor-not-allowed tw-select-none">
                <i class="fas fa-chevron-right tw-text-[10px]"></i>
            </span>
        <?php endif; ?>

    </div>
</nav>
<?php endif; ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>