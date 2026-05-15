

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Factures Journalières</h3>
                </div>
                <div class="card-body">
                    
                    <form method="GET" class="row mb-4">
                        <div class="col-md-3">
                            <label>Patient</label>
                            <select name="patient_id" class="form-control">
                                <option value="">Tous patients</option>
                                <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->id); ?>" <?php echo e(request('patient_id') == $p->id ? 'selected' : ''); ?>>
                                        <?php echo e($p->name); ?> <?php echo e($p->prenom); ?> (<?php echo e($p->numero_dossier ?? ''); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Date</label>
                            <input type="date" name="date" value="<?php echo e($date); ?>" class="form-control">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </form>

                    
                    <?php if($dailyFactures->first()): ?>
                        <div class="mb-3">
                            <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-secondary">← Patients</a>
                            <form method="POST" action="<?php echo e(route('factures.journalieres.generer', $dailyFactures->first()->patient_id)); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <input type="date" name="date_facture" value="<?php echo e(now()->format('Y-m-d')); ?>" class="form-control d-inline-block w-auto" style="width: 160px;">
                                <button type="submit" class="btn btn-success">Générer Facture <?php echo e(now()->format('d/m')); ?></button>
                            </form>
                        </div>
                    <?php endif; ?>

                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Médecin</th>
                                    <th>Lignes</th>
                                    <th>Total</th>
                                    <th>Avance</th>
                                    <th>Reste</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $dailyFactures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><strong><?php echo e($f->date_facture->format('d/m/Y')); ?></strong></td>
                                        <td><?php echo e($f->patient->name ?? '[Supprimé]'); ?> <?php echo e($f->patient->prenom); ?></td>
                                        <td><?php echo e($f->medecin_principal->name ?? '-'); ?></td>
                                        <td>
                                            <details>
                                                <summary><?php echo e($f->lignes_count); ?> lignes</summary>
                                                <ul class="small">
                                                    <?php $__currentLoopData = $f->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($l->type); ?>: <?php echo e($l->description); ?> (<?php echo e($l->quantite); ?> x <?php echo e(number_format($l->montant_unitaire,0,',',' ')); ?> = <?php echo e(number_format($l->montant_total,0,',',' ')); ?>)</li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </details>
                                        </td>
                                        <td><strong><?php echo e(number_format($f->total_montant, 0, ',', ' ')); ?> FCFA</strong></td>
                                        <td><?php echo e(number_format($f->total_avance, 0, ',', ' ')); ?> FCFA</td>
                                        <td class="fw-bold <?php echo e($f->total_reste > 0 ? 'text-danger' : 'text-success'); ?>"><?php echo e(number_format($f->total_reste, 0, ',', ' ')); ?> FCFA</td>
                                        <td><?php echo $f->statut_badge; ?></td>
                                        <td>
                                            <?php if($f->isImprimable()): ?>
                                                <a href="<?php echo e(route('factures.journalieres.pdf', $f)); ?>" class="btn btn-sm btn-info" target="_blank">PDF</a>
                                            <?php endif; ?>
                                            <a href="#" class="btn btn-sm btn-warning">Payer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i>Aucune facture journalière</i><br>
                                            <a href="<?php echo e(route('patients.index')); ?>">Créer patient pour tester →</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php echo e($dailyFactures->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/journalieres/index.blade.php ENDPATH**/ ?>