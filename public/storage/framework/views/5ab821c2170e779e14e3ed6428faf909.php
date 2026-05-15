
<?php $__env->startSection('title', 'CMCU | Liste des produits'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\Produit::class)): ?>
    <div class="container-fluid py-4">
        
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="fw-bold text-primary mb-1">
                            <i class="fas fa-boxes"></i> Gestion des Produits
                        </h1>
                        <p class="text-muted mb-0">
                            <?php if($category): ?>
                                Catégorie: <strong><?php echo e($category); ?></strong>
                            <?php else: ?>
                                Tous les produits
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Produit::class)): ?>
                    <a href="<?php echo e(route('produits.create')); ?>" class="btn btn-success btn-lg shadow-sm">
                        <i class="fas fa-plus-circle"></i> Nouveau Produit
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-0"><?php echo e($produitCount); ?></h3>
                        <p class="text-muted mb-0">
                            <?php if($category): ?> de cette catégorie <?php else: ?> Total Produits <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-recycle fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-0">
                            <?php echo e(\App\Models\Produit::where('is_reusable', true)->count()); ?>

                        </h3>
                        <p class="mb-0">Produits Réutilisables</p>
                        <a href="<?php echo e(route('produits.reusable-list')); ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approveEditRequests', \App\Models\Produit::class)): ?>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-key fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-0">
                            <?php echo e(\App\Models\ProduitEditRequest::where('status', 'pending')->count()); ?>

                        </h3>
                        <p class="mb-0">Permissions en Attente</p>
                        <a href="<?php echo e(route('produits.edit-permissions.pending')); ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-0">
                            <?php echo e(\App\Models\Produit::whereColumn('qte_stock', '<=', 'qte_alerte')->count()); ?>

                        </h3>
                        <p class="mb-0">Stock Faible</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <!-- Category Filter -->
                            <div class="col-md-8 mb-2">
                                <div class="btn-group w-100" role="group">
                                    <a href="<?php echo e(route('produits.index')); ?>" 
                                       class="btn <?php echo e(!$category ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                        <i class="fas fa-th"></i> Tous (<?php echo e($produitCount); ?>)
                                    </a>
                                    <a href="<?php echo e(route('produits.index', ['categorie' => 'PHARMACEUTIQUE'])); ?>" 
                                       class="btn <?php echo e($category === 'PHARMACEUTIQUE' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                        <i class="fas fa-pills"></i> Pharmaceutique (<?php echo e($categoryCounts['PHARMACEUTIQUE']); ?>)
                                    </a>
                                    <a href="<?php echo e(route('produits.index', ['categorie' => 'MATERIEL'])); ?>" 
                                       class="btn <?php echo e($category === 'MATERIEL' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                        <i class="fas fa-tools"></i> Matériel (<?php echo e($categoryCounts['MATERIEL']); ?>)
                                    </a>
                                    <a href="<?php echo e(route('produits.index', ['categorie' => 'ANESTHESISTE'])); ?>" 
                                       class="btn <?php echo e($category === 'ANESTHESISTE' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                        <i class="fas fa-syringe"></i> Anesthésiste (<?php echo e($categoryCounts['ANESTHESISTE']); ?>)
                                    </a>
                                </div>
                            </div>

                            <!-- Reusable Filter -->
                            <div class="col-md-4 mb-2">
                                <div class="btn-group w-100" role="group">
                                    <a href="<?php echo e(route('produits.index', array_merge(request()->all(), ['reusable' => '1']))); ?>" 
                                       class="btn <?php echo e(request('reusable') === '1' ? 'btn-success' : 'btn-outline-success'); ?>">
                                        <i class="fas fa-recycle"></i> Réutilisables
                                    </a>
                                    <a href="<?php echo e(route('produits.reusable-list')); ?>" 
                                       class="btn btn-outline-info">
                                        <i class="fas fa-list"></i> Vue Détaillée
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Products Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="produitsTable" class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Désignation</th>
                                        <th>Catégorie</th>
                                        <th>Stock</th>
                                        <th>Alerte</th>
                                        <th>Prix Unitaire</th>
                                        <?php if(in_array(auth()->user()->role_id, [1, 5, 7])): ?>
                                        <th class="text-center">Réutilisable</th>
                                        <?php endif; ?>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php echo e($produit->qte_stock <= $produit->qte_alerte ? 'table-danger' : ''); ?>">
                                        <td><?php echo e($produit->id); ?></td>
                                        <td>
                                            <strong><?php echo e($produit->designation); ?></strong>
                                            <?php if($produit->is_reusable): ?>
                                                <br><small class="badge bg-success"><i class="fas fa-recycle"></i> Réutilisable</small>
                                            <?php endif; ?>
                                        </td>
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
                                            <?php if($produit->is_reusable && ($produit->qte_en_utilisation > 0 || $produit->qte_en_sterilisation > 0)): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?php if($produit->qte_en_utilisation > 0): ?>
                                                        <span class="badge bg-warning text-dark"><?php echo e($produit->qte_en_utilisation); ?> en usage</span>
                                                    <?php endif; ?>
                                                    <?php if($produit->qte_en_sterilisation > 0): ?>
                                                        <span class="badge bg-info"><?php echo e($produit->qte_en_sterilisation); ?> en stéril.</span>
                                                    <?php endif; ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($produit->qte_alerte); ?></td>
                                        <td><?php echo e(number_format($produit->prix_unitaire, 0, ',', ' ')); ?> FCFA</td>
                                        
                                        
                                        <?php if(in_array(auth()->user()->role_id, [1, 5, 7])): ?>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input reusable-toggle" 
                                                       type="checkbox" 
                                                       id="reusable_<?php echo e($produit->id); ?>" 
                                                       data-produit-id="<?php echo e($produit->id); ?>"
                                                       data-produit-name="<?php echo e($produit->designation); ?>"
                                                       <?php echo e($produit->is_reusable ? 'checked' : ''); ?>

                                                       style="cursor: pointer; transform: scale(1.3);">
                                            </div>
                                            <?php if($produit->is_reusable && $produit->methode_sterilisation_recommandee): ?>
                                                <br><small class="text-muted"><?php echo e(ucfirst($produit->methode_sterilisation_recommandee)); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <?php endif; ?>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if($produit->is_reusable && in_array(auth()->user()->role_id, [1, 5, 7])): ?>
                                                <a href="<?php echo e(route('produits.edit-reusable-settings', $produit->id)); ?>" 
                                                   class="btn btn-sm btn-success" 
                                                   title="Paramètres réutilisable">
                                                    <i class="fas fa-cog"></i>
                                                </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $produit)): ?>
                                                <a href="<?php echo e(route('produits.edit', $produit->id)); ?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $produit)): ?>
                                                <form action="<?php echo e(route('produits.destroy', $produit->id)); ?>" 
                                                      method="POST" 
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAuditLogs', \App\Models\Produit::class)): ?>
                                                <a href="<?php echo e(route('produits.audit-logs.show', $produit->id)); ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Historique">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="mt-3">
                            <?php echo e($produits->appends(request()->query())->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions (Admin/Gestionnaire) -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approveEditRequests', \App\Models\Produit::class)): ?>
        <div class="row mt-4">
            <div class="col text-center">
                <div class="btn-group" role="group">
                    <a href="<?php echo e(route('produits.edit-permissions.pending')); ?>" class="btn btn-warning btn-lg">
                        <i class="fas fa-key"></i> Permissions en Attente
                        <span class="badge bg-light text-dark ms-2">
                            <?php echo e(\App\Models\ProduitEditRequest::where('status', 'pending')->count()); ?>

                        </span>
                    </a>
                    <a href="<?php echo e(route('produits.edit-permissions.history')); ?>" class="btn btn-info btn-lg">
                        <i class="fas fa-history"></i> Historique des Permissions
                    </a>
                    <a href="<?php echo e(route('produits.audit-logs')); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-clipboard-list"></i> Journal d'Audit
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
    <?php endif; ?>
</div>


<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php $__env->startPush('scripts'); ?>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<script>

waitForjQuery(function() {
$(document).ready(function() {
    // Initialize DataTable
    $('#produitsTable').DataTable({
        language: {
            url: "<?php echo e(asset('vendor/i18n/fr_fr.json')); ?>"
        },
        pageLength: 30,
        responsive: true,
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [<?php if(in_array(auth()->user()->role_id, [1, 5, 7])): ?> 6, 7 <?php else: ?> 6 <?php endif; ?>] } // Disable sorting on action columns
        ]
    });

    // Setup CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle reusable toggle
    $('.reusable-toggle').on('change', function() {
        const checkbox = $(this);
        const produitId = checkbox.data('produit-id');
        const produitName = checkbox.data('produit-name');
        const newStatus = checkbox.is(':checked');
        
        const message = newStatus 
            ? `Marquer "${produitName}" comme produit réutilisable?` 
            : `Retirer "${produitName}" de la liste des produits réutilisables?`;
        
        if (!confirm(message)) {
            // Revert checkbox if user cancels
            checkbox.prop('checked', !newStatus);
            return;
        }

        // Show loading state
        checkbox.prop('disabled', true);
        const row = checkbox.closest('tr');
        row.css('opacity', '0.6');

        // Send AJAX request
        $.ajax({
            url: `/admin/produits/${produitId}/toggle-reusable`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showNotification('success', response.message);
                    
                    // Reload page to update UI
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('error', response.message || 'Erreur lors de la mise à jour');
                    checkbox.prop('checked', !newStatus);
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Erreur lors de la mise à jour';
                showNotification('error', errorMessage);
                checkbox.prop('checked', !newStatus);
            },
            complete: function() {
                checkbox.prop('disabled', false);
                row.css('opacity', '1');
            }
        });
    });

    // Show notification function
    function showNotification(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert at top of container
        $('.container-fluid').prepend(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }
});


});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/produits/index.blade.php ENDPATH**/ ?>