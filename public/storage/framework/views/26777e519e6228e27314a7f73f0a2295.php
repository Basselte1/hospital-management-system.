
<?php $__env->startSection('title', 'CMCU | Liste des Ventes'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-list"></i> Liste des Ventes
                </h1>
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('pharmacie.index')); ?>" class="btn btn-success">
                    <i class="fas fa-arrow-left"></i> Retour à la pharmacie
                </a>
            </div>
            
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtres</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?php echo e(route('pharmacie.sales.list')); ?>">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Type de Vente</label>
                                    <select name="type_vente" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="patient" <?php echo e(request('type_vente') == 'patient' ? 'selected' : ''); ?>>Patient</option>
                                        <option value="client_externe" <?php echo e(request('type_vente') == 'client_externe' ? 'selected' : ''); ?>>Externe</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Statut Paiement</label>
                                    <select name="statut_paiement" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="en_attente" <?php echo e(request('statut_paiement') == 'en_attente' ? 'selected' : ''); ?>>En Attente</option>
                                        <option value="soldee" <?php echo e(request('statut_paiement') == 'soldee' ? 'selected' : ''); ?>>Soldée</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Date Début</label>
                                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label>Date Fin</label>
                                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Filtrer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Vente</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Client</th>
                                        <th>Pharmacien</th>
                                        <th class="text-end">Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $ventes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                                        <td><?php echo e($vente->pharmacien->name ?? 'N/A'); ?></td>
                                        <td class="text-end"><strong><?php echo e(number_format($vente->montant_total)); ?> FCFA</strong></td>
                                        <td>
                                            <?php if($vente->isSoldee()): ?>
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Soldée</span>
                                            <?php else: ?>
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> En attente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('pharmacie.sales.show', $vente->id)); ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('pharmacie.sales.invoice', $vente->id)); ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               title="Télécharger facture"
                                               target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Aucune vente trouvée
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <?php echo e($ventes->appends(request()->query())->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/pharmacie/list.blade.php ENDPATH**/ ?>