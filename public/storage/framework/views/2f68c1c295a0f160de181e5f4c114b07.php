<tbody style="display: none;" id="myDIV">
<tr>
    <td>
        <b>NOM ET PRENOM DU PATIENT :</b>
    </td>
    <td><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></td>
</tr>
<tr>
    <td>
        <b>NUMERO DE DOSSIER :</b>
    </td>
    <td><?php echo e($patient->numero_dossier); ?></td>
</tr>
<tr>
    <td>
        <b>FRAIS DE <?php echo e(strtoupper($patient->details_motif) ?? 'CONSULTATION'); ?> :</b>
    </td>
    <td><?php echo e($patient->montant); ?> FCFA</td>
</tr>
<?php $__currentLoopData = $patient->dossiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dossier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <tr>
        <td>
            <b>GENRE :</b>
        </td>
        <td><?php echo e($dossier->sexe); ?></td>
    </tr>
    <tr>
        <td>
            <b>PROFESSION :</b>
        </td>
        <td><?php echo e($dossier->profession); ?></td>
    </tr>
    <tr>
        <td>
            <b>ADRESSE :</b>
        </td>
        <td><?php echo e($dossier->adresse); ?></td>
    </tr>
    <tr>
        <td>
            <b>PORTABLE :</b>
        </td>
        <td>
            <?php echo e($dossier->portable_1); ?><br>
            <?php echo e($dossier->portable_2); ?>

        </td>
    </tr>
    <tr>
        <td>
            <b>FAX :</b>
        </td>
        <td><?php echo e($dossier->fax); ?></td>
    </tr>
    <tr>
        <td>
            <b>EMAIL :</b>
        </td>
        <td><?php echo e($dossier->email); ?></td>
    </tr>
    <tr>
        <td>
            <b>LIEU DE NAISSANCE :</b>
        </td>
        <td><?php echo e($dossier->lieu_naissance); ?></td>
    </tr>
    <tr>
        <td>
            <b>DATE DE NAISSANCE :</b>
        </td>
        <td><?php echo e($dossier->date_naissance); ?></td>
    </tr>
    <!-- <tr>
        <td>
            <b>PERSONNE DE CONFIANCE :</b>
        </td>
        <td><?php echo e($dossier->personne_confiance); ?></td>
    </tr>
    <tr>
        <td>
            <b>TELEPHONE PERSONNE DE CONFIANCE :</b>
        </td>
        <td><?php echo e($dossier->tel_personne_confiance); ?></td>
    </tr> -->
    <tr>
        <td>
            <b>PERSONNE A CONTACTER :</b>
        </td>
        <td><?php echo e($dossier->personne_contact); ?></td>
    </tr>
    <tr>
        <td>
            <b>TELEPHONE PERSONNE A CONTACTER :</b>
        </td>
        <td><?php echo e($dossier->tel_personne_contact); ?></td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/consultations/partials/detail_patient.blade.php ENDPATH**/ ?>