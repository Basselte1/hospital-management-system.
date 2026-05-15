
<?php $__env->startSection('title', 'CMCU | Stérilisations'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-info">Historique des Stérilisations</h1>
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

        <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(session('warning')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtres</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('reusable-products.sterilizations.index')); ?>" method="GET">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select class="form-select" name="statut" id="statut">
                                        <option value="">Tous</option>
                                        <option value="en_cours" <?php echo e(request('statut') == 'en_cours' ? 'selected' : ''); ?>>
                                            En Cours
                                        </option>
                                        <option value="termine_en_attente" <?php echo e(request('statut') == 'termine_en_attente' ? 'selected' : ''); ?>>
                                            Terminé - En Attente
                                        </option>
                                        <option value="valide" <?php echo e(request('statut') == 'valide' ? 'selected' : ''); ?>>
                                            Validé
                                        </option>
                                        <option value="retourne" <?php echo e(request('statut') == 'retourne' ? 'selected' : ''); ?>>
                                            Retourné au Stock
                                        </option>
                                        <option value="rejete" <?php echo e(request('statut') == 'rejete' ? 'selected' : ''); ?>>
                                            Rejeté
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="date_debut" class="form-label">Date Début</label>
                                    <input type="date" 
                                           class="form-control" 
                                           name="date_debut" 
                                           value="<?php echo e(request('date_debut')); ?>">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="date_fin" class="form-label">Date Fin</label>
                                    <input type="date" 
                                           class="form-control" 
                                           name="date_fin" 
                                           value="<?php echo e(request('date_fin')); ?>">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filtrer
                                        </button>
                                        <a href="<?php echo e(route('reusable-products.sterilizations.index')); ?>" 
                                           class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i> Réinitialiser
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <?php
                $stats = [
                    'en_cours' => $sterilizations->where('statut', 'en_cours')->count(),
                    'en_attente' => $sterilizations->where('statut', 'termine_en_attente')->count(),
                    'valide' => $sterilizations->where('statut', 'valide')->count(),
                    'retourne' => $sterilizations->where('statut', 'retourne')->count(),
                ];
            ?>
            <div class="col-md-3">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['en_cours']); ?></h3>
                        <p class="mb-0">En Cours</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['en_attente']); ?></h3>
                        <p class="mb-0">En Attente Validation</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['valide']); ?></h3>
                        <p class="mb-0">Validés</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body text-center">
                        <h3><?php echo e($stats['retourne']); ?></h3>
                        <p class="mb-0">Retournés</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sterilizations List -->
        <?php if($sterilizations->count() == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucune stérilisation trouvée
        </div>
        <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Lot</th>
                                        <th>Produit</th>
                                        <th class="text-center">Quantité</th>
                                        <th>Méthode</th>
                                        <th>Date</th>
                                        <th class="text-center">Statut</th>
                                        <th>Stérilisé par</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $sterilizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sterilization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($sterilization->numero_lot); ?></strong>
                                        </td>
                                        <td><?php echo e($sterilization->produit->designation); ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary"><?php echo e($sterilization->quantite); ?></span>
                                        </td>
                                        <td>
                                            <small><?php echo e($sterilization->getMethodeLabel()); ?></small>
                                        </td>
                                        <td><?php echo e($sterilization->date_sterilisation->format('d/m/Y')); ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-<?php echo e($sterilization->getStatutBadgeColor()); ?>">
                                                <?php echo e($sterilization->getStatutLabel()); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <small><?php echo e($sterilization->sterilisePar->name); ?></small>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('reusable-products.sterilizations.show', $sterilization->id)); ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <?php if($sterilization->isRetourne()): ?>
                                            <a href="<?php echo e(route('reusable-products.sterilizations.certificate', $sterilization->id)); ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               title="Certificat PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php echo e($sterilizations->appends(request()->query())->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="<?php echo e(route('reusable-products.sterilizations.create.form')); ?>" class="btn btn-info text-white">
                    <i class="fas fa-plus"></i> Nouvelle Stérilisation
                </a>
                <a href="<?php echo e(route('reusable-products.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour au Tableau de Bord
                </a>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/reusable_products/sterilizations.blade.php ENDPATH**/ ?>