 <?php $__env->startSection('title', 'CMCU | Liste des chambres'); ?> <?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--// top-bar -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\chambre::class)): ?>
        <div class="container">
            <h1 class="text-center">LISTE DES CHAMBRES</h1>
        </div>
        <hr>
        <div class="container">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?php echo e(url('/admin/chambres/?categorie=VIP')); ?>" class="btn btn-success">VIP</a>
                        <a href="<?php echo e(url('/admin/chambres/?categorie=CLASSIQUE')); ?>" class="btn btn-primary">CLASSIQUE</a>
                        <a href="<?php echo e(url('/admin/chambres/?categorie=BLOC OPERATOIRE')); ?>" class="btn btn-info">BLOC</a>
                        <a href="<?php echo e(url('/admin/chambres')); ?>" class="btn btn-info"><i class="fas fa-rotate"></i></a>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-lg-12">
                <div class="table-responsive">
                    <!--  -->
                    <i class="table_info">Les montants sont exprimés en <b> FCFA</b></i>
                    <table id="myTable" class="table table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <td>ID</td>
                            <td>NUMERO</td>
                            <td>CATEGORIE</td>
                            <td>PRIX</td>
                            <td>STATUT</td>
                            <td>PATIENT</td>
                            <td>DUREE</td>
                            <td>ACTION</td>
                        </tr>
                        <tbody>
                        <?php $__currentLoopData = $chambres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chambre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($chambre->id); ?></td>
                                <td><?php echo e($chambre->numero); ?></td>
                                <td><?php echo e($chambre->categorie); ?></td>
                                <td><?php echo e($chambre->prix); ?></td>
                                <?php if($chambre->statut == 'occupé'): ?>
                                    <td><span class="badge badge-danger"><?php echo e($chambre->statut); ?></span></td>
                                <?php elseif($chambre->statut == 'libre'): ?>
                                    <td><span class="badge badge-primary"><?php echo e($chambre->statut); ?></span></td>
                                <?php endif; ?>
                                <td>
                                    <?php if($chambre->patient && $chambre->patient != 'null'): ?>
                                        <strong><?php echo e($chambre->patient); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($chambre->jour && $chambre->jour != 'null'): ?>
                                        <?php echo e($chambre->jour); ?> jour(s)
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Chambre::class)): ?>
                                    <a href="<?php echo e(route('chambres.edit',$chambre->id)); ?>" class="btn btn-primary btn-sm" title="Modifier les informations de la chambre"><i class="far fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if($chambre->statut == 'occupé'): ?>
                                        <form style="display: inline-flex;" action="<?php echo e(route('chambres_minus.update',$chambre->id)); ?>" method="post">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="patient" value="null">
                                            <input type="hidden" name="statut" value="libre">
                                            <input type="hidden" name="jour" value="null">
                                            <p data-bs-placement="top" data-bs-toggle="tooltip" title="Liberer la chambre">
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-minus"></i></button>
                                            </p>
                                        </form>
                                    <?php endif; ?>
                                    <?php if($chambre->statut == 'libre'): ?>
                                        <a href="<?php echo e(route('chambres.attribute',$chambre->id)); ?>" class="btn btn-success btn-sm" title="Attribuer cette chambre à un patient"><i class="fas fa-plus"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    
                </div>

            </div>
        </div> </br>
        <div class="col-md-3 offset-md-4 text-center">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Chambre::class)): ?>
            <a href="<?php echo e(route('chambres.create')); ?>" class="btn btn-primary table_link_right">Ajouter une nouvelle chambre</a>
            <?php endif; ?>
        </div>
       <?php endif; ?>
    </div>
    </div>
    </body>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/chambres/index.blade.php ENDPATH**/ ?>