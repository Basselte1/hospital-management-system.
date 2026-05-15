<div class="modal fade" id="EditObservationModal<?php echo e($observation_medicale->id); ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier Observation Médicale</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('observations_medicales.update', $observation_medicale->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" value="<?php echo e($observation_medicale->date); ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Observation</label>
                        <textarea name="observation" class="form-control" rows="4" required><?php echo e($observation_medicale->observation); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Anesthésiste</label>
                        <input type="text" name="anesthesiste" value="<?php echo e($observation_medicale->anesthesiste); ?>" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/observation_medicale_edit.blade.php ENDPATH**/ ?>