
<tbody id="editMotifMontform" style="display: none;">
    <form action="<?php echo e(route('patients.motif_montant.update', $patient->id)); ?>" method="POST">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        
        <?php
            $inputCls = 'tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-800 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10 tw-transition-colors';
            $labelCls = 'tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-whitespace-nowrap';
        ?>

        
        <tr>
            <td class="tw-px-3 tw-py-2.5 tw-align-middle <?php echo e($labelCls); ?>">
                Nom & prénom
            </td>
            <td class="tw-px-3 tw-py-2.5">
                <div class="tw-flex tw-gap-2">
                    <input name="name"   class="<?php echo e($inputCls); ?>" value="<?php echo e(old('name',   $patient->name)); ?>" type="text" placeholder="Nom">
                    <input name="prenom" class="<?php echo e($inputCls); ?>" value="<?php echo e(old('prenom', $patient->prenom)); ?>" type="text" placeholder="Prénom">
                </div>
            </td>
        </tr>

        
        <tr class="tw-bg-slate-50">
            <td class="tw-px-3 tw-py-2.5 tw-align-middle <?php echo e($labelCls); ?>">
                Médecin / Infirmier <span class="tw-text-red-500">*</span>
            </td>
            <td class="tw-px-3 tw-py-2.5">
                <?php
                    // Si medecin_r n’existe pas (ou vide), on prend infirmier.
                    // medecin_r peut parfois contenir soit un ID soit un texte.
                    $medecinValue = old('medecin_r');
                    if ($medecinValue === null || $medecinValue === '') {
                        $medecinValue = $patient->medecin_r ?? '';
                    }
                    if ($medecinValue === '' || $medecinValue === null) {
                        $medecinValue = $patient->infirmier ?? '';
                    }

                    // Calcul du texte attendu pour le selected (option value = "name prenom").
                    $medecinSelectedText = '';
                    if (is_numeric($medecinValue)) {
                        $u = \App\Models\User::select('name','prenom')->find((int) $medecinValue);
                        $medecinSelectedText = $u ? trim($u->name.' '.$u->prenom) : (string) $medecinValue;
                    } else {
                        $medecinSelectedText = (string) $medecinValue;
                    }
                ?>

                <select name="medecin_r" id="medecin_r" required class="<?php echo e($inputCls); ?>">
                    <option value="">— Sélectionner médecin / infirmier —</option>
                    <?php $__currentLoopData = $medecin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->name); ?> <?php echo e($user->prenom); ?>"
                            <?php echo e($medecinSelectedText === ($user->name.' '.$user->prenom) ? 'selected' : ''); ?>>
                            <?php echo e($user->name); ?> <?php echo e($user->prenom); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </td>
        </tr>

        
        <tr>
            <td class="tw-px-3 tw-py-2.5 tw-align-middle <?php echo e($labelCls); ?>">
                Motif <span class="tw-text-red-500">*</span>
            </td>
            <td class="tw-px-3 tw-py-2.5">
                <select name="motif" id="motif" class="<?php echo e($inputCls); ?>" onchange="new_ckChange(this)">
                    <option <?php echo e(old('motif', $patient->motif) == 'Consultation' ? 'selected' : ''); ?>>Consultation</option>
                    <option <?php echo e(old('motif', $patient->motif) == 'Acte'         ? 'selected' : ''); ?>>Acte</option>
                    <option <?php echo e(old('motif', $patient->motif) == 'Examen'       ? 'selected' : ''); ?>>Examen</option>
                    <option <?php echo e(old('motif', $patient->motif) == 'Autres'       ? 'selected' : ''); ?>>Autres</option>
                </select>
            </td>
        </tr>

        
        <tr class="tw-bg-slate-50">
            <td class="tw-px-3 tw-py-2.5 tw-align-middle">
                <label for="details_motif" id="label_details_motif" class="<?php echo e($labelCls); ?>">
                    Détails motif <span class="tw-text-red-500">*</span>
                </label>
            </td>
            <td class="tw-px-3 tw-py-2.5">
                <input name="details_motif" id="details_motif" class="<?php echo e($inputCls); ?>"
                       value="<?php echo e(old('details_motif', $patient->details_motif)); ?>"
                       type="text" placeholder="Précisez le motif">
            </td>
        </tr>

        
        <tr>
            <td class="tw-px-3 tw-py-2.5 tw-align-middle <?php echo e($labelCls); ?>">
                Montant <span class="tw-text-red-500">*</span>
            </td>
            <td class="tw-px-3 tw-py-2.5">
                <input name="montant" class="<?php echo e($inputCls); ?> tw-w-48"
                       value="<?php echo e(old('montant', $patient->montant)); ?>"
                       type="number" placeholder="0">
            </td>
        </tr>

        
        <tr class="tw-bg-slate-50">
            <td class="tw-px-3 tw-py-2.5 tw-align-middle <?php echo e($labelCls); ?>">Assurance</td>
            <td class="tw-px-3 tw-py-2.5">
                <input name="assurance" class="<?php echo e($inputCls); ?>"
                       value="<?php echo e(old('assurance', $patient->assurance)); ?>"
                       type="text" placeholder="Nom de l'assurance (si assuré)">
            </td>
        </tr>

        
        <tr>
            <td class="tw-px-3 tw-py-2.5 tw-align-middle <?php echo e($labelCls); ?>">
                Avance <span class="tw-text-red-500">*</span>
            </td>
            <td class="tw-px-3 tw-py-2.5">
                <input name="avance" class="<?php echo e($inputCls); ?> tw-w-48"
                       value="<?php echo e(old('avance', $patient->avance)); ?>"
                       type="number" placeholder="0">
            </td>
        </tr>

        
        <tr class="tw-bg-slate-50">
            <td class="tw-px-3 tw-py-2.5 tw-align-middle <?php echo e($labelCls); ?>">N° assurance</td>
            <td class="tw-px-3 tw-py-2.5">
                <input name="numero_assurance" class="<?php echo e($inputCls); ?>"
                       value="<?php echo e(old('numero_assurance', $patient->numero_assurance)); ?>"
                       type="text" placeholder="Numéro d'assurance (si assuré)">
            </td>
        </tr>

        
        <tr>
            <td class="tw-px-3 tw-py-2.5 tw-align-middle <?php echo e($labelCls); ?>">Taux prise en charge</td>
            <td class="tw-px-3 tw-py-2.5">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <select name="prise_en_charge" id="prise_en_charge" required class="<?php echo e($inputCls); ?> tw-w-28">
                        <?php $__currentLoopData = range(0, 100); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taux): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php echo e(old('prise_en_charge', $patient->prise_en_charge) == $taux ? 'selected' : ''); ?>><?php echo e($taux); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="tw-text-sm tw-font-semibold tw-text-slate-600">%</span>
                </div>
            </td>
        </tr>

        
        <tr class="tw-bg-slate-50">
            <td class="tw-px-3 tw-py-2.5 tw-align-middle <?php echo e($labelCls); ?>">Moyen de paiement</td>
            <td class="tw-px-3 tw-py-2.5">
                <select name="mode_paiement" id="mode_paiement" class="<?php echo e($inputCls); ?>">
                    <optgroup label="Monnaie électronique">
                        <option value="orange money"      <?php echo e(old('mode_paiement', $patient->mode_paiement) == 'orange money' ? 'selected' : ''); ?>>Orange Money</option>
                        <option value="mtn mobile money"  <?php echo e(old('mode_paiement', $patient->mode_paiement) == 'mtn mobile money' ? 'selected' : ''); ?>>MTN Mobile Money</option>
                    </optgroup>
                    <optgroup label="Autres moyens">
                        <option value="espèce"              <?php echo e(old('mode_paiement', $patient->mode_paiement) == 'espèce' ? 'selected' : ''); ?>>Espèce</option>
                        <option value="chèque"              <?php echo e(old('mode_paiement', $patient->mode_paiement) == 'chèque' ? 'selected' : ''); ?>>Chèque</option>
                        <option value="virement"            <?php echo e(old('mode_paiement', $patient->mode_paiement) == 'virement' ? 'selected' : ''); ?>>Virement</option>
                        <option value="bon de prise en charge" <?php echo e(old('mode_paiement', $patient->mode_paiement) == 'bon de prise en charge' ? 'selected' : ''); ?>>Bon de prise en charge</option>
                        <option value="autre"               <?php echo e(old('mode_paiement', $patient->mode_paiement) == 'autre' ? 'selected' : ''); ?>>Autre</option>
                    </optgroup>
                </select>
            </td>
        </tr>

        
        <tr>
            <td class="tw-px-3 tw-py-4" colspan="2">
                <button type="submit"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-shadow-sm tw-border-0 tw-cursor-pointer">
                    <i class="fas fa-check tw-text-xs"></i>
                    Enregistrer
                </button>
            </td>
        </tr>

    </form>
</tbody>

<?php $__env->startSection('script'); ?>
<script>
function new_ckChange(ckType) {
    var motif = document.getElementById('motif');
    var choix = motif[motif.selectedIndex].value;
    if (choix === 'Consultation') {
        document.getElementById('label_details_motif').innerHTML = 'Détail motif <span class="tw-text-red-500">*</span>';
        document.getElementById('details_motif').value = 'Consultation';
    } else {
        document.getElementById('details_motif').value = '';
    }
    if (choix === 'Acte' || choix === 'Examen') {
        document.getElementById('label_details_motif').innerHTML = 'Type ' + choix.toLowerCase() + ' <span class="tw-text-red-500">*</span>';
    }
    if (choix === 'Autres') {
        document.getElementById('label_details_motif').innerHTML = 'Détails motif <span class="tw-text-red-500">*</span>';
    }
}
</script>
<?php $__env->stopSection(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/partials/motif_et_montant.blade.php ENDPATH**/ ?>