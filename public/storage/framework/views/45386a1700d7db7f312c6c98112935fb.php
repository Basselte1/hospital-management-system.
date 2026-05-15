

<?php $__env->startSection('title', 'CMCU | Liste des roles'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
        <div class="container">
            <h1 class="text-center">LISTE DES ROLES</h1>
        </div>
        <hr>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <!--  -->
                        <table id="myTable" class="table table-bordered table-striped" width="100%">
                            <thead>
                            <th>
                                ID
                            </th>
                            <th>ROLE</th>
                            <th>EDITER</th>
                            
                            <th>SUPPRIMER</th>
                            </thead>
                            <tbody>

                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($role->id); ?></td>
                                    <td><?php echo e($role->name); ?></td>
                                    <td>
                                        <p data-bs-placement="top" data-bs-toggle="tooltip" title="Edit">
                                            <a href="<?php echo e(route('roles.edit', $role->id)); ?>" class="btn btn-primary btn-sm"><i class="far fa-edit"></i></a>
                                        </p>
                                    </td>
                                    
                                        
                                            
                                        
                                    
                                    <td>
                                        <form action="<?php echo e(route('roles.destroy', $role->id)); ?>" method="post">
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
                        <div class="clearfix"></div>
                        
                    </div>
                    <br>
                    <div class="table_link_right text-center">
                        <a href="<?php echo e(route('roles.create')); ?>" class="btn btn-primary">Ajouter un role</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        function myFunction() {
            if(!confirm("Veuillez confirmer la suppréssion du role"))
                event.preventDefault();
        }
    </script>
    </body>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>