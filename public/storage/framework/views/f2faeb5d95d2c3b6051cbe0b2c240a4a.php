
<?php $__env->startSection('title', 'CMCU | Détails Bon de Commande'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">

        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">Bon de Commande <?php echo e($bonCommande->numero_bon); ?></h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <!-- Alerts -->
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

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col d-flex flex-wrap gap-2">

                <a href="<?php echo e(route('bon-commandes.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>

                
                <?php if(in_array(auth()->user()->role_id, [1, 3, 5])): ?>
                <a href="<?php echo e(route('bon-commandes.pdf', $bonCommande->id)); ?>"
                   class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <?php endif; ?>

                
                <?php if($bonCommande->canBeEdited() && in_array(auth()->user()->role_id, [1, 5])): ?>
                <a href="<?php echo e(route('bon-commandes.edit', $bonCommande->id)); ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <?php endif; ?>

                
                <?php if($bonCommande->isBrouillon() && in_array(auth()->user()->role_id, [1, 5])): ?>
                <form action="<?php echo e(route('bon-commandes.send-for-validation', $bonCommande->id)); ?>"
                      method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-info"
                            onclick="return confirm('Soumettre ce bon de commande au gestionnaire pour validation ?')">
                        <i class="fas fa-paper-plane"></i> Soumettre pour validation
                    </button>
                </form>
                <?php endif; ?>

                
                <?php if($bonCommande->statut === 'envoye' && in_array(auth()->user()->role_id, [1, 3])): ?>
                <button type="button" class="btn btn-success"
                        data-bs-toggle="modal" data-bs-target="#validateModal">
                    <i class="fas fa-check-circle"></i> Valider la commande
                </button>
                <button type="button" class="btn btn-danger"
                        data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times-circle"></i> Rejeter
                </button>
                <?php endif; ?>

                
                <?php if(in_array($bonCommande->statut, ['valide', 'receptionne'])
                    && in_array(auth()->user()->role_id, [1, 3, 5])
                    && $bonCommande->fournisseur_email): ?>
                <form action="<?php echo e(route('bon-commandes.send', $bonCommande->id)); ?>"
                      method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success"
                            onclick="return confirm('Envoyer ce bon de commande par email à <?php echo e($bonCommande->fournisseur_email); ?> ?')">
                        <i class="fas fa-envelope"></i> Envoyer par email
                    </button>
                </form>
                <?php endif; ?>

            </div>
        </div>

        <!-- Status Badge -->
        <div class="row mb-4">
            <div class="col text-center">
                <?php
                    $statusMap = [
                        'brouillon'   => ['secondary', 'fa-file',         'BROUILLON'],
                        'envoye'      => ['warning',   'fa-hourglass-half','EN ATTENTE DE VALIDATION'],
                        'valide'      => ['info',      'fa-check',         'VALIDÉ — EN ATTENTE DE RÉCEPTION'],
                        'receptionne' => ['success',   'fa-check-double',  'RÉCEPTIONNÉ'],
                        'annule'      => ['danger',    'fa-times',         'ANNULÉ'],
                    ];
                    [$color, $icon, $label] = $statusMap[$bonCommande->statut] ?? ['secondary', 'fa-question', strtoupper($bonCommande->statut)];
                ?>
                <span class="badge bg-<?php echo e($color); ?> px-4 py-2" style="font-size:1.1rem;">
                    <i class="fas <?php echo e($icon); ?>"></i> <?php echo e($label); ?>

                </span>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-6">

                <!-- Supplier Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-truck"></i> Informations Fournisseur</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr><th width="40%">Nom:</th><td><?php echo e($bonCommande->fournisseur_nom); ?></td></tr>
                            <?php if($bonCommande->fournisseur_email): ?>
                            <tr><th>Email:</th>
                                <td>
                                    <?php echo e($bonCommande->fournisseur_email); ?>

                                    <?php if(!in_array($bonCommande->statut, ['valide','receptionne'])): ?>
                                        <br><small class="text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            L'email ne peut être envoyé qu'après validation
                                        </small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if($bonCommande->fournisseur_telephone): ?>
                            <tr><th>Téléphone:</th><td><?php echo e($bonCommande->fournisseur_telephone); ?></td></tr>
                            <?php endif; ?>
                            <?php if($bonCommande->fournisseur_adresse): ?>
                            <tr><th>Adresse:</th><td><?php echo e($bonCommande->fournisseur_adresse); ?></td></tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar"></i> Informations Commande</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr><th width="40%">N° Bon:</th><td><strong><?php echo e($bonCommande->numero_bon); ?></strong></td></tr>
                            <tr><th>Date:</th><td><?php echo e($bonCommande->date_commande->format('d/m/Y')); ?></td></tr>
                            <?php if($bonCommande->date_livraison_souhaitee): ?>
                            <tr><th>Livraison souhaitée:</th><td><?php echo e($bonCommande->date_livraison_souhaitee->format('d/m/Y')); ?></td></tr>
                            <?php endif; ?>
                            <tr><th>Créé par:</th><td><?php echo e($bonCommande->createdBy->name ?? 'N/A'); ?></td></tr>
                            <tr><th>Date création:</th><td><?php echo e($bonCommande->created_at->format('d/m/Y H:i')); ?></td></tr>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="col-lg-6">

                <?php if($bonCommande->validated_at): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check"></i> Validation</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr><th width="40%">Validé par:</th><td><?php echo e($bonCommande->validatedBy->name ?? 'N/A'); ?></td></tr>
                            <tr><th>Date:</th><td><?php echo e($bonCommande->validated_at->format('d/m/Y H:i')); ?></td></tr>
                            <?php if($bonCommande->validation_comment): ?>
                            <tr><th>Commentaire:</th><td><?php echo e($bonCommande->validation_comment); ?></td></tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($bonCommande->received_at): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Réception</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr><th width="40%">Réceptionné par:</th><td><?php echo e($bonCommande->receivedBy->name ?? 'N/A'); ?></td></tr>
                            <tr><th>Date:</th><td><?php echo e($bonCommande->received_at->format('d/m/Y H:i')); ?></td></tr>
                            <?php if($bonCommande->reception_comment): ?>
                            <tr><th>Commentaire:</th><td><?php echo e($bonCommande->reception_comment); ?></td></tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($bonCommande->notes): ?>
                <div class="card shadow-sm mb-4 border-warning">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notes</h5>
                    </div>
                    <div class="card-body">
                        <?php echo e($bonCommande->notes); ?>

                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Products -->
        <div class="card shadow-sm mt-2">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-boxes"></i> Produits Commandés</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Désignation</th>
                                <th>Catégorie</th>
                                <th class="text-center">Qté Commandée</th>
                                <?php if($bonCommande->isReceptionne()): ?>
                                <th class="text-center">Qté Reçue</th>
                                <?php endif; ?>
                                <th class="text-end">Prix Unit.</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $bonCommande->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i + 1); ?></td>
                                <td><strong><?php echo e($item->designation); ?></strong></td>
                                <td><span class="badge bg-secondary"><?php echo e($item->categorie); ?></span></td>
                                <td class="text-center"><?php echo e($item->quantite_commandee); ?></td>
                                <?php if($bonCommande->isReceptionne()): ?>
                                <td class="text-center">
                                    <span class="badge bg-<?php echo e($item->isFullyReceived() ? 'success' : 'warning'); ?>">
                                        <?php echo e($item->quantite_recue); ?>

                                    </span>
                                </td>
                                <?php endif; ?>
                                <td class="text-end"><?php echo e(number_format($item->prix_unitaire, 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-end"><strong><?php echo e(number_format($item->montant_ligne, 0, ',', ' ')); ?> FCFA</strong></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-info">
                            <tr>
                                <td colspan="<?php echo e($bonCommande->isReceptionne() ? 6 : 5); ?>" class="text-end fw-bold">
                                    MONTANT TOTAL:
                                </td>
                                <td class="text-end fw-bold" style="font-size:1.1rem;">
                                    <?php echo e(number_format($bonCommande->montant_total, 0, ',', ' ')); ?> FCFA
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="validateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle"></i> Valider le Bon de Commande</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('bon-commandes.validate', $bonCommande->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p>Confirmer la validation de <strong><?php echo e($bonCommande->numero_bon); ?></strong> ?</p>
                    <p class="text-muted">Montant: <strong><?php echo e(number_format($bonCommande->montant_total, 0, ',', ' ')); ?> FCFA</strong></p>
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


<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle"></i> Rejeter le Bon de Commande</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('bon-commandes.reject', $bonCommande->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p>Rejeter <strong><?php echo e($bonCommande->numero_bon); ?></strong> ?</p>
                    <div class="mb-3">
                        <label class="form-label">Raison du rejet <span class="text-danger">*</span></label>
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

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/bon_commandes/show.blade.php ENDPATH**/ ?>