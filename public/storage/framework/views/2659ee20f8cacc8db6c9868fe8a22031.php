
<?php $__env->startSection('title', 'CMCU | Détail Vente'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-file-invoice"></i> Détail de la Vente
                </h1>
                <p class="text-muted"><?php echo e($vente->numero_vente); ?></p>
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('pharmacie.sales.list')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la Liste
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Sale Information -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations de la Vente</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Numéro de Vente:</strong><br>
                                <span class="fs-5 text-primary"><?php echo e($vente->numero_vente); ?></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Date:</strong><br>
                                <?php echo e($vente->created_at->format('d/m/Y H:i')); ?>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Type de Vente:</strong><br>
                                <?php if($vente->isPatientSale()): ?>
                                <span class="badge bg-primary">Vente Patient</span>
                                <?php else: ?>
                                <span class="badge bg-success">Vente Externe</span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <strong>Pharmacien:</strong><br>
                                <?php echo e($vente->pharmacien->name ?? 'N/A'); ?>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Client:</strong><br>
                                <?php if($vente->isPatientSale()): ?>
                                    <i class="fas fa-user-injured"></i> <?php echo e($vente->patient->name); ?> <?php echo e($vente->patient->prenom); ?>

                                    <br><small class="text-muted">Dossier N°: <?php echo e($vente->patient->numero_dossier); ?></small>
                                    <?php if($vente->ordonance): ?>
                                    <br><small class="text-info"><i class="fas fa-file-prescription"></i> Ordonnance du <?php echo e($vente->ordonance->created_at->format('d/m/Y')); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <i class="fas fa-hospital"></i> <?php echo e($vente->client->nom); ?> <?php echo e($vente->client->prenom); ?>

                                    <?php if($vente->client->motif): ?>
                                    <br><small class="text-muted"><?php echo e($vente->client->motif); ?></small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if($vente->notes): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Notes:</strong><br>
                                <p class="text-muted"><?php echo e($vente->notes); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Items -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-pills"></i> Produits</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Quantité</th>
                                        <th class="text-end">Prix Unit.</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $vente->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php echo e($item->designation); ?>

                                            <?php if($item->stock_deducted): ?>
                                            <span class="badge bg-success ms-2"><i class="fas fa-check"></i> Stock Déduit</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?php echo e($item->quantite); ?></td>
                                        <td class="text-end"><?php echo e(number_format($item->prix_unitaire)); ?> FCFA</td>
                                        <td class="text-end"><strong><?php echo e(number_format($item->montant_ligne)); ?> FCFA</strong></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">TOTAL:</th>
                                        <th class="text-end text-primary fs-5"><?php echo e(number_format($vente->montant_total)); ?> FCFA</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information & Actions -->
            <div class="col-md-4">
                <!-- Payment Status -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header <?php echo e($vente->isSoldee() ? 'bg-success text-white' : 'bg-warning text-dark'); ?>">
                        <h5 class="mb-0">
                            <i class="fas <?php echo e($vente->isSoldee() ? 'fa-check-circle' : 'fa-clock'); ?>"></i> 
                            Statut Paiement
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if($vente->isSoldee()): ?>
                        <div class="alert alert-success mb-3">
                            <i class="fas fa-check-circle"></i> <strong>SOLDÉE</strong>
                        </div>
                        <p><strong>Montant Payé:</strong><br><?php echo e(number_format($vente->montant_paye)); ?> FCFA</p>
                        <p><strong>Date Paiement:</strong><br><?php echo e($vente->date_paiement->format('d/m/Y H:i')); ?></p>
                        <p><strong>Paiement reçu par:</strong><br><?php echo e($vente->caissier->name ?? 'N/A'); ?></p>
                        <?php if($vente->mode_paiement): ?>
                        <p><strong>Mode de Paiement:</strong><br><?php echo e($vente->mode_paiement); ?></p>
                        <?php endif; ?>
                        <?php if($vente->reference_paiement): ?>
                        <p><strong>Référence:</strong><br><?php echo e($vente->reference_paiement); ?></p>
                        <?php endif; ?>
                        <?php else: ?>
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-clock"></i> <strong>EN ATTENTE DE PAIEMENT</strong>
                        </div>
                        <p><strong>Montant à Payer:</strong></p>
                        <h3 class="text-danger"><?php echo e(number_format($vente->montant_total)); ?> FCFA</h3>

                        <?php if(in_array(auth()->user()->role_id, [1, 6, 7])): ?>
                        <hr>
                        <form action="<?php echo e(route('pharmacie.sales.soldee', $vente->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label>Mode de Paiement *</label>
                                <select name="mode_paiement" class="form-control" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="Espèces">Espèces</option>
                                    <option value="Mobile Money">Mobile Money</option>
                                    <option value="Carte Bancaire">Carte Bancaire</option>
                                    <option value="Chèque">Chèque</option>
                                    <option value="Virement">Virement</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Référence/N° Transaction</label>
                                <input type="text" name="reference_paiement" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="fas fa-check"></i> Marquer comme Soldée
                            </button>
                        </form>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="<?php echo e(route('pharmacie.sales.invoice', $vente->id)); ?>" 
                           class="btn btn-danger w-100 mb-2" target="_blank">
                            <i class="fas fa-file-pdf"></i> Imprimer Facture (x2)
                        </a>
                        
                        <?php if($vente->isSoldee()): ?>
                        <a href="<?php echo e(route('pharmacie.sales.receipt', $vente->id)); ?>" 
                           class="btn btn-success w-100 mb-2" target="_blank">
                            <i class="fas fa-receipt"></i> Imprimer Reçu
                        </a>
                        <?php endif; ?>

                        <a href="<?php echo e(route('pharmacie.index')); ?>" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-home"></i> Retour Accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/pharmacie/show.blade.php ENDPATH**/ ?>