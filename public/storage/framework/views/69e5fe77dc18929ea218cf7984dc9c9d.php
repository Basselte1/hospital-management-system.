

<?php $__env->startSection('title', 'CMCU | Bilan facture'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\User::class)): ?>
            <div class="container_fluid">
                <h1 class="text-center">FACTURES DEVIS</h1>
                <hr>
            </div>
            <div class="container pt-3">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <!--  -->
                        <table id="myTable" class="table table-striped table-bordered dt-responsive display nowrap td-responsive" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <td>ID</td>
                                <td>PATIENT</td>
                                <td>DESIGNATION</td>
                                <td>MONTANT</td>
                                <td>ACTION</td>
                            </tr>
                            <tbody>
                           <!-- <?php $__currentLoopData = $facture_devis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture_devi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($facture_devi->numero_facture); ?></td>
                                    <td><?php echo e($facture_devi->patient->name); ?> <?php echo e($facture_devi->patient->prenom); ?></td>
                                    <td><?php echo e($facture_devi->designation_devis); ?></td>
                                    <td><?php echo e($facture_devi->montant_devis); ?> <b>FCFA</b></td>
                                    <td style="display: inline-flex;">
                                        <p class="me-2" data-bs-placement="top" data-bs-toggle="tooltip" title="Voire les détails">
                                            <a class="btn btn-success btn-sm me-1" title="Imprimer la facture de devis" href="<?php echo e(route('facture_devis.pdf', $facture_devi->id)); ?>"><i class="fas fa-print"></i></a>
                                        </p>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
                            </tbody>
                        </table>
                    </div>
                    <!-- <a href="<?php echo e(route('facture_devis.create')); ?>" class="btn btn-primary table_link_right"> Ajouter une facture</a> -->
                     
                </div>
            </div>
    </div>
    </div>
    <?php endif; ?>

    </body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/devis.blade.php ENDPATH**/ ?>