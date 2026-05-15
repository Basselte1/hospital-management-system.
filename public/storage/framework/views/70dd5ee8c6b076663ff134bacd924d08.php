

<?php $__env->startSection('title', 'Rapport — ' . $user->name . ' ' . $user->prenom); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3">


<?php
    $color   = $config['color'] ?? 'secondary';
    $icon    = $config['icon']  ?? 'fa-user';
    $initials = strtoupper(substr($user->name,0,1)) . strtoupper(substr($user->prenom??'',0,1));
    $debutFmt = \Carbon\Carbon::parse($dateDebut)->format('d/m/Y');
    $finFmt   = \Carbon\Carbon::parse($dateFin)->format('d/m/Y');
?>

<div class="d-flex align-items-center gap-3 mb-3 flex-wrap">

    
    <div class="rounded-circle bg-<?php echo e($color); ?> bg-opacity-15 d-flex align-items-center justify-content-center flex-shrink-0"
         style="width:44px;height:44px;font-size:15px;font-weight:700;color:var(--bs-<?php echo e($color); ?>)">
        <?php echo e($initials); ?>

    </div>

    
    <div style="line-height:1.3">
        <div class="fw-semibold" style="font-size:15px">
            <?php echo e($user->name); ?> <?php echo e($user->prenom); ?>

            <span class="badge bg-<?php echo e($color); ?> bg-opacity-10 text-<?php echo e($color); ?> ms-1" style="font-size:11px">
                <i class="fas <?php echo e($icon); ?> me-1"></i><?php echo e($config['label'] ?? 'Utilisateur'); ?>

            </span>
        </div>
        <?php if($user->specialite): ?>
            <div class="text-muted" style="font-size:11px"><?php echo e($user->specialite); ?></div>
        <?php endif; ?>
        <div class="text-muted" style="font-size:11px">
            <i class="fas fa-calendar me-1"></i>Du <strong><?php echo e($debutFmt); ?></strong> au <strong><?php echo e($finFmt); ?></strong>
        </div>
    </div>

    
    <div class="ms-auto d-flex align-items-center gap-2 flex-wrap">
        <form method="GET" action="<?php echo e(route('rapports.show', $user)); ?>"
              class="d-flex align-items-center gap-1">
            <input type="date" name="date_debut" class="form-control form-control-sm"
                   value="<?php echo e($dateDebut); ?>" style="width:125px">
            <span class="text-muted small">→</span>
            <input type="date" name="date_fin" class="form-control form-control-sm"
                   value="<?php echo e($dateFin); ?>" style="width:125px">
            <button class="btn btn-primary btn-sm px-2" title="Filtrer">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <a href="<?php echo e(route('rapports.show.pdf', ['user'=>$user->id,'date_debut'=>$dateDebut,'date_fin'=>$dateFin])); ?>"
           class="btn btn-outline-danger btn-sm" target="_blank">
            <i class="fas fa-print me-1"></i>Imprimer
        </a>
        <a href="<?php echo e(route('rapports.index', ['date_debut'=>$dateDebut,'date_fin'=>$dateFin])); ?>"
           class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Retour
        </a>
    </div>
</div>


<?php if(! empty($rapport['metrics'])): ?>
<div class="row g-2 mb-3">
    <?php $__currentLoopData = $rapport['metrics']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $meta = $labels[$key] ?? ['label'=>$key,'icon'=>'fa-circle','type'=>'count','color'=>'secondary']; ?>
        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
            <div class="card border-0 h-100"
                 style="border-left:3px solid var(--bs-<?php echo e($meta['color']); ?>) !important;background:#f8f9fa">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center gap-1 mb-1">
                        <i class="fas <?php echo e($meta['icon']); ?> text-<?php echo e($meta['color']); ?>" style="font-size:10px"></i>
                        <span class="text-muted text-truncate" style="font-size:10px;max-width:100%"
                              title="<?php echo e($meta['label']); ?>"><?php echo e($meta['label']); ?></span>
                    </div>
                    <div class="fw-bold <?php echo e($val > 0 ? '' : 'text-muted'); ?>" style="font-size:18px;line-height:1">
                        <?php if($meta['type'] === 'money'): ?>
                            <?php echo e(number_format($val,0,',',' ')); ?>

                            <span style="font-size:9px;font-weight:400" class="text-muted">FCFA</span>
                        <?php else: ?>
                            <?php echo e($val); ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php else: ?>
    <div class="alert alert-info py-2 mb-3" style="font-size:13px">
        <i class="fas fa-info-circle me-1"></i>Aucune activité enregistrée pour cette période.
    </div>
<?php endif; ?>


<?php if(! empty($evolution)): ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="fw-semibold text-muted" style="font-size:12px">
                <i class="fas fa-chart-bar me-1 text-<?php echo e($color); ?>"></i>Évolution journalière
            </span>
        </div>
        <canvas id="evolutionChart" height="55"></canvas>
    </div>
</div>
<?php endif; ?>


<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-2 px-3 d-flex justify-content-between align-items-center">
        <span class="fw-semibold" style="font-size:13px">
            <i class="fas fa-table me-1"></i>Récapitulatif détaillé
        </span>
        <span class="text-muted" style="font-size:11px">
            Total activité : <strong><?php echo e($rapport['total_actes']); ?> actes</strong>
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0" style="font-size:12px">
            <thead style="background:#f1f3f5">
                <tr>
                    <th class="ps-3 py-2">Indicateur</th>
                    <th class="text-end py-2" style="width:130px">Valeur</th>
                    <th class="text-end pe-3 py-2" style="width:80px">Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $rapport['metrics']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $meta = $labels[$key] ?? ['label'=>$key,'type'=>'count','icon'=>'fa-circle','color'=>'secondary']; ?>
                    <tr>
                        <td class="ps-3 py-2">
                            <i class="fas <?php echo e($meta['icon']); ?> text-<?php echo e($meta['color']); ?> me-2" style="width:14px;font-size:11px"></i>
                            <?php echo e($meta['label']); ?>

                        </td>
                        <td class="text-end py-2 fw-semibold">
                            <?php if($meta['type'] === 'money'): ?>
                                <?php echo e(number_format($val,0,',',' ')); ?>

                                <span class="text-muted fw-normal" style="font-size:10px">FCFA</span>
                            <?php else: ?>
                                <?php echo e($val); ?>

                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-3 py-2">
                            <?php if($val > 0): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill" style="font-size:10px">Actif</span>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:11px">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold" style="background:#eef2ff;font-size:12px">
                    <td class="ps-3 py-2">Total actes (non financier)</td>
                    <td class="text-end py-2"><?php echo e($rapport['total_actes']); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if(! empty($evolution)): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {
    const jours = <?php echo json_encode(array_column($evolution, 'jour'), 512) ?>;
    const vals  = <?php echo json_encode(array_column($evolution, 'total'), 512) ?>;
    const ctx   = document.getElementById('evolutionChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: jours.map(d => { const p = d.split('-'); return p[2]+'/'+p[1]; }),
            datasets: [{
                label: 'Activité',
                data: vals,
                backgroundColor: 'rgba(13,110,253,.12)',
                borderColor: 'rgba(13,110,253,.75)',
                borderWidth: 1.5,
                borderRadius: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero:true, ticks:{ stepSize:1, font:{size:10} }, grid:{ color:'rgba(0,0,0,.04)' } },
                x: { grid:{ display:false }, ticks:{ font:{size:10} } }
            }
        }
    });
})();
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/rapports/show.blade.php ENDPATH**/ ?>