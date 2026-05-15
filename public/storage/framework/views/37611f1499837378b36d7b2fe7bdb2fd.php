

<?php $__env->startSection('title', 'CMCU | Liste des clients'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Patient::class)): ?>
        <div class="container">
            <h1 class="text-center">LISTE DES CLIENTS</h1>
        </div>
        <hr>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <!--  -->
                        <table id="myTable" class="table table-bordred table-striped">
                            <thead>
                           
                            <th>NOM </th>
                            <th>PRENOM </th>
                            <th>DATE DE CREATION</th>
                            <th>ACTION</th>
                            </thead>
                            <tbody>

                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                   
                                    <td><?php echo e($client->nom); ?></td>
                                    <td><?php echo e($client->prenom); ?></td>
                                    <td><?php echo e($client->created_at->toFormattedDateString()); ?></td>
                                    <td style="display: inline-flex;">
                                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('consulter', \App\Models\Patient::class)): ?>
                                   
                                        <p data-bs-placement="top" data-bs-toggle="tooltip" title="Delete">
                                            <a class="btn btn-success btn-sm me-1" title="Générer la facture du client" href="<?php echo e(route('clientP.pdf', $client->id)); ?>" onClick='if(this.disabled){ return false; } else { this.disabled = true; }'><i class="far fa-plus-square"></i></a>
                                        </p>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', \App\Models\Patient::class)): ?>
                                        <form action="<?php echo e(route('clients.destroy', $client->id)); ?>" method="post">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <p data-bs-placement="top" data-bs-toggle="tooltip" title="Delete">
                                                <button type="submit" class="btn btn-danger btn-sm me-1" title="Supprimer le dossier du client"  onclick="return myFunction()"><i class="fas fa-trash-alt"></i></button>
                                            </p>
                                        </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                        <div class="clearfix"></div>
                        
                    </div>
                </div>
            </div>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print', \App\Models\Patient::class)): ?>
            <div class="col-md-12 text-center">

                <a href="<?php echo e(route('clients.create')); ?>" class="btn btn-primary" title="Vous allez jouter un nouveau client dans le système">Ajouter un client</a>

            </div>
         <?php endif; ?>
        </div>
    </div>
   
    <script>
        function myFunction() {
            if(!confirm("Veuillez confirmer la suppréssion du dossier client"))
                event.preventDefault();
        }
    </script>
     <?php endif; ?>
    </body>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/clients/index.blade.php ENDPATH**/ ?>