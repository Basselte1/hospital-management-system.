<div class="modal fade" id="FicheInterventionAll" tabindex="-1" role="dialog" aria-labelledby="ordonanceAll" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">FICHES D'INTERVENTION</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if(count($patient->fiche_interventions)): ?>

                    <h3>FICHES D'INTERVENTION</h3>
                    <br>
                    <div class="table-responsive">
                        <?php if(count($fiche_interventions)): ?>
                            <table id="myTable" class="table table-bordred table-striped">
                                <thead>
                                <th>TYPE D'INTERVENTION</th>
                                <th>POSITION PATIENT</th>
                                <th>ANESTHESIE</th>
                                <th>DATE INTERVENTION</th>
                                <th>IMPPRIMER</th>
                                </thead>
                                <tbody>

                                <?php $__currentLoopData = $patient->fiche_interventions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fiche_intervention): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <tr>
                                        <td><?php echo e($fiche_intervention->type_intervention); ?></td>
                                        <td><?php echo e($fiche_intervention->position_patient); ?></td>
                                        <td><?php echo e($fiche_intervention->anesthesie); ?></td>
                                        <td><?php echo e($fiche_intervention->date_intervention); ?></td>
                                        <td>
                                            <a class="btn btn-success btn-sm" title="Imprimer la fiche d'intervention" href="<?php echo e(route('fiche_intervention.pdf', $fiche_intervention->id)); ?>">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr></tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                </tbody>
                            </table>
                        <?php endif; ?>
                        <div class="clearfix"></div>
                        

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/modal/fiche_intervention_show.blade.php ENDPATH**/ ?>