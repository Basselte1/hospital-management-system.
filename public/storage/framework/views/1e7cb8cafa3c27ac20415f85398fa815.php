<div class="modal fade" id="biologieAll" tabindex="-1" role="dialog" aria-labelledby="feuilleAll" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" >EXAMENS BIOLOGIE</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php if(count($patient->prescriptions)): ?>

                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordred table-striped">
                            <thead>
                            <th>DESCRIPTION</th>
                            <th>DATE</th>
                            <th>IMPPRIMER</th>
                            </thead>
                            <tbody>

                            <?php $__currentLoopData = $patient->prescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <td>
                                        <?php echo e($prescription->hematologie); ?>

                                        <?php echo e($prescription->hemostase); ?>

                                        <?php echo e($prescription->biochimie); ?>

                                        <?php echo e($prescription->hormonologie); ?>

                                        <?php echo e($prescription->marqeurs); ?>

                                        <?php echo e($prescription->bacteriologie); ?>

                                        <?php echo e($prescription->spermiologie); ?>

                                        <?php echo e($prescription->serologie); ?>

                                        <?php echo e($prescription->urines); ?>

                                        <?php echo e($prescription->examen); ?>

                                    </td>
                                    <td><?php echo e($prescription->created_at->toFormattedDateString()); ?></td>
                                    <td>
                                        <a class="btn btn-success btn-sm" title="Imprimer l'ordonance" href="<?php echo e(route('prescription_examens.pdf', $prescription->id)); ?>">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr></tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            </tbody>
                        </table>
                        <div class="clearfix"></div>
                        <?php echo e($ordonances->links()); ?>


                    </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/modal/index_examen_biologie.blade.php ENDPATH**/ ?>