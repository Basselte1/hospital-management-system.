
<?php $__env->startSection('title', 'CMCU | Factures Soins Infirmiers'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('admin.factures.partials._factures_common', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="fc-page">

    
    <div class="fc-page-header">
        <div class="fc-page-title">
            <div class="fc-title-icon" style="background:#FFF8E1; color:#F57F17;">
                <i class="fas fa-syringe"></i>
            </div>
            Factures Actes
        </div>
        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <button type="button" class="fc-btn fc-btn-warning"
                    data-bs-toggle="modal" data-bs-target="#modalNouvelleFacSoin">
                <i class="fas fa-plus"></i> Nouveau soin facturable
            </button>
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
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <?php if(isset($factureSoins)): ?>
    <div class="fc-kpi-row">
        <div class="fc-kpi amber">
            <div class="fc-kpi-label">Total actes</div>
            <div class="fc-kpi-value"><?php echo e($factureSoins->total()); ?></div>
            <div class="fc-kpi-sub">sur la période</div>
        </div>
        <div class="fc-kpi blue">
            <div class="fc-kpi-label">Montant total</div>
            <div class="fc-kpi-value"><?php echo e(number_format($factureSoins->sum('montant_total'), 0, ',', ' ')); ?></div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi green">
            <div class="fc-kpi-label">Encaissé</div>
            <div class="fc-kpi-value"><?php echo e(number_format($factureSoins->sum('avance'), 0, ',', ' ')); ?></div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi red">
            <div class="fc-kpi-label">Reste</div>
            <div class="fc-kpi-value"><?php echo e(number_format($factureSoins->sum('reste'), 0, ',', ' ')); ?></div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi teal">
            <div class="fc-kpi-label">Part assurances</div>
            <div class="fc-kpi-value"><?php echo e(number_format($factureSoins->sum('assurancec'), 0, ',', ' ')); ?></div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
    </div>
    <?php endif; ?>

    
    <form action="<?php echo e(route('facturation.actes.search')); ?>" method="POST">
        <?php echo csrf_field(); ?>
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
                <span class="fc-toolbar-label">Infirmier(e)</span>
                <select name="infirmier" class="form-select" style="width:180px;">
                    <option value="">Tous les infirmiers</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupUsers): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <optgroup label="<?php echo e($group); ?>">
                            <?php $__currentLoopData = $groupUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(old('assigne_a') == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->role_id == 2 ? 'Dr.' : ''); ?> <?php echo e($user->name); ?> <?php echo e($user->prenom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </optgroup>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="fc-toolbar-sep"></div>
            <button type="submit" class="fc-btn fc-btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
            <a href="<?php echo e(route('facturation.actes.index')); ?>" class="fc-btn fc-btn-light">
                <i class="fas fa-undo"></i> Réinitialiser
            </a>
        </div>
    </form>

    
    <?php if(isset($factureSoins)): ?>

    <div class="fc-proforma-info">
        <i class="fas fa-info-circle"></i>
        Les factures avec un reste &gt; 0 sont des <strong>PROFORMAS</strong>.
    </div>

    <div class="fc-table-card">
        <div class="fc-table-card-header">
            <div class="fc-table-card-header-left">
                <i class="fas fa-notes-medical" style="color:var(--fc-amber);"></i>
                <span style="font-size:14px; font-weight:700;">
                    Période du <strong><?php echo e($startDate->format('d/m/Y')); ?></strong> au <strong><?php echo e($endDate->format('d/m/Y')); ?></strong>
                </span>
                <span class="fc-badge fc-badge-warning"><?php echo e($factureSoins->total()); ?> facture(s)</span>
            </div>
            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <span style="font-size:11px; color:var(--fc-text-muted); font-style:italic;">Montants en FCFA</span>
                <select id="statutFilterSoins" class="form-select form-select-sm" style="width:150px; font-size:12px;">
                    <option value="">Tous les statuts</option>
                    <option value="soldee">Soldées</option>
                    <option value="proforma">Non soldées</option>
                </select>
                <input type="text" id="searchSoins" class="form-control form-control-sm"
                       placeholder="Rechercher patient..." style="width:180px; font-size:12px;">
            </div>
        </div>

        <div class="fc-table-responsive">
            <table class="fc-table" id="tableSoins">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Actions</th>
                        <th>Patient</th>
                        <th>Acte infirmier</th>
                        <th>Montant</th>
                        <th>Part Ass.</th>
                        <th>Part Patient</th>
                        <th>Avancé</th>
                        <th>Reste</th>
                        <th>Mode paiement</th>
                        <th>Infirmier(e)</th>
                        <th>Médecin</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $factureSoins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($facture->isSoldee() ? 'soldee' : ''); ?>"
                        data-statut="<?php echo e($facture->isSoldee() ? 'soldee' : 'proforma'); ?>">
                        <td><strong><?php echo e($facture->numero); ?></strong></td>

                        
                        <td>
                            <div class="fc-actions">
                                <?php if($facture->isProforma()): ?>
                                    <a href="<?php echo e(route('facturation.acte_pdf', $facture->id)); ?>"
                                       class="fc-action-btn print-pf"
                                       title=" Imprimer PROFORMA — reste: <?php echo e(number_format($facture->reste,0,',',' ')); ?> FCFA">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $facture)): ?>
                                    <form action="<?php echo e(route('factures.destroy', $facture->id)); ?>"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Supprimer cette facture ?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="button"
                                            class="fc-action-btn delete btn-confirm-delete-soin"
                                            title="Supprimer"
                                            data-facture-id="<?php echo e($facture->id); ?>"
                                            data-facture-numero="<?php echo e($facture->numero); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>

                                <?php else: ?>

                                    <a href= "<?php echo e(route('facturation.facturation.acte_pdf', $facture->id)); ?>"
                                       class="fc-action-btn print-ok" title="Imprimer la facture soldée">
                                        <i class="fas fa-print"></i>
                                    </a>
                                   
                                <?php endif; ?>

                                  
                                  <a href="<?php echo e(route('facturation.factures.apercu_acte', $facture->id)); ?>"
                                    class="fc-action-btn view" title="Aperçu">
                                    <i class="fas fa-eye"></i>
                                    </a>

                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $facture)): ?>
                                    <?php if($facture->isModifiable()): ?>
                                        <button type="button"
                                                class="fc-action-btn edit btn-open-modal-edit-soin"
                                                title="Modifier le paiement"
                                                data-id-facture="<?php echo e($facture->id); ?>"
                                                data-mode_paiement="<?php echo e($facture->mode_paiement); ?>"
                                                data-montant="<?php echo e($facture->montant); ?>"
                                                data-reste="<?php echo e($facture->reste); ?>"
                                                data-prise_en_charge="<?php echo e($facture->patient->prise_en_charge ?? 0); ?>">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                             
                            </div>
                        </td>

                        <td>
                            <div style="font-weight:500;"><?php echo e($facture->patient_display_name); ?></div>
                            <?php if($facture->patient): ?>
                                <div style="font-size:11px; color:var(--fc-text-muted);">N°<?php echo e($facture->patient->numero_dossier); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            
                        <?php if($facture->lignes->isNotEmpty()): ?>
                            <?php $__currentLoopData = $facture->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="display:flex; align-items:center; gap:5px; margin-bottom:3px;">
                                    <span class="fc-badge fc-badge-warning" style="font-size:10px;">Soin</span>
                                    <span style="font-size:12px;"><?php echo e(\Illuminate\Support\Str::limit($ligne->libelle, 35)); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <span style="font-size:12px; color:var(--fc-text-muted);"><?php echo e(\Illuminate\Support\Str::limit($facture->details_motif ?? $facture->motif, 40)); ?></span>
                        <?php endif; ?>
                        </td>
                        <td style="font-weight:600; text-align:right;"><?php echo e(number_format($facture->montant_total, 0, ',', ' ')); ?></td>
                        <td style="text-align:right; color:var(--fc-teal);"><?php echo e(number_format($facture->assurancec, 0, ',', ' ')); ?></td>
                        <td style="text-align:right;"><?php echo e(number_format($facture->assurec, 0, ',', ' ')); ?></td>
                        <td style="text-align:right; color:var(--fc-green); font-weight:600;"><?php echo e(number_format($facture->patient->avance, 0, ',', ' ')); ?></td>
                        <td style="text-align:right;">
                            <span class="<?php echo e($facture->reste == 0 ? 'fc-reste-zero' : 'fc-reste-nonzero'); ?>">
                                <?php echo e(number_format($facture->reste, 0, ',', ' ')); ?>

                            </span>
                        </td>
                        <td><span class="fc-badge fc-badge-gray"><?php echo e(ucfirst($facture->mode_paiement ?? 'espèce')); ?></span></td>

                        
                        <td style="font-size:12px;">
                          
                          
                           
                                <span class="fc-badge fc-badge-warning" style="font-size:10px;">
                                    <i class="fas fa-user-nurse"></i>
                                    <!--<?php echo e($facture->patient->infirmier ?? '-'); ?>-->    
                                    <?php echo e($medecinInfirmier->infirmiere ?? '-'); ?>                    
                              
                                </span>
                          
                          
                        </td>
                        
                        <td style="font-size:12px;">
                             
                          
                          
                                <span class="fc-badge fc-badge-warning" style="font-size:10px;">
                                    <i class="fas fa-user-nurse"></i>
                                    <!--<?php echo e($facture->patient->infirmier ?? '-'); ?>-->    
                                       

                                       <?php echo e($medecinInfirmier->infirmiere ?? '-'); ?>                    
                              
                                </span>
                            
                            <!--<?php echo e($facture->patient->medecin_r ?? '—'); ?>-->            
                    
                        </td>
                        
                        <td style="white-space:nowrap; font-size:12px; color:var(--fc-text-muted);">
                            <?php echo e($facture->created_at->format('d/m/Y')); ?><br>
                            <span style="font-size:10px;"><?php echo e($facture->created_at->format('H:i')); ?></span>
                        </td>
                        <td>
                            <?php if($facture->isSoldee()): ?>
                                <span class="fc-badge fc-badge-success"><i class="fas fa-check-circle"></i> Soldée</span>
                                <?php if($facture->is_printed): ?>
                                    <div style="font-size:10px; color:var(--fc-text-muted); margin-top:3px;">
                                        <i class="fas fa-print"></i> Imprimée le <?php echo e($facture->printed_at?->format('d/m/Y')); ?>

                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="fc-badge fc-badge-warning"><i class="fas fa-clock"></i> Non soldée</span>
                                <div style="font-size:10px; color:#C62828; margin-top:3px;">
                                    <i class="fas fa-file-invoice"></i> Proforma
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right; text-transform:uppercase; letter-spacing:.5px;">TOTAL :</td>
                        <td style="text-align:right;"><?php echo e(number_format($factureSoins->sum('montant_total'), 0, ',', ' ')); ?></td>
                        <td style="text-align:right; color:var(--fc-teal);"><?php echo e(number_format($factureSoins->sum('assurancec'), 0, ',', ' ')); ?></td>
                        <td style="text-align:right;"><?php echo e(number_format($factureSoins->sum('assurec'), 0, ',', ' ')); ?></td>
                        <td style="text-align:right; color:var(--fc-green);"><?php echo e(number_format($factureSoins->sum('avance'), 0, ',', ' ')); ?></td>
                        <td style="text-align:right; color:var(--fc-red);"><?php echo e(number_format($factureSoins->sum('reste'), 0, ',', ' ')); ?></td>
                        <td colspan="5"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="padding:14px 16px; border-top:1px solid var(--fc-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
            <div style="font-size:12px; color:var(--fc-text-muted);">
                Affichage de <?php echo e($factureSoins->firstItem()); ?> à <?php echo e($factureSoins->lastItem()); ?>

                sur <?php echo e($factureSoins->total()); ?> résultats
            </div>
            <?php echo e($factureSoins->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
    <?php endif; ?>

    
    <div class="fc-bilan-section">
        <div class="fc-bilan-title">
            <i class="fas fa-file-pdf" style="color:var(--fc-amber);"></i>
            Générer un bilan journalier — Soins infirmiers
        </div>
        <form action="<?php echo e(route('facturation.actes.bilan_pdf')); ?>" method="POST" class="d-flex align-items-end flex-wrap gap-3">
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
            <button type="submit" class="fc-btn fc-btn-warning">
                <i class="fas fa-file-pdf"></i> Imprimer le bilan
            </button>
        </form>
    </div>
</div>


<div class="modal fade fc-modal" id="editFacSoinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#F57F17,#FF8F00);">
                <h5 class="modal-title text-white"><i class="fas fa-pen me-2"></i>Modifier le paiement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editFacSoinForm" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="fc-field-group">
                                <label>Montant (FCFA)</label>
                                <input type="number" name="montant" id="soin-montant" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fc-field-group">
                                <label>Montant perçu (FCFA)</label>
                                <input type="number" name="percu" id="soin-percu" class="form-control" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="fc-field-group">
                                <label>Reste à payer</label>
                                <input type="number" name="reste" id="soin-reste" class="form-control fc-reste-input" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fc-field-group">
                                <label>Mode de paiement</label>
                                <select name="mode_paiement" class="form-select">
                                    <option value="espèce">Espèce</option>
                                    <option value="mobile">Mobile Money</option>
                                    <option value="chèque">Chèque</option>
                                    <option value="virement">Virement</option>
                                    <option value="bon de prise en charge">Bon de prise en charge</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="fc-btn fc-btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="fc-btn fc-btn-warning">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('statutFilterSoins')?.addEventListener('change', function () {
        const val = this.value;
        document.querySelectorAll('#tableSoins tbody tr').forEach(function (r) {
            r.style.display = (!val || r.dataset.statut === val) ? '' : 'none';
        });
    });

    document.getElementById('searchSoins')?.addEventListener('input', function () {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#tableSoins tbody tr').forEach(function (r) {
            r.style.display = r.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    document.querySelectorAll('.btn-open-modal-edit-soin').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const reste = parseFloat(this.dataset.reste) || 0;
            document.getElementById('soin-montant').value = this.dataset.montant;
            document.getElementById('soin-reste').value   = reste;
            document.getElementById('editFacSoinForm').action =
                '/admin/factures-consultation/' + this.dataset.idFacture;
            colorReste(reste, 'soin-reste');
            new bootstrap.Modal(document.getElementById('editFacSoinModal')).show();
        });
    });

    document.getElementById('soin-percu')?.addEventListener('input', function () {
        const reste = parseFloat(document.getElementById('soin-reste').value) || 0;
        const percu = parseFloat(this.value) || 0;
        const nr    = Math.max(0, reste - percu);
        document.getElementById('soin-reste').value = nr;
        colorReste(nr, 'soin-reste');
    });

    function colorReste(val, id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.toggle('zero',    val === 0);
        el.classList.toggle('nonzero', val > 0);
    }

    document.querySelectorAll('.btn-confirm-delete-soin').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.factureId, num = this.dataset.factureNumero;
            if (confirm('Supprimer la facture soin N°' + num + ' ?')) {
                const f = document.createElement('form');
                f.method = 'POST';
                f.action = '/admin/factures-consultation/' + id;
                f.innerHTML = '<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">' +
                              '<input type="hidden" name="_method" value="DELETE">';
                document.body.appendChild(f); f.submit();
            }
        });
    });

    setTimeout(function () { document.querySelectorAll('.alert').forEach(function (a) { a.style.opacity = '0'; }); }, 5000);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/facture_actes.blade.php ENDPATH**/ ?>