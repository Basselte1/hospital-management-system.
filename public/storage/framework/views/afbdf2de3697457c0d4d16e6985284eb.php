

<?php $__env->startSection('title', 'CMCU | Modifier prescription médicale'); ?>

<?php $__env->startSection('content'); ?>

<body>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 toppad offset-md-0">
                        <a class="btn btn-success" href="<?php echo e(route('fiche.prescription_medicale.index', $patient)); ?>" title="Prescriptions médicales">
                            <i class="fas fa-arrow-left"></i>  Retour a  la liste
                        </a>
                    </div>
                    
                    <div class="container">
                        <br>
                        <h3 class="text-center">MODIFIER PRESCRIPTION MÉDICALE</h3>
                        <h5 class="text-center text-muted"><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></h5>
                        <br>
                        
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-pills"></i> Informations de la prescription</h5>
                            </div>
                            <div class="card-body">
                                <?php if($errors->any()): ?>
                                    <div class="alert alert-danger">
                                        <strong><i class="fas fa-exclamation-triangle"></i> Erreurs de validation :</strong>
                                        <ul class="mb-0 mt-2">
                                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($error); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <form action="<?php echo e(route('prescription_medicale.update', $prescription_medicale->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    
                                    <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">
                                    <input type="hidden" name="fiche_prescription_medicale_id" value="<?php echo e($prescription_medicale->fiche_prescription_medicale_id); ?>">

                                    
                                    <?php echo $__env->make('admin.consultations.infirmiers.form._prescription_medicale_fields', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                    <hr class="my-4">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Enregistrer les modifications
                                            </button>
                                            <a href="<?php echo e(route('fiche.prescription_medicale.index', $patient)); ?>" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Annuler
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/infirmiers/form/prescription_medicale_edit.blade.php ENDPATH**/ ?>