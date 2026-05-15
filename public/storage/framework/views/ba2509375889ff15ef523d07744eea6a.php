<?php if($parametre->id): ?>
    <form method="post" action="<?php echo e(route('fiche_parametres.update', $parametre->id)); ?>" class="form-horizontal form-label-left">
        <?php echo method_field('put'); ?>
        <?php echo csrf_field(); ?>
<?php else: ?>
    <form method="post" action="<?php echo e(route('fiche_parametres.store')); ?>" class="form-horizontal form-label-left">
        <?php echo csrf_field(); ?>
<?php endif; ?>

    <table class="table">
        <tbody>
        <tr>
            <td>
                <b>Date de naissance : <span class="text-danger">*</span></b>
            </td>
            <td>
                <?php if(isset($dossier) && $dossier && $dossier->date_naissance): ?>
                    
                    <div class="input-group">
                        <input type="date" 
                               name="date_naissance" 
                               value="<?php echo e(old('date_naissance', $dossier->date_naissance)); ?>" 
                               class="form-control bg-light" 
                             
                               required>
                        <span class="input-group-text bg-info text-white" title="Valeur récupérée du dossier patient">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Récupéré du dossier patient
                    </small>
                <?php else: ?>
                    
                    <input type="date" 
                           name="date_naissance" 
                           value="<?php echo e(old('date_naissance', $parametre->date_naissance ?? '')); ?>" 
                           class="form-control" 
                           required>
                    <small class="text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Veuillez compléter le dossier patient pour éviter de saisir à nouveau
                    </small>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><b>TA :</b> <span class="text-danger">*</span></td>
            <td>
                <label for="bras_gauche">Bras gauche :</label>
                <input type="text" 
                       name="bras_gauche" 
                       id="bras_gauche" 
                       value="<?php echo e(old('bras_gauche', $parametre->bras_gauche ?? '')); ?>" 
                       class="form-control" 
                       placeholder=" mmHg" 
                       required>
                <label for="bras_droit">Bras droit :</label>
                <input type="text" 
                       name="bras_droit" 
                       id="bras_droit" 
                       value="<?php echo e(old('bras_droit', $parametre->bras_droit ?? '')); ?>" 
                       class="form-control" 
                       placeholder=" mmHg" 
                       required>
            </td>
        </tr>
        <tr>
            <td><b>Température :</b> <span class="text-danger">*</span></td>
            <td>
                <input type="number" 
                       name="temperature" 
                       value="<?php echo e(old('temperature', $parametre->temperature ?? '')); ?>" 
                       class="form-control col-md-5" 
                       placeholder=" °C" 
                       step="any" 
                       required>
            </td>
        </tr>
        <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">
        <tr>
            <td><b>FR :</b> <span class="text-danger">*</span></td>
            <td>
                <input type="text" 
                       name="fr" 
                       value="<?php echo e(old('fr', $parametre->fr ?? '')); ?>" 
                       class="form-control" 
                       placeholder="  Mvts/min" 
                       required>
            </td>
        </tr>
        <tr>
            <td><b>FC :</b> <span class="text-danger">*</span></td>
            <td>
                <input type="text" 
                       name="fc" 
                       value="<?php echo e(old('fc', $parametre->fc ?? '')); ?>" 
                       class="form-control" 
                       placeholder="  Pls/min" 
                       required>
            </td>
        </tr>
        <tr>
            <td><b>Gly :</b> </td>
            <td>
                <input type="text" 
                       name="glycemie" 
                       value="<?php echo e(old('glycemie', $parametre->glycemie ?? '')); ?>" 
                       class="form-control" 
                       placeholder="  g/l">
            </td>
        </tr>
        <tr>
            <td><b>SPO2 :</b><span class="text-danger">*</span></td>
            <td>
                <input type="text" 
                       name="spo2" 
                       value="<?php echo e(old('spo2', $parametre->spo2 ?? '')); ?>" 
                       class="form-control" 
                       placeholder="  %">
            </td>
        </tr>
        <tr>
            <td><b>Poids :</b> <span class="text-danger">*</span></td>
            <td>
                <input type="number" 
                       name="poids" 
                       value="<?php echo e(old('poids', $parametre->poids ?? '')); ?>" 
                       class="form-control col-md-5" 
                       placeholder="  Kgs" 
                       step="any" 
                       required>
            </td>
        </tr>
        <tr>
            <td><b>Taille (en metre):</b> <span class="text-danger">*</span></td>
            <td>
                <input type="number" 
                       name="taille" 
                       value="<?php echo e(old('taille', $parametre->taille ?? '')); ?>" 
                       class="form-control col-md-5" 
                       placeholder="0.00" 
                       step="any" 
                       required>
            </td>
        </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Ajouter au dossier</button>
</form><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/infirmiers/form/fiche_parametre_form.blade.php ENDPATH**/ ?>