
<?php $__env->startSection('title', 'CMCU | Liste Complète des Examens'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1 class="mb-4">📋 Tous les Examens du Système</h1>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Tous les objets "examen" dans FactureConsultation</h5>
            <a href="<?php echo e(route('facturation.examens.index')); ?>" class="btn btn-primary">
                ← Retour Factures Examens
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Numéro</th>
                            <th>Motif</th>
                            <th>Patient</th>
                            <th>Montant</th>
                            <th>Reste</th>
                            <th>Date</th>
                            <th>Lignes Examens</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $examens = \App\Models\FactureConsultation::with(['patient', 'lignes'])
                                ->where(function($q){
                                    $q->where('motif', 'examen')
                                      ->orWhereHas('lignes', fn($q2)=>$q2->where('type_acte', 'like', '%examen%'));
                                })
                                ->latest()
                                ->paginate(50);
                        ?>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $examens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($facture->id); ?></strong></td>
                            <td><?php echo e($facture->numero); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo e($facture->motif); ?></span>
<?php if($facture->details_motif): ?>
                                    <br><small><?php echo e(substr($facture->details_motif, 0, 50)); ?><?php echo e(strlen($facture->details_motif) > 50 ? '...' : ''); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo e($facture->patient ? $facture->patient->name.' '.$facture->patient->prenom : $facture->patient_name); ?>

<?php if($facture->patient && $facture->patient->numero_dossier): ?>
                                    <br><small class="text-muted">N°<?php echo e($facture->patient->numero_dossier); ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?> FCFA</td>
                            <td class="<?php echo e($facture->reste > 0 ? 'text-danger fw-bold' : 'text-success'); ?>">
                                <?php echo e(number_format($facture->reste ?? 0, 0, ',', ' ')); ?> FCFA
                            </td>
                            <td><?php echo e($facture->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <?php if($facture->lignes->count()): ?>
                                    <ul class="mb-0 small">
                                        <?php $__currentLoopData = $facture->lignes->where('type_acte', 'like', '%examen%'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <span class="badge bg-secondary me-1"><?php echo e($ligne->type_acte); ?></span>
                                                <?php echo e(Str::limit($ligne->libelle, 30)); ?>

                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php else: ?>
                                    <span class="text-muted">Aucune ligne examen</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">Aucun examen trouvé</h5>
                                <p class="text-muted mb-0">Aucune facture avec motif='examen' ou lignes type_acte like '%examen%'</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($examens->links()); ?>

            
            <div class="mt-4 p-3 bg-light">
                <h6>Résumé:</h6>
                <div>Total factures: <strong><?php echo e($examens->total()); ?></strong></div>
                <div>Montant total: <strong><?php echo e(number_format($examens->sum('montant'), 0, ',', ' ')); ?> FCFA</strong></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/liste_examens.blade.php ENDPATH**/ ?>