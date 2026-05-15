

<?php $__env->startSection('title', 'CMCU | Ajouter un produit'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Produit::class)): ?>
        <div class="container">
            <h1 class="text-center">AJOUTER UN PRODUIT</h1>
            <hr>
            <div class="row">
                <div class="col-md-7">
                    <!--  -->
                </div>
            </div>

            <div class="card" style="width: 40rem;">
                <div class="card-body">
                    <small class="text-info" title="Les champs marqués par une étoile rouge sont obligatoire"><i class="fas fa-info-circle"></i></small>
                    <hr>
                    <form class="mb-3 col-md-10" method="post" action="<?php echo e(route('produits.store')); ?>">
                        <div class="mb-3">
                            <?php echo csrf_field(); ?>
                            <label for="designation">Désignation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="designation" value="<?php echo e(old('designation')); ?>" required/>
                        </div>
                        <div class="mb-3">
                            <label for="categorie">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-control" name="categorie" id="categorie" required>
                                <option value="PHARMACEUTIQUE">PHARMACEUTIQUE</option>
                                <option value="MATERIEL">MATERIEL</option>
                                <option value="ANESTHESISTE">ANESTHESISTE</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="qte_stock">Quantité <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="qte_stock" value="<?php echo e(old('qte_stock')); ?>" required/>
                        </div>
                        <div class="mb-3">
                            <label for="qte_alerte">Quanité d'alerte <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="qte_alerte" value="<?php echo e(old('qte_alerte')); ?>" required/>
                        </div>
                        <div class="mb-3">
                            <label for="prix_unitaire">Prix unitaire <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prix_unitaire" value="<?php echo e(old('prix_unitaire')); ?>" required/>
                        </div>
                        <button type="submit" class="btn btn-primary" title="Enregistrement d'un nouveau produit">Enregistrer</button>
                        <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-info float-end" title="Retour à la liste des produits"><i
                                class="fas fa-arrow-left"></i> Retour à la liste des produits</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    </body>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/produits/create.blade.php ENDPATH**/ ?>