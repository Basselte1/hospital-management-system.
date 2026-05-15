
<div class="flash-messages-wrapper" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
    
    <?php if($message = Session::get('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong><?php echo e($message); ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if($message = Session::get('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-times-circle me-2"></i>
        <strong><?php echo e($message); ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if($message = Session::get('warning')): ?>
    <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-triangle-exclamation me-2"></i>
        <!-- <i class="fa-solid fa-triangle-exclamation me-2"></i> -->
        <strong><?php echo e($message); ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if($message = Session::get('info')): ?>
    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong><?php echo e($message); ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-times-circle me-2"></i>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
</div>


<?php if(Session::has('success') || Session::has('error') || Session::has('warning') || Session::has('info')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.flash-messages-wrapper .alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000); // Auto-dismiss after 5 seconds
    });
</script>
<?php endif; ?>





<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcu\resources\views/partials/flash_messages.blade.php ENDPATH**/ ?>