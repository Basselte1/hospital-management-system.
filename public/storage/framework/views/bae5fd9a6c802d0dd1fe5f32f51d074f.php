
<?php $__env->startSection('title', 'CMCU | Vérification des stocks'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('verifyStock', \App\Models\Produit::class)): ?>
    <div class="container-fluid py-4">

        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">Vérification des Stocks</h1>
                <hr class="w-25 mx-auto">
                <p class="text-muted">Vérifiez que les stocks entrés correspondent aux réceptions de l'hôpital</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h4><?php echo e($recentProduits->total()); ?></h4>
                        <p class="mb-0">Produits Disponible</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm bg-warning text-dark">
                    <div class="card-body text-center">
                        <h4><?php echo e(\App\Models\Produit::where('status', 'pending')->count()); ?></h4>
                        <p class="mb-0">En Attente</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm bg-danger text-white">
                    <div class="card-body text-center">
                        <h4><?php echo e(\App\Models\Produit::where('qte_stock', '<=', \DB::raw('qte_alerte'))->where('status', 'approved')->count()); ?></h4>
                        <p class="mb-0">Stock Faible</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-boxes"></i> Produits en Stock</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="stockTable" class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Désignation</th>
                                        <th>Catégorie</th>
                                        <th>Stock Reçu</th>
                                        <th>Seuil Alerte</th>
                                        <th>Prix Unitaire</th>
                                        <th>Ajouté par</th>
                                        <th>Date d'ajout</th>
                                        <th>Statut Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentProduits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php echo e($produit->qte_stock <= $produit->qte_alerte ? 'table-danger' : ''); ?>">
                                        <td><?php echo e($produit->id); ?></td>
                                        <td><strong><?php echo e($produit->designation); ?></strong></td>
                                        <td>
                                            <span class="badge 
                                                <?php if($produit->categorie == 'PHARMACEUTIQUE'): ?> bg-primary
                                                <?php elseif($produit->categorie == 'MATERIEL'): ?> bg-secondary
                                                <?php else: ?> bg-info
                                                <?php endif; ?>">
                                                <?php echo e($produit->categorie); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($produit->qte_stock <= $produit->qte_alerte ? 'danger' : 'success'); ?> fs-6">
                                                <?php echo e($produit->qte_stock); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($produit->qte_alerte); ?></td>
                                        <td><?php echo e(number_format($produit->prix_unitaire, 0, ',', ' ')); ?> FCFA</td>
                                        <td>
                                            <small>
                                                <i class="fas fa-user"></i> <?php echo e($produit->createdBy->name ?? 'N/A'); ?>

                                            </small>
                                        </td>
                                        <td>
                                            <small><?php echo e($produit->created_at->format('d/m/Y H:i')); ?></small>
                                        </td>
                                        <td>
                                            <?php if($produit->qte_stock <= $produit->qte_alerte): ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Stock Faible
                                                </span>
                                            <?php elseif($produit->qte_stock <= ($produit->qte_alerte * 1.5)): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-exclamation-circle"></i> À Surveiller
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Bon
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="mt-3">
                            <?php echo e($recentProduits->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col text-center">
                <a href="<?php echo e(route('produits.edit-permissions.pending')); ?>" class="btn btn-warning btn-lg">
                    <i class="fas fa-clock"></i> Voir les Produits en Attente
                    <span class="badge bg-light text-dark ms-2">
                        <?php echo e(\App\Models\Produit::where('status', 'pending')->count()); ?>

                    </span>
                </a>
                <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary btn-lg ms-2">
                    <i class="fas fa-list"></i> Tous les Produits
                </a>
            </div>
        </div>

    </div>
    <?php endif; ?>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#stockTable').DataTable({
            language: {
                url: "<?php echo e(asset('vendor/i18n/fr_fr.json')); ?>"
            },
            pageLength: 25,
            responsive: true,
            order: [[7, 'desc']], // Sort by date
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/produits/stock_verification.blade.php ENDPATH**/ ?>