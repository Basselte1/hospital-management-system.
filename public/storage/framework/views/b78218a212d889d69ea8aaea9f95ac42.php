
<?php $__env->startSection('title', 'CMCU | Détails Réception'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-success">Réception <?php echo e($reception->numero_reception); ?></h1>
                <hr class="w-25 mx-auto">
                <?php if(!$reception->isValidated()): ?>
                <span class="badge bg-warning text-dark" style="font-size: 1.1rem;">
                    <i class="fas fa-clock"></i> EN ATTENTE DE VALIDATION
                </span>
                <?php else: ?>
                <span class="badge bg-success" style="font-size: 1.1rem;">
                    <i class="fas fa-check-double"></i> VALIDÉE
                </span>
                <?php endif; ?>
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col">
                <a href="<?php echo e(route('stock-receptions.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <a href="<?php echo e(route('stock-receptions.pdf', $reception->id)); ?>" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <?php if(!$reception->isValidated() && in_array(auth()->user()->role_id, [1, 3])): ?>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#validateModal">
                    <i class="fas fa-check"></i> Valider et Mettre à Jour le Stock
                </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Informations Réception</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><th width="40%">N° Réception:</th><td><strong><?php echo e($reception->numero_reception); ?></strong></td></tr>
                            <tr><th>Bon de Commande:</th><td><?php echo e($reception->bonCommande->numero_bon); ?></td></tr>
                            <tr><th>Date Réception:</th><td><?php echo e($reception->date_reception->format('d/m/Y')); ?></td></tr>
                            <tr><th>Réceptionné par:</th><td><?php echo e($reception->receivedBy->name ?? 'N/A'); ?></td></tr>
                            <?php if($reception->numero_bl): ?>
                            <tr><th>N° BL:</th><td><?php echo e($reception->numero_bl); ?></td></tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <?php if($reception->isValidated()): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Validation</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><th>Validé par:</th><td><?php echo e($reception->validatedBy->name ?? 'N/A'); ?></td></tr>
                            <tr><th>Date:</th><td><?php echo e($reception->validated_at->format('d/m/Y H:i')); ?></td></tr>
                            <?php if($reception->validation_notes): ?>
                            <tr><th>Notes:</th><td><?php echo e($reception->validation_notes); ?></td></tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <h3 class="text-primary"><?php echo e($reception->getTotalQuantiteRecue()); ?></h3>
                                <small>Reçues</small>
                            </div>
                            <div class="col-4">
                                <h3 class="text-success"><?php echo e($reception->getTotalQuantiteAcceptee()); ?></h3>
                                <small>Acceptées</small>
                            </div>
                            <div class="col-4">
                                <h3 class="text-danger"><?php echo e($reception->getTotalQuantiteRefusee()); ?></h3>
                                <small>Refusées</small>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if($reception->commentaire || $reception->problemes_constates): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Remarques</h5>
                    </div>
                    <div class="card-body">
                        <?php if($reception->commentaire): ?>
                        <p><strong>Commentaire:</strong> <?php echo e($reception->commentaire); ?></p>
                        <?php endif; ?>
                        <?php if($reception->problemes_constates): ?>
                        <div class="alert alert-danger">
                            <strong>Problèmes:</strong> <?php echo e($reception->problemes_constates); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Products -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Produits Réceptionnés</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Commandée</th>
                                        <th class="text-center">Reçue</th>
                                        <th class="text-center">Acceptée</th>
                                        <th class="text-center">Refusée</th>
                                        <th>État</th>
                                        <th>Observation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $reception->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php echo e($item->hasQualityIssues() ? 'table-warning' : ''); ?>">
                                        <td><strong><?php echo e($item->bonCommandeItem->designation); ?></strong></td>
                                        <td class="text-center"><?php echo e($item->quantite_commandee); ?></td>
                                        <td class="text-center"><?php echo e($item->quantite_recue); ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?php echo e($item->quantite_acceptee); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if($item->quantite_refusee > 0): ?>
                                            <span class="badge bg-danger"><?php echo e($item->quantite_refusee); ?></span>
                                            <?php else: ?>
                                            <span class="badge bg-secondary">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($item->etat_produit === 'conforme'): ?>
                                            <span class="badge bg-success">Conforme</span>
                                            <?php elseif($item->etat_produit === 'non_conforme'): ?>
                                            <span class="badge bg-warning text-dark">Non Conforme</span>
                                            <?php else: ?>
                                            <span class="badge bg-danger">Endommagé</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><small><?php echo e($item->observation); ?></small></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Validation Modal -->
<?php if(!$reception->isValidated() && in_array(auth()->user()->role_id, [1, 3])): ?>
<div class="modal fade" id="validateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Valider la Réception</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('stock-receptions.validate', $reception->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p>En validant cette réception, le stock des produits sera automatiquement mis à jour.</p>
                    <div class="alert alert-info">
                        <strong><?php echo e($reception->getTotalQuantiteAcceptee()); ?></strong> produit(s) seront ajoutés au stock.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes de validation (optionnel):</label>
                        <textarea name="validation_notes" class="form-control" rows="3"></textarea>
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
<?php endif; ?>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/stock_receptions/show.blade.php ENDPATH**/ ?>