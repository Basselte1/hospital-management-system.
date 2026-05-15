<?php if($consultation->id): ?>
    <form method="POST" action="<?php echo e(route('consultation_chirurgien.update', $consultation->id)); ?>" class="form-horizontal form-label-left">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
<?php else: ?>
    <form method="POST" action="<?php echo e(route('consultation_chirurgien.store')); ?>" class="form-horizontal form-label-left">
        <?php echo csrf_field(); ?>
<?php endif; ?>

<tr>
    <td>
        <h5 class="text-primary"><strong>CONSULTATION</strong></h5>
    </td>
    <td></td>
</tr>
<tr> 
    <td><b>Médecin de référence :</b> <span class="text-danger">*</span></td>
    <td>
        <input type="text" class="form-control splitLines" name="medecin_r" id="medecin_r" value="<?php echo e(old('medecin_r', $consultation->medecin_r ?? Auth::user()->name . ' ' . Auth::user()->prenom)); ?>">
    </td>
</tr>
<tr>
    <td><b>Motif de consultation :</b> <span class="text-danger">*</span></td>
    <td><textarea name="motif_c" class="form-control splitLines" rows="4" required><?php echo e(old('motif_c', $consultation->motif_c ?? '')); ?></textarea></td>
</tr>
<tr>
    <td><b>Interrogatoire :</b> <span class="text-danger">*</span></td>
    <td><textarea name="interrogatoire" class="form-control splitLines" rows="5" required><?php echo e(old('interrogatoire', $consultation->interrogatoire ?? '')); ?></textarea></td>
</tr>
<tr>
    <td><b>Antécédents médicaux :</b></td>
    <td><textarea name="antecedent_m" class="form-control splitLines" rows="3"><?php echo e(old('antecedent_m', $consultation->antecedent_m ?? '')); ?></textarea></td>
</tr>
<tr>
    <td><b>Antécédents chirurgicaux :</b></td>
    <td><textarea name="antecedent_c" class="form-control splitLines" rows="3"><?php echo e(old('antecedent_c', $consultation->antecedent_c ?? '')); ?></textarea></td>
</tr>
<tr>
    <td><b>Allergies :</b></td>
    <td><textarea name="allergie" placeholder="Laisser vide si Aucune allergie a signaler." class="form-control splitLines" rows="3"><?php echo e(old('allergie', $consultation->allergie ?? '')); ?></textarea></td>
</tr>
<tr>
    <td><b>Groupe sanguin du patient :</b></td>
    <td>
        <select class="form-control col-md-5" name="groupe" id="groupe">
            <option value="">Groupes sanguins</option>
            <?php $__currentLoopData = ['O-', 'O+', 'B-', 'B+', 'A-', 'A+', 'AB-', 'AB+']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($group); ?>" <?php echo e(old('groupe', $consultation->groupe ?? '') == $group ? 'selected' : ''); ?>><?php echo e($group); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </td>
</tr>
<tr>
    <td>
        <h5 class="text-primary"><strong>EXAMENS</strong></h5>
    </td>
    <td></td>
</tr>
<tr>
    <td><input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>" class="form-control"></td>
    <td></td>
</tr>
<tr>
    <td><b>Examens physiques :</b> <span class="text-danger">*</span></td>
    <td><textarea name="examen_p" class="form-control splitLines" rows="4" required><?php echo e(old('examen_p', $consultation->examen_p ?? '')); ?></textarea></td>
</tr>
<tr>
    <td><b>Examens compléméntaires:</b> <span class="text-danger">*</span></td>
    <td><textarea name="examen_c" class="form-control splitLines" rows="4" required><?php echo e(old('examen_c', $consultation->examen_c ?? '')); ?></textarea></td>
</tr>
<tr>
    <td><b>Diagnostic médical :</b> <span class="text-danger">*</span></td>
    <td><textarea name="diagnostic" class="form-control splitLines" rows="4" required><?php echo e(old('diagnostic', $consultation->diagnostic ?? '')); ?></textarea></td>
</tr>
<tr>
    <td><b>Proposition thérapeutique :</b> <span class="text-danger">*</span></td>
    <td><textarea name="proposition_therapeutique" class="form-control splitLines" rows="4" required><?php echo e(old('proposition_therapeutique', $consultation->proposition_therapeutique ?? '')); ?></textarea></td>
