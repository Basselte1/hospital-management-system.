 <?php $__env->startSection('title', 'CMCU | Modifier une chambre'); ?> <?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
        <div class="container">
            <h1 class="text-center">MODIFIER UNE CHAMBRE</h1>
            <hr>
            <!--  -->
            <div class="col-md-6">
                <form method="post" action="<?php echo e(route('chambres.update', $chambre->id)); ?>">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">numero:</label>
                        <input type="text" class="form-control" name="numero" value=<?php echo e($chambre->numero); ?> required/>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlSelect1">CATEGORIE</label>
                        <select class="form-control" name="categorie"  id="exampleFormControlSelect1" required>
                            <option >classique</option>
                            <option>mvp</option>
                            <option>vip</option>
                            <!-- <option value="classique" <?php echo e($chambre->categorie == 'classique' ? 'selected' : ''); ?>>classique</option>
                            <option value="mvp" <?php echo e($chambre->categorie == 'mvp' ? 'selected' : ''); ?>>mvp</option>
                            <option value="vip" <?php echo e($chambre->categorie == 'vip' ? 'selected' : ''); ?>>vip</option> -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlSelect1">PRIX</label>
                        <select class="form-control" name="prix"  id="exampleFormControlSelect1" value="<?php echo e($chambre->prix); ?>" required>
                            <option >2500</option>
                            <option>5000</option>
                            <option>10000</option>
                            <!-- <option value="2500" <?php echo e($chambre->prix == '2500' ? 'selected' : ''); ?>>2500</option>
                            <option value="5000" <?php echo e($chambre->prix == '5000' ? 'selected' : ''); ?>>5000</option>
                            <option value="10000" <?php echo e($chambre->prix == '10000' ? 'selected' : ''); ?>>10000</option> -->
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">MODIFIER</button>
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


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/chambres/edit.blade.php ENDPATH**/ ?>