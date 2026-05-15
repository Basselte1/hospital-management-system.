<link href="<?php echo e(public_path('vendor/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" media="all" />

<?php
    \Carbon\Carbon::setLocale('fr');
    $printDate = \Carbon\Carbon::now()->translatedFormat('d F Y');
    $bilanDate = \Carbon\Carbon::parse($date)->translatedFormat('d F Y');
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 11px;
        color: #222;
        background: #fff;
    }
    h3  { font-size: 16px; font-weight: 700; text-transform: uppercase; margin: 0; }
    h5  { font-size: 12px; font-weight: 600; color: #1a5276; }
    .header-band {
        background-color: #1a5276;
        color: #fff;
        padding: 8px 14px;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 14px;
    }
    thead th {
        background-color: #1a5276;
        color: #fff;
        padding: 6px 8px;
        text-align: center;
        font-size: 10px;
        text-transform: uppercase;
    }
    tbody td {
        padding: 5px 7px;
        border: 1px solid #ccc;
        vertical-align: top;
    }
    tbody tr:nth-child(even) { background: #eaf2fb; }
    tfoot td {
        background-color: #1a5276;
        color: #fff;
        font-weight: bold;
        padding: 5px 7px;
        font-size: 11px;
    }
    .badge-soldee     { background: #1e8449; color: #fff; padding: 2px 6px; border-radius: 3px; }
    .badge-nonsoldee  { background: #d68910; color: #fff; padding: 2px 6px; border-radius: 3px; }
    .mode-block       { margin-top: 10px; padding: 8px 12px; border: 1px solid #1a5276; border-radius: 4px; }
    .mode-block td    { border: none; padding: 2px 8px; }
    .cmcu-footer {
        position: fixed; bottom: 0; left: 0; right: 0;
        background-color: #1a5276; color: #fff;
        text-align: center; font-size: 7px; padding: 4px 10px; line-height: 1.5;
    }
    ul.examen-list { margin: 0; padding-left: 14px; }
    ul.examen-list li { margin-bottom: 2px; }
</style>


<table width="100%" style="border-collapse:collapse; margin-bottom:8px;">
    <tr>
        <td width="15%" style="vertical-align:middle; padding-right:8px;">
            <img src="<?php echo e(public_path('admin/images/logo.jpg')); ?>" style="width:70px;height:auto;" alt="Logo">
        </td>
        <td width="85%" style="text-align:center; vertical-align:middle;">
            <div style="font-weight:bold; font-size:13px; color:#1a5276; text-transform:uppercase; letter-spacing:.5px;">
                CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE
            </div>
            <div style="font-size:8px; margin-top:1px;">ONMC : N°5531 — Tél : +237 233 42 33 89 / +237 698 87 39 45</div>
            <div style="font-size:8px;">Vallée Manga Bell Douala-Bali — cmcu_cmcu@yahoo.fr</div>
        </td>
    </tr>
</table>
<div style="border-top:3px solid #1a5276; margin:0 0 10px 0;"></div>


<div class="header-band" style="text-align:center;">
    <h3>Bilan Journalier des Examens de Laboratoire</h3>
    <div style="font-size:11px; margin-top:3px;">
        Date : <strong><?php echo e($bilanDate); ?></strong>
        &nbsp;|&nbsp; Imprimé le : <?php echo e($printDate); ?>

    </div>
</div>


<table>
    <thead>
        <tr>
            <th style="width:7%">N° Fact.</th>
            <th style="width:15%">Patient</th>
            <th style="width:25%">Examens réalisés</th>
            <th style="width:9%">Montant</th>
            <th style="width:9%">Part Ass.</th>
            <th style="width:9%">Part Patient</th>
            <th style="width:8%">Perçu</th>
            <th style="width:8%">Reste</th>
            <th style="width:10%">Mode paiement</th>
            <th style="width:10%">Médecin</th>
            <th style="width:6%">Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $tFactures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td style="text-align:center; font-weight:600;"><?php echo e($f->numero); ?></td>
            <td><?php echo e($f->name); ?></td>
            <td>
                <?php if(!empty($f->examens)): ?>
                    <ul class="examen-list">
                        <?php $__currentLoopData = $f->examens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <?php echo e($ex['libelle'] ?? '—'); ?>

                                <?php if(!empty($ex['montant'])): ?>
                                    <span style="color:#555;">(<?php echo e(number_format($ex['montant'],0,',',' ')); ?> F)</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <span style="color:#999;">—</span>
                <?php endif; ?>
            </td>
            <td style="text-align:right;"><?php echo e(number_format($f->montant,0,',',' ')); ?></td>
            <td style="text-align:right;"><?php echo e(number_format($f->partAssurance,0,',',' ')); ?></td>
            <td style="text-align:right;"><?php echo e(number_format($f->partPatient,0,',',' ')); ?></td>
            <td style="text-align:right; color:#1e8449; font-weight:600;">
                <?php echo e(number_format($f->percu,0,',',' ')); ?>

            </td>
            <td style="text-align:right; color:<?php echo e($f->reste > 0 ? '#c0392b' : '#1e8449'); ?>; font-weight:600;">
                <?php echo e(number_format($f->reste,0,',',' ')); ?>

            </td>
            <td style="text-align:center;"><?php echo e($f->demarcheur ?? $f->medecin); ?></td>
            <td><?php echo e($f->medecin); ?></td>
            <td style="text-align:center;">
                <?php if(strtolower($f->statut) === 'soldée'): ?>
                    <span class="badge-soldee">Soldée</span>
                <?php else: ?>
                    <span class="badge-nonsoldee">En cours</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="11" style="text-align:center; color:#999; padding:20px;">
                Aucune facture d'examen pour cette date.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;">TOTAUX :</td>
            <td style="text-align:right;"><?php echo e(number_format($totalMontant,0,',',' ')); ?> F</td>
            <td style="text-align:right;"><?php echo e(number_format($totalPartAssurance,0,',',' ')); ?> F</td>
            <td style="text-align:right;"><?php echo e(number_format($totalPartPatient,0,',',' ')); ?> F</td>
            <td style="text-align:right;"><?php echo e(number_format($totalPercu,0,',',' ')); ?> F</td>
            <td style="text-align:right;"><?php echo e(number_format($totalReste,0,',',' ')); ?> F</td>
            <td colspan="3"></td>
        </tr>
    </tfoot>
</table>


<div class="mode-block">
    <h5><i>Détail des encaissements par mode de paiement</i></h5>
    <table style="margin-bottom:0; width:auto;">
        <tbody>
            <?php $__currentLoopData = $mode_paiement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="padding:2px 12px; border:none; text-transform:capitalize; font-weight:600;">
                    <?php echo e($mode->name); ?>

                </td>
                <td style="padding:2px 12px; border:none; text-align:right;">
                    <?php echo e(number_format($mode->val, 0, ',', ' ')); ?> FCFA
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr style="border-top:1px solid #1a5276;">
                <td style="padding:3px 12px; border:none; font-weight:700; color:#1a5276;">TOTAL ENCAISSÉ</td>
                <td style="padding:3px 12px; border:none; font-weight:700; color:#1a5276; text-align:right;">
                    <?php echo e(number_format($totalPercu, 0, ',', ' ')); ?> FCFA
                </td>
            </tr>
        </tbody>
    </table>
</div>


<div class="cmcu-footer">
    Urgences Urologiques — Cancérologie — Centre de la Prostate — Coelioscopie — Calcul Urinaire<br>
    Explorations Endoscopiques — Échographie — Débimétrie — Biopsies de la Prostate — Médecine Générale
</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/etats/bilan_examen.blade.php ENDPATH**/ ?>