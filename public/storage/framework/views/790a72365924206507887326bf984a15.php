<link href="<?php echo e(public_path('vendor/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" media="all" />

<style>
    body {
        font-size: 14px;
        color: #333;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
    }

    .logo {
        width: 80px;
        height: auto;
    }

    .clinic-name {
        font-size: 18px;
        font-weight: 700;
        color: #4463dc;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .clinic-info small {
        display: block;
        line-height: 1.4;
        color: #555;
    }

    .divider {
        border-top: 3px solid #4463dc;
        margin: 15px 0 25px;
    }

    .meta-section {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .meta-section p {
        margin: 0;
        line-height: 1.5;
    }

    .title {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin: 25px 0;
        color: #222;
        text-transform: uppercase;
    }

    table.ordonance-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        border-radius: 6px;
        overflow: hidden;
    }

    .ordonance-table th {
        background-color: #4463dc;
        color: #fff;
        font-weight: 600;
        text-align: center;
        padding: 10px;
    }

    .ordonance-table td {
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 14px;
    }

    .ordonance-table tr:nth-child(even) {
        background-color: #f2f6fc;
    }

    .signature {
        margin-top: 50px;
        text-align: right;
        font-style: italic;
        color: #555;
    }

    .signature strong {
        color: #000;
    }
</style>

<div class="container">

    
    <table width="100%" style="margin-bottom: 10px;">
        <tr>
            <td width="20%" valign="top">
                <img class="logo" src="<?php echo e(public_path('admin/images/logo.jpg')); ?>">
            </td>
            <td width="80%" align="right" valign="top">
                <p><b>CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</b></p>
                <p>007/10/D/ONMC</p>
                <p>VALLEE MANGA BELL DOUALA-BALI</p>
                <p>Arrêté N° 3203/A/MINSANTE/SG/DOSTS/SDOS/SFSP</p>
                <small>TEL:(+237) 233 423 389 / 674 068 988 / 698 873 945</small><br>
                <small>Email : info@cmcu-cm.com</small><br>
                <small>www.cmcu-cm.com / cmcu_cmcu@yahoo.fr</small>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    
    <div class="meta-section">
        <div>
            <p><strong>Dr <?php echo e($user->prenom ?? ''); ?> <?php echo e($user->name); ?></strong></p>
            <p><small><?php echo e($user->specialite); ?></small></p>
            <p><small>ONMC: <?php echo e($user->onmc); ?></small></p>
        </div>
        <div class="text-end">
            <p><small><?php echo e(\Carbon\Carbon::parse($ordonance->created_at)->format('d/m/Y H:i')); ?></small></p>
            <p class="patient"><strong>Patient :</strong> <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></p>
        </div>
    </div>

    
    <div class="title">Ordonnance Médicale</div>

    <?php
        $medicaments = explode(',', $ordonance->medicament ?? '');
        $descriptions = explode(',', $ordonance->description ?? '');
        $quantites = explode(',', $ordonance->quantite ?? '');
        $rowCount = max(count($medicaments), count($descriptions), count($quantites));
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
                    <td class="text-center"><?php echo e($i + 1); ?></td>
                    <td><?php echo e($medicaments[$i] ?? ''); ?></td>
                    <td><?php echo e($descriptions[$i] ?? ''); ?></td>
                    <td class="text-center"><?php echo e($quantites[$i] ?? ''); ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    
    <div class="signature">
        <p><strong>Signature & Cachet</strong></p>
    </div>

</div><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/etats/ordonance.blade.php ENDPATH**/ ?>