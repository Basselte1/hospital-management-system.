



<?php $__env->startSection('title', 'CMCU | Aperçu Facture Acte — ' . $facture->numero); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="container py-4">

        
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

         
            <a href="<?php echo e(route('facturation.actes.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>

            <div class="d-flex gap-2 flex-wrap align-items-center">

                
                <?php if($isProforma): ?>
                    <span class="badge bg-warning text-dark fs-6 py-2 px-3">
                        <i class="fas fa-clock me-1"></i> PROFORMA
                    </span>
                <?php else: ?>
                    <span class="badge bg-success fs-6 py-2 px-3">
                        <i class="fas fa-check-circle me-1"></i> SOLDÉE
                    </span>
                <?php endif; ?>

              
                <a href="<?php echo e(route('facturation.facturation.acte_pdf', $facture->id)); ?>"
                   class="btn <?php echo e($isProforma ? 'btn-outline-primary' : 'btn-success'); ?>"
                   title="<?php echo e($isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture'); ?>">
                    <i class="fas fa-print me-1"></i>
                    <?php echo e($isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture'); ?>

                </a>

            </div>
        </div>

        
        <?php if(!$isProforma && $facture->is_printed && $facture->printed_at): ?>
            <div class="alert alert-info py-2 mb-3" style="max-width:860px; margin:0 auto;">
                <i class="fas fa-info-circle me-1"></i>
                Imprimée le <strong><?php echo e($facture->printed_at->format('d/m/Y à H:i')); ?></strong>
                <?php if($facture->printer): ?>
                    par <strong><?php echo e(trim(($facture->printer->prenom ?? '') . ' ' . ($facture->printer->name ?? ''))); ?></strong>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <div class="card shadow-sm" style="max-width: 860px; margin: 0 auto; position: relative; overflow: hidden;">

            
            <?php if($isProforma): ?>
                <div style="
                    position: absolute; top: 50%; left: 50%;
                    transform: translate(-50%, -50%) rotate(-35deg);
                    font-size: 72px; font-weight: 900;
                    color: rgba(200, 30, 30, 0.07);
                    white-space: nowrap; pointer-events: none; z-index: 0;
                    letter-spacing: 12px;
                ">PROFORMA</div>
            <?php endif; ?>

            <div class="card-body p-4" style="position: relative; z-index: 1;">

                
                <div class="text-center border-bottom pb-3 mb-3">
                    <strong>CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE</strong><br>
                    <small class="text-muted">
                        Vallée Manga Bell — Douala-Bali<br>
                        Tél : (+237) 233 423 389 / 674 068 988 / 698 873 945<br>
                        www.cmcu-cm.com
                    </small>
                </div>

                
                <div class="text-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <?php if($isProforma): ?> FACTURE PROFORMA — <?php else: ?> REÇU — <?php endif; ?>
                        ACTE / SOIN INFIRMIER
                    </h5>
                    <span class="text-muted">
                        N° <?php echo e($facture->numero); ?> | Dossier : <?php echo e($facture->patient->numero_dossier ?? $facture->patient_numero_dossier ?? '—'); ?>

                    </span>
                </div>

                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold text-muted" style="width:40%">Patient</td>
                                <td><?php echo e($facture->patient_display_name); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">N° Facture</td>
                                <td><?php echo e($facture->numero); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Date</td>
                                <td><?php echo e($facture->created_at->format('d/m/Y')); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <?php if($facture->assurance && $facture->assurancec > 0): ?>
                            <tr>
                                <td class="fw-semibold text-muted" style="width:40%">Assurance</td>
                                <td><?php echo e($facture->numero_assurance ?? '—'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Prise en charge</td>
                                <td><?php echo e($facture->prise_en_charge ?? 0); ?> %</td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="fw-semibold text-muted">Mode paiement</td>
                                <td><?php echo e(ucfirst($facture->mode_paiement ?? '—')); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                
                <?php
                    $lignes = $facture->lignes;
                ?>

                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:38%">Acte / Soin</th>
                            <th style="width:22%">Intervenant</th>
                            <th class="text-center" style="width:10%">Qté</th>
                            <th class="text-end" style="width:15%">P.U.</th>
                            <th class="text-end" style="width:15%">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning me-1" style="font-size:10px;">
                                        Soin
                                    </span>
                                    <?php echo e($ligne->libelle); ?>

                                    <?php if($ligne->date_acte): ?>
                                        <br><small class="text-muted"><?php echo e(\Carbon\Carbon::parse($ligne->date_acte)->format('d/m/Y')); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted" style="font-size:12px;">
                                    <?php echo e($ligne->medecin ?? $ligne->infirmiere ?? '—'); ?>

                                </td>
                                <td class="text-center"><?php echo e($ligne->quantite ?? 1); ?></td>
                                <td class="text-end"><?php echo e(number_format((int)$ligne->montant, 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-end fw-semibold">
                                    <?php echo e(number_format((int)$ligne->montant * ($ligne->quantite ?? 1), 0, ',', ' ')); ?> FCFA
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted fst-italic">Aucune ligne enregistrée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                    <tfoot>
                        
                        <?php if($facture->assurance && $facture->assurancec > 0): ?>
                        <tr class="table-light">
                            <td colspan="4" class="text-end text-muted">
                                <small>Part assurance (<?php echo e($facture->prise_en_charge ?? 0); ?>%) :</small>
                            </td>
                            <td class="text-end text-muted">
                                <small><?php echo e(number_format($facture->assurancec, 0, ',', ' ')); ?> FCFA</small>
                            </td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="4" class="text-end text-muted">
                                <small>Part patient :</small>
                            </td>
                            <td class="text-end text-muted">
                                <small><?php echo e(number_format($facture->assurec ?? 0, 0, ',', ' ')); ?> FCFA</small>
                            </td>
                        </tr>
                        <?php endif; ?>

                        
                        <tr class="table-dark">
                            <td colspan="4" class="text-end fw-bold">TOTAL GÉNÉRAL</td>
                            <td class="text-end fw-bold fs-6">
                                <?php echo e(number_format($facture->montant_total, 0, ',', ' ')); ?> FCFA
                            </td>
                        </tr>

                        <?php if(($facture->avance ?? 0) > 0): ?>
                        <tr class="table-success bg-opacity-50">
                            <td colspan="4" class="text-end">Avance perçue :</td>
                            <td class="text-end text-success fw-semibold">
                                − <?php echo e(number_format($facture->avance, 0, ',', ' ')); ?> FCFA
                            </td>
                        </tr>
                        <?php endif; ?>

                        <tr class="<?php echo e(($facture->reste ?? 0) > 0 ? 'table-danger' : 'table-success'); ?> bg-opacity-25">
                            <td colspan="4" class="text-end fw-bold">Reste à payer :</td>
                            <td class="text-end fw-bold <?php echo e(($facture->reste ?? 0) > 0 ? 'text-danger' : 'text-success'); ?>">
                                <?php echo e(number_format($facture->reste ?? 0, 0, ',', ' ')); ?> FCFA
                            </td>
                        </tr>
                    </tfoot>
                </table>

                
                <div class="d-flex justify-content-between mt-3 text-muted small">
                    <span>
                        Caissier(e) :
                        <strong><?php echo e(trim(($facture->user->prenom ?? '') . ' ' . ($facture->user->name ?? '—'))); ?></strong>
                    </span>
                    <span>Douala, le <?php echo e($facture->created_at->format('d/m/Y')); ?></span>
                </div>

                <?php if($isProforma): ?>
                    <div class="alert alert-warning mt-3 mb-0 py-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Document PROFORMA</strong> — Cette facture n'est pas encore soldée.
                    </div>
                <?php endif; ?>

            </div>
        </div>

        
        <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
          
            <a href="<?php echo e(route('facturation.actes.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>

            
            <a href="<?php echo e(route('facturation.facturation.acte_pdf', $facture->id)); ?>"
               class="btn <?php echo e($isProforma ? 'btn-outline-primary' : 'btn-success'); ?> btn-lg">
                <i class="fas fa-print me-1"></i>
                <?php echo e($isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture'); ?>

            </a>
        </div>

    </div>

</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/partials/apercu_acte.blade.php ENDPATH**/ ?>