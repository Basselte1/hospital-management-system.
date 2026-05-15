
<?php $__env->startSection('title', 'Facturation - Chambres'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Factures Chambres</h4>
            </div>
        </div>
    </div>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="float-end"><?php echo e($factures->total()); ?></div>
                    <span>Total Séjours</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="float-end"><?php echo e(number_format($factures->sum('montant_total'), 0, ',', ' ')); ?> FCFA</div>
                    <span>Montant Total</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="float-end"><?php echo e(number_format($factures->sum('avance'), 0, ',', ' ')); ?> FCFA</div>
                    <span>Encaissé</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="float-end"><?php echo e(number_format($factures->sum('reste'), 0, ',', ' ')); ?> FCFA</div>
                    <span>Reste à Payer</span>
                </div>
            </div>
        </div>
    </div>

    
    <form method="GET">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <input type="date" name="date_debut" class="form-control" value="<?php echo e(request('date_debut')); ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_fin" class="form-control" value="<?php echo e(request('date_fin')); ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher patient/chambre..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                        <a href="<?php echo e(route('facturation.chambres.index')); ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Patient</th>
                            <th>Chambre</th>
                            <th>Montant</th>
                            <th>Avance</th>
                            <th>Reste</th>
                            <th>Statut</th>
                            <th>Période</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $factures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($facture->numero); ?></strong></td>
                            <td><?php echo e($facture->patient_name); ?> (<?php echo e($facture->patient->numero_dossier ?? ''); ?>)</td>
                            <td><?php echo e($facture->chambre->numero ?? 'N/A'); ?></td>
                            <td><?php echo e(number_format($facture->montant_total, 0, ',', ' ')); ?> FCFA</td>
                            <td><?php echo e(number_format($facture->avance, 0, ',', ' ')); ?> FCFA</td>
                            <td>
                                <span class="<?php echo e($facture->reste == 0 ? 'badge bg-success' : 'badge bg-warning'); ?>">
                                    <?php echo e(number_format($facture->reste, 0, ',', ' ')); ?> FCFA
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo e($facture->statut == 'Soldée' ? 'bg-success' : 'bg-warning'); ?>">
                                    <?php echo e($facture->statut); ?>

                                </span>
                            </td>
                            <td>
                                <?php echo e($facture->date_entre?->format('d/m/Y')); ?> 
                                <?php if($facture->date_sortie): ?>
                                → <?php echo e($facture->date_sortie->format('d/m/Y')); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('facturation.chambres.show', $facture)); ?>" class="btn btn-sm btn-info">Voir</a>
                                <a href="<?php echo e(route('facturation.chambres.print', $facture)); ?>" class="btn btn-sm btn-success">Imprimer</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">Aucune facture chambre trouvée</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($factures->appends(request()->query())->links()); ?>

        </div>
    </div>

    
    <div class="text-end mt-3">
        <a href="<?php echo e(route('facturation.chambres.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Nouvelle Facture Chambre
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/facturation/chambres/index.blade.php ENDPATH**/ ?>