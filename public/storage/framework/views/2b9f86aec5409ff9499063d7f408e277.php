

<?php $__env->startSection('title', 'CMCU | Observations médicales'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="row mb-1">
            <div class="col-sm-12">
                <h1 class="text-center ">Surveillance d'aptitude ≥ 9/10 </h1>
            </div>
        </div>
        <hr>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            
                            
                            <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-primary" title="Retour vers la liste des patients">
                                <i class="fas fa-list-ul"></i> PATIENTS
                            </a>
                            
                            
                            <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn btn-success" title="Retour au dossier patient">
                                <i class="fas fa-arrow-left"></i> Retour au dossier patient
                            </a>
                            
                        </div>
                    </div>
                </div>

                <br>
                <div class="table-responsive">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                        <button type="button" class="btn btn-success"
                                title="Administrer des soins" data-bs-toggle="modal" data-bs-target="#SurveillanceAptitude">
                            <i class="fas fa-user-secret"></i>
                        </button>
                    <?php endif; ?>
                    <table class="table table-bordered table-striped col-md-12">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
                            <tr>
                                <th colspan="0">HORAIRES</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td colspan="0"><?php echo e($surveillance_score->horaire); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th colspan="0">TA s/d</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td colspan="0"><?php echo e($surveillance_score->ta); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>FC</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->fc); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>SPO2</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->spo2); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>FR</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->fr); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>DOULEUR (EN/EVA)</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->douleur); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>TEMPERATURE</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->temperature); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>GLYCEMIE</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->glycemie); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>SEDATION</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->sedation); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>NAUSEES</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->nausee); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>VOMISSEMENTS</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->vomissement); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>SAIGNEMENTS</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->saignement); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>PANSEMENTS</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->pansement); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>DRAINS</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->drains); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>MICTION</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->miction); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>LEVER</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->lever); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <tr>
                                <th>SCORE</th>
                                <?php $__currentLoopData = $surveillance_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($surveillance_score->score); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        
        <?php echo $__env->make('admin.modal.surveillance_aptitude_form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
    </div>
    </body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/consultations/infirmiers/index_scrore_aptitude.blade.php ENDPATH**/ ?>