
<?php $__env->startSection('title', 'CMCU | Produits Réutilisables'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-success">
                    <i class="fas fa-recycle"></i> Produits Réutilisables
                </h1>
                <hr class="w-25 mx-auto">
                <p class="text-muted">Liste complète des produits marqués comme réutilisables</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['total']); ?></h3>
                        <p class="mb-0">Total Réutilisables</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['disponible']); ?></h3>
                        <p class="mb-0">Disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['en_utilisation']); ?></h3>
                        <p class="mb-0">En Utilisation</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['en_sterilisation']); ?></h3>
                        <p class="mb-0">En Stérilisation</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Products Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Catégorie</th>
                                        <th class="text-center">Stock Total</th>
                                        <th class="text-center">Disponible</th>
                                        <th class="text-center">En Utilisation</th>
                                        <th class="text-center">En Stérilisation</th>
                                        <th>Méthode Stérilisation</th>
                                        <th>Température</th>
                                        <th>Durée</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $reusableProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $disponible = $produit->qte_stock - $produit->qte_en_utilisation - $produit->qte_en_sterilisation;
                                    ?>
                                    <tr>
                                        <td><strong><?php echo e($produit->designation); ?></strong></td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo e($produit->categorie); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark"><?php echo e($produit->qte_stock); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-<?php echo e($disponible > 0 ? 'success' : 'danger'); ?>">
                                                <?php echo e($disponible); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark"><?php echo e($produit->qte_en_utilisation); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?php echo e($produit->qte_en_sterilisation); ?></span>
                                        </td>
                                        <td>
                                            <?php if($produit->methode_sterilisation_recommandee): ?>
                                                <small class="badge bg-primary">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $produit->methode_sterilisation_recommandee))); ?>

                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted">Non défini</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($produit->temperature_sterilisation): ?>
                                                <small><?php echo e($produit->temperature_sterilisation); ?>°C</small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($produit->duree_sterilisation_recommandee): ?>
                                                <small><?php echo e($produit->duree_sterilisation_recommandee); ?> min</small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(in_array(auth()->user()->role_id, [1, 5, 7])): ?>
                                            <a href="<?php echo e(route('produits.edit-reusable-settings', $produit->id)); ?>" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Modifier paramètres">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            Aucun produit réutilisable trouvé
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php echo e($reusableProducts->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="row mt-4">
            <div class="col text-center">
                <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Retour à la Liste Générale
                </a>
                <?php if(in_array(auth()->user()->role_id, [1, 4, 5, 7])): ?>
                <a href="<?php echo e(route('reusable-products.index')); ?>" class="btn btn-success btn-lg">
                    <i class="fas fa-recycle"></i> Gestion des Réutilisables
                </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/produits/reusable_list.blade.php ENDPATH**/ ?>