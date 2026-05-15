
<?php $__env->startSection('title', 'CMCU | Produits Réutilisables'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-success">Gestion des Produits Réutilisables</h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['total_reusable']); ?></h3>
                        <p class="mb-0">Produits Réutilisables</p>
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
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['usages_en_attente']); ?></h3>
                        <p class="mb-0">À Collecter</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-tasks"></i> Actions Rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('reusable-products.record-usage.form')); ?>" class="btn btn-lg btn-primary w-100">
                                    <i class="fas fa-clipboard"></i><br>
                                    Enregistrer Utilisation
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('reusable-products.usages.pending')); ?>" class="btn btn-lg btn-warning w-100">
                                    <i class="fas fa-box"></i><br>
                                    Collecter Produits (<?php echo e($stats['usages_en_attente']); ?>)
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('reusable-products.sterilizations.create.form')); ?>" class="btn btn-lg btn-info w-100">
                                    <i class="fas fa-fire"></i><br>
                                    Lancer Stérilisation
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('reusable-products.sterilizations.index', ['statut' => 'termine_en_attente'])); ?>" class="btn btn-lg btn-secondary w-100">
                                    <i class="fas fa-check-circle"></i><br>
                                    Valider Stérilisations (<?php echo e($stats['sterilisations_en_attente']); ?>)
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('reusable-products.sterilizations.index', ['statut' => 'valide'])); ?>" class="btn btn-lg btn-success w-100">
                                    <i class="fas fa-undo"></i><br>
                                    Retourner au Stock
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('reusable-products.sterilizations.index')); ?>" class="btn btn-lg btn-outline-primary w-100">
                                    <i class="fas fa-history"></i><br>
                                    Historique
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reusable Products List -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-recycle"></i> Produits Réutilisables</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Catégorie</th>
                                        <th class="text-center">Stock Total</th>
                                        <th class="text-center">En Utilisation</th>
                                        <th class="text-center">En Stérilisation</th>
                                        <th class="text-center">Disponible</th>
                                        <th>Méthode Stérilisation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $reusableProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><strong><?php echo e($produit->designation); ?></strong></td>
                                        <td><span class="badge bg-secondary"><?php echo e($produit->categorie); ?></span></td>
                                        <td class="text-center"><?php echo e($produit->qte_stock); ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark"><?php echo e($produit->qte_en_utilisation); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?php echo e($produit->qte_en_sterilisation); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?php echo e($produit->qte_stock - $produit->qte_en_utilisation - $produit->qte_en_sterilisation); ?></span>
                                        </td>
                                        <td>
                                            <small><?php echo e($produit->methode_sterilisation_recommandee ?? 'Non spécifié'); ?></small>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            Aucun produit réutilisable trouvé
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/reusable_products/index.blade.php ENDPATH**/ ?>