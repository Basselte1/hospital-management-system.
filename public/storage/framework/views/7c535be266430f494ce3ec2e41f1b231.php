
<?php $__env->startSection('title', 'CMCU | Commandes Prêtes pour Réception'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-info">Commandes Prêtes pour Réception</h1>
                <hr class="w-25 mx-auto">
                <p class="text-muted">Bons de commande validés en attente de réception du stock</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if($ordersReady->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucune commande en attente de réception
        </div>
        <?php else: ?>

        <!-- Orders Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-info">
                                    <tr>
                                        <th>N° Bon</th>
                                        <th>Fournisseur</th>
                                        <th>Date Commande</th>
                                        <th>Montant</th>
                                        <th>Validé par</th>
                                        <th>Date Validation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $ordersReady; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong><?php echo e($bon->numero_bon); ?></strong></td>
                                        <td><?php echo e($bon->fournisseur_nom); ?></td>
                                        <td><?php echo e($bon->date_commande->format('d/m/Y')); ?></td>
                                        <td><?php echo e(number_format($bon->montant_total, 0, ',', ' ')); ?> FCFA</td>
                                        <td>
                                            <small><?php echo e($bon->validatedBy->name ?? 'N/A'); ?></small>
                                        </td>
                                        <td>
                                            <small><?php echo e($bon->validated_at ? $bon->validated_at->format('d/m/Y') : 'N/A'); ?></small>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('bon-commandes.show', $bon->id)); ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="<?php echo e(route('stock-receptions.create', $bon->id)); ?>" 
                                               class="btn btn-sm btn-success" 
                                               title="Réceptionner">
                                                <i class="fas fa-box"></i> Réceptionner
                                            </a>

                                            <a href="<?php echo e(route('bon-commandes.pdf', $bon->id)); ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               title="Télécharger PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php echo e($ordersReady->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?php echo e(route('bon-commandes.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/stock_receptions/ready.blade.php ENDPATH**/ ?>