
<?php $__env->startSection('title', 'CMCU | Modifier un produit'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <h1 class="text-center">MODIFIER UN PRODUIT</h1>
        <hr>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
        <div class="alert alert-info alert-dismissible fade show">
            <i class="fas fa-info-circle"></i> <?php echo e(session('info')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if($canEditDirectly): ?>
        <!-- User has permission to edit -->
        <div class="card" style="width: 40rem;">
            <div class="card-body">
                <?php if($activeEditPermission): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Vous avez la permission de modifier ce produit.
                    Accordée le <?php echo e($activeEditPermission->reviewed_at->format('d/m/Y à H:i')); ?>

                </div>
                <?php endif; ?>

                <small class="text-info" title="Les champs marqués par une étoile rouge sont obligatoire">
                    <i class="fas fa-info-circle"></i>
                </small>
                <hr>
                
                <form class="mb-3 col-md-10" method="post" action="<?php echo e(route('produits.update', $produit)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    
                    <div class="mb-3">
                        <label for="designation">Désignation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['designation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               name="designation" value="<?php echo e(old('designation', $produit->designation)); ?>" required/>
                        <?php $__errorArgs = ['designation'];
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
                        <label for="categorie">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-control <?php $__errorArgs = ['categorie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="categorie" id="categorie" required>
                            <option value="PHARMACEUTIQUE" <?php echo e(old('categorie', $produit->categorie) == 'PHARMACEUTIQUE' ? 'selected' : ''); ?>>PHARMACEUTIQUE</option>
                            <option value="MATERIEL" <?php echo e(old('categorie', $produit->categorie) == 'MATERIEL' ? 'selected' : ''); ?>>MATERIEL</option>
                            <option value="ANESTHESISTE" <?php echo e(old('categorie', $produit->categorie) == 'ANESTHESISTE' ? 'selected' : ''); ?>>ANESTHESISTE</option>
                        </select>
                        <?php $__errorArgs = ['categorie'];
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
                        <label for="qte_stock">Quantité <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['qte_stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               name="qte_stock" value="<?php echo e(old('qte_stock', $produit->qte_stock)); ?>" required/>
                        <?php $__errorArgs = ['qte_stock'];
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
                        <label for="qte_alerte">Quantité d'alerte <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['qte_alerte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               name="qte_alerte" value="<?php echo e(old('qte_alerte', $produit->qte_alerte)); ?>" required/>
                        <?php $__errorArgs = ['qte_alerte'];
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
                        <label for="prix_unitaire">Prix unitaire <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['prix_unitaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               name="prix_unitaire" value="<?php echo e(old('prix_unitaire', $produit->prix_unitaire)); ?>" required/>
                        <?php $__errorArgs = ['prix_unitaire'];
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

                    <button type="submit" class="btn btn-primary" title="Enregistrer les modifications">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-info float-end" 
                       title="Retour à la liste des produits">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </form>
            </div>
        </div>

        <?php else: ?>
        <!-- User needs to request permission -->
        <div class="card" style="width: 40rem;">
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-lock"></i> Vous devez demander la permission pour modifier ce produit.
                </div>

                <h5>Informations du Produit</h5>
                <hr>
                <div class="mb-3">
                    <strong>Désignation:</strong> <?php echo e($produit->designation); ?>

                </div>
                <div class="mb-3">
                    <strong>Catégorie:</strong> <?php echo e($produit->categorie); ?>

                </div>
                <div class="mb-3">
                    <strong>Quantité en stock:</strong> <?php echo e($produit->qte_stock); ?>

                </div>
                <div class="mb-3">
                    <strong>Seuil d'alerte:</strong> <?php echo e($produit->qte_alerte); ?>

                </div>
                <div class="mb-3">
                    <strong>Prix unitaire:</strong> <?php echo e(number_format($produit->prix_unitaire, 0, ',', ' ')); ?> FCFA
                </div>

                <hr>
                <h5>Demander la Permission de Modification</h5>

                <form method="post" action="<?php echo e(route('produits.edit-permissions.request', $produit)); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="reason">Raison de la modification <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  rows="4" required 
                                  placeholder="Expliquez pourquoi vous devez modifier ce produit..."><?php echo e(old('reason')); ?></textarea>
                        <?php $__errorArgs = ['reason'];
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
                            Cette demande sera examinée par un Admin ou Gestionnaire
                        </small>
                    </div>

                    <button type="submit" class="btn btn-warning" title="Soumettre la demande">
                        <i class="fas fa-paper-plane"></i> Demander la Permission
                    </button>
                    <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-info float-end" 
                       title="Retour à la liste des produits">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Active Permissions for this Product (if admin) -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approveEditRequests', \App\Models\Produit::class)): ?>
        <?php if($produit->activeEditPermissions->count() > 0): ?>
        <div class="card mt-4" style="width: 40rem;">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Permissions Actives</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Accordée le</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $produit->activeEditPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($permission->requestedBy->name); ?></td>
                            <td><?php echo e($permission->reviewed_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <form action="<?php echo e(route('produits.edit-permissions.revoke', $permission->id)); ?>" 
                                      method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-warning" 
                                            onclick="return confirm('Révoquer cette permission ?')">
                                        <i class="fas fa-ban"></i> Révoquer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/produits/edit.blade.php ENDPATH**/ ?>