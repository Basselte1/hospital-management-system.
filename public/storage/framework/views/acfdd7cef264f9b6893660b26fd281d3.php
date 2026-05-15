

<?php $__env->startSection('title', 'CMCU | Surveillance rapprochée des paramètres'); ?>

<?php $__env->startSection('content'); ?>

    <body>

    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="row mb-1">
            <div class="col-sm-12">
                <h1 class="text-center ">SURVEILLANCE RAPPROCHEE DES PARAMETRES</h1>
            </div>
        </div>
        <hr>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
            <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn btn-success float-end"
               title="Retour à la liste des patients">
                <i class="fas fa-arrow-left"></i> Retour au dossier patient
            </a>
            <div class="container">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <td>
                                            <h1 class="text-info"><a href="<?php echo e(route('surveillance_rapproche', $patient->id)); ?>">Pré-opératoire</a></h1>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php if(!empty($paramPre)): ?>
                                    <tr>
                                        <td class="table-active"><b>DATE :</b></td>
                                        <td class="table-active"><b><?php echo e($paramPre->date); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><b>HEURE :</b></td>
                                        <td><?php echo e($paramPre->heure); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>T.A :</b></td>
                                        <td><?php echo e($paramPre->ta); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>F.R :</b></td>
                                        <td><?php echo e($paramPre->fr); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>POULS :</b></td>
                                        <td><?php echo e($paramPre->pouls); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>SPO2 :</b></td>
                                        <td><?php echo e($paramPre->spo2); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>T° :</b></td>
                                        <td><?php echo e($paramPre->temperature); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>DIURESE :</b></td>
                                        <td><?php echo e($paramPre->diurese); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>CONSCIENCE :</b></td>
                                        <td><?php echo e($paramPre->conscience); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>DOULEUR :</b></td>
                                        <td><?php echo e($paramPre->douleur); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>OBSERVATIONS / PLAINTES :</b></td>
                                        <td><?php echo e($paramPre->observation_plainte); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>
                                            <h1 class="text-info"><a href="<?php echo e(route('surveillance_rapproche', $patient->id)); ?>">Post-opératoire</a></h1>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php if(!empty($paramPost)): ?>
                                        <tr>
                                            <td class="table-active"><b>DATE :</b></td>
                                            <td class="table-active"><b><?php echo e($paramPost->date); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td><b>HEURE :</b></td>
                                            <td><?php echo e($paramPost->heure); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>T.A :</b></td>
                                            <td><?php echo e($paramPost->ta); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>F.R :</b></td>
                                            <td><?php echo e($paramPost->fr); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>POULS :</b></td>
                                            <td><?php echo e($paramPost->pouls); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>T° :</b></td>
                                            <td><?php echo e($paramPost->temperature); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>DIURESE :</b></td>
                                            <td><?php echo e($paramPost->diurese); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>CONSCIENCE :</b></td>
                                            <td><?php echo e($paramPost->conscience); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>DOULEUR :</b></td>
                                            <td><?php echo e($paramPost->douleur); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>OBSERVATIONS / PLAINTES :</b></td>
                                            <td><?php echo e($paramPost->observation_plainte); ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    </thead>

                                </table>
                                <?php echo $__env->make('admin.modal.surveillance_rapproche_param', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
    </body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/consultations/infirmiers/surveillance_rapproche_param.blade.php ENDPATH**/ ?>