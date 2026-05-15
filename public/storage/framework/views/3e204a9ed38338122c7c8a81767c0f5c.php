
<?php $__env->startSection('title', 'CMCU | Mes Patients Suivis'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* Styles pour la pagination */
.pagination-wrapper {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin: 0.5rem 0;
    padding: 0.5rem 0;
}

.pagination-wrapper .pagination {
    margin-bottom: 0;
    display: flex;
    list-style: none;
    padding-left: 0;
}

.pagination-wrapper .pagination li {
    margin: 0 2px;
}

.pagination-wrapper .pagination .page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.pagination-wrapper .pagination .page-link:hover {
    z-index: 2;
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination-wrapper .pagination .page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.pagination-wrapper .pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    cursor: not-allowed;
    background-color: #fff;
    border-color: #dee2e6;
}

.pagination-wrapper .pagination .page-link svg {
    width: 14px !important;
    height: 14px !important;
    vertical-align: middle;
}

.pagination-results {
    text-align: left;
    color: #6c757d;
    font-size: 0.875rem;
    margin: 0.5rem 0;
}

.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
}

/* Style pour la barre de recherche */
.search-bar {
    background-color: #f8f9fa;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.search-bar .form-control {
    border-radius: 20px;
    padding: 0.5rem 1rem;
}

.search-bar .btn {
    border-radius: 20px;
    padding: 0.5rem 1.5rem;
}

.clear-search {
    border-radius: 20px;
}
</style>

<body>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-3">
                <h2 class="text-center">
                    <i class="fas fa-user-check"></i> Mes Patients Suivis
                </h2>
                <p class="text-muted text-center">Liste des patients que vous avez consultés</p>
            </div>
        </div>
        <hr>
        
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> 
                            Total: <?php echo e($patients->total()); ?> patient(s)
                        </h5>
                    </div>
                    
                    <!-- Barre de recherche -->
                    <div class="search-bar">
                        <form method="GET" action="<?php echo e(route('patients.suivis')); ?>" id="searchForm">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" 
                                               name="search" 
                                               class="form-control" 
                                               placeholder="Rechercher par nom, prénom ou n° dossier..." 
                                               value="<?php echo e(request('search')); ?>"
                                               id="searchInput">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search"></i> Rechercher
                                    </button>
                                    <?php if(request('search')): ?>
                                        <a href="<?php echo e(route('patients.suivis')); ?>" class="btn btn-secondary clear-search">
                                            <i class="fas fa-times"></i> Effacer
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <?php if($patients->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Dossier</th>
                                        <th>Nom & Prénom</th>
                                        <th>Téléphone</th>
                                        <th>Dernière Consultation</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary"><?php echo e($patient->numero_dossier); ?></span>
                                            <?php if($patient->isNew()): ?>
                                                <span class="badge bg-success ms-2 animate-pulse">NEW</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                                $telephone = $patient->user->telephone ?? null;
                                                if (!$telephone && $patient->dossiers->first()) {
                                                    $telephone = $patient->dossiers->first()->portable_1 ?: $patient->dossiers->first()->portable_2;
                                                }
                                            ?>
                                            <?php if($telephone): ?>
                                                <i class="fas fa-phone text-success"></i> <?php echo e($telephone); ?>

                                            <?php else: ?>
                                                <span class="text-muted">Non renseigné</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $lastConsultation = $patient->consultations->first();
                                                $lastConsultationAnes = $patient->consultation_anesthesistes->first();
                                                
                                                $mostRecent = null;
                                                if ($lastConsultation && $lastConsultationAnes) {
                                                    $mostRecent = $lastConsultation->created_at > $lastConsultationAnes->created_at 
                                                        ? $lastConsultation 
                                                        : $lastConsultationAnes;
                                                } else {
                                                    $mostRecent = $lastConsultation ?: $lastConsultationAnes;
                                                }
                                            ?>
                                            
                                            <?php if($mostRecent): ?>
                                                <span class="text-muted">
                                                    <i class="far fa-clock"></i>
                                                    <?php echo e($mostRecent->created_at->diffForHumans()); ?>

                                                </span>
                                                <br>
                                                <small class="text-secondary">
                                                    <?php echo e($mostRecent->created_at->format('d/m/Y')); ?>

                                                </small>
                                            <?php else: ?>
                                                <span class="text-warning">Aucune consultation</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($patient->consultations->count() > 0): ?>
                                                <span class="badge bg-success">Chirurgien</span>
                                            <?php endif; ?>
                                            <?php if($patient->consultation_anesthesistes->count() > 0): ?>
                                                <span class="badge bg-warning">Anesthésiste</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('patients.show', $patient->id)); ?>" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="Voir le dossier">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                                                    <?php if($patient->consultations->count() > 0): ?>
                                                    <a href="<?php echo e(route('consultations.edit', $patient->id)); ?>" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Modifier consultation">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                                                    <?php if($patient->consultation_anesthesistes->count() > 0): ?>
                                                    <a href="<?php echo e(route('consultations.edit', $patient->id)); ?>" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Modifier consultation anesthésiste">
                                                        <i class="fas fa-stethoscope"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination améliorée -->
                        <div class="pagination-container">
                            <div class="pagination-results">
                                Affichage de <?php echo e($patients->firstItem()); ?> à <?php echo e($patients->lastItem()); ?> 
                                sur <?php echo e($patients->total()); ?> résultat(s)
                            </div>
                            <div class="pagination-wrapper">
                                <?php echo e($patients->appends(request()->query())->links('pagination::bootstrap-4')); ?>

                            </div>
                        </div>
                        
                        <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <h5>
                                <?php if(request('search')): ?>
                                    Aucun résultat trouvé pour "<?php echo e(request('search')); ?>"
                                <?php else: ?>
                                    Aucun patient suivi
                                <?php endif; ?>
                            </h5>
                            <p class="mb-0">
                                <?php if(request('search')): ?>
                                    Essayez avec d'autres critères de recherche.
                                    <br>
                                    <a href="<?php echo e(route('patients.suivis')); ?>" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-arrow-left"></i> Voir tous les patients
                                    </a>
                                <?php else: ?>
                                    Vous n'avez pas encore de patients en suivi.
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Patients
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo e($patients->total()); ?>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Consultations Chirurgien
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo e($patients->filter(fn($p) => $p->consultations->count() > 0)->count()); ?>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-md fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Consultations Anesthésiste
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo e($patients->filter(fn($p) => $p->consultation_anesthesistes->count() > 0)->count()); ?>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Recherche en temps réel (optionnel - décommentez si vous voulez)
/*
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(function() {
        document.getElementById('searchForm').submit();
    }, 500);
});
*/
</script>

</body>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/patients_suivis/index.blade.php ENDPATH**/ ?>