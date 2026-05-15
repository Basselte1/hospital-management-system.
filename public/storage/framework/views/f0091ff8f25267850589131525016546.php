<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>LETTRE DE CONSULTATION - <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; line-height: 1.5; color: #333; margin: 0; padding: 20px 40px; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .header-logo { width: 18%; vertical-align: middle; padding-right: 15px; }
        .header-info { width: 82%; text-align: center; font-size: 9pt; line-height: 1.4; }
        .header-title { font-weight: bold; font-size: 13pt; color: #4463dc; text-transform: uppercase; margin-bottom: 3px; }
        .divider { border-top: 3px solid #4463dc; margin: 8px 0 15px 0; }
        .meta-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 10pt; }
        .subject { text-align: center; margin: 25px 0; font-weight: bold; text-decoration: underline; font-size: 12pt; }
        .patient-id { text-align: center; margin-bottom: 20px; font-style: italic; }
        .closing { margin-top: 30px; }
        .signature-block { margin-top: 40px; text-align: right; }
        .signature-name { font-weight: bold; margin-top: 25px; }
        .footer-band { position: fixed; bottom: 0; left: 0; right: 0; background-color: #4463dc; color: #fff; text-align: center; font-size: 7pt; padding: 4px 15px; line-height: 1.4; }
        @media print { body { padding: 15mm 25mm; } }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="<?php echo e(public_path('admin/images/logo.jpg')); ?>" style="width: 75px; height: auto;" alt="Logo CMCU">
            </td>
            <td class="header-info">
                <div class="header-title">Centre Médico-Chirurgical d'Urologie</div>
                <div>ONMC : N°5531 - Décision N°007/10/D/ONMC/P/SG/MM</div>
                <div>Arrêté N° 3203/A/MINSANTE/SG/DOSTS/SDOS/SFSP</div>
                <div>Tél : +237 233 42 33 89 / +237 698 87 39 45 / +237 674 06 89 88</div>
                <div>www.cmcu-cm.com | cmcu_cmcu@yahoo.fr</div>
                <div>Vallée Manga Bell Douala - Bali | Consultation sur rendez-vous</div>
                <div>N° Contribuable : P016400474386D</div>
            </td>
        </tr>
    </table>
    <div class="divider"></div>

    <div class="meta-row">
        <div>
            <strong>Dr <?php echo e($consultations->user->name); ?> <?php echo e($consultations->user->prenom); ?></strong><br>
            <?php echo e($consultations->user->specialite); ?>

            <?php if($consultations->user->onmc): ?> - ONMC : <?php echo e($consultations->user->onmc); ?> <?php endif; ?>
        </div>
        <div>
            Douala, le <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?><br>
            <?php if($consultations->medecin_r): ?>
                À l'attention du Dr <?php echo e($consultations->medecin_r); ?>

            <?php endif; ?>
        </div>
    </div>

    <div class="subject">LETTRE DE LIAISON - CONSULTATION D'UROLOGIE</div>

    <?php $civilite = ($dossier && $dossier->sexe === 'Masculin') ? 'M.' : 'Mme'; ?>

    <div class="patient-id">
        Concernant : <?php echo e($civilite); ?>

        <strong><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></strong>
        <?php if($dossier && $dossier->date_naissance): ?>
            <br>Né<?php echo e($civilite === 'Mme' ? 'e' : ''); ?> le <?php echo e(\Carbon\Carbon::parse($dossier->date_naissance)->translatedFormat('d F Y')); ?>

            <?php if($dossier->age): ?> • Âge : <?php echo e($dossier->age); ?> ans <?php endif; ?>
        <?php endif; ?>
    </div>

    <p>Cher Confrère,
    J'ai l'honneur de vous adresser ce courrier suite à la consultation d'urologie effectuée le
    <strong><?php echo e(\Carbon\Carbon::parse($consultations->created_at ?? now())->translatedFormat('d F Y')); ?></strong>
    concernant <strong><?php echo e($civilite); ?> <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></strong>.
    <?php if($consultations->motif_c): ?>
        Le motif principal était : <strong><?php echo e($consultations->motif_c); ?></strong>.
    <?php endif; ?>
    <?php if($consultations->interrogatoire || $consultations->antecedent_m || $consultations->antecedent_c || $consultations->allergie): ?>
        L'anamnèse a révélé
        <?php if($consultations->interrogatoire): ?> <?php echo e($consultations->interrogatoire); ?> <?php endif; ?>
        <?php if($consultations->antecedent_m): ?> avec antécédents médicaux <?php echo e($consultations->antecedent_m); ?>, <?php endif; ?>
        <?php if($consultations->antecedent_c): ?> chirurgicaux <?php echo e($consultations->antecedent_c); ?>, <?php endif; ?>
        <?php if($consultations->allergie): ?> et allergies connues : <?php echo e($consultations->allergie); ?>. <?php endif; ?>
    <?php endif; ?>
    <?php if($consultations->groupe): ?>
        Les données biologiques incluent le groupe sanguin : <?php echo e($consultations->groupe); ?>.
    <?php endif; ?>
    </p>

    <p>
    <?php if($consultations->examen_p): ?>
        L'examen physique a montré : <?php echo e($consultations->examen_p); ?>.
    <?php endif; ?>
    <?php if($consultations->examen_c): ?>
        Les examens complémentaires réalisés étaient : <?php echo nl2br(e($consultations->examen_c)); ?>.
    <?php endif; ?>
    <?php if($consultations->diagnostic): ?>
        Le diagnostic retenu est : <strong><?php echo e($consultations->diagnostic); ?></strong>.
    <?php endif; ?>
    <?php if($consultations->proposition_therapeutique): ?>
        Sur cette base, le traitement proposé est le suivant : <strong><?php echo e($consultations->proposition_therapeutique); ?></strong>.
    <?php endif; ?>
    <?php if($consultations->proposition): ?>
        Les modalités de suivi incluent :
        <?php if($consultations->proposition == 'Hospitalisation'): ?>
            une hospitalisation pour suivi médical
        <?php endif; ?>
        <?php if($consultations->proposition == 'Intervention chirurgicale'): ?>
            une intervention chirurgicale
            <?php if($consultations->type_intervention): ?> (type : <strong><?php echo e($consultations->type_intervention); ?></strong><?php if($consultations->date_intervention): ?>, prévue le <strong><?php echo e($consultations->date_intervention); ?></strong><?php endif; ?>) <?php endif; ?>
        <?php endif; ?>
        <?php if($consultations->proposition == 'Consultation'): ?>
            une consultation de contrôle
            <?php if($consultations->date_consultation): ?> prévue le <?php echo e($consultations->date_consultation); ?> <?php endif; ?>
        <?php endif; ?>
        <?php if($consultations->proposition == "Consultation d'anesthésiste"): ?>
            une consultation pré-anesthésique
            <?php if($consultations->date_consultation_anesthesiste): ?> prévue le <?php echo e($consultations->date_consultation_anesthesiste); ?> <?php endif; ?>
        <?php endif; ?>
        <?php if($consultations->proposition == 'Actes à réaliser' && $consultations->acte): ?>
            des actes complémentaires : <?php echo nl2br(e($consultations->acte)); ?>

        <?php endif; ?>
        .
    <?php endif; ?>
    </p>

    <p>Je reste à votre entière disposition pour tout complément d'information concernant la prise en charge de ce patient.
        Veuillez agréer, Cher Confrère, l'expression de mes salutations les plus confraternelles.</p>

    <div class="signature-block">
        <div class="signature-name">Dr <?php echo e($consultations->user->name); ?> <?php echo e($consultations->user->prenom); ?></div>
        <div><?php echo e($consultations->user->specialite); ?></div>
        <?php if($consultations->user->onmc): ?>
            <div style="font-size: 9pt;">ONMC : <?php echo e($consultations->user->onmc); ?></div>
        <?php endif; ?>
    </div>

    <div class="footer-band">
        Urgences Urologiques • Cancérologie • Centre de la Prostate • Cœlioscopie • Lithiase urinaire •
        Incontinence • Stérilité masculine • Dysfonction érectile • Lithotritie extracorporelle •
        Endoscopie • Échographie • Débitmétrie • Biopsies prostatiques • Médecine générale • Médecine du travail
    </div>

</body>
</html><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/etats/lettre.blade.php ENDPATH**/ ?>