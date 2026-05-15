

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapport — <?php echo e($user->name); ?> <?php echo e($user->prenom); ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            background: #fff;
            padding: 32px;
        }

        /* ── En-tête ───────────────────────────────────── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .header-left h1 { font-size: 18px; font-weight: 700; color: #0d6efd; }
        .header-left p  { font-size: 11px; color: #666; margin-top: 2px; }
        .header-right   { text-align: right; font-size: 11px; color: #444; }
        .header-right strong { display: block; font-size: 13px; color: #1a1a1a; }

        /* ── Bandeau identité ──────────────────────────── */
        .identity-bar {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 0 6px 6px 0;
        }
        .identity-bar .name   { font-size: 15px; font-weight: 700; }
        .identity-bar .role   { font-size: 11px; color: #555; margin-top: 2px; }
        .identity-bar .period { font-size: 11px; color: #888; margin-top: 4px; }

        /* ── Grille KPI ────────────────────────────────── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 24px;
        }
        .kpi-card {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 10px 12px;
        }
        .kpi-card .kpi-label { font-size: 10px; color: #777; margin-bottom: 4px; }
        .kpi-card .kpi-value { font-size: 16px; font-weight: 700; color: #1a1a1a; }
        .kpi-card .kpi-unit  { font-size: 10px; color: #999; font-weight: normal; }
        .kpi-card.highlight  { border-color: #0d6efd; background: #f0f5ff; }
        .kpi-card.highlight .kpi-value { color: #0d6efd; }

        /* ── Tableau détail ────────────────────────────── */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { background: #0d6efd; color: #fff; }
        thead th { padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody tr:last-child { font-weight: 700; background: #eef2ff; }
        td, tbody th { padding: 7px 10px; font-size: 11px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .badge-active  { background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 99px; font-size: 10px; }
        .badge-empty   { background: #f3f4f6; color: #9ca3af; padding: 2px 8px; border-radius: 99px; font-size: 10px; }

        /* ── Pied de page ──────────────────────────────── */
        .footer {
            margin-top: 32px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px;
            color: #aaa;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            .kpi-grid { grid-template-columns: repeat(3, 1fr); }
        }
    </style>
</head>
<body>

    
    <div class="no-print" style="margin-bottom:16px">
        <button onclick="window.print()"
                style="background:#0d6efd;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:13px">
            🖨️ Imprimer / Enregistrer en PDF
        </button>
        <a href="<?php echo e(url()->previous()); ?>"
           style="margin-left:10px;font-size:12px;color:#555;text-decoration:none">← Retour</a>
    </div>

    
    <div class="header">
        <div class="header-left">
            <h1>Rapport d'activité</h1>
            <p>Système de gestion hospitalière — <?php echo e(now()->format('d/m/Y H:i')); ?></p>
        </div>
        <div class="header-right">
            <strong>Période analysée</strong>
            Du <?php echo e(\Carbon\Carbon::parse($dateDebut)->format('d/m/Y')); ?>

            au <?php echo e(\Carbon\Carbon::parse($dateFin)->format('d/m/Y')); ?>

        </div>
    </div>

    
    <?php $config = $config ?? []; ?>
    <div class="identity-bar">
        <div class="name"><?php echo e($user->name); ?> <?php echo e($user->prenom); ?></div>
        <div class="role">
            <?php echo e($config['label'] ?? 'Utilisateur'); ?>

            <?php if($user->specialite): ?> — <?php echo e($user->specialite); ?> <?php endif; ?>
            <?php if($user->onmc): ?> (ONMC : <?php echo e($user->onmc); ?>) <?php endif; ?>
        </div>
        <div class="period">
            Rapport généré le <?php echo e(now()->translatedFormat('d F Y à H:i')); ?>

        </div>
    </div>

    
    <?php if(! empty($rapport['metrics'])): ?>
    <p class="section-title">Indicateurs clés</p>
    <div class="kpi-grid">
        <?php $__currentLoopData = $rapport['metrics']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $meta = $labels[$key] ?? ['label' => $key, 'type' => 'count']; ?>
            <div class="kpi-card <?php echo e($val > 0 ? 'highlight' : ''); ?>">
                <div class="kpi-label"><?php echo e($meta['label']); ?></div>
                <div class="kpi-value">
                    <?php if($meta['type'] === 'money'): ?>
                        <?php echo e(number_format($val, 0, ',', ' ')); ?>

                        <span class="kpi-unit">FCFA</span>
                    <?php else: ?>
                        <?php echo e($val); ?>

                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <p class="section-title">Détail des activités</p>
    <table>
        <thead>
            <tr>
                <th style="width:55%">Indicateur</th>
                <th class="text-right" style="width:25%">Valeur</th>
                <th class="text-right" style="width:20%">Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $rapport['metrics']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $meta = $labels[$key] ?? ['label' => $key, 'type' => 'count']; ?>
                <tr>
                    <td><?php echo e($meta['label']); ?></td>
                    <td class="text-right">
                        <?php if($meta['type'] === 'money'): ?>
                            <?php echo e(number_format($val, 0, ',', ' ')); ?> FCFA
                        <?php else: ?>
                            <?php echo e($val); ?>

                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?php if($val > 0): ?>
                            <span class="badge-active">Actif</span>
                        <?php else: ?>
                            <span class="badge-empty">Aucun</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>Total actes (activités non financières)</td>
                <td class="text-right"><?php echo e($rapport['total_actes']); ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <?php else: ?>
        <p style="color:#999;text-align:center;padding:32px 0">Aucune activité sur cette période.</p>
    <?php endif; ?>

    
    <div class="footer">
        <span>Document généré automatiquement — confidentiel</span>
        <span><?php echo e($user->name); ?> <?php echo e($user->prenom); ?> | Réf. rapport #<?php echo e($user->id); ?>-<?php echo e(now()->format('Ymd')); ?></span>
    </div>

</body>
</html><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/rapports/pdf.blade.php ENDPATH**/ ?>