<?php $__env->startSection('link'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/css/examens_scannes_styles.css')); ?>">
<?php $__env->stopSection(); ?>
<div id="examens_scannes_form" style="display: none;">

    <div class="row">
        <?php $__currentLoopData = $examens_scannes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 mt-3">
            <div class="container_">
                <div class="button_container_add border rounded-lg">
                    <!-- FIXED: Changed from asset($examen->image) to asset('storage/' . $examen->image) -->
                    <div class="d-flex align-items-center add_button justify-content-center" 
                         data-bs-toggle="popover" 
                         title="Description" 
                         data-bs-content="<?php echo e($examen->description); ?>" 
                         style="cursor: pointer;">
                        <img src="<?php echo e(asset('storage/' . $examen->image)); ?>" 
                             alt="Examen Scanné" 
                             class="image"
                             style="max-width: 100%; max-height: 300px; object-fit: contain;">
                    </div>
                </div>

                <div class="button_container d-flex justify-content-between">
                    <a class="btn overlay btn-outline-dark" 
                       href="<?php echo e(asset('storage/' . $examen->image)); ?>" 
                       target="_blank">
                        <i class="fas fa-eye"></i>
                    </a>
                    <form action="<?php echo e(route('examens.destroy', $examen->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?> 
                        <?php echo method_field('DELETE'); ?>
                        <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">
                        <button class="btn overlay btn-outline-dark" 
                                type="submit">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                <div class="overlay description"><?php echo e($examen->nom); ?></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <div class="pt-3 d-flex justify-content-between">
        <button class="btn btn-info" 
                data-bs-toggle="modal" 
                data-bs-target="#modal_examens_scannes" 
                title="Ajouter une image">
            <i class="fas fa-plus"></i>
        </button>
        <?php echo e($examens_scannes->links()); ?>

    </div>

    <!-- The Modal -->
    <div class="modal fade" id="modal_examens_scannes">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter une nouvelle image</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form action="<?php echo e(route('examens.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <?php echo Html::input('hidden', 'patient_id', $patient->id); ?>

                                
                                <div class="mb-3">
                                    <label class="form-label" for="customFile">Choisir une image</label>
                                    <input type="file" 
                                           class="form-control" 
                                           onchange="handleFiles(this.files)" 
                                           id="customFile" 
                                           name="image"
                                           accept="image/*"
                                           required>
                                </div>
                                
                                <div class="mb-3">
                                    <input type="text" 
                                           class="form-control" 
                                           name="nom" 
                                           id="libelle" 
                                           placeholder="Libellé"
                                           required>
                                </div>
                                
                                <div class="mb-3">
                                    <textarea class="form-control" 
                                              rows="5" 
                                              id="description" 
                                              name="description" 
                                              placeholder="Description"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <div>
                                    <p>
                                        <span id="preview">
                                            <img id="img1" 
                                                 src="<?php echo e(asset('admin/images/default.png')); ?>" 
                                                 alt="Aperçu de l'image" 
                                                 style="width: 100%; max-height: 300px; object-fit: contain;" />
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Ajouter</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview function
function handleFiles(files) {
    if (files.length > 0) {
        const file = files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('img1').src = e.target.result;
        };
        
        reader.readAsDataURL(file);
    }
}

// Initialize popovers
document.addEventListener('DOMContentLoaded', function() {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});
</script><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcu\resources\views/admin/patients/partials/examens_scannes.blade.php ENDPATH**/ ?>