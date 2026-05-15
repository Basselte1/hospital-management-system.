

<div id="flash-messages-portal"
     style="position: fixed; top: 1rem; right: 1rem; z-index: 99999 !important;
            display: flex; flex-direction: column; gap: 0.75rem;
            width: 100%; max-width: 24rem; pointer-events: none;">
    <div style="pointer-events: auto;">
        
        <?php if($message = Session::get('success')): ?>
        <div role="alert" data-flash="true"
             class="tw-flex tw-items-start tw-gap-3 tw-rounded-2xl tw-border tw-border-emerald-200 tw-bg-white tw-px-4 tw-py-3.5 tw-shadow-xl tw-ring-1 tw-ring-inset tw-ring-emerald-100
                    tw-animate-[flash-in_0.35s_cubic-bezier(0.34,1.56,0.64,1)_both]">
            <span class="tw-mt-0.5 tw-flex tw-h-7 tw-w-7 tw-shrink-0 tw-items-center tw-justify-center tw-rounded-xl tw-bg-emerald-100">
                <i class="fas fa-check tw-text-emerald-600 tw-text-sm"></i>
            </span>
            <div class="tw-flex-1 tw-min-w-0">
                <p class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-emerald-600 tw-mb-0.5">Succès</p>
                <p class="tw-text-sm tw-text-slate-700 tw-mb-0 tw-leading-snug"><?php echo e($message); ?></p>
            </div>
            <button type="button" onclick="this.closest('[data-flash]').remove()"
                    class="tw-mt-0.5 tw-shrink-0 tw-text-slate-300 hover:tw-text-slate-500 tw-transition-colors tw-text-lg tw-leading-none tw-cursor-pointer"
                    aria-label="Fermer">&times;</button>
        </div>
        <?php endif; ?>

        
        <?php if($message = Session::get('error')): ?>
        <div role="alert" data-flash="true"
             class="tw-flex tw-items-start tw-gap-3 tw-rounded-2xl tw-border tw-border-red-200 tw-bg-white tw-px-4 tw-py-3.5 tw-shadow-xl tw-ring-1 tw-ring-inset tw-ring-red-100
                    tw-animate-[flash-in_0.35s_cubic-bezier(0.34,1.56,0.64,1)_both]">
            <span class="tw-mt-0.5 tw-flex tw-h-7 tw-w-7 tw-shrink-0 tw-items-center tw-justify-center tw-rounded-xl tw-bg-red-100">
                <i class="fas fa-xmark tw-text-red-600 tw-text-sm"></i>
            </span>
            <div class="tw-flex-1 tw-min-w-0">
                <p class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-red-600 tw-mb-0.5">Erreur</p>
                <p class="tw-text-sm tw-text-slate-700 tw-mb-0 tw-leading-snug"><?php echo e($message); ?></p>
            </div>
            <button type="button" onclick="this.closest('[data-flash]').remove()"
                    class="tw-mt-0.5 tw-shrink-0 tw-text-slate-300 hover:tw-text-slate-500 tw-transition-colors tw-text-lg tw-leading-none tw-cursor-pointer"
                    aria-label="Fermer">&times;</button>
        </div>
        <?php endif; ?>

        
        <?php if($message = Session::get('warning')): ?>
        <div role="alert" data-flash="true"
             class="tw-flex tw-items-start tw-gap-3 tw-rounded-2xl tw-border tw-border-amber-200 tw-bg-white tw-px-4 tw-py-3.5 tw-shadow-xl tw-ring-1 tw-ring-inset tw-ring-amber-100
                    tw-animate-[flash-in_0.35s_cubic-bezier(0.34,1.56,0.64,1)_both]">
            <span class="tw-mt-0.5 tw-flex tw-h-7 tw-w-7 tw-shrink-0 tw-items-center tw-justify-center tw-rounded-xl tw-bg-amber-100">
                <i class="fas fa-triangle-exclamation tw-text-amber-600 tw-text-sm"></i>
            </span>
            <div class="tw-flex-1 tw-min-w-0">
                <p class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-amber-600 tw-mb-0.5">Attention</p>
                <p class="tw-text-sm tw-text-slate-700 tw-mb-0 tw-leading-snug"><?php echo e($message); ?></p>
            </div>
            <button type="button" onclick="this.closest('[data-flash]').remove()"
                    class="tw-mt-0.5 tw-shrink-0 tw-text-slate-300 hover:tw-text-slate-500 tw-transition-colors tw-text-lg tw-leading-none tw-cursor-pointer"
                    aria-label="Fermer">&times;</button>
        </div>
        <?php endif; ?>

        
        <?php if($message = Session::get('info')): ?>
        <div role="alert" data-flash="true"
             class="tw-flex tw-items-start tw-gap-3 tw-rounded-2xl tw-border tw-border-[#BFDBFE] tw-bg-white tw-px-4 tw-py-3.5 tw-shadow-xl tw-ring-1 tw-ring-inset tw-ring-blue-100
                    tw-animate-[flash-in_0.35s_cubic-bezier(0.34,1.56,0.64,1)_both]">
            <span class="tw-mt-0.5 tw-flex tw-h-7 tw-w-7 tw-shrink-0 tw-items-center tw-justify-center tw-rounded-xl tw-bg-[#BFDBFE]">
                <i class="fas fa-info tw-text-[#1D4ED8] tw-text-sm"></i>
            </span>
            <div class="tw-flex-1 tw-min-w-0">
                <p class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-[#1D4ED8] tw-mb-0.5">Information</p>
                <p class="tw-text-sm tw-text-slate-700 tw-mb-0 tw-leading-snug"><?php echo e($message); ?></p>
            </div>
            <button type="button" onclick="this.closest('[data-flash]').remove()"
                    class="tw-mt-0.5 tw-shrink-0 tw-text-slate-300 hover:tw-text-slate-500 tw-transition-colors tw-text-lg tw-leading-none tw-cursor-pointer"
                    aria-label="Fermer">&times;</button>
        </div>
        <?php endif; ?>

        
        <?php if($errors->any()): ?>
        <div role="alert" data-flash="true"
             class="tw-flex tw-items-start tw-gap-3 tw-rounded-2xl tw-border tw-border-red-200 tw-bg-white tw-px-4 tw-py-3.5 tw-shadow-xl tw-ring-1 tw-ring-inset tw-ring-red-100
                    tw-animate-[flash-in_0.35s_cubic-bezier(0.34,1.56,0.64,1)_both]">
            <span class="tw-mt-0.5 tw-flex tw-h-7 tw-w-7 tw-shrink-0 tw-items-center tw-justify-center tw-rounded-xl tw-bg-red-100">
                <i class="fas fa-circle-exclamation tw-text-red-600 tw-text-sm"></i>
            </span>
            <div class="tw-flex-1 tw-min-w-0">
                <p class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-red-600 tw-mb-1">Erreurs de formulaire</p>
                <ul class="tw-list-none tw-m-0 tw-p-0 tw-space-y-0.5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="tw-text-sm tw-text-slate-700 tw-leading-snug tw-flex tw-items-start tw-gap-1.5">
                        <span class="tw-mt-1.5 tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-red-400 tw-shrink-0"></span>
                        <?php echo e($error); ?>

                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <button type="button" onclick="this.closest('[data-flash]').remove()"
                    class="tw-mt-0.5 tw-shrink-0 tw-text-slate-300 hover:tw-text-slate-500 tw-transition-colors tw-text-lg tw-leading-none tw-cursor-pointer"
                    aria-label="Fermer">&times;</button>
        </div>
        <?php endif; ?>
    </div>
</div>


<?php if(Session::hasAny(['success','error','warning','info']) || $errors->any()): ?>
<style>
    @keyframes flash-in {
        from { opacity: 0; transform: translateX(1.5rem) scale(0.95); }
        to   { opacity: 1; transform: translateX(0)       scale(1);    }
    }
    [data-flash] {
        transition: opacity 0.3s ease, transform 0.3s ease, max-height 0.35s ease;
    }
    [data-flash].tw-dismissing {
        opacity: 0;
        transform: translateX(1rem);
        max-height: 0;
        overflow: hidden;
        padding-top: 0;
        padding-bottom: 0;
        margin: 0;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var DISMISS_AFTER = 5000;
        var ANIM_DURATION = 350;

        function dismissFlash(el) {
            el.classList.add('tw-dismissing');
            setTimeout(function () { el.remove(); }, ANIM_DURATION);
        }

        document.querySelectorAll('[data-flash="true"]').forEach(function (el, idx) {
            el.style.animationDelay = (idx * 80) + 'ms';
            setTimeout(function () {
                dismissFlash(el);
            }, DISMISS_AFTER + idx * 80);
        });
    });
</script>
<?php endif; ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/partials/flash_messages.blade.php ENDPATH**/ ?>