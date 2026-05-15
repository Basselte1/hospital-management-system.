

<?php $__env->startSection('title', 'CMCU | Prescriptions médicales'); ?>

<?php $__env->startSection('content'); ?>

<body>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
        <div class="col-md-12 toppad offset-md-0">
            <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn btn-success float-end">
                <i class="fas fa-arrow-left"></i> Retour au dossier patient
            </a>
        </div>
        
        <div class="container px-0">
            <h1 class="text-center">PRESCRIPTIONS MEDICALES</h1>
            <hr>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12 px-0">
                    <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-bordered table-hover dt-responsive display nowrap td-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>DATE</th>
                                    <th>MEDICAMENT</th>
                                    <th>POSOLOGIE</th>
                                    <th>Horaire</th>
                                    <th>VOIE</th>
                                    <th>Administrations</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php $__empty_1 = true; $__currentLoopData = $prescription_medicales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription_medicale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e(\Carbon\Carbon::parse($prescription_medicale->date)->format('d/m/Y')); ?></td>
                                    <td><?php echo e($prescription_medicale->medicament); ?></td>
                                    <td><?php echo e($prescription_medicale->posologie); ?></td>
                                    <td>
                                        
                                        <?php echo e($prescription_medicale->formatted_time_slots); ?>

                                    </td>
                                    <td><?php echo e($prescription_medicale->voie); ?></td>
                                    <td>
                                        <button title="Afficher la liste des soins administrés" 
                                                class="btn btn_admin_prescription_medicale" 
                                                data-bs-toggle="modal" 
                                                data-bs-admin_list="<?php echo e(json_encode($prescription_medicale->adminPrescriptionMedicales)); ?>" 
                                                data-bs-target="#admin_prescription_medicale">
                                            Détails...
                                        </button>
                                        
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                                        <button title="Saisir un nouveau soin" 
                                                class="btn btn-primary btn-sm rounded-circle btn_admin_prescription_medicale_form" 
                                                data-bs-toggle="modal" 
                                                data-bs-prescription_medicale_id="<?php echo e($prescription_medicale->id); ?>" 
                                                data-bs-target="#admin_prescription_medicale_form">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <?php endif; ?>
                                        
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                                        <a href="<?php echo e(route('prescription_medicale.edit', $prescription_medicale->id)); ?>?patient=<?php echo e($patient->id); ?>" 
                                           class="btn btn-secondary btn-sm rounded-circle" 
                                           title="Modifier la prescription">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucune prescription enregistrée</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <?php if($prescription_medicales instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                        <div class="d-flex justify-content-center">
                            <?php echo e($prescription_medicales->links()); ?>

                        </div>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                    <button type="button" class="btn btn-primary table_link_right" 
                            data-bs-toggle="modal" 
                            data-bs-target="#PrescriptionMedicale">
                        <i class="fas fa-plus"></i> Nouveau enregistrement
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="row pt-4 mt-5 shadow mb-5" style="background-color: rgba(0, 0, 0, 0.05)">
                <div class="col-2">
                    <img src="<?php echo e(asset('admin/images/important.png')); ?>" alt="Important!!!" width="100%">
                </div>
                <div class="col-10 text-center pt-4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                    <div class="float-end">
                        <button title="Modifier" 
                                class="btn btn-secondary rounded-circle float-end" 
                                data-bs-toggle="modal" 
                                data-bs-target="#prescription_medicale_form">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <h3><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></h3>
                    <p>
                        <strong>Allergies : </strong>
                        <span class="text-light p-1 rounded <?php echo e($fiche_prescription_medicale->allergie ? 'bg-danger' : 'bg-primary'); ?>">
                            <?php echo e($fiche_prescription_medicale->allergie ?: 'Aucune allergie déclarée'); ?>

                        </span>
                    </p>
                </div>

                <div class="col-12 mb-3">
                    <div class="row p-3">
                        <div class="col-sm-4">
                            <div class="mx-auto p-3 border bg-white">
                                <h5>Régime</h5>
                                <br>
                                <p><?php echo e($fiche_prescription_medicale->regime ?: 'Aucun régime spécifique'); ?></p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mx-auto p-3 border bg-white">
                                <h5>Consultations spécialisées</h5>
                                <br>
                                <p><?php echo e($fiche_prescription_medicale->consultation_specialise ?: 'Aucune consultation requise'); ?></p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mx-auto p-3 border bg-white">
                                <h5>Autres (Protocoles, Surveillance...)</h5>
                                <br>
                                <p><?php echo e($fiche_prescription_medicale->protocole ?: 'Aucun protocole spécifique'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <?php echo $__env->make('admin.consultations.infirmiers.form.elt_prescription_medicale_form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin.consultations.infirmiers.form.prescription_medicale_form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin.consultations.infirmiers.admin_prescription_medicale', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin.consultations.infirmiers.form.admin_prescription_medicale_form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    // Wait for jQuery to be loaded
    function waitForjQuery(callback) {
        if (typeof jQuery !== 'undefined') {
            callback();
        } else {
            setTimeout(function() { waitForjQuery(callback); }, 100);
        }
    }

    waitForjQuery(function() {
        $(document).ready(function() {
            
            const infirmieres = <?php echo json_encode($infirmieres, 15, 512) ?>;
            
            // Handle "Saisir un nouveau soin" button
            $(document).on("click", ".btn_admin_prescription_medicale_form", function(e) {
                const prescription_medicale_id = $(this).data('bs-prescription_medicale_id');
                
                if (!prescription_medicale_id) {
                    console.error(' ID prescription non trouvé !');
                    return;
                }
                
                // Build URL manually
                const url = '/admin/prescriptions-medicales/' + prescription_medicale_id + '/Admin-PM';
                
                // Set form action
                $("#apm_form").attr('action', url);
                console.log(' Action du formulaire définie:', $("#apm_form").attr('action'));
            });

            // Handle administration details display
            $(document).on("click", ".btn_admin_prescription_medicale", function() {
                let table_body = $('<tbody></tbody>');
                let data = $(this).data('bs-admin_list');

                $('#admin_prescription_medicale_table tbody').empty();
                
                if (!data || data.length === 0) {
                    console.log('Aucune administration trouvée');
                    table_body.append('<tr><td colspan="6" class="text-center">Aucune administration enregistrée</td></tr>');
                    $('#admin_prescription_medicale_table tbody').html(table_body.html());
                    return;
                }
                
                $.each(data, function(index, value) {
                    let dmatin = value.matin == null ? '' : value.matin;
                    let dinfirmiere = infirmieres.find(element => element.id == value.user_id);
                    let ddate = value.created_at ? value.created_at.substring(0, 10) : '';
                    let dapre_midi = value.apre_midi == null ? '' : value.apre_midi;
                    let dsoir = value.soir == null ? '' : value.soir;
                    let dnuit = value.nuit == null ? '' : value.nuit;

                    table_body.append(
                        '<tr>' +
                            '<td>' + ddate + '</td>' +
                            '<td>' + (dinfirmiere ? dinfirmiere.name : 'N/A') + '</td>' +
                            '<td>' + dmatin + '</td>' +
                            '<td>' + dapre_midi + '</td>' +
                            '<td>' + dsoir + '</td>' +
                            '<td>' + dnuit + '</td>' +
                        '</tr>'
                    );
                });
                
                $('#admin_prescription_medicale_table tbody').html(table_body.html());
            });

            // Handle "Informations Importantes" modal
            $("#prescription_medicale_form").on('show.bs.modal', function (event) {
                console.log(' Modal "Informations Importantes" ouvert');
                
                $('#allergie').val('<?php echo e($fiche_prescription_medicale->allergie ?? ''); ?>');
                $('#regime').val('<?php echo e($fiche_prescription_medicale->regime ?? ''); ?>');
                $('#consultation_specialise').val('<?php echo e($fiche_prescription_medicale->consultation_specialise ?? ''); ?>');
                $('#protocole').val('<?php echo e($fiche_prescription_medicale->protocole ?? ''); ?>');
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/consultations/infirmiers/index_prescription_medicale.blade.php ENDPATH**/ ?>