
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fiche Encaissements — <?php echo e($date ?? ''); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            padding: 10px 12px;
            color: #1a2332;
            background: #ffffff;
        }

        /* ══════════════════════════════════════════════════════
           EN-TÊTE ÉTABLISSEMENT
        ══════════════════════════════════════════════════════ */
        .header-container {
            margin-bottom: 10px;
            padding: 10px 12px;
            border-radius: 5px;
          
        }
        .header-flex   { display: table; width: 100%; border-collapse: collapse; }
        .header-logo   { display: table-cell; width: 85px; vertical-align: middle; padding-right: 10px; }
        .logo          { width: 78px; height: auto; border-radius: 4px; }
        .header-info   { display: table-cell; text-align: center; vertical-align: middle; }
        .company-name  {
            font-size: 12px; font-weight: bold; color: #1a5490;
            text-transform: uppercase; letter-spacing: 0.4px;
            margin-top:-55px;
        }
        .company-details { font-size: 8.5px; color: #495057; margin: 1px 0; }
        .divider {
            border: none; height: 2px; margin: 8px 0;
            background: linear-gradient(to right, #1a5490, #3498db, #1a5490);
            border-radius: 2px;
        }

        /* ══════════════════════════════════════════════════════
           TITRE DU DOCUMENT
        ══════════════════════════════════════════════════════ */
        .document-title {
            text-align: center; font-size: 11px; font-weight: bold;
            margin: 0 0 8px 0; text-transform: uppercase; padding: 8px;
            background: linear-gradient(135deg, #1a5490 0%, #2980b9 100%);
            color: white; border-radius: 4px; letter-spacing: 0.8px;
        }

        /* ══════════════════════════════════════════════════════
           BLOC MÉTADONNÉES — HORIZONTAL (4 cellules, 1 ligne)
           Correction 5
        ══════════════════════════════════════════════════════ 
        .doc-meta-wrap {
            width: 100%;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .doc-meta-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .doc-meta-cell {
            display: table-cell;
            width: 25%;
            padding: 5px 8px;
            border-right: 1px solid #cbd5e0;
            vertical-align: middle;
            background: #f8fafc;
            white-space: nowrap;
        }
        .doc-meta-cell:nth-child(1) { background: #eef4fb; }
        .doc-meta-cell:nth-child(4) { background: #fffbeb; border-right: none; }
        .doc-meta-label {
            font-size: 7px; text-transform: uppercase; letter-spacing: 0.4px;
            color: #718096; display: block; margin-bottom: 2px;
        }
        .doc-meta-value {
            font-size: 9.5px; font-weight: bold; color: #1a2332;
        }


        /* */
        .doc-meta-row {
            display: flex;
            flex-direction: column; /* aligne les enfants en colonne */
            width: 100%;
        }

        .doc-meta-cell {
            display: block; /* chaque cellule prend toute la largeur */
            padding: 8px 10px;
            border-bottom: 1px solid #cbd5e0;
            background: #f8fafc;
        }

        .doc-meta-cell:last-child {
            border-bottom: none; /* pas de bordure sur le dernier */
        }

        .doc-meta-cell:nth-child(1) { background: #eef4fb; }
        .doc-meta-cell:nth-child(4) { background: #fffbeb; }
        .doc-meta-label {
            font-size: 7px; text-transform: uppercase; letter-spacing: 0.4px;
            color: #718096; 
        }
        .doc-meta-value {
            font-size: 9.5px; font-weight: bold; color: #1a2332;
        }


        /* ══════════════════════════════════════════════════════
           RÉSUMÉ FINANCIER — HORIZONTAL (5 cellules, 1 ligne)
           Correction 6
        ══════════════════════════════════════════════════════ 
        .summary-wrap {
            width: 100%;
            border: 1px solid #1a5490;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .summary-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .summary-cell {
            display: table-cell;
            width: 20%;
            text-align: center;
            padding: 6px 4px;
            border-right: 1px solid #bee3f8;
            vertical-align: middle;
        }
        .summary-cell:last-child   { border-right: none; }
        .summary-cell:nth-child(1) { background: #ebf5ff; }
        .summary-cell:nth-child(2) { background: #e6ffed; }
        .summary-cell:nth-child(3) { background: #fff5f5; }
        .summary-cell:nth-child(4) { background: #f5f5f5; }
        .summary-cell:nth-child(5) { background: #faf5ff; }

        .summary-label {
            font-size: 7px; text-transform: uppercase; letter-spacing: 0.3px;
            color: #4a5568; display: block; margin-bottom: 2px;
            white-space: nowrap;
        }
        .summary-value {
            font-size: 10.5px; font-weight: bold;
            font-family: 'DejaVu Sans Mono', monospace;
            white-space: nowrap;
        }
        .sv-montant    { color: #1a5490; }
        .sv-percu      { color: #27ae60; }
        .sv-reste      { color: #c0392b; }
        .sv-assurec    { color: #546e7a; }
        .sv-assurancec { color: #8e44ad; }*/

            .summary-wrap {
                width: 100%;
                border: 1px solid #1a5490;
                border-radius: 4px;
                overflow: hidden;
                margin-bottom: 8px;
            }

            .summary-row {
                display: flex;              /* aligne les cellules en ligne */
                flex-direction: row;        /* horizontal */
                width: 100%;
            }

            .summary-cell {
                flex: 1;                    /* chaque cellule prend une part égale */
                display: flex;
                flex-direction: column;     /* label au-dessus, valeur en dessous */
                justify-content: center;
                align-items: center;
                text-align: center;
                padding: 6px 4px;
                border-right: 1px solid #bee3f8;
            }

            .summary-cell:last-child { border-right: none; }

            .summary-cell:nth-child(1) { background: #ebf5ff; }
            .summary-cell:nth-child(2) { background: #e6ffed; }
            .summary-cell:nth-child(3) { background: #fff5f5; }
            .summary-cell:nth-child(4) { background: #f5f5f5; }
            .summary-cell:nth-child(5) { background: #faf5ff; }

            .summary-label {
                font-size: 7px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                color: #4a5568;
                margin-bottom: 2px;
                white-space: nowrap;
            }

            .summary-value {
                font-size: 10.5px;
                font-weight: bold;
                font-family: 'DejaVu Sans Mono', monospace;
                white-space: nowrap;
            }

            .sv-montant    { color: #1a5490; }
            .sv-percu      { color: #27ae60; }
            .sv-reste      { color: #c0392b; }
            .sv-assurec    { color: #546e7a; }
            .sv-assurancec { color: #8e44ad; }


        /* ══════════════════════════════════════════════════════
           TABLEAU PRINCIPAL
        ══════════════════════════════════════════════════════ */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 8px 0;
            border: 1px solid #90a4ae;
        }
        .main-table th,
        .main-table td {
            border: 1px solid #90a4ae;
            padding: 4px 4px;
            text-align: left;
            vertical-align: middle;
        }
        .main-table thead th {
            background: linear-gradient(135deg, #263238 0%, #37474f 100%);
            color: white;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 7px;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }
        .main-table tbody tr:nth-child(even) { background-color: #f7f9fc; }

        /* Largeurs colonnes (14 colonnes — sans Caissier) */
        .col-sn         { width: 3%;  text-align: center; font-weight: 600; color: #546e7a; }
        .col-heure      { width: 5%;  text-align: center; white-space: nowrap; }
        .col-patient    { width: 14%; color: #1a2332; font-weight: 500; }
        .col-nature     { width: 19%; } /* details_motif = description réelle */
        .col-montant,
        .col-avance,
        .col-reste      { width: 7%;  text-align: right; }
        .col-part-p,
        .col-part-a     { width: 6%;  text-align: right; }
        .col-statut     { width: 9%;  text-align: center; }
        .col-demarcheur { width: 8%;  font-size: 7.5px; color: #546e7a; }
        .col-medecin    { width: 9%;  font-size: 7.5px; }
        .col-ecart      { width: 5%;  text-align: center; }
        .col-numero     { width: 5%;  text-align: center; font-weight: 600; color: #2980b9; }

        .amount {
            font-family: 'DejaVu Sans Mono', monospace;
            white-space: nowrap;
            font-weight: 600;
        }

        /* Statut paiement */
        .statut-solde   { color: #2e7d32; font-weight: bold; font-size: 7.5px; }
        .statut-acompte { color: #e65100; font-weight: bold; font-size: 7.5px; }
        .statut-attente { color: #b71c1c; font-weight: bold; font-size: 7.5px; }

        /* Écart */
        .ecart-ok     { color: #2e7d32; font-weight: bold; }
        .ecart-alerte { color: #c0392b; font-weight: bold; }

        /* Ligne de total */
        .total-row th,
        .total-row td {
            background: linear-gradient(135deg, #1a5490 0%, #2980b9 100%);
            color: white; font-weight: bold; font-size: 8.5px;
            border-color: #1a5490;
        }
        .total-row th { text-align: left; padding-left: 6px; text-transform: uppercase; }

        thead { display: table-header-group; }
        tr    { page-break-inside: avoid; }

        /* ══════════════════════════════════════════════════════
           MODE DE PAIEMENT
        ══════════════════════════════════════════════════════ */
        .payment-block {
            margin: 8px 0 4px 0;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
        }
        .payment-block-header {
            background: linear-gradient(135deg, #ecf0f1 0%, #dfe6e9 100%);
            padding: 5px 10px;
            font-weight: bold;
            font-size: 8.5px;
            text-transform: uppercase;
            color: #2c3e50;
            letter-spacing: 0.3px;
        }
        .payment-table { width: 100%; border-collapse: collapse; }
        .payment-table td {
            border: 1px solid #dee2e6;
            padding: 5px 6px;
            text-align: center;
            font-size: 8.5px;
        }
        .payment-table .ph {
            background: linear-gradient(135deg, #607d8b 0%, #78909c 100%);
            color: white; font-weight: bold; text-transform: uppercase;
            font-size: 7.5px; letter-spacing: 0.3px;
        }
        .payment-table .pv {
            font-size: 10px; background: #fff;
            color: #27ae60; font-weight: bold;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        /* ══════════════════════════════════════════════════════
           SIGNATURES — HORIZONTALES, MÊME PAGE
           Correction 4 :
           - display table → 3 cellules côte à côte
           - page-break-inside: avoid  → jamais coupées
           - page-break-before: avoid  → collées au contenu
        ══════════════════════════════════════════════════════ 
        .signatures-section {
            page-break-inside: avoid;
            page-break-before: avoid;
            margin-top: 6px;
            border-top: 2px solid #dee2e6;
            padding-top: 8px;
        }
        .sig-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .sig-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 10px;
            vertical-align: top;
            border-right: 1px dashed #cbd5e0;
        }
        .sig-cell:last-child { border-right: none; }

        .sig-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            display: block;
            padding-bottom: 3px;
            border-bottom: 2px solid #3498db;
            margin-bottom: 6px;
        }
        .sig-name {
            font-size: 8px;
            color: #2c3e50;
            font-weight: bold;
            display: block;
            margin-bottom: 26px;  espace pour signature manuscrite 
        }
        .sig-line {
            border-top: 1px solid #90a4ae;
            padding-top: 2px;
            font-size: 7px;
            color: #b0bec5;
            font-style: italic;
        }*/
       
        .sig-row {
            display: flex;                  /* aligne les cellules en ligne */
            justify-content: center;
            align-items : center;        /* centre les colonnes dans la largeur */
           
        }

        .sig-cell {
                                /* chaque cellule prend une largeur égale */
            text-align: center;
            padding: 10px 10px;
            border-right: 1px dashed #cbd5e0;
        }

        .sig-cell:last-child { border-right: none; }

        .sig-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #5c5d5f;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            display: block;
            padding-bottom: 3px;
            border-bottom: 2px solid #3498db;
            margin-bottom: 6px;
        }

        .sig-name {
            font-size: 8px;
            color: #2c3e50;
            font-weight: bold;
            display: block;
            margin-bottom: 26px; /* espace pour signature manuscrite */
        }

        .sig-line {
            border-top: 1px solid #90a4ae;
            padding-top: 2px;
            font-size: 7px;
            color: #b0bec5;
            font-style: italic;
        }


        /* ══════════════════════════════════════════════════════
           PIED DE PAGE FIXE
        ══════════════════════════════════════════════════════ */
        .footer {
            position:fixed;
            bottom: 6px;
          
            left: 0; right: 0;
            text-align: center;
            font-size: 7px;
            padding: 4px;
            border-top: 1px solid #e2e8f0;
            color: #99a3af;
            background: #f8fafc;
        }

        /* ══════════════════════════════════════════════════════
           ADAPTATION AUTOMATIQUE DU CONTENU
           Correction 8 :
           < 15 lignes  → police normale (9px)
           15–25 lignes → .compact  (7.5px)
           > 25 lignes  → .very-compact (7px)
        ══════════════════════════════════════════════════════ */
        body.compact .main-table th,
        body.compact .main-table td  { font-size: 7.5px; padding: 3px 3px; }
        body.compact .main-table thead th { font-size: 6.5px; }
        body.compact .amount         { font-size: 7.5px; }
        body.compact .statut-solde,
        body.compact .statut-acompte,
        body.compact .statut-attente { font-size: 7px; }
        body.compact .col-nature     { font-size: 7px; }

        body.very-compact .main-table th,
        body.very-compact .main-table td  { font-size: 7px; padding: 2px 3px; }
        body.very-compact .main-table thead th { font-size: 6px; }
        body.very-compact .amount          { font-size: 7px; }
        body.very-compact .col-nature      { font-size: 6.5px; }

        @media print {
            body  { padding: 6px; }
            .main-table { page-break-inside: auto; }
        }
    </style>
</head>

<?php $nbLignes = $tFactures->count(); ?>

<body class="<?php echo e($nbLignes >= 26 ? 'very-compact' : ($nbLignes >= 15 ? 'compact' : '')); ?>">

    
    <div class="header-container">
        <div class="header-flex">
            <div class="header-logo">
                <?php if(file_exists(public_path('admin/images/logo.jpg'))): ?>
                    <img class="logo" src="<?php echo e(public_path('admin/images/logo.jpg')); ?>" alt="Logo CMCU">
                <?php endif; ?>
            </div>
            <div class="header-info">
                <div class="company-name">Centre Médico-Chirurgical d'Urologie</div>
                <div class="company-details">Vallée Manga Bell — Douala-Bali</div>
                <div class="company-details">Tél : (+237) 233 423 389 / 674 068 988 / 698 873 945</div>
                <div class="company-details">www.cmcu-cm.com</div>
            </div>
        </div>
        <hr class="divider">
    </div>

    
    <div class="document-title">
        Fiche de Suivi des Encaissements Journaliers
        <?php if(!empty($service)): ?> — <?php echo e(strtoupper($service)); ?> <?php endif; ?>
    </div>

    
    <div class="doc-meta-wrap">
        <div class="doc-meta-row">
            <div class="doc-meta-cell">
                <span class="doc-meta-label">Date de la journee :</span>
                <span class="doc-meta-value">
                    <?php echo e(isset($date) ? \Carbon\Carbon::parse($date)->format('d/m/Y') : '—'); ?>

                </span>
            </div>
            <div class="doc-meta-cell">
                <span class="doc-meta-label">Caissier responsable :</span>
                <span class="doc-meta-value"><?php echo e($caissier ?? '—'); ?></span>
            </div>
            <div class="doc-meta-cell">
                <span class="doc-meta-label">Service :</span>
                <span class="doc-meta-value">
                    <?php echo e(!empty($service) ? $service : 'Tous services'); ?>

                </span>
            </div>
            <div class="doc-meta-cell">
                <span class="doc-meta-label">Nombre de factures :</span>
                <span class="doc-meta-value" style="color:#c05621;">
                    <?php echo e($nbLignes); ?> facture(s)
                </span>
            </div>
        </div>
    </div>

    
    <div class="summary-wrap">
        <div class="summary-row">
            <div class="summary-cell">
                <span class="summary-label">Montant facture</span>
                <span class="summary-value sv-montant">
                    <?php echo e(number_format($totalMontant, 0, ',', ' ')); ?> F
                </span>
            </div>
            <div class="summary-cell">
                <span class="summary-label">Total percu</span>
                <span class="summary-value sv-percu">
                    <?php echo e(number_format($totalPercu, 0, ',', ' ')); ?> F
                </span>
            </div>
            <div class="summary-cell">
                <span class="summary-label">Reste a recouvrer</span>
                <span class="summary-value sv-reste">
                    <?php echo e(number_format($totalReste, 0, ',', ' ')); ?> F
                </span>
            </div>
            <div class="summary-cell">
                <span class="summary-label">Part patients (assurec)</span>
                <span class="summary-value sv-assurec">
                    <?php echo e(number_format($totalPartPatient, 0, ',', ' ')); ?> F
                </span>
            </div>
            <div class="summary-cell">
                <span class="summary-label">Part assurance</span>
                <span class="summary-value sv-assurancec">
                    <?php echo e(number_format($totalPartAssurance, 0, ',', ' ')); ?> F
                </span>
            </div>
        </div>
    </div>

    
    <table class="main-table">
        <thead>
            <tr>
                <th class="col-sn">N°</th>
                <th class="col-heure">Heure</th>
                <th class="col-patient">Patient</th>
                <th class="col-nature">Description / Acte</th>
                <th class="col-montant">Montant</th>
                <th class="col-avance">Percu</th>
                <th class="col-reste">Reste</th>
                <th class="col-part-p">Part Pat.</th>
                <th class="col-part-a">Part Ass.</th>
                <th class="col-statut">Statut Paiement</th>
                <th class="col-demarcheur">Demarcheur</th>
                <th class="col-medecin">Medecin</th>
                <th class="col-ecart">Ecart</th>
                <th class="col-numero">N° Fact.</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $tFactures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    /*
                     * DESCRIPTION / ACTE (correction NB)
                     * Priorité : details_motif → motif → '—'
                     * Tronqué à 45 caractères pour ne pas casser le layout
                     */
                    $description = !empty($facture->details_motif)
                        ? $facture->details_motif
                        : ($facture->motif ?? '—');
                    $description = mb_strlen($description) > 45
                        ? mb_substr($description, 0, 45) . '…'
                        : $description;

                    /*
                     * HEURE (correction 3)
                     * Alimentée par le controller :
                     *   $factureData->heure = $historique_facture
                     *       ->created_at->format('H:i')
                     */
                    $heure = $facture->heure ?? '—';

                    /* STATUT PAIEMENT */
                    $reste = $facture->reste ?? 0;
                    $percu = $facture->percu ?? 0;
                    if ($reste == 0) {
                        $statutLabel = 'Paye integralmt';
                        $statutClass = 'statut-solde';
                    } elseif ($percu > 0) {
                        $statutLabel = 'Acompte verse';
                        $statutClass = 'statut-acompte';
                    } else {
                        $statutLabel = 'En attente';
                        $statutClass = 'statut-attente';
                    }

                    /* ÉCART PAR LIGNE (alerte globale supprimée — correction 1) */
                    $partP   = $facture->partPatient   ?? 0;
                    $partA   = $facture->partAssurance ?? 0;
                    $montant = $facture->montant       ?? 0;
                    $ecart   = abs($montant - ($partP + $partA));
                    $ecartOk = $ecart < 1;
                ?>
                <tr>
                    <td class="col-sn"><?php echo e($index + 1); ?></td>

                    
                    <td class="col-heure">
                        <span style="font-family:'DejaVu Sans Mono',monospace;">
                            <?php echo e($heure); ?>

                        </span>
                    </td>

                    <td class="col-patient"><?php echo e($facture->name ?? '—'); ?></td>

                    
                    <td class="col-nature"><?php echo e($description); ?></td>

                    <td class="col-montant">
                        <span class="amount"><?php echo e(number_format($montant, 0, ',', ' ')); ?></span>
                    </td>
                    <td class="col-avance">
                        <span class="amount" style="color:#27ae60;">
                            <?php echo e(number_format($percu, 0, ',', ' ')); ?>

                        </span>
                    </td>
                    <td class="col-reste">
                        <span class="amount <?php echo e($reste > 0 ? 'ecart-alerte' : 'ecart-ok'); ?>">
                            <?php echo e(number_format($reste, 0, ',', ' ')); ?>

                        </span>
                    </td>
                    <td class="col-part-p">
                        <span class="amount"><?php echo e(number_format($partP, 0, ',', ' ')); ?></span>
                    </td>
                    <td class="col-part-a">
                        <span class="amount" style="color:#8e44ad;">
                            <?php echo e(number_format($partA, 0, ',', ' ')); ?>

                        </span>
                    </td>

                    
                    <td class="col-statut">
                        <span class="<?php echo e($statutClass); ?>"><?php echo e($statutLabel); ?></span>
                    </td>

                    

                    <td class="col-demarcheur"><?php echo e($facture->demarcheur ?? '—'); ?></td>
                    <td class="col-medecin"><?php echo e($facture->medecin ?? '—'); ?></td>

                    
                    <td class="col-ecart">
                        <?php if($ecartOk): ?>
                            <span class="ecart-ok">0</span>
                        <?php else: ?>
                            <span class="ecart-alerte">
                                <?php echo e(number_format($ecart, 0, ',', ' ')); ?>

                            </span>
                        <?php endif; ?>
                    </td>

                    <td class="col-numero"><?php echo e($facture->numero ?? '—'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="14" style="text-align:center; padding:10px; color:#a0aec0; font-style:italic;">
                        Aucune facture enregistree pour cette journee.
                    </td>
                </tr>
            <?php endif; ?>

            
            <?php if($nbLignes > 0): ?>
            <tr class="total-row">
                <th colspan="4">TOTAUX (<?php echo e($nbLignes); ?> facture(s))</th>
                <td class="col-montant">
                    <span class="amount"><?php echo e(number_format($totalMontant, 0, ',', ' ')); ?></span>
                </td>
                <td class="col-avance">
                    <span class="amount"><?php echo e(number_format($totalPercu, 0, ',', ' ')); ?></span>
                </td>
                <td class="col-reste">
                    <span class="amount"><?php echo e(number_format($totalReste, 0, ',', ' ')); ?></span>
                </td>
                <td class="col-part-p">
                    <span class="amount"><?php echo e(number_format($totalPartPatient, 0, ',', ' ')); ?></span>
                </td>
                <td class="col-part-a">
                    <span class="amount"><?php echo e(number_format($totalPartAssurance, 0, ',', ' ')); ?></span>
                </td>
                <td colspan="5"></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <?php if($mode_paiement && $mode_paiement->count() > 0): ?>
    <div class="payment-block">
        <div class="payment-block-header">Detail des encaissements par mode de paiement</div>
        <table class="payment-table">
            <tr>
                <?php $__currentLoopData = $mode_paiement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="ph"><?php echo e($mp->name); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <tr>
                <?php $__currentLoopData = $mode_paiement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="pv"><?php echo e(number_format($mp->val, 0, ',', ' ')); ?> F</td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </table>
    </div>
    <?php endif; ?>

    
    <div class="signatures-section">
        <div class="sig-row">

            <div class="sig-cell">
                <span class="sig-title">Caissier(e) responsable  :</span>
                <span class="sig-name"><?php echo e($caissier ?? '&nbsp;'); ?></span>
                <div class="sig-line">Signature</div>
            </div>

            <div class="sig-cell">
                <span class="sig-title">Comptable  : </span>
                <span class="sig-name">&nbsp;</span>
                <div class="sig-line">Signature</div>
            </div>

            <div class="sig-cell">
                <span class="sig-title">Direction / Superviseur  :</span>
                <span class="sig-name">&nbsp;</span>
                <div class="sig-line">Signature</div>
            </div>
        </div>
    </div>

    
    <div class="footer">
        CMCU — Fiche d'encaissement journalier —
        Generee le <?php echo e(now()->format('d/m/Y a H:i')); ?> —
        Document confidentiel a usage interne
    </div>

</body>
</html><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/etats/bilan_consultation.blade.php ENDPATH**/ ?>