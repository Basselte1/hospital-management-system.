

<?php $__env->startSection('title', 'CMCU | Liste des produits pharmaceutique'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
        <div class="container">
            <h1 class="text-center">LISTE DES PRODUITS DE L'ANESTHESISTE</h1>
        </div>
        <hr>
        <div class="container">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
            <a href="<?php echo e(route('pharmaceutique.facturation')); ?>" title="Proceder à la facturation" class="btn btn-success btn-sm col-md-1 float-end">
                Facture
                <span class="badge text-dark"><p><?php echo e(Session::has('cart') ? Session::get('cart')->totalQte : 0); ?></p></span>
            </a>
            <?php endif; ?>

            </br>
            </br>
            </br>
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <table id="myTable" class="table table-bordered table-striped" width="100%">
                            <thead>
                            <tr>
                                <td>ID</td>
                                <td>DESIGNATION</td>
                                <td>QUANTITE STOCK</td>
                                <td>QUANTITE ALERTE</td>
                                <td>PRIX UNITAIRE</td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                                <td>AJOUTER A LA FACTURE</td>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Produit::class)): ?>
                                <td>EDITER</td>
                                <td>SUPPRIMER</td>
                                <?php endif; ?>
                            </tr>
                            <tbody>
                            <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($produit->id); ?></td>
                                    <td><?php echo e($produit->designation); ?></td>
                                    <td><?php echo e($produit->qte_stock); ?></td>
                                    <td><?php echo e($produit->qte_alerte); ?></td>
                                    <td><?php echo e($produit->prix_unitaire); ?></td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                                    <td><a href="<?php echo e(route('pharmaceutique.cart', $produit->id)); ?>" class="btn btn-success" title="Ajouter à la facture"><i class="fas fa-plus-square"></i></a></td>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Produit::class)): ?>
                                    <td><a href="<?php echo e(route('produits.edit',$produit->id)); ?>" title="Enregistrer une nouvelle entré en stock" class="btn btn-primary"><i class="far fa-edit"></i></a></td>
                                    <td>
                                        <form action="<?php echo e(route('produits.destroy', $produit->id)); ?>" method="post">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-danger" title="Supprimer le produit du stock" type="submit"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
    </body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/produits/anesthesiste.blade.php ENDPATH**/ ?>