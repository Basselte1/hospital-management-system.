
<?php $__env->startSection('title', 'CMCU | Nouveau Bon de Commande'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">Nouveau Bon de Commande</h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <!-- Form -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <form action="<?php echo e(route('bon-commandes.store')); ?>" method="POST" id="bonCommandeForm">
                            <?php echo csrf_field(); ?>

                            <!-- Supplier Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-truck"></i> Informations Fournisseur</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fournisseur_nom" class="form-label">
                                                Nom du Fournisseur <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control <?php $__errorArgs = ['fournisseur_nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="fournisseur_nom" 
                                                   name="fournisseur_nom" 
                                                   value="<?php echo e(old('fournisseur_nom')); ?>" 
                                                   required>
                                            <?php $__errorArgs = ['fournisseur_nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="fournisseur_email" class="form-label">Email</label>
                                            <input type="email" 
                                                   class="form-control <?php $__errorArgs = ['fournisseur_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="fournisseur_email" 
                                                   name="fournisseur_email" 
                                                   value="<?php echo e(old('fournisseur_email')); ?>">
                                            <?php $__errorArgs = ['fournisseur_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="fournisseur_telephone" class="form-label">Téléphone</label>
                                            <input type="text" 
                                                   class="form-control <?php $__errorArgs = ['fournisseur_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="fournisseur_telephone" 
                                                   name="fournisseur_telephone" 
                                                   value="<?php echo e(old('fournisseur_telephone')); ?>">
                                            <?php $__errorArgs = ['fournisseur_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="fournisseur_adresse" class="form-label">Adresse</label>
                                            <input type="text" 
                                                   class="form-control <?php $__errorArgs = ['fournisseur_adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="fournisseur_adresse" 
                                                   name="fournisseur_adresse" 
                                                   value="<?php echo e(old('fournisseur_adresse')); ?>">
                                            <?php $__errorArgs = ['fournisseur_adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-calendar"></i> Informations Commande</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="date_commande" class="form-label">
                                                Date de Commande <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" 
                                                   class="form-control <?php $__errorArgs = ['date_commande'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="date_commande" 
                                                   name="date_commande" 
                                                   value="<?php echo e(old('date_commande', date('Y-m-d'))); ?>" 
                                                   required>
                                            <?php $__errorArgs = ['date_commande'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="date_livraison_souhaitee" class="form-label">
                                                Date de Livraison Souhaitée
                                            </label>
                                            <input type="date" 
                                                   class="form-control <?php $__errorArgs = ['date_livraison_souhaitee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="date_livraison_souhaitee" 
                                                   name="date_livraison_souhaitee" 
                                                   value="<?php echo e(old('date_livraison_souhaitee')); ?>">
                                            <?php $__errorArgs = ['date_livraison_souhaitee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="notes" class="form-label">Notes / Remarques</label>
                                            <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                      id="notes" 
                                                      name="notes" 
                                                      rows="3"><?php echo e(old('notes')); ?></textarea>
                                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Products List -->
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Produits à Commander</h5>
                                    <button type="button" class="btn btn-light btn-sm" id="addItemBtn">
                                        <i class="fas fa-plus"></i> Ajouter un produit
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="itemsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="30%">Désignation <span class="text-danger">*</span></th>
                                                    <th width="15%">Catégorie <span class="text-danger">*</span></th>
                                                    <th width="15%">Quantité <span class="text-danger">*</span></th>
                                                    <th width="15%">Prix Unitaire <span class="text-danger">*</span></th>
                                                    <th width="15%">Montant</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="itemsBody">
                                                <!-- Items will be added here dynamically -->
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-info">
                                                    <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                                    <td colspan="2">
                                                        <strong id="totalAmount">0 FCFA</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Enregistrer le Bon de Commande
                                    </button>
                                    <a href="<?php echo e(route('bon-commandes.index')); ?>" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Product Selection Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sélectionner un Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="searchProduct" placeholder="Rechercher un produit...">
                <div class="table-responsive">
                    <table class="table table-hover" id="productsTable">
                        <thead>
                            <tr>
                                <th>Désignation</th>
                                <th>Catégorie</th>
                                <th>Stock</th>
                                <th>Prix</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($produit->designation); ?></td>
                                <td><?php echo e($produit->categorie); ?></td>
                                <td><?php echo e($produit->qte_stock); ?></td>
                                <td><?php echo e(number_format($produit->prix_unitaire, 0, ',', ' ')); ?></td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-primary select-product"
                                            data-id="<?php echo e($produit->id); ?>"
                                            data-designation="<?php echo e($produit->designation); ?>"
                                            data-categorie="<?php echo e($produit->categorie); ?>"
                                            data-prix="<?php echo e($produit->prix_unitaire); ?>">
                                        Sélectionner
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<?php $__env->startPush('scripts'); ?>
<script>

waitForjQuery(function() {

let itemCounter = 0;
let currentRow = null;


$(document).ready(function() {
    // Add first item automatically
    addItem();

    // Add item button
    $('#addItemBtn').click(function() {
        addItem();
    });

    // Show product modal when clicking on designation input
    $(document).on('click', '.designation-input', function() {
        currentRow = $(this).closest('tr');
        $('#productModal').modal('show');
    });

    // Select product from modal
    $('.select-product').click(function() {
        const produitId = $(this).data('id');
        const designation = $(this).data('designation');
        const categorie = $(this).data('categorie');
        const prix = $(this).data('prix');

        if (currentRow) {
            currentRow.find('.produit-id').val(produitId);
            currentRow.find('.designation-input').val(designation);
            currentRow.find('.categorie-select').val(categorie);
            currentRow.find('.prix-input').val(prix);
            currentRow.find('.quantite-input').trigger('input');
        }

        $('#productModal').modal('hide');
    });

    // Search products in modal
    $('#searchProduct').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#productsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Calculate line total on quantity or price change
    $(document).on('input', '.quantite-input, .prix-input', function() {
        calculateLineTotal($(this).closest('tr'));
        calculateGrandTotal();
    });

    // Remove item
    $(document).on('click', '.remove-item', function() {
        if ($('#itemsBody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        } else {
            alert('Vous devez avoir au moins un produit dans la commande');
        }
    });
});

function addItem() {
    itemCounter++;
    const row = `
        <tr>
            <td>
                <input type="hidden" name="items[${itemCounter}][produit_id]" class="produit-id">
                <input type="text" 
                       class="form-control designation-input" 
                       name="items[${itemCounter}][designation]" 
                       placeholder="Cliquez pour sélectionner..." 
                       required
                       readonly>
            </td>
            <td>
                <select class="form-select categorie-select" name="items[${itemCounter}][categorie]" required>
                    <option value="PHARMACEUTIQUE">PHARMACEUTIQUE</option>
                    <option value="MATERIEL">MATERIEL</option>
                    <option value="ANESTHESISTE">ANESTHESISTE</option>
                </select>
            </td>
            <td>
                <input type="number" 
                       class="form-control quantite-input" 
                       name="items[${itemCounter}][quantite]" 
                       min="1" 
                       value="1" 
                       required>
            </td>
            <td>
                <input type="number" 
                       class="form-control prix-input" 
                       name="items[${itemCounter}][prix_unitaire]" 
                       min="0" 
                       value="0" 
                       required>
            </td>
            <td class="montant-cell">0 FCFA</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    $('#itemsBody').append(row);
}

function calculateLineTotal(row) {
    const quantite = parseInt(row.find('.quantite-input').val()) || 0;
    const prix = parseInt(row.find('.prix-input').val()) || 0;
    const total = quantite * prix;
    row.find('.montant-cell').text(total.toLocaleString('fr-FR') + ' FCFA');
}

function calculateGrandTotal() {
    let grandTotal = 0;
    $('#itemsBody tr').each(function() {
        const quantite = parseInt($(this).find('.quantite-input').val()) || 0;
        const prix = parseInt($(this).find('.prix-input').val()) || 0;
        grandTotal += (quantite * prix);
    });
    $('#totalAmount').text(grandTotal.toLocaleString('fr-FR') + ' FCFA');
}


});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/bon_commandes/create.blade.php ENDPATH**/ ?>