<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0066cc;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #0066cc;
        }
        .info-row {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
            border-top: 2px solid #0066cc;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background-color: white;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0066cc;
            color: white;
        }
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .alert-info {
            background-color: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">CMCU</h1>
        <p style="margin: 5px 0 0 0;">Centre Médico-Chirurgical d'Urgence</p>
    </div>

    <div class="content">
        <h2 style="color: #0066cc;">Bon de Commande N° <?php echo e($bonCommande->numero_bon); ?></h2>
        
        <p>Bonjour,</p>
        
        <p>Nous vous adressons notre bon de commande <strong><?php echo e($bonCommande->numero_bon); ?></strong>.</p>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #0066cc;">Détails de la Commande</h3>
            
            <div class="info-row">
                <span class="label">Date de Commande:</span>
                <?php echo e($bonCommande->date_commande->format('d/m/Y')); ?>

            </div>

            <?php if($bonCommande->date_livraison_souhaitee): ?>
            <div class="info-row">
                <span class="label">Date de Livraison Souhaitée:</span>
                <?php echo e($bonCommande->date_livraison_souhaitee->format('d/m/Y')); ?>

            </div>
            <?php endif; ?>

            <div class="info-row">
                <span class="label">Montant Total:</span>
                <strong style="color: #0066cc; font-size: 18px;">
                    <?php echo e(number_format($bonCommande->montant_total, 0, ',', ' ')); ?> FCFA
                </strong>
            </div>
        </div>

        <h3 style="color: #0066cc;">Résumé des Articles</h3>
        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th style="text-align: center;">Quantité</th>
                    <th style="text-align: right;">Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $bonCommande->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->designation); ?></td>
                    <td style="text-align: center;"><?php echo e($item->quantite_commandee); ?></td>
                    <td style="text-align: right;"><?php echo e(number_format($item->montant_ligne, 0, ',', ' ')); ?> FCFA</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">TOTAL:</td>
                    <td style="text-align: right;"><?php echo e(number_format($bonCommande->montant_total, 0, ',', ' ')); ?> FCFA</td>
                </tr>
            </tbody>
        </table>

        <?php if($bonCommande->notes): ?>
        <div style="background-color: #fffbcc; border-left: 4px solid #ffcc00; padding: 15px; margin: 20px 0;">
            <strong>Remarques:</strong><br>
            <?php echo e($bonCommande->notes); ?>

        </div>
        <?php endif; ?>

        <div class="alert-info">
            <strong>Note:</strong> Pour obtenir une version PDF officielle de ce bon de commande, 
            veuillez nous contacter ou consulter notre système en ligne.
        </div>

        <p>
            Nous vous prions de bien vouloir nous confirmer la réception de cette commande et de nous communiquer 
            les délais de livraison.
        </p>

        <p>
            Pour toute question, n'hésitez pas à nous contacter.
        </p>

        <div style="margin: 30px 0;">
            <p style="margin: 0;">Cordialement,</p>
            <p style="margin: 5px 0; font-weight: bold;"><?php echo e($bonCommande->createdBy->name ?? 'Service Logistique'); ?></p>
            <p style="margin: 5px 0; color: #666;">CMCU - Centre Médico-Chirurgical d'Urgence</p>
        </div>
    </div>

    <div class="footer">
        <p>
            <strong>CMCU - Centre Médico-Chirurgical d'Urgence</strong><br>
            Douala, Cameroun<br>
            Tél: +237 698873945/ +237 674068988 | Email: cmcu_@yahoo.fr
        </p>
        <p style="font-size: 11px; color: #999;">
            Cet email a été généré automatiquement, merci de ne pas y répondre directement.
        </p>
    </div>
</body>
</html><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/bon_commandes/email.blade.php ENDPATH**/ ?>