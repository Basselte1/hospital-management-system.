 <?php $__env->startSection('title', 'CMCU | Ajouter une fiche une chambre'); ?> <?php $__env->startSection('content'); ?>
    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- //Page Content Holder  -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="container">
            <h1 class="text-center">ATTRIBUTION DE CHAMBRE AU PATIENT</h1>
            <hr>
            <!--  -->
            <!--  -->
            <div class="col-md-6">
                <form method="post" action="<?php echo e(route('chambres_status.update', $chambre->id)); ?>">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="name">NUMERO :</label>
                        <input type="text" class="form-control"  name="numero"  value="<?php echo e($chambre->numero); ?>" disabled/>
                    </div>
                    <div class="mb-3">
                        <label>CATEGORIE :</label>
                        <select class="form-control"  name="categorie" id="exampleFormControlSelect1" disabled>
                            <option value="<?php echo e($chambre->categorie); ?>"  <?php echo e($chambre->id == ' ' ? 'selected' : ''); ?>><?php echo e($chambre->categorie); ?></option>
                        </select>
                    </div>
                    <input type="hidden" name="statut" value="occupé">
                    <select class="form-control"  name="patient" id="patient" required>
                        <option>Nom du patient :</option>
                        <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($patient->name); ?>"><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <label>Nombre de jours :</label>
                    <input type="number" class="form-control  col-md-4" name="jour" value="<?php echo e(request('jour')); ?>" placeholder="nombre de jours">
                    <br>
                    <br>
            
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary ">ENREGISTRER</button>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo e(route('chambres.index')); ?>" class="btn btn-success float-end"
                            title="Retour">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </body>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/chambres/attribute.blade.php ENDPATH**/ ?>