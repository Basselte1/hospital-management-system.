<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Facture Consultation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
        }
        .container {
            border: 1px solid #000;
            padding: 12px;
            margin-bottom: 20px;
            position: relative; /* nécessaire pour le filigrane en position absolue */
            overflow: hidden;   /* évite que le filigrane déborde du cadre */
        }

        /* ─── Filigrane PROFORMA ─── */
        .proforma-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 18px;
            font-weight: bold;
            color: rgba(200, 30, 30, 0.13);
            white-space: nowrap;
            pointer-events: none;
            z-index: 999;
            letter-spacing: 8px;
            /*border: 2px solid rgba(200, 30, 30, 0.13);*/
            padding: 5px 5px;
            border-radius: 6px;
        }

        /* ─── Badge PROFORMA dans le titre ─── 
        .badge-proforma {
            display: inline-block;
            background-color: rgba(200, 30, 30, 0.12);
            color: #c00;
            border: 1px solid rgba(200, 30, 30, 0.4);
            border-radius: 4px;
            padding: 1px 6px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 2px;
            vertical-align: middle;
            margin-left: 4px;
        }*/
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        h6 { margin: 3px 0; font-size: 11px; }
        h5 { margin: 5px 0; font-size: 12px; }
        h4 { margin: 5px 0; font-size: 13px; }
        .bold { font-weight: bold; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }
        th, td {
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .invoice-id {
            color: #000;
            margin: 10px 0;
            font-size: 14px;
        }
        .notices {
            margin-top: 15px;
            padding-left: 6px;
        }
        footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #aaa;
            text-align: center;
            font-size: 10px;
            color: #333;
        }
        .logo {
            width: 40px;
            display: block;
            margin: 0 auto 8px;
        }
        @media print {
            body { font-size: 11px !important; }
        }
    </style>
</head>
<body>

        
<?php
    $devise         = $facture['devise']          ?? 'XAF';
    $montantDevise  = $facture['montant_devise']  ?? null;
    $tauxConversion = $facture['taux_conversion'] ?? null;
    $isPaiementDevise = ($devise !== 'XAF') && $montantDevise && $tauxConversion;

    /**
     * Calcul du rendu monnaie (informatif, jamais stocké).
     * remisFCFA = montant remis en FCFA
     * dettePatientFCFA = assurec (part patient) si assurance, sinon montant total
     */
    $dettePatientFCFA = !empty($facture['assurec']) && $facture['assurec'] > 0
        ? $facture['assurec']
        : $facture['montant'];

    $remisFCFA  = $isPaiementDevise ? ($montantDevise * $tauxConversion) : ($facture['avance'] ?? 0);
    $renduFCFA  = $isPaiementDevise ? max(0, $remisFCFA - $dettePatientFCFA) : 0;
