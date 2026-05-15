

<?php $__env->startSection('title', 'CMCU | Factures Examens'); ?>

<?php $__env->startSection('content'); ?>

<body>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\User::class)): ?>
        <div class="container_fluid">
            <h1 class="text-center">FACTURES D'EXAMENS DE LABORATOIRE</h1>
            <hr>

            
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
                <div class="d-flex justify-content-end mb-3">
                    <button type="button"
                            class="btn btn-success btn-md"
                            data-bs-toggle="modal"
                            data-bs-target="#modalNouvelleFactureExamen">
                        <i class="fas fa-plus me-2"></i>Nouvelle facture examen
                    </button>
                </div>
        <div class="container pt-3">

                

                
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="<?php echo e(route('search.date.examen')); ?>" method="post" class="w-100">
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
                </div>

                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-chart-bar me-2"></i>
                                <strong>Générer un bilan journalier des examens</strong>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo e(route('bilan_examen.pdf')); ?>" method="post"
                                      class="d-flex align-items-end flex-wrap gap-3">
                                    <?php echo csrf_field(); ?>
                                    <div>
                                        <label for="bilan-day" class="form-label fw-bold">
                                            Sélectionner la date du bilan
                                        </label>
                                        <select name="day" id="bilan-day" class="form-select" style="min-width:180px">
                                            <?php $__currentLoopData = $lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($date); ?>"
                                                    <?php echo e($date === now()->format('Y-m-d') ? 'selected' : ''); ?>>
                                                    <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('d F Y')); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-info text-white">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        Exporter le bilan PDF
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php if(isset($factureExamens)): ?>
                <div class="col-lg-12 mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="mb-0">
                            Période du <strong><?php echo e($startDate->format('d/m/Y')); ?></strong>
                            au <strong><?php echo e($endDate->format('d/m/Y')); ?></strong>
                            <span class="badge bg-info ms-2"><?php echo e($factureExamens->total()); ?> facture(s)</span>
                        </p>
                    </div>

                    <div class="table-responsive">
                        <i class="table_info text-muted">Les montants sont exprimés en <b>FCFA</b></i>

                        <table id="myTableExamen" class="table table-hover table-bordered display"
                               cellspacing="0" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>ACTIONS</th>
                                    <th>PATIENT</th>
                                    <th>EXAMENS</th>
                                    <th>MONTANT TOTAL</th>
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
                                <?php $__currentLoopData = $factureExamens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($facture->isSoldee() ? 'table-success' : ''); ?>">
                                    <td><strong><?php echo e($facture->numero); ?></strong></td>

                                    
                                    <td>
                                        <div class="btn-group" role="group">

                                            
                                            <?php if($facture->isProforma()): ?>
                                                <a class="btn btn-warning btn-sm"
                                                   title="PROFORMA — reste: <?php echo e(number_format($facture->reste,0,',',' ')); ?> FCFA"
                                                   data-bs-toggle="tooltip"
                                                   href="<?php echo e(route('factures.consultation_pdf', $facture->id)); ?>">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                            <?php else: ?>
                                                <a class="btn btn-success btn-sm"
                                                   title="Imprimer la facture soldée"
                                                   data-bs-toggle="tooltip"
                                                   href="<?php echo e(route('factures.consultation_pdf', $facture->id)); ?>">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            <?php endif; ?>

                                            
                                            <a class="btn btn-info btn-sm"
                                               href="<?php echo e(route('factures.apercu_consultation', $facture->id)); ?>"
                                               title="Voir l'aperçu" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            
                                            <button type="button"
                                                    class="btn btn-secondary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalAjoutElement"
                                                    data-facture-id="<?php echo e($facture->id); ?>"
                                                    data-facture-numero="<?php echo e($facture->numero); ?>"
                                                    data-patient-id="<?php echo e($facture->patient_id); ?>"
                                                    data-medecin="<?php echo e($facture->medecin_r ?? ''); ?>"
                                                    title="Ajouter un examen">
                                                <i class="fas fa-plus"></i>
                                            </button>

                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $facture)): ?>
                                                <?php if($facture->isModifiable()): ?>
                                                    <button type="button"
                                                            class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            title="Modifier le règlement"
                                                            data-bs-target="#edit_facture_modal"
                                                            data-facture-id="<?php echo e($facture->id); ?>"
                                                            data-facture-numero="<?php echo e($facture->numero); ?>"
                                                            data-montant="<?php echo e($facture->montant); ?>"
                                                            data-avance="<?php echo e($facture->avance); ?>"
                                                            data-reste="<?php echo e($facture->reste); ?>"
                                                            data-assurec="<?php echo e($facture->assurec); ?>"
                                                            data-mode-paiement="<?php echo e($facture->mode_paiement); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $facture)): ?>
                                                <?php if(!$facture->isSoldee()): ?>
                                                    <form action="<?php echo e(route('factures.destroy', $facture->id)); ?>"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Supprimer cette facture ?')">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                        </div>
                                    </td>

                                    
                                    <td>
                                        <?php if($facture->patient): ?>
                                            <strong><?php echo e($facture->patient->name); ?>

                                                <?php echo e($facture->patient->prenom); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                N° <?php echo e($facture->patient->numero_dossier ?? '—'); ?>

                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">
                                                <?php echo e($facture->patient_name ?? '[Patient supprimé]'); ?>

                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td style="max-width:220px">
                                        <?php if($facture->lignes->count()): ?>
                                            <ul class="mb-0 ps-3 small">
                                                <?php $__currentLoopData = $facture->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($ligne->libelle); ?>

                                                        <span class="text-muted">
                                                            (<?php echo e(number_format($ligne->montant,0,',',' ')); ?> F)
                                                        </span>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?></td>
                                    <td><?php echo e(number_format($facture->assurancec ?? 0, 0, ',', ' ')); ?></td>
                                    <td><?php echo e(number_format($facture->assurec ?? 0, 0, ',', ' ')); ?></td>
                                    <td><?php echo e(number_format($facture->avance ?? 0, 0, ',', ' ')); ?></td>
                                    <td>
                                        <span class="fw-bold <?php echo e(($facture->reste ?? 0) > 0 ? 'text-danger' : 'text-success'); ?>">
                                            <?php echo e(number_format($facture->reste ?? 0, 0, ',', ' ')); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($facture->mode_paiement ?? '—'); ?></td>
                                    <td><?php echo e($facture->medecin_r ?? '—'); ?></td>
                                    <td><?php echo e($facture->created_at->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <?php echo $facture->statut_badge; ?>

                                        <?php if($facture->is_printed): ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-print"></i>
                                                <?php echo e(optional($facture->printed_at)->format('d/m/Y')); ?>

                                            </small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>

                            
                            <tfoot class="table-dark fw-bold">
                                <tr>
                                    <td colspan="4" class="text-end">TOTAUX :</td>
                                    <td><?php echo e(number_format($factureExamens->sum('montant'), 0, ',', ' ')); ?></td>
                                    <td><?php echo e(number_format($factureExamens->sum('assurancec'), 0, ',', ' ')); ?></td>
                                    <td><?php echo e(number_format($factureExamens->sum('assurec'), 0, ',', ' ')); ?></td>
                                    <td><?php echo e(number_format($factureExamens->sum('avance'), 0, ',', ' ')); ?></td>
                                    <td><?php echo e(number_format($factureExamens->sum('reste'), 0, ',', ' ')); ?></td>
                                    <td colspan="4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    
                    <div class="d-flex justify-content-center mt-3">
                        <?php echo e($factureExamens->links()); ?>

                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>

        
        <div class="modal fade" id="edit_facture_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-edit me-2"></i>
                            Modifier le règlement — Facture N°&nbsp;<span id="modal-edit-numero"></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditFacture" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Montant total (FCFA)</label>
                                    <input type="number" name="montant" id="edit-montant"
                                           class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Part patient (assurec)</label>
                                    <input type="number" name="assurec_display" id="edit-assurec"
                                           class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Reste à payer</label>
                                    <input type="number" name="reste" id="edit-reste"
                                           class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Montant perçu ce règlement <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="percu" id="edit-percu"
                                           class="form-control" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Mode de paiement <span class="text-danger">*</span>
                                    </label>
                                    <select name="mode_paiement" id="edit-mode-paiement" class="form-select" required>
                                        <option value="espèce">Espèce</option>
                                        <option value="orange money">Orange Money</option>
                                        <option value="mtn mobile money">MTN Mobile Money</option>
                                        <option value="chèque">Chèque</option>
                                        <option value="virement">Virement</option>
                                        <option value="bon de prise en charge">Bon de prise en charge</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3 text-end">
                                <button type="button" class="btn btn-secondary me-2"
                                        data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        <?php echo $__env->make('admin.factures.partials.modal-ajouter-element', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.factures.partials.create-facture-examen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
</body>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Init DataTable ───────────────────────────────────────────────────────────

 waitForjQuery ( function() {
    $(document).ready(function () {
    const table = $('#myTableExamen').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
        order: [[11, 'desc']],
        pageLength: 50,
        columnDefs: [{ orderable: false, targets: [1] }],
    });

    // Filtre statut
    $('#statut-filter').on('change', function () {
        const val = $(this).val().toLowerCase();
        table.column(12).search(val, false, false).draw();
    });

    // ── Modal Edit : injecter les données ────────────────────────────────────
    $('#edit_facture_modal').on('show.bs.modal', function (e) {
        const btn     = $(e.relatedTarget);
        const id      = btn.data('facture-id');
        const numero  = btn.data('facture-numero');
        const montant = btn.data('montant');
        const reste   = btn.data('reste');
        const assurec = btn.data('assurec');
        const mode    = btn.data('mode-paiement');

        $('#modal-edit-numero').text(numero);
        $('#edit-montant').val(montant);
        $('#edit-reste').val(reste);
        $('#edit-assurec').val(assurec);
        $('#edit-percu').val('').attr('max', reste);
        $('#edit-mode-paiement').val(mode);

        // Action du formulaire
        $('#formEditFacture').attr(
            'action',
            '<?php echo e(url("admin/factures-consultation")); ?>/' + id
        );
    });

    // ── Modal Ajout Element : injecter les données ───────────────────────────
    $('#modalAjoutElement').on('show.bs.modal', function (e) {
        const btn = $(e.relatedTarget);
        $('#modal-facture-numero').text(btn.data('facture-numero'));
        $('#input-facture-id').val(btn.data('facture-id'));
        // Déclencher le chargement AJAX si patientId disponible
        const patientId = btn.data('patient-id');
        if (patientId) {
            $('#input-facture-id').data('patient-id', patientId);
        }
    });
 }});


    // ── Initialisation Select2 pour le patient (réutilise la route AJAX existante) ──
 /**    waitForjQuery ( function() {
$(document).ready(function () {
 
    // Select2 pour le patient
    $('#fe-patient-select').select2({
        dropdownParent: $('#modalNouvelleFactureExamen'),
        placeholder: 'Rechercher un patient (nom, prénom, n° dossier)...',
        minimumInputLength: 2,
        ajax: {
            // Route existante : PatientVisitsController@searchPatients
            url: '<?php echo e(route("patient-visits.search-patients")); ?>',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            },
        },
    });
 
    // Quand un patient est sélectionné → afficher son dossier + charger ses prescriptions
    $('#fe-patient-select').on('select2:select', function (e) {
        const patient = e.params.data;
 
        // Pré-remplir n° dossier
        $('#fe-numero-dossier').val(patient.numero_dossier ?? '');
 
        // Charger les prescriptions AJAX (route existante : api.factures.patient-examens)
        $.get('<?php echo e(route("api.factures.patient-examens")); ?>', { patient_id: patient.id })
            .done(function (prescriptions) {
                const select = $('#fe-prescription-select');
                select.empty().append('<option value="">-- Saisir manuellement --</option>');
 
                if (prescriptions.length > 0) {
                    prescriptions.forEach(function (p) {
                        // On stocke le libellé et le médecin en data-attributes
                        select.append(
                            $('<option>')
                                .val(p.id)
                                .text(p.detail + ' — ' + p.nom.substring(0, 60) + (p.nom.length > 60 ? '...' : ''))
                                .data('libelle', p.libelle)
                                .data('medecin', p.medecin)
                        );
                    });
                    $('#fe-prescriptions-card').show();
                } else {
                    // Aucune prescription : on cache la section, on laisse saisie manuelle
                    $('#fe-prescriptions-card').hide();
                }
            })
            .fail(function () {
                $('#fe-prescriptions-card').hide();
            });
 
        // Mettre à jour le résumé
        updateResume();
    });
 
    // Quand une prescription est sélectionnée → pré-remplir libellé et médecin
    $('#fe-prescription-select').on('change', function () {
        const selected = $(this).find(':selected');
        const libelle  = selected.data('libelle');
        const medecin  = selected.data('medecin');
 
        if (libelle) {
            $('#fe-libelle').val(libelle);
        }
        if (medecin) {
            $('#fe-medecin').val(medecin);
        }
 
        updateResume();
    });
 
    // Calcul auto du reste = montant - avance
    $('#fe-montant, #fe-avance').on('input', function () {
        const montant = parseFloat($('#fe-montant').val()) || 0;
        const avance  = parseFloat($('#fe-avance').val()) || 0;
        const reste   = Math.max(0, montant - avance);
 
        $('#fe-reste-display').val(
            reste.toLocaleString('fr-FR') + ' FCFA'
        );
 
        // Couleur selon reste
        if (reste === 0) {
            $('#fe-reste-display').removeClass('text-danger').addClass('text-success');
        } else {
            $('#fe-reste-display').removeClass('text-success').addClass('text-danger');
        }
 
        updateResume();
    });
 
    // Validation avant soumission
    $('#formNouvelleFactureExamen').on('submit', function (e) {
        const patientId = $('#fe-patient-select').val();
        const libelle   = $('#fe-libelle').val().trim();
        const montant   = parseFloat($('#fe-montant').val()) || 0;
 
        if (!patientId) {
            e.preventDefault();
            alert('Veuillez sélectionner un patient.');
            return false;
        }
        if (!libelle) {
            e.preventDefault();
            alert('Veuillez saisir la description des examens.');
            return false;
        }
        if (montant <= 0) {
            e.preventDefault();
            alert('Le montant doit être supérieur à 0.');
            return false;
        }
 
        // Désactiver le bouton pour éviter double-soumission
        $('#fe-submit-btn').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin me-1"></i>Enregistrement...'
        );
    });
 
    // Réinitialiser le modal à la fermeture
    $('#modalNouvelleFactureExamen').on('hidden.bs.modal', function () {
        $('#formNouvelleFactureExamen')[0].reset();
        $('#fe-patient-select').val(null).trigger('change');
        $('#fe-numero-dossier').val('');
        $('#fe-prescriptions-card').hide();
        $('#fe-reste-display').val('0 FCFA').removeClass('text-danger text-success');
        $('#fe-resume').addClass('d-none');
        $('#fe-submit-btn').prop('disabled', false).html(
            '<i class="fas fa-save me-1"></i>Créer la facture examen'
        );
    });
 
    // ── Résumé en temps réel ─────────────────────────────────────────────────
    function updateResume() {
        const patientText = $('#fe-patient-select option:selected').text();
        const montant     = parseFloat($('#fe-montant').val()) || 0;
        const avance      = parseFloat($('#fe-avance').val()) || 0;
        const reste       = Math.max(0, montant - avance);
 
        if (patientText && patientText !== '-- Rechercher un patient --' && montant > 0) {
            $('#fe-resume-text').text(
                'Facture pour ' + patientText +
                ' — Montant : ' + montant.toLocaleString('fr-FR') + ' FCFA' +
                ' | Avance : ' + avance.toLocaleString('fr-FR') + ' FCFA' +
                ' | Reste : ' + reste.toLocaleString('fr-FR') + ' FCFA'
            );
            $('#fe-resume').removeClass('d-none');
        } else {
            $('#fe-resume').addClass('d-none');
        }
    }
 
    $('#fe-libelle, #fe-medecin').on('input', updateResume);
}
});*/
 
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/examen.blade.php ENDPATH**/ ?>