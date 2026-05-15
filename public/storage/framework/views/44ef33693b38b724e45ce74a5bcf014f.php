 <?php $__env->startSection('title', 'CMCU | Liste des produits'); ?> <?php $__env->startSection('content'); ?>

<body>

<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
    <div class="container">
        <h1 class="text-center ">LISTE DE FICHES DE SATISFACTIONS</h1>
    </div>
    <hr>
        <div class="col-3 offset-8 text-center">
            <a href="" class=" btn btn-danger "><span class="glyphicon glyphicon-ok-sign"></span>&#xA0;
                <h2>TOTAL FICHES :</h2>
                <h1><P><?php echo e($fiche->total()); ?></P> </h1>
            </a>
        </div>
        <br>
    <div class="container">
        <div class="col-lg-12">
            <div class="table-responsive">
                <!--  -->
                <table id="myTable" class="table table-bordered table-striped" width="100%">
                    <thead>
                    <tr>
                        <td>ID</td>
                        <td>NOM</td>
                        <td>PRENOM</td>

                        <td>INFIRMIER EN CHARGE</td>
                        <td>ACCUEIL</td>
                        <td>RESTAURANT</td>
                        <td>CHAMBRE</td>
                        <td>SOINS</td>
                        <td>UNE NOTE</td>
                        <td>VOIR</td>
                        <td>EDITER</td>
                        <td>IMPRIMER</td>
                        <td>SUPPRIMER</td>
                    </tr>
                    <tbody>
                    <?php $__currentLoopData = $fiche; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fiches): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($fiches->id); ?></td>
                        <td><?php echo e($fiches->nom); ?></td>
                        <td><?php echo e($fiches->prenom); ?></td>

                        <td><?php echo e($fiches->infirmier_charge); ?></td>
                        <td><?php echo e($fiches->accueil); ?></td>
                        <td><?php echo e($fiches->restauration); ?></td>
                        <td><?php echo e($fiches->chambre); ?></td>
                        <td><?php echo e($fiches->soins); ?></td>
                        <td><?php echo e($fiches->notes); ?></td>
                        <td><a href="<?php echo e(Route('fiches.show', $fiches->id)); ?>" class="btn btn-primary"><i class="fas fa-eye"></i></a></td>
                        <td><a href="<?php echo e(Route('fiches.edit', $fiches->id)); ?>" class="btn btn-primary"><i class="far fa-edit"></i></a></td>
                        <td>
                            <p data-bs-placement="top" data-bs-toggle="tooltip" title="Imprimer">
                                <a class="btn btn-success btn-sm" title="Imprimer" href="<?php echo e(route('fiche.pdf', $fiches->id)); ?>"><i class="fas fa-print"></i></a>
                            </p>
                        </td>
                        <td>
                            <form action="<?php echo e(route('fiches.destroy', $fiches->id)); ?>" method="post">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <p data-bs-placement="top" data-bs-toggle="tooltip" title="Delete">
                                    <button type="submit" class="btn btn-danger btn-sm"  onclick="return myFunction()"><i class="fas fa-trash-alt"></i></button>
                                </p>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                
            </div>

        </div>
    </div> </br>
    <div class="table_link_right text-center">
        <a href="<?php echo e(Route('fiches.create')); ?>" class="btn btn-primary" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span>&#xA0;AJOUTER UNE NOUVELLE FICHE</a>
    </div>
</div>
</div>
<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
</body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/fiches/index.blade.php ENDPATH**/ ?>