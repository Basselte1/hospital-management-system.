

<?php $__env->startSection('title', 'CMCU | Historique des visites - ' . $patient->name . ' ' . $patient->prenom); ?>

<?php $__env->startSection('content'); ?>

<body>
    <div class="se-pre-con"></div>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="container-fluid px-4 py-4">
            <!-- En-tête avec info patient -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                   
                                    <h3 class="mb-1">
                                        <i class="fas fa-user-circle text-primary me-2"></i>
                                        <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?>

                                    </h3>
                                    <p class="text-muted mb-0">
                                        <span class="me-3">
                                            <i class="fas fa-hashtag me-1"></i>
                                            CMCU-<?php echo e($patient->numero_dossier); ?>

                                        </span>
                                        <?php if($patient->dossiers->first()): ?>
                                        <span class="me-3">
                                            <i class="fas fa-phone me-1"></i>
                                            <?php echo e($patient->dossiers->first()->portable_1 ?? 'N/A'); ?>

                                        </span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <a href="<?php echo e(route('patients.show', $patient)); ?>" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-folder-open me-2"></i>Dossier complet
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createVisit', \App\Models\Patient::class)): ?>
                                    <a href="<?php echo e(route('patient-visits.create')); ?>?patient_id=<?php echo e($patient->id); ?>" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>Nouvelle visite
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques du patient -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check fa-3x text-primary mb-2"></i>
                            <h6 class="text-muted mb-1">Nombre de visites</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['total_visits'])); ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave fa-3x text-success mb-2"></i>
                            <h6 class="text-muted mb-1">Total dépensé</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['total_spent'])); ?> F</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-3x text-info mb-2"></i>
                            <h6 class="text-muted mb-1">Total payé</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['total_paid'])); ?> F</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-circle fa-3x text-<?php echo e($stats['total_remaining'] > 0 ? 'danger' : 'success'); ?> mb-2"></i>
                            <h6 class="text-muted mb-1">Reste à payer</h6>
                            <h3 class="mb-0 text-<?php echo e($stats['total_remaining'] > 0 ? 'danger' : 'success'); ?>">
                                <?php echo e(number_format($stats['total_remaining'])); ?> F
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des visites -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Historique des visites
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            <?php if($visits->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Motif</th>
                                            <th>Médecin</th>
                                            <th>Montant</th>
                                            <th>Avance</th>
                                            <th>Reste</th>
                                            <th>Mode paiement</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $visits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span class="fw-semibold"><?php echo e($visit->visit_date->format('d/m/Y')); ?></span>
                                                <?php if($visit->isToday()): ?>
                                                    <br><span class="badge bg-info mt-1">Aujourd'hui</span>
                                                <?php elseif($visit->isRecent()): ?>
                                                    <br><span class="badge bg-warning mt-1">Récent</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo e($visit->motif ?? 'Non spécifié'); ?></strong>
                                                    <?php if($visit->details_motif): ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php echo e($visit->details_motif ? str($visit->details_motif)->limit(50) : '-'); ?>

                                                    </small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?php echo e($visit->medecin_r ?? '-'); ?></td>
                                            <td><?php echo e(number_format($visit->montant)); ?> F</td>
                                            <td><?php echo e(number_format($visit->avance)); ?> F</td>
                                            <td>
                                                <span class="<?php echo e($visit->reste > 0 ? 'text-danger fw-semibold' : 'text-success'); ?>">
                                                    <?php echo e(number_format($visit->reste)); ?> F
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo e($visit->mode_paiement ?? 'espèce'); ?></span>
                                            </td>
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
                                                    <!--<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Patient::class)): ?>
                                                    <a href="<?php echo e(route('patient-visits.edit', $visit)); ?>" 
                                                        class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a-->
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', \App\Models\Patient::class)): ?>
                                                    <form action="<?php echo e(route('patient-visits.destroy', $visit)); ?>" method="POST" 
                                                        onsubmit="return confirm('Voulez-vous vraiment supprimer cette visite ?');">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr class="fw-bold">
                                            <td colspan="3" class="text-end">TOTAUX :</td>
                                            <td><?php echo e(number_format($stats['total_spent'])); ?> F</td>
                                            <td><?php echo e(number_format($stats['total_paid'])); ?> F</td>
                                            <td class="text-<?php echo e($stats['total_remaining'] > 0 ? 'danger' : 'success'); ?>">
                                                <?php echo e(number_format($stats['total_remaining'])); ?> F
                                            </td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <?php if($visits->hasPages()): ?>
                            <div class="card-footer bg-white">
                                <?php echo e($visits->links()); ?>

                            </div>
                            <?php endif; ?>

                            <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucune visite enregistrée</h5>
                                <p class="text-muted mb-3">Ce patient n'a pas encore d'historique de visites</p>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Patient::class)): ?>
                                <a href="<?php echo e(route('patient-visits.create')); ?>?patient_id=<?php echo e($patient->id); ?>" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Enregistrer la première visite
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline alternative (optionnel - peut être affiché/masqué avec un toggle) -->
            <?php if($visits->count() > 0): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-stream me-2"></i>
                                Vue chronologique
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <?php $__currentLoopData = $visits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="timeline-item mb-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="timeline-marker bg-<?php echo e($visit->status_badge_color); ?>">
                                                <i class="fas fa-check text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="card border-start border-3 border-<?php echo e($visit->status_badge_color); ?>">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="mb-1"><?php echo e($visit->motif ?? 'Visite médicale'); ?></h6>
                                                        <small class="text-muted"><?php echo e($visit->visit_date->format('d/m/Y')); ?></small>
                                                    </div>
                                                    <p class="text-muted mb-2">
                                                        <i class="fas fa-user-md me-1"></i><?php echo e($visit->medecin_r ?? 'Non spécifié'); ?>

                                                    </p>
                                                    <div class="d-flex gap-3">
                                                        <span><i class="fas fa-money-bill me-1"></i><?php echo e(number_format($visit->montant)); ?> F</span>
                                                        <span><i class="fas fa-hand-holding-usd me-1"></i><?php echo e(number_format($visit->avance)); ?> F</span>
                                                        <?php if($visit->reste > 0): ?>
                                                        <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Reste: <?php echo e(number_format($visit->reste)); ?> F</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .timeline-marker {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/patient_visits/patient_history.blade.php ENDPATH**/ ?>