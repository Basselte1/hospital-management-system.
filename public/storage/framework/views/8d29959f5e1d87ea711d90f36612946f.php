
<?php $__env->startSection('title', 'CMCU | Paramètres Produit Réutilisable'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-success">
                    <i class="fas fa-cog"></i> Paramètres de Réutilisation
                </h1>
                <hr class="w-25 mx-auto">
                <h4 class="text-muted"><?php echo e($produit->designation); ?></h4>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Product Info Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Produit</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Désignation:</strong> <?php echo e($produit->designation); ?></p>
                                <p><strong>Catégorie:</strong> <span class="badge bg-secondary"><?php echo e($produit->categorie); ?></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Stock Total:</strong> <?php echo e($produit->qte_stock); ?></p>
                                <p><strong>Prix Unitaire:</strong> <?php echo e(number_format($produit->prix_unitaire, 0, ',', ' ')); ?> FCFA</p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-recycle"></i> 
                                    <strong>Ce produit est marqué comme réutilisable</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Form -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Paramètres de Stérilisation</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('produits.update-reusable-settings', $produit->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <!-- Utilization Parameters -->
                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-sync-alt"></i> Paramètres d'Utilisation
                                </h6>

                                <div class="mb-3">
                                    <label for="nombre_utilisations_max" class="form-label">
                                        Nombre Maximum d'Utilisations
                                        <i class="fas fa-question-circle text-muted" 
                                           title="Nombre de fois que le produit peut être réutilisé avant remplacement"></i>
                                    </label>
                                    <input type="number" 
                                           class="form-control <?php $__errorArgs = ['nombre_utilisations_max'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           name="nombre_utilisations_max" 
                                           id="nombre_utilisations_max"
                                           value="<?php echo e(old('nombre_utilisations_max', $produit->nombre_utilisations_max)); ?>" 
                                           min="1" 
                                           placeholder="Ex: 50">
                                    <small class="text-muted">
                                        Laisser vide si pas de limite définie
                                    </small>
                                    <?php $__errorArgs = ['nombre_utilisations_max'];
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

                                <div class="mb-3">
                                    <label for="notes_utilisation" class="form-label">
                                        Notes d'Utilisation
                                    </label>
                                    <textarea class="form-control <?php $__errorArgs = ['notes_utilisation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              name="notes_utilisation" 
                                              id="notes_utilisation"
                                              rows="3"
                                              placeholder="Instructions spéciales, précautions, conditions d'utilisation..."><?php echo e(old('notes_utilisation', $produit->notes_utilisation)); ?></textarea>
                                    <?php $__errorArgs = ['notes_utilisation'];
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

                            <!-- Sterilization Parameters -->
                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-fire"></i> Paramètres de Stérilisation Recommandés
                                </h6>

                                <div class="alert alert-info">
                                    <i class="fas fa-lightbulb"></i>
                                    <strong>Important:</strong> Ces paramètres seront utilisés par défaut lors de la création d'un nouveau lot de stérilisation.
                                </div>

                                <div class="mb-3">
                                    <label for="methode_sterilisation_recommandee" class="form-label">
                                        Méthode de Stérilisation <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['methode_sterilisation_recommandee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            name="methode_sterilisation_recommandee" 
                                            id="methode_sterilisation_recommandee" 
                                            required>
                                        <option value="">Sélectionner une méthode...</option>
                                        <option value="autoclave" 
                                                <?php echo e(old('methode_sterilisation_recommandee', $produit->methode_sterilisation_recommandee) == 'autoclave' ? 'selected' : ''); ?>>
                                            Autoclave (Vapeur sous pression)
                                        </option>
                                        <option value="chaleur_seche" 
                                                <?php echo e(old('methode_sterilisation_recommandee', $produit->methode_sterilisation_recommandee) == 'chaleur_seche' ? 'selected' : ''); ?>>
                                            Chaleur Sèche (Poupinel)
                                        </option>
                                        <option value="gaz_eto" 
                                                <?php echo e(old('methode_sterilisation_recommandee', $produit->methode_sterilisation_recommandee) == 'gaz_eto' ? 'selected' : ''); ?>>
                                            Gaz ETO (Oxyde d'éthylène)
                                        </option>
                                        <option value="plasma" 
                                                <?php echo e(old('methode_sterilisation_recommandee', $produit->methode_sterilisation_recommandee) == 'plasma' ? 'selected' : ''); ?>>
                                            Plasma (Peroxyde d'hydrogène)
                                        </option>
                                        <option value="chimique" 
                                                <?php echo e(old('methode_sterilisation_recommandee', $produit->methode_sterilisation_recommandee) == 'chimique' ? 'selected' : ''); ?>>
                                            Chimique (Immersion)
                                        </option>
                                        <option value="autre" 
                                                <?php echo e(old('methode_sterilisation_recommandee', $produit->methode_sterilisation_recommandee) == 'autre' ? 'selected' : ''); ?>>
                                            Autre
                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['methode_sterilisation_recommandee'];
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

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="temperature_sterilisation" class="form-label">
                                            Température Recommandée (°C)
                                        </label>
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['temperature_sterilisation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               name="temperature_sterilisation" 
                                               id="temperature_sterilisation"
                                               value="<?php echo e(old('temperature_sterilisation', $produit->temperature_sterilisation)); ?>" 
                                               min="50" 
                                               max="200"
                                               placeholder="Ex: 121">
                                        <small class="text-muted">
                                            Autoclave: 121-134°C | Chaleur sèche: 160-170°C
                                        </small>
                                        <?php $__errorArgs = ['temperature_sterilisation'];
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
                                        <label for="duree_sterilisation_recommandee" class="form-label">
                                            Durée Recommandée (minutes)
                                        </label>
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['duree_sterilisation_recommandee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               name="duree_sterilisation_recommandee" 
                                               id="duree_sterilisation_recommandee"
                                               value="<?php echo e(old('duree_sterilisation_recommandee', $produit->duree_sterilisation_recommandee)); ?>" 
                                               min="1"
                                               placeholder="Ex: 20">
                                        <small class="text-muted">
                                            Autoclave: 15-30 min | Chaleur sèche: 60-120 min
                                        </small>
                                        <?php $__errorArgs = ['duree_sterilisation_recommandee'];
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

                            <!-- Preset Buttons -->
                            <div class="mb-4">
                                <h6 class="text-secondary">Presets Rapides:</h6>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary preset-btn" 
                                            data-method="autoclave" 
                                            data-temp="121" 
                                            data-duration="20">
                                        Autoclave Standard
                                    </button>
                                    <button type="button" class="btn btn-outline-primary preset-btn" 
                                            data-method="autoclave" 
                                            data-temp="134" 
                                            data-duration="15">
                                        Autoclave Rapide
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary preset-btn" 
                                            data-method="chaleur_seche" 
                                            data-temp="160" 
                                            data-duration="90">
                                        Chaleur Sèche
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Enregistrer les Paramètres
                                </button>
                                <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary btn-lg">
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
    // Preset button handlers
    $('.preset-btn').on('click', function() {
        const method = $(this).data('method');
        const temp = $(this).data('temp');
        const duration = $(this).data('duration');
        
        $('#methode_sterilisation_recommandee').val(method);
        $('#temperature_sterilisation').val(temp);
        $('#duree_sterilisation_recommandee').val(duration);
        
        // Visual feedback
        $('.preset-btn').removeClass('active');
        $(this).addClass('active');
    });

    // Update temperature/duration hints based on method
    $('#methode_sterilisation_recommandee').on('change', function() {
        const method = $(this).val();
        let tempHint = '';
        let durationHint = '';
        
        switch(method) {
            case 'autoclave':
                tempHint = 'Autoclave: 121-134°C';
                durationHint = 'Autoclave: 15-30 min';
                break;
            case 'chaleur_seche':
                tempHint = 'Chaleur sèche: 160-170°C';
                durationHint = 'Chaleur sèche: 60-120 min';
                break;
            case 'gaz_eto':
                tempHint = 'ETO: 37-63°C';
                durationHint = 'ETO: 2-12 heures';
                break;
            case 'plasma':
                tempHint = 'Plasma: 45-50°C';
                durationHint = 'Plasma: 45-75 min';
                break;
        }
        
        $('#temperature_sterilisation').next('small').text(tempHint);
        $('#duree_sterilisation_recommandee').next('small').text(durationHint);
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/produits/edit_reusable_settings.blade.php ENDPATH**/ ?>