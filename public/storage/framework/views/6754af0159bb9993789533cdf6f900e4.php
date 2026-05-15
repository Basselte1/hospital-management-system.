<link href="<?php echo e(public_path('vendor/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" media="all" />

<style>
    body {
        font-size: 14px;
        color: #333;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fff;
    }
    .meta-section p { margin: 0; line-height: 1.5; }
    .title {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin: 20px 0;
        color: #222;
        text-transform: uppercase;
    }
    table.ordonance-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .ordonance-table th { background-color: #4463dc; color: #fff; font-weight: 600; text-align: center; padding: 10px; }
    .ordonance-table td { border: 1px solid #ddd; padding: 10px; font-size: 14px; }
    .ordonance-table tr:nth-child(even) { background-color: #f2f6fc; }
    .signature { margin-top: 50px; text-align: right; font-style: italic; color: #555; }
    .signature strong { color: #000; }
    .cmcu-footer {
        position: fixed; bottom: 0; left: 0; right: 0;
        background-color: #4463dc; color: #fff;
        text-align: center; font-size: 8px; padding: 5px 10px; line-height: 1.5;
    }
</style>

<div class="container">

    
    <table width="100%" style="border-collapse: collapse; margin-bottom: 0;">
        <tr>
            <td width="18%" style="vertical-align: middle; padding-right: 8px;">
                <img src="<?php echo e(public_path('admin/images/logo.jpg')); ?>" style="width: 80px; height: auto;" alt="Logo CMCU">
            </td>
            <td width="82%" style="text-align: center; vertical-align: middle;">
                <div style="font-weight: bold; font-size: 14px; color: #4463dc; text-transform: uppercase; letter-spacing: 0.5px;">CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE</div>
                <div style="font-size: 9px; margin-top: 1px;">ONMC : N°5531 DÉCISION N°007/10/D/ONMC/P/SG/MM</div>
                <div style="font-size: 9px;">Arrêté N° 3203/A/MINSANTE/SG/DOSTS/SDOS/SFSP</div>
                <div style="font-size: 9px;">Tél : +237 233 42 33 89 / +237 698 87 39 45 / +237 674 06 89 88</div>
                <div style="font-size: 9px;">Site internet : www.cmcu-cm.com | Email : cmcu_cmcu@yahoo.fr</div>
                <div style="font-size: 9px;">Situé à la vallée Manga Bell Douala - Bali</div>
                <div style="font-size: 9px; color: #4463dc;">Consultation sur rendez-vous</div>
                <div style="font-size: 9px;">N° de contribuable : P016400474386D</div>
            </td>
        </tr>
    </table>
    <div style="border-top: 3px solid #4463dc; margin: 6px 0 14px 0;"></div>

    
    <table width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td style="vertical-align: top;">
                <p><strong>Dr <?php echo e($user->prenom ?? ''); ?> <?php echo e($user->name); ?></strong></p>
                <p><small><?php echo e($user->specialite); ?></small></p>
                <p><small>ONMC: <?php echo e($user->onmc); ?></small></p>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <p><small><?php echo e(\Carbon\Carbon::parse($ordonance->created_at)->format('d/m/Y H:i')); ?></small></p>
                <p><strong>Patient :</strong> <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></p>
            </td>
        </tr>
    </table>

    
    <div class="title">Ordonnance Médicale</div>

    <?php
        // PIPE-ONLY parser — never split on comma.
        // Medication names legitimately contain commas (e.g. "Amoxicilline 500 mg, gélule").
        // Legacy records without ' | ' are treated as one item until re-saved.
        $parseField = function(string $v): array {
            $v = trim($v);
            if ($v === '') return [];
            if (str_contains($v, ' | ')) {
                return array_map('trim', explode(' | ', $v));
            }
            return [$v]; // legacy: whole value = one item
        };
        $medicaments  = $parseField((string) ($ordonance->medicament  ?? ''));
        $descriptions = $parseField((string) ($ordonance->description ?? ''));
        $quantites    = $parseField((string) ($ordonance->quantite    ?? ''));
        $rowCount = max(count($medicaments), count($descriptions), count($quantites), 1);
    ?>

    
    <table class="ordonance-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Médicament</th>
                <th>Posologie</th>
                <th>Quantité</th>
            </tr>
        </thead>
        <tbody>
            <?php for($i = 0; $i < $rowCount; $i++): ?>
                <tr>
                    <td style="text-align:center;"><?php echo e($i + 1); ?></td>
                    <td><?php echo e($medicaments[$i] ?? ''); ?></td>
                    <td><?php echo e($descriptions[$i] ?? ''); ?></td>
                    <td style="text-align:center;"><?php echo e($quantites[$i] ?? ''); ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    
    <div class="signature">
        <p><strong>Signature & Cachet</strong></p>
    </div>

</div>


<div class="cmcu-footer">
    Urgences Urologiques - Cancérologie - Centre de la Prostate - Coelioscopie - Calcul Urinaire<br>
    Incontinence Urinaire - Stérilité Masculine - Dysfonctionnement érectile - Lithotritie Extracorporelle<br>
    Explorations Endoscopiques - Échographie - Débimétrie - Biopsies de la Prostate<br>
    Médecine Générale - Médecine du Travail
</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/etats/ordonance.blade.php ENDPATH**/ ?>