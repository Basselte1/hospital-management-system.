
<?php $__env->startSection('title', 'CMCU | Demandes de Modification en Attente'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-warning">Demandes de Modification de Produits</h1>
                <hr class="w-25 mx-auto">
                <p class="text-muted">Validation par le Gestionnaire</p>
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if($editRequests->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucune demande de modification en attente
        </div>
        <?php else: ?>

        <!-- Batch Actions -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="<?php echo e(route('produits.edit-requests.batch.approve')); ?>" method="POST" id="batchForm">
                    <?php echo csrf_field(); ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5><i class="fas fa-tasks"></i> Actions Groupées</h5>
                            <small class="text-muted"><span id="selectedCount">0</span> demande(s) sélectionnée(s)</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success" id="batchApproveBtn" disabled>
                                <i class="fas fa-check"></i> Approuver la Sélection
                            </button>
                            <button type="button" class="btn btn-danger" id="batchRejectBtn" disabled
                                    data-bs-toggle="modal" data-bs-target="#batchRejectModal">
                                <i class="fas fa-times"></i> Rejeter la Sélection
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Requests List -->
        <?php $__currentLoopData = $editRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input request-checkbox" 
                               type="checkbox" 
                               name="request_ids[]" 
                               value="<?php echo e($request->id); ?>"
                               form="batchForm">
                        <label class="form-check-label">
                            <h5 class="mb-0">
                                <i class="fas fa-edit"></i> <?php echo e($request->produit->designation); ?>

                            </h5>
                        </label>
                    </div>
                    <span class="badge bg-light text-dark">
                        <?php echo e($request->created_at->format('d/m/Y H:i')); ?>

                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Demandé par:</strong> <?php echo e($request->requestedBy->name); ?></p>
                        <p><strong>Rôle:</strong> <?php echo e($request->requestedBy->role->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Produit ID:</strong> <?php echo e($request->produit->id); ?></p>
                        <p><strong>Catégorie:</strong> <?php echo e($request->produit->categorie); ?></p>
                    </div>
                </div>

                <!-- Changes Summary -->
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Champ</th>
                                <th>Valeur Actuelle</th>
                                <th>Nouvelle Valeur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $request->proposed_changes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $newValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e(ucfirst($field)); ?></strong></td>
                                <td><?php echo e($request->original_data[$field] ?? 'N/A'); ?></td>
                                <td class="text-primary"><strong><?php echo e($newValue); ?></strong></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" 
                            data-bs-target="#approveModal<?php echo e($request->id); ?>">
                        <i class="fas fa-check"></i> Approuver
                    </button>
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

        <!-- Approve Modal -->
        <div class="modal fade" id="approveModal<?php echo e($request->id); ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Approuver la Modification</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('produits.edit-requests.approve', $request->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir approuver cette modification?</p>
                            <p class="text-muted">Le produit <strong><?php echo e($request->produit->designation); ?></strong> sera mis à jour.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Confirmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal<?php echo e($request->id); ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Rejeter la Modification</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('produits.edit-requests.reject', $request->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir rejeter cette modification?</p>
                            
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

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary">
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
                <h5 class="modal-title">Rejeter la Sélection</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('produits.edit-requests.batch.reject')); ?>" method="POST" id="batchRejectForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Raison du rejet <span class="text-danger">*</span></label>
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

<?php $__env->startPush('scripts'); ?>
<script>

waitForjQuery(function() {

$(document).ready(function() {
    // Handle checkbox selection
    $('.request-checkbox').change(function() {
        updateBatchActions();
    });

    function updateBatchActions() {
        const checkedCount = $('.request-checkbox:checked').length;
        $('#selectedCount').text(checkedCount);
        $('#batchApproveBtn, #batchRejectBtn').prop('disabled', checkedCount === 0);
    }

    // Sync checkboxes between forms
    $('#batchRejectForm').submit(function() {
        // Copy selected checkboxes to reject form
        $('#batchRejectForm').append($('.request-checkbox:checked').clone());
    });
});
</script>

});

<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/produits/edit_requests_pending.blade.php ENDPATH**/ ?>