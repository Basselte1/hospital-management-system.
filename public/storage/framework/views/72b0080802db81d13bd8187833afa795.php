<?php $__currentLoopData = session('flash_notification', collect())->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($message['overlay']): ?>
        <?php echo $__env->make('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
        <div class="alert
                    alert-<?php echo e($message['level']); ?>

                    <?php echo e($message['important'] ? 'alert-important' : ''); ?>"
                    role="alert"
        >
            <?php if($message['important']): ?>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                ></button>
            <?php endif; ?>

            <?php echo $message['message']; ?>

        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php echo e(session()->forget('flash_notification')); ?>

<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/vendor/flash/message.blade.php ENDPATH**/ ?>