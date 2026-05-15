
<?php $__env->startSection('title', 'CMCU | Enregistrer Utilisation'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">Enregistrer Utilisation de Produit</h1>
                <hr class="w-25 mx-auto">
                <p class="text-muted">Enregistrer l'utilisation de produits réutilisables</p>
            </div>
        </div>

        <!-- Error/Success Messages -->
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <strong>Erreurs de validation:</strong>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Usage Form -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Formulaire d'Utilisation</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('reusable-products.record-usage.store')); ?>" method="POST" id="usageForm">
                            <?php echo csrf_field(); ?>

                            <!-- Product Selection -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-box"></i> Sélection du Produit
                                    </h6>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="produit_id" class="form-label">
                                        Produit <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" name="produit_id" id="produit_id" required>
                                        <option value="">-- Sélectionner un produit --</option>
                                        
                                        <?php
                                            $reusableProducts = $products->where('is_reusable', true);
                                            $nonReusableProducts = $products->where('is_reusable', false);
                                        ?>
                                        
                                        <?php if($reusableProducts->count() > 0): ?>
                                        <optgroup label="━━━━ PRODUITS RÉUTILISABLES ━━━━">
                                            <?php $__currentLoopData = $reusableProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $disponible = $produit->qte_stock - $produit->qte_en_utilisation - $produit->qte_en_sterilisation;
                                                ?>
                                                <option value="<?php echo e($produit->id); ?>" 
                                                        data-stock="<?php echo e($produit->qte_stock); ?>"
                                                        data-disponible="<?php echo e($disponible); ?>"
                                                        data-is-reusable="true"
                                                        <?php echo e(old('produit_id') == $produit->id ? 'selected' : ''); ?>>
                                                    <?php echo e($produit->designation); ?> 
                                                    (<?php echo e($produit->categorie); ?>) 
                                                    - Dispo: <?php echo e($disponible); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </optgroup>
                                        <?php endif; ?>

                                        <?php if($nonReusableProducts->count() > 0): ?>
                                        <optgroup label="━━━━ PRODUITS MATÉRIEL (Non Réutilisables) ━━━━">
                                            <?php $__currentLoopData = $nonReusableProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $disponible = $produit->qte_stock;
                                                ?>
                                                <option value="<?php echo e($produit->id); ?>" 
                                                        data-stock="<?php echo e($produit->qte_stock); ?>"
                                                        data-disponible="<?php echo e($disponible); ?>"
                                                        data-is-reusable="false"
                                                        <?php echo e(old('produit_id') == $produit->id ? 'selected' : ''); ?>>
                                                    <?php echo e($produit->designation); ?> 
                                                    (<?php echo e($produit->categorie); ?>) 
                                                    - Stock: <?php echo e($disponible); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </optgroup>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Les produits réutilisables sont affichés en premier
                                    </small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="quantite" class="form-label">
                                        Quantité <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           name="quantite" 
                                           id="quantite" 
                                           min="1" 
                                           value="<?php echo e(old('quantite', 1)); ?>"
                                           required>
                                    <small class="form-text text-muted" id="stock-info">
                                        Sélectionnez un produit pour voir le stock
                                    </small>
                                </div>
                            </div>

                            <!-- Usage Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-info-circle"></i> Détails d'Utilisation
                                    </h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="type_utilisation" class="form-label">
                                        Type d'Utilisation <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" name="type_utilisation" id="type_utilisation" required>
                                        <option value="">-- Sélectionner --</option>
                                        <option value="intervention_chirurgicale" <?php echo e(old('type_utilisation') == 'intervention_chirurgicale' ? 'selected' : ''); ?>>
                                            Intervention Chirurgicale
                                        </option>
                                        <option value="consultation" <?php echo e(old('type_utilisation') == 'consultation' ? 'selected' : ''); ?>>
                                            Consultation
                                        </option>
                                        <option value="hospitalisation" <?php echo e(old('type_utilisation') == 'hospitalisation' ? 'selected' : ''); ?>>
                                            Hospitalisation
                                        </option>
                                        <option value="urgence" <?php echo e(old('type_utilisation') == 'urgence' ? 'selected' : ''); ?>>
                                            Urgence
                                        </option>
                                        <option value="autre" <?php echo e(old('type_utilisation') == 'autre' ? 'selected' : ''); ?>>
                                            Autre
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="service" class="form-label">Service</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="service" 
                                           id="service" 
                                           value="<?php echo e(old('service')); ?>"
                                           placeholder="Ex: Bloc opératoire, Urgences...">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_utilisation" class="form-label">
                                        Date d'Utilisation <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           name="date_utilisation" 
                                           id="date_utilisation" 
                                           value="<?php echo e(old('date_utilisation', date('Y-m-d'))); ?>"
                                           required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="heure_utilisation" class="form-label">Heure d'Utilisation</label>
                                    <input type="time" 
                                           class="form-control" 
                                           name="heure_utilisation" 
                                           id="heure_utilisation" 
                                           value="<?php echo e(old('heure_utilisation')); ?>">
                                </div>
                            </div>

                            <!-- Patient & Staff -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-user-md"></i> Patient et Personnel
                                    </h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="patient_id" class="form-label">Patient</label>
                                    <select class="form-select" name="patient_id" id="patient_id">
                                        <option value="">-- Aucun patient --</option>
                                        <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($patient->id); ?>" <?php echo e(old('patient_id') == $patient->id ? 'selected' : ''); ?>>
                                                <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?> 
                                                (Dossier: <?php echo e($patient->numero_dossier); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="medecin_id" class="form-label">Médecin</label>
                                    <select class="form-select" name="medecin_id" id="medecin_id">
                                        <option value="">-- Sélectionner un médecin --</option>
                                        <?php $__currentLoopData = $medecins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medecin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($medecin->id); ?>" <?php echo e(old('medecin_id') == $medecin->id ? 'selected' : ''); ?>>
                                                Dr. <?php echo e($medecin->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="infirmier_id" class="form-label">Infirmier(ère)</label>
                                    <select class="form-select" name="infirmier_id" id="infirmier_id">
                                        <option value="">-- Sélectionner un(e) infirmier(ère) --</option>
                                        <?php $__currentLoopData = $infirmiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $infirmier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($infirmier->id); ?>" <?php echo e(old('infirmier_id') == $infirmier->id ? 'selected' : ''); ?>>
                                                <?php echo e($infirmier->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Return Information (for reusable products) -->
                            <div class="row mb-4" id="return-section" style="display: none;">
                                <div class="col-12">
                                    <h6 class="text-success border-bottom pb-2">
                                        <i class="fas fa-recycle"></i> Informations de Retour (Produits Réutilisables)
                                    </h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="quantite_retournable" class="form-label">
                                        Quantité Retournable
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           name="quantite_retournable" 
                                           id="quantite_retournable" 
                                           min="0" 
                                           value="<?php echo e(old('quantite_retournable')); ?>">
                                    <small class="form-text text-muted">
                                        Laissez vide si toute la quantité est retournable
                                    </small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="quantite_perdue" class="form-label">
                                        Quantité Perdue/Endommagée
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           name="quantite_perdue" 
                                           id="quantite_perdue" 
                                           min="0" 
                                           value="<?php echo e(old('quantite_perdue', 0)); ?>">
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-comment"></i> Informations Complémentaires
                                    </h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="motif" class="form-label">Motif d'Utilisation</label>
                                    <textarea class="form-control" 
                                              name="motif" 
                                              id="motif" 
                                              rows="3" 
                                              placeholder="Raison de l'utilisation..."><?php echo e(old('motif')); ?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="observations" class="form-label">Observations</label>
                                    <textarea class="form-control" 
                                              name="observations" 
                                              id="observations" 
                                              rows="3" 
                                              placeholder="Remarques particulières..."><?php echo e(old('observations')); ?></textarea>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Enregistrer l'Utilisation
                                    </button>
                                    <a href="<?php echo e(route('reusable-products.index')); ?>" class="btn btn-secondary btn-lg">
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

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const produitSelect = document.getElementById('produit_id');
    const quantiteInput = document.getElementById('quantite');
    const stockInfo = document.getElementById('stock-info');
    const returnSection = document.getElementById('return-section');
    const quantiteRetournableInput = document.getElementById('quantite_retournable');
    
    // Update stock info and show/hide return section based on product selection
    produitSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const disponible = selectedOption.dataset.disponible;
            const isReusable = selectedOption.dataset.isReusable === 'true';
            
            stockInfo.innerHTML = `<i class="fas fa-info-circle"></i> Stock disponible: <strong>${disponible}</strong> unité(s)`;
            
            // Show/hide return section based on reusability
            if (isReusable) {
                returnSection.style.display = '';
                stockInfo.classList.add('text-success');
                stockInfo.classList.remove('text-muted');
            } else {
                returnSection.style.display = 'none';
                stockInfo.classList.remove('text-success');
                stockInfo.classList.add('text-muted');
            }
            
            // Set max quantity
            quantiteInput.max = disponible;
        } else {
            stockInfo.innerHTML = 'Sélectionnez un produit pour voir le stock';
            stockInfo.classList.remove('text-success');
            stockInfo.classList.add('text-muted');
            returnSection.style.display = 'none';
            quantiteInput.max = '';
        }
    });
    
    // Auto-calculate returnable quantity
    quantiteInput.addEventListener('input', function() {
        if (returnSection.style.display !== 'none' && !quantiteRetournableInput.value) {
            const quantitePerdue = parseInt(document.getElementById('quantite_perdue').value) || 0;
            const quantite = parseInt(this.value) || 0;
            quantiteRetournableInput.value = Math.max(0, quantite - quantitePerdue);
        }
    });
    
    document.getElementById('quantite_perdue').addEventListener('input', function() {
        const quantite = parseInt(quantiteInput.value) || 0;
        const quantitePerdue = parseInt(this.value) || 0;
        quantiteRetournableInput.value = Math.max(0, quantite - quantitePerdue);
    });
    
    // Trigger change event on page load if there's a pre-selected product
    if (produitSelect.value) {
        produitSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/reusable_products/record_usage_form.blade.php ENDPATH**/ ?>