
<?php $__env->startSection('title', 'CMCU | Réceptions de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">Historique des Réceptions</h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Réception</th>
                                        <th>Bon Commande</th>
                                        <th>Date</th>
                                        <th>Réceptionné par</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $receptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reception): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><strong><?php echo e($reception->numero_reception); ?></strong></td>
                                        <td><?php echo e($reception->bonCommande->numero_bon); ?></td>
                                        <td><?php echo e($reception->date_reception->format('d/m/Y')); ?></td>
                                        <td><?php echo e($reception->receivedBy->name ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($reception->isValidated()): ?>
                                            <span class="badge bg-success">Validée</span>
                                            <?php else: ?>
                                            <span class="badge bg-warning text-dark">En attente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('stock-receptions.show', $reception->id)); ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('stock-receptions.pdf', $reception->id)); ?>" 
                                               class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Aucune réception trouvée
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <?php echo e($receptions->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/stock_receptions/index.blade.php ENDPATH**/ ?>