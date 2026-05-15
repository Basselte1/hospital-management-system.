

<?php $__env->startSection('title', 'CMCU | Fiches de consommables'); ?>

<?php $__env->startSection('content'); ?>

<style type="text/css">
    .tt-dropdown-menu {
        width: 100% !important;
    }
    .tt-menu {
        width: 422px;
        margin: 12px 0;
        padding: 8px 0;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
    }
    .tt-suggestion:hover {
        cursor: pointer;
        color: #fff;
        background-color: #0097cf;
    }
    #scrollable-dropdown-menu {
        max-height: 150px;
        overflow-y: auto;
    }
    .tt-suggestion p {
        margin: 0;
    }
</style>

<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
    <div class="row mb-1">
        <div class="col-sm-12">
            <h1 class="text-center">FICHES DE CONSOMMABLES</h1>
        </div>
    </div>
    <hr>
    
    <div class="container">
        <!-- Boutons de navigation -->
        <div class="d-grid gap-2 mb-2 d-md-block">
            <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn btn-success float-end" title="Retour à la liste des patients">
                <i class="fas fa-arrow-left"></i> Retour au dossier patient
            </a>
            <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-success offset-0">
                <i class="fas fa-list-ul"></i> Liste des patients
            </a>
        </div>

        <!-- Messages d'erreur et de succès -->
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erreur !</strong>
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Informations patient -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Patient: <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></h5>
                <p class="card-text">Dossier N°: <?php echo e($patient->numero_dossier); ?></p>
            </div>
        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" width="40%">CONSOMMABLES</th>
                            <th class="text-center" width="10%">P (Jour)</th>
                            <th class="text-center" width="10%">G (Nuit)</th>
                            <th class="text-center" width="10%">DATE</th>
                            <th class="text-center" width="10%">IDE</th>
                            <th class="text-center" width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- FORMULAIRE D'AJOUT -->
                        <form method="POST" action="<?php echo e(route('fiche_consommable.store')); ?>" id="formConsommable">
                            <?php echo csrf_field(); ?>
                            
                            <!-- Champs cachés -->
                            <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">
                            <input type="hidden" name="user_id" value="<?php echo e($user_id); ?>">
                            
                            <tr class="table-info">
                                <td>
                                    <!--select
                                        name="consommable"
                                        id="consommable"
                                        class="form-control"
                                        required>
                                        <option value="">Sélectionner un consommable...</option>
                                        <?php $__currentLoopData = $produits->groupBy('categorie'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie => $produitsParCategorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <optgroup label="<?php echo e($categorie); ?>">
                                                <?php $__currentLoopData = $produitsParCategorie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($produit->designation); ?>"
                                                            <?php echo e(old('consommable') == $produit->designation ? 'selected' : ''); ?>

                                                            <?php if($produit->qte_stock <= 0): ?> style="color: red;" <?php elseif($produit->qte_stock <= $produit->qte_alerte): ?> style="color: orange;" <?php endif; ?>>
                                                        <?php echo e($produit->designation); ?>

                                                        <?php if($produit->qte_stock <= 0): ?>
                                                            (ÉPUISÉ)
                                                        <?php elseif($produit->qte_stock <= $produit->qte_alerte): ?>
                                                            (Stock faible: <?php echo e($produit->qte_stock); ?>)
                                                        <?php else: ?>
                                                            (Stock: <?php echo e($produit->qte_stock); ?>)
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select-->

                                    <input type="text"
                                            name="consommable"
                                            id="consommable"
                                            class="form-control"
                                            list="consommablesList"
                                            placeholder="Saisir ou sélectionner un consommable..."
                                            value="<?php echo e(old('consommable')); ?>"
                                            required>

                                        <datalist id="consommablesList" style= "background-color: #fff;">
                                            <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($produit->designation); ?>" style= "background-color: #fff;">
                                                    <?php echo e($produit->designation); ?>

                                                    <?php if($produit->qte_stock <= 0): ?>
                                                        (Rupture)
                                                    <?php elseif($produit->qte_stock <= $produit->qte_alerte): ?>
                                                        (Stock faible: <?php echo e($produit->qte_stock); ?>)
                                                    <?php else: ?>
                                                        (Stock: <?php echo e($produit->qte_stock); ?>)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </datalist>

                                        <?php $__errorArgs = ['consommable'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


                                    <?php $__errorArgs = ['consommable'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                
                                <td>
                                    <input 
                                        type="number" 
                                        name="jour" 
                                        class="form-control" 
                                        min="0" 
                                        step="1"
                                        value="<?php echo e(old('jour', 0)); ?>"
                                        placeholder="0">
                                    <?php $__errorArgs = ['jour'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </td>
                                
                                <td>
                                    <input 
                                        type="number" 
                                        name="nuit" 
                                        class="form-control" 
                                        min="0" 
                                        step="1"
                                        value="<?php echo e(old('nuit', 0)); ?>"
                                        placeholder="0">
                                    <?php $__errorArgs = ['nuit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </td>
                                
                                <td>
                                    <input 
                                        type="date" 
                                        name="date" 
                                        class="form-control" 
                                        value="<?php echo e(old('date', \Carbon\Carbon::now()->toDateString())); ?>"
                                        required>
                                    <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </td>
                                
                                <td class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-save"></i> Enregistrer
                                    </button>
                                </td>
                            </tr>
                        </form>
                        <!-- FIN FORMULAIRE -->

                      <!-- En-tête de la liste -->
                        <tr class="table-active">
                            <th>CONSOMMABLES</th>
                            <th>P (Jour)</th>
                            <th>G (Nuit)</th>
                            <th>DATE</th>
                            <th>IDE</th>
                            <th>ACTION</th>
                        </tr>

                        <!-- LISTE DES CONSOMMABLES -->
                        <?php $__empty_1 = true; $__currentLoopData = $consommables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consommable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($consommable->consommable); ?></td>
                                <td class="text-center"><?php echo e($consommable->jour ?? 0); ?></td>
                                <td class="text-center"><?php echo e($consommable->nuit ?? 0); ?></td>
                                <td class="text-center"><?php echo e(\Carbon\Carbon::parse($consommable->date)->format('d/m/Y')); ?></td>
                                <td><?php echo e($consommable->user->name ?? 'N/A'); ?> <?php echo e($consommable->user->prenom); ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($consommable->id); ?>" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="<?php echo e(route('fiche_consommable.destroy', $consommable->id)); ?>" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce consommable ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal de modification -->
                            <div class="modal fade" id="editModal<?php echo e($consommable->id); ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo e($consommable->id); ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?php echo e($consommable->id); ?>">Modifier le consommable</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('fiche_consommable.update', $consommable->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="consommable<?php echo e($consommable->id); ?>" class="form-label">Consommable</label>
                                                    <input type="text"
                                                            name="consommable"
                                                            id="consommable<?php echo e($consommable->id); ?>"
                                                            class="form-control"
                                                            list="consommablesList<?php echo e($consommable->id); ?>"
                                                            placeholder="Saisir ou sélectionner un consommable..."
                                                            value="<?php echo e($consommable->consommable); ?>"
                                                            required>

                                                        <datalist id="consommablesList<?php echo e($consommable->id); ?>" style= "background-color: #fff;">
                                                            <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($produit->designation); ?>" style= "background-color: #fff;">
                                                                    <?php echo e($produit->designation); ?>

                                                                    <?php if($produit->qte_stock <= 0): ?>
                                                                        (Rupture)
                                                                    <?php elseif($produit->qte_stock <= $produit->qte_alerte): ?>
                                                                        (Stock faible: <?php echo e($produit->qte_stock); ?>)
                                                                    <?php else: ?>
                                                                        (Stock: <?php echo e($produit->qte_stock); ?>)
                                                                    <?php endif; ?>
                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </datalist>

                                                        <?php $__errorArgs = ['consommable'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <small class="text-danger"><?php echo e($message); ?></small>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jour<?php echo e($consommable->id); ?>" class="form-label">P (Jour)</label>
                                                    <input type="number" class="form-control" id="jour<?php echo e($consommable->id); ?>" name="jour" value="<?php echo e($consommable->jour); ?>" min="0">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nuit<?php echo e($consommable->id); ?>" class="form-label">G (Nuit)</label>
                                                    <input type="number" class="form-control" id="nuit<?php echo e($consommable->id); ?>" name="nuit" value="<?php echo e($consommable->nuit); ?>" min="0">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="date<?php echo e($consommable->id); ?>" class="form-label">Date</label>
                                                    <input type="date" class="form-control" id="date<?php echo e($consommable->id); ?>" name="date" value="<?php echo e($consommable->date); ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <em>Aucun consommable enregistré pour ce patient</em>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if($consommables->hasPages()): ?>
                    <div class="d-flex justify-content-center">
                        <?php echo e($consommables->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<!--<?php $__env->startPush('scripts'); ?>-->
<?php $__env->startSection('script'); ?>
<script>
waitForjQuery(function() {
    $(document).ready(function() {
        // Initialiser Select2 sur le champ consommable pour permettre la recherche
        $('#consommable').select2({
            placeholder: 'Tapez pour rechercher un consommable...',
            allowClear: true,
            width: '100%',
            minimumInputLength: 1,
            language: {
                noResults: function() {
                    return "Aucun résultat trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                },
                inputTooShort: function() {
                    return "Tapez au moins 1 caractère";
                }
            }
        });

        // Gestion de la soumission du formulaire
        $('#formConsommable').on('submit', function(e) {
            console.log('Formulaire en cours de soumission...');

            var consommable = $('#consommable').val();
            var jour = $('input[name="jour"]').val();
            var nuit = $('input[name="nuit"]').val();
            var date = $('input[name="date"]').val();


            if (!consommable || consommable.trim() === '') {
                e.preventDefault();
                alert('Veuillez sélectionner un consommable');
                return false;
            }

            if (!date) {
                e.preventDefault();
                alert('Veuillez saisir une date');
                return false;
            }

            // Vérifier qu'au moins jour ou nuit est renseigné
            if ((!jour || jour == 0) && (!nuit || nuit == 0)) {
                if (!confirm('Aucune quantité n\'est renseignée. Voulez-vous continuer ?')) {
                    e.preventDefault();
                    return false;
                }
            }

            console.log('Données du formulaire:', {
                consommable: consommable,
                jour: jour,
                nuit: nuit,
                date: date
            });

            // Le formulaire sera soumis normalement
            return true;
        });

        // Auto-dismiss des alertes après 5 secondes
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/patients/fiche_consommable.blade.php ENDPATH**/ ?>