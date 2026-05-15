
<?php $__env->startSection('title', 'CMCU | Historique des Modifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAuditLogs', \App\Models\Produit::class)): ?>
    <div class="container-fluid py-4">

        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-history"></i> Historique des Modifications
                </h1>
                <hr class="w-25 mx-auto">
                <p class="text-muted">Journal complet de toutes les modifications des produits</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtres</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?php echo e(route('produits.audit-logs')); ?>">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Action</label>
                                    <select name="action" class="form-select">
                                        <option value="">Toutes les actions</option>
                                        <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($action); ?>" <?php echo e(request('action') == $action ? 'selected' : ''); ?>>
                                            <?php echo e(ucfirst(str_replace('_', ' ', $action))); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date début</label>
                                    <input type="date" name="date_from" class="form-control" 
                                           value="<?php echo e(request('date_from')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date fin</label>
                                    <input type="date" name="date_to" class="form-control" 
                                           value="<?php echo e(request('date_to')); ?>">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search"></i> Filtrer
                                    </button>
                                    <a href="<?php echo e(route('produits.audit-logs')); ?>" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Réinitialiser
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Logs d'Audit</h5>
                        <span class="badge bg-light text-dark"><?php echo e($auditLogs->total()); ?> entrées</span>
                    </div>
                    <div class="card-body">
                        <?php if($auditLogs->count() == 0): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucun log trouvé avec ces critères
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date/Heure</th>
                                        <th>Action</th>
                                        <th>Produit</th>
                                        <th>Utilisateur</th>
                                        <th>Description</th>
                                        <th>Détails</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <small><?php echo e($log->created_at->format('d/m/Y H:i:s')); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($log->getActionColor()); ?>">
                                                <i class="<?php echo e($log->getActionIcon()); ?>"></i>
                                                <?php echo e($log->getActionLabel()); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($log->produit): ?>
                                            <a href="<?php echo e(route('produits.audit-logs.show', $log->produit_id)); ?>" 
                                               class="text-primary">
                                                <?php echo e($log->produit->designation); ?>

                                            </a>
                                            <?php else: ?>
                                            <span class="text-muted">Produit supprimé</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="fas fa-user"></i>
                                                <?php echo e($log->user->name ?? 'N/A'); ?>

                                            </small>
                                        </td>
                                        <td><?php echo e($log->description); ?></td>
                                        <td>
                                            <?php if($log->old_values || $log->new_values): ?>
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal<?php echo e($log->id); ?>">
                                                <i class="fas fa-eye"></i> Voir
                                            </button>

                                            <!-- Details Modal -->
                                            <div class="modal fade" id="detailsModal<?php echo e($log->id); ?>" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-<?php echo e($log->getActionColor()); ?> text-white">
                                                            <h5 class="modal-title">
                                                                <i class="<?php echo e($log->getActionIcon()); ?>"></i>
                                                                Détails - <?php echo e($log->getActionLabel()); ?>

                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <strong>Date:</strong> <?php echo e($log->created_at->format('d/m/Y H:i:s')); ?><br>
                                                                <strong>Utilisateur:</strong> <?php echo e($log->user->name ?? 'N/A'); ?><br>
                                                                <strong>IP:</strong> <?php echo e($log->ip_address ?? 'N/A'); ?>

                                                            </div>
                                                            
                                                            <?php if($log->getFormattedChanges()): ?>
                                                            <h6>Modifications:</h6>
                                                            <table class="table table-sm table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Champ</th>
                                                                        <th>Ancienne Valeur</th>
                                                                        <th>Nouvelle Valeur</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $__currentLoopData = $log->getFormattedChanges(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        <td><strong><?php echo e($change['field']); ?></strong></td>
                                                                        <td><?php echo e($change['old'] ?? 'N/A'); ?></td>
                                                                        <td class="text-primary"><strong><?php echo e($change['new']); ?></strong></td>
                                                                    </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tbody>
                                                            </table>
                                                            <?php else: ?>
                                                            <p class="text-muted">Aucun détail de modification disponible</p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php else: ?>
                                            <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php echo e($auditLogs->appends(request()->query())->links('pagination::bootstrap-5')); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux produits
            </a>
        </div>

    </div>
    <?php endif; ?>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/produits/audit_logs.blade.php ENDPATH**/ ?>