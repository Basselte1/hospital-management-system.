 <?php $__env->startSection('title', 'CMCU | Ajouter une fiche une chambre'); ?> <?php $__env->startSection('content'); ?>
    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="container">
            <h1 class="text-center">AJOUTER UNE CHAMBRE</h1>
            <hr>
            <!--  -->
            <!--  -->
            <div class="col-md-6">
                <form method="post" action="<?php echo e(route('chambres.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3 fw-bold">
                        <label for="name">NUMERO:</label>
                        <input type="text" class="form-control" name="numero"  />
                    </div>
                    <div class="mb-3 fw-bold">
                        <label for="exampleFormControlSelect1">CATEGORIE</label>
                        <select class="form-control" name="categorie" id="exampleFormControlSelect1">
                            <option value="">Veuillez choisir la catégorie</option>
                            <option value="Classique">Classique</option>
                            <option value="vip">VIP</option>
                            <option value="bloc">Bloc</option>
                        </select>
                    </div>
                    <select class="form-control" name="prix" id="exampleFormControlSelect1">
                        <option>Cout de la chambre</option>
                        <option value="2500">2500</option>
                        <option value="5000">5000</option>
                        <option value="10000">10000</option>
                        <option value="0">0</option>
                    </select>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/chambres/create.blade.php ENDPATH**/ ?>