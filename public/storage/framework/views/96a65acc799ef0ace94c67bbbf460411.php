
<?php $__env->startSection('title', 'CMCU | Consultations chirurgien'); ?>
<?php $__env->startSection('content'); ?>
    <body>
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="container">
            <div class="row">
                <h1 class="text-center">CONSULTATIONS DU PATIENT</h1>
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
                                <?php $__currentLoopData = $consultations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consultation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="table-active text-primary"><b><h6>CONSULTATION DU</h6></b></td>
                                    <td class="table-active"><b><?php echo e($consultation->created_at->toFormattedDateString()); ?></b></td>
                                    <!-- <td><a class="btn btn-success btn-action-fixed" title="Imprimer la lettre de sortie" href="<?php echo e(route('print.sortie', $patient->id)); ?>">
                                        <i class="fas fa-print"></i>
                                        </a>
                                    </td> -->
                                </tr>
                                <tr>
                                    <td><b>MEDECIN DE REFERENCE</b></td>
                                    <td> Dr. <?php echo e($consultation->medecin_r); ?></td>
                                </tr>
                                <tr>
                                    <td><b>NOM ET PRENOM DU MEDECIN</b></td> 
                                    <td> Dr. <?php echo e($consultation->user->name); ?></td>
                                </tr>
                                <tr>
                                    <td><b>MOTIF DE CONSULTATION</b></td>
                                    <td><?php echo e($consultation->motif_c); ?></td>
                                </tr>
                                <tr>
                                    <td><b>INTERROGATOIE</b></td>
                                    <td><?php echo e($consultation->interrogatoire); ?></td>
                                </tr>
                                <tr>
                                    <td><b>EXAMENS PHYSIQUES</b></td>
                                    <td><?php echo e($consultation->examen_p); ?></td>
                                </tr>
                                <tr>
                                    <td><b>EXAMENS COMPLEMENTAIRES</b></td>
                                    <td><?php echo e($consultation->examen_c); ?></td>
                                </tr>
                                <tr>
                                    <td><b>PROPOSITIONS THERAPEUTIQUES</b></td>
                                    <td><?php echo e($consultation->proposition_therapeutique); ?></td>
                                </tr>
                                <tr>
                                    <td><b>PROPOSITIONS DE SUIVI</b></td>
                                    <td><?php echo e($consultation->proposition); ?></td>
                                </tr>
                                <?php if(!empty($consultation->type_intervention)): ?>
                                <tr>
                                    <td><b>TYPE D'INTERVENTION</b></td>
                                    <td><?php echo e($consultation->type_intervention); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(!empty($consultation->date_intervention)): ?>
                                <tr>
                                    <td><b>DATE INTERVENTION</b></td>
                                    <td><?php echo e($consultation->date_intervention); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(!empty($consultation->acte)): ?>
                                <tr>
                                    <td><b>TYPE D'ACTES A REALISER</b></td>
                                    <td><?php echo e($consultation->acte); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(!empty($consultation->devis_id)): ?>
                                <tr>
                                    <td><b>DEVIS PREVISIONNEL</b></td>
                                    <td><?php echo e($consultation->devis_id); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(!empty($consultation->date_consultation_anesthesiste)): ?>
                                <tr>
                                    <td><b>DATE CONSULTATION ANESTHESISTE</b></td>
                                    <td><?php echo e($consultation->date_consultation_anesthesiste); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(!empty($consultation->date_consultation)): ?>
                                <tr>
                                    <td><b>DATE PROCHAINE CONSULTATION</b></td>
                                    <td><?php echo e($consultation->date_consultation); ?></td>
                                </tr>
                                <?php endif; ?>
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



<?php $__env->startPush('scripts'); ?>
<script>
    // Initialize checkbox states when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initializeCheckboxStates();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/consultations/chirurgiens/index_consultation_chirurgien.blade.php ENDPATH**/ ?>