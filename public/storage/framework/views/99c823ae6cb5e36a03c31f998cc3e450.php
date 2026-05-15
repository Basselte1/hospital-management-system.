<div class="modal fade" id="ficheSuiviAll" tabindex="-1" role="dialog" aria-labelledby="ficheSuiviAll" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CONSULTATION DE SUIVI</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php if(count($patient->consultationdesuivi)): ?>

                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordred table-striped">
                            <thead>
                            <th>INTERROGATOIRE</th>
                            <th>COMMENTAIRE</th>
                            <th>DATE</th>
                            <th>VOIR</th>
                            </thead>
                            <tbody>

                            <?php $__currentLoopData = $patient->consultationdesuivi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consultationdesuivis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    
                                    <td><?php echo e($consultationdesuivis->interrogatoire); ?></td>
                                    <td> <?php echo e($consultationdesuivis->commentaire); ?></td>
                                    <td><?php echo e($consultationdesuivis->date_creation); ?></td>
                                       
                                    
                                    <td>
                                        <a class="btn btn-primary btn-sm" title="voir" href="<?php echo e(route('consultationsdesuivi.show', $consultationdesuivis->id)); ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr></tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            </tbody>
                        </table>
                        <div class="clearfix"></div>
                      

                    </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/modal/fichede_suivi.blade.php ENDPATH**/ ?>