?>
    <!-- BOTH COPIES ON SINGLE PAGE -->
    <div style="display: table; width: 100%;">
        <!-- FIRST COPY -->
        <div style="display: table-cell; width: 100%; padding: 2%; vertical-align: top;">
            <div class="container">

                
                <?php if($is_proforma ?? false): ?>
                    <div class="proforma-watermark">PROFORMA</div>
                <?php endif; ?>

                <div class="text-center">
                    <?php if(file_exists(public_path('admin/images/logo.jpg'))): ?>
                        <img class="logo" src="<?php echo e(public_path('admin/images/logo.jpg')); ?>" alt="">
                    <?php endif; ?>
                    <h6 class="bold">CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</h6>
                    <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
                    <h6>TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945</h6>
                    <h6>www.cmcu-cm.com</h6>
                </div>

                <div class="text-center">
                    <h6 class="invoice-id">
                        <?php if($is_proforma ?? false): ?>
                            FACTURE PROFORMA — <?php echo e(strtoupper($facture['details_motif'] ?? 'CONSULTATION')); ?>

                            <span class="badge-proforma">PROFORMA</span>
                        <?php else: ?>
                            RECU <?php echo e(strtoupper($facture['details_motif'] ?? 'CONSULTATION')); ?>

                        <?php endif; ?>
                        N° <?php echo e($patient['numero_dossier']); ?>

                    </h6>
                </div>

                <?php if(!empty($facture['assurancec'])): ?>
                    <h6 class="text-center">ASSURANCE: <?php echo e($facture['assurance'] ?? ''); ?></h6>
                <?php endif; ?>

                <?php if(!empty($patient['demarcheur'])): ?>
                    <h6 class="text-center"><?php echo e($patient['demarcheur']); ?></h6>
                <?php endif; ?>

                <?php if(!empty($facture['assurancec'])): ?>
                    <h6 class="text-center">
                        PART ASSURANCE: <?php echo e($facture['assurancec']); ?> |
                        PART PATIENT: <?php echo e($patient['assurec'] ?? 0); ?>

                    </h6>
                <?php endif; ?>

                <table>
                    <thead>
                        <tr>
                            <th class="text-left">NOM</th>
                            <th class="text-left">PRENOM</th>
                            <th class="text-left">MONTANT (FCFA)</th>
                            <th class="text-left">AVANCE</th>
                            <th class="text-left">RESTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left"><h5><?php echo e($patient['name']); ?></h5></td>
                            <td class="text-left"><h5><?php echo e($patient['prenom']); ?></h5></td>
                            <td class="text-left"><h4><?php echo e(number_format($facture['montant'], 0, ',', ' ')); ?></h4></td>
                            <td class="text-left"><h4><?php echo e(number_format($facture['avance'] ?? 0, 0, ',', ' ')); ?></h4></td>
                            <td class="text-left"><h4><?php echo e(number_format($facture['reste'] ?? 0, 0, ',', ' ')); ?></h4></td>
                        </tr>
                    </tbody>
                </table>

                <div class="notices">
                    <h6>LA CAISSE: <?php echo e(($printer['prenom'] ?? '') . ' ' . ($printer['name'] ?? '')); ?></h6>
                    <h6>Douala, <?php echo e(isset($patient['created_at']) ? \Carbon\Carbon::parse($patient['created_at'])->format('d/m/Y') : date('d/m/Y')); ?></h6>
                </div>

                <!--footer>
                    Centre Medico-chirurgical d'urologie situÃ© Ã  la VallÃ©e Douala Manga Bell Douala-Bali.<br>
                    TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945<br>
                    SITE WEB: http://www.cmcu-cm.com
                </footer-->
            </div>
            <footer style=" margin-top: 40px;">
                    Centre Medico-chirurgical d'urologie situe a  la Valle Douala Manga Bell Douala-Bali.<br>
                    TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945<br>
                    SITE WEB: http://www.cmcu-cm.com
                </footer> 
        </div>
                

        <!-- SECOND COPY -->
        <div style="display: table-cell; width: 100%; padding: 2%; vertical-align: top;">
            <div class="container">

                
                <?php if($is_proforma ?? false): ?>
                    <div class="proforma-watermark">PROFORMA</div>
                <?php endif; ?>

                <div class="text-center">
                    <?php if(file_exists(public_path('admin/images/logo.jpg'))): ?>
                        <img class="logo" src="<?php echo e(public_path('admin/images/logo.jpg')); ?>" alt="">
                    <?php endif; ?>
                    <h6 class="bold">CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</h6>
                    <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
                    <h6>TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945</h6>
                    <h6>www.cmcu-cm.com</h6>
                </div>

                <div class="text-center">
                    <h6 class="invoice-id">
                        <?php if($is_proforma ?? false): ?>
                            FACTURE PROFORMA — <?php echo e(strtoupper($facture['details_motif'] ?? 'CONSULTATION')); ?>

                            <span class="badge-proforma">PROFORMA</span>
                        <?php else: ?>
                            RECU <?php echo e(strtoupper($facture['details_motif'] ?? 'CONSULTATION')); ?>

                        <?php endif; ?>
                        N° <?php echo e($patient['numero_dossier']); ?>

                    </h6>
                </div>

                <?php if(!empty($facture['assurancec'])): ?>
                    <h6 class="text-center">ASSURANCE: <?php echo e($facture['assurance'] ?? ''); ?></h6>
                <?php endif; ?>

                <?php if(!empty($patient['demarcheur'])): ?>
                    <h6 class="text-center"><?php echo e($patient['demarcheur']); ?></h6>
                <?php endif; ?>

                <?php if(!empty($facture['assurancec'])): ?>
                    <h6 class="text-center">
                        PART ASSURANCE: <?php echo e($facture['assurancec']); ?> |
                        PART PATIENT: <?php echo e($patient['assurec'] ?? 0); ?>

                    </h6>
                <?php endif; ?>

                <table>
                    <thead>
                        <tr>
                            <th class="text-left">NOM</th>
                            <th class="text-left">PRENOM</th>
                            <th class="text-left">MONTANT (FCFA)</th>
                            <th class="text-left">AVANCE</th>
                            <th class="text-left">RESTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left"><h5><?php echo e($patient['name']); ?></h5></td>
                            <td class="text-left"><h5><?php echo e($patient['prenom']); ?></h5></td>
                            <td class="text-left"><h4><?php echo e(number_format($facture['montant'], 0, ',', ' ')); ?></h4></td>
                            <td class="text-left"><h4><?php echo e(number_format($facture['avance'] ?? 0, 0, ',', ' ')); ?></h4></td>
                            <td class="text-left"><h4><?php echo e(number_format($facture['reste'] ?? 0, 0, ',', ' ')); ?></h4></td>
                        </tr>
                    </tbody>
                </table>

                
                    <?php if($isPaiementDevise): ?>
                        <div class="devise-info">
                            <span class="devise-label">Paiement en devise :</span>
                            <?php echo e(number_format($montantDevise, 2, ',', ' ')); ?> <?php echo e($devise); ?>

                            × <?php echo e(number_format($tauxConversion, 2, ',', ' ')); ?>

                            = <?php echo e(number_format($montantDevise * $tauxConversion, 0, ',', ' ')); ?> FCFA
                            <?php if($renduFCFA > 0): ?>
                                &nbsp;|&nbsp;
                                <span class="devise-label">Rendu :</span>
                                <?php echo e(number_format($renduFCFA, 0, ',', ' ')); ?> FCFA
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <div class="notices">
                    <h6>LA CAISSE: <?php echo e(($printer['prenom'] ?? '') . ' ' . ($printer['name'] ?? '')); ?></h6>
                    <h6>Douala, <?php echo e(isset($patient['created_at']) ? \Carbon\Carbon::parse($patient['created_at'])->format('d/m/Y') : date('d/m/Y')); ?></h6>
                </div>

                <!--footer>
                    Centre Medico-chirurgical d'urologie situe a  la Valle Douala Manga Bell Douala-Bali.<br>
                    TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945<br>
                    SITE WEB: http://www.cmcu-cm.com
                </footer-->
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/etats/consultation.blade.php ENDPATH**/ ?>