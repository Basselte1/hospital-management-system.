
<?php $__env->startSection('title', 'CMCU | Consultations anesthésiques'); ?>
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

                                    <td><h1 class="text-info">CONSULTATIONS</h1></td>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $consultationAnesthesistes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consultationAnesthesiste): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="table-active"><b>DATE</b></td>
                                        <td class="table-active"><?php echo e($consultationAnesthesiste->created_at->toFormattedDateString()); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>SPECIALITE</b></td>
                                        <td> <?php echo e($consultationAnesthesiste->specialite); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>MEDECIN TRAITANT</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->medecin_traitant); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>OPERATEUR</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->operateur); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>ANESTHESISTE</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->user->name); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>MOTIF DE D'ADMISSION</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->motif_admission); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>DATE D'INTERVENTION</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->date_intervention); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>MEMO</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->memo); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>ANESTHESIE EN SALLE</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->anesthesi_salle); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>DATE D'HOSPITALISATION</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->date_hospitalisation); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>CLASSE ASA</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->classe_asa); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>BENEFICES RISQUES</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->benefice_risque); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>RISQUES</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->risque); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>JEUNE PRE-OPERATOIRE</b> </td>
                                        <td>
                                            <p><b>Solide : </b></p>
                                            <?php echo e($consultationAnesthesiste->solide); ?>

                                            <p><b>Liquide : </b></p>
                                            <?php echo e($consultationAnesthesiste->liquide); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>ANTECEDENTS / TRAITEMENT</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->antecedent_traitement); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>ALLERGIES</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->allergie); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>EXAMENS CLINIQUES</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->examen_clinique); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <p><b>INTUBATION :</b></p>
                                            <?php echo e($consultationAnesthesiste->intubation); ?>

                                            <p><b>MALLAMPATI :</b></p>
                                            <?php echo e($consultationAnesthesiste->mallampati); ?>

                                            <p><b>DISTANCE-INTERINCISIVE :</b></p>
                                            <?php echo e($consultationAnesthesiste->distance_interincisive); ?>

                                            <p><b>DISTANCE-TYROMENTONIERE :</b></p>
                                            <?php echo e($consultationAnesthesiste->distance_tyromentoniere); ?>

                                            <p><b>MOBILITE SERVICALE :</b></p>
                                            <?php echo e($consultationAnesthesiste->mobilite_servicale); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>TECHNIQUE D'ANESTHESIE</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->technique_anesthesie1); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>TECHNIQUE D'ANESTHESIE ENVISAGEE</b> </td>
                                        <td>
                                            <?php $__currentLoopData = explode(",", $consultationAnesthesiste->technique_anesthesie); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $technique): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <ul>
                                                    <li>
                                                        <?php echo e($technique); ?>

                                                    </li>
                                                </ul>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>ANTIBIOPROPHYLAXIE</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->antibiotique); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>SYNTHES PREOPERATOIRE</b> </td>
                                        <td><?php echo e($consultationAnesthesiste->synthese_preop); ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>EXAMENS PARACLINIQUES</b> </td>
                                        <td>
                                            <?php $__currentLoopData = explode(",", $consultationAnesthesiste->examen_paraclinique); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <ul>
                                                    <li>
                                                        <?php echo e($examen); ?>

                                                    </li>
                                                </ul>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/consultations/anesthesistes/index_consultation_anesthesiste.blade.php ENDPATH**/ ?>