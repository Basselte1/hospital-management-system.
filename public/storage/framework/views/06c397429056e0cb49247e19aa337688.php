
<?php $__env->startSection('title', 'CMCU | Bons de Commande'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">Gestion des Bons de Commande</h1>
                <hr class="w-25 mx-auto">
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

        <!-- Workflow Info Alert -->
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Workflow des Bons de Commande</h5>
            <ol class="mb-0">
                <li><strong>Création:</strong> Logistique crée le bon de commande (statut: BROUILLON)</li>
                <li><strong>Validation:</strong> Gestionnaire valide le bon (statut: VALIDÉ)</li>
                <li><strong>Impression/Envoi:</strong> Après validation, le PDF peut être généré et envoyé au fournisseur</li>
                <li><strong>Réception:</strong> Le stock est réceptionné (statut: RÉCEPTIONNÉ)</li>
            </ol>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Action Buttons -->
        <?php if(in_array(auth()->user()->role_id, [1, 3, 5])): ?>
        <div class="row mb-4">
            <div class="col">
                <a href="<?php echo e(route('bon-commandes.create')); ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle"></i> Nouveau Bon de Commande
                </a>
                
                <?php if(auth()->user()->role_id === 3 || auth()->user()->role_id === 1): ?>
                <a href="<?php echo e(route('bon-commandes.validation')); ?>" class="btn btn-warning btn-lg">
                    <i class="fas fa-clipboard-check"></i> Valider les Commandes
                    <?php
                        $pendingValidation = \App\Models\BonCommande::where('statut', 'envoye')->count();
                    ?>
                    <?php if($pendingValidation > 0): ?>
                    <span class="badge bg-danger"><?php echo e($pendingValidation); ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-secondary text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($bonCommandes->where('statut', 'brouillon')->count()); ?></h3>
                        <p class="mb-0">Brouillons</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($bonCommandes->where('statut', 'envoye')->count()); ?></h3>
                        <p class="mb-0">En Attente Validation</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($bonCommandes->where('statut', 'valide')->count()); ?></h3>
                        <p class="mb-0">Validés</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($bonCommandes->where('statut', 'receptionne')->count()); ?></h3>
                        <p class="mb-0">Réceptionnés</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bons de Commande Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="bonCommandesTable" class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Bon</th>
                                        <th>Date Commande</th>
                                        <th>Fournisseur</th>
                                        <th>Montant Total</th>
                                        <th>Statut</th>
                                        <th>Validation</th>
                                        <th>Créé par</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $bonCommandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($bon->numero_bon); ?></strong>
                                        </td>
                                        <td><?php echo e($bon->date_commande->format('d/m/Y')); ?></td>
                                        <td><?php echo e($bon->fournisseur_nom); ?></td>
                                        <td><?php echo e(number_format($bon->montant_total, 0, ',', ' ')); ?> FCFA</td>
                                        <td>
                                            <?php if($bon->statut === 'brouillon'): ?>
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-file"></i> Brouillon
                                                </span>
                                            <?php elseif($bon->statut === 'envoye'): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock"></i> En Attente Validation
                                                </span>
                                            <?php elseif($bon->statut === 'valide'): ?>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-check"></i> Validé
                                                </span>
                                            <?php elseif($bon->statut === 'receptionne'): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-double"></i> Réceptionné
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times"></i> Annulé
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($bon->validated_at): ?>
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle"></i> 
                                                    <?php echo e($bon->validatedBy->name ?? 'N/A'); ?><br>
                                                    <?php echo e($bon->validated_at->format('d/m/Y')); ?>

                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted">
                                                    <i class="fas fa-hourglass-half"></i> Non validé
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?php echo e($bon->createdBy->name ?? 'N/A'); ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group-vertical btn-group-sm" role="group">
                                                <!-- View Button -->
                                                <a href="<?php echo e(route('bon-commandes.show', $bon->id)); ?>" 
                                                   class="btn btn-sm btn-outline-primary mb-1" 
                                                   title="Voir détails">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>

                                                <!-- PDF Button - REQUIRES VALIDATION -->
                                                <?php if($bon->isValide() || $bon->isEnvoye() || $bon->isReceptionne() || in_array(auth()->user()->role_id, [1, 3])): ?>
                                                <a href="<?php echo e(route('bon-commandes.pdf', $bon->id)); ?>" 
                                                   class="btn btn-sm btn-outline-danger mb-1" 
                                                   title="Télécharger PDF">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </a>
                                                <?php else: ?>
                                                <button class="btn btn-sm btn-outline-secondary mb-1" 
                                                        disabled
                                                        title="Validation requise pour imprimer">
                                                    <i class="fas fa-lock"></i> PDF
                                                </button>
                                                <?php endif; ?>

                                                <!-- Edit Button (only for brouillon) -->
                                                <?php if($bon->canBeEdited() && in_array(auth()->user()->role_id, [1, 5])): ?>
                                                <a href="<?php echo e(route('bon-commandes.edit', $bon->id)); ?>" 
                                                   class="btn btn-sm btn-outline-warning mb-1" 
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <?php endif; ?>

                                                <!-- Send to Validation Button (brouillon -> envoye) -->
                                                <?php if($bon->isBrouillon() && in_array(auth()->user()->role_id, [1, 5])): ?>
                                                <form action="<?php echo e(route('bon-commandes.send-for-validation', $bon->id)); ?>" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-primary mb-1" 
                                                            onclick="return confirm('Envoyer ce bon pour validation ?')" 
                                                            title="Envoyer pour validation">
                                                        <i class="fas fa-share"></i> Envoyer
                                                    </button>
                                                </form>
                                                <?php endif; ?>

                                                <!-- Send Email Button (only for VALIDATED orders) -->
                                                <?php if(($bon->isValide() || $bon->isEnvoye()) && in_array(auth()->user()->role_id, [1, 3, 5])): ?>
                                                <form action="<?php echo e(route('bon-commandes.send', $bon->id)); ?>" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success mb-1" 
                                                            onclick="return confirm('Envoyer ce bon de commande par email au fournisseur ?')" 
                                                            title="Envoyer par email"
                                                            <?php echo e(!$bon->fournisseur_email ? 'disabled' : ''); ?>>
                                                        <i class="fas fa-envelope"></i> Email
                                                    </button>
                                                </form>
                                                <?php endif; ?>

                                                <!-- Delete Button (only for brouillon) -->
                                                <?php if($bon->canBeEdited() && in_array(auth()->user()->role_id, [1, 5])): ?>
                                                <form action="<?php echo e(route('bon-commandes.destroy', $bon->id)); ?>" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Supprimer ce bon de commande ?')" 
                                                            title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i> Supprimer
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <i class="fas fa-inbox"></i> Aucun bon de commande trouvé
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php echo e($bonCommandes->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#bonCommandesTable').DataTable({
            language: {
                url: "<?php echo e(asset('vendor/i18n/fr_fr.json')); ?>"
            },
            pageLength: 15,
            responsive: true,
            order: [[0, 'desc']]
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/bon_commandes/index.blade.php ENDPATH**/ ?>