
<?php $__env->startSection('title', 'CMCU | Historique des Permissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-history"></i> Historique des Permissions de Modification
                </h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h4><?php echo e($editRequests->where('status', 'approved')->count()); ?></h4>
                        <p class="mb-0"><i class="fas fa-check-circle"></i> Approuvées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm bg-danger text-white">
                    <div class="card-body text-center">
                        <h4><?php echo e($editRequests->where('status', 'rejected')->count()); ?></h4>
                        <p class="mb-0"><i class="fas fa-times-circle"></i> Rejetées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm bg-secondary text-white">
                    <div class="card-body text-center">
                        <h4><?php echo e($editRequests->where('can_edit', false)->where('status', 'approved')->count()); ?></h4>
                        <p class="mb-0"><i class="fas fa-ban"></i> Révoquées</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if($editRequests->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucune permission traitée
        </div>
        <?php else: ?>

        <!-- Permissions List -->
        <?php $__currentLoopData = $editRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header <?php echo e($request->getStatusColor() === 'success' ? 'bg-success' : ($request->getStatusColor() === 'danger' ? 'bg-danger' : 'bg-secondary')); ?> text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="<?php echo e($request->getStatusIcon()); ?>"></i>
                            <?php echo e($request->produit->designation); ?>

                        </h5>
                        <small>
                            Demandé par: <?php echo e($request->requestedBy->name); ?> 
                            le <?php echo e($request->created_at->format('d/m/Y à H:i')); ?>

                        </small>
                    </div>
                    <span class="badge bg-light text-dark">
                        <?php echo e($request->getStatusLabel()); ?>

                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Produit:</strong> <?php echo e($request->produit->designation); ?></p>
                        <p><strong>Catégorie:</strong> <?php echo e($request->produit->categorie); ?></p>
                        <p><strong>Demandeur:</strong> <?php echo e($request->requestedBy->name); ?> (<?php echo e($request->requestedBy->role->name ?? 'N/A'); ?>)</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Traité par:</strong> <?php echo e($request->reviewedBy->name ?? 'N/A'); ?></p>
                        <p><strong>Date de traitement:</strong> 
                            <?php echo e($request->reviewed_at ? $request->reviewed_at->format('d/m/Y à H:i') : 'N/A'); ?>

                        </p>
                        <?php if($request->isRevoked()): ?>
                        <p><strong>Révoqué par:</strong> <?php echo e($request->revokedBy->name ?? 'N/A'); ?></p>
                        <p><strong>Date de révocation:</strong> 
                            <?php echo e($request->revoked_at ? $request->revoked_at->format('d/m/Y à H:i') : 'N/A'); ?>

                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Reason -->
                <div class="alert alert-light mb-3">
                    <strong><i class="fas fa-comment"></i> Raison de la demande:</strong><br>
                    <?php echo e($request->reason); ?>

                </div>

                <?php if($request->review_comment): ?>
                <div class="alert alert-<?php echo e($request->status === 'approved' ? 'success' : 'danger'); ?>">
                    <strong>Commentaire:</strong> <?php echo e($request->review_comment); ?>

                </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('produits.edit', $request->produit->id)); ?>" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> Voir le produit
                    </a>

                    <?php if($request->isActive()): ?>
                    <form action="<?php echo e(route('produits.edit-permissions.revoke', $request->id)); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-warning btn-sm" 
                                onclick="return confirm('Révoquer cette permission ?')">
                            <i class="fas fa-ban"></i> Révoquer
                        </button>
                    </form>
                    <?php endif; ?>
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
            <a href="<?php echo e(route('produits.edit-permissions.pending')); ?>" class="btn btn-warning">
                <i class="fas fa-clock"></i> Demandes en attente
            </a>
            <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/produits/edit_permissions_history.blade.php ENDPATH**/ ?>