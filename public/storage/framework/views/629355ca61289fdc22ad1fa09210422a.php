
<?php $__env->startSection('title', 'CMCU | Modifier un rôle'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">

        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="<?php echo e(route('roles.index')); ?>" class="hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">Rôles</a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">Modifier · <?php echo e($role->name); ?></span>
            </nav>

            
            <div class="tw-max-w-xl">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                    
                    <div class="tw-px-6 tw-py-5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="far fa-edit tw-text-amber-500 tw-text-sm"></i>
                        </div>
                        <div>
                            <h1 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Modifier le rôle</h1>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">ID #<?php echo e($role->id); ?></p>
                        </div>
                    </div>

                    
                    <form action="<?php echo e(route('roles.update', $role->id)); ?>" method="POST" class="tw-p-6">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        
                        <div class="tw-flex tw-items-start tw-gap-2.5 tw-bg-amber-50 tw-border tw-border-amber-200 tw-rounded-xl tw-px-4 tw-py-3 tw-mb-6">
                            <i class="fas fa-info-circle tw-text-amber-500 tw-text-sm tw-mt-0.5 tw-shrink-0"></i>
                            <p class="tw-text-xs tw-text-amber-700 tw-mb-0 tw-leading-relaxed">
                                Le champ rôle est obligatoire. Un même rôle ne peut pas être enregistré plusieurs fois.
                            </p>
                        </div>

                        
                        <div class="tw-mb-6">
                            <label for="name" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Nom du rôle
                                <span class="tw-text-red-500 tw-ml-0.5">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="<?php echo e(old('name', $role->name)); ?>"
                                required
                                class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-800 tw-bg-white tw-border tw-border-slate-200 tw-rounded-xl tw-transition-colors tw-duration-150 focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> tw-border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            >
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-duration-150 tw-shadow-sm tw-border-0 tw-cursor-pointer">
                                <i class="fas fa-check tw-text-xs"></i>
                                Enregistrer les modifications
                            </button>
                            <a href="<?php echo e(route('roles.index')); ?>"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-duration-150 tw-no-underline">
                                <i class="fas fa-arrow-left tw-text-xs"></i>
                                Retour
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/roles/edit.blade.php ENDPATH**/ ?>