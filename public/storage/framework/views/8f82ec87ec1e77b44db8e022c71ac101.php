
<?php $__env->startSection('title', 'CMCU | Compte-rendu d\'opérations'); ?>
<?php $__env->startSection('content'); ?>
    <body>
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12  toppad  offset-md-0 ">
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
                                            <li class="list-group-item"><i class="fa fa-phone"></i> <b>TELEPHONE :</b> <?php echo e($patient->telephone); ?></li>
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
                    
                                        <h1 class="text-center">COMPTE-RENDU</h1>
                                   
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $compteRenduBlocOperatoires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compteRenduBlocOperatoire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="table-active"><b>DATE</b></td>
                                            <td class="table-active"><?php echo e($compteRenduBlocOperatoire->created_at->toFormattedDateString()); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>OPERATEUR</b></td>
                                            <td><b>Dr.</b> <?php echo e($compteRenduBlocOperatoire->chirurgien); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>AIDE OPERATOIRE</b></td>
                                            <td><b>Dr.</b> <?php echo e($compteRenduBlocOperatoire->aide_op); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>ANESTHESISTE</b></td>
                                            <td><b>Dr.</b> <?php echo e($compteRenduBlocOperatoire->anesthesiste); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>INFIRMIER ANESTHESISTE</b></td>
                                            <td> <?php echo e($compteRenduBlocOperatoire->infirmier_anesthesiste); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>DATE D'ENTREE</b> </td>
                                            <td><?php echo e($compteRenduBlocOperatoire->date_e); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>TYPE D'ENTREE</b></td>
                                            <td> <?php echo e($compteRenduBlocOperatoire->type_e); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>DATE DE SORTIE</b></td>
                                            <td> <?php echo e($compteRenduBlocOperatoire->date_s); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>TYPE DE SORTIE</b></td>
                                            <td> <?php echo e($compteRenduBlocOperatoire->type_s); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>DATE INTERVENTION</b></td>
                                            <td> <?php echo e($compteRenduBlocOperatoire->date_intervention); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>DUREE INTERVENTION</b></td>
                                            <td> <?php echo e($compteRenduBlocOperatoire->dure_intervention); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>COMPTE-RENDU OPERATOIRE</b></td>
                                            <td><?php echo e($compteRenduBlocOperatoire->compte_rendu_o); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>INDICATIONS OPERATOIRE</b></td>
                                            <td><?php echo e($compteRenduBlocOperatoire->indication_operatoire); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>RESULTATS HISTOPATHOLOGIQUES</b></td>
                                            <td><?php echo e($compteRenduBlocOperatoire->resultat_histo); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>SUITES OPERATOIRES</b></td>
                                            <td><?php echo e($compteRenduBlocOperatoire->suite_operatoire); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>TRAITEMENT PROPOSE</b></td>
                                            <td><?php echo e($compteRenduBlocOperatoire->traitement_propose); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>SOINS</b></td>
                                            <td><?php echo e($compteRenduBlocOperatoire->soins); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>CONCLUSION</b></td>
                                            <td><?php echo e($compteRenduBlocOperatoire->conclusion); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>

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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/chirurgiens/index_compte_rendu_operatoire.blade.php ENDPATH**/ ?>