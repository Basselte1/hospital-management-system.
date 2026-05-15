

<?php $__env->startSection('title', 'CMCU | Détails de la visite'); ?>

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
                                <i class="fas fa-file-medical text-primary me-2"></i>
                                Détails de la visite
                            </h2>
                            <p class="text-muted mb-0">
                                Visite du <?php echo e($visit->visit_date->format('d/m/Y')); ?>

                            </p>
                        </div>
                        <div>
                            <a href="<?php echo e(route('patient-visits.index', $visit->patient)); ?>" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Retour à liste Visite
                            </a>
                           <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Patient::class)): ?>
                            <a href="<?php echo e(route('patient-visits.edit', $visit)); ?>" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                            <?php endif; ?>-->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="row">
                <!-- Colonne gauche : Info patient -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2"></i>Information patient
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h4><?php echo e($visit->patient->name); ?> <?php echo e($visit->patient->prenom); ?></h4>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-hashtag me-1"></i>
                                    CMCU-<?php echo e($visit->patient->numero_dossier); ?>

                                </p>
                                <?php if($visit->patient->dossiers->first()): ?>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-phone me-1"></i>
                                    <?php echo e($visit->patient->dossiers->first()->portable_1 ?? 'N/A'); ?>

                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-envelope me-1"></i>
                                    <?php echo e($visit->patient->dossiers->first()->email ?? 'N/A'); ?>

                                </p>
                                <?php endif; ?>
                            </div>
                            <div class="d-grid">
                                <a href="<?php echo e(route('patients.show', $visit->patient)); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-folder-open me-2"></i>Voir dossier complet
                                </a>
                            </div>
                            <br>
                            <div class="d-grid">
                                <a href="<?php echo e(route('patient-visits.patient-history', $visit->patient)); ?>" class="btn btn-outline-success">
                                    <i class="fas fa-folder-open me-2"></i>Voir historique Visite du Patient
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Statut de la visite -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Statut de la visite</h6>
                            <h3>
                                <span class="badge bg-<?php echo e($visit->status_badge_color); ?> px-4 py-2">
                                    <?php echo e($visit->status_label); ?>

                                </span>
                            </h3>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('updateVisitStatus', \App\Models\Patient::class)): ?>
                            <form action="<?php echo e(route('patient-visits.update-status', $visit)); ?>" method="POST" class="mt-3">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <div class="d-flex gap-2">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="en_attente" <?php echo e($visit->status == 'en_attente' ? 'selected' : ''); ?>>En attente</option>
                                        <option value="en_cours" <?php echo e($visit->status == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                                        <option value="terminee" <?php echo e($visit->status == 'terminee' ? 'selected' : ''); ?>>Terminée</option>
                                        <option value="annulee" <?php echo e($visit->status == 'annulee' ? 'selected' : ''); ?>>Annulée</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite : Détails de la visite -->
                <div class="col-md-8">
                    <!-- Informations médicales -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-notes-medical me-2"></i>Informations médicales
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Date de la visite</label>
                                    <p class="fw-semibold mb-0"><?php echo e($visit->visit_date->format('d/m/Y')); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Médecin traitant</label>
                                    <p class="fw-semibold mb-0"><?php echo e($visit->medecin_r ?? 'Non spécifié'); ?></p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="text-muted small">Motif de consultation</label>
                                    <p class="fw-semibold mb-0"><?php echo e($visit->motif ?? 'Non spécifié'); ?></p>
                                </div>
                                <?php if($visit->details_motif): ?>
                                <div class="col-md-12 mb-3">
                                    <label class="text-muted small">Détails du motif</label>
                                    <p class="mb-0"><?php echo e($visit->details_motif); ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if($visit->notes): ?>
                                <div class="col-md-12">
                                    <label class="text-muted small">Notes</label>
                                    <div class="p-3 bg-light rounded">
                                        <p class="mb-0"><?php echo e($visit->notes); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Informations financières -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-money-bill-wave me-2"></i>Informations financières
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">Montant total</small>
                                        <h4 class="mb-0 text-dark"><?php echo e(number_format($visit->montant)); ?> F</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">Avance</small>
                                        <h4 class="mb-0 text-success"><?php echo e(number_format($visit->avance)); ?> F</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">Reste</small>
                                        <h4 class="mb-0 <?php echo e($visit->reste > 0 ? 'text-danger' : 'text-success'); ?>">
                                            <?php echo e(number_format($visit->reste)); ?> F
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Mode de paiement</label>
                                    <p class="mb-0">
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($visit->mode_paiement ?? 'espèce')); ?></span>
                                    </p>
                                </div>
                                <?php if($visit->mode_paiement_info_sup): ?>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Informations supplémentaires</label>
                                    <p class="mb-0"><?php echo e($visit->mode_paiement_info_sup); ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if($visit->demarcheur): ?>
                                <div class="col-md-6">
                                    <label class="text-muted small">Démarcheur</label>
                                    <p class="mb-0"><?php echo e($visit->demarcheur); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Assurance -->
                    <?php if($visit->assurance): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-shield-alt me-2"></i>Informations d'assurance
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Assurance</label>
                                    <p class="fw-semibold mb-0"><?php echo e($visit->assurance); ?></p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">N° d'assurance</label>
                                    <p class="fw-semibold mb-0"><?php echo e($visit->numero_assurance ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Prise en charge</label>
                                    <p class="fw-semibold mb-0"><?php echo e($visit->prise_en_charge ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Métadonnées -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row text-muted small">
                                <div class="col-md-6">
                                    <i class="fas fa-user me-1"></i>
                                    Enregistré par: <?php echo e($visit->user->name ?? 'N/A'); ?> <?php echo e($visit->user->prenom ?? ''); ?>

                                </div>
                                <div class="col-md-6 text-md-end">
                                    <i class="fas fa-clock me-1"></i>
                                    Le <?php echo e($visit->created_at->format('d/m/Y à H:i')); ?>

                                </div>
                            </div>
                            <?php if($visit->updated_at->ne($visit->created_at)): ?>
                            <div class="row text-muted small mt-2">
                                <div class="col-12 text-md-end">
                                    <i class="fas fa-edit me-1"></i>
                                    Dernière modification: <?php echo e($visit->updated_at->format('d/m/Y à H:i')); ?>

                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/patient_visits/show.blade.php ENDPATH**/ ?>