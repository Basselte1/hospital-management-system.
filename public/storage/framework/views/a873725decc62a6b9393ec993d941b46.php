 <?php $__env->startSection('title', 'CMCU | Liste des produits pharmaceutique'); ?> <?php $__env->startSection('content'); ?>

    <body>
    
    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="container">
                <h2 class="text-center">FACTURATION</h2>
                <div class="row"> 
                    <!-- <div class="row mb-3">
                
                        <div class="col-12">
                            <?php echo $__env->make('admin.patients.partials.menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <a href="<?php echo e(route('produits.pharmaceutique')); ?>" class="btn btn-success float-end" title="Retour à la liste des patients">
                                <i class="fas fa-arrow-left"></i> Retour au produits pharmaceutique
                            </a>
                        </div>
                    </div> -->
                    <div class="col-md-12 col-lg-10 offset-md-1">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th class="">Prix unitaire</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Reduire</th>
                                    <th class="text-center">Ajouter</th>
                                    <th class="text-center">Supprimer</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(Session::has('cart')): ?>
                                <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr data-product-id="<?php echo e($produit['item']['id']); ?>">
                                    <td class="col-md-8 col-lg-6">
                                        <div class="media-body">
                                            <p><?php echo e($produit['item']['designation']); ?></p>
                                        </div>
                                    </td>
                                    <td class="col-md-1 col-lg-1" style="text-align: center">
                                        <input 
                                            type="number" 
                                            class="form-control quantity-input" 
                                            min="1"
                                            value="<?php echo e($produit['quantite']); ?>"
                                            data-product-id="<?php echo e($produit['item']['id']); ?>"
                                            data-old-qty="<?php echo e($produit['quantite']); ?>"
                                        >
                                    </td>
                                    <td class="col-md-1 col-lg-1 text-center">
                                        <strong><?php echo e($produit['prix_unitaire']); ?></strong>
                                    </td>
                                    <td class="col-md-1 col-lg-1 text-center item-total">
                                        <strong>
                                            <?php echo e($produit['quantite'] * $produit['prix_unitaire']); ?>

                                        </strong>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('facturation.reduire', ['id' => $produit['item']['id']])); ?>" 
                                        class="btn btn-primary quantity-action" 
                                        title="Reduire la quantité">
                                            <i class="fas fa-minus"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('pharmaceutique.cart', $produit['item']['id'])); ?>" 
                                        class="btn btn-success quantity-action" 
                                        title="Ajouter la quantité">
                                            <i class="fas fa-plus-square"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('facturation.supprimer', ['id' => $produit['item']['id']])); ?>" 
                                        class="btn btn-danger quantity-action" 
                                        title="Supprimer le produit">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td><h3>Total</h3></td>
                                <td class="text-end">
                                    <h3><strong class="grand-total"><?php echo e($totalPrix); ?></strong></h3>
                                </td>
                            </tr>
                            <tr>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>
                                <td>&#xA0;</td>

                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <form action="<?php echo e(route('pharmacie.pdf')); ?>" method="post" class="mb-3">
                            <?php echo csrf_field(); ?>
                            <td>
                                <label for="patient"><b>Nom du patient :</b></label>
                                <select name="patient" id="patient" class="form-control col-md-5 mb-2">
                                    <option value="">Nom du patient</option>
                                    <?php $__currentLoopData = $patient; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patients): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($patients->name); ?>"><?php echo e($patients->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Produit::class)): ?>
                            <td>
                                <a href="<?php echo e(route('produits.pharmaceutique')); ?>" title="Retour à la liste des produits" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Ajouter des produits</a>
                            </td>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Produit::class)): ?>
                            <td>
                                <a href="<?php echo e(route('produits.anesthesiste')); ?>" title="Retour à la liste des produits" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Ajouter des produits</a>
                            </td>
                            <?php endif; ?>

                            <td>
                                <button type="submit" href="<?php echo e(route('pharmacie.pdf')); ?>" title="Imprimer la facture" class="btn btn-success float-end">Imprimer <i class="fas fa-print"></i></button>
                            </td>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

    </div>
    </div>
    <script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Persist selected patient
        const patientSelect = document.getElementById('patient');
        const savedPatient = localStorage.getItem('selectedPatient');
        
        if (savedPatient && patientSelect) {
            patientSelect.value = savedPatient;
        }
        
        if (patientSelect) {
            patientSelect.addEventListener('change', function() {
                localStorage.setItem('selectedPatient', this.value);
            });
        }

        // Handle quantity input changes
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.dataset.productId;
                const newQty = parseInt(this.value) || 1;
                const oldQty = parseInt(this.dataset.oldQty) || 1;
                
                if (newQty < 1) {
                    this.value = 1;
                    return;
                }
                
                // IMPORTANT: Don't clear the value before update completes
                updateQuantity(productId, newQty, oldQty, this);  // Pass the input element
            });
        });

        // Handle add/reduce buttons with AJAX
        document.querySelectorAll('.quantity-action').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.href;
                
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartDisplay(data);
                    } else {
                        alert(data.message || 'Erreur lors de la mise à jour');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Fallback to page reload if AJAX fails
                    window.location.href = url;
                });
            });
        });

    function updateQuantity(productId, newQty, oldQty, inputElement) {
        const diff = newQty - oldQty;
        const url = diff > 0 
            ? `/admin/pharmaceutiques/${productId}` 
            : `/admin/reduire/${productId}`;
        
        const requests = Math.abs(diff);
        let completed = 0;
        
        for (let i = 0; i < requests; i++) {
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                completed++;
                if (completed === requests && data.success) {
                    updateCartDisplay(data);
                    // Update the oldQty AFTER successful update
                    if (inputElement) {
                        inputElement.dataset.oldQty = newQty;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restore original value on error
                if (inputElement) {
                    inputElement.value = oldQty;
                }
            });
        }
    }
    
    
    function updateCartDisplay(data) {
        // Check if cart is empty
        if (data.cartEmpty) {
            window.location.reload();  // Reload to show empty cart message
            return;
        }
        
        // Update each product row
        Object.keys(data.items).forEach(id => {
            const item = data.items[id];
            const row = document.querySelector(`tr[data-product-id="${id}"]`);
            
            if (row) {
                const qtyInput = row.querySelector('.quantity-input');
                const itemTotal = row.querySelector('.item-total strong');
                
                // Update quantity
                if (qtyInput) {
                    qtyInput.value = item.qty;
                    qtyInput.dataset.oldQty = item.qty;
                }
                
                // Update item total
                if (itemTotal) {
                    itemTotal.textContent = item.price;
                }
            }
        });
        
        // Update grand total
        const grandTotal = document.querySelector('.grand-total');
        if (grandTotal) {
            grandTotal.textContent = data.totalPrix;
        }
        
        // Update badge count
        const badge = document.querySelector('.badge p');
        if (badge) {
            badge.textContent = data.totalQte;
        }
    }
    
    
    // In your Cart.php model
    public function reduceByOne($id) {
        if (isset($this->items[$id])) {
            $this->items[$id]['qty']--;
            $this->items[$id]['price'] -= $this->items[$id]['item']['prix_unitaire'];
            $this->totalQte--;
            $this->totalPrix -= $this->items[$id]['item']['prix_unitaire'];
            
            if ($this->items[$id]['qty'] <= 0) {
                unset($this->items[$id]);
            }
        }
    }

    // REMOVE these lines:
    const savedPatient = localStorage.getItem('selectedPatient');
    if (savedPatient && patientSelect) {
        patientSelect.value = savedPatient;
    }
    patientSelect.addEventListener('change', function() {
        localStorage.setItem('selectedPatient', this.value);
    });



    });
    </script>

    </body>

<?php $__env->stopSection(); ?>














<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/produits/facturation.blade.php ENDPATH**/ ?>