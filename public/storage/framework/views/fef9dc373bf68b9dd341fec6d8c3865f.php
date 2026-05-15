 <?php $__env->startSection('title', 'CMCU | Liste des produits pharmaceutique'); ?> <?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="container">
                <h2 class="text-center">FACTURATION</h2>
                <div class="row">
                    <div class="col-md-12 col-lg-10 offset-md-1">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th class="">Prix unitaire</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Reduire</th>
                                    <th class="text-center">Ajouter</th>
                                    <th class="text-center">Supprimer</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(Session::has('cart')): ?>
                                <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="col-md-8 col-lg-6">
                                        <div class="media-body">
                                            <p class=""><?php echo e($produit['item']['designation']); ?></p>
                                        </div>
                                    </td>
                                    <td class="col-md-1 col-lg-1" style="text-align: center">
                                        <input type="number" class="form-control" id="exampleInputEmail1" value="<?php echo e($produit['quantite']); ?>">
                                    </td>
                                    <td class="col-md-1 col-lg-1 text-center"><strong><?php echo e($produit['prix_unitaire']); ?></strong></td>
                                    <td class="col-md-1 col-lg-1 text-center"><strong><?php echo e($totalPrix); ?></strong></td>
                                    <td>
                                        <a href="<?php echo e(route('facturation.reduire', ['id' => $produit['item']['id']])); ?>" title="Reduire la quantité" class="btn btn-primary"> <i class="fas fa-minus"></i></a>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('pharmaceutique.cart', $produit['item']['id'])); ?>" class="btn btn-success" title="Ajouter la quantité"><i class="fas fa-plus-square"></i></a>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('facturation.supprimer', ['id' => $produit['item']['id']])); ?>" title="Supprimer le produit de la facture" class="btn btn-danger"> <i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>
                                    <h3>Total</h3>
                                </td>
                                <td class="text-end">
                                    <h3><strong><?php echo e($totalPrix); ?></strong></h3>
                                </td>
                            </tr>
                            <tr>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>

                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <form action="<?php echo e(route('pharmacie.pdf')); ?>" method="post" class="mb-3">
                            <?php echo csrf_field(); ?>
                            <td>
                                <label for="patient"><b>Nom du patient :</b></label>
                                <select name="patient" id="patient" class="form-control col-md-5 mb-2">
                                    <option value="">Nom du patient</option>
                                    <?php $__currentLoopData = $patient; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patients): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($patients->name); ?>"><?php echo e($patients->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Produit::class)): ?>
                            <td>
                                <a href="<?php echo e(route('produits.pharmaceutique')); ?>" title="Retour à la liste des produits" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Ajouter des produits</a>
                            </td>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Produit::class)): ?>
                            <td>
                                <a href="<?php echo e(route('produits.anesthesiste')); ?>" title="Retour à la liste des produits" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Ajouter des produits</a>
                            </td>
                            <?php endif; ?>

                            <td>
                                <button type="submit" href="<?php echo e(route('pharmacie.pdf')); ?>" title="Imprimer la facture" class="btn btn-success float-end">Imprimer <i class="fas fa-print"></i></button>
                            </td>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

    </div>
    </div>
    <script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
    </body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/produits/facturation.blade.php ENDPATH**/ ?>