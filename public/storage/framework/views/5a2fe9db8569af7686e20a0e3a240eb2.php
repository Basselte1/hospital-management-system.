

<?php $__env->startSection('title', 'Bilan Patient - ' . $patient->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('rapports.index')); ?>">Rapports</a></li>
                        <li class="breadcrumb-item active">Bilan <?php echo e($patient->name); ?></li>
                    </ol>
                </div>
                <h4 class="page-title">Bilan Médical Patient: <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></h4>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-md-4">
            <form method="GET" action="<?php echo e(route('rapports.patient', $patient->id)); ?>">
                <div class="row">
                    <div class="col-md-6">
                        <input type="date" name="start-date" class="form-control" value="<?php echo e($startDate); ?>" max="<?php echo e($endDate); ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="date" name="end-date" class="form-control" value="<?php echo e($endDate); ?>" min="<?php echo e($startDate); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2 w-100">Filtrer</button>
            </form>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-file-invoice font-size-24"></i></div>
                    <h5><?php echo e($factures->count()); ?></h5>
                    <p>Factures</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-coins font-size-24"></i></div>
                    <h5><?php echo e(number_format($totalCumule, 0, ',', ' ')); ?> FCFA</h5>
                    <p>Montant Total</p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Historique Factures Journalières</h4>
                    <?php if($factures->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Avance</th>
                                        <th>Reste</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $factures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($facture->date_facture->format('d/m/Y')); ?></td>
                                        <td><?php echo e(number_format($facture->total_montant, 0, ',', ' ')); ?> FCFA</td>
                                        <td><?php echo e(number_format($facture->total_avance, 0, ',', ' ')); ?> FCFA</td>
                                        <td><?php echo e(number_format($facture->total_montant - $facture->total_avance, 0, ',', ' ')); ?> FCFA</td>
                                        <td>
                                            <a href="<?php echo e(route('factures.journalieres.pdf', $facture->id)); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-print"></i> PDF
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Aucune facture trouvée pour cette période.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/rapports/patient.blade.php ENDPATH**/ ?>