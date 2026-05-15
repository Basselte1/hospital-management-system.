
<?php $__env->startSection('title', 'CMCU | Liste des utilisateurs'); ?>
<?php $__env->startSection('breadcrumb', 'Utilisateurs'); ?>
<?php $__env->startSection('page_title', 'Utilisateurs'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Utilisateurs</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Gestion des comptes utilisateurs</p>
                </div>
                <a href="<?php echo e(route('users.create')); ?>"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-plus tw-text-xs"></i> Ajouter un utilisateur
                </a>
            </div>

            
            <?php if(session('success')): ?>
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i>
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i>
                <?php echo e(session('error')); ?>

            </div>
            <?php endif; ?>

            
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-users tw-text-[#1D4ED8]"></i>
                        <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Liste des utilisateurs</h2>
                        <span class="tw-ml-2 tw-inline-flex tw-items-center tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]"><?php echo e($users->total()); ?></span>
                    </div>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm" id="myTable">
                        <thead>
                            <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Utilisateur</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Login</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Rôle</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Téléphone</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3">
                                    <div class="tw-flex tw-items-center tw-gap-3">
                                        <div class="tw-w-8 tw-h-8 tw-rounded-full tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                            <span class="tw-text-white tw-text-xs tw-font-bold tw-uppercase"><?php echo e(mb_substr($user->name, 0, 1)); ?></span>
                                        </div>
                                        <div>
                                            <p class="tw-font-semibold tw-text-slate-700 tw-mb-0"><?php echo e($user->name); ?></p>
                                            <p class="tw-text-slate-400 tw-text-xs tw-mb-0"><?php echo e($user->prenom); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-font-mono tw-text-slate-600 tw-text-xs"><?php echo e($user->login); ?></td>
                                <td class="tw-px-4 tw-py-3">
                                    <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">
                                        <?php echo e($user->role ? $user->role->name : 'N/A'); ?>

                                    </span>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600"><?php echo e($user->telephone); ?></td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                        <a href="<?php echo e(route('users.edit', $user->id)); ?>"
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline" title="Modifier">
                                            <i class="far fa-edit tw-text-xs"></i>
                                        </a>
                                        <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="post" class="tw-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" onclick="return myFunction()"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-transition-colors" title="Supprimer">
                                                <i class="fas fa-trash-alt tw-text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($users->hasPages()): ?>
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    <?php echo e($users->links()); ?>

                </div>
                <?php endif; ?>

                <?php if($users->hasPages()): ?>
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    <?php echo e($users->links()); ?>

                </div>
                <?php endif; ?>
            </div>

        </main>
    </div>
</div>

<script>
function myFunction() {
    return confirm("Veuillez confirmer la suppression de l'utilisateur");
}
</script>
<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/users/index.blade.php ENDPATH**/ ?>