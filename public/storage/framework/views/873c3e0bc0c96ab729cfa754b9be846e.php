
<?php $__env->startSection('title', 'CMCU | Produits à Collecter'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-warning">Produits Utilisés - À Collecter</h1>
                <hr class="w-25 mx-auto">
                <p class="text-muted">Produits en attente de collecte pour stérilisation</p>
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

        <?php if($pendingUsages->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucun produit en attente de collecte
        </div>
        <?php else: ?>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-warning text-dark shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($pendingUsages->total()); ?></h3>
                        <p class="mb-0">Usages en Attente</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($pendingUsages->sum('quantite_retournable')); ?></h3>
                        <p class="mb-0">Unités à Collecter</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($pendingUsages->sum('quantite_perdue')); ?></h3>
                        <p class="mb-0">Unités Perdues</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usages List -->
        <?php $__currentLoopData = $pendingUsages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-box"></i> <?php echo e($usage->produit->designation); ?>

                    </h5>
                    <div>
                        <span class="badge bg-light text-dark me-2">
                            ID: #<?php echo e($usage->id); ?>

                        </span>
                        <span class="badge bg-info">
                            <?php echo e($usage->getTypeUtilisationLabel()); ?>

                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Usage Information -->
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-info-circle"></i> Informations d'Utilisation</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Date Utilisation:</th>
                                <td><?php echo e($usage->date_utilisation->format('d/m/Y')); ?> 
                                    <?php if($usage->heure_utilisation): ?>
                                        à <?php echo e($usage->heure_utilisation); ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Service:</th>
                                <td><?php echo e($usage->service ?? 'N/A'); ?></td>
                            </tr>
                            <?php if($usage->patient): ?>
                            <tr>
                                <th>Patient:</th>
                                <td><?php echo e($usage->patient->name); ?> <?php echo e($usage->patient->prenom); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($usage->medecin): ?>
                            <tr>
                                <th>Médecin:</th>
                                <td><?php echo e($usage->medecin->name); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($usage->infirmier): ?>
                            <tr>
                                <th>Infirmier(ère):</th>
                                <td><?php echo e($usage->infirmier->name); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Enregistré par:</th>
                                <td><?php echo e($usage->enregistrePar->name); ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Quantities -->
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="fas fa-calculator"></i> Quantités</h6>
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="card bg-light">
                                    <div class="card-body p-2">
                                        <h4 class="mb-0"><?php echo e($usage->quantite); ?></h4>
                                        <small>Utilisée</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body p-2">
                                        <h4 class="mb-0"><?php echo e($usage->quantite_retournable); ?></h4>
                                        <small>Retournable</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body p-2">
                                        <h4 class="mb-0"><?php echo e($usage->quantite_perdue); ?></h4>
                                        <small>Perdue</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if($usage->motif): ?>
                        <div class="alert alert-info mb-2">
                            <strong>Motif:</strong> <?php echo e($usage->motif); ?>

                        </div>
                        <?php endif; ?>

                        <?php if($usage->observations): ?>
                        <div class="alert alert-warning mb-0">
                            <strong>Observations:</strong> <?php echo e($usage->observations); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-3 text-end">
                    <?php if($usage->canBeCollected()): ?>
                    <form action="<?php echo e(route('reusable-products.usages.collect', $usage->id)); ?>" 
                          method="POST" 
                          style="display: inline;"
                          onsubmit="return confirm('Confirmer la collecte de <?php echo e($usage->quantite_retournable); ?> unité(s) de <?php echo e($usage->produit->designation); ?> ?');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Collecter pour Stérilisation
                        </button>
                    </form>
                    <?php else: ?>
                    <span class="badge bg-secondary">
                        <i class="fas fa-ban"></i> Non disponible pour collecte
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer text-muted">
                <small>
                    <i class="fas fa-clock"></i> Enregistré le <?php echo e($usage->created_at->format('d/m/Y à H:i')); ?>

                </small>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Pagination -->
        <div class="mt-3">
            <?php echo e($pendingUsages->links('pagination::bootstrap-5')); ?>

        </div>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?php echo e(route('reusable-products.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au Tableau de Bord
            </a>
        </div>

    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/reusable_products/pending_usages.blade.php ENDPATH**/ ?>