</tr>
<tr>
    <td><b>Proposition de suivi :</b> <span class="text-danger">*</span></td>
    <td class="form-group small">
        <?php
            $propositions = [];
            if (isset($consultation->proposition)) {
                if (is_string($consultation->proposition)) {
                    $propositions = array_map('trim', explode(',', $consultation->proposition));
                } elseif (is_array($consultation->proposition)) {
                    $propositions = $consultation->proposition;
                }
            }
            // Check old input first (for validation errors)
            if (old('proposition')) {
                $propositions = old('proposition');
            }
        ?>
        <div class="form-check">
            <input class="form-check-input" onClick="ckChange(this)" type="checkbox" name="proposition[]" id="decision1" value="Hospitalisation" <?php echo e(in_array('Hospitalisation', $propositions) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="decision1">Hospitalisation</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" onClick="ckChange(this)" type="checkbox" name="proposition[]" id="decision2" value="Intervention chirurgicale" <?php echo e(in_array('Intervention chirurgicale', $propositions) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="decision2">Intervention chirurgicale</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" onClick="ckChange(this)" type="checkbox" name="proposition[]" id="decision3" value="Consultation" <?php echo e(in_array('Consultation', $propositions) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="decision3">Consultation</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" onClick="ckChange(this)" type="checkbox" name="proposition[]" id="decision4" value="Actes à réaliser" <?php echo e(in_array('Actes à réaliser', $propositions) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="decision4">Actes à réaliser</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" onClick="ckChange(this)" type="checkbox" name="proposition[]" id="decision5" value="Consultation d'anesthésiste" <?php echo e(in_array("Consultation d'anesthésiste", $propositions) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="decision5">Consultation d'anesthésiste</label>
        </div>
    </td>
</tr>
<tr id="type_intervention" style='display:none;'>
    <td><b>Type d'intervention :</b></td>
    <td><textarea name="type_intervention" class="form-control splitLines" rows="4"><?php echo e(old('type_intervention', $consultation->type_intervention ?? '')); ?></textarea></td>
</tr>
<tr id="type_intervention_date">
    <td><b>Date intervention :</b></td>
    <td>
        <?php
            // Format datetime for datetime-local input
            $dateIntervention = old('date_intervention', $consultation->date_intervention ?? '');
            if ($dateIntervention && $dateIntervention instanceof \Carbon\Carbon) {
                $dateIntervention = $dateIntervention->format('Y-m-d\TH:i');
            } elseif ($dateIntervention) {
                try {
                    $dateIntervention = \Carbon\Carbon::parse($dateIntervention)->format('Y-m-d\TH:i');
                } catch (\Exception $e) {
                    $dateIntervention = '';
                }
            }
        ?>
        <input type="datetime-local" name="date_intervention" value="<?php echo e($dateIntervention); ?>" class="form-control col-md-7">
        <small class="form-text text-muted">Sélectionnez la date et l'heure de l'intervention</small>
    </td>
</tr>
<tr id="type_acte" style='display:none;'>
    <td><b>Type d'actes à réaliser :</b></td>
    <td>
        <?php
            $actes = [];
            if (isset($consultation->acte)) {
                if (is_string($consultation->acte)) {
                    $actes = array_map('trim', explode(',', $consultation->acte));
                } elseif (is_array($consultation->acte)) {
                    $actes = $consultation->acte;
                }
            }
            // Check old input first (for validation errors)
            if (old('acte')) {
                $actes = old('acte');
            }
        ?>
        <div class="form-check">
            <input type="checkbox" name="acte[]" id="acte1" value="Echographie de l'arbre urinaire" <?php echo e(in_array("Echographie de l'arbre urinaire", $actes) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="acte1">Echographie de l'arbre urinaire</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="acte[]" id="acte2" value="Cystoscopie" <?php echo e(in_array('Cystoscopie', $actes) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="acte2">Cystoscopie</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="acte[]" id="acte3" value="Biopsie prostatique" <?php echo e(in_array('Biopsie prostatique', $actes) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="acte3">Biopsie prostatique</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="acte[]" id="acte4" value="Débitimétrie" <?php echo e(in_array('Débitimétrie', $actes) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="acte4">Débitimétrie</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="acte[]" id="acte5" value="Echographie prostatique sous rectale" <?php echo e(in_array('Echographie prostatique sous rectale', $actes) ? 'checked' : ''); ?>> 
            <label class="form-check-label" for="acte5">Echographie prostatique sous rectale</label>
        </div>
    </td>
</tr>
<tr id="anesthesiste" style='display:none;'>
    <td><b>Date consultation anesthésiste :</b></td>
    <td>
        <?php
            // Format datetime for datetime-local input
            $dateConsultAnesthesiste = old('date_consultation_anesthesiste', $consultation->date_consultation_anesthesiste ?? '');
            if ($dateConsultAnesthesiste && $dateConsultAnesthesiste instanceof \Carbon\Carbon) {
                $dateConsultAnesthesiste = $dateConsultAnesthesiste->format('Y-m-d\TH:i');
            } elseif ($dateConsultAnesthesiste) {
                try {
                    $dateConsultAnesthesiste = \Carbon\Carbon::parse($dateConsultAnesthesiste)->format('Y-m-d\TH:i');
                } catch (\Exception $e) {
                    $dateConsultAnesthesiste = '';
                }
            }
        ?>
        <input type="datetime-local" name="date_consultation_anesthesiste" value="<?php echo e($dateConsultAnesthesiste); ?>" class="form-control col-md-7">
        <small class="form-text text-muted">Sélectionnez la date et l'heure de la consultation</small>
    </td>
</tr>
<tr id="consultation" style='display:none;'>
    <td><b>Date de consultation :</b></td>
    <td>
        <?php
            // Format datetime for datetime-local input
            $dateConsultation = old('date_consultation', $consultation->date_consultation ?? '');
            if ($dateConsultation && $dateConsultation instanceof \Carbon\Carbon) {
                $dateConsultation = $dateConsultation->format('Y-m-d\TH:i');
            } elseif ($dateConsultation) {
                try {
                    $dateConsultation = \Carbon\Carbon::parse($dateConsultation)->format('Y-m-d\TH:i');
                } catch (\Exception $e) {
                    $dateConsultation = '';
                }
            }
        ?>
        <input type="datetime-local" name="date_consultation" value="<?php echo e($dateConsultation); ?>" class="form-control col-md-7">
        <small class="form-text text-muted">Sélectionnez la date et l'heure de la prochaine consultation</small>
    </td>
</tr>
<tr>
    <td>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </td>
    <td></td>
</tr>
</form><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/consultations/chirurgiens/form/consultation_chirurgien_form.blade.php ENDPATH**/ ?>