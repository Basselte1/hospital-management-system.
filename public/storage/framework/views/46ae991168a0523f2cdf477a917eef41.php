

<?php $__env->startSection('title', 'CMCU | Surveillance rapprochée des paramètres'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    <style>
        .custom-table{border-collapse:collapse;width:100%;border:solid 1px #c0c0c0;font-family:open sans;font-size:11px}
        .custom-table th,.custom-table td{text-align:left;padding:8px;border:solid 1px #c0c0c0}
        .custom-table th{color:#000080}
        .custom-table tr:nth-child(odd){background-color:#f7f7ff}
        .custom-table>thead>tr{background-color:#dde8f7!important}
        .tbtn{border:0;outline:0;background-color:transparent;font-size:13px;cursor:pointer}
        .toggler{display:none}
        .toggler1{display:table-row;}
        .custom-table a{color: #0033cc;}
        .custom-table a:hover{color: #f00;}
        .page-header{background-color: #eee;}
    </style>

    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="row mb-1">
            <div class="col-sm-12">
                <h1 class="text-center ">SURVEILLANCE RAPPROCHEE DES PARAMETRES</h1>
            </div>
        </div>
        <hr>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
            <a href="<?php echo e(route('surveillance_rapproche.index', $patient->id)); ?>" class="btn btn-success float-end  mb-2"
               title="Retour à la liste des patients">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <div class="container">
                <table class="custom-table">
                    <thead>
                    <tr>
                        <th>PERIODES</th>
                        <th>DATE / HEURE</th>
                        <th>T.A</th>
                        <th>F.R</th>
                        <th>POULS</th>
                        <th>SPO2</th>
                        <th>T°</th>
                        <th>DIURESE</th>
                        <th>CONSCIENCE</th>
                        <th>DOULEUR</th>
                        <th>OBSERVATIONS / PLAINTES</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="20" class="page-header"><button type="button" class="tbtn"><i class="fa fa-plus-circle fa-minus-circle"></i>Pré-opératoire</button> </td>
                    </tr>
                        <?php if(!empty($paramPres)): ?>
                            <?php $__currentLoopData = $paramPres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paramPre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="toggler toggler1">
                                    <td></td>
                                    <td><b><?php echo e($paramPre->date); ?> / <?php echo e($paramPre->heure); ?></b></td>
                                    <td><?php echo e($paramPre->ta); ?></td>
                                    <td><?php echo e($paramPre->fr); ?></td>
                                    <td><?php echo e($paramPre->pouls); ?></td>
                                    <td><?php echo e($paramPre->spo2); ?></td>
                                    <td><?php echo e($paramPre->temperature); ?></td>
                                    <td><?php echo e($paramPre->diurese); ?></td>
                                    <td><?php echo e($paramPre->conscience); ?></td>
                                    <td><?php echo e($paramPre->douleur); ?></td>
                                    <td><?php echo e($paramPre->observation_plainte); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                    <tbody>
                    <tr>
                        <td colspan="20" class="page-header"><button type="button" class="tbtn"><i class="fa fa-plus-circle"></i> Post-opératoire</button></td>
                    </tr>
                        <?php if(!empty($paramPosts)): ?>
                            <?php $__currentLoopData = $paramPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paramPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="toggler">
                                <td></td>
                                <td><b><?php echo e($paramPost->date); ?> / <?php echo e($paramPost->heure); ?></b></td>
                                <td><?php echo e($paramPost->ta); ?></td>
                                <td><?php echo e($paramPost->fr); ?></td>
                                <td><?php echo e($paramPost->pouls); ?></td>
                                <td><?php echo e($paramPost->spo2); ?></td>
                                <td><?php echo e($paramPost->temperature); ?></td>
                                <td><?php echo e($paramPost->diurese); ?></td>
                                <td><?php echo e($paramPost->conscience); ?></td>
                                <td><?php echo e($paramPost->douleur); ?></td>
                                <td><?php echo e($paramPost->observation_plainte); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>

    </body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/consultations/infirmiers/index_surveillance_rapproche_param.blade.php ENDPATH**/ ?>