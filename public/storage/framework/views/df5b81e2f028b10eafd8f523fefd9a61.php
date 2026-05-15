
<?php $__env->startSection('title', 'CMCU | Réception de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">Réceptionner Bon de Commande</h1>
                <hr class="w-25 mx-auto">
                <p class="text-muted"><?php echo e($bonCommande->numero_bon); ?></p>
            </div>
        </div>

        <!-- Bon Commande Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations du Bon de Commande</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Fournisseur:</strong> <?php echo e($bonCommande->fournisseur_nom); ?></p>
                        <p><strong>Date Commande:</strong> <?php echo e($bonCommande->date_commande->format('d/m/Y')); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Montant Total:</strong> <?php echo e(number_format($bonCommande->montant_total, 0, ',', ' ')); ?> FCFA</p>
                        <p><strong>Statut:</strong> <span class="badge bg-success">Validé</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reception Form -->
        <form action="<?php echo e(route('stock-receptions.store')); ?>" method="POST" id="receptionForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="bon_commande_id" value="<?php echo e($bonCommande->id); ?>">

            <!-- Reception Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Détails de la Réception</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date de Réception <span class="text-danger">*</span></label>
                                <input type="date" name="date_reception" class="form-control" 
                                       value="<?php echo e(old('date_reception', date('Y-m-d'))); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Numéro BL</label>
                                <input type="text" name="numero_bl" class="form-control" 
                                       value="<?php echo e(old('numero_bl')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nom du Livreur</label>
                                <input type="text" name="livreur_nom" class="form-control" 
                                       value="<?php echo e(old('livreur_nom')); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Téléphone Livreur</label>
                                <input type="text" name="livreur_telephone" class="form-control" 
                                       value="<?php echo e(old('livreur_telephone')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Condition de Livraison</label>
                                <select name="condition_livraison" class="form-select">
                                    <option value="">Sélectionner...</option>
                                    <option value="excellente">Excellente</option>
                                    <option value="bonne">Bonne</option>
                                    <option value="acceptable">Acceptable</option>
                                    <option value="mauvaise">Mauvaise</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Commentaire Général</label>
                                <textarea name="commentaire" class="form-control" rows="2"><?php echo e(old('commentaire')); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Reception -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Produits à Réceptionner</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Désignation</th>
                                    <th>Catégorie</th>
                                    <th>Qté Commandée</th>
                                    <th>Qté Reçue</th>
                                    <th>État</th>
                                    <th>Produit Existant</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $bonCommande->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="product-row">
                                    <td>
                                        <strong><?php echo e($item->designation); ?></strong>
                                        <input type="hidden" name="items[<?php echo e($index); ?>][bon_commande_item_id]" value="<?php echo e($item->id); ?>">
                                    </td>
                                    <td><?php echo e($item->categorie); ?></td>
                                    <td class="text-center"><?php echo e($item->quantite_commandee); ?></td>
                                    <td>
                                        <input type="number" 
                                               name="items[<?php echo e($index); ?>][quantite_recue]" 
                                               class="form-control form-control-sm qte-recue" 
                                               min="0" 
                                               max="<?php echo e($item->quantite_commandee); ?>"
                                               value="<?php echo e($item->quantite_commandee); ?>" 
                                               required>
                                    </td>
                                    <td>
                                        <select name="items[<?php echo e($index); ?>][etat_produit]" 
                                                class="form-select form-select-sm etat-produit" required>
                                            <option value="conforme">Conforme</option>
                                            <option value="non_conforme">Non Conforme</option>
                                            <option value="endommage">Endommagé</option>
                                        </select>
                                    </td>
                                    <td>
                                        <?php if($productsStatus[$item->id]['exists']): ?>
                                            <div class="alert alert-success alert-sm mb-0 p-2">
                                                <i class="fas fa-check-circle"></i> 
                                                <strong><?php echo e($productsStatus[$item->id]['product']->designation); ?></strong>
                                                <br>
                                                <small>Stock actuel: <?php echo e($productsStatus[$item->id]['product']->qte_stock); ?></small>
                                                <input type="hidden" name="items[<?php echo e($index); ?>][existing_product_id]" 
                                                       value="<?php echo e($productsStatus[$item->id]['product']->id); ?>">
                                                <input type="hidden" name="items[<?php echo e($index); ?>][create_new_product]" value="0">
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-warning alert-sm mb-0 p-2">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                <strong>Nouveau produit</strong>
                                                <input type="hidden" name="items[<?php echo e($index); ?>][create_new_product]" value="1">
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailsModal<?php echo e($index); ?>">
                                            <i class="fas fa-edit"></i> Détails
                                        </button>
                                    </td>
                                </tr>

                                <!-- Details Modal -->
                                <div class="modal fade" id="detailsModal<?php echo e($index); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Détails: <?php echo e($item->designation); ?></h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Quantité Acceptée</label>
                                                            <input type="number" 
                                                                   name="items[<?php echo e($index); ?>][quantite_acceptee]" 
                                                                   class="form-control" 
                                                                   min="0">
                                                            <small class="text-muted">Laisser vide si toute la quantité reçue est acceptée</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Quantité Refusée</label>
                                                            <input type="number" 
                                                                   name="items[<?php echo e($index); ?>][quantite_refusee]" 
                                                                   class="form-control" 
                                                                   min="0" 
                                                                   value="0">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Date de Péremption</label>
                                                            <input type="date" 
                                                                   name="items[<?php echo e($index); ?>][date_peremption]" 
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Numéro de Lot</label>
                                                            <input type="text" 
                                                                   name="items[<?php echo e($index); ?>][numero_lot]" 
                                                                   class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Observation</label>
                                                            <textarea name="items[<?php echo e($index); ?>][observation]" 
                                                                      class="form-control" 
                                                                      rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if(!$productsStatus[$item->id]['exists']): ?>
                                                <hr>
                                                <h6 class="text-warning"><i class="fas fa-plus-circle"></i> Informations du Nouveau Produit</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                                            <select name="items[<?php echo e($index); ?>][new_product_categorie]" 
                                                                    class="form-select" required>
                                                                <option value="">Sélectionner...</option>
                                                                <option value="PHARMACEUTIQUE" <?php echo e($item->categorie == 'PHARMACEUTIQUE' ? 'selected' : ''); ?>>PHARMACEUTIQUE</option>
                                                                <option value="MATERIEL" <?php echo e($item->categorie == 'MATERIEL' ? 'selected' : ''); ?>>MATERIEL MEDICAL</option>
                                                                <option value="ANESTHESISTE" <?php echo e($item->categorie == 'ANESTHESISTE' ? 'selected' : ''); ?>>ANESTHESISTE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Prix Unitaire (FCFA) <span class="text-danger">*</span></label>
                                                            <input type="number" 
                                                                   name="items[<?php echo e($index); ?>][new_product_prix_unitaire]" 
                                                                   class="form-control" 
                                                                   min="0" 
                                                                   value="<?php echo e($item->prix_unitaire); ?>" 
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Quantité d'Alerte <span class="text-danger">*</span></label>
                                                            <input type="number" 
                                                                   name="items[<?php echo e($index); ?>][new_product_qte_alerte]" 
                                                                   class="form-control" 
                                                                   min="0" 
                                                                   value="10" 
                                                                   required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="text-center mb-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Enregistrer la Réception
                </button>
                <a href="<?php echo e(route('stock-receptions.ready')); ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>


<script>
waitForjQuery(function() {

$(document).ready(function() {
    // Validate quantities on form submit
    $('#receptionForm').submit(function(e) {
        let valid = true;
        
        $('.qte-recue').each(function() {
            const qty = parseInt($(this).val());
            if (isNaN(qty) || qty < 0) {
                valid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Veuillez vérifier les quantités saisies');
        }
    });

    // Auto-fill accepted quantity
    $('.qte-recue').change(function() {
        const row = $(this).closest('.product-row');
        const etat = row.find('.etat-produit').val();
        
        if (etat === 'conforme') {
            // Could auto-fill accepted quantity if needed
        }
    });
});


});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/stock_receptions/create.blade.php ENDPATH**/ ?>