<div class="modal fade" id="SpostAnesth" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SURVEILLANCE POST ANESTHESIQUE - <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></h5>
                <button type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('surveillance_post_anesthesise.store')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="container">
                        <div class="col-md-10  toppad">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td><b>Surveillance :</b> <span class="text-danger">*</span></td>
                                            <td>
                                                <textarea name="surveillance" class="form-control" cols="55" rows="3" required></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Traitement(s) :</b> </td>
                                            <td>
                                                <textarea name="traitement" class="form-control" cols="55" rows="3" required></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Examen(s) paraclinique(s) post opératoire :</b> </td>
                                            <td>
                                                <textarea name="examen_paraclinique" class="form-control" cols="55" rows="3" required></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Observation(s) :</b> <span class="text-danger">*</span></td>
                                            <td>
                                                <textarea name="observation" class="form-control" cols="55" rows="3" required></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for=""><b>Date de sortie :</b></label>
                                                <input type="date" class="form-control" name="date_sortie" required>
                                            </td>
                                            <td>
                                                <label for=""><b>Heure de sortie :</b></label>
                                                <input type="time" class="form-control col-md-5" name="heur_sortie" required>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Ajouter au dossier</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="hidden" value="<?php echo e($patient->id); ?>" name="patient_id">
                    <input type="hidden" value="<?php echo e(Carbon\Carbon::now()->ToDateString()); ?>" name="date_creation">
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/modal/surveillance_post_a.blade.php ENDPATH**/ ?>