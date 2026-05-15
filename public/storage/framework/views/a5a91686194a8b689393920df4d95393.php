

<?php $__env->startSection('title', 'CMCU | Liste des produits pharmaceutique'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">

        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">Liste des Produits de l'Anesthésiste</h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <!-- Facturation Button -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
        <div class="row mb-4">
            <div class="col text-end">
                <a href="<?php echo e(route('pharmaceutique.facturation')); ?>" 
                   class="btn btn-lg btn-success shadow-sm" 
                   title="Procéder à la facturation">
                    <i class="fas fa-file-invoice"></i> Facture
                    <span class="badge bg-light text-dark ms-2">
                        <?php echo e(Session::has('cart') ? Session::get('cart')->totalQte : 0); ?>

                    </span>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Products Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="produitsTable" class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Désignation</th>
                                        <th>Stock</th>
                                        <th>Alerte</th>
                                        <th>Prix Unitaire</th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                                        <th>Ajouter à la Facture</th>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Produit::class)): ?>
                                        <th>Éditer</th>
                                        <th>Supprimer</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($produit->id); ?></td>
                                        <td><?php echo e($produit->designation); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($produit->qte_stock <= $produit->qte_alerte ? 'danger' : 'success'); ?>">
                                                <?php echo e($produit->qte_stock); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($produit->qte_alerte); ?></td>
                                        <td><?php echo e(number_format($produit->prix_unitaire, 0, ',', ' ')); ?> FCFA</td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                                        <td>
                                            <a href="<?php echo e(route('pharmaceutique.cart', $produit->id)); ?>" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Ajouter à la facture">
                                                <i class="fas fa-plus-square"></i>
                                            </a>
                                        </td>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Produit::class)): ?>
                                        <td>
                                            <a href="<?php echo e(route('produits.edit',$produit->id)); ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <form action="<?php echo e(route('produits.destroy', $produit->id)); ?>" method="post" class="d-inline">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Supprimer ce produit ?')" 
                                                        title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <?php echo e($produits->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>



<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#produitsTable').DataTable({
            language: {
                url: "<?php echo e(asset('vendor/i18n/fr_fr.json')); ?>" // store the language file locally too
            },
            pageLength: 10,
            responsive: true,
            dom: 'Bfrtip', // enables buttons
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/produits/anesthesiste.blade.php ENDPATH**/ ?>