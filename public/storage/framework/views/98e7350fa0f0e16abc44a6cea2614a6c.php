<link href="<?php echo e(public_path('vendor/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" media="all" />

<?php
    \Carbon\Carbon::setLocale('fr');
    $printDate = \Carbon\Carbon::now()->translatedFormat('d F Y');
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        color: #333;
        background-color: #f9fafb;
    }

    .logo {
        width: 90px;
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
        margin: 20px 0;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #4463dc;
        margin-top: 20px;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .card {
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .card-body p {
        margin-bottom: 8px;
    }

    .footer {
        margin-top: 40px;
        text-align: right;
        font-style: italic;
        color: #555;
    }

    .footer strong {
        color: #000;
    }

    h3 {
        font-size: 20px;
        font-weight: 700;
        color: #222;
        text-transform: uppercase;
    }

    h5 {
        font-size: 15px;
        font-weight: 600;
        color: #4463dc;
    }

    .exam-block {
        margin-bottom: 15px;
        padding: 10px;
        background: #f2f6fc;
        border-radius: 6px;
    }

    .exam-list {
        list-style-type: disc;
        padding-left: 20px;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 10px; /* spacing between items */
    }

    .exam-list li {
        flex: 1 1 45%; /* two items per row */
        margin-bottom: 5px;
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
                <small>www.cmcu-cm.com | cmcu_cmcu@yahoo.fr</small>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    
    

    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <p><strong>Dr <?php echo e($prescription->user->name); ?> <?php echo e($prescription->user->prenom); ?></strong></p>
            <p><small><?php echo e($prescription->user->specialite ?? ''); ?></small></p>
            <p><small>ONMC: <?php echo e($prescription->user->onmc ?? ''); ?></small></p>
        </div>

        <div style="text-align:right">
            <p><small>Douala, le <?php echo e($printDate); ?></small></p>
        </div>
    </div>


    
    <div class="card">
        <div class="card-body">
            <div class="section-title">Patient</div>
            <p><strong>Nom :</strong> <?php echo e($prescription->patient->name); ?> <?php echo e($prescription->patient->prenom); ?></p>
        </div>
    </div>

    
    <div class="text-center mb-4">
        <h3>Bulletin d'Examens</h3>
    </div>

    
    <div>

        <?php if($prescription->hematologie): ?>
            <div class="exam-block d-flex flex-column">
                <h5>Hématologie</h5>
                <ul class="exam-list d-flex flex-wrap">
                    <?php $__currentLoopData = (array) $prescription->hematologie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($prescription->hemostase): ?>
            <div class="exam-block d-flex flex-column">
                <h5>hemostase</h5>
                <ul class="exam-list d-flex flex-wrap">
                    <?php $__currentLoopData = (array) $prescription->hemostase; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($prescription->biochimie): ?>
            <div class="exam-block d-flex flex-column">
                <h5>Biochimie</h5>
                <ul class="exam-list d-flex flex-wrap">
                    <?php $__currentLoopData = (array) $prescription->biochimie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($prescription->serologie): ?>
            <div class="exam-block d-flex flex-column">
                <h5>Sérologie</h5>
                <ul class="exam-list d-flex flex-wrap">
                    <?php $__currentLoopData = (array) $prescription->serologie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>


        <?php if($prescription->hormonologie): ?>
            <div class="exam-block d-flex flex-column">
                <h5>Hormonologie</h5>
                <ul class="exam-list d-flex flex-wrap">
                    <?php $__currentLoopData = (array) $prescription->hormonologie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($prescription->marqueurs): ?>
            <div class="exam-block d-flex flex-column">
                <h5>Marqueurs</h5>
                <ul class="exam-list d-flex flex-wrap">
                    <?php $__currentLoopData = (array) $prescription->marqueurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($prescription->bacteriologie): ?>
            <div class="exam-block d-flex flex-column">
                <h5>Bactériologie</h5>
                <ul class="exam-list d-flex flex-wrap">
                    <?php $__currentLoopData = (array) $prescription->bacteriologie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($prescription->spermiologie): ?>
            <div class="exam-block d-flex flex-column">
                <h5>Spermiologie</h5>
                <ul class="exam-list d-flex flex-wrap">
                    <?php $__currentLoopData = (array) $prescription->spermiologie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($prescription->urines): ?>
            <div class="exam-block d-flex flex-column">
                <h5>Urines</h5>
                <ul class="exam-list d-flex flex-wrap">
                    <?php $__currentLoopData = (array) $prescription->urines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="footer">
        <p><strong>Signature & Cachet du Médecin</strong></p>
    </div>

</div>
<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/etats/prescriptions.blade.php ENDPATH**/ ?>