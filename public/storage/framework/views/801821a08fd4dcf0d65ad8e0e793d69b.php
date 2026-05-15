
<?php $__env->startSection('title', 'CMCU | Facture Finale'); ?>

<?php $__env->startSection('content'); ?>

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('admin.factures.partials._factures_common', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="fc-page">

    
    <div class="fc-page-header">
        <div class="fc-page-title">
            <div class="fc-title-icon" style="background:#EDE7F6; color:#4527A0;">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            Facture Finale — Récapitulatif par patient
        </div>
        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <a href="<?php echo e(route('facturation.dashboard')); ?>" class="fc-btn fc-btn-light">
                <i class="fas fa-arrow-left"></i> Accueil
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="fc-kpi-row">
        <div class="fc-kpi purple">
            <div class="fc-kpi-label">Patients facturés</div>
            <div class="fc-kpi-value"><?php echo e($totalPatients ?? 0); ?></div>
            <div class="fc-kpi-sub">sur la période</div>
        </div>
        <div class="fc-kpi blue">
            <div class="fc-kpi-label">Total général</div>
            <div class="fc-kpi-value"><?php echo e(number_format($totalGeneral ?? 0, 0, ',', ' ')); ?></div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi green">
            <div class="fc-kpi-label">Total encaissé</div>
            <div class="fc-kpi-value"><?php echo e(number_format($totalEncaisse ?? 0, 0, ',', ' ')); ?></div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi red">
            <div class="fc-kpi-label">Reste total</div>
            <div class="fc-kpi-value"><?php echo e(number_format($totalReste ?? 0, 0, ',', ' ')); ?></div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi teal">
            <div class="fc-kpi-label">Part assurances</div>
            <div class="fc-kpi-value"><?php echo e(number_format($totalAssurance ?? 0, 0, ',', ' ')); ?></div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi amber">
            <div class="fc-kpi-label">Part patients</div>
            <div class="fc-kpi-value"><?php echo e(number_format($totalPartPatient ?? 0, 0, ',', ' ')); ?></div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
    </div>

    
    <form action="<?php echo e(route('finale.index')); ?>" method="GET">
        <div class="fc-toolbar">
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Date de début</span>
                <input type="date" name="start-date" class="form-control"
                       value="<?php echo e(request('start-date', $startDate->format('Y-m-d'))); ?>" style="width:160px;">
            </div>
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Date de fin</span>
                <input type="date" name="end-date" class="form-control"
                       value="<?php echo e(request('end-date', $endDate->format('Y-m-d'))); ?>" style="width:160px;">
            </div>
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Statut</span>
                <select name="statut" class="form-select" style="width:160px;">
                    <option value="">Tous les statuts</option>
                    <option value="soldee"  <?php echo e(request('statut') === 'soldee'  ? 'selected':''); ?>>Soldées</option>
                    <option value="proforma"<?php echo e(request('statut') === 'proforma'? 'selected':''); ?>>Non soldées</option>
                </select>
            </div>
            <div class="fc-toolbar-sep"></div>
            <button type="submit" class="fc-btn fc-btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
         
        </div>
    </form>

    
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:10px; margin-bottom:16px;">
        <div style="background:#fff; border:1px solid #CFD8DC; border-left:4px solid #1565C0; border-radius:8px; padding:12px 14px;">
            <div style="font-size:11px; color:#607D8B; text-transform:uppercase; font-weight:600; margin-bottom:4px;">Consultations</div>
            <div style="font-size:18px; font-weight:700; color:#263238;"><?php echo e(number_format($totalConsultations ?? 0, 0, ',', ' ')); ?> <span style="font-size:11px; color:#607D8B;">FCFA</span></div>
        </div>
        <div style="background:#fff; border:1px solid #CFD8DC; border-left:4px solid #00695C; border-radius:8px; padding:12px 14px;">
            <div style="font-size:11px; color:#607D8B; text-transform:uppercase; font-weight:600; margin-bottom:4px;">Examens</div>
            <div style="font-size:18px; font-weight:700; color:#263238;"><?php echo e(number_format($totalExamens ?? 0, 0, ',', ' ')); ?> <span style="font-size:11px; color:#607D8B;">FCFA</span></div>
        </div>
        <div style="background:#fff; border:1px solid #CFD8DC; border-left:4px solid #F57F17; border-radius:8px; padding:12px 14px;">
            <div style="font-size:11px; color:#607D8B; text-transform:uppercase; font-weight:600; margin-bottom:4px;">Soins infirmiers</div>
            <div style="font-size:18px; font-weight:700; color:#263238;"><?php echo e(number_format($totalSoins ?? 0, 0, ',', ' ')); ?> <span style="font-size:11px; color:#607D8B;">FCFA</span></div>
        </div>
        <div style="background:#fff; border:1px solid #CFD8DC; border-left:4px solid #6A1B9A; border-radius:8px; padding:12px 14px;">
            <div style="font-size:11px; color:#607D8B; text-transform:uppercase; font-weight:600; margin-bottom:4px;">Pharmacie</div>
            <div style="font-size:18px; font-weight:700; color:#263238;"><?php echo e(number_format($totalPharmacie ?? 0, 0, ',', ' ')); ?> <span style="font-size:11px; color:#607D8B;">FCFA</span></div>
        </div>
        <div style="background:#fff; border:1px solid #CFD8DC; border-left:4px solid #37474F; border-radius:8px; padding:12px 14px;">
            <div style="font-size:11px; color:#607D8B; text-transform:uppercase; font-weight:600; margin-bottom:4px;">Autres actes</div>
            <div style="font-size:18px; font-weight:700; color:#263238;"><?php echo e(number_format($totalAutres ?? 0, 0, ',', ' ')); ?> <span style="font-size:11px; color:#607D8B;">FCFA</span></div>
        </div>
    </div>

    
    <div class="fc-table-card">
        <div class="fc-table-card-header">
            <div class="fc-table-card-header-left">
                <i class="fas fa-file-invoice-dollar" style="color:#4527A0;"></i>
                <span style="font-size:14px; font-weight:700;">
                    Récapitulatif du <strong><?php echo e($startDate->format('d/m/Y')); ?></strong> au <strong><?php echo e($endDate->format('d/m/Y')); ?></strong>
                </span>
                <span class="fc-badge fc-badge-purple"><?php echo e($totalPatients ?? 0); ?> patient(s)</span>
            </div>
            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <span style="font-size:11px; color:var(--fc-text-muted); font-style:italic;">Montants en FCFA</span>
                <input type="text" id="searchFinale" class="form-control form-control-sm"
                       placeholder="Rechercher patient..." style="width:180px; font-size:12px;">
            </div>
        </div>

        <div class="fc-table-responsive">
            <table class="fc-table" id="tableFinale">
                <thead>
                    <tr>
                        <th>N° Dossier</th>
                        <th>Patient</th>
                        <th style="text-align:center; color:#1565C0;">Consultations</th>
                        <th style="text-align:center; color:#00695C;">Examens</th>
                        <th style="text-align:center; color:#F57F17;">Soins inf.</th>
                        <th style="text-align:center; color:#6A1B9A;">Pharmacie</th>
                        <!--th style="text-align:center; color:#37474F;">Autres</th-->
                        <th style="text-align:right;">Total</th>
                        <th style="text-align:right; color:#00695C;">Part Ass.</th>
                        <th style="text-align:right;">Part Patient</th>
                        <th style="text-align:right; color:#2E7D32;">Avancé</th>
                        <th style="text-align:right;">Reste</th>
                        <th>Statut global</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $facturesGroupees ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patientData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $totalPatient   = $patientData['total'] ?? 0;
                        $avancePatient  = $patientData['avance'] ?? 0;
                        $restePatient   = $patientData['reste'] ?? 0;
                        $isSolde        = $restePatient == 0;
                    ?>
                    <tr class="<?php echo e($isSolde ? 'soldee' : ''); ?>"
                        data-statut="<?php echo e($isSolde ? 'soldee' : 'proforma'); ?>">
                        <td>
                            <strong style="font-size:12px; color:#607D8B;">
                                N°<?php echo e($patientData['patient']->numero_dossier ?? '—'); ?>

                            </strong>
                        </td>
                        <td>
                            <div style="font-weight:600;">
                                <?php echo e($patientData['patient']->name ?? ''); ?> <?php echo e($patientData['patient']->prenom ?? ''); ?>

                            </div>
                            <?php if($patientData['patient']->assurance ?? false): ?>
                                <div style="font-size:10px; color:var(--fc-teal);">
                                    <i class="fas fa-shield-alt"></i> <?php echo e($patientData['patient']->assurance); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center; font-size:13px;">
                            <span style="color:#1565C0;"><?php echo e($patientData['consultations'] > 0 ? number_format($patientData['consultations'], 0, ',', ' ') : '—'); ?></span>
                        </td>
                        <td style="text-align:center; font-size:13px;">
                           <span style="color:#00695C;"> <?php echo e($patientData['examens'] > 0 ? number_format($patientData['examens'], 0, ',', ' ') : '—'); ?></span>
                        </td>
                        <td style="text-align:center; font-size:13px;">
                            <span style="color:#F57F17;"><?php echo e($patientData['soins'] > 0 ? number_format($patientData['soins'], 0, ',', ' ') : '—'); ?></span>
                        </td>
                        <td style="text-align:center; font-size:13px;">
                           <span style="color:#6A1B9A;"> <?php echo e(($patientData['pharmacie'] ?? 0) > 0 ? number_format($patientData['pharmacie'], 0, ',', ' ') : '—'); ?></span>
                        </td>
                        <!--td style="text-align:center; font-size:13px;">
                            <span style="color:#ccc"><?php echo e(($patientData['autres'] ?? 0) > 0 ? number_format($patientData['autres'], 0, ',', ' ') : '—'); ?></span>
                        </td-->
                        <td style="text-align:right; font-weight:700; font-size:14px;">
                            <?php echo e(number_format($totalPatient, 0, ',', ' ')); ?>

                        </td>
                        <td style="text-align:right; color:var(--fc-teal);">
                            <?php echo e(number_format($patientData['assurancec'] ?? 0, 0, ',', ' ')); ?>

                        </td>
                        <td style="text-align:right;">
                            <?php echo e(number_format($patientData['assurec'] ?? 0, 0, ',', ' ')); ?>

                        </td>
                        <td style="text-align:right; color:var(--fc-green); font-weight:600;">
                            <?php echo e(number_format($avancePatient, 0, ',', ' ')); ?>

                        </td>
                        <td style="text-align:right;">
                            <span class="<?php echo e($restePatient == 0 ? 'fc-reste-zero' : 'fc-reste-nonzero'); ?>">
                                <?php echo e(number_format($restePatient, 0, ',', ' ')); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($isSolde): ?>
                                <span class="fc-badge fc-badge-success"><i class="fas fa-check-circle"></i> Soldée</span>
                            <?php else: ?>
                                <span class="fc-badge fc-badge-warning"><i class="fas fa-clock"></i> Non soldée</span>
                                <div style="font-size:10px; color:#C62828; margin-top:2px;"><i class="fas fa-file-invoice"></i> Proforma</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fc-actions">
                                
                                <a href="<?php echo e(route('final.pdf', $patientData['patient']->id ?? 0)); ?>?start=<?php echo e($startDate->format('Y-m-d')); ?>&end=<?php echo e($endDate->format('Y-m-d')); ?>"
                                   class="fc-action-btn <?php echo e($isSolde ? 'print-ok' : 'print-pf'); ?>"
                                   title="<?php echo e($isSolde ? 'Facture finale soldée' : 'Proforma — reste à payer'); ?>">
                                    <i class="fas <?php echo e($isSolde ? 'fa-file-invoice-dollar' : 'fa-file-invoice'); ?>"></i>
                                </a>
                                
                                <button type="button"
                                        class="fc-action-btn view btn-voir-detail"
                                        title="Voir le détail des factures"
                                        data-patient-id="<?php echo e($patientData['patient']->id ?? 0); ?>"
                                        data-patient-name="<?php echo e($patientData['patient']->name ?? ''); ?> <?php echo e($patientData['patient']->prenom ?? ''); ?>"
                                        data-total="<?php echo e($totalPatient); ?>"
                                        data-consultations="<?php echo e($patientData['consultations'] ?? 0); ?>"
                                        data-examens="<?php echo e($patientData['examens'] ?? 0); ?>"
                                        data-soins="<?php echo e($patientData['soins'] ?? 0); ?>"
                                        data-pharmacie="<?php echo e($patientData['pharmacie'] ?? 0); ?>"
                                        data-assurancec="<?php echo e($patientData['assurancec'] ?? 0); ?>"
                                        data-assurec="<?php echo e($patientData['assurec'] ?? 0); ?>"
                                        data-avance="<?php echo e($avancePatient); ?>"
                                        data-reste="<?php echo e($restePatient); ?>"
                                       data-lignes-examens="<?php echo e(e(json_encode($patientData['lignes_examens']))); ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="14" style="text-align:center; padding:40px; color:var(--fc-text-muted);">
                            <i class="fas fa-inbox" style="font-size:32px; display:block; margin-bottom:10px; opacity:.3;"></i>
                            Aucune facture sur cette période
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>

                
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align:right; text-transform:uppercase; letter-spacing:.5px;">TOTAUX :</td>
                        <td style="text-align:center; color:#1565C0; font-weight:700;"><?php echo e(number_format($totalConsultations ?? 0, 0, ',', ' ')); ?></td>
                        <td style="text-align:center; color:#00695C; font-weight:700;"><?php echo e(number_format($totalExamens ?? 0, 0, ',', ' ')); ?></td>
                        <td style="text-align:center; color:#F57F17; font-weight:700;"><?php echo e(number_format($totalSoins ?? 0, 0, ',', ' ')); ?></td>
                        <td style="text-align:center; color:#6A1B9A; font-weight:700;"><?php echo e(number_format($totalPharmacie ?? 0, 0, ',', ' ')); ?></td>
                        <td style="text-align:center; color:#37474F; font-weight:700;"><?php echo e(number_format($totalAutres ?? 0, 0, ',', ' ')); ?></td>
                        <td style="text-align:right; font-weight:700;"><?php echo e(number_format($totalGeneral ?? 0, 0, ',', ' ')); ?></td>
                        <td style="text-align:right; color:var(--fc-teal); font-weight:700;"><?php echo e(number_format($totalAssurance ?? 0, 0, ',', ' ')); ?></td>
                        <td style="text-align:right; font-weight:700;"><?php echo e(number_format($totalPartPatient ?? 0, 0, ',', ' ')); ?></td>
                        <td style="text-align:right; color:var(--fc-green); font-weight:700;"><?php echo e(number_format($totalEncaisse ?? 0, 0, ',', ' ')); ?></td>
                        <td style="text-align:right; color:var(--fc-red); font-weight:700;"><?php echo e(number_format($totalReste ?? 0, 0, ',', ' ')); ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php if(isset($facturesGroupees) && method_exists($facturesGroupees, 'links')): ?>
        <div style="padding:14px 16px; border-top:1px solid var(--fc-border); display:flex; justify-content:flex-end;">
            <?php echo e($facturesGroupees->links('pagination::bootstrap-5')); ?>

        </div>
        <?php endif; ?>
    </div>

    
    <div class="fc-bilan-section">
        <div class="fc-bilan-title">
            <i class="fas fa-file-pdf" style="color:#4527A0;"></i>
            Générer un bilan journalier — Toutes catégories
        </div>
        <form action="<?php echo e(route('finale.bilan_pdf')); ?>" method="POST" class="d-flex align-items-end flex-wrap gap-3">
            <?php echo csrf_field(); ?>
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Sélectionner la date</span>
                <select name="day" class="form-select" style="min-width:200px; height:36px; font-size:13px;">
                    <?php $__currentLoopData = $lists ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($date); ?>" <?php echo e($date === now()->format('Y-m-d') ? 'selected' : ''); ?>>
                            <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('d F Y')); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Service</span>
                <select name="service" class="form-select" style="min-width:180px; height:36px; font-size:13px;">
                    <option value="">Tous les services</option>
                    <option value="consultation">Consultations</option>
                    <option value="examen">Examens</option>
                    <option value="soins">Soins infirmiers</option>
                    <option value="pharmacie">Pharmacie</option>
                </select>
            </div>
            <button type="submit" class="fc-btn" style="background:#4527A0; color:#fff; border-color:#4527A0;">
                <i class="fas fa-file-pdf"></i> Imprimer le bilan
            </button>
        </form>
    </div>
</div>


<div class="modal fade fc-modal" id="modalDetailPatient" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#4527A0,#5E35B1);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    Récapitulatif — <span id="detail-patient-name"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detail-body">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:16px;">
                    <div style="background:#E3F2FD; border-radius:6px; padding:10px 12px; border-left:3px solid #1565C0;">
                        <div style="font-size:10px; color:#1565C0; font-weight:600; text-transform:uppercase;">Consultations</div>
                        <div style="font-size:16px; font-weight:700; color:#263238;" id="d-consultations">—</div>
                    </div>
                    <div style="background:#E0F2F1; border-radius:6px; padding:10px 12px; border-left:3px solid #00695C;">
                        <div style="font-size:10px; color:#00695C; font-weight:600; text-transform:uppercase;">Examens</div>
                        <div style="font-size:16px; font-weight:700; color:#263238;" id="d-examens">—</div>
                    </div>
                    <div style="background:#FFF8E1; border-radius:6px; padding:10px 12px; border-left:3px solid #F57F17;">
                        <div style="font-size:10px; color:#F57F17; font-weight:600; text-transform:uppercase;">Soins infirmiers</div>
                        <div style="font-size:16px; font-weight:700; color:#263238;" id="d-soins">—</div>
                    </div>
                    <div style="background:#F3E5F5; border-radius:6px; padding:10px 12px; border-left:3px solid #6A1B9A;">
                        <div style="font-size:10px; color:#6A1B9A; font-weight:600; text-transform:uppercase;">Pharmacie</div>
                        <div style="font-size:16px; font-weight:700; color:#263238;" id="d-pharmacie">—</div>
                    </div>
                </div>
                <div style="background:#FAFAFA; border:1px solid #CFD8DC; border-radius:8px; padding:14px;">
                    <div style="display:flex; justify-content:space-between; font-size:13px; padding:5px 0; border-bottom:1px solid #ECEFF1;">
                        <span style="color:#607D8B;">Total brut</span>
                        <strong id="d-total">—</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13px; padding:5px 0; border-bottom:1px solid #ECEFF1;">
                        <span style="color:#00695C;">Part assurance</span>
                        <span style="color:#00695C; font-weight:600;" id="d-assurancec">—</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13px; padding:5px 0; border-bottom:1px solid #ECEFF1;">
                        <span style="color:#607D8B;">Part patient</span>
                        <span id="d-assurec">—</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13px; padding:5px 0; border-bottom:1px solid #ECEFF1;">
                        <span style="color:#2E7D32;">Montant encaissé</span>
                        <span style="color:#2E7D32; font-weight:600;" id="d-avance">—</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:15px; font-weight:700; padding:8px 0 2px;">
                        <span>Reste à payer</span>
                        <span id="d-reste" class="fc-reste-nonzero">—</span>
                    </div>
                </div>
                <div style="margin-top:20px;">
                <h6 style="font-weight:600; color:#00695C;">Détail des examens</h6>
                <table class="table table-sm table-bordered" id="table-examens">
                    <thead style="background:#E0F2F1; font-size:12px;">
                        <tr>
                            <th>Libellé</th>
                            <th>Montant</th>
                            <th>Technicien</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="fc-btn fc-btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <a id="d-print-link" href="#" class="fc-btn" style="background:#4527A0; color:#fff; border-color:#4527A0;">
                    <i class="fas fa-file-invoice-dollar"></i> Générer la facture
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Recherche ──────────────────────────────────────────────────────────
    document.getElementById('searchFinale')?.addEventListener('input', function () {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#tableFinale tbody tr').forEach(function (r) {
            r.style.display = r.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    // ── Modal détail patient ───────────────────────────────────────────────
    document.querySelectorAll('.btn-voir-detail').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const fmt = v => parseFloat(v).toLocaleString('fr-FR') + ' FCFA';
            const reste = parseFloat(this.dataset.reste) || 0;

            document.getElementById('detail-patient-name').textContent = this.dataset.patientName;
            document.getElementById('d-consultations').textContent = fmt(this.dataset.consultations);
            document.getElementById('d-examens').textContent       = fmt(this.dataset.examens);
            document.getElementById('d-soins').textContent         = fmt(this.dataset.soins);
            document.getElementById('d-pharmacie').textContent     = fmt(this.dataset.pharmacie);
            document.getElementById('d-total').textContent         = fmt(this.dataset.total);
            document.getElementById('d-assurancec').textContent    = fmt(this.dataset.assurancec);
            document.getElementById('d-assurec').textContent       = fmt(this.dataset.assurec);
            document.getElementById('d-avance').textContent        = fmt(this.dataset.avance);
            document.getElementById('d-reste').textContent         = fmt(reste);
            document.getElementById('d-reste').className           = reste === 0 ? 'fc-reste-zero' : 'fc-reste-nonzero';
            document.getElementById('d-print-link').href           =
                '/admin/factures-finale/' + this.dataset.patientId + '/pdf';

            new bootstrap.Modal(document.getElementById('modalDetailPatient')).show();

            // ── Détail des examens ───────────────────────────────────────────────
            const lignesExamens = JSON.parse(this.dataset.lignesExamens || '[]');
            const tbody = document.querySelector('#table-examens tbody');
            tbody.innerHTML = ''; // reset

            lignesExamens.forEach(ligne => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${ligne.libelle ?? ''}</td>
                    <td>${parseFloat(ligne.montant ?? 0).toLocaleString('fr-FR')} FCFA</td>
                    <td>${ligne.technicien ?? ''}</td>
                `;
                tbody.appendChild(tr);
            });

            tbody.innerHTML = '';
            if (lignesExamens.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="3" class="text-center text-muted">Aucun examen enregistré</td>`;
                tbody.appendChild(tr);
            } else {
                lignesExamens.forEach(ligne => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${ligne.libelle ?? ''}</td>
                        <td>${parseFloat(ligne.montant ?? 0).toLocaleString('fr-FR')} FCFA</td>
                        <td>${ligne.technicien ?? ''}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }


        });
    });

    setTimeout(function () { document.querySelectorAll('.alert').forEach(function (a) { a.style.opacity = '0'; }); }, 5000);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/facture_finale.blade.php ENDPATH**/ ?>