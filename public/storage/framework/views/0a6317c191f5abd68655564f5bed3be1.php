
<?php $__env->startSection('title', 'CMCU | Mon profil'); ?>
<?php $__env->startSection('breadcrumb', 'Mon profil'); ?>
<?php $__env->startSection('page_title', 'Mon profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <?php if(session('success')): ?>
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i> <?php echo e(session('error')); ?>

            </div>
            <?php endif; ?>

            <div class="tw-max-w-2xl">

                
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                    
                    <div class="tw-h-24 tw-bg-[#1D4ED8]"></div>

                    
                    <div class="tw-px-6 tw-pb-6">
                        <div class="-tw-mt-10 tw-mb-4">
                            <div class="tw-w-20 tw-h-20 tw-rounded-full tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-center tw-ring-4 tw-ring-white">
                                <span class="tw-text-white tw-text-3xl tw-font-bold tw-uppercase"><?php echo e(mb_substr($user->name, 0, 1)); ?></span>
                            </div>
                        </div>
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <div>
                                <h2 class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0"><?php echo e($user->name); ?> <?php echo e($user->prenom); ?></h2>
                                <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-mt-1"><?php echo e($user->role->name); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-6">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-id-card tw-text-[#1D4ED8]"></i>
                        <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Informations personnelles</h2>
                    </div>
                    <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">

                        <div class="tw-flex tw-items-start tw-gap-3">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/60 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-user tw-text-[#1D4ED8] tw-text-xs"></i>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Nom complet</p>
                                <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0"><?php echo e($user->name); ?> <?php echo e($user->prenom); ?></p>
                            </div>
                        </div>

                        <div class="tw-flex tw-items-start tw-gap-3">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/60 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-at tw-text-[#1D4ED8] tw-text-xs"></i>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Login</p>
                                <p class="tw-text-sm tw-font-mono tw-font-semibold tw-text-slate-700 tw-mb-0"><?php echo e($user->login); ?></p>
                            </div>
                        </div>

                        <div class="tw-flex tw-items-start tw-gap-3">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/60 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-venus-mars tw-text-[#1D4ED8] tw-text-xs"></i>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Sexe</p>
                                <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0"><?php echo e($user->sexe); ?></p>
                            </div>
                        </div>

                        <div class="tw-flex tw-items-start tw-gap-3">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/60 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-phone tw-text-[#1D4ED8] tw-text-xs"></i>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Téléphone</p>
                                <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0"><?php echo e($user->telephone); ?></p>
                            </div>
                        </div>

                        <div class="tw-flex tw-items-start tw-gap-3">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/60 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-birthday-cake tw-text-[#1D4ED8] tw-text-xs"></i>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Date de naissance</p>
                                <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0"><?php echo e($user->date_naissance); ?></p>
                            </div>
                        </div>

                        <div class="tw-flex tw-items-start tw-gap-3">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/60 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-map-marker-alt tw-text-[#1D4ED8] tw-text-xs"></i>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Lieu de naissance</p>
                                <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0"><?php echo e($user->lieu_naissance); ?></p>
                            </div>
                        </div>

                    </div>
                </div>

                
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                    <button type="button"
                        class="tw-w-full tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-border-0 tw-bg-transparent tw-cursor-pointer"
                        data-bs-toggle="collapse" data-bs-target="#form_mdp" aria-expanded="false" id="passwordCollapseBtn">
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-lock tw-text-[#1D4ED8]"></i>
                            <span class="tw-text-base tw-font-semibold tw-text-slate-700">Changer de mot de passe</span>
                        </div>
                        <i class="fas fa-chevron-down tw-text-slate-400 tw-text-sm tw-transition-transform tw-duration-200" id="passwordChevron"></i>
                    </button>

                    <div class="collapse" id="form_mdp">
                        <div class="tw-p-6">
                            <form action="<?php echo e(route('users.changePassword', $user->id)); ?>" method="POST" class="tw-space-y-4">
                                <?php echo method_field('PATCH'); ?>
                                <?php echo csrf_field(); ?>

                                <div class="tw-max-w-sm">
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Ancien mot de passe <span class="tw-text-red-500">*</span></label>
                                    <input name="old_pass" id="old_pass" type="password" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Nouveau mot de passe <span class="tw-text-red-500">*</span></label>
                                        <input name="password" id="password" type="password" placeholder="Nouveau mot de passe" required
                                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                    </div>
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Confirmer <span class="tw-text-red-500">*</span></label>
                                        <div class="tw-flex tw-gap-2">
                                            <input id="confirm_password" name="password_confirmation" type="password" placeholder="Confirmer" required
                                                class="tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                            <button type="button" onclick="show_password()"
                                                class="tw-flex tw-items-center tw-justify-center tw-w-10 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-500 tw-border-0 tw-transition-colors">
                                                <i id="show_pass" class="fas fa-eye tw-text-sm"></i>
                                            </button>
                                        </div>
                                        <span id="message" class="tw-block tw-mt-1 tw-text-xs"></span>
                                    </div>
                                </div>

                                <div class="tw-flex tw-gap-3 tw-pt-2">
                                    <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-px-6 tw-py-2.5 tw-transition-colors tw-border-0">
                                        <i class="fas fa-save tw-text-xs"></i> Modifier
                                    </button>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                                        class="tw-inline-flex tw-items-center tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-6 tw-py-2.5 tw-transition-colors tw-no-underline">
                                        Annuler
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script src="<?php echo e(asset('vendor/js/jquery-3.2.1.slim.min.js')); ?>"></script>
<script>
    $('#password, #confirm_password').on('keyup', function () {
        if ($('#password').val() && $('#password').val() == $('#confirm_password').val()) {
            $('#message').html('<span class="tw-text-green-600"><i class="fas fa-check"></i> Mots de passe identiques</span>');
        } else {
            $('#message').html('<span class="tw-text-red-500"><i class="fas fa-times"></i> Mots de passe différents</span>');
        }
    });

    function show_password() {
        var x = document.getElementById('password'), y = document.getElementById('confirm_password');
        var isPass = x.type === 'password';
        x.type = y.type = isPass ? 'text' : 'password';
        $('#show_pass').toggleClass('fa-eye fa-eye-slash');
    }

    // Rotate chevron on collapse
    var collapseEl = document.getElementById('form_mdp');
    if (collapseEl) {
        collapseEl.addEventListener('show.bs.collapse', function () {
            document.getElementById('passwordChevron').style.transform = 'rotate(180deg)';
        });
        collapseEl.addEventListener('hide.bs.collapse', function () {
            document.getElementById('passwordChevron').style.transform = '';
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/users/profile.blade.php ENDPATH**/ ?>