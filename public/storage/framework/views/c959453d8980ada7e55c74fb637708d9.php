
<?php $__env->startSection('title', 'CMCU | Historique Pharmacie'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-history"></i> Historique des Ventes - Pharmacie
                </h1>
                <p class="text-muted">Rapport détaillé des transactions pharmaceutiques</p>
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('pharmacie.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <!-- Statistics Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Ventes</h6>
                                <h3 class="mb-0"><?php echo e($stats['total_ventes']); ?></h3>
                            </div>
                            <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Montant Total</h6>
                                <h3 class="mb-0"><?php echo e(number_format($stats['total_montant'])); ?> FCFA</h3>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Ventes Soldées</h6>
                                <h3 class="mb-0"><?php echo e($stats['total_soldees']); ?></h3>
                                <small><?php echo e($stats['total_ventes'] > 0 ? number_format(($stats['total_soldees'] / $stats['total_ventes']) * 100, 1) : 0); ?>%</small>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">En Attente</h6>
                                <h3 class="mb-0"><?php echo e($stats['total_en_attente']); ?></h3>
                                <small><?php echo e($stats['total_ventes'] > 0 ? number_format(($stats['total_en_attente'] / $stats['total_ventes']) * 100, 1) : 0); ?>%</small>
                            </div>
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Répartition par Type</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-primary me-2">Patient</span>
                                    <strong><?php echo e($stats['ventes_patients']); ?></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-success me-2">Externe</span>
                                    <strong><?php echo e($stats['ventes_externes']); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Période</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Du:</strong> <?php echo e(\Carbon\Carbon::parse($dateFrom)->format('d/m/Y')); ?>

                            </div>
                            <div>
                                <strong>Au:</strong> <?php echo e(\Carbon\Carbon::parse($dateTo)->format('d/m/Y')); ?>

                            </div>
                            <div>
                                <strong><?php echo e(\Carbon\Carbon::parse($dateFrom)->diffInDays(\Carbon\Carbon::parse($dateTo)) + 1); ?> jours</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtres de Recherche</h5>
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="filterCollapse">
                        <div class="card-body">
                            <form method="GET" action="<?php echo e(route('pharmacie.history')); ?>" id="filter-form">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Date Début</label>
                                        <input type="date" 
                                               name="date_from" 
                                               class="form-control" 
                                               value="<?php echo e($dateFrom); ?>"
                                               max="<?php echo e(now()->toDateString()); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date Fin</label>
                                        <input type="date" 
                                               name="date_to" 
                                               class="form-control" 
                                               value="<?php echo e($dateTo); ?>"
                                               max="<?php echo e(now()->toDateString()); ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Type de Vente</label>
                                        <select name="type_vente" class="form-control">
                                            <option value="">Tous</option>
                                            <option value="patient" <?php echo e(request('type_vente') == 'patient' ? 'selected' : ''); ?>>Patient</option>
                                            <option value="client_externe" <?php echo e(request('type_vente') == 'client_externe' ? 'selected' : ''); ?>>Externe</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Statut Paiement</label>
                                        <select name="statut_paiement" class="form-control">
                                            <option value="">Tous</option>
                                            <option value="soldee" <?php echo e(request('statut_paiement') == 'soldee' ? 'selected' : ''); ?>>Soldée</option>
                                            <option value="en_attente" <?php echo e(request('statut_paiement') == 'en_attente' ? 'selected' : ''); ?>>En attente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-search"></i> Filtrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quick Date Filters -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary quick-date" data-period="today">Aujourd'hui</button>
                                            <button type="button" class="btn btn-outline-secondary quick-date" data-period="yesterday">Hier</button>
                                            <button type="button" class="btn btn-outline-secondary quick-date" data-period="week">Cette semaine</button>
                                            <button type="button" class="btn btn-outline-secondary quick-date" data-period="month">Ce mois</button>
                                            <button type="button" class="btn btn-outline-secondary quick-date" data-period="last-month">Mois dernier</button>
                                            <button type="button" class="btn btn-outline-danger" id="reset-filters">Réinitialiser</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <?php if($ventes->count() > 0): ?>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Liste des Ventes (<?php echo e($ventes->count()); ?> résultats)</h5>
                        <div class="btn-group btn-group-sm">
                            <button onclick="window.print()" class="btn btn-outline-primary">
                                <i class="fas fa-print"></i> Imprimer
                            </button>
                            <button onclick="exportToExcel()" class="btn btn-outline-success">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm" id="history-table">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>N° Vente</th>
                                        <th>Date/Heure</th>
                                        <th>Type</th>
                                        <th>Client</th>
                                        <th>Pharmacien</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Caissier</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $ventes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong><?php echo e($vente->numero_vente); ?></strong></td>
                                        <td>
                                            <small><?php echo e($vente->created_at->format('d/m/Y')); ?></small><br>
                                            <small class="text-muted"><?php echo e($vente->created_at->format('H:i')); ?></small>
                                        </td>
                                        <td>
                                            <?php if($vente->isPatientSale()): ?>
                                            <span class="badge bg-primary">Patient</span>
                                            <?php else: ?>
                                            <span class="badge bg-success">Externe</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($vente->isPatientSale()): ?>
                                                <strong><?php echo e($vente->patient->name ?? 'N/A'); ?></strong><br>
                                                <small class="text-muted"><?php echo e($vente->patient->numero_dossier ?? ''); ?></small>
                                            <?php else: ?>
                                                <strong><?php echo e($vente->client->nom ?? 'N/A'); ?></strong><br>
                                                <small class="text-muted"><?php echo e($vente->client->telephone ?? ''); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?php echo e($vente->pharmacien->name ?? 'N/A'); ?></small>
                                        </td>
                                        <td>
                                            <strong><?php echo e(number_format($vente->montant_total)); ?> FCFA</strong>
                                        </td>
                                        <td>
                                            <?php if($vente->isSoldee()): ?>
                                            <span class="badge bg-success">Soldée</span><br>
                                            <small class="text-muted"><?php echo e($vente->date_paiement ? $vente->date_paiement->format('d/m/Y') : ''); ?></small>
                                            <?php else: ?>
                                            <span class="badge bg-warning text-dark">En attente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($vente->caissier): ?>
                                            <small><?php echo e($vente->caissier->name); ?></small>
                                            <?php else: ?>
                                            <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo e(route('pharmacie.sales.show', $vente->id)); ?>" 
                                                   class="btn btn-outline-primary btn-sm"
                                                   title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="5" class="text-end">TOTAL:</td>
                                        <td><?php echo e(number_format($ventes->sum('montant_total'))); ?> FCFA</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Top 10 Produits Vendus</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Quantité</th>
                                        <th class="text-end">CA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $topProducts = collect();
                                    foreach($ventes as $vente) {
                                        foreach($vente->items as $item) {
                                            $key = $item->designation;
                                            if ($topProducts->has($key)) {
                                                $existing = $topProducts->get($key);
                                                $topProducts->put($key, [
                                                    'designation' => $item->designation,
                                                    'quantite' => $existing['quantite'] + $item->quantite,
                                                    'montant' => $existing['montant'] + $item->montant_ligne
                                                ]);
                                            } else {
                                                $topProducts->put($key, [
                                                    'designation' => $item->designation,
                                                    'quantite' => $item->quantite,
                                                    'montant' => $item->montant_ligne
                                                ]);
                                            }
                                        }
                                    }
                                    $topProducts = $topProducts->sortByDesc('montant')->take(10);
                                    ?>
                                    
                                    <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($product['designation']); ?></td>
                                        <td class="text-center"><?php echo e($product['quantite']); ?></td>
                                        <td class="text-end"><?php echo e(number_format($product['montant'])); ?> FCFA</td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune vente trouvée</h5>
                        <p class="text-muted">Essayez de modifier les filtres de recherche</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>

waitForjQuery(function() {
$(document).ready(function() {
    
    // Quick date filters
    $('.quick-date').click(function() {
        let period = $(this).data('period');
        let today = new Date();
        let dateFrom, dateTo;
        
        switch(period) {
            case 'today':
                dateFrom = dateTo = formatDate(today);
                break;
            case 'yesterday':
                let yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                dateFrom = dateTo = formatDate(yesterday);
                break;
            case 'week':
                let weekStart = new Date(today);
                weekStart.setDate(today.getDate() - today.getDay());
                dateFrom = formatDate(weekStart);
                dateTo = formatDate(today);
                break;
            case 'month':
                dateFrom = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
                dateTo = formatDate(today);
                break;
            case 'last-month':
                let lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                let lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
                dateFrom = formatDate(lastMonth);
                dateTo = formatDate(lastMonthEnd);
                break;
        }
        
        $('input[name="date_from"]').val(dateFrom);
        $('input[name="date_to"]').val(dateTo);
        $('#filter-form').submit();
    });
    
    // Reset filters
    $('#reset-filters').click(function() {
        window.location.href = '<?php echo e(route("pharmacie.history")); ?>';
    });
    
    function formatDate(date) {
        let year = date.getFullYear();
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
});

// Export to Excel
function exportToExcel() {
    let table = document.getElementById('history-table');
    if (!table) {
        alert('Aucune donnée à exporter');
        return;
    }
    let html = table.outerHTML;
    let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    let downloadLink = document.createElement("a");
    downloadLink.href = url;
    downloadLink.download = 'historique_pharmacie_' + new Date().toISOString().split('T')[0] + '.xls';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

});
</script>

<style>
@media print {
    .wrapper aside,
    .wrapper header,
    .btn-group,
    #filter-form,
    .card-header button,
    #filterCollapse {
        display: none !important;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    table {
        font-size: 10pt;
    }
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/pharmacie/history.blade.php ENDPATH**/ ?>