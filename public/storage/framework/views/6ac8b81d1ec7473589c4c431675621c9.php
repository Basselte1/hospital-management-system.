
<?php $__env->startSection('title', 'CMCU | Renseignement du dossier patient'); ?>
<?php $__env->startSection('content'); ?>
  
    
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
        <div class="container_fluid">
              <h1 class="text-center">CONSULTATION DE SUIVI - <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?> </h1>
              <hr>
        </div> 
        <div class="container">

          <?php if($errors->any()): ?>
              <div class="alert alert-danger">
                  <ul class="mb-0">
                      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <li><?php echo e($error); ?></li>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
              </div>
          <?php endif; ?>

          <form class="mb-3" action="<?php echo e(route('consultationsdesuivi.store')); ?>" method="post">
              <?php echo csrf_field(); ?>

              <div class="row">
                  <div class="mb-3 col-md-6">
                      <label for="interrogatoire" class="col-form-label text-md-end">
                          Interrogatoire <span class="text-danger">*</span>
                      </label>
                      <textarea 
                          rows="10" 
                          name="interrogatoire" 
                          class="form-control <?php $__errorArgs = ['interrogatoire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          required><?php echo e(old('interrogatoire')); ?></textarea>

                      <?php $__errorArgs = ['interrogatoire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                          <div class="invalid-feedback"><?php echo e($message); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>

                  <div class="mb-3 col-md-6">
                      <label for="commentaire" class="col-form-label text-md-end">
                          Commentaire <span class="text-danger">*</span>
                      </label>
                      <textarea 
                          rows="10" 
                          name="commentaire" 
                          class="form-control <?php $__errorArgs = ['commentaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          required><?php echo e(old('commentaire')); ?></textarea>

                      <?php $__errorArgs = ['commentaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                          <div class="invalid-feedback"><?php echo e($message); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>

              <div class="row">
                  <div class="mb-3 col-md-6">
                      <label for="date_creation" class="col-form-label text-md-end">
                          Date <span class="text-danger">*</span>
                      </label>
                      <input 
                          name="date_creation" 
                          type="date" 
                          class="form-control <?php $__errorArgs = ['date_creation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          value="<?php echo e(old('date_creation')); ?>" 
                          required>

                      <?php $__errorArgs = ['date_creation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                          <div class="invalid-feedback"><?php echo e($message); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>

                  <div class="col-md-6 d-flex align-items-end">
                      <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">

                      <button type="submit" class="btn btn-primary btn-lg col-sm-4">
                          Ajouter
                      </button>

                      <a href="<?php echo e(route('patients.show', $patient->id)); ?>" 
                        class="btn btn-warning btn-lg col-md-5 offset-md-1">
                          Annuler
                      </a>
                  </div>
              </div>
          </form>


        </div>

        
        <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/suivi_consultation/create.blade.php ENDPATH**/ ?>