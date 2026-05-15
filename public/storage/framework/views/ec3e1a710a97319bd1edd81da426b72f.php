<div class="modal fade" id="PrescriptionMedicale" tabindex="-1" aria-labelledby="PrescriptionMedicaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-sm">
            
            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="PrescriptionMedicaleLabel">
                    <i class="fas fa-pills"></i> Nouvelle prescription
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                
                <form action="<?php echo e(route('prescription_medicale.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">

                    <!-- Patient info -->
                    <p class="text-danger fw-bold mb-3">
                        <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?>

                    </p>

                    <!-- Médicament & Posologie -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><b>Médicament & Forme :</b></label>
                            <input type="text" name="medicament" class="form-control" 
                                   placeholder="Ex: Paracétamol 500mg comprimé" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><b>Posologie :</b></label>
                            <input type="text" name="posologie" class="form-control" 
                                   placeholder="Ex: " required>
                        </div>
                    </div>

                    <!-- Horaire & Voie -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label"><b>Horaire d'administration :</b></label>
                            <small class="text-muted d-block mb-2">Sélectionnez au moins un horaire</small>
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = ['00H','02H','04H','06H','08H','10H','12H','14H','16H','18H','20H','22H']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $horaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" 
                                               id="horaire<?php echo e($horaire); ?>" 
                                               name="horaire[]" 
                                               value="<?php echo e($horaire); ?>">
                                        <label class="form-check-label" for="horaire<?php echo e($horaire); ?>">
                                            <?php echo e($horaire); ?>

                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><b>Voie :</b></label>
                            <select id="voieSelect" name="voie" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="PO">PO (Per Os - Orale)</option>
                                <option value="IV">IV (Intraveineuse)</option>
                                <option value="IM">IM (Intramusculaire)</option>
                                <option value="SC">SC (Sous-cutanée)</option>
                                <option value="Rectale">Rectale</option>
                                <option value="Cutanée">Cutanée</option>
                                <option value="Inhalation">Inhalation</option>
                                <option value="Autre">Autre</option>
                            </select>

                            <!-- Hidden input for custom value -->
                            <input type="text" id="voieAutreInput" name="voie_autre" 
                                class="form-control mt-2" 
                                placeholder="Précisez la voie" 
                                style="display:none;">
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const voieSelect = document.getElementById("voieSelect");
    const voieAutreInput = document.getElementById("voieAutreInput");

    function toggleAutreInput() {
        if (voieSelect && voieAutreInput) {
            if (voieSelect.value === "Autre") {
                voieAutreInput.style.display = "block";
                voieAutreInput.required = true;
            } else {
                voieAutreInput.style.display = "none";
                voieAutreInput.required = false;
                voieAutreInput.value = "";
            }
        }
    }

    if (voieSelect) {
        voieSelect.addEventListener("change", toggleAutreInput);
        toggleAutreInput(); // Run on page load
    }
});
</script><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/consultations/infirmiers/form/elt_prescription_medicale_form.blade.php ENDPATH**/ ?>