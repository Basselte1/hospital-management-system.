
<?php if($message = Session::get('success')): ?>
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-bs-dismiss="alert">×</button>
    <strong><?php echo e($message); ?></strong>
</div>
<?php endif; ?>


    <?php if($message = Session::get('error')): ?>
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-bs-dismiss="alert">×</button>
        <strong><?php echo e($message); ?></strong>
    </div>
    <?php endif; ?>


<?php if($errors->any()): ?>
<div class="alert alert-danger">
    <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">&times;</button>
    <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>


<?php if($message = Session::get('warning')): ?>
<div class="alert alert-warning alert-block">
    <button type="button" class="close" data-bs-dismiss="alert">×</button>
    <strong><?php echo e($message); ?></strong>
</div>
<?php endif; ?>


<?php if($message = Session::get('info')): ?>
<div class="alert alert-info alert-block">
    <button type="button" class="close" data-bs-dismiss="alert">×</button>
    <strong><?php echo e($message); ?></strong>
</div>
<?php endif; ?>









<?php if(Session::has('flashy_notification.message')): ?>
<script id="flashy-template" type="text/template">
    <div class="flashy flashy--<?php echo e(Session::get('flashy_notification.type')); ?>">
            <i class="material-icons">speaker_notes</i>
            <a href="#" class="flashy__body" target="_blank"></a>
        </div>
    </script>

<script>
    flashy("<?php echo e(Session::get('flashy_notification.message')); ?>", "<?php echo e(Session::get('flashy_notification.link')); ?>");
</script>
<?php endif; ?>






































<?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/partials/flash.blade.php ENDPATH**/ ?>