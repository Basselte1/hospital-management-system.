

<?php $__env->startSection('title', 'CMCU | Factures Consultation'); ?>

<?php $__env->startSection('content'); ?>

<body>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\User::class)): ?>
        <div class="container_fluid">
            <h1 class="text-center">FACTURES DE CONSULTATION</h1>
            <hr>
            
            <!-- Messages de succès/erreur -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="container pt-3">
                <!-- Filtres de recherche -->
                <div class="row">
                    <form action="<?php echo e(route('search.date')); ?>" method="post" class="w-100">
                        <?php echo csrf_field(); ?>
                        <div class="d-flex align-items-end flex-wrap gap-3">
                            <div>
                                <label for="start-date" class="form-label">Date de début</label>
                                <input type="date" id="start-date" name="start-date" 
                                       class="form-control" 
                                       value="<?php echo e(request('start-date', $startDate->format('Y-m-d'))); ?>">
                            </div>
                            
                            <div>
                                <label for="end-date1" class="form-label">Date de fin</label>
                                <input type="date" id="end-date1" name="end-date" 
                                       class="form-control"
                                       value="<?php echo e(request('end-date', $endDate->format('Y-m-d'))); ?>">
                            </div>
                            
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <?php if(isset($factureConsultations)): ?>
                <div class="col-lg-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="mb-0">
                            Période du <strong><?php echo e($startDate->format('d/m/Y')); ?></strong> 
                            au <strong><?php echo e($endDate->format('d/m/Y')); ?></strong>
                            <span class="badge bg-primary ms-2"><?php echo e($factureConsultations->total()); ?> facture(s)</span>
                        </p>
                    </div>
                    
                    <div class="table-responsive">
                        <i class="table_info text-muted">Les montants sont exprimés en <b>FCFA</b></i>
                        
                        <table id="myTable" class="table table-hover table-bordered display" cellspacing="0" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>ACTIONS</th>
                                    <th>PATIENT</th>
                                    <th>MOTIF</th>
                                    <th>MONTANT</th>
                                    <th>PART ASS.</th>
                                    <th>PART PATIENT</th>
                                    <th>AVANCÉ</th>
                                    <th>RESTE</th>
                                    <th>MODE PAIEMENT</th>
                                    <th>MÉDECIN</th>
                                    <th>DATE</th>
                                    <th>
                                        STATUT
                                        <br>
                                        <select id="statut-filter" class="form-select form-select-sm">
                                            <option value="">Tous les statuts</option>
                                            <option value="soldée">Soldées</option>
                                            <option value="non soldée">Non soldées</option>
                                        </select>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $factureConsultations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($facture->isSoldee() ? 'table-success' : ''); ?>">
                                    <td><strong><?php echo e($facture->numero); ?></strong></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if($facture->isImprimable()): ?>
                                                <!-- Bouton d'impression (vert) pour factures soldées -->
                                                <a class="btn btn-success btn-sm" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Imprimer la facture soldée" 
                                                   href="<?php echo e(route('factures.consultation_pdf', $facture->id)); ?>">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            <?php else: ?>
                                                <!-- Bouton d'impression désactivé pour factures non soldées -->
                                                <button class="btn btn-secondary btn-sm" 
                                                        disabled 
                                                        data-bs-toggle="tooltip" 
                                                        title="Impression disponible une fois la facture soldée">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $facture)): ?>
                                                <?php if($facture->isModifiable()): ?>
                                                    <!-- Bouton de modification (bleu) pour factures non soldées -->
                                                    <button type="button" 
                                                            class="btn btn-info btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            title="Modifier la facture" 
                                                            data-bs-target="#edit_facture_modal" 
                                                            data-id-facture="<?php echo e($facture->id); ?>" 
                                                            data-nom="<?php echo e($facture->patient->name); ?>" 
                                                            data-montant="<?php echo e($facture->montant); ?>" 
                                                            data-reste="<?php echo e($facture->reste); ?>" 
                                                            data-mode_paiement="<?php echo e($facture->mode_paiement); ?>" 
                                                            data-prise_en_charge="<?php echo e($facture->patient->prise_en_charge ?? 0); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    
                                                    <!-- Bouton de suppression pour factures non soldées -->
                                                    <form action="<?php echo e(route('factures.destroy', $facture->id)); ?>" 
                                                          method="post" 
                                                          class="d-inline">
                                                        <?php echo csrf_field(); ?> 
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" 
                                                                class="btn btn-danger btn-sm" 
                                                                data-bs-toggle="tooltip" 
                                                                title="Supprimer la facture" 
                                                                onclick="return confirm('Voulez-vous vraiment supprimer cette facture ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <!-- Boutons désactivés pour factures soldées -->
                                                    <button class="btn btn-secondary btn-sm" 
                                                            disabled 
                                                            data-bs-toggle="tooltip" 
                                                            title="Modification impossible : facture soldée">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-secondary btn-sm" 
                                                            disabled 
                                                            data-bs-toggle="tooltip" 
                                                            title="Suppression impossible : facture soldée">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td> <?php echo e($facture->patient ? $facture->patient->name : 'Non défini'); ?></td> <!--<?php echo e($facture->patient->name); ?>-->
                                    <td><?php echo e($facture->details_motif ?? 'Consultation'); ?></td>
                                    <td class="text-end"><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?></td>
                                    <td class="text-end"><?php echo e(number_format($facture->assurancec, 0, ',', ' ')); ?></td>
                                    <td class="text-end"><?php echo e(number_format($facture->assurec, 0, ',', ' ')); ?></td>
                                    <td class="text-end"><?php echo e(number_format($facture->avance, 0, ',', ' ')); ?></td>
                                    <td class="text-end">
                                        <strong class="<?php echo e($facture->reste > 0 ? 'text-danger' : 'text-success'); ?>">
                                            <?php echo e(number_format($facture->reste, 0, ',', ' ')); ?>

                                        </strong>
                                    </td>
                                    <td>
                                        <?php echo e($facture->mode_paiement === 'bon de prise en charge' ? 'BPC' : ucfirst($facture->mode_paiement)); ?>

                                        <?php if($facture->mode_paiement_info_sup): ?>
                                            <?php $__currentLoopData = preg_split("/[\/]{2} /", $facture->mode_paiement_info_sup, 0, PREG_SPLIT_NO_EMPTY); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info_sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <br><small class="text-muted"><?php echo e($info_sup); ?></small>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($facture->medecin_r); ?></td>
                                    <td style="white-space: nowrap"><?php echo e($facture->created_at->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <?php if($facture->isSoldee()): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Soldée
                                            </span>
                                            <?php if($facture->is_printed): ?>
                                                <br><small class="text-muted">Imprimée le <?php echo e($facture->printed_at->format('d/m/Y')); ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-circle me-1"></i>Non soldée
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">TOTAL:</th>
                                    <th class="text-end"><?php echo e(number_format($factureConsultations->sum('montant'), 0, ',', ' ')); ?></th>
                                    <th class="text-end"><?php echo e(number_format($factureConsultations->sum('assurancec'), 0, ',', ' ')); ?></th>
                                    <th class="text-end"><?php echo e(number_format($factureConsultations->sum('assurec'), 0, ',', ' ')); ?></th>
                                    <th class="text-end"><?php echo e(number_format($factureConsultations->sum('avance'), 0, ',', ' ')); ?></th>
                                    <th class="text-end text-danger"><?php echo e(number_format($factureConsultations->sum('reste'), 0, ',', ' ')); ?></th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>
                        
                        <!-- Formulaire pour imprimer le bilan -->
                        <form class="mb-3 mt-4" method="POST" action="<?php echo e(route('bilan_consultation.pdf')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-file-pdf me-2"></i>Générer un bilan journalier
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <label for="day" class="form-label">Date</label>
                                            <select name="day" id="day" class="form-select" required>
                                                <option value="">Choisir une date</option>
                                                <?php $__currentLoopData = $lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($list); ?>"><?php echo e(Carbon\Carbon::parse($list)->format('d/m/Y')); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="service" class="form-label">Service</label>
                                            <select name="service" id="service" class="form-select" required>
                                                <option value="Tout" selected>Tous les services</option>
                                                <option value="Consultation">Consultation</option>
                                                <option value="Acte">Acte</option>
                                                <option value="Examen">Examen</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-print me-2"></i>Imprimer le bilan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Pagination 
                        <div class="d-flex justify-content-center">
                            <?php echo e($factureConsultations->links()); ?>

                        </div>-->
                    </div>
                </div>
                <?php endif; ?> 
            </div>
        </div>
        
        <!-- Modal de modification -->
        <div id="edit_facture_modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="edit_facture_modallabel">Nouveau versement</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="edit_facture_form" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Important:</strong> Cette facture n'est pas encore soldée. Vous pouvez la modifier.
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="montant" class="form-label">Montant total</label>
                                        <input name="montant" id="montant" class="form-control" type="number" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="part_patient" class="form-label">Part patient</label>
                                        <input name="part_patient" id="part_patient" class="form-control" type="number" min="0" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="reste" class="form-label">Reste à payer</label>
                                        <input name="reste" id="reste" class="form-control" type="number" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="percu" class="form-label">
                                            Montant versé <span class="text-danger">*</span>
                                        </label>
                                        <input name="percu" id="percu" class="form-control" type="number" min="0" placeholder="0" required>
                                        <small class="text-muted">Entrez le montant que le patient verse maintenant</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mode_paiement" class="form-label">Mode de paiement</label>
                                        <select name="mode_paiement" id="mode_paiement" class="form-select" required>
                                            <optgroup label="Monnaie électronique">
                                                <option value="orange money">Orange Money</option>
                                                <option value="mtn mobile money">MTN Mobile Money</option>
                                            </optgroup>
                                            <optgroup label="Autres moyens">
                                                <option value="espèce">Espèce</option>
                                                <option value="chèque">Chèque</option>
                                                <option value="virement">Virement</option>
                                                <option value="bon de prise en charge">Bon de prise en charge</option>
                                                <option value="autre">Autre</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    
                                    <!-- Champs conditionnels pour chèque -->
                                    <div id="cheque_fields" class="d-none">
                                        <div class="mb-3">
                                            <label for="num_cheque" class="form-label">N° Chèque</label>
                                            <input type="text" name="num_cheque" id="num_cheque" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="emetteur_cheque" class="form-label">Émetteur</label>
                                            <input type="text" name="emetteur_cheque" id="emetteur_cheque" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="banque_cheque" class="form-label">Banque</label>
                                            <input type="text" name="banque_cheque" id="banque_cheque" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <!-- Champ conditionnel pour BPC -->
                                    <div id="bpc_fields" class="d-none">
                                        <div class="mb-3">
                                            <label for="emetteur_bpc" class="form-label">Émetteur BPC</label>
                                            <input type="text" name="emetteur_bpc" id="emetteur_bpc" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Fermer
                            </button>
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-save me-1"></i>Enregistrer
                            </button>
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
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    $(document).ready(function() {
        // Initialiser les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // DataTables
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        
        // Gestion du modal de modification
        $('#edit_facture_modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id_facture = button.data('id-facture');
            var mode_paiement = button.data('mode_paiement');
            var montant_facture = button.data('montant');
            var reste = button.data('reste');
            var prise_en_charge = button.data('prise_en_charge');
            
            $('#montant').val(montant_facture);
            $('#mode_paiement').val(mode_paiement);
            $('#reste').val(reste);
            
            if (isNaN(prise_en_charge)) {
                $('#montant').attr('data-prise_en_charge', 0);
                $('#part_patient').val(montant_facture);
            } else {
                $('#montant').attr('data-prise_en_charge', prise_en_charge);
                $('#part_patient').val(montant_facture * (100 - prise_en_charge) / 100);
            }
            
            $('#edit_facture_form').attr("action", "<?php echo e(url('admin/factures-consultation')); ?>" + "/" + id_facture);
        });
        
        // Calcul automatique de la part patient
        $('#montant').on('change', function() {
            var PEC = $(this).data('prise_en_charge');
            var montant = $(this).val();
            $('#part_patient').val(montant * (100 - PEC) / 100);
        });
        
        // Afficher/masquer les champs selon le mode de paiement
        $('#mode_paiement').on('change', function() {
            var value = $(this).val();
            $('#cheque_fields').addClass('d-none');
            $('#bpc_fields').addClass('d-none');
            
            if (value === 'chèque') {
                $('#cheque_fields').removeClass('d-none');
            } else if (value === 'bon de prise en charge') {
                $('#bpc_fields').removeClass('d-none');
            }
        });
        
        // Auto-dismiss des alertes après 5 secondes
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Filtrage par statut
        $('#statut-filter').on('change', function() {
            var selectedValue = $(this).val();
            var table = $('#myTable');
            var rows = table.find('tbody tr');

            rows.each(function() {
                var row = $(this);
                var statusCell = row.find('td:last-child');
                var badge = statusCell.find('.badge');
                var isSoldee = badge.hasClass('bg-success');
                var isNonSoldee = badge.hasClass('bg-warning');

                if (selectedValue === '') {
                    // Afficher toutes les lignes
                    row.show();
                } else if (selectedValue === 'soldée' && isSoldee) {
                    row.show();
                } else if (selectedValue === 'non soldée' && isNonSoldee) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/factures/consultation.blade.php ENDPATH**/ ?>