<?php
    $horaires_selected = isset($prescription_medicale) 
        ? ($prescription_medicale->horaire ?? []) 
        : (old('horaire') ?? []);
    
    $all_horaires = ['00H', '02H', '04H', '06H', '08H', '10H', '12H', '14H', '16H', '18H', '20H', '22H'];
?>


<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label"><b>Médicament & Forme :</b> <span class="text-danger">*</span></label>
        <input type="text"
               name="medicament"
               class="form-control <?php $__errorArgs = ['medicament'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('medicament', (string) ($prescription_medicale->medicament ?? ''))); ?>"
               placeholder="Ex: Paracétamol 500mg comprimé"
               required>
        <?php $__errorArgs = ['medicament'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback"><?php echo e((string) $message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-6">
        <label class="form-label"><b>Posologie :</b> <span class="text-danger">*</span></label>
        <input type="text"
               name="posologie"
               class="form-control <?php $__errorArgs = ['posologie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('posologie', (string) ($prescription_medicale->posologie ?? ''))); ?>"
               placeholder="Ex: "
               required>
        <?php $__errorArgs = ['posologie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback"><?php echo e((string) $message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-8">
        <label class="form-label"><b>Horaire d'administration :</b> <span class="text-danger">*</span></label>
        <small class="text-muted d-block mb-2">Sélectionnez au moins un horaire</small>
        
        <div class="row">
            <?php $__currentLoopData = $all_horaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $horaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3 col-4 mb-2">
                <div class="form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="horaire_<?php echo e($horaire); ?>" 
                           name="horaire[]" 
                           value="<?php echo e($horaire); ?>"
                           <?php echo e(in_array($horaire, $horaires_selected) ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="horaire_<?php echo e($horaire); ?>">
                        <?php echo e($horaire); ?>

                    </label>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php $__errorArgs = ['horaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="text-danger small"><?php echo e((string) $message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-4">
        <label class="form-label"><b>Voie d'administration :</b> <span class="text-danger">*</span></label>
        <select name="voie" class="form-control <?php $__errorArgs = ['voie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
            <option value="">-- Sélectionner --</option>
            <option value="PO" <?php echo e(old('voie', (string) ($prescription_medicale->voie ?? '')) == 'PO' ? 'selected' : ''); ?>>
                PO (Per Os - Orale)
            </option>
            <option value="IV" <?php echo e(old('voie', (string) ($prescription_medicale->voie ?? '')) == 'IV' ? 'selected' : ''); ?>>
                IV (Intraveineuse)
            </option>
            <option value="IM" <?php echo e(old('voie', (string) ($prescription_medicale->voie ?? '')) == 'IM' ? 'selected' : ''); ?>>
                IM (Intramusculaire)
            </option>
            <option value="SC" <?php echo e(old('voie', (string) ($prescription_medicale->voie ?? '')) == 'SC' ? 'selected' : ''); ?>>
                SC (Sous-cutanée)
            </option>
            <option value="Rectale" <?php echo e(old('voie', (string) ($prescription_medicale->voie ?? '')) == 'Rectale' ? 'selected' : ''); ?>>
                Rectale
            </option>
            <option value="Cutanée" <?php echo e(old('voie', (string) ($prescription_medicale->voie ?? '')) == 'Cutanée' ? 'selected' : ''); ?>>
                Cutanée
            </option>
            <option value="Inhalation" <?php echo e(old('voie', (string) ($prescription_medicale->voie ?? '')) == 'Inhalation' ? 'selected' : ''); ?>>
                Inhalation
            </option>
            <option value="Autre" <?php echo e(old('voie', (string) ($prescription_medicale->voie ?? '')) == 'Autre' ? 'selected' : ''); ?>>
                Autre
            </option>
        </select>
        <?php $__errorArgs = ['voie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback"><?php echo e((string) $message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/infirmiers/form/_prescription_medicale_fields.blade.php ENDPATH**/ ?>