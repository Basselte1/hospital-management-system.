

<?php $__env->startSection('title', 'CMCU | Surveillance post-anesthésique'); ?>

<?php $__env->startSection('content'); ?>

    <body>

    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
                                            <h1 class="text-info">SURVEILLANCE</h1>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php $__currentLoopData = $surveillance_post_anesthesiques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveillance_post_anesthesique): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!empty($surveillance_post_anesthesique->surveillance)): ?>
                                        <tr>
                                            <td class="table-active"><b class="badge badge-danger">DATE: <?php echo e($surveillance_post_anesthesique->date_creation); ?></b></td>
                                            <td class="table-active">
                                                <button class="btn btn-primary mb-1" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#SpostAnesthUpdate<?php echo e($surveillance_post_anesthesique->id); ?>"
                                                        title="Apporter des modifications">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($surveillance_post_anesthesique->surveillance)): ?>
                                        <tr>
                                            <td><b>SURVEILLANCE</b></td>
                                            <td><?php echo e(nl2br($surveillance_post_anesthesique->surveillance)); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($surveillance_post_anesthesique->traitement)): ?>
                                        <tr>
                                            <td><b>TRAITEMENT</b></td>
                                            <td><?php echo e(nl2br($surveillance_post_anesthesique->traitement)); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($surveillance_post_anesthesique->examen_paraclinique)): ?>
                                        <tr>
                                            <td><b>EXAMEN</b></td>
                                            <td><?php echo e(nl2br($surveillance_post_anesthesique->examen_paraclinique)); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($surveillance_post_anesthesique->observation)): ?>
                                        <tr>
                                            <td><b>OBSERVATION</b></td>
                                            <td><?php echo e(nl2br($surveillance_post_anesthesique->observation)); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($surveillance_post_anesthesique->date_sortie)): ?>
                                        <tr>
                                            <td><b>DATE / HEURE de SORTIE</b></td>
                                            <td>
                                                <p>Date: <?php echo e(nl2br($surveillance_post_anesthesique->date_sortie)); ?></p>
                                                <p>Heure: <?php echo e(nl2br($surveillance_post_anesthesique->heur_sortie)); ?></p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

            <?php echo $__env->make('admin.modal.surveillance_post_a_update', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
    </body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/index_surveillance_post_anesthesique.blade.php ENDPATH**/ ?>