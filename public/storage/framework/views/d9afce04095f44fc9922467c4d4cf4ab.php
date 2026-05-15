
<?php $__env->startSection('title', 'CMCU | Lancer Stérilisation'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-info">Lancer une Stérilisation</h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-fire"></i> Nouveau Lot de Stérilisation</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('reusable-products.sterilizations.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <!-- Product Selection -->
                            <div class="mb-3">
                                <label for="produit_id" class="form-label">
                                    Produit à Stériliser <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?php $__errorArgs = ['produit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        name="produit_id" 
                                        id="produit_id" 
                                        required>
                                    <option value="">Sélectionner un produit...</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($produit->id); ?>" 
                                                data-qte="<?php echo e($produit->qte_en_sterilisation); ?>"
                                                data-methode="<?php echo e($produit->methode_sterilisation_recommandee); ?>"
                                                data-duree="<?php echo e($produit->duree_sterilisation_recommandee); ?>"
                                                data-temperature="<?php echo e($produit->temperature_sterilisation); ?>"
                                                <?php echo e(old('produit_id') == $produit->id ? 'selected' : ''); ?>>
                                            <?php echo e($produit->designation); ?> 
                                            (<?php echo e($produit->qte_en_sterilisation); ?> unité(s) en attente)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option value="" disabled>Aucun produit en attente de stérilisation</option>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['produit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">
                                    Seuls les produits avec quantité en stérilisation > 0 sont affichés
                                </small>
                            </div>

                            <!-- Quantity -->
                            <div class="mb-3">
                                <label for="quantite" class="form-label">
                                    Quantité à Stériliser <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control <?php $__errorArgs = ['quantite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       name="quantite" 
                                       id="quantite" 
                                       value="<?php echo e(old('quantite', 1)); ?>" 
                                       min="1" 
                                       required>
                                <?php $__errorArgs = ['quantite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted" id="qte_disponible_text"></small>
                            </div>

                            <!-- Sterilization Method -->
                            <div class="mb-3">
                                <label for="methode_sterilisation" class="form-label">
                                    Méthode de Stérilisation <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?php $__errorArgs = ['methode_sterilisation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        name="methode_sterilisation" 
                                        id="methode_sterilisation" 
                                        required>
                                    <option value="">Sélectionner...</option>
                                    <option value="autoclave" <?php echo e(old('methode_sterilisation') == 'autoclave' ? 'selected' : ''); ?>>
                                        Autoclave (Vapeur sous Pression)
                                    </option>
                                    <option value="chaleur_seche" <?php echo e(old('methode_sterilisation') == 'chaleur_seche' ? 'selected' : ''); ?>>
                                        Chaleur Sèche (Poupinel)
                                    </option>
                                    <option value="gaz_eto" <?php echo e(old('methode_sterilisation') == 'gaz_eto' ? 'selected' : ''); ?>>
                                        Gaz ETO (Oxyde d'Éthylène)
                                    </option>
                                    <option value="plasma" <?php echo e(old('methode_sterilisation') == 'plasma' ? 'selected' : ''); ?>>
                                        Plasma (Peroxyde d'Hydrogène)
                                    </option>
                                    <option value="chimique" <?php echo e(old('methode_sterilisation') == 'chimique' ? 'selected' : ''); ?>>
                                        Chimique (Immersion)
                                    </option>
                                    <option value="autre" <?php echo e(old('methode_sterilisation') == 'autre' ? 'selected' : ''); ?>>
                                        Autre
                                    </option>
                                </select>
                                <?php $__errorArgs = ['methode_sterilisation'];
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

                            <!-- Date and Time -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_sterilisation" class="form-label">
                                        Date de Stérilisation <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control <?php $__errorArgs = ['date_sterilisation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           name="date_sterilisation" 
                                           value="<?php echo e(old('date_sterilisation', date('Y-m-d'))); ?>" 
                                           required>
                                    <?php $__errorArgs = ['date_sterilisation'];
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
                                    <label for="heure_debut" class="form-label">Heure de Début</label>
                                    <input type="time" 
                                           class="form-control" 
                                           name="heure_debut" 
                                           value="<?php echo e(old('heure_debut', date('H:i'))); ?>">
                                </div>
                            </div>

                            <!-- Parameters -->
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-sliders-h"></i> Paramètres de Stérilisation</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="temperature" class="form-label">
                                                Température (°C)
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="temperature" 
                                                   id="temperature" 
                                                   value="<?php echo e(old('temperature')); ?>" 
                                                   min="0">
                                            <small class="text-muted">Recommandée: <span id="temp_recommandee">-</span></small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="duree_minutes" class="form-label">
                                                Durée (minutes)
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="duree_minutes" 
                                                   id="duree_minutes" 
                                                   value="<?php echo e(old('duree_minutes')); ?>" 
                                                   min="0">
                                            <small class="text-muted">Recommandée: <span id="duree_recommandee">-</span></small>
                                        </div>
                                    </div>

                                    <div class="alert alert-info mb-0">
                                        <strong>Guide Rapide:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li><strong>Autoclave:</strong> 121-134°C, 15-30 min</li>
                                            <li><strong>Chaleur Sèche:</strong> 160-170°C, 60-120 min</li>
                                            <li><strong>Gaz ETO:</strong> 37-63°C, 2-12 heures</li>
                                            <li><strong>Plasma:</strong> 45-50°C, 45-75 min</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Quality Indicator -->
                            <div class="mb-3">
                                <label for="type_indicateur" class="form-label">
                                    Type d'Indicateur de Qualité
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       name="type_indicateur" 
                                       id="type_indicateur" 
                                       value="<?php echo e(old('type_indicateur')); ?>" 
                                       placeholder="Ex: Indicateur biologique, chimique, intégrateur...">
                                <small class="text-muted">
                                    Spécifier le type d'indicateur utilisé pour vérifier l'efficacité
                                </small>
                            </div>

                            <!-- Observations -->
                            <div class="mb-3">
                                <label for="observations" class="form-label">Observations</label>
                                <textarea class="form-control" 
                                          name="observations" 
                                          id="observations" 
                                          rows="3"><?php echo e(old('observations')); ?></textarea>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-info btn-lg text-white">
                                    <i class="fas fa-fire"></i> Lancer la Stérilisation
                                </button>
                                <a href="<?php echo e(route('reusable-products.index')); ?>" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Update recommended values when product is selected
    $('#produit_id').change(function() {
        const option = $(this).find('option:selected');
        const qte = option.data('qte');
        const methode = option.data('methode');
        const duree = option.data('duree');
        const temperature = option.data('temperature');

        // Update quantity available text
        if (qte) {
            $('#qte_disponible_text').text(`Maximum disponible: ${qte} unité(s)`);
            $('#quantite').attr('max', qte);
        } else {
            $('#qte_disponible_text').text('');
            $('#quantite').removeAttr('max');
        }

        // Auto-select recommended method
        if (methode && methode !== 'non_applicable') {
            $('#methode_sterilisation').val(methode);
        }

        // Show recommended values
        if (temperature) {
            $('#temperature').val(temperature);
            $('#temp_recommandee').text(temperature + '°C');
        } else {
            $('#temp_recommandee').text('-');
        }

        if (duree) {
            $('#duree_minutes').val(duree);
            $('#duree_recommandee').text(duree + ' min');
        } else {
            $('#duree_recommandee').text('-');
        }
    });

    // Trigger change on page load if product is pre-selected
    if ($('#produit_id').val()) {
        $('#produit_id').trigger('change');
    }

    // Validate quantity doesn't exceed available
    $('form').submit(function(e) {
        const qte = parseInt($('#quantite').val());
        const max = parseInt($('#quantite').attr('max'));
        
        if (max && qte > max) {
            e.preventDefault();
            alert(`La quantité ne peut pas dépasser ${max} unité(s) disponible(s)`);
            return false;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/reusable_products/create_sterilization.blade.php ENDPATH**/ ?>