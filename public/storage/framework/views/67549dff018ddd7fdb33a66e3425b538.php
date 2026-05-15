
<?php $__env->startSection('title', 'CMCU | Mes Permissions de Modification'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-user-lock"></i> Mes Permissions de Modification
                </h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
        <div class="alert alert-info alert-dismissible fade show">
            <i class="fas fa-info-circle"></i> <?php echo e(session('info')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h4><?php echo e($editRequests->where('status', 'approved')->where('can_edit', true)->count()); ?></h4>
                        <p class="mb-0"><i class="fas fa-check-circle"></i> Permissions Actives</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm bg-warning text-dark">
                    <div class="card-body text-center">
                        <h4><?php echo e($editRequests->where('status', 'pending')->count()); ?></h4>
                        <p class="mb-0"><i class="fas fa-clock"></i> En Attente</p>
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
        </div>

        <?php if($editRequests->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Vous n'avez aucune demande de permission
        </div>
        <?php else: ?>

        <!-- Permissions List -->
        <?php $__currentLoopData = $editRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-<?php echo e($request->getStatusColor()); ?> text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="<?php echo e($request->getStatusIcon()); ?>"></i>
                            <?php echo e($request->produit->designation); ?>

                        </h5>
                        <small>Demandé le <?php echo e($request->created_at->format('d/m/Y à H:i')); ?></small>
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
                        <p><strong>Stock actuel:</strong> <?php echo e($request->produit->qte_stock); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Statut:</strong> 
                            <span class="badge bg-<?php echo e($request->getStatusColor()); ?>">
                                <?php echo e($request->getStatusLabel()); ?>

                            </span>
                        </p>
                        <?php if($request->reviewed_at): ?>
                        <p><strong>Traité par:</strong> <?php echo e($request->reviewedBy->name ?? 'N/A'); ?></p>
                        <p><strong>Traité le:</strong> <?php echo e($request->reviewed_at->format('d/m/Y à H:i')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Reason -->
                <div class="alert alert-light mb-3">
                    <strong><i class="fas fa-comment"></i> Votre raison:</strong><br>
                    <?php echo e($request->reason); ?>

                </div>

                <?php if($request->review_comment): ?>
                <div class="alert alert-<?php echo e($request->status === 'approved' ? 'success' : 'danger'); ?>">
                    <strong>Réponse:</strong> <?php echo e($request->review_comment); ?>

                </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="d-flex gap-2">
                    <?php if($request->isActive()): ?>
                    <a href="<?php echo e(route('produits.edit', $request->produit->id)); ?>" 
                       class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier le Produit
                    </a>
                    <?php elseif($request->isPending()): ?>
                    <span class="text-muted">
                        <i class="fas fa-clock"></i> En attente d'approbation...
                    </span>
                    <?php endif; ?>

                    <a href="<?php echo e(route('produits.edit', $request->produit->id)); ?>" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> Voir le Produit
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Pagination -->
        <div class="mt-3">
            <?php echo e($editRequests->links('pagination::bootstrap-5')); ?>

        </div>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux Produits
            </a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/produits/my_edit_permissions.blade.php ENDPATH**/ ?>