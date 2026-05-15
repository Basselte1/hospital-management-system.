

<?php $__env->startSection('title', 'CMCU | Historique des visites'); ?>

<?php $__env->startSection('content'); ?>

<body>
    <div class="se-pre-con"></div>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="container-fluid px-4 py-4">
            <!-- En-tête -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">
                                <i class="fas fa-history text-primary me-2"></i>
                                <?php if(auth()->user()->role_id === 2): ?>
                                    Mes visites patients
                                <?php else: ?>
                                    Historique des visites patients
                                <?php endif; ?>
                            </h2>
                            <?php if(auth()->user()->role_id === 2): ?>
                            <p class="text-muted mb-0">
                                <i class="fas fa-user-md me-1"></i>
                                <?php echo e(auth()->user()->name); ?> <?php echo e(auth()->user()->prenom); ?>

                            </p>
                            <?php endif; ?>
                        </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createVisit', \App\Models\Patient::class)): ?>
                        <a href="<?php echo e(route('patient-visits.create')); ?>" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Nouvelle visite
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-muted">Total visites</h6>
                                    <h4 class="mb-0"><?php echo e(number_format($stats['total_visits'])); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-muted">Aujourd'hui</h6>
                                    <h4 class="mb-0"><?php echo e(number_format($stats['today_visits'])); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-hourglass-half fa-2x text-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-muted">En attente</h6>
                                    <h4 class="mb-0"><?php echo e(number_format($stats['pending_visits'])); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-muted">Revenu total</h6>
                                    <h4 class="mb-0"><?php echo e(number_format($stats['total_revenue'])); ?> F</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form action="<?php echo e(route('patient-visits.index')); ?>" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Recherche</label>
                                    <input type="text" name="search" class="form-control" 
                                        value="<?php echo e($search); ?>" placeholder="Nom ou numéro dossier">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Statut</label>
                                    <select name="status" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="en_attente" <?php echo e($status == 'en_attente' ? 'selected' : ''); ?>>En attente</option>
                                        <option value="en_cours" <?php echo e($status == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                                        <option value="terminee" <?php echo e($status == 'terminee' ? 'selected' : ''); ?>>Terminée</option>
                                        <option value="annulee" <?php echo e($status == 'annulee' ? 'selected' : ''); ?>>Annulée</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Date début</label>
                                    <input type="date" name="date_from" class="form-control" value="<?php echo e($dateFrom); ?>">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Date fin</label>
                                    <input type="date" name="date_to" class="form-control" value="<?php echo e($dateTo); ?>">
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label fw-semibold">Par page</label>
                                    <select name="per_page" class="form-control">
                                        <option value="15" <?php echo e($perPage == 15 ? 'selected' : ''); ?>>15</option>
                                        <option value="30" <?php echo e($perPage == 30 ? 'selected' : ''); ?>>30</option>
                                        <option value="50" <?php echo e($perPage == 50 ? 'selected' : ''); ?>>50</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-filter me-1"></i>Filtrer
                                        </button>
                                        <a href="<?php echo e(route('patient-visits.index')); ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-redo"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Patient</th>
                                            <th>Motif</th>
                                            <?php if(auth()->user()->role_id !== 2): ?>
                                            <th>Médecin</th>
                                            <?php endif; ?>
                                            
                                            <?php if(auth()->user()->role_id === 1 || auth()->user()->role_id === 6): ?>
                                            <th>Montant</th>
                                            <th>Avance</th>
                                            <th>Reste</th>
                                            <?php endif; ?>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                            
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $visits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <span class="fw-semibold"><?php echo e($visit->visit_date->format('d/m/Y')); ?></span>
                                                <?php if($visit->isToday()): ?>
                                                    <span class="badge bg-info ms-1">Aujourd'hui</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold"><?php echo e($visit->patient_display_name); ?></span>
                                                    <small class="text-muted">
                                                        <?php if($visit->patient_numero_dossier): ?>
                                                            CMCU-<?php echo e($visit->patient_numero_dossier); ?>

                                                        <?php else: ?>
                                                            <em class="text-warning">Patient supprimé</em>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span title="<?php echo e($visit->details_motif); ?>">
                                                    <?php echo e($visit->motif ? \Illuminate\Support\Str::limit($visit->motif, 30) : '-'); ?>

                                                </span>
                                            </td>
                                            <?php if(auth()->user()->role_id !== 2): ?>
                                            <td><?php echo e($visit->medecin_r ?? '-'); ?></td>
                                            <?php endif; ?>
                                            <?php if(auth()->user()->role_id === 1 || auth()->user()->role_id === 6): ?>
                                            <td><?php echo e(number_format($visit->montant)); ?> F</td>
                                            <td><?php echo e(number_format($visit->avance)); ?> F</td>
                                            <td>
                                                <span class="<?php echo e($visit->reste > 0 ? 'text-danger fw-semibold' : 'text-success'); ?>">
                                                    <?php echo e(number_format($visit->reste)); ?> F
                                                </span>
                                            </td>
                                            <?php endif; ?>
                                            <td>
                                                <span class="badge bg-<?php echo e($visit->status_badge_color); ?>">
                                                    <?php echo e($visit->status_label); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="<?php echo e(route('patient-visits.show', $visit)); ?>" 
                                                        class="btn btn-sm btn-primary" title="Voir détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if($visit->patient): ?>
                                                    <a href="<?php echo e(route('patients.show', $visit->patient)); ?>" 
                                                        class="btn btn-sm btn-info" title="Dossier patient">
                                                        <i class="fas fa-folder-open"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                   <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Patient::class)): ?>
                                                    <a href="<?php echo e(route('patient-visits.edit', $visit)); ?>" 
                                                        class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php endif; ?> --->
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                                <p class="text-muted">Aucune visite trouvée</p>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php if($visits->hasPages()): ?>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Affichage de <?php echo e($visits->firstItem()); ?> à <?php echo e($visits->lastItem()); ?> sur <?php echo e($visits->total()); ?> visites
                                </div>
                                <?php echo e($visits->appends(request()->query())->links()); ?>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/patient_visits/index.blade.php ENDPATH**/ ?>