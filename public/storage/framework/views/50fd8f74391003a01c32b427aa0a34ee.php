<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu <?php echo e($vente->numero_vente); ?></title>
    <style>
        @page { margin: 20px; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #28a745;
            padding-bottom: 15px;
        }
        .hospital-info {
            font-size: 14pt;
            font-weight: bold;
        }
        .receipt-title {
            font-size: 20pt;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            background-color: #28a745;
            color: white;
            padding: 10px;
        }
        .paid-stamp {
            position: absolute;
            top: 100px;
            right: 50px;
            transform: rotate(-15deg);
            border: 5px solid #28a745;
            color: #28a745;
            font-size: 30pt;
            font-weight: bold;
            padding: 10px 20px;
            opacity: 0.5;
        }
        .info-section {
            margin: 15px 0;
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #28a745;
        }
        .info-row {
            margin: 8px 0;
            display: flex;
            justify-content: space-between;
        }
        .info-label {
            font-weight: bold;
            width: 40%;
        }
        .info-value {
            width: 60%;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #28a745;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-box {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 5px;
        }
        .payment-info {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="paid-stamp">PAYÉ</div>
    
    <div class="header">
        <div class="hospital-info">
            CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE<br>
            CMCU
        </div>
        <div style="font-size: 9pt; margin-top: 10px;">
            Pharmacie • Adresse • Téléphone • Email
        </div>
    </div>

    <div class="receipt-title">
        REÇU DE PAIEMENT
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">N° Reçu:</span>
            <span class="info-value"><strong><?php echo e($vente->numero_vente); ?></strong></span>
        </div>
        <div class="info-row">
            <span class="info-label">Date de Vente:</span>
            <span class="info-value"><?php echo e($vente->created_at->format('d/m/Y H:i')); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Date de Paiement:</span>
            <span class="info-value"><strong><?php echo e($vente->date_paiement->format('d/m/Y H:i')); ?></strong></span>
        </div>
    </div>

    <div class="info-section">
        <h3 style="margin-top: 0; color: #28a745;">CLIENT</h3>
        <?php if($vente->isPatientSale()): ?>
            <div class="info-row">
                <span class="info-label">Nom:</span>
                <span class="info-value"><strong><?php echo e($vente->patient->name); ?> <?php echo e($vente->patient->prenom); ?></strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Dossier N°:</span>
                <span class="info-value"><?php echo e($vente->patient->numero_dossier); ?></span>
            </div>
            <?php if($vente->ordonance): ?>
            <div class="info-row">
                <span class="info-label">Ordonnance:</span>
                <span class="info-value"><?php echo e($vente->ordonance->created_at->format('d/m/Y')); ?></span>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="info-row">
                <span class="info-label">Nom:</span>
                <span class="info-value"><strong><?php echo e($vente->client->nom); ?> <?php echo e($vente->client->prenom); ?></strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Institution:</span>
                <span class="info-value"><?php echo e($vente->client->motif); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <h3 style="margin-top: 30px;">DÉTAIL DE L'ACHAT</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 50%;">PRODUIT</th>
                <th class="text-center" style="width: 15%;">QTÉ</th>
                <th class="text-right" style="width: 17.5%;">PRIX UNIT.</th>
                <th class="text-right" style="width: 17.5%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $vente->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($item->designation); ?></td>
                <td class="text-center"><?php echo e($item->quantite); ?></td>
                <td class="text-right"><?php echo e(number_format($item->prix_unitaire, 0, ',', ' ')); ?> FCFA</td>
                <td class="text-right"><?php echo e(number_format($item->montant_ligne, 0, ',', ' ')); ?> FCFA</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="total-box">
        <div style="font-size: 14pt;">MONTANT TOTAL PAYÉ</div>
        <div style="font-size: 24pt; margin-top: 10px;">
            <strong><?php echo e(number_format($vente->montant_paye, 0, ',', ' ')); ?> FCFA</strong>
        </div>
    </div>

    <div class="payment-info">
        <h4 style="margin-top: 0; color: #155724;">INFORMATIONS DE PAIEMENT</h4>
        <div class="info-row">
            <span class="info-label">Mode de Paiement:</span>
            <span class="info-value"><strong><?php echo e($vente->mode_paiement ?? 'N/A'); ?></strong></span>
        </div>
        <?php if($vente->reference_paiement): ?>
        <div class="info-row">
            <span class="info-label">Référence:</span>
            <span class="info-value"><?php echo e($vente->reference_paiement); ?></span>
        </div>
        <?php endif; ?>
        <div class="info-row">
            <span class="info-label">Caissier:</span>
            <span class="info-value"><?php echo e($vente->caissier->name ?? 'N/A'); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Pharmacien:</span>
            <span class="info-value"><?php echo e($vente->pharmacien->name ?? 'N/A'); ?></span>
        </div>
    </div>

    <div style="margin-top: 50px; padding: 20px; border: 2px dashed #28a745; background-color: #f8f9fa;">
        <p style="margin: 0; text-align: center;">
            <strong>Merci pour votre confiance!</strong><br>
            Ce reçu atteste du paiement intégral de la facture <?php echo e($vente->numero_vente); ?>.<br>
            Conservez ce document comme preuve de paiement.
        </p>
    </div>

    <div style="margin-top: 60px;">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; text-align: center;">
                    <strong>Signature Caissier</strong><br><br>
                    _____________________
                </td>
                <td style="border: none; width: 50%; text-align: center;">
                    <strong>Cachet de l'hôpital</strong><br><br>
                    
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <hr style="border-top: 1px solid #ddd; margin: 30px 0 10px 0;">
        Document généré le <?php echo e(now()->format('d/m/Y à H:i')); ?><br>
        CMCU - Centre Médico-Chirurgical d'Urologie - Pharmacie<br>
        <em>Ce reçu est valable comme preuve de paiement</em>
    </div>
</body>
</html><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/pharmacie/receipt_pdf.blade.php ENDPATH**/ ?>