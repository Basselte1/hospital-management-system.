<?php $__env->startSection('title', 'Gestion des Rendez-vous - CMCU'); ?>
<?php $__env->startSection('content'); ?>
<div id="app">
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="container-fluid">
            <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="row">
                <div class="col-12">
                    <!--  -->
                    <!-- Events Calendar Component - only pass essential props -->
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/events/index.blade.php ENDPATH**/ ?>