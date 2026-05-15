
<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/partials/flash_form.blade.php ENDPATH**/ ?>