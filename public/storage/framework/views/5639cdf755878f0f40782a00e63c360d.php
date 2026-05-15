<?php if($compteRenduBlocOperatoire->id): ?>
    <form method="POST" action="<?php echo e(route('compte_rendu_bloc.update', $compteRenduBlocOperatoire->id)); ?>" class="form-horizontal form-label-left">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
<?php else: ?>
    <form method="POST" action="<?php echo e(route('compte_rendu_bloc.store')); ?>" class="form-horizontal form-label-left">
        <?php echo csrf_field(); ?>
<?php endif; ?>

    <tr>
        <td>
            <h5 class="text-primary"><strong>ENTRE / SORTIE PATIENT</strong></h5>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>
            <b>Date d'entré :</b>
            <span class="text-danger">*</span>
        </td>
        <td>
            <b>Type :</b>
            <span class="text-danger">*</span>
        </td>
    </tr>
    <tr>
        <td>
            <input type="date" name="date_e" value="<?php echo e(old('date_e', $compteRenduBlocOperatoire->date_e ?? '')); ?>" class="form-control" required>
        </td>
        <td>
            <select name="type_e" class="form-control col-md-6" required>
                <option value="">Motif d'entrée</option>
                <?php $__currentLoopData = ['Urgence', 'Hospitalisation', 'Ambulatoire']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type); ?>" <?php echo e(old('type_e', $compteRenduBlocOperatoire->type_e ?? '') == $type ? 'selected' : ''); ?>><?php echo e($type); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <b>Date de sortie :</b>
            <span class="text-danger">*</span>
        </td>
        <td>
            <b>Type :</b>
            <span class="text-danger">*</span>
        </td>
    </tr>
    <tr>
        <td>
            <input type="date" name="date_s" value="<?php echo e(old('date_s', $compteRenduBlocOperatoire->date_s ?? '')); ?>" class="form-control" required>
        </td>
        <td>
            <select name="type_s" class="form-control col-md-6" required>
                <option value="">Motif de sortie</option>
                <?php $__currentLoopData = ['Retour au domicile', 'Transfert', 'Convalescence', 'Décédé']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type); ?>" <?php echo e(old('type_s', $compteRenduBlocOperatoire->type_s ?? '') == $type ? 'selected' : ''); ?>><?php echo e($type); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <h5 class="text-primary"><strong>EQUIPE MEDICALE</strong></h5>
        </td>
        <td></td>
    </tr>
    <tr>
        <td><b>Nom du chirurgien :</b> <span class="text-danger">*</span></td>
        <td>
            <select class="form-control col-md-6" name="chirurgien" id="chirurgien" required>
                <option value="">Nom du chirurgien</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->name); ?> <?php echo e($user->prenom); ?>" <?php echo e(old('chirurgien', $compteRenduBlocOperatoire->chirurgien ?? '') == ($user->name . ' ' . $user->prenom) ? 'selected' : ''); ?>>
                        <?php echo e($user->name); ?> <?php echo e($user->prenom); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><b>Aide opératoire :</b> <span class="text-danger">*</span></td>
        <td>
            <select class="form-control col-md-6" name="aide_op" id="aide_op" required>
                <option value="">Nom de l'aide opératoire</option>
                <option value="Aucun" <?php echo e(old('aide_op', $compteRenduBlocOperatoire->aide_op ?? '') == 'Aucun' ? 'selected' : ''); ?>>Aucun</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->name); ?> <?php echo e($user->prenom); ?>" <?php echo e(old('aide_op', $compteRenduBlocOperatoire->aide_op ?? '') == ($user->name . ' ' . $user->prenom) ? 'selected' : ''); ?>>
                        <?php echo e($user->name); ?> <?php echo e($user->prenom); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><b>Anesthésiste :</b> <span class="text-danger">*</span></td>
        <td>
            <select class="form-control col-md-6" name="anesthesiste" id="anesthesiste" required>
                <option value="">Nom de l'anesthésiste</option>
                <?php $__currentLoopData = $anesthesistes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anesthesiste): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($anesthesiste->name); ?> <?php echo e($anesthesiste->prenom ?? ''); ?>" <?php echo e(old('anesthesiste', $compteRenduBlocOperatoire->anesthesiste ?? '') == ($anesthesiste->name . ' ' . ($anesthesiste->prenom ?? '')) ? 'selected' : ''); ?>>
                        <?php echo e($anesthesiste->name); ?> <?php echo e($anesthesiste->prenom ?? ''); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><b>Infirmier anesthésiste :</b> <span class="text-danger">*</span></td>  
        <td>
            <input class="form-control" type="text" name="infirmier_anesthesiste" value="<?php echo e(old('infirmier_anesthesiste', $compteRenduBlocOperatoire->infirmier_anesthesiste ?? '')); ?>" placeholder="Nom de l'infirmier anesthésiste" required>
        </td>
    </tr>
    <tr>
        <td>
            <h5 class="text-primary"><strong>DETAILS OPERATIONS</strong></h5>
        </td>
        <td></td>
    </tr>
    <tr>
        <td><b>Titre de l'intervention</b> <span class="text-danger">*</span></td>
        <td><input type="text" name="titre_intervention" value="<?php echo e(old('titre_intervention', $compteRenduBlocOperatoire->titre_intervention ?? '')); ?>" class="form-control" placeholder="Titre de l'intervention" required></td>
    </tr>
    <tr>
        <td><b>Type d'intervention</b> <span class="text-danger">*</span></td>
        <td><input type="text" name="type_intervention" value="<?php echo e(old('type_intervention', $compteRenduBlocOperatoire->type_intervention ?? '')); ?>" class="form-control" placeholder="Type d'intervention" required></td>
    </tr>
    <tr>
        <td><b>Date de l'intervention :</b> <span class="text-danger">*</span></td>
        <td><input type="date" name="date_intervention" value="<?php echo e(old('date_intervention', $compteRenduBlocOperatoire->date_intervention ?? '')); ?>" class="form-control col-md-5" required></td>
    </tr>
    <tr>
        <td><b>Durée de l'intervention :</b> <span class="text-danger">*</span></td>
        <td><input type="time" name="dure_intervention" value="<?php echo e(old('dure_intervention', $compteRenduBlocOperatoire->dure_intervention ?? '')); ?>" class="form-control col-md-5" required></td>
    </tr>
    <tr>
        <td><b>Indications opératoires :</b> <span class="text-danger">*</span></td>
        <td><textarea name="indication_operatoire" class="form-control splitLines" rows="4" required><?php echo e(old('indication_operatoire', $compteRenduBlocOperatoire->indication_operatoire ?? '')); ?></textarea></td>
    </tr>
    <tr>
        <td><b>Compte-rendu opératoire :</b> <span class="text-danger">*</span></td>
        <td><textarea name="compte_rendu_o" class="form-control splitLines" rows="4" required><?php echo e(old('compte_rendu_o', $compteRenduBlocOperatoire->compte_rendu_o ?? '')); ?></textarea></td>
    </tr>
    <tr>
        <td><b>Résultats histo-pathologiques :</b></td>
        <td><textarea name="resultat_histo" class="form-control splitLines" rows="4"><?php echo e(old('resultat_histo', $compteRenduBlocOperatoire->resultat_histo ?? '')); ?></textarea></td>
    </tr>
    <tr>
        <td><b>Suites opératoires:</b> <span class="text-danger">*</span></td>
        <td><textarea name="suite_operatoire" class="form-control splitLines" rows="4" required><?php echo e(old('suite_operatoire', $compteRenduBlocOperatoire->suite_operatoire ?? '')); ?></textarea></td>
    </tr>
    <tr>
        <td><b>Traitement proposé :</b></td>
        <td><textarea name="traitement_propose" class="form-control splitLines" rows="4"><?php echo e(old('traitement_propose', $compteRenduBlocOperatoire->traitement_propose ?? '')); ?></textarea></td>
    </tr>
    <tr>
        <td><b>Soins et examens à réaliser :</b></td>
        <td><textarea name="soins" class="form-control splitLines" rows="4"><?php echo e(old('soins', $compteRenduBlocOperatoire->soins ?? '')); ?></textarea></td>
    </tr>
    <tr>
        <td><b>Proposition de suivi :</b></td>
        <td><textarea name="proposition_suivi" class="form-control splitLines" rows="3"><?php echo e(old('proposition_suivi', $compteRenduBlocOperatoire->proposition_suivi ?? '')); ?></textarea></td>
    </tr>
    <tr>
        <td><b>Conclusions :</b> <span class="text-danger">*</span></td>
        <td><textarea name="conclusion" class="form-control splitLines" rows="4" required><?php echo e(old('conclusion', $compteRenduBlocOperatoire->conclusion ?? '')); ?></textarea></td>
    </tr>
    <tr>
        <td><input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>"></td>
        <td></td>
    </tr>
    <tr>
        <td>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </td>
        <td></td>
    </tr>
</form><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/consultations/chirurgiens/form/compte_rendu_operatoire_form.blade.php ENDPATH**/ ?>