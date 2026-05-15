

<?php $__env->startSection('title', 'CMCU | Liste des utilisateurs'); ?>

<?php $__env->startSection('content'); ?>

<body>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <div class="container">
            <h1 class="text-center">LISTE DES UTILISATEURS</h1>
        </div>
        <hr>
        
        <div class="container">
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <!--  -->
                        <table id="myTable" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <th>ID</th>
                                <th>NOM</th>
                                <th>LOGIN</th>
                                <th>ROLE</th>
                                <th>TELEPHONE</th>
                                <th>EDITER</th>
                                <th>SUPPRIMER</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($user->id); ?></td>
                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->login); ?></td>
                                        
                                        <td><?php echo e($user->role ? $user->role->name : 'N/A'); ?></td>
                                        <td><?php echo e($user->telephone); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-primary btn-sm">
                                                <i class="far fa-edit"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="post">
                                                <?php echo csrf_field(); ?> 
                                                <?php echo method_field('DELETE'); ?>
                                                <p data-placement="top" data-toggle="tooltip" title="Delete">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return myFunction()">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </p>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <div class="clearfix"></div>
                        <?php echo e($users->links()); ?>

                    </div>
                    <br>
                    <div class="text-center table_link_right">
                        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">Ajouter un utilisateur</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function myFunction() {
            if(!confirm("Veuillez confirmer la suppréssion de l'utilisateur"))
                event.preventDefault();
        }
    </script>
    <script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
</body>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/users/index.blade.php ENDPATH**/ ?>