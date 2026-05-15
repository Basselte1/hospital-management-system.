<?php $__env->startSection('title', 'Gestion des Rendez-vous - CMCU'); ?>

<?php $__env->startSection('content'); ?>
<div id="app">
    <div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-0">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
            <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
                <events-calendar
                    :editable="true"
                    view-mode="timeline"
                    :can-create="<?php echo e(json_encode(auth()->user()->can('create', App\Models\Event::class))); ?>"
                    :can-update="<?php echo e(json_encode(auth()->user()->can('update', App\Models\Event::class))); ?>"
                    :can-delete="<?php echo e(json_encode(auth()->user()->can('delete', App\Models\Event::class))); ?>"
                    :user-role="<?php echo e(auth()->user()->role_id); ?>"
                    <?php if(auth()->user()->role_id === 2): ?>
                        :medecin-id="<?php echo e(auth()->user()->id); ?>"
                        medecin-name="<?php echo e(auth()->user()->name . ' ' . auth()->user()->prenom); ?>"
                    <?php endif; ?>
                ></events-calendar>
            </main>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/events/index.blade.php ENDPATH**/ ?>