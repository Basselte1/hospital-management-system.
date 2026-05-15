<div class="modal fade" id="SurveillanceAptitude" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <small>FREQUENCE DE SURVEILLANCE J'USQU'A L'OBTENSION D'UN SCORE D'APTITUDE D'AU MOINS 9/10. lOSQUE LE PATIENT <span class="text-danger"><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></span> EST HABILLE ET SORTIE DE LA CHAMBRE, ARRET DE LA SUVEILLANCE</h5>
                <button type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('surveillance_score.store')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="container">
                        <div class="col-md-10  toppad">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td style="width: 50%;">
                                                <label for=""><b>HORAIRES :</b> <span class="text-danger">*</span></label>
                                                <input type="datetime-local" class="form-control" name="horaire" value="<?php echo e(old('horaire')); ?>" required>
                                            </td>
                                            <td>
                                                <label for=""><b>T.A s/d :</b> <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="ta" value="<?php echo e(old('ta')); ?>" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for=""><b>F.C :</b> <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="fc" value="<?php echo e(old('fc')); ?>" required>
                                            </td>
                                            <td>
                                                <label for=""><b>SPO2 :</b> <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="spo2" value="<?php echo e(old('spo2')); ?>" required>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <label for=""><b>F.R :</b> <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="fr" value="<?php echo e(old('fr')); ?>" required>
                                            </td>
                                            <td>
                                                <label for=""><b>DOULEUR (EN/EVA) :</b></label>
                                                <input type="text" class="form-control" name="douleur" value="<?php echo e(old('douleur')); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for=""><b>T° :</b> <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="temperature" min="0" step="any" value="<?php echo e(old('temperature')); ?>" required>
                                            </td>
                                            <td>
                                                <label for=""><b>GLYCEMIE :</b></label>
                                                <input type="text" class="form-control" name="glycemie" value="<?php echo e(old('glycemie')); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for=""><b>SEDATION :</b></label>
                                                <input type="text" class="form-control" name="sedation" value="<?php echo e(old('sedation')); ?>">
                                            </td>
                                            <td>
                                                <label for=""><b>NAUSEES :</b></label>
                                                <input type="text" class="form-control" name="nausee" value="<?php echo e(old('nausee')); ?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <label for=""><b>VOMISSEMENTS :</b></label>
                                                <input type="text" class="form-control" name="vomissement" value="<?php echo e(old('vomissement')); ?>">
                                            </td>
                                            <td>
                                                <label for=""><b>SAIGNEMENTS :</b></label>
                                                <input type="text" class="form-control" name="saignement" value="<?php echo e(old('saignement')); ?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <label for=""><b>PANSEMENTS :</b></label>
                                                <input type="text" class="form-control" name="pansement" value="<?php echo e(old('pansement')); ?>">
                                            </td>
                                            <td>
                                                <label for=""><b>CONSCIENCE :</b></label>
                                                <input type="text" class="form-control" name="conscience" value="<?php echo e(old('conscience')); ?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <label for=""><b>DRAINS :</b></label>
                                                <input type="text" class="form-control" name="drains" value="<?php echo e(old('drains')); ?>">
                                            </td>
                                            <td>
                                                <label for=""><b>MICTION :</b></label>
                                                <select name="miction" class="form-control">
                                                    <option value="">Miction</option>
                                                    <option value="Oui"> Oui</option>
                                                    <option value="Non"> Non</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for=""><b>LEVER :</b></label>
                                                <select name="lever" class="form-control">
                                                    <option value="">Lever</option>
                                                    <option value="Oui"> Oui</option>
                                                    <option value="Non"> Non</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label for=""><b>SCORE D'APTITUDE :</b></label>
                                                <input type="text" class="form-control" name="score" value="<?php echo e(old('score')); ?>">
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="hidden" value="<?php echo e($patient->id); ?>" name="patient_id">
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/modal/surveillance_aptitude_form.blade.php ENDPATH**/ ?>