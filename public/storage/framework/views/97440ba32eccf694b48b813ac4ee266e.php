

<?php $__env->startSection('title', 'Agenda Dr ' . $medecin->name . ' ' . $medecin->prenom . ' - CMCU'); ?>

<?php $__env->startSection('content'); ?>
<div id="app">
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <div class="container-fluid">
            <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <div class="row">
                <div class="col-12">
                    <!--  -->
                    
                    <!-- Events Calendar Component for specific medecin -->
                    <events-calendar 
                        :medecin-id="<?php echo e($medecinId); ?>"
                        medecin-name="<?php echo e($medecin->name); ?> <?php echo e($medecin->prenom); ?>"
                        :editable="<?php echo e(auth()->user()->can('update', App\Models\Event::class) ? 'true' : 'false'); ?>"
                        view-mode="calendar"
                        :can-create="<?php echo e(json_encode(auth()->user()->can('create', App\Models\Event::class))); ?>"
                        :can-update="<?php echo e(json_encode(auth()->user()->can('update', App\Models\Event::class))); ?>"
                        :can-delete="<?php echo e(json_encode(auth()->user()->can('delete', App\Models\Event::class))); ?>"
                        :user-role="<?php echo e(json_encode(auth()->user()->role_id)); ?>"
                    ></events-calendar>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/events/show.blade.php ENDPATH**/ ?>