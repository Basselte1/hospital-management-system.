
<?php $__env->startSection('title', 'Accueil | Tableau de bord'); ?>
<?php $__env->startSection('breadcrumb', 'Tableau de bord'); ?>
<?php $__env->startSection('page_title', 'Accueil'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">

        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <div class="tw-mb-6">
                <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Tableau de bord</h1>
                <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Bienvenue, <span class="tw-font-medium tw-text-[#1D4ED8]"><?php echo e(Auth::user()->name); ?></span> — vue d'ensemble de l'activité</p>
            </div>

            
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-4 tw-gap-4 tw-mb-6">

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\User::class)): ?>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-duration-200">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-users tw-text-[#1D4ED8] tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Utilisateurs</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1"><?php echo e($users); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Patient::class)): ?>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-duration-200">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-user-injured tw-text-[#14B8A6] tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Patients</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1"><?php echo e($patients); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\chambre::class)): ?>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-duration-200">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fa-solid fa-bed tw-text-indigo-500 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Chambres</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1"><?php echo e($chambres); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Produit::class)): ?>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-duration-200">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-boxes tw-text-amber-500 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Produits en Stock</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">
                            <?php if(auth()->user()->role_id === 1): ?>
                                <?php echo e(\App\Models\Produit::count()); ?>

                            <?php else: ?>
                                <?php echo e(\App\Models\Produit::where('status', 'approved')->count()); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewPending', \App\Models\Produit::class)): ?>
            <div class="tw-mb-6">
                <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-3 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-chart-line tw-text-[#1D4ED8]"></i>
                    Statistiques d'Approbation
                </h2>
                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', \App\Models\Produit::class)): ?>
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-border-l-4 tw-border-red-400 tw-shadow-sm tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-red-50 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-times-circle tw-text-red-400 tw-text-lg"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Produits Rejetés</p>
                            <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5"><?php echo e($rejectedCount); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Patient::class)): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
            <div class="tw-mb-6">
                <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-3 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-stethoscope tw-text-[#14B8A6]"></i>
                    Métriques Cliniques
                </h2>
                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-4">

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-border-l-4 tw-border-[#1D4ED8] tw-shadow-sm tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-calendar tw-text-[#1D4ED8] tw-text-lg"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Rendez-vous</p>
                            <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5"><?php echo e($events); ?></p>
                        </div>
                    </div>

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-border-l-4 tw-border-[#14B8A6] tw-shadow-sm tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-teal-50 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-user-check tw-text-[#14B8A6] tw-text-lg"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Patients Suivis</p>
                            <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5"><?php echo e($patients_suivis); ?></p>
                        </div>
                    </div>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('verifyStock', \App\Models\Produit::class)): ?>
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-border-l-4 tw-border-amber-400 tw-shadow-sm tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-exclamation-triangle tw-text-amber-400 tw-text-lg"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Stock Faible</p>
                            <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">
                                <?php echo e(\App\Models\Produit::where('status', 'approved')->whereColumn('qte_stock', '<=', 'qte_alerte')->count()); ?>

                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>

        </main>
    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>