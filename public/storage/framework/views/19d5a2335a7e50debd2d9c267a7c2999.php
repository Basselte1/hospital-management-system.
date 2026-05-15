<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Facture Client</title>
    <link href="<?php echo e(public_path('vendor/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" media="all"/>
    <style>
        /*
         * CORRECTION 1 : body font-size était à 3px → texte totalement invisible dans le PDF.
         * On passe à 11px, taille lisible standard pour un document imprimé.
         */
        body { font-size: 11px; font-family: DejaVu Sans, sans-serif; }

        .logo { width: 60px; }

        .entete { text-align: center; border-bottom: 2px solid #3989c6; margin-bottom: 10px; padding-bottom: 8px; }
        .entete h6 { margin: 2px 0; font-size: 11px; }

        /* ── Séparateur entre les 2 exemplaires ── */
        .separateur {
            border: none;
            border-top: 2px dashed #aaa;
            margin: 20px 0;
        }

        /* ── Bloc facture ── */
        .bloc-facture { padding: 10px 20px; }

        .invoice-id {
            text-align: center;
            color: #3989c6;
            font-size: 13px;
            margin-bottom: 6px;
        }

        /* ── Tableau montants ── */
        .table-montants {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .table-montants th {
            background: #3989c6;
            color: #fff;
            padding: 6px 8px;
            font-size: 11px;
            font-weight: bold;
            text-align: left;
        }
        .table-montants td {
            padding: 6px 8px;
            background: #eee;
            border-bottom: 1px solid #fff;
            font-size: 11px;
            vertical-align: middle;
        }
        .table-montants .valeur {
            font-size: 12px;
            font-weight: bold;
        }

        /* ── Ligne assurance ── */
        .assurance-row {
            display: table;
            width: 100%;
            margin: 6px 0;
            font-size: 11px;
        }
        .assurance-row .cell {
            display: table-cell;
            width: 50%;
            padding: 4px 8px;
            background: #e8f0ff;
            border: 1px solid #c8d4e8;
        }

        /* ── Caisse / date ── */
        .info-bas {
            margin-top: 8px;
            font-size: 10px;
            color: #444;
        }

        /* ── Pied ── */
        .pied {
            margin-top: 12px;
            border-top: 1px solid #aaa;
            padding-top: 4px;
            text-align: center;
            font-size: 9px;
            color: #777;
        }

        @media print {
            .separateur { page-break-after: always; }
        }
    </style>
</head>
<body>




<div class="bloc-facture">

    <div class="entete">
        <img class="logo" src="<?php echo e(public_path('admin/images/logo.jpg')); ?>" alt="">
        <h6><strong>CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</strong></h6>
        <h6>VALLÉE MANGA BELL – DOUALA-BALI</h6>
        <h6>TEL : (+237) 233 423 389 / 674 068 988 / 698 873 945</h6>
        <h6>www.cmcu-cm.com</h6>
    </div>

    <p class="invoice-id">REÇU – MOTIF : <?php echo e($facture->motif); ?></p>

    <?php if($facture->assurance): ?>
        
        <div class="assurance-row">
            <div class="cell"><strong>ASSURANCE :</strong> <?php echo e($facture->assurance); ?></div>
            <div class="cell"><strong>N° :</strong> <?php echo e($facture->numero_assurance ?? '—'); ?></div>
        </div>
        <div class="assurance-row">
            <div class="cell"><strong>PART PATIENT :</strong> <?php echo e(number_format($facture->partpatient, 0, ',', ' ')); ?> FCFA</div>
            <div class="cell"><strong>PART ASSURANCE :</strong> <?php echo e(number_format($facture->partassurance, 0, ',', ' ')); ?> FCFA</div>
        </div>
    <?php endif; ?>

    <?php if($facture->demarcheur): ?>
        <p style="text-align:center; font-size:10px; color:#555">Démarcheur : <?php echo e($facture->demarcheur); ?></p>
    <?php endif; ?>

    <table class="table-montants">
        <thead>
            <tr>
                <th>NOM</th>
                <th>PRÉNOM</th>
                <th>MONTANT (FCFA)</th>
                <th>AVANCE</th>
                <th>RESTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="valeur"><?php echo e($facture->nom); ?></td>
                <td class="valeur"><?php echo e($facture->prenom); ?></td>
                <td class="valeur"><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?></td>
                <td class="valeur"><?php echo e(number_format($facture->avance ?? 0, 0, ',', ' ')); ?></td>
                <td class="valeur"><?php echo e(number_format($facture->reste ?? 0, 0, ',', ' ')); ?></td>
            </tr>
            
            <tr>
                <td colspan="3" class="info-bas">
                    
                    LA CAISSE : <?php echo e($facture->user?->prenom); ?> <?php echo e($facture->user?->name); ?>

                </td>
                <td colspan="2" class="info-bas">
                    Douala, <?php echo e(\Carbon\Carbon::parse($facture->date_insertion)->format('d/m/Y')); ?>

                </td>
            </tr>
        </tbody>
    </table>

    <div class="pied">
        Centre Médico-Chirurgical d'Urologie – Vallée Manga Bell, Douala-Bali.
        TEL : (+237) 233 423 389 / 674 068 988 / 698 873 945 – www.cmcu-cm.com
    </div>
</div>


<hr class="separateur">


<div class="bloc-facture">

    <div class="entete">
        <img class="logo" src="<?php echo e(public_path('admin/images/logo.jpg')); ?>" alt="">
        <h6><strong>CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</strong></h6>
        <h6>VALLÉE MANGA BELL – DOUALA-BALI</h6>
        <h6>TEL : (+237) 233 423 389 / 674 068 988 / 698 873 945</h6>
        <h6>www.cmcu-cm.com</h6>
    </div>

    <p class="invoice-id">SOUCHE – MOTIF : <?php echo e($facture->motif); ?></p>

    <?php if($facture->assurance): ?>
        <div class="assurance-row">
            <div class="cell"><strong>ASSURANCE :</strong> <?php echo e($facture->assurance); ?></div>
            <div class="cell"><strong>N° :</strong> <?php echo e($facture->numero_assurance ?? '—'); ?></div>
        </div>
        <div class="assurance-row">
            <div class="cell"><strong>PART PATIENT :</strong> <?php echo e(number_format($facture->partpatient, 0, ',', ' ')); ?> FCFA</div>
            <div class="cell"><strong>PART ASSURANCE :</strong> <?php echo e(number_format($facture->partassurance, 0, ',', ' ')); ?> FCFA</div>
        </div>
    <?php endif; ?>

    <?php if($facture->demarcheur): ?>
        <p style="text-align:center; font-size:10px; color:#555">Démarcheur : <?php echo e($facture->demarcheur); ?></p>
    <?php endif; ?>

    <table class="table-montants">
        <thead>
            <tr>
                <th>NOM</th>
                <th>PRÉNOM</th>
                <th>MONTANT (FCFA)</th>
                <th>AVANCE</th>
                <th>RESTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="valeur"><?php echo e($facture->nom); ?></td>
                <td class="valeur"><?php echo e($facture->prenom); ?></td>
                <td class="valeur"><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?></td>
                <td class="valeur"><?php echo e(number_format($facture->avance ?? 0, 0, ',', ' ')); ?></td>
                <td class="valeur"><?php echo e(number_format($facture->reste ?? 0, 0, ',', ' ')); ?></td>
            </tr>
            <tr>
                <td colspan="3" class="info-bas">
                    LA CAISSE : <?php echo e($facture->user?->prenom); ?> <?php echo e($facture->user?->name); ?>

                </td>
                <td colspan="2" class="info-bas">
                    Douala, <?php echo e(\Carbon\Carbon::parse($facture->date_insertion)->format('d/m/Y')); ?>

                </td>
            </tr>
        </tbody>
    </table>

    <div class="pied">
        Centre Médico-Chirurgical d'Urologie – Vallée Manga Bell, Douala-Bali.
        TEL : (+237) 233 423 389 / 674 068 988 / 698 873 945 – www.cmcu-cm.com
    </div>
</div>

</body>
</html><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/etats/clientP.blade.php ENDPATH**/ ?>