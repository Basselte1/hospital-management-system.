

<?php $__env->startSection('title', 'CMCU | Bilan facure'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\User::class)): ?>
            <div class="container">
                <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-success float-end" title="Retour à la liste des clients">
                        <i class="fas fa-arrow-left">
                            
                        </i> Retour à la liste des clients
                </a>
                <h1 class="text-center">FACTURES CLIENTS</h1>
                <hr>
            </div>
            <div class="col-md-3 offset-md-8 text-center">
            </div>
            <div class="container">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <!--  -->
                        <table id="myTable" class="table table-striped table-bordered dt-responsive display nowrap td-responsive" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                            <td>ID</td>
                                <td>PATIENT</td>
                                <td>MOTIF</td>
                                <td>MONTANT</td>
                                <td>AVANCE</td>
                                <td>MEDECIN</td>    
                                <td>DATE</td>
                                <td>ACTION</td>
                            </tr>
                            <tbody>
                            <?php $__currentLoopData = $facturesClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($facture->id); ?></td>
                                    <td><?php echo e($facture->nom); ?></td>
                                    <td><?php echo e($facture->motif); ?></td>
                                    <td><?php echo e($facture->montant); ?> <b>FCFA</b></td>
                                    <td><?php echo e($facture->avance); ?> <b>FCFA</b></td>
                                    <td><?php echo e($facture->medecin_r); ?></td>
                                    <td><?php echo e($facture->created_at); ?></td>
                                    <td style="display: inline-flex;">
                                        <p class="me-2" data-bs-placement="top" data-bs-toggle="tooltip" title="Voire les détails">
                                            <a class="btn btn-success btn-sm me-1" title="Imprimer la facture du client" href="<?php echo e(route('factures.client_pdf', $facture->id)); ?>"><i class="fas fa-print"></i></a>
                                        </p>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\User::class)): ?>
                                            <form action="<?php echo e(route('factures.destroy', $facture->id)); ?>" method="post">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <p data-bs-placement="top" data-bs-toggle="tooltip" title="Supprimer la facture">
                                                    <button type="submit" class="btn btn-danger btn-sm"  onclick="return myFunction()"><i class="fas fa-trash-alt"></i></button>
                                                </p>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <br>
                        
                        <form class="mb-3 col-4" method="POST" action="<?php echo e(route('bilan_clientexterne.pdf')); ?>">
                            <?php echo csrf_field(); ?>
                          <div class="input-group mb-3  ">
                            <select name="day" class="form-control" required>
                                <option>Bien vouloir choisir une date</option>
                                <?php $__currentLoopData = $lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($list); ?>"><?php echo e($list); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        
                                <div class="input-group-append">
                                    <button class="btn btn-primary">Imprimer</button>
                                </div>
                          </div>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
    </div>
    </div>
    <?php endif; ?>

    </body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/client.blade.php ENDPATH**/ ?>