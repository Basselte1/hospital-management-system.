<div class="modal fade" id="DetailPremedication" tabindex="-1" role="dialog" aria-labelledby="ordonanceAll" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">PREMEDICATION</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if(count($patient->premedications)): ?>

                    <h3>PREMEDICATION</h3>
                    <br>
                    <div class="table-responsive">
                        <?php if(count($premedications)): ?>
                            <table id="myTable" class="table table-bordred table-striped">
                                <thead>
                                <th>MEDICAMENT</th>
                                <th>CONSIGNES IDE</th>
                                <th>PREPARATION</th>
                                <th>DATE DE CREATION</th>
                                </thead>
                                <tbody>

                                <?php $__currentLoopData = $patient->premedications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $premedication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <tr>
                                        <td><?php echo e($premedication->medicament); ?></td>
                                        <td><?php echo e($premedication->consigne_ide); ?></td>
                                        <td><?php echo e($premedication->preparation); ?></td>
                                        <td><?php echo e($premedication->created_at); ?></td>
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

<?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/modal/detail_premedication_preparation.blade.php ENDPATH**/ ?>