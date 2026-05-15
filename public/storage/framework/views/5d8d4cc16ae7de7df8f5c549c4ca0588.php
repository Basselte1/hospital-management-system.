<div class="modal fade" id="imagerieAll" tabindex="-1" role="dialog" aria-labelledby="feuilleAll" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">EXAMENS IMAGERIE</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if(count($patient->imageries)): ?>

                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordred table-striped">
                            <thead>
                            <th>DESCRIPTION</th>
                            <th>DATE</th>
                            <th>IMPPRIMER</th>
                            </thead>
                            <tbody>

                            <?php $__currentLoopData = $patient->imageries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imagerie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <td>
                                        <?php echo e($imagerie->radiographie); ?>

                                        <?php echo e($imagerie->echographie); ?>

                                        <?php echo e($imagerie->scanner); ?>

                                        <?php echo e($imagerie->irm); ?>

                                        <?php echo e($imagerie->scintigraphie); ?>

                                        <?php echo e($imagerie->autre); ?>

                                    </td>
                                    <td><?php echo e($imagerie->created_at->toFormattedDateString()); ?></td>
                                    <td>
                                        <a class="btn btn-success btn-sm" title="Imprimer l'ordonance" href="<?php echo e(route('imageries_examens.pdf', $imagerie->id)); ?>">
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
<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/modal/index_examen_imagerie.blade.php ENDPATH**/ ?>