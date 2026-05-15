
<?php $__env->startSection('title', 'CMCU | Renseignement du dossier patient'); ?>
<?php $__env->startSection('content'); ?>
  
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
        <div id="products" class="row view-group">
                <div class="item col-xs-4 col-lg-4">
                    <div class="thumbnail card">
                        
                        <div class="caption card-body">
                            <h4 class="group card-title inner list-group-item-heading">
                                Interrogatoire</h4>
                            <p class="group inner list-group-item-text">
                                    <?php echo e($consultationdesuivi->interrogatoire); ?></p>
                                <br>
                                <div class="row">
                               
                                <div class="col-xs-12 col-md-6">
                                    <a class="btn btn-success"><?php echo e($consultationdesuivi->date_creation); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item col-xs-4 col-lg-4">
                    <div class="thumbnail card">
                        
                        <div class="caption card-body">
                            <h4 class="group card-title inner list-group-item-heading">
                                Commentaire</h4>
                            <p class="group inner list-group-item-text">
                               <?php echo e($consultationdesuivi->commentaire); ?></p>
                                <br>
                                <div class="row">
                                
                                <div class="col-xs-4 col-md-6">
                                    <a class="btn btn-success"><?php echo e($consultationdesuivi->date_creation); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
              
            </div>
        <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/suivi_consultation/show.blade.php ENDPATH**/ ?>