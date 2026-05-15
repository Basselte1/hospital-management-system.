
<?php $__env->startSection('title', 'CMCU | Fiche de paramètres patient'); ?>
<?php $__env->startSection('content'); ?>
    <body>
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12  toppad  offset-md-0 mb-2">
                    <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn btn-success float-end"><i
                            class="fas fa-arrow-left"></i> Retour au dossier patient</a>
                </div>
                <div class="col-md-10">
                    <!-- resumt -->
                    <div class="card">
                        <div class="card-header resume-heading">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="col-12 col-md-12">
                                        <ul class="list-group">
                                            <li class="list-group-item"><b>NOM ET PRENOM DU PATIENT :</b> <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></li>
                                            <li class="list-group-item"><i class="fa fa-phone"></i> <b>TELEPHONE :</b> <?php echo e($patient->portable_1); ?></li>
                                            <li class="list-group-item"><i class="fa fa-envelope"></i> E-Mail: <?php echo e($patient->email); ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <td></td>
                                    <td><h1 class="text-info">PARAMETRES PATIENT</h1></td>
                                </tr>
                                <?php $__currentLoopData = $parametres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parametre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="table-active"><b>DATE</b></td>
                                        <td class="table-active"><?php echo e($parametre->created_at->toFormattedDateString()); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>DATE DE NAISSANCE</b></td>
                                        <td> <?php echo e($parametre->date_naissance); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>AGE</b></td>
                                        <td> <?php echo e($parametre->age); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>POIDS</b></td>
                                        <td> <?php echo e($parametre->poids); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>TAILLE</b></td>
                                        <td> <?php echo e($parametre->taille); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>TEMPERATURE</b></td>
                                        <td> <?php echo e($parametre->temperature); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>GLYCEMIE</b></td>
                                        <td> <?php echo e($parametre->glycemie); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>SPO2</b></td>
                                        <td> <?php echo e($parametre->spo2); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>IMC / BMI</b></td> 
                                        <td> <?php echo e($parametre->inc_bmi); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>TA :</b></td>
                                        <td>
                                            <b>Bg :</b> <?php echo e($parametre->bras_gauche ?? '—'); ?> mmHg
                                            <br>
                                            <b>Bd :</b> <?php echo e($parametre->bras_droit ?? '—'); ?> mmHg
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td><b>TA</b></td>
                                        <td> <?php echo e($parametre->ta); ?></td>
                                    </tr> -->
                                    <tr>
                                        <td><b>FR</b></td>
                                        <td> <?php echo e($parametre->fr); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>FC</b></td>
                                        <td> <?php echo e($parametre->fc); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
                <!-- resume -->
            </div>

        </div>
    </div>

    </body>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/infirmiers/index_fiche_parametre.blade.php ENDPATH**/ ?>