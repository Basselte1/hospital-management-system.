<div class="text-center">
    <?php if(file_exists(public_path('admin/images/logo.jpg'))): ?>
        <img class="logo" src="<?php echo e(public_path('admin/images/logo.jpg')); ?>">
    <?php endif; ?>
    <h6 class="bold">CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</h6>
    <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
    <h6>TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945</h6>
    <h6>www.cmcu-cm.com</h6>
</div>

<div class="text-center">
    <h6 class="invoice-id">
        RECU <?php echo e(strtoupper($facture['details_motif'] ?? 'CONSULTATION')); ?>

        N° <?php echo e($patient['numero_dossier']); ?>

    </h6>
</div>

<?php if(!empty($facture['assurancec'])): ?>
    <h6 class="text-center">ASSURANCE: <?php echo e($facture['assurance'] ?? ''); ?></h6>
<?php endif; ?>

<?php if(!empty($patient['demarcheur'])): ?>
    <h6 class="text-center"><?php echo e($patient['demarcheur']); ?></h6>
<?php endif; ?>

<?php if(!empty($facture['assurancec'])): ?>
    <h6 class="text-center">
        PART ASSURANCE: <?php echo e($facture['assurancec']); ?> |
        PART PATIENT: <?php echo e($patient['assurec'] ?? 0); ?>

    </h6>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th class="text-left">NOM</th>
            <th class="text-left">PRENOM</th>
            <th class="text-left">MONTANT (FCFA)</th>
            <th class="text-left">AVANCE</th>
            <th class="text-left">RESTE</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-left"><h5><?php echo e($patient['name']); ?></h5></td>
            <td class="text-left"><h5><?php echo e($patient['prenom']); ?></h5></td>
            <td class="text-left"><h4><?php echo e(number_format($facture['montant'], 0, ',', ' ')); ?></h4></td>
            <td class="text-left"><h4><?php echo e(number_format($facture['avance'] ?? 0, 0, ',', ' ')); ?></h4></td>
            <td class="text-left"><h4><?php echo e(number_format($facture['reste'] ?? 0, 0, ',', ' ')); ?></h4></td>
        </tr>
    </tbody>
</table>

<div class="notices">
    <h6>LA CAISSE: <?php echo e(($patient['user']['prenom'] ?? '') . ' ' . ($patient['user']['name'] ?? 'N/A')); ?></h6>
    <h6>Douala, <?php echo e(isset($patient['created_at']) ? \Carbon\Carbon::parse($patient['created_at'])->format('d/m/Y') : date('d/m/Y')); ?></h6>
</div>

<footer>
    Centre Medico-chirurgical d'urologie situé à la Vallée Douala Manga Bell Douala-Bali.<br>
    TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945<br>
    SITE WEB: http://www.cmcu-cm.com
</footer>
<?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/etats/partials/facture_content.blade.php ENDPATH**/ ?>