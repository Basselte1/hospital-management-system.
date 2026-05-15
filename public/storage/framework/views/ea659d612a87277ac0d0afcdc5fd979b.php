

<?php $__env->startSection('title', 'CMCU | Ajouter un client'); ?>


<?php $__env->startSection('content'); ?>


<?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="tw-container tw-mx-auto tw-px-4 tw-py-8 tw-max-w-4xl">

    <!-- Bouton retour -->
    <div class="tw-mb-8">
        <a href="<?php echo e(route('clients.index')); ?>" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-green-600 hover:tw-bg-green-700 tw-text-white tw-font-medium tw-rounded-xl tw-shadow-soft tw-transition-all tw-text-sm" title="Retour à la liste des clients">
            <i class="fas fa-arrow-left tw-mr-2"></i> Retour à la liste des clients
        </a>
    </div>

    <!-- Titre principal -->
    <div class="tw-text-center tw-mb-12">
        <h1 class="tw-text-4xl tw-font-bold tw-text-gray-900 tw-mb-4">AJOUTER UN CLIENT</h1>
        <div class="tw-w-12 tw-h-1 tw-bg-primary-500 tw-mx-auto tw-rounded-full"></div>
    </div>

    <div class="card" style="width: 40rem;">
        <div class="card-body">
            <h5 class="card-title">Ajouter un client</h5>
            <small class="text-info" title="Les champs marqués par une étoile rouge sont obligatoire"><i class="fas fa-info-circle"></i></small>
            <hr>
            <form class="mb-3 col-md-10" action="<?php echo e(route('clients.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="col-md-12">

                    <div class="mb-3">
                        <b>Médecin :</b> <span class="text-danger">*</span>
                        <select class="form-control" name="medecin_r" id="medecin_r" required>
                            <option value="medecin_r"> Nom du médecin</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                    value="<?php echo e($user->name); ?> <?php echo e($user->prenom); ?>"
                                    <?php echo e(old('medecin_r') == $user->name . ' ' . $user->prenom ? 'selected' : ''); ?>>
                                    <?php echo e($user->name); ?> <?php echo e($user->prenom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input name="nom" class="form-control" value="<?php echo e(old('nom')); ?>" type="text" placeholder="Nom" required>
                    </div>

                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prenom <span class="text-danger">*</span></label>
                        <input name="prenom" class="form-control" value="<?php echo e(old('prenom')); ?>" type="text" placeholder="prenom">
                    </div>

                    <div class="mb-3">
                        <label for="motif" class="form-label">Motif <span class="text-danger">*</span></label>
                        <input name="motif" class="form-control" value="<?php echo e(old('motif')); ?>" type="text" placeholder="motif">
                    </div>

                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant <span class="text-danger">*</span></label>
                        <input name="montant" class="form-control" value="<?php echo e(old('montant')); ?>" type="text" placeholder="montant">
                    </div>

                    <div class="mb-3">
                        <label for="avance" class="form-label">Avance</label>
                        <input name="avance" class="form-control" value="<?php echo e(old('avance')); ?>" type="text" placeholder="avance">
                    </div>

                    <div class="mb-3">
                        <label for="demarcheur" class="form-label">Démarcheur :</label>
                        <select class="form-control" name="demarcheur" id="demarcheur">
                            <option></option>
                            <option>DMH</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assurance" class="form-label">Assurance</label>
                        <input name="assurance" class="form-control" value="<?php echo e(old('assurance')); ?>" type="text" placeholder="nom de l'assurance si le patient est assuré">
                    </div>

                    <div class="mb-3">
                        <label for="numero_assurance" class="form-label">Numéro d'assurance</label>
                        <input name="numero_assurance" class="form-control" value="<?php echo e(old('numero_assurance')); ?>" type="text" placeholder="Numéro d'assurance si le patient est assuré">
                    </div>

                    <div class="mb-3">
                        <label for="prise_en_charge" class="form-label">Taux de Prise en Charge :</label>
                        <select class="form-control" name="prise_en_charge" id="prise_en_charge" required>
                            <?php $__currentLoopData = range(0, 100); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taux): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($taux == 0): ?>
                                    <option>aucune</option>
                                <?php else: ?>
                                    <option><?php echo e($taux); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date_insertion" class="form-label">Date Création</label>
                        <input type="date" name="date_insertion" class="form-control" value="<?php echo e(old('date_insertion')); ?>" placeholder="date de création du dossier au cmcu" required>
                    </div>

                    <br>

                    <button type="submit" class="btn btn-primary btn-lg col-md-5" title="En cliquant sur ce bouton vous enregistrez un nouveau patient">Ajouter</button>
                    <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-warning btn-lg col-md-5 offset-md-1" title="Retour à la liste des patients">Annuler</a>

                </div>
            </form>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/clients/create.blade.php ENDPATH**/ ?>