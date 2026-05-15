
<?php $__env->startSection('title', 'CMCU | Renseignement du dossier patient'); ?>
<?php $__env->startSection('content'); ?>
    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="container_fluid">
              <h1 class="text-center">COMPTE-RENDU OPERATOIRE - <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?> </h1>
              <hr>
          </div>
        <div class="container">
            <div class="row">
                <div class="col-md-10  toppad ">
                    <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn btn-success float-end"><i
                            class="fas fa-arrow-left"></i> Retour au dossier patient</a>
                </div>
                <br>
                <br>
                <div class="col-md-10  toppad">
                    <div class="card">
                        <div class="card-body">
                            <!--  -->
                            <small class="text-danger"><i><strong><i class="fas fa-exclamation-triangle"></i> Attention
                                        !! espace réservé au médecin</strong></i></small>
                            <table class="table table-user-information ">
                                <tbody>
                                    <?php echo $__env->make('admin.consultations.chirurgiens.form.compte_rendu_operatoire_form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






















    </body>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/consultations/create_compte_rendu_operatoire.blade.php ENDPATH**/ ?>