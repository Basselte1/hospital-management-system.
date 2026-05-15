

<?php $__env->startSection('title', 'CMCU | Ajouter un rôle'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="container">
            <form action="<?php echo e(route('roles.store')); ?>" method="POST">
                <div class="row">
                    <div class="col-6">
                        <!--  -->
                    </div>
                </div>
                <?php echo csrf_field(); ?>

                <h4 class="">Ajouter un nouveau rôle</h4>
                <small class="text-info" title="Le champs rôle est obligatoire et un rôle ne peut être enregistrer plusieurs fois"><i class="fas fa-info-circle"></i>Le champs rôle est obligatoire et un rôle ne peut être enregistrer plusieurs fois</small>
                <div class="row">
                    <div>
                        <hr>
                    </div>
                </div>
                <label for="name">Nom du rôle  <span class="text-danger">*</span></label>
                <br>
                <br>
                <input name="name" class="form-control col-6" type="text" value="<?php echo e(old('name')); ?>" placeholder="Nom du rôle" required><br>

                <button type="submit" class="btn btn-primary btn-sm col-2" title="Valider votre enregistrement">Ajouter</button>
                <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-info btn-sm col-2" title="Annuller">Retour</a>
                <!-- <h4>Ajouter un nouveau rôle</h4>
                <small class="text-info" title="Le champs rôle est obligatoire et un rôle ne peut être enregistrer plusieurs fois"><i class="fas fa-info-circle"></i>Le champs rôle est obligatoire et un rôle ne peut être enregistrer plusieurs fois</small>
                <div class="row">
                    <div class=" my-4">
                        <hr class="my-4">
                    </div>
                </div>
                <label for="name">Nom du rôle  <span class="text-danger">*</span></label>
                <br>

                
                   
                <input name="name" class="form-control col-md-6" type="text" value="<?php echo e(old('name')); ?>" placeholder="Nom du rôle" required><br>
                <div class="row mt-4">
                <div class="col-md-12 ">
                     <button type="submit" class="btn btn-primary btn-sm col-md-2" title="Valider votre enregistrement">Ajouter</button>
                    <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-info btn-sm col-md-2" title="Annuller">Retour</a> -->
                    <!-- <div class="row g-3 align-self-centers justify-content-end">
                        <div class="col align-self-center">
                            <button type="submit" class="btn btn-primary btn-sm w-50" title="Valider votre enregistrement">Ajouter</button>
                        </div>    

                        <div class="col align-self-center">
                            <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-secondary btn-sm w-50" title="Annuler">Retour</a>
                        </div> -->
                        
                    </div>
                </div>
                </div>
            </form>
        </div>


        
    </div>
    </body>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/roles/create.blade.php ENDPATH**/ ?>