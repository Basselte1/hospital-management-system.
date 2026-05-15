



<?php $__env->startSection('title', 'CMCU | Aperçu Facture — ' . $facture->numero); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="container py-4">

        
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

            
            <a href="<?php echo e(route('factures.consultation')); ?>" class="btn btn-secondary">
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

             
                <a href="<?php echo e(route('factures.consultation_pdf', $facture->id)); ?>"
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
                        <?php if($isProforma): ?>
                            FACTURE PROFORMA —
                        <?php else: ?>
                            REÇU —
                        <?php endif; ?>
                        <?php echo e(strtoupper($facture->details_motif ?? $facture->motif ?? 'CONSULTATION')); ?>

                    </h5>
                    <span class="text-muted">N° Dossier : <?php echo e($facture->patient->numero_dossier ?? '—'); ?></span>
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
                            <tr>
                                <td class="fw-semibold text-muted">Motif</td>
                                <td><?php echo e($facture->motif ?? '—'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold text-muted" style="width:40%">Médecin</td>
                                <td><?php echo e($facture->medecin_r ?? '—'); ?></td>
                            </tr>
                            <?php if(!empty($facture->assurance)): ?>
                            <tr>
                                <td class="fw-semibold text-muted">Assurance</td>
                                <td><?php echo e($facture->assurance); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(!empty($facture->demarcheur)): ?>
                            <tr>
                                <td class="fw-semibold text-muted">Démarcheur</td>
                                <td><?php echo e($facture->demarcheur); ?></td>
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
                    $lignesCollection = $facture->lignes;

                    // Chercher la ligne consultation snapshotée
                    $ligneConsBase = $lignesCollection->firstWhere('type_acte', 'consultation');
                    $montantConsBase = $ligneConsBase
                        ? (int) $ligneConsBase->montant
                        : (int) ($facture->montant - $totalLignes);

                    // Les lignes à afficher en détail = tout sauf la ligne consultation de base
                    $autresLignes = $ligneConsBase
                        ? $lignesCollection->where('type_acte', '!=', 'consultation')
                        : $lignesCollection;
                ?>

                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:18%">Type</th>
                            <th style="width:42%">Description</th>
                            <th style="width:20%">Médecin</th>
                            <th class="text-end" style="width:20%">Montant</th>
                        </tr>
                    </thead>
                    <tbody>

                        
                        <tr>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                    <?php echo e($facture->motif); ?>

                                </span>
                            </td>
                            <td>
                                <?php echo e($facture->motif ?? 'Consultation médicale'); ?>

                                <?php if(!empty($facture->details_motif)): ?>
                                    <br><small class="text-muted"><?php echo e($facture->details_motif); ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted"><?php echo e($facture->medecin_r ?? '—'); ?></td>
                            <td class="text-end fw-semibold">
                                <?php echo e(number_format($montantConsBase, 0, ',', ' ')); ?> FCFA
                            </td>
                        </tr>

                        
                        <?php $__currentLoopData = $autresLignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $badgeClass = match($ligne->type_acte) {
                                    'examen_labo'    => 'bg-info bg-opacity-10 text-info border border-info',
                                    'soin_infirmier' => 'bg-warning bg-opacity-10 text-warning border border-warning',
                                    default          => 'bg-secondary bg-opacity-10 text-secondary border border-secondary',
                                };
                            ?>
                            <tr>
                                <td>
                                    <span class="badge <?php echo e($badgeClass); ?>">
                                        <?php echo e($ligne->label_type); ?>

                                    </span>
                                </td>
                                <td><?php echo e($ligne->libelle); ?></td>
                                <td class="text-muted">
                                    <?php echo e($ligne->medecin ?? $ligne->infirmniere ?? '—'); ?>

                                </td>
                                <td class="text-end fw-semibold">
                                    
                                    <?php echo e(number_format((int)$ligne->montant, 0, ',', ' ')); ?> FCFA
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>

                    
                    <tfoot>

                        <?php if(!empty($facture->assurancec) && $facture->assurancec > 0): ?>
                        <tr class="table-light">
                            <td colspan="3" class="text-end text-muted">
                                <small>Part assurance (<?php echo e($facture->patient->prise_en_charge ?? 0); ?>%) :</small>
                            </td>
                            <td class="text-end text-muted">
                                <small><?php echo e(number_format($facture->assurancec, 0, ',', ' ')); ?> FCFA</small>
                            </td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="3" class="text-end text-muted">
                                <small>Part patient :</small>
                            </td>
                            <td class="text-end text-muted">
                                <small><?php echo e(number_format($facture->assurec ?? 0, 0, ',', ' ')); ?> FCFA</small>
                            </td>
                        </tr>
                        <?php endif; ?>

                        <tr class="table-dark">
                            <td colspan="3" class="text-end fw-bold">TOTAL GÉNÉRAL</td>
                            <td class="text-end fw-bold fs-6">
                                <?php echo e(number_format($facture->montant, 0, ',', ' ')); ?> FCFA
                            </td>
                        </tr>

                        <?php if(($facture->avance ?? 0) > 0): ?>
                        <tr class="table-success bg-opacity-50">
                            <td colspan="3" class="text-end">Avance perçue :</td>
                            <td class="text-end text-success fw-semibold">
                                − <?php echo e(number_format($facture->avance, 0, ',', ' ')); ?> FCFA
                            </td>
                        </tr>
                        <?php endif; ?>

                        <tr class="<?php echo e(($facture->reste ?? 0) > 0 ? 'table-danger' : 'table-success'); ?> bg-opacity-25">
                            <td colspan="3" class="text-end fw-bold">Reste à payer :</td>
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
                        Le document imprimé portera la mention <strong>PROFORMA</strong>.
                    </div>
                <?php endif; ?>

            </div>
        </div>

        
        <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
            <a href="<?php echo e(route('factures.consultation')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>

           

            <a href="<?php echo e(route('factures.consultation_pdf', $facture->id)); ?>"
               class="btn <?php echo e($isProforma ? 'btn-outline-primary' : 'btn-success'); ?> btn-lg">
                <i class="fas fa-print me-1"></i>
                <?php echo e($isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture'); ?>

            </a>
        </div>

    </div>

</div>

<script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/apercu_consultation.blade.php ENDPATH**/ ?>