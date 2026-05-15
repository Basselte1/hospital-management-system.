 
<?php $__env->startSection('title', 'Accueil | admin'); ?> 
<?php $__env->startSection('content'); ?>
<body>
<div class="se-pre-con"></div>
<div class="wrapper">
<?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!-- Page Content Holder -->
<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--// top-bar -->
    <div class="row">
        <!-- Stats -->
        <div class="outer-w3-agile col-xl">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\User::class)): ?>
                <div class="stat-grid p-3 d-flex align-items-center justify-content-between bg-primary">
                    <div class="s-l">
                        <h5>UTILISATEURS</h5>
                    </div>
                    <div class="s-r">
                        <h6><?php echo e($users); ?>

                            <i class="far fa-user"></i>
                        </h6>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Patient::class)): ?>
                <div class="stat-grid p-3 mt-3 d-flex align-items-center justify-content-between bg-success">
                    <div class="s-l">
                        <h5>PATIENTS</h5>
                    </div>
                    <div class="s-r">
                        <h6><?php echo e($patients); ?>

                            <i class="fas fa-users"></i>
                        </h6>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\chambre::class)): ?>
                <div class="stat-grid p-3 mt-3 d-flex align-items-center justify-content-between bg-danger">
                    <div class="s-l">
                        <h5>CHAMBRES</h5>
                    </div>
                    <div class="s-r">
                        <h6><?php echo e($chambres); ?>

                            <i class="fa-solid fa-bed"></i>
                        </h6>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Produit::class)): ?>
            <div class="stat-grid p-3 mt-3 d-flex align-items-center justify-content-between bg-success">
                <div class="s-l">
                    <h5>FICHES DE SATISFACTIONS</h5>
                    <p class="paragraph-agileits-w3layouts"></p>
                </div>
                <div class="s-r">
                    <h6>
                        <?php echo e($fiches); ?>

                        <i class="fas fa-tasks"></i>
                    </h6>
                </div>
            </div>
            <?php endif; ?> -->

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Produit::class)): ?>
                <div class="stat-grid p-3 mt-3 d-flex align-items-center justify-content-between bg-success">
                    <div class="s-l">
                        <h5>PRODUITS EN STOCK</h5>
                        <p class="paragraph-agileits-w3layouts"></p>
                    </div>
                    <div class="s-r">
                        <h6>
                            <?php if(auth()->user()->role_id === 1): ?>
                                <?php echo e(\App\Models\Produit::count()); ?>

                            <?php else: ?>
                                <?php echo e(\App\Models\Produit::where('status', 'approved')->count()); ?>

                            <?php endif; ?>
                            <i class="fas fa-tasks"></i>
                        </h6>
                    </div>
                </div>
            <?php endif; ?>
            
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewPending', \App\Models\Produit::class)): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-chart-line"></i> Statistiques d'Approbation
                        </h5>
                    </div>
                    
                    
                    
                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', \App\Models\Produit::class)): ?>
                    
                    <div class="col-xl-4 col-md-6 mb-3">
                        <div class="card border-start border-danger border-4 shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row g-0 align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                            Produits Rejetés
                                        </div>
                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo e($rejectedCount); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            
            
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Patient::class)): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
                    <div class="row mt-4">
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row g-0 align-items-center">
                                        <div class="col me-2">
                                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Rendez-vous</div>
                                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo e($events); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Patients Suivis Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-start border-success border-4 shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row g-0 align-items-center">
                                        <div class="col me-2">
                                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                                    Patients suivis
                                            </div>
                                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo e($patients_suivis); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Low Stock Alert -->
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('verifyStock', \App\Models\Produit::class)): ?>
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-start border-danger border-4 shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row g-0 align-items-center">
                                        <div class="col me-2">
                                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">Stock Faible</div>
                                            <div class="h5 mb-0 fw-bold text-gray-800">
                                                <?php echo e(\App\Models\Produit::where('status', 'approved')
                                                    ->whereColumn('qte_stock', '<=', 'qte_alerte')
                                                    ->count()); ?>

                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
        </div>
    </div>
    
    
</div>

<style>
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.2) !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
</body>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>