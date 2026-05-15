

<?php $__env->startSection('title', 'Rapport Médecin - ' . $medecin->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('rapports.index')); ?>">Rapports</a></li>
                        <li class="breadcrumb-item active">Médecin <?php echo e($medecin->name); ?></li>
                    </ol>
                </div>
                <h4 class="page-title">Rapport Médecin: <?php echo e($medecin->name); ?></h4>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-md-4">
            <form method="GET" action="<?php echo e(route('rapports.medecin', $medecin->id)); ?>">
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
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-calendar-day font-size-24"></i></div>
                    <h5><?php echo e($rapports->flatten()->count()); ?></h5>
                    <p>Consultations</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-money-bill-wave font-size-24"></i></div>
                    <h5><?php echo e(number_format($totalCA, 0, ',', ' ')); ?> FCFA</h5>
                    <p>CA Total</p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Détails par période</h4>
                    <?php if($rapports->count() > 0): ?>
                        <?php $__currentLoopData = $rapports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mois => $moisRapports): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <h5 class="mt-4 mb-2"><?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $mois)->translatedFormat('MMMM YYYY')); ?></h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Patient</th>
                                            <th>Montant</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $moisRapports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rapport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($rapport->date_facture->format('d/m/Y')); ?></td>
                                            <td><?php echo e($rapport->patient->name ?? 'N/A'); ?></td>
                                            <td><?php echo e(number_format($rapport->lignes_sum_montant_total ?? 0, 0, ',', ' ')); ?> FCFA</td>
                                            <td>
                                                <a href="<?php echo e(route('factures.journalieres.pdf', $rapport->id)); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-print"></i> PDF
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Aucun rapport trouvé pour cette période.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/rapports/medecin.blade.php ENDPATH**/ ?>