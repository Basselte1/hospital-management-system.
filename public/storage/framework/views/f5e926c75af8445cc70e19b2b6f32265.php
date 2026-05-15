
<?php $__env->startSection('title', 'CMCU | Renseignement du dossier patient'); ?>
<?php $__env->startSection('content'); ?>
    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="container">
            <div class="row">
                <div class="col-12  toppad  offset-md-0 ">
                    <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn btn-success float-end"><i
                            class="fas fa-arrow-left "></i> Retour au dossier patient</a>
                </div>
                <br>
                <br>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                <div class="col-10  offset-md-0  toppad">
                    <div class="card">
                        <div class="card-body">
                            <!--  -->
                            <h3 class="card-title" ">Informations relatives au dossier du patient <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></h3>
                            <small class="text-danger" ><i><strong><i class="fas fa-exclamation-triangle"></i> Attention
                                        !! espace réservé au médecin</strong></i>
                            </small>
                            <table class="table table-user-information ">
                                <tbody>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                                        <?php echo $__env->make('admin.consultations.chirurgiens.form.consultation_chirurgien_form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                                        <?php echo $__env->make('admin.consultations.anesthesistes.form.consultation_anesthesiste_form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                    <div class="col-10  offset-md-0  toppad">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title text-uppercase text-primary" "><b>Prise des paramètres du patient <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></b>
                                    <small><strong></strong></small>
                                </div>
                                <small class="text-info" title="La prise des paramètres du patient doit être quotidienne"><i
                                        class="fas fa-info-circle"></i>La prise des paramètres du patient doit être quotidienne</small>
                                <?php echo $__env->make('admin.consultations.infirmiers.form.fiche_parametre_form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>






















    </body>
<?php $__env->stopSection(); ?>







<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/consultations/create.blade.php ENDPATH**/ ?>