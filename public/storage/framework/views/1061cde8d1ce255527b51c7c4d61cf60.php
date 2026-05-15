<?php $__env->startSection('title', 'CMCU | Liste des devis'); ?>

<?php $__env->startSection('content'); ?>

<body>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\Devi::class)): ?>
        <div class="container-fluid px-4 py-4">
            <h1 class="text-center mb-4">LISTE DES DEVIS</h1>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h6>Brouillons</h6>
                            <h3><?php echo e($devis->where('statut', 'brouillon')->count()); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h6>En attente</h6>
                            <h3><?php echo e($devis->where('statut', 'en_attente')->count()); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h6>Validés</h6>
                            <h3><?php echo e($devis->where('statut', 'valide')->count()); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h6>Refusés</h6>
                            <h3><?php echo e($devis->where('statut', 'refuse')->count()); ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Devis Table -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="devisTable" class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Patient</th>
                                    <th>Médecin</th>
                                    <th>Montant</th>
                                    <th>Réduction</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $devis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $devi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($devi->code); ?></strong></td>
                                    <td>
                                        <?php if($devi->patient): ?>
                                        <?php echo e($devi->patient->name); ?> <?php echo e($devi->patient->prenom); ?>

                                        <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($devi->medecin): ?>
                                        Dr. <?php echo e($devi->medecin->name); ?>

                                       
                                        
                                        <!-- helps gestionnaires identify devis that can't be sent for validation -->
                                        <?php elseif(!$devi->medecin_id && $devi->statut == 'brouillon'): ?>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Pas de médecin assigné
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">Non assigné</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo e(number_format($devi->montant_apres_reduction, 0, ',', ' ')); ?> FCFA</strong>
                                        </div>
                                        <?php if($devi->pourcentage_reduction > 0): ?>
                                        <small class="text-muted">
                                            <del><?php echo e(number_format($devi->montant_avant_reduction, 0, ',', ' ')); ?> FCFA</del>
                                        </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($devi->pourcentage_reduction > 0): ?>
                                        <span class="badge bg-info">-<?php echo e($devi->pourcentage_reduction); ?>%</span>
                                        <?php else: ?>
                                        <span class="text-muted">Aucune</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($devi->statut == 'brouillon'): ?>
                                        <span class="badge bg-secondary">Brouillon</span>
                                        <?php elseif($devi->statut == 'en_attente'): ?>
                                        <span class="badge bg-warning">En attente</span>
                                        <?php elseif($devi->statut == 'valide'): ?>
                                        <span class="badge bg-success">Validé</span>
                                        <?php elseif($devi->statut == 'refuse'): ?>
                                            <span class="badge bg-danger">Refusé</span>
                                            <?php if($devi->commentaire_medecin): ?>
                                                <i class="fas fa-comment-dots text-danger ms-1" 
                                                title="Raison: <?php echo e($devi->commentaire_medecin); ?>" 
                                                data-bs-toggle="tooltip"></i>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><small><?php echo e($devi->created_at->format('d/m/Y')); ?></small></td>
                                    
                                    <td class="text-center">

                                        <!-- Edit (Gestionnaire / Admin) -->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Devi::class)): ?>
                                            <?php if($devi->statut == 'brouillon' || $devi->statut == 'refuse'): ?>
                                                

                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary btn-edit-devis"
                                                        data-bs-target="#devisModal"
                                                        data-bs-toggle="modal" 
                                                        data-id="<?php echo e($devi->id); ?>"
                                                        title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php endif; ?>

                                            <!-- Send for validation (brouillon → en_attente) -->
                                            <?php if($devi->statut == 'brouillon'): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success action-btn"
                                                        data-action="<?php echo e(route('devis.envoyer_validation', $devi->id)); ?>"
                                                        data-method="POST"
                                                        data-confirm="Envoyer ce devis au médecin pour validation ?"
                                                        title="Envoyer pour validation">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            <?php endif; ?>

                                            <!-- Cancel send (en_attente → brouillon) -->
                                            <?php if($devi->statut == 'en_attente' && $devi->user_id == Auth::id()): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-warning action-btn"
                                                        data-action="<?php echo e(route('devis.annuler_envoi', $devi->id)); ?>"
                                                        data-method="POST"
                                                        data-confirm="Annuler l'envoi ? Le devis reviendra en brouillon."
                                                        title="Annuler l'envoi">
                                                    <i class="fas fa-undo-alt"></i>
                                                </button>
                                            <?php endif; ?>

                                            <!-- Cancel refusal (refuse → brouillon) -->
                                            <?php if($devi->statut == 'refuse' && $devi->user_id == Auth::id()): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-info action-btn"
                                                        data-action="<?php echo e(route('devis.annuler_refus', $devi->id)); ?>"
                                                        data-method="POST"
                                                        data-confirm="Réinitialiser ce devis refusé ? Il reviendra en brouillon."
                                                        title="Réinitialiser le devis refusé">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <!-- Undo validation (assigned doctor OR admin) -->
                                        <?php if($devi->statut == 'valide' && (
                                                ($devi->medecin_id == Auth::id() && Auth::user()->role_id == 2)
                                                || Auth::user()->role_id == 1
                                            )): ?>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-warning action-btn"
                                                    data-action="<?php echo e(route('devis.annuler_validation', $devi->id)); ?>"
                                                    data-method="POST"
                                                    data-confirm="Annuler la validation de ce devis ?"
                                                    title="Annuler validation">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Validate / Reduce / Refuse (assigned doctor OR admin) -->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('valider', \App\Models\Devi::class)): ?>
                                            <?php if($devi->statut == 'en_attente' && (
                                                    ($devi->medecin_id == Auth::id() && Auth::user()->role_id == 2)
                                                    || Auth::user()->role_id == 1
                                                )): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#reductionModal"
                                                        data-devi-id="<?php echo e($devi->id); ?>"
                                                        data-montant="<?php echo e($devi->montant_avant_reduction); ?>"
                                                        title="Appliquer réduction">
                                                    <i class="fas fa-percent"></i>
                                                </button>

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#validerModal"
                                                        data-devi-id="<?php echo e($devi->id); ?>"
                                                        title="Valider">
                                                    <i class="fas fa-check"></i>
                                                </button>

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#refuserModal"
                                                        data-devi-id="<?php echo e($devi->id); ?>"
                                                        title="Refuser">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <!-- View (always visible) -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info view-devis-btn"
                                                data-devi='<?php echo json_encode($devi, 15, 512) ?>'
                                                title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print', \App\Models\Devi::class)): ?>
                                            <?php if($devi->statut == 'valide'): ?>
                                                <a href="<?php echo e(route('devis.print', $devi->id)); ?>"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Imprimer">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <!-- Delete -->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', \App\Models\Devi::class)): ?>
                                            <?php if($devi->statut == 'brouillon' || $devi->statut == 'refuse'): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger action-btn"
                                                        data-action="<?php echo e(route('devis.destroy', $devi->id)); ?>"
                                                        data-method="DELETE"
                                                        data-confirm="Êtes-vous sûr de vouloir supprimer ce devis ?"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <?php echo e($devis->links()); ?>

                </div>
            </div>
        </div>

        
        <form id="actionForm" method="POST" action="" style="display:none;">
            <?php echo csrf_field(); ?>
        </form>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Devi::class)): ?>
        <div class="text-center table_link_right">
            <button type="button" 
                    data-bs-toggle="modal" 
                    data-bs-target="#devisModal" 
                    data-mode="create"
                    class="btn btn-primary me-1" 
                    title="Créer un nouveau devis">
                <i class="fas fa-plus me-2"></i>Nouveau Devis
            </button>
        </div>
        <?php endif; ?>

        
        <!-- Main Devis Modal (Create/Edit/Print) -->
        <?php echo $__env->make('admin.devis.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Reduction Modal (Doctor) -->
        <?php echo $__env->make('admin.devis.modals.reduction', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Validation Modal (Doctor) -->
        <?php echo $__env->make('admin.devis.modals.validation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Refusal Modal (Doctor) -->
        <?php echo $__env->make('admin.devis.modals.refusal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- View Devis Modal -->
        <div class="modal fade" id="viewDevisModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails du Devis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="viewDevisContent">
                    <!-- Content loaded via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
</body>



<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('admin/js/devis/convert_chiffre_lettre.js')); ?>"></script>
<script src="<?php echo e(asset('admin/js/devis/devis.js')); ?>"></script>

<script>
waitForjQuery(function() {
    $(document).on('click', '.action-btn', function(e) {
        e.preventDefault();

        var action     = $(this).data('action');
        var method     = ($(this).data('method') || 'POST').toUpperCase();
        var confirmMsg = $(this).data('confirm');

        if (confirmMsg && !confirm(confirmMsg)) {
            return;
        }

        var $form = $('#actionForm');

        // Set target URL
        $form.attr('action', action);

        // Remove any previous method spoof field, then add the right one
        $form.find('input[name="_method"]').remove();
        if (method !== 'POST') {
            $form.append(
                $('<input>', { type: 'hidden', name: '_method', value: method })
            );
        }

        $form.submit();
    });
});
</script>

<script>
console.log('Loading view devis functionality...');

// Define viewDevis function in GLOBAL scope
window.viewDevis = function(devi) {
    console.log('viewDevis called with:', devi);
    
    if (!devi) {
        console.error('No devis data provided');
        return;
    }
    
    try {
        let statusClass = 'secondary';
        let statusText = devi.statut || 'N/A';
        
        if (devi.statut === 'valide') {
            statusClass = 'success';
            statusText = 'Validé';
        } else if (devi.statut === 'en_attente') {
            statusClass = 'warning';
            statusText = 'En attente';
        } else if (devi.statut === 'refuse') {
            statusClass = 'danger';
            statusText = 'Refusé';
        } else if (devi.statut === 'brouillon') {
            statusClass = 'secondary';
            statusText = 'Brouillon';
        }
        
        const patientName = devi.patient ? (devi.patient.name + ' ' + devi.patient.prenom) : 'N/A';
        const medecinName = devi.medecin ? ('Dr. ' + devi.medecin.name + ' ' + (devi.medecin.prenom || '')) : 'Non assigné';
        
        // IMPORTANT: Laravel converts camelCase to snake_case in JSON
        const ligneDevis = devi.ligne_devis || [];
        
        console.log('Ligne devis count:', ligneDevis.length);
        
        // Build line items table
        let lignesHtml = '';
        if (ligneDevis.length > 0) {
            lignesHtml = `
                <h6 class="mt-3">Éléments du devis:</h6>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Élément</th>
                            <th class="text-end">Qté</th>
                            <th class="text-end">Prix U.</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            ligneDevis.forEach(function(ligne, index) {
                const quantite = parseFloat(ligne.quantite) || 0;
                const prixU = parseFloat(ligne.prix_u) || 0;
                const total = quantite * prixU;
                
                lignesHtml += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${ligne.element || 'N/A'}</td>
                        <td class="text-end">${quantite}</td>
                        <td class="text-end">${parseInt(prixU).toLocaleString('fr-FR')} FCFA</td>
                        <td class="text-end">${parseInt(total).toLocaleString('fr-FR')} FCFA</td>
                    </tr>
                `;
            });
            
            lignesHtml += `
                    </tbody>
                </table>
            `;
        } else {
            lignesHtml = `
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle"></i> Aucun élément dans ce devis
                </div>
            `;
        }
        
        // Build hospitalization section
        let hospitalizationHtml = '';
        const hasHospitalization = (devi.nbr_chambre > 0) || (devi.nbr_visite > 0) || (devi.nbr_ami_jour > 0);
        
        if (hasHospitalization) {
            hospitalizationHtml = `
                <h6 class="mt-3">Hospitalisation:</h6>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th class="text-end">Quantité</th>
                            <th class="text-end">Prix U.</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            if (devi.nbr_chambre > 0) {
                const totalChambre = (parseFloat(devi.nbr_chambre) || 0) * (parseFloat(devi.pu_chambre) || 0);
                hospitalizationHtml += `
                    <tr>
                        <td>Chambre</td>
                        <td class="text-end">${devi.nbr_chambre}</td>
                        <td class="text-end">${parseInt(devi.pu_chambre).toLocaleString('fr-FR')} FCFA</td>
                        <td class="text-end">${parseInt(totalChambre).toLocaleString('fr-FR')} FCFA</td>
                    </tr>
                `;
            }
            
            if (devi.nbr_visite > 0) {
                const totalVisite = (parseFloat(devi.nbr_visite) || 0) * (parseFloat(devi.pu_visite) || 0);
                hospitalizationHtml += `
                    <tr>
                        <td>Visite</td>
                        <td class="text-end">${devi.nbr_visite}</td>
                        <td class="text-end">${parseInt(devi.pu_visite).toLocaleString('fr-FR')} FCFA</td>
                        <td class="text-end">${parseInt(totalVisite).toLocaleString('fr-FR')} FCFA</td>
                    </tr>
                `;
            }
            
            if (devi.nbr_ami_jour > 0) {
                const totalAmi = (parseFloat(devi.nbr_ami_jour) || 0) * (parseFloat(devi.pu_ami_jour) || 0);
                hospitalizationHtml += `
                    <tr>
                        <td>AMI-JOUR</td>
                        <td class="text-end">${devi.nbr_ami_jour}</td>
                        <td class="text-end">${parseInt(devi.pu_ami_jour).toLocaleString('fr-FR')} FCFA</td>
                        <td class="text-end">${parseInt(totalAmi).toLocaleString('fr-FR')} FCFA</td>
                    </tr>
                `;
            }
            
            hospitalizationHtml += `
                    </tbody>
                </table>
            `;
        }
        
        // Build comments section
        let commentsHtml = '';
        if (devi.commentaire_medecin) {
            commentsHtml = `
                <div class="alert alert-info mt-3">
                    <strong><i class="fas fa-comment-medical"></i> Commentaire du médecin:</strong><br>
                    ${devi.commentaire_medecin}
                </div>
            `;
        }
        
        let content = `
            <div class="row mb-3">
                <div class="col-6"><strong>Code:</strong> ${devi.code || 'N/A'}</div>
                <div class="col-6"><strong>Type:</strong> 
                    <span class="badge bg-primary">${devi.acces || 'N/A'}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6"><strong>Nom:</strong> ${devi.nom || 'N/A'}</div>
                <div class="col-6"><strong>Statut:</strong> 
                    <span class="badge bg-${statusClass}">
                        ${statusText}
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6"><strong>Patient:</strong> ${patientName}</div>
                <div class="col-6"><strong>Médecin:</strong> ${medecinName}</div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <strong>Date de création:</strong> 
                    ${devi.created_at ? new Date(devi.created_at).toLocaleDateString('fr-FR') : 'N/A'}
                </div>
            </div>
            <hr>
            ${lignesHtml}
            ${hospitalizationHtml}
            ${commentsHtml}
            <hr>
            <h6>Résumé des montants:</h6>
            <div class="row">
                <div class="col-6">
                    <strong>Montant initial:</strong><br>
                    <span class="fs-5">${parseInt(devi.montant_avant_reduction || 0).toLocaleString('fr-FR')} FCFA</span>
                </div>
                <div class="col-6">
                    <strong>Réduction appliquée:</strong><br>
                    <span class="fs-5 ${devi.pourcentage_reduction > 0 ? 'text-danger' : ''}">${devi.pourcentage_reduction || 0}%</span>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="alert alert-success mb-0">
                        <strong>Montant final à payer:</strong><br>
                        <span class="fs-4">${parseInt(devi.montant_apres_reduction || 0).toLocaleString('fr-FR')} FCFA</span>
                    </div>
                </div>
            </div>
        `;
        
        // Update modal content
        $('#viewDevisContent').html(content);
        
        // Show modal using Bootstrap 5
        const modalEl = document.getElementById('viewDevisModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            console.error('Modal element not found');
        }
        
    } catch (error) {
        console.error('Error in viewDevis:', error);
        alert('Erreur lors de l\'affichage du devis');
    }
};

// Attach event listeners using delegation
waitForjQuery(function() {
    $(document).ready(function() {
        console.log('Attaching view devis button listeners...');
        
        // Use event delegation for dynamically loaded content
        $(document).on('click', '.view-devis-btn', function(e) {
            e.preventDefault();
            console.log('View button clicked');
            
            const deviData = $(this).data('devi');
            console.log('Devis data from button:', deviData);
            
            if (deviData) {
                viewDevis(deviData);
            } else {
                console.error('No devis data found on button');
                alert('Erreur: Données du devis introuvables');
            }
        });
        
        console.log('View devis listeners attached');
    });
});

console.log('View devis functionality loaded');
</script>


<script>
// Initialize DataTable ONCE
waitForjQuery(function() {
    $(document).ready(function() {
        if ($('#devisTable').length && !$.fn.DataTable.isDataTable('#devisTable')) {
            console.log('Initializing devisTable...');
            $('#devisTable').DataTable({
                language: { 
                    url: "<?php echo e(asset('vendor/i18n/fr_fr.json')); ?>" 
                },
                pageLength: 10,
                responsive: true,
                order: [[6, 'desc']]
            });
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/devis/index.blade.php ENDPATH**/ ?>