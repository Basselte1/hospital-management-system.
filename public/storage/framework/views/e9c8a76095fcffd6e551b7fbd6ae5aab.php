
<?php $__env->startSection('title', 'CMCU | Détails Stérilisation'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-info">Stérilisation <?php echo e($sterilization->numero_lot); ?></h1>
                <hr class="w-25 mx-auto">
                <span class="badge bg-<?php echo e($sterilization->getStatutBadgeColor()); ?>" style="font-size: 1.1rem;">
                    <?php echo e($sterilization->getStatutLabel()); ?>

                </span>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(session('warning')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="<?php echo e(route('reusable-products.sterilizations.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>

                <?php if($sterilization->isRetourne()): ?>
                <a href="<?php echo e(route('reusable-products.sterilizations.certificate', $sterilization->id)); ?>" 
                   class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Certificat PDF
                </a>
                <?php endif; ?>

                <?php if($sterilization->isEnCours()): ?>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#completeModal">
                    <i class="fas fa-check"></i> Marquer comme Terminé
                </button>
                <?php endif; ?>

                <?php if($sterilization->isTermine() && in_array(auth()->user()->role_id, [1, 7])): ?>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#validateModal">
                    <i class="fas fa-check-double"></i> Valider
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times"></i> Rejeter
                </button>
                <?php endif; ?>

                <?php if($sterilization->isValide() && in_array(auth()->user()->role_id, [1, 7])): ?>
                <form action="<?php echo e(route('reusable-products.sterilizations.return-to-stock', $sterilization->id)); ?>" 
                      method="POST" 
                      style="display: inline;"
                      onsubmit="return confirm('Confirmer le retour de <?php echo e($sterilization->quantite); ?> unité(s) au stock ?');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-undo"></i> Retourner au Stock
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-6">
                <!-- Product Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Produit</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Désignation:</th>
                                <td><strong><?php echo e($sterilization->produit->designation); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Catégorie:</th>
                                <td><span class="badge bg-secondary"><?php echo e($sterilization->produit->categorie); ?></span></td>
                            </tr>
                            <tr>
                                <th>Quantité Stérilisée:</th>
                                <td><span class="badge bg-primary"><?php echo e($sterilization->quantite); ?> unité(s)</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Sterilization Parameters -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Paramètres</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">N° Lot:</th>
                                <td><strong><?php echo e($sterilization->numero_lot); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Méthode:</th>
                                <td><?php echo e($sterilization->getMethodeLabel()); ?></td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td><?php echo e($sterilization->date_sterilisation->format('d/m/Y')); ?></td>
                            </tr>
                            <?php if($sterilization->heure_debut): ?>
                            <tr>
                                <th>Heure Début:</th>
                                <td><?php echo e($sterilization->heure_debut); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($sterilization->heure_fin): ?>
                            <tr>
                                <th>Heure Fin:</th>
                                <td><?php echo e($sterilization->heure_fin); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($sterilization->temperature): ?>
                            <tr>
                                <th>Température:</th>
                                <td><?php echo e($sterilization->temperature); ?>°C</td>
                            </tr>
                            <?php endif; ?>
                            <?php if($sterilization->duree_minutes): ?>
                            <tr>
                                <th>Durée:</th>
                                <td><?php echo e($sterilization->duree_minutes); ?> minutes</td>
                            </tr>
                            <?php endif; ?>
                            <?php if($sterilization->type_indicateur): ?>
                            <tr>
                                <th>Indicateur:</th>
                                <td><?php echo e($sterilization->type_indicateur); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <!-- Quality Control -->
                <?php if(!$sterilization->isEnCours()): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="fas fa-check-circle"></i> Contrôle Qualité</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Résultat Test:</th>
                                <td>
                                    <?php if($sterilization->resultat_test === 'conforme'): ?>
                                    <span class="badge bg-success">Conforme</span>
                                    <?php elseif($sterilization->resultat_test === 'non_conforme'): ?>
                                    <span class="badge bg-danger">Non Conforme</span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">En Attente</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column -->
            <div class="col-lg-6">
                <!-- Personnel Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-users"></i> Personnel</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Stérilisé par:</th>
                                <td><?php echo e($sterilization->sterilisePar->name); ?></td>
                            </tr>
                            <?php if($sterilization->verifie_par): ?>
                            <tr>
                                <th>Vérifié par:</th>
                                <td><?php echo e($sterilization->verifiePar->name); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($sterilization->retourne_par): ?>
                            <tr>
                                <th>Retourné par:</th>
                                <td><?php echo e($sterilization->retournePar->name); ?></td>
                            </tr>
                            <tr>
                                <th>Date Retour:</th>
                                <td><?php echo e($sterilization->retourne_at->format('d/m/Y H:i')); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <!-- Observations -->
                <?php if($sterilization->observations || $sterilization->raison_rejet): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Remarques</h5>
                    </div>
                    <div class="card-body">
                        <?php if($sterilization->observations): ?>
                        <div class="mb-3">
                            <strong>Observations:</strong>
                            <p class="mb-0"><?php echo e($sterilization->observations); ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if($sterilization->raison_rejet): ?>
                        <div class="alert alert-danger mb-0">
                            <strong>Raison du Rejet:</strong>
                            <p class="mb-0"><?php echo e($sterilization->raison_rejet); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Timeline -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Chronologie</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <i class="fas fa-plus-circle text-info"></i>
                                <div>
                                    <strong>Créée</strong>
                                    <small class="d-block text-muted">
                                        <?php echo e($sterilization->created_at->format('d/m/Y H:i')); ?>

                                    </small>
                                </div>
                            </div>

                            <?php if(!$sterilization->isEnCours()): ?>
                            <div class="timeline-item">
                                <i class="fas fa-check text-warning"></i>
                                <div>
                                    <strong>Terminée</strong>
                                    <small class="d-block text-muted">
                                        <?php echo e($sterilization->updated_at->format('d/m/Y H:i')); ?>

                                    </small>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if($sterilization->verifie_par): ?>
                            <div class="timeline-item">
                                <i class="fas fa-check-double text-success"></i>
                                <div>
                                    <strong><?php echo e($sterilization->isRejete() ? 'Rejetée' : 'Validée'); ?></strong>
                                    <small class="d-block text-muted">
                                        Par <?php echo e($sterilization->verifiePar->name); ?>

                                    </small>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if($sterilization->retourne_at): ?>
                            <div class="timeline-item">
                                <i class="fas fa-undo text-primary"></i>
                                <div>
                                    <strong>Retournée au Stock</strong>
                                    <small class="d-block text-muted">
                                        <?php echo e($sterilization->retourne_at->format('d/m/Y H:i')); ?>

                                    </small>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Complete Modal -->
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Marquer comme Terminé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('reusable-products.sterilizations.complete', $sterilization->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Heure de Fin</label>
                        <input type="time" class="form-control" name="heure_fin" value="<?php echo e(date('H:i')); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Résultat du Test <span class="text-danger">*</span></label>
                        <select class="form-select" name="resultat_test" required>
                            <option value="">Sélectionner...</option>
                            <option value="conforme">Conforme</option>
                            <option value="non_conforme">Non Conforme</option>
                            <option value="en_attente">En Attente</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observations</label>
                        <textarea class="form-control" name="observations" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-check"></i> Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Validate Modal -->
<div class="modal fade" id="validateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Valider la Stérilisation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('reusable-products.sterilizations.validate', $sterilization->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="valider">
                <div class="modal-body">
                    <p>Confirmer que la stérilisation du lot <strong><?php echo e($sterilization->numero_lot); ?></strong> est conforme et peut être retournée au stock ?</p>
                    <div class="alert alert-success">
                        <strong><?php echo e($sterilization->quantite); ?></strong> unité(s) seront prêtes pour retour au stock.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-double"></i> Valider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Rejeter la Stérilisation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('reusable-products.sterilizations.validate', $sterilization->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="rejeter">
                <div class="modal-body">
                    <p>La stérilisation a échoué. Les produits seront marqués comme perdus.</p>
                    <div class="mb-3">
                        <label class="form-label">Raison du Rejet <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="raison_rejet" rows="3" required></textarea>
                    </div>
                    <div class="alert alert-danger">
                        <strong>Attention:</strong> Les <?php echo e($sterilization->quantite); ?> unité(s) ne seront PAS retournées au stock.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Confirmer le Rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
    border-left: 2px solid #dee2e6;
}
.timeline-item:last-child {
    border-left: none;
}
.timeline-item i {
    position: absolute;
    left: -10px;
    background: white;
    padding: 2px;
}
.timeline-item > div {
    margin-left: 15px;
}
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/reusable_products/sterilization_show.blade.php ENDPATH**/ ?>