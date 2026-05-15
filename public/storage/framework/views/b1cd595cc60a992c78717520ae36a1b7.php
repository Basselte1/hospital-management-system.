
<?php $__env->startSection('title', 'CMCU | Produits en attente d\'approbation'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewPending', \App\Models\Produit::class)): ?>
    <div class="container-fluid py-4">

        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-warning">Produits en Attente d'Approbation</h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <!-- Alert Info or Batch Actions -->
        <?php if($pendingProduits->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucun produit en attente d'approbation
        </div>
        <?php else: ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', \App\Models\Produit::class)): ?>
            <!-- Batch Action Buttons -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <h5 class="mb-1"><i class="fas fa-tasks"></i> Actions Groupées</h5>
                                    <p class="text-muted mb-0">
                                        <small><?php echo e($pendingProduits->total()); ?> produit(s) en attente sur cette page</small>
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <!-- Approve All Button -->
                                    <button type="button" 
                                            class="btn btn-success btn-lg" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#batchApproveModal"
                                            <?php echo e($pendingProduits->total() == 0 ? 'disabled' : ''); ?>>
                                        <i class="fas fa-check-double"></i> Tout Approuver
                                        <span class="badge bg-light text-success ms-1"><?php echo e($pendingProduits->total()); ?></span>
                                    </button>

                                    <!-- Reject All Button -->
                                    <button type="button" 
                                            class="btn btn-danger btn-lg" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#batchRejectModal"
                                            <?php echo e($pendingProduits->total() == 0 ? 'disabled' : ''); ?>>
                                        <i class="fas fa-times-circle"></i> Tout Rejeter
                                        <span class="badge bg-light text-danger ms-1"><?php echo e($pendingProduits->total()); ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Pending Products Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-warning">
                                    <tr>
                                        <th>ID</th>
                                        <th>Désignation</th>
                                        <th>Catégorie</th>
                                        <th>Stock</th>
                                        <th>Alerte</th>
                                        <th>Prix Unitaire</th>
                                        <th>Créé par</th>
                                        <th>Date de création</th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', \App\Models\Produit::class)): ?>
                                        <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $pendingProduits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($produit->id); ?></td>
                                        <td><strong><?php echo e($produit->designation); ?></strong></td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($produit->categorie); ?></span>
                                        </td>
                                        <td><?php echo e($produit->qte_stock); ?></td>
                                        <td><?php echo e($produit->qte_alerte); ?></td>
                                        <td><?php echo e(number_format($produit->prix_unitaire, 0, ',', ' ')); ?> FCFA</td>
                                        <td><?php echo e($produit->createdBy->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($produit->created_at->format('d/m/Y H:i')); ?></td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', \App\Models\Produit::class)): ?>
                                        <td>
                                            <form action="<?php echo e(route('produits.approve', $produit->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-success" title="Approuver">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal<?php echo e($produit->id); ?>"
                                                    title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>

                                            <!-- Individual Reject Modal -->
                                            <div class="modal fade" id="rejectModal<?php echo e($produit->id); ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Rejeter le produit</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="<?php echo e(route('produits.reject', $produit->id)); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Raison du rejet <span class="text-danger">*</span></label>
                                                                    <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        
                        <div class="mt-3">
                            <?php echo e($pendingProduits->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

    </div>

    <!-- Batch Approve Modal -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', \App\Models\Produit::class)): ?>
    <div class="modal fade" id="batchApproveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-double"></i> Approuver Tous les Produits
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('produits.batch.approve')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Vous êtes sur le point d'approuver <strong>TOUS</strong> les produits en attente.
                        </div>
                        <p class="mb-0">
                            <strong>Nombre de produits à approuver:</strong> <?php echo e(\App\Models\Produit::where('status', 'pending')->count()); ?>

                        </p>
                        <p class="text-muted mb-0">
                            <small>Cette action approuvera tous les produits en attente, pas seulement ceux de la page actuelle.</small>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Confirmer l'approbation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Batch Reject Modal -->
    <div class="modal fade" id="batchRejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle"></i> Rejeter Tous les Produits
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('produits.batch.reject')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Vous êtes sur le point de rejeter <strong>TOUS</strong> les produits en attente.
                        </div>
                        <p>
                            <strong>Nombre de produits à rejeter:</strong> <?php echo e(\App\Models\Produit::where('status', 'pending')->count()); ?>

                        </p>
                        <div class="mb-3">
                            <label class="form-label">Raison du rejet (commune à tous) <span class="text-danger">*</span></label>
                            <textarea name="batch_rejection_reason" class="form-control" rows="3" required 
                                      placeholder="Cette raison sera appliquée à tous les produits rejetés..."></textarea>
                            <small class="text-muted">Cette raison sera appliquée à tous les produits en attente.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Confirmer le rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/produits/pending.blade.php ENDPATH**/ ?>