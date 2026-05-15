

<?php $__env->startSection('title', 'CMCU | Gestion des éléments de devis'); ?>

<?php $__env->startSection('content'); ?>

<?php
use Illuminate\Support\Str;
?>

<body>

    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\DevisElement::class)): ?>
        <div class="container-fluid px-4 py-4">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1"><i class="fas fa-list-ul text-primary me-2"></i>Éléments de Devis</h2>
                            <p class="text-muted mb-0">Gestion des éléments prédéfinis pour les devis</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addElementModal">
                            <i class="fas fa-plus me-2"></i>Nouvel Élément
                        </button>
                    </div>
                </div>
            </div>

            <!-- Elements Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="elementsTable" class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Code</th>
                                    <th>Prix Unitaire</th>
                                    <th>Statut</th>
                                    <th>Créé par</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($element->nom); ?></strong>
                                        <?php if($element->description): ?>
                                        <br><small class="text-muted"><?php echo e(Str::limit($element->description, 50)); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge bg-secondary"><?php echo e($element->code ?? 'N/A'); ?></span></td>
                                    <td><strong><?php echo e(number_format($element->prix_unitaire, 0, ',', ' ')); ?> FCFA</strong></td>
                                    <td>
                                        <?php if($element->actif): ?>
                                        <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                        <span class="badge bg-warning">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($element->user->name ?? 'N/A'); ?></td>
                                    <td><small><?php echo e($element->created_at->format('d/m/Y')); ?></small></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editElementModal"
                                                data-id="<?php echo e($element->id); ?>"
                                                data-nom="<?php echo e($element->nom); ?>"
                                                data-code="<?php echo e($element->code); ?>"
                                                data-prix="<?php echo e($element->prix_unitaire); ?>"
                                                data-description="<?php echo e($element->description); ?>"
                                                data-actif="<?php echo e($element->actif); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <form action="<?php echo e(route('devis_elements.destroy', $element->id)); ?>" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <?php echo e($elements->links()); ?>

                </div>
            </div>
        </div>

        <!-- Add Element Modal -->
        <div class="modal fade" id="addElementModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('devis_elements.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header">
                            <h5 class="modal-title">Nouvel Élément de Devis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom" required 
                                       placeholder="Ex: CS ANESTHESIQUE EN INTERNE">
                            </div>
                            
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" name="code" 
                                       placeholder="Ex: KC, KA">
                            </div>
                            
                            <div class="mb-3">
                                <label for="prix_unitaire" class="form-label">Prix Unitaire (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="prix_unitaire" required min="0" 
                                       placeholder="Ex: 25000">
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" 
                                          placeholder="Description optionnelle"></textarea>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="actif" value="1" checked id="actif_add">
                                <label class="form-check-label" for="actif_add">
                                    Actif
                                </label>
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

        <!-- Edit Element Modal -->
        <div class="modal fade" id="editElementModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editElementForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier l'Élément</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom" id="edit_nom" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_code" class="form-label">Code</label>
                                <input type="text" class="form-control" name="code" id="edit_code">
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_prix_unitaire" class="form-label">Prix Unitaire (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="prix_unitaire" id="edit_prix_unitaire" required min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="actif" value="1" id="edit_actif">
                                <label class="form-check-label" for="edit_actif">
                                    Actif
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const editModal = document.getElementById('editElementModal');

    editModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        // Get data attributes
        const id = button.getAttribute('data-id');
        const nom = button.getAttribute('data-nom');
        const code = button.getAttribute('data-code');
        const prix = button.getAttribute('data-prix');
        const description = button.getAttribute('data-description');
        const actif = button.getAttribute('data-actif');

        // Update form action
        const form = editModal.querySelector('#editElementForm');
        form.action = `/admin/devis-elements/${id}`;

        // Fill fields
        editModal.querySelector('#edit_nom').value = nom ?? '';
        editModal.querySelector('#edit_code').value = code ?? '';
        editModal.querySelector('#edit_prix_unitaire').value = prix ?? '';
        editModal.querySelector('#edit_description').value = description ?? '';
        editModal.querySelector('#edit_actif').checked = actif == 1;
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/devis_elements/index.blade.php ENDPATH**/ ?>