
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center"
        style="cursor:pointer ;"
        data-bs-toggle="collapse"
        data-bs-target="#historiqueVisites"
        aria-expanded="true">

        <!--h5 class="mb-0"-->
        <span>
            <i class="fas fa-history me-2"></i>
            Historique des visites
            <span class="badge bg-white text-info ms-2"><?php echo e($patient->visits()->count()); ?></span>
        </span>
        <i class="fas fa-chevron-up transition-icon" id="historiqueIcon"></i>

    </div>
    <div class="collapse show" id="historiqueVisites">
    <div class="card-body">
        <?php if($patient->visits()->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Motif</th>
                            <?php if(in_array(auth()->user()->role_id, [1, 6])): ?>
                            <th>Montant</th>
                            <th>Reste</th>
                            <?php endif; ?>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $patient->visits()->latest('visit_date')->limit(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <small class="fw-semibold"><?php echo e($visit->visit_date->format('d/m/Y')); ?></small>
                                <?php if($visit->isToday()): ?>
                                    <span class="badge bg-info ms-1">Aujourd'hui</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small> 
                                     <?php echo e($visit->motif ? str($visit->motif)->limit(25) : '-'); ?>

                                </small>
                            </td>
                            <?php if(in_array(auth()->user()->role_id, [1, 6])): ?>
                            <td><small><?php echo e(number_format($visit->montant)); ?> F</small></td>
                            <td>
                                <small class="<?php echo e($visit->reste > 0 ? 'text-danger fw-semibold' : 'text-success'); ?>">
                                    <?php echo e(number_format($visit->reste)); ?> F
                                </small>
                            </td>
                            <?php endif; ?>
                            <td>
                                <span class="badge bg-<?php echo e($visit->status_badge_color); ?> badge-sm">
                                    <?php echo e($visit->status_label); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('patient-visits.patient-history', $visit->patient)); ?>" class="btn btn-sm btn-primary" title="Voir son historique">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($patient->visits()->count() > 5): ?>
            <div class="text-center mt-3">
                <a href="<?php echo e(route('patient-visits.patient-history', $patient)); ?>" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-list me-1"></i>
                    Voir toutes les visites (<?php echo e($patient->visits()->count()); ?>)
                </a>
            </div>
            <?php endif; ?>

            <?php if(in_array(auth()->user()->role_id, [1, 6])): ?>
            <div class="row mt-3 pt-3 border-top">
                <div class="col-md-4">
                    <div class="text-center">
                        <small class="text-muted d-block">Total dépensé</small>
                        <strong class="text-success"><?php echo e(number_format($patient->visits()->sum('montant'))); ?> F</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <small class="text-muted d-block">Total payé</small>
                        <strong class="text-info"><?php echo e(number_format($patient->visits()->sum('avance'))); ?> F</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <small class="text-muted d-block">Reste à payer</small>
                        <strong class="<?php echo e($patient->visits()->sum('reste') > 0 ? 'text-danger' : 'text-success'); ?>">
                            <?php echo e(number_format($patient->visits()->sum('reste'))); ?> F
                        </strong>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-3">
                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-2">Aucune visite enregistrée via le nouveau système</p>
                <a href="<?php echo e(route('patient-visits.create')); ?>?patient_id=<?php echo e($patient->id); ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>Enregistrer une visite
                </a>
            </div>
        <?php endif; ?>
    </div>
    </div>
</div>

<script>
    document.getElementById("historiqueVisites").addEventListener("hide.bs.collapse", function() {
        document.getElementById("historiqueIcon").style.transform = 'rotate(180deg)';
    });
    document.getElementById("historiqueVisites").addEventListener("show.bs.collapse", function() {
        document.getElementById("historiqueIcon").style.transform = 'rotate(0deg)';
    });
</script><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/patients/patient_visits_widget.blade.php ENDPATH**/ ?>