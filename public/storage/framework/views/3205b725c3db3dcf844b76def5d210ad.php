
<?php $__env->startSection('title', 'CMCU | Demandes de Permission en Attente'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-warning">Demandes de Permission de Modification</h1>
                <hr class="w-25 mx-auto">
                <!-- <p class="text-muted">Validation par l'Admin ou le Gestionnaire</p> -->
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if($editRequests->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucune demande de permission en attente
        </div>
        <?php else: ?>

        <!-- Batch Actions -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5><i class="fas fa-tasks"></i> Actions Groupées</h5>
                        <small class="text-muted"><?php echo e($editRequests->total()); ?> demande(s) en attente</small>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="<?php echo e(route('produits.edit-permissions.batch.approve')); ?>" method="POST" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('Approuver toutes les demandes en attente ?')">
                                <i class="fas fa-check-double"></i> Approuver Toutes
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#batchRejectModal">
                            <i class="fas fa-times-circle"></i> Rejeter Toutes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Permission Requests List -->
        <?php $__currentLoopData = $editRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-key"></i> Permission pour: <?php echo e($request->produit->designation); ?>

                        </h5>
                        <small>Demandé par: <?php echo e($request->requestedBy->name); ?> (<?php echo e($request->requestedBy->role->name ?? 'N/A'); ?>)</small>
                    </div>
                    <span class="badge bg-light text-dark">
                        <?php echo e($request->created_at->format('d/m/Y H:i')); ?>

                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Produit:</strong> <?php echo e($request->produit->designation); ?></p>
                        <p><strong>Catégorie:</strong> <?php echo e($request->produit->categorie); ?></p>
                        <p><strong>Stock actuel:</strong> <?php echo e($request->produit->qte_stock); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Demandé il y a:</strong> <?php echo e($request->getTimeSinceRequest()); ?></p>
                        <p><strong>Rôle:</strong> <?php echo e($request->requestedBy->role->name ?? 'N/A'); ?></p>
                    </div>
                </div>

                <!-- Reason -->
                <div class="alert alert-light mb-3">
                    <strong><i class="fas fa-comment"></i> Raison:</strong><br>
                    <?php echo e($request->reason); ?>

                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <form action="<?php echo e(route('produits.edit-permissions.approve', $request->id)); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Approuver
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#rejectModal<?php echo e($request->id); ?>">
                        <i class="fas fa-times"></i> Rejeter
                    </button>

                    <a href="<?php echo e(route('produits.edit', $request->produit->id)); ?>" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-eye"></i> Voir Produit
                    </a>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal<?php echo e($request->id); ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Rejeter la Permission</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('produits.edit-permissions.reject', $request->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir rejeter cette demande?</p>
                            
                            <div class="mb-3">
                                <label class="form-label">Raison du rejet <span class="text-danger">*</span></label>
                                <textarea name="review_comment" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i> Confirmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Pagination -->
        <div class="mt-3">
            <?php echo e($editRequests->links('pagination::bootstrap-5')); ?>

        </div>
        <?php endif; ?>

        <!-- Navigation Buttons -->
        <div class="text-center mt-4">
            <a href="<?php echo e(route('produits.edit-permissions.history')); ?>" class="btn btn-info">
                <i class="fas fa-history"></i> Historique des Permissions
            </a>
            <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>

<!-- Batch Reject Modal -->
<div class="modal fade" id="batchRejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Rejeter Toutes les Demandes</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('produits.edit-permissions.batch.reject')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Vous êtes sur le point de rejeter toutes les demandes en attente.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Raison du rejet (commune à toutes) <span class="text-danger">*</span></label>
                        <textarea name="batch_comment" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/produits/edit_permissions_pending.blade.php ENDPATH**/ ?>