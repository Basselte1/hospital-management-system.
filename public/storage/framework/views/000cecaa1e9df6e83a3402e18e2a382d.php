

<?php
    /*
     * ── CALCUL DU MONTANT DE BASE CONSULTATION ─────────────────────────────
     *
     * Règle : si des lignes existent, la première ligne de type "consultation"
     * contient le montant snapshot de la consultation de base.
     * Si cette ligne n'existe pas (ancienne facture sans lignes), on affiche
     * le montant total de la facture dans la ligne consultation, et les lignes
     * ajoutées affichent chacune leur montant propre.
     *
     * AVANT (bug) : $montantConsultationBase était utilisé sans être défini → erreur.
     * APRÈS (fix) : calculé ici à partir des données disponibles.
     */
    $lignesCollection = collect($lignes);

    // Chercher la ligne de type "consultation" (snapshot de base)
    $ligneConsultationBase = $lignesCollection->firstWhere('type_acte', 'consultation');

    if ($ligneConsultationBase) {
        // Cas normal : la consultation a été snapshotée en ligne lors du 1er ajout
        $montantConsultationBase = (int) ($ligneConsultationBase['montant'] ?? 0);
        // Les "autres" lignes = tout sauf la ligne consultation de base
        $autresLignes = $lignesCollection->where('type_acte', '!=', 'consultation')->values();
    } else {
        // Ancienne facture sans lignes ou facture simple sans ajout
        // → montant total = montant de la consultation, pas de lignes supplémentaires
        $montantConsultationBase = (int) ($facture['montant'] ?? 0);
        $autresLignes = collect([]);
    }

    // Titre du document
    $titreDocument = $is_proforma ? 'FACTURE PROFORMA' : 'REÇU';

    // Nom patient (avec fallback snapshot)
    $nomPatient = trim(($patient['name'] ?? '') . ' ' . ($patient['prenom'] ?? ''));
    if (empty(trim($nomPatient))) {
        $nomPatient = $facture['patient_name'] ?? '[Patient inconnu]';
    }

    // Caissier
    $caissier = '';
    if (!empty($printer)) {
        $caissier = trim(($printer['prenom'] ?? '') . ' ' . ($printer['name'] ?? ''));
    }

    // Prise en charge assurance
    $priseEnCharge = (float) ($patient['prise_en_charge'] ?? 0);
?>


<div class="text-center" style="border-bottom: 1px solid #000; padding-bottom: 8px; margin-bottom: 8px;">
    <h5 class="bold">CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE</h5>
    <h6>Vallée Manga Bell — Douala-Bali</h6>
    <h6>Tél : (+237) 233 423 389 / 674 068 988 / 698 873 945</h6>
    <h6>www.cmcu-cm.com</h6>
</div>


<div class="text-center" style="margin: 8px 0;">
    <h4 class="bold">
        <?php echo e($titreDocument); ?> — <?php echo e(strtoupper($facture['details_motif'] ?? $facture['motif'] ?? 'CONSULTATION')); ?>

    </h4>
    <h6>N° Dossier : <?php echo e($patient['numero_dossier'] ?? '—'); ?></h6>
</div>


<table style="margin: 8px 0; font-size: 10px;">
    <tr>
        <td style="width:25%; border:none; padding:2px 4px;"><strong>Patient</strong></td>
        <td style="width:25%; border:none; padding:2px 4px;"><?php echo e($nomPatient); ?></td>
        <td style="width:25%; border:none; padding:2px 4px;"><strong>N° Facture</strong></td>
        <td style="width:25%; border:none; padding:2px 4px;"><?php echo e($facture['numero'] ?? '—'); ?></td>
    </tr>
    <tr>
        <td style="border:none; padding:2px 4px;"><strong>Date</strong></td>
        <td style="border:none; padding:2px 4px;">
            <?php echo e(isset($facture['created_at']) ? \Carbon\Carbon::parse($facture['created_at'])->format('d/m/Y') : '—'); ?>

        </td>
        <td style="border:none; padding:2px 4px;"><strong>Mode paiement</strong></td>
        <td style="border:none; padding:2px 4px;"><?php echo e(ucfirst($facture['mode_paiement'] ?? '—')); ?></td>
    </tr>
    <tr>
        <td style="border:none; padding:2px 4px;"><strong>Motif</strong></td>
        <td style="border:none; padding:2px 4px;"><?php echo e($facture['motif'] ?? '—'); ?></td>
        <td style="border:none; padding:2px 4px;"><strong>Médecin</strong></td>
        <td style="border:none; padding:2px 4px;"><?php echo e($facture['medecin_r'] ?? '—'); ?></td>
    </tr>
    <?php if(!empty($facture['assurance'])): ?>
    <tr>
        <td style="border:none; padding:2px 4px;"><strong>Assurance</strong></td>
        <td style="border:none; padding:2px 4px;" colspan="3"><?php echo e($facture['assurance']); ?></td>
    </tr>
    <?php endif; ?>
    <?php if(!empty($facture['demarcheur'])): ?>
    <tr>
        <td style="border:none; padding:2px 4px;"><strong>Démarcheur</strong></td>
        <td style="border:none; padding:2px 4px;" colspan="3"><?php echo e($facture['demarcheur']); ?></td>
    </tr>
    <?php endif; ?>
</table>



