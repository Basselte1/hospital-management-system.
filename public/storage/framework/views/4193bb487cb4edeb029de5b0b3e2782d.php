

<?php $__env->startSection('title', 'Rapports d\'activité'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3">


<div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-chart-bar text-primary me-1"></i>Rapports d'activité
        </h5>
        <span class="text-muted" style="font-size:11px">Vue par utilisateur · filtrée par rôle et période</span>
    </div>
    <a href="<?php echo e(route('rapports.export', request()->all())); ?>"
       class="btn btn-outline-secondary btn-sm py-1">
        <i class="fas fa-file-pdf me-1"></i>Export PDF
    </a>
</div>


<div class="card border-0 shadow-sm mb-3" style="background:#f8f9fa">
    <div class="card-body py-2 px-3">
        <form method="GET" action="<?php echo e(route('rapports.index')); ?>" id="filterForm"
              class="d-flex flex-wrap align-items-end gap-2">

            
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Période</div>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn <?php echo e($periode==='jour'    ? 'btn-primary' : 'btn-outline-secondary'); ?>"
                            onclick="setPeriode('jour')">Aujourd'hui</button>
                    <button type="button" class="btn <?php echo e($periode==='semaine' ? 'btn-primary' : 'btn-outline-secondary'); ?>"
                            onclick="setPeriode('semaine')">Semaine</button>
                    <button type="button" class="btn <?php echo e($periode==='mois'    ? 'btn-primary' : 'btn-outline-secondary'); ?>"
                            onclick="setPeriode('mois')">Mois</button>
                </div>
                <input type="hidden" name="periode" id="periodeInput" value="<?php echo e($periode); ?>">
            </div>

            
            <div class="vr d-none d-md-block mx-1" style="height:32px;align-self:flex-end"></div>

            
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Du</div>
                <input type="date" name="date_debut" id="dateDebut"
                       class="form-control form-control-sm"
                       value="<?php echo e($dateDebut); ?>" style="width:130px"
                       onchange="clearPeriode()">
            </div>

            
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Au</div>
                <input type="date" name="date_fin" id="dateFin"
                       class="form-control form-control-sm"
                       value="<?php echo e($dateFin); ?>" style="width:130px"
                       onchange="clearPeriode()">
            </div>

            
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Rôle</div>
                <select name="role_id" id="roleSelect"
                        class="form-select form-select-sm" style="min-width:145px"
                        onchange="filterUsersSelect(this.value)">
                    <option value="">— Tous les rôles —</option>
                    <?php $__currentLoopData = $users->pluck('role_id')->unique()->sort(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $rc = \App\Http\Controllers\RapportController::getRoleConfig($rid); ?>
                        <?php if($rc): ?>
                            <option value="<?php echo e($rid); ?>" <?php echo e($roleId == $rid ? 'selected' : ''); ?>>
                                <?php echo e($rc['label']); ?>

                            </option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Utilisateur</div>
                <select name="user_id" id="userSelect"
                        class="form-select form-select-sm" style="min-width:175px">
                    <option value="">— Tous —</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>"
                                data-role="<?php echo e($u->role_id); ?>"
                                <?php echo e($userId == $u->id ? 'selected' : ''); ?>>
                            <?php echo e($u->name); ?> <?php echo e($u->prenom); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="d-flex gap-1 align-self-end">
                <button type="submit" class="btn btn-primary btn-sm py-1 px-3">
                    <i class="fas fa-search me-1"></i>Filtrer
                </button>
                <a href="<?php echo e(route('rapports.index')); ?>"
                   class="btn btn-outline-secondary btn-sm py-1 px-2" title="Réinitialiser">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>
</div>


<?php if($resumeGlobal): ?>
<div class="d-flex flex-wrap gap-2 mb-3">
    <?php
        $kpis = [
            ['label'=>'Consultations', 'val'=>$resumeGlobal['total_consultations'], 'icon'=>'fa-stethoscope','color'=>'primary'],
            ['label'=>'Visites',       'val'=>$resumeGlobal['total_visites'],       'icon'=>'fa-hospital-user','color'=>'success'],
            ['label'=>'Nvx patients',  'val'=>$resumeGlobal['total_patients'],      'icon'=>'fa-user-plus',  'color'=>'info'],
            ['label'=>'Facturé',       'val'=>number_format($resumeGlobal['total_facture'],   0,',',' ').' FCFA','icon'=>'fa-money-bill',   'color'=>'warning'],
            ['label'=>'Encaissé',      'val'=>number_format($resumeGlobal['total_encaisse'],  0,',',' ').' FCFA','icon'=>'fa-cash-register','color'=>'success'],
            ['label'=>'Reste',         'val'=>number_format($resumeGlobal['total_reste'],     0,',',' ').' FCFA','icon'=>'fa-clock',        'color'=>'danger'],
        ];
    ?>
    <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded"
         style="background:#fff;border:1px solid #e9ecef;font-size:12px;white-space:nowrap">
        <i class="fas <?php echo e($kpi['icon']); ?> text-<?php echo e($kpi['color']); ?>" style="font-size:11px"></i>
        <span class="text-muted"><?php echo e($kpi['label']); ?></span>
        <strong><?php echo e($kpi['val']); ?></strong>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<?php if($rapportUsers->isEmpty()): ?>
    <div class="text-center py-4 text-muted small">
        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-30"></i>
        Aucune activité trouvée pour cette période.
    </div>
<?php else: ?>
    <?php $__currentLoopData = $rapportUsers->groupBy(fn($r) => $r['user']->role_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gRoleId => $groupe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $rc = \App\Http\Controllers\RapportController::getRoleConfig($gRoleId); ?>
        <?php if(!$rc): ?> <?php continue; ?> <?php endif; ?>

        
        <div class="d-flex align-items-center gap-2 mb-1 mt-3">
            <i class="fas <?php echo e($rc['icon']); ?> text-<?php echo e($rc['color']); ?>" style="font-size:11px"></i>
            <span class="text-uppercase fw-bold text-muted" style="font-size:10px;letter-spacing:.6px">
                <?php echo e($rc['label']); ?>s
            </span>
            <span class="badge rounded-pill bg-<?php echo e($rc['color']); ?> bg-opacity-10 text-<?php echo e($rc['color']); ?>"
                  style="font-size:10px"><?php echo e($groupe->count()); ?></span>
        </div>

        
        <div class="card border-0 shadow-sm mb-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0" style="font-size:12px">
                    <thead style="background:#f1f3f5">
                        <tr>
                            <th class="ps-3 py-2" style="width:200px">Utilisateur</th>
                            
                            <?php
                                $firstRapport  = $groupe->first();
                                $metricKeys    = array_keys($firstRapport['metrics']);
                                $metricLabels  = $firstRapport['labels'];
                            ?>
                            <?php $__currentLoopData = $metricKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $ml = $metricLabels[$mk] ?? []; ?>
                                <th class="text-center py-2" style="min-width:90px;max-width:110px">
                                    <i class="fas <?php echo e($ml['icon'] ?? 'fa-circle'); ?> text-<?php echo e($ml['color'] ?? 'secondary'); ?> me-1"
                                       style="font-size:10px"></i>
                                    <span title="<?php echo e($ml['label'] ?? $mk); ?>" style="max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:inline-block;vertical-align:middle">
                                        
                                        <?php echo e(mb_substr($ml['label'] ?? $mk, 0, 14)); ?>

                                    </span>
                                </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <th class="text-center py-2" style="width:75px">Total</th>
                            <th class="py-2" style="width:70px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $groupe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rapport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $u = $rapport['user']; ?>
                        <tr>
                            
                            <td class="ps-3 py-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-<?php echo e($rc['color']); ?> bg-opacity-10"
                                         style="width:28px;height:28px;font-size:10px;font-weight:600;color:var(--bs-<?php echo e($rc['color']); ?>)">
                                        <?php echo e(strtoupper(substr($u->name,0,1))); ?><?php echo e(strtoupper(substr($u->prenom??'',0,1))); ?>

                                    </div>
                                    <div style="line-height:1.2">
                                        <div class="fw-semibold text-truncate" style="max-width:130px"><?php echo e($u->name); ?> <?php echo e($u->prenom); ?></div>
                                        <?php if($u->specialite): ?>
                                            <div class="text-muted" style="font-size:10px">
                                                <?php echo e(mb_substr($u->specialite, 0, 18)); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            
                            <?php $__currentLoopData = $metricKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $val  = $rapport['metrics'][$mk] ?? 0;
                                    $ml   = $metricLabels[$mk] ?? [];
                                    $isMoney = ($ml['type'] ?? 'count') === 'money';
                                ?>
                                <td class="text-center py-2">
                                    <?php if($val > 0): ?>
                                        <span class="fw-semibold text-<?php echo e($isMoney ? 'warning' : 'dark'); ?>">
                                            <?php if($isMoney): ?>
                                                <?php echo e(number_format($val,0,',',' ')); ?>

                                            <?php else: ?>
                                                <?php echo e($val); ?>

                                            <?php endif; ?>
                                        </span>
                                        <?php if($isMoney): ?>
                                            <span class="text-muted" style="font-size:9px">FCFA</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted opacity-40">—</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <td class="text-center py-2">
                                <span class="badge rounded-pill
                                    <?php echo e($rapport['total_actes'] > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted'); ?>"
                                    style="font-size:11px">
                                    <?php echo e($rapport['total_actes']); ?>

                                </span>
                            </td>

                            
                            <td class="py-2 text-end pe-2">
                                <a href="<?php echo e(route('rapports.show', ['user'=>$u->id,'date_debut'=>$dateDebut,'date_fin'=>$dateFin])); ?>"
                                   class="btn btn-outline-<?php echo e($rc['color']); ?> btn-sm py-0 px-2"
                                   style="font-size:11px" title="Voir le rapport complet">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    // ── Données utilisateurs injectées depuis Blade ───────────────────
    // Structure : [ {id, role_id, name}, ... ]
    const allUsers = <?php echo json_encode($usersJson, 15, 512) ?>;
       

    const roleSelect = document.getElementById('roleSelect');
    const userSelect = document.getElementById('userSelect');
    const selectedUserId = <?php echo e($userId ?? 'null'); ?>;

    // ── Filtre le select Utilisateur selon le rôle choisi ────────────
    window.filterUsersSelect = function (roleId) {
        const current = userSelect.value;
        userSelect.innerHTML = '<option value="">— Tous —</option>';

        const filtered = roleId
            ? allUsers.filter(u => String(u.role_id) === String(roleId))
            : allUsers;

        filtered.forEach(u => {
            const opt = document.createElement('option');
            opt.value = u.id;
            opt.textContent = u.label;
            if (String(u.id) === String(current)) opt.selected = true;
            userSelect.appendChild(opt);
        });
    };

    // Appliquer au chargement si un rôle est déjà sélectionné
    if (roleSelect.value) {
        filterUsersSelect(roleSelect.value);
        // Re-sélectionner l'utilisateur courant si présent
        if (selectedUserId) userSelect.value = selectedUserId;
    }

    // ── Période → remplit Du/Au puis soumet ──────────────────────────
    window.setPeriode = function (p) {
        const today = new Date();
        const fmt   = d => d.toISOString().slice(0, 10);

        let debut = fmt(today);
        let fin   = fmt(today);

        if (p === 'semaine') {
            const day  = today.getDay() === 0 ? 6 : today.getDay() - 1; // lundi = 0
            const mon  = new Date(today); mon.setDate(today.getDate() - day);
            debut = fmt(mon);
        } else if (p === 'mois') {
            debut = fmt(new Date(today.getFullYear(), today.getMonth(), 1));
        }

        document.getElementById('dateDebut').value  = debut;
        document.getElementById('dateFin').value    = fin;
        document.getElementById('periodeInput').value = p;

        // Soumettre directement
        document.getElementById('filterForm').submit();
    };

    // ── Si l'utilisateur change Du/Au manuellement → efface Période ──
    window.clearPeriode = function () {
        document.getElementById('periodeInput').value = 'libre';
        // Désélectionner les boutons visuellement
        document.querySelectorAll('.btn-group .btn-primary').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-secondary');
        });
    };
})();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/rapports/index.blade.php ENDPATH**/ ?>