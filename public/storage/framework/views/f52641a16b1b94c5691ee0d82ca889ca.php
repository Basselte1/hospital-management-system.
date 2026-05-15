<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
    <link href="<?php echo e(public_path('vendor/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" media="all"/>
    <style>
        body { font-size: 10px; font-family: DejaVu Sans, sans-serif; }
        .logo { width: 80px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1a3a6b; color: #fff; padding: 5px 6px; font-size: 10px; text-align: left; }
        td { padding: 4px 6px; border-bottom: 1px solid #ddd; font-size: 10px; vertical-align: middle; }
        tr:nth-child(even) td { background: #f5f8ff; }
        .tr-total td { background: #e8f0ff; font-weight: bold; border-top: 2px solid #1a3a6b; }
        hr { border: none; border-top: 2px solid #c0392b; margin: 6px 0; }

        /* ── Signatures ── */
        .signatures { width: 100%; margin-top: 20px; }
        .signatures td { text-align: center; border: none; font-size: 10px; padding-top: 30px; }
        .signatures .ligne { border-top: 1px solid #333; width: 120px; margin: 0 auto; }

        .footer {
            position: fixed;
            bottom: 5px;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
    </style>
</head>
<body>


<table style="width:100%; margin-bottom:8px;">
    <tr>
        <td style="width:15%; border:none; vertical-align:middle;">
            <img class="logo" src="<?php echo e(public_path('admin/images/logo.jpg')); ?>" alt="">
        </td>
        <td style="text-align:center; border:none; vertical-align:middle;">
            <strong style="font-size:11px;">CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</strong><br>
            VALLÉE MANGA BELL – DOUALA-BALI<br>
            TEL : (+237) 233 423 389 / 674 068 988 / 698 873 945<br>
            www.cmcu-cm.com
        </td>
    </tr>
</table>

<hr>

<h5 style="text-align:center; font-size:12px; margin:8px 0;">
    <u>FICHE DE SUIVI DES ENCAISSEMENTS JOURNALIERS – PATIENTS EXTERNES</u>
</h5>

<table>
    <thead>
        <tr>
            <th>CLIENT</th>
            <th>MONTANT</th>
            <th>MOTIF</th>
            <th>AVANCE</th>
            <th>RESTE</th>
            <th>PART PATIENT</th>
            <th>PART ASSURANCE</th>
            <th>DMH</th>
            <th>MÉDECIN</th>
            <th>DATE</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $factures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($facture->nom ?? ''); ?> <?php echo e($facture->prenom ?? ''); ?></td>
            <td><?php echo e(number_format($facture->montant ?? 0, 0, ',', ' ')); ?></td>
            <td><?php echo e($facture->motif ?? ''); ?></td>
            <td><?php echo e(number_format($facture->avance ?? 0, 0, ',', ' ')); ?></td>
            <td><?php echo e(number_format($facture->reste ?? 0, 0, ',', ' ')); ?></td>
            <td><?php echo e(number_format($facture->partpatient ?? 0, 0, ',', ' ')); ?></td>
            <td><?php echo e(number_format($facture->partassurance ?? 0, 0, ',', ' ')); ?></td>
            <td><?php echo e($facture->demarcheur ?? ''); ?></td>
            <td><?php echo e($facture->medecin_r ?? ''); ?></td>
            <td><?php echo e($facture->date_insertion ?? ''); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <tr class="tr-total">
            <td><strong>TOTAL (FCFA)</strong></td>
            <td><strong><?php echo e(number_format($totalPercu ?? 0, 0, ',', ' ')); ?></strong></td>
            <td></td>
            <td><strong><?php echo e(number_format($avances ?? 0, 0, ',', ' ')); ?></strong></td>
            <td><strong><?php echo e(number_format($restes ?? 0, 0, ',', ' ')); ?></strong></td>
            <td><strong><?php echo e(number_format($clients ?? 0, 0, ',', ' ')); ?></strong></td>
            <td><strong><?php echo e(number_format($assurances ?? 0, 0, ',', ' ')); ?></strong></td>
            <td></td><td></td><td></td>
        </tr>
    </tbody>
</table>


<table class="signatures">
    <tr>
        <td>
            <div class="ligne"></div>
            GESTIONNAIRE
        </td>
        <td>
            <div class="ligne"></div>
            COMPTABLE
        </td>
        <td>
            <div class="ligne"></div>
            ASSISTANTE
        </td>
    </tr>
</table>

<div class="footer">
    TEL : (+237) 233 423 389 / 674 068 988 / 698 873 945 &nbsp;|&nbsp; www.cmcu-cm.com
</div>

</body>
</html><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/etats/bilan_clientexterne.blade.php ENDPATH**/ ?>