<table class="lignes-table" style="margin-top: 10px;">
    <thead>
        <tr>
            <th class="col-type">Type</th>
            <th class="col-libelle">Description</th>
            <th class="col-medecin">Médecin / Infirmier(e)</th>
            <th class="col-montant">Montant (FCFA)</th>
        </tr>
    </thead>
    <tbody>

        
        <tr>
            <td class="col-type">Consultation</td>
            <td class="col-libelle">
                <?php echo e($facture['motif'] ?? 'Consultation médicale'); ?>

                <?php if(!empty($facture['details_motif'])): ?>
                    <br><small style="color:#555;"><?php echo e($facture['details_motif']); ?></small>
                <?php endif; ?>
            </td>
            <td class="col-medecin"><?php echo e($facture['medecin_r'] ?? '—'); ?></td>
            <td class="col-montant">
                <?php echo e(number_format($montantConsultationBase, 0, ',', ' ')); ?>

            </td>
        </tr>

        
        <?php $__currentLoopData = $autresLignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="col-type">
                <?php echo e($typeLabels[$ligne['type_acte']] ?? ucfirst(str_replace('_', ' ', $ligne['type_acte']))); ?>

            </td>
            <td class="col-libelle"><?php echo e($ligne['libelle'] ?? '—'); ?></td>
            <td class="col-medecin">
                <?php echo e($ligne['medecin'] ?? $ligne['infirmiere'] ?? $ligne['infirmniere'] ?? '—'); ?>

            </td>
            <td class="col-montant">
                
                <?php echo e(number_format((int)($ligne['montant'] ?? 0), 0, ',', ' ')); ?>

            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </tbody>

    
    <tfoot>
        
        <?php if($priseEnCharge > 0 && !empty($facture['assurancec']) && $facture['assurancec'] > 0): ?>
        <tr>
            <td colspan="3" style="text-align:right; font-style:italic; font-size:10px;">
                Part assurance (<?php echo e($priseEnCharge); ?>%) :
            </td>
            <td style="text-align:right; font-size:10px;">
                <?php echo e(number_format((float)($facture['assurancec'] ?? 0), 0, ',', ' ')); ?>

            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right; font-style:italic; font-size:10px;">
                Part patient :
            </td>
            <td style="text-align:right; font-size:10px;">
                <?php echo e(number_format((float)($facture['assurec'] ?? 0), 0, ',', ' ')); ?>

            </td>
        </tr>
        <?php endif; ?>

        
        <tr class="lignes-total">
            <td colspan="3" style="text-align:right;">TOTAL GÉNÉRAL</td>
            <td style="text-align:right;">
                <?php echo e(number_format((float)($facture['montant'] ?? 0), 0, ',', ' ')); ?> FCFA
            </td>
        </tr>

        
        <?php if(($facture['avance'] ?? 0) > 0): ?>
        <tr>
            <td colspan="3" style="text-align:right;">Avance perçue :</td>
            <td style="text-align:right;">
                − <?php echo e(number_format((float)($facture['avance'] ?? 0), 0, ',', ' ')); ?> FCFA
            </td>
        </tr>
        <?php endif; ?>

        
        <?php if($isPaiementDevise): ?>
        <tr class="devise-row">
            <td colspan="3" style="text-align:right;">
                <span class="devise-label">Remis (<?php echo e($devise); ?>) :</span>
            </td>
            <td style="text-align:right;">
                <span class="devise-detail">
                    <?php echo e(number_format((float)$montantDevise, 2, ',', ' ')); ?> <?php echo e($devise); ?>

                    × <?php echo e($tauxConversion); ?>

                    = <?php echo e(number_format((float)$montantDevise * (float)$tauxConversion, 0, ',', ' ')); ?> FCFA
                </span>
            </td>
        </tr>
        <?php if($renduFCFA > 0): ?>
        <tr class="rendu-row">
            <td colspan="3" style="text-align:right;">
                <span class="rendu-label">Rendu :</span>
            </td>
            <td style="text-align:right;">
                <?php echo e(number_format((float)$renduFCFA, 0, ',', ' ')); ?> FCFA
            </td>
        </tr>
        <?php endif; ?>
        <?php endif; ?>

        
        <tr style="background-color: <?php echo e(($facture['reste'] ?? 0) > 0 ? '#fff0f0' : '#f0fff0'); ?>;">
            <td colspan="3" style="text-align:right; font-weight:bold;">Reste à payer :</td>
            <td style="text-align:right; font-weight:bold; color: <?php echo e(($facture['reste'] ?? 0) > 0 ? '#c00' : '#060'); ?>;">
                <?php echo e(number_format((float)($facture['reste'] ?? 0), 0, ',', ' ')); ?> FCFA
            </td>
        </tr>

    </tfoot>
</table>


<div style="display:table; width:100%; margin-top:12px; font-size:10px;">
    <div style="display:table-cell; text-align:left;">
        Caissier(e) : <strong><?php echo e($caissier ?: '—'); ?></strong>
    </div>
    <div style="display:table-cell; text-align:right;">
        Douala, le <?php echo e(isset($facture['created_at']) ? \Carbon\Carbon::parse($facture['created_at'])->format('d/m/Y') : '—'); ?>

    </div>
</div>


<?php if($is_proforma): ?>
<div style="margin-top:10px; padding:6px 10px; border:1px solid #c8a000; background-color:#fffbe6; font-size:10px; text-align:center;">
    <strong>⚠ DOCUMENT PROFORMA</strong> — Cette facture n'est pas encore soldée.
</div>
<?php endif; ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/etats/partials/corps_facture_consultation.blade.php ENDPATH**/ ?>