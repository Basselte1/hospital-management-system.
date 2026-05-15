

<?php $__env->startSection('title', 'CMCU | Liste des patients'); ?>

<?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Patient::class)): ?>
        <div class="container">
            <h1 class="text-center">LISTE DES PATIENTS</h1>
        </div>
        <hr>
        <div class="container">
            <!--  -->
            <div class="row">
                <div class="col-lg-12">
                <form action="<?php echo e(route('search.results')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control col-md-5" required>
                    <button type="submit" class="btn btn-primary btn-lg">Search</button>
                </form>
                <?php if(isset($patients)): ?>
                    <div class="table-responsive">
                        <p>Results of your reasearch on <strong><?php echo e($name); ?></strong></p><br>
                        <table id="myTable" class="table table-bordered table-hover" width="100%">
                            <thead>
                            <th>NUMERO</th>
                            <th>NOM </th>
                            <th>PRENOM</th>
                            <th>Assurance</th>
                            <th>DATE DE CREATION</th>
                            <th>ACTION</th>
                            </thead>
                            <tbody>

                            <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        CMCU - <?php echo e($patient->numero_dossier); ?>

                                        <?php if($patient->isNew()): ?>
                                            <span class="badge bg-success ms-2 animate-pulse">NEW</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($patient->name); ?></td>
                                    <td><?php echo e($patient->prenom); ?></td>
                                    <td><?php echo e($patient->prise_en_charge); ?></td>
                                    <td><?php echo e($patient->date_insertion); ?></td>
                                    <td>
                                        <div class="d-flex"> 
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('consulter', \App\Models\Patient::class)): ?>
                                            <a href="<?php echo e(route('patients.show', $patient->id)); ?>" title="consulter le dossier du patient" class="btn btn-primary btn-sm me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                        <?php endif; ?>
                                        
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print', \App\Models\Patient::class)): ?>
                                        
                                                <a class="btn btn-success btn-sm me-1" title="Générer la facture" href="<?php echo e(route('consultation.pdf', $patient->id)); ?>" onClick='if(this.disabled){ return false; } else { this.disabled = true; }'><i class="far fa-plus-square"></i></a>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', \App\Models\Patient::class)): ?>
                                            <form action="<?php echo e(route('patients.destroy', $patient->id)); ?>" method="post">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <p data-bs-placement="top" data-bs-toggle="tooltip" title="Delete">
                                                    <button type="submit" class="btn btn-danger btn-sm me-1" title="Supprimer le dossier du patient"  onclick="return myFunction()"><i class="fas fa-trash-alt"></i></button>
                                                </p>
                                            </form>
                                        <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                        <div class="clearfix"></div>

                        
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print', \App\Models\Patient::class)): ?>
            <div class="text-center table_link_right">

                <a href="<?php echo e(route('patients.create')); ?>" class="btn btn-primary" title="Vous allez jouter un nouveau patient dans le système">Ajouter un patient</a>

            </div>
        <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>
    <script>
        function myFunction() {
            if(!confirm("Veuillez confirmer la suppréssion du dossier patient"))
                event.preventDefault();
        }
    </script>
    </body>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/patients/index.blade.php ENDPATH**/ ?>