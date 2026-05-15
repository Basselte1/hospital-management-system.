
<?php $__env->startSection('title', 'CMCU | Vente Externe'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-success">
                    <i class="fas fa-hospital"></i> Nouvelle Vente Externe
                </h1>
                <p class="text-muted">Vente à un autre hôpital ou client externe</p>
                <hr class="w-25 mx-auto">
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('pharmacie.index')); ?>" class="btn btn-success">
                    <i class="fas fa-arrow-left"></i> Retour à la pharmacie
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="<?php echo e(route('pharmacie.sales.external.store')); ?>" method="POST" id="external-sale-form">
                            <?php echo csrf_field(); ?>

                            <!-- Client Information -->
                            <div class="card mb-4 bg-light">
                                <div class="card-body">
                                    <h5 class="mb-3"><i class="fas fa-user"></i> Informations Client</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nom *</label>
                                            <input type="text" name="client_nom" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Prénom</label>
                                            <input type="text" name="client_prenom" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Institution/Hôpital</label>
                                            <input type="text" name="client_institution" class="form-control" 
                                                   placeholder="Ex: Hôpital Central de Yaoundé">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Products Section -->
                            <h5 class="mb-3"><i class="fas fa-pills"></i> Produits à Vendre</h5>

                            <div id="items-container">
                                <div class="card mb-2 item-row">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-5">
                                                <label class="small">Produit *</label>
                                                <select name="items[0][produit_id]" class="form-control product-select" required>
                                                    <option value="">Sélectionner un produit...</option>
                                                    <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($p->id); ?>" 
                                                            data-prix="<?php echo e($p->prix_unitaire); ?>" 
                                                            data-stock="<?php echo e($p->qte_stock); ?>">
                                                        <?php echo e($p->designation); ?> 
                                                        (Stock: <?php echo e($p->qte_stock); ?>) - 
                                                        <?php echo e(number_format($p->prix_unitaire)); ?> FCFA
                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small">Quantité *</label>
                                                <input type="number" 
                                                       name="items[0][quantite]" 
                                                       class="form-control quantity-input" 
                                                       value="1" 
                                                       min="1" 
                                                       required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small">Prix Unit.</label>
                                                <input type="text" 
                                                       class="form-control prix-display" 
                                                       readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small">Total</label>
                                                <input type="text" 
                                                       class="form-control total-display" 
                                                       readonly>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="small">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm w-100 remove-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success mb-3" id="add-item">
                                <i class="fas fa-plus"></i> Ajouter un Produit
                            </button>

                            <!-- Notes and Total -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label>Notes / Remarques</label>
                                            <textarea name="notes" 
                                                      class="form-control" 
                                                      rows="2"
                                                      placeholder="Ajouter des notes sur cette vente..."></textarea>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <h4>Total</h4>
                                            <h2 class="text-success" id="grand-total">0 FCFA</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Note:</strong> Une facture sera générée et devra être payée à la caisse avant la remise des produits.
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 text-end">
                                <a href="<?php echo e(route('pharmacie.index')); ?>" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Créer la Vente
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
waitForjQuery(function() {


let itemCounter = 1;

    $(document).ready(function() {
        // Add item
        $('#add-item').click(function() {
            let html = `
            <div class="card mb-2 item-row">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <label class="small">Produit *</label>
                            <select name="items[${itemCounter}][produit_id]" class="form-control product-select" required>
                                <option value="">Sélectionner...</option>
                                <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" data-prix="<?php echo e($p->prix_unitaire); ?>" data-stock="<?php echo e($p->qte_stock); ?>">
                                    <?php echo e($p->designation); ?> (Stock: <?php echo e($p->qte_stock); ?>) - <?php echo e(number_format($p->prix_unitaire)); ?> FCFA
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small">Quantité *</label>
                            <input type="number" name="items[${itemCounter}][quantite]" class="form-control quantity-input" value="1" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <label class="small">Prix Unit.</label>
                            <input type="text" class="form-control prix-display" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="small">Total</label>
                            <input type="text" class="form-control total-display" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="small">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm w-100 remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
            
            $('#items-container').append(html);
            itemCounter++;
        });

        // Remove item
        $(document).on('click', '.remove-item', function() {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
                calculateTotal();
            } else {
                alert('Il faut au moins un produit');
            }
        });

        // Product selection change
        $(document).on('change', '.product-select', function() {
            let selected = $(this).find(':selected');
            let prix = selected.data('prix');
            let stock = selected.data('stock');
            let row = $(this).closest('.item-row');
            
            row.find('.prix-display').val(prix ? prix.toLocaleString() : '');
            row.find('.quantity-input').attr('max', stock);
            calculateRowTotal(row);
        });

        // Quantity change
        $(document).on('input', '.quantity-input', function() {
            let row = $(this).closest('.item-row');
            let max = parseInt($(this).attr('max'));
            let val = parseInt($(this).val());
            
            if (val > max) {
                alert('Stock insuffisant! Maximum disponible: ' + max);
                $(this).val(max);
            }
            
            calculateRowTotal(row);
        });

        function calculateRowTotal(row) {
            let prix = parseInt(row.find('.product-select :selected').data('prix')) || 0;
            let qty = parseInt(row.find('.quantity-input').val()) || 0;
            let total = prix * qty;
            row.find('.total-display').val(total.toLocaleString());
            calculateTotal();
        }

        function calculateTotal() {
            let grandTotal = 0;
            $('.item-row').each(function() {
                let prix = parseInt($(this).find('.product-select :selected').data('prix')) || 0;
                let qty = parseInt($(this).find('.quantity-input').val()) || 0;
                grandTotal += prix * qty;
            });
            $('#grand-total').text(grandTotal.toLocaleString() + ' FCFA');
        }

        // Form validation
        $('#external-sale-form').submit(function(e) {
            let clientNom = $('input[name="client_nom"]').val().trim();
            
            if (!clientNom) {
                e.preventDefault();
                alert('Veuillez entrer le nom du client');
                $('input[name="client_nom"]').focus();
                return false;
            }

            let hasValidProduct = false;
            $('.product-select').each(function() {
                if ($(this).val()) {
                    hasValidProduct = true;
                }
            });

            if (!hasValidProduct) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un produit');
                return false;
            }
        });

        // Initial calculation
        calculateTotal();
    });
});

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/pharmacie/create_external_sale.blade.php ENDPATH**/ ?>