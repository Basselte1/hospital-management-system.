
<?php $__env->startSection('title', 'CMCU | Validation des Bons de Commande'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-warning">Validation des Bons de Commande</h1>
                <hr class="w-25 mx-auto">
                <p class="text-muted">Bons en attente de validation par le Gestionnaire</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if($pendingOrders->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucun bon de commande en attente de validation
        </div>
        <?php else: ?>

        <!-- Batch Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <form action="<?php echo e(route('bon-commandes.batch-validate')); ?>" method="POST" id="batchForm">
                            <?php echo csrf_field(); ?>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <h5 class="mb-1"><i class="fas fa-tasks"></i> Actions Groupées</h5>
                                    <p class="text-muted mb-0">
                                        <small><span id="selectedCount">0</span> bon(s) sélectionné(s)</small>
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success" id="batchValidateBtn" disabled>
                                        <i class="fas fa-check"></i> Valider la Sélection
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3" id="batchCommentSection" style="display: none;">
                                <label class="form-label">Commentaire (optionnel):</label>
                                <textarea name="batch_comment" class="form-control" rows="2"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <?php $__currentLoopData = $pendingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input order-checkbox" 
                               type="checkbox" 
                               name="order_ids[]" 
                               value="<?php echo e($bon->id); ?>"
                               form="batchForm">
                        <label class="form-check-label">
                            <h5 class="mb-0">
                                <i class="fas fa-file-invoice"></i> <?php echo e($bon->numero_bon); ?>

                            </h5>
                        </label>
                    </div>
                    <span class="badge bg-light text-dark">
                        <?php echo e(number_format($bon->montant_total, 0, ',', ' ')); ?> FCFA
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Order Info -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Fournisseur:</strong> <?php echo e($bon->fournisseur_nom); ?></p>
                        <p class="mb-1"><strong>Date Commande:</strong> <?php echo e($bon->date_commande->format('d/m/Y')); ?></p>
                        <?php if($bon->date_livraison_souhaitee): ?>
                        <p class="mb-1"><strong>Livraison Souhaitée:</strong> <?php echo e($bon->date_livraison_souhaitee->format('d/m/Y')); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Créé par:</strong> <?php echo e($bon->createdBy->name ?? 'N/A'); ?></p>
                        <p class="mb-1"><strong>Date Création:</strong> <?php echo e($bon->created_at->format('d/m/Y H:i')); ?></p>
                        <p class="mb-1"><strong>Nombre d'articles:</strong> <?php echo e($bon->items->count()); ?></p>
                    </div>
                </div>

                <?php if($bon->notes): ?>
                <div class="alert alert-info mb-3">
                    <strong>Notes:</strong> <?php echo e($bon->notes); ?>

                </div>
                <?php endif; ?>

                <!-- Products Summary -->
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Désignation</th>
                                <th>Catégorie</th>
                                <th class="text-center">Quantité</th>
                                <th class="text-end">Prix Unit.</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $bon->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->designation); ?></td>
                                <td><span class="badge bg-secondary"><?php echo e($item->categorie); ?></span></td>
                                <td class="text-center"><?php echo e($item->quantite_commandee); ?></td>
                                <td class="text-end"><?php echo e(number_format($item->prix_unitaire, 0, ',', ' ')); ?></td>
                                <td class="text-end"><?php echo e(number_format($item->montant_ligne, 0, ',', ' ')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-info">
                            <tr>
                                <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                <td class="text-end"><strong><?php echo e(number_format($bon->montant_total, 0, ',', ' ')); ?> FCFA</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <!-- View PDF -->
                    <a href="<?php echo e(route('bon-commandes.pdf', $bon->id)); ?>" 
                       class="btn btn-outline-danger" 
                       target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>

                    <!-- View Details -->
                    <a href="<?php echo e(route('bon-commandes.show', $bon->id)); ?>" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-eye"></i> Détails
                    </a>

                    <!-- Validate -->
                    <button type="button" 
                            class="btn btn-success" 
                            data-bs-toggle="modal" 
                            data-bs-target="#validateModal<?php echo e($bon->id); ?>">
                        <i class="fas fa-check"></i> Valider
                    </button>

                    <!-- Reject -->
                    <button type="button" 
                            class="btn btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#rejectModal<?php echo e($bon->id); ?>">
                        <i class="fas fa-times"></i> Rejeter
                    </button>
                </div>
            </div>
        </div>

        <!-- Validate Modal -->
        <div class="modal fade" id="validateModal<?php echo e($bon->id); ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Valider le Bon de Commande</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('bon-commandes.validate', $bon->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir valider le bon de commande <strong><?php echo e($bon->numero_bon); ?></strong> ?</p>
                            <p class="text-muted">Montant: <strong><?php echo e(number_format($bon->montant_total, 0, ',', ' ')); ?> FCFA</strong></p>
                            
                            <div class="mb-3">
                                <label class="form-label">Commentaire (optionnel):</label>
                                <textarea name="validation_comment" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Confirmer la Validation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal<?php echo e($bon->id); ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Rejeter le Bon de Commande</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('bon-commandes.reject', $bon->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir rejeter le bon de commande <strong><?php echo e($bon->numero_bon); ?></strong> ?</p>
                            
                            <div class="mb-3">
                                <label class="form-label">Raison du rejet <span class="text-danger">*</span>:</label>
                                <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i> Confirmer le Rejet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Pagination -->
        <div class="mt-3">
            <?php echo e($pendingOrders->links('pagination::bootstrap-5')); ?>

        </div>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?php echo e(route('bon-commandes.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Handle checkbox selection
    $('.order-checkbox').change(function() {
        updateBatchActions();
    });

    function updateBatchActions() {
        const checkedCount = $('.order-checkbox:checked').length;
        $('#selectedCount').text(checkedCount);
        $('#batchValidateBtn').prop('disabled', checkedCount === 0);
        
        if (checkedCount > 0) {
            $('#batchCommentSection').show();
        } else {
            $('#batchCommentSection').hide();
        }
    }

    // Confirm batch validation
    $('#batchForm').submit(function(e) {
        const count = $('.order-checkbox:checked').length;
        if (!confirm(`Valider ${count} bon(s) de commande sélectionné(s) ?`)) {
            e.preventDefault();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/bon_commandes/validation.blade.php ENDPATH**/ ?>