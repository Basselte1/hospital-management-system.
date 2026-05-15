
<?php $__env->startSection('title', 'CMCU | Demandes de modification en attente'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approveEditRequests', \App\Models\Produit::class)): ?>
    <div class="container-fluid py-4">

        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">Demandes de Modification en Attente</h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <!-- Alert Info or Batch Actions -->
        <?php if($pendingRequests->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucune demande de modification en attente
        </div>
        <?php else: ?>
        <!-- Batch Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="mb-1"><i class="fas fa-tasks"></i> Actions Groupées</h5>
                                <p class="text-muted mb-0">
                                    <small><?php echo e($pendingRequests->total()); ?> modification(s) en attente sur cette page</small>
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <!-- Approve All Edits Button -->
                                <button type="button" 
                                        class="btn btn-success btn-lg" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#batchApproveEditsModal"
                                        <?php echo e($pendingRequests->total() == 0 ? 'disabled' : ''); ?>>
                                    <i class="fas fa-check-double"></i> Tout Approuver
                                    <span class="badge bg-light text-success ms-1"><?php echo e($pendingRequests->total()); ?></span>
                                </button>

                                <!-- Reject All Edits Button -->
                                <button type="button" 
                                        class="btn btn-danger btn-lg" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#batchRejectEditsModal"
                                        <?php echo e($pendingRequests->total() == 0 ? 'disabled' : ''); ?>>
                                    <i class="fas fa-times-circle"></i> Tout Rejeter
                                    <span class="badge bg-light text-danger ms-1"><?php echo e($pendingRequests->total()); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Pending Edit Requests -->
        <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-edit"></i> Modification du produit: <?php echo e($request->produit->designation); ?>

                        </h5>
                        <small>Demandé par: <?php echo e($request->requestedBy->name); ?> le <?php echo e($request->created_at->format('d/m/Y à H:i')); ?></small>
                    </div>
                    <span class="badge bg-warning text-dark">ID: #<?php echo e($request->id); ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Champ</th>
                                <th>Valeur Actuelle</th>
                                <th>Valeur Proposée</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $request->changes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e(ucfirst(str_replace('_', ' ', $field))); ?></strong></td>
                                <td><?php echo e($change['old']); ?></td>
                                <td class="bg-warning bg-opacity-25">
                                    <strong><?php echo e($change['new']); ?></strong>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <form action="<?php echo e(route('produits.edit-requests.approve', $request->id)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Approuver
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#rejectEditModal<?php echo e($request->id); ?>">
                        <i class="fas fa-times"></i> Rejeter
                    </button>
                </div>
            </div>
        </div>

        <!-- Individual Reject Edit Modal -->
        <div class="modal fade" id="rejectEditModal<?php echo e($request->id); ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rejeter la demande de modification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('produits.edit-requests.reject', $request->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Commentaire <span class="text-danger">*</span></label>
                                <textarea name="review_comment" class="form-control" rows="3" required></textarea>
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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <div class="mt-3">
            <?php echo e($pendingRequests->links('pagination::bootstrap-5')); ?>

        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

    </div>

    <!-- Batch Approve Edits Modal -->
    <div class="modal fade" id="batchApproveEditsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-double"></i> Approuver Toutes les Modifications
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('produits.edit-requests.batch.approve')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Vous êtes sur le point d'approuver <strong>TOUTES</strong> les demandes de modification en attente.
                        </div>
                        <p class="mb-2">
                            <strong>Nombre de modifications à approuver:</strong> <?php echo e(\App\Models\ProduitEditRequest::where('status', 'pending')->count()); ?>

                        </p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Attention:</strong> Cette action appliquera immédiatement toutes les modifications aux produits concernés.
                        </div>
                        <p class="text-muted mb-0">
                            <small>Cette action approuvera toutes les modifications en attente, pas seulement celles de la page actuelle.</small>
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

    <!-- Batch Reject Edits Modal -->
    <div class="modal fade" id="batchRejectEditsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle"></i> Rejeter Toutes les Modifications
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('produits.edit-requests.batch.reject')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Vous êtes sur le point de rejeter <strong>TOUTES</strong> les demandes de modification en attente.
                        </div>
                        <p>
                            <strong>Nombre de modifications à rejeter:</strong> <?php echo e(\App\Models\ProduitEditRequest::where('status', 'pending')->count()); ?>

                        </p>
                        <div class="mb-3">
                            <label class="form-label">Commentaire (commun à tous) <span class="text-danger">*</span></label>
                            <textarea name="batch_review_comment" class="form-control" rows="3" required 
                                      placeholder="Ce commentaire sera appliqué à toutes les modifications rejetées..."></textarea>
                            <small class="text-muted">Ce commentaire sera appliqué à toutes les modifications en attente.</small>
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
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/produits/pending_edits.blade.php ENDPATH**/ ?>