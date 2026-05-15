
<tbody style="display: none;" id="myDIV">

    
    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-52 tw-whitespace-nowrap">
            Nom et Prénom
        </td>
        <td class="tw-px-3 tw-py-2.5 tw-font-semibold tw-text-slate-800 tw-text-sm">
            <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?>

        </td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-52 tw-whitespace-nowrap">
            Numéro de Dossier
        </td>
        <td class="tw-px-3 tw-py-2.5">
            <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-xs tw-font-bold tw-px-2.5 tw-py-0.5 tw-tracking-wide">
                <?php echo e($patient->numero_dossier); ?>

            </span>
        </td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">
            Frais de <?php echo e(strtoupper($patient->details_motif ?? 'Consultation')); ?>

        </td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-font-bold tw-text-[#1D4ED8]">
            <?php echo e(number_format($patient->montant, 0, ',', ' ')); ?>&nbsp;FCFA
        </td>
    </tr>

    
    <?php $__currentLoopData = $patient->dossiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dossier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Genre</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700"><?php echo e($dossier->sexe); ?></td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Profession</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700"><?php echo e($dossier->profession ?: '—'); ?></td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Adresse</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700"><?php echo e($dossier->adresse ?: '—'); ?></td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Portable</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-space-y-0.5">
            <div><?php echo e($dossier->portable_1 ?: '—'); ?></div>
            <?php if($dossier->portable_2): ?>
            <div class="tw-text-slate-500"><?php echo e($dossier->portable_2); ?></div>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Fax</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700"><?php echo e($dossier->fax ?: '—'); ?></td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Email</td>
        <td class="tw-px-3 tw-py-2.5">
            <?php if($dossier->email): ?>
            <a href="mailto:<?php echo e($dossier->email); ?>"
               class="tw-text-[#1D4ED8] hover:tw-underline tw-no-underline tw-text-sm">
                <?php echo e($dossier->email); ?>

            </a>
            <?php else: ?>
            <span class="tw-text-slate-400">—</span>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Lieu de naissance</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700"><?php echo e($dossier->lieu_naissance ?: '—'); ?></td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Date de naissance</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700"><?php echo e($dossier->date_naissance ?: '—'); ?></td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Personne à contacter</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700"><?php echo e($dossier->personne_contact ?: '—'); ?></td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Tél. personne à contacter</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700"><?php echo e($dossier->tel_personne_contact ?: '—'); ?></td>
    </tr>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tbody><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/partials/detail_patient.blade.php ENDPATH**/ ?>