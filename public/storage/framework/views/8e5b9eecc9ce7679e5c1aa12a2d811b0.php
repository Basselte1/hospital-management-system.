
<?php $__env->startSection('title', 'CMCU | Pharmacie'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-prescription-bottle-alt"></i> Pharmacie
                </h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Ventes Aujourd'hui</h6>
                                <h3 class="mb-0"><?php echo e($stats['ventes_today']); ?></h3>
                            </div>
                            <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Montant Aujourd'hui</h6>
                                <h3 class="mb-0"><?php echo e(number_format($stats['montant_today'])); ?> FCFA</h3>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-md-4">
                <div class="card bg-warning text-dark shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">En Attente Paiement</h6>
                                <h3 class="mb-0"><?php echo e($stats['ventes_en_attente']); ?></h3>
                            </div>
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Alertes Stock</h6>
                                <h3 class="mb-0"><?php echo e($stats['stock_alerts']); ?></h3>
                            </div>
                            <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-bolt"></i> Actions Rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('pharmacie.sales.patient.create')); ?>" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-user-injured fa-2x d-block mb-2"></i>
                                    <strong>Vente Patient</strong>
                                    <small class="d-block">Avec ordonnance</small>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('pharmacie.sales.external.create')); ?>" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-hospital fa-2x d-block mb-2"></i>
                                    <strong>Vente Externe</strong>
                                    <small class="d-block">Autre hôpital/Client</small>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('pharmacie.sales.list')); ?>" class="btn btn-info btn-lg w-100">
                                    <i class="fas fa-list fa-2x d-block mb-2"></i>
                                    <strong>Liste Ventes</strong>
                                    <small class="d-block">Toutes les ventes</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Ventes Récentes</h5>
                        <a href="<?php echo e(route('pharmacie.history')); ?>" class="btn btn-sm btn-outline-primary">
                            Voir l'historique complet
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Vente</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Client</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><strong><?php echo e($vente->numero_vente); ?></strong></td>
                                        <td><?php echo e($vente->created_at->format('d/m/Y H:i')); ?></td>
                                        <td>
                                            <?php if($vente->isPatientSale()): ?>
                                            <span class="badge bg-primary">Patient</span>
                                            <?php else: ?>
                                            <span class="badge bg-success">Externe</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($vente->customer_name); ?></td>
                                        <td><strong><?php echo e(number_format($vente->montant_total)); ?> FCFA</strong></td>
                                        <td>
                                            <?php if($vente->isSoldee()): ?>
                                            <span class="badge bg-success">Soldée</span>
                                            <?php else: ?>
                                            <span class="badge bg-warning text-dark">En attente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('pharmacie.sales.show', $vente->id)); ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            Aucune vente récente
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Auto-refresh stats every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/pharmacie/index.blade.php ENDPATH**/ ?>