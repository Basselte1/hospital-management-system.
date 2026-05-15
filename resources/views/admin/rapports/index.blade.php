{{-- admin/rapports/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Rapports d\'activité')

@section('content')
<div class="container-fluid px-3">

{{-- ══════════════════════════════════════════════════════════════
     EN-TÊTE + FILTRES  (zone fixe compacte)
══════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-chart-bar text-primary me-1"></i>Rapports d'activité
        </h5>
        <span class="text-muted" style="font-size:11px">Vue par utilisateur · filtrée par rôle et période</span>
    </div>
    <a href="{{ route('rapports.export', request()->all()) }}"
       class="btn btn-outline-secondary btn-sm py-1">
        <i class="fas fa-file-pdf me-1"></i>Export PDF
    </a>
</div>

{{-- ── Barre de filtres ────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm mb-3" style="background:#f8f9fa">
    <div class="card-body py-2 px-3">
        <form method="GET" action="{{ route('rapports.index') }}" id="filterForm"
              class="d-flex flex-wrap align-items-end gap-2">

            {{-- 1. Raccourcis Période (INDÉPENDANTS : remplissent Du/Au et soumettent) --}}
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Période</div>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn {{ $periode==='jour'    ? 'btn-primary' : 'btn-outline-secondary' }}"
                            onclick="setPeriode('jour')">Aujourd'hui</button>
                    <button type="button" class="btn {{ $periode==='semaine' ? 'btn-primary' : 'btn-outline-secondary' }}"
                            onclick="setPeriode('semaine')">Semaine</button>
                    <button type="button" class="btn {{ $periode==='mois'    ? 'btn-primary' : 'btn-outline-secondary' }}"
                            onclick="setPeriode('mois')">Mois</button>
                </div>
                <input type="hidden" name="periode" id="periodeInput" value="{{ $periode }}">
            </div>

            {{-- Séparateur visuel --}}
            <div class="vr d-none d-md-block mx-1" style="height:32px;align-self:flex-end"></div>

            {{-- 2. Du ──── --}}
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Du</div>
                <input type="date" name="date_debut" id="dateDebut"
                       class="form-control form-control-sm"
                       value="{{ $dateDebut }}" style="width:130px"
                       onchange="clearPeriode()">
            </div>

            {{-- 3. Au ──── --}}
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Au</div>
                <input type="date" name="date_fin" id="dateFin"
                       class="form-control form-control-sm"
                       value="{{ $dateFin }}" style="width:130px"
                       onchange="clearPeriode()">
            </div>

            {{-- 4. Rôle (déclenche rechargement du select Utilisateur) --}}
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Rôle</div>
                <select name="role_id" id="roleSelect"
                        class="form-select form-select-sm" style="min-width:145px"
                        onchange="filterUsersSelect(this.value)">
                    <option value="">— Tous les rôles —</option>
                    @foreach ($users->pluck('role_id')->unique()->sort() as $rid)
                        @php $rc = \App\Http\Controllers\RapportController::getRoleConfig($rid); @endphp
                        @if ($rc)
                            <option value="{{ $rid }}" {{ $roleId == $rid ? 'selected' : '' }}>
                                {{ $rc['label'] }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            {{-- 5. Utilisateur (chargé dynamiquement selon le rôle) --}}
            <div>
                <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Utilisateur</div>
                <select name="user_id" id="userSelect"
                        class="form-select form-select-sm" style="min-width:175px">
                    <option value="">— Tous —</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}"
                                data-role="{{ $u->role_id }}"
                                {{ $userId == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} {{ $u->prenom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Boutons --}}
            <div class="d-flex gap-1 align-self-end">
                <button type="submit" class="btn btn-primary btn-sm py-1 px-3">
                    <i class="fas fa-search me-1"></i>Filtrer
                </button>
                <a href="{{ route('rapports.index') }}"
                   class="btn btn-outline-secondary btn-sm py-1 px-2" title="Réinitialiser">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     RÉSUMÉ GLOBAL  (Admin seulement — ligne compacte)
══════════════════════════════════════════════════════════════ --}}
@if ($resumeGlobal)
<div class="d-flex flex-wrap gap-2 mb-3">
    @php
        $kpis = [
            ['label'=>'Consultations', 'val'=>$resumeGlobal['total_consultations'], 'icon'=>'fa-stethoscope','color'=>'primary'],
            ['label'=>'Visites',       'val'=>$resumeGlobal['total_visites'],       'icon'=>'fa-hospital-user','color'=>'success'],
            ['label'=>'Nvx patients',  'val'=>$resumeGlobal['total_patients'],      'icon'=>'fa-user-plus',  'color'=>'info'],
            ['label'=>'Facturé',       'val'=>number_format($resumeGlobal['total_facture'],   0,',',' ').' FCFA','icon'=>'fa-money-bill',   'color'=>'warning'],
            ['label'=>'Encaissé',      'val'=>number_format($resumeGlobal['total_encaisse'],  0,',',' ').' FCFA','icon'=>'fa-cash-register','color'=>'success'],
            ['label'=>'Reste',         'val'=>number_format($resumeGlobal['total_reste'],     0,',',' ').' FCFA','icon'=>'fa-clock',        'color'=>'danger'],
        ];
    @endphp
    @foreach ($kpis as $kpi)
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded"
         style="background:#fff;border:1px solid #e9ecef;font-size:12px;white-space:nowrap">
        <i class="fas {{ $kpi['icon'] }} text-{{ $kpi['color'] }}" style="font-size:11px"></i>
        <span class="text-muted">{{ $kpi['label'] }}</span>
        <strong>{{ $kpi['val'] }}</strong>
    </div>
    @endforeach
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════
     TABLEAU PRINCIPAL — un tableau par groupe de rôle
══════════════════════════════════════════════════════════════ --}}
@if ($rapportUsers->isEmpty())
    <div class="text-center py-4 text-muted small">
        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-30"></i>
        Aucune activité trouvée pour cette période.
    </div>
@else
    @foreach ($rapportUsers->groupBy(fn($r) => $r['user']->role_id) as $gRoleId => $groupe)
        @php $rc = \App\Http\Controllers\RapportController::getRoleConfig($gRoleId); @endphp
        @if (!$rc) @continue @endif

        {{-- En-tête du groupe --}}
        <div class="d-flex align-items-center gap-2 mb-1 mt-3">
            <i class="fas {{ $rc['icon'] }} text-{{ $rc['color'] }}" style="font-size:11px"></i>
            <span class="text-uppercase fw-bold text-muted" style="font-size:10px;letter-spacing:.6px">
                {{ $rc['label'] }}s
            </span>
            <span class="badge rounded-pill bg-{{ $rc['color'] }} bg-opacity-10 text-{{ $rc['color'] }}"
                  style="font-size:10px">{{ $groupe->count() }}</span>
        </div>

        {{-- Tableau compact --}}
        <div class="card border-0 shadow-sm mb-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0" style="font-size:12px">
                    <thead style="background:#f1f3f5">
                        <tr>
                            <th class="ps-3 py-2" style="width:200px">Utilisateur</th>
                            {{-- Colonnes métriques : prendre les métriques du 1er élément du groupe --}}
                            @php
                                $firstRapport  = $groupe->first();
                                $metricKeys    = array_keys($firstRapport['metrics']);
                                $metricLabels  = $firstRapport['labels'];
                            @endphp
                            @foreach ($metricKeys as $mk)
                                @php $ml = $metricLabels[$mk] ?? []; @endphp
                                <th class="text-center py-2" style="min-width:90px;max-width:110px">
                                    <i class="fas {{ $ml['icon'] ?? 'fa-circle' }} text-{{ $ml['color'] ?? 'secondary' }} me-1"
                                       style="font-size:10px"></i>
                                    <span title="{{ $ml['label'] ?? $mk }}" style="max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:inline-block;vertical-align:middle">
                                        {{-- Str::limit($ml['label'] ?? $mk, 14) --}}
                                        {{ mb_substr($ml['label'] ?? $mk, 0, 14) }}
                                    </span>
                                </th>
                            @endforeach
                            <th class="text-center py-2" style="width:75px">Total</th>
                            <th class="py-2" style="width:70px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupe as $rapport)
                        @php $u = $rapport['user']; @endphp
                        <tr>
                            {{-- Identité --}}
                            <td class="ps-3 py-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-{{ $rc['color'] }} bg-opacity-10"
                                         style="width:28px;height:28px;font-size:10px;font-weight:600;color:var(--bs-{{ $rc['color'] }})">
                                        {{ strtoupper(substr($u->name,0,1)) }}{{ strtoupper(substr($u->prenom??'',0,1)) }}
                                    </div>
                                    <div style="line-height:1.2">
                                        <div class="fw-semibold text-truncate" style="max-width:130px">{{ $u->name }} {{ $u->prenom }}</div>
                                        @if($u->specialite)
                                            <div class="text-muted" style="font-size:10px">{{-- Str::limit($u->specialite,18) --}}
                                                {{ mb_substr($u->specialite, 0, 18) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Valeurs métriques --}}
                            @foreach ($metricKeys as $mk)
                                @php
                                    $val  = $rapport['metrics'][$mk] ?? 0;
                                    $ml   = $metricLabels[$mk] ?? [];
                                    $isMoney = ($ml['type'] ?? 'count') === 'money';
                                @endphp
                                <td class="text-center py-2">
                                    @if ($val > 0)
                                        <span class="fw-semibold text-{{ $isMoney ? 'warning' : 'dark' }}">
                                            @if ($isMoney)
                                                {{ number_format($val,0,',',' ') }}
                                            @else
                                                {{ $val }}
                                            @endif
                                        </span>
                                        @if ($isMoney)
                                            <span class="text-muted" style="font-size:9px">FCFA</span>
                                        @endif
                                    @else
                                        <span class="text-muted opacity-40">—</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- Total actes --}}
                            <td class="text-center py-2">
                                <span class="badge rounded-pill
                                    {{ $rapport['total_actes'] > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted' }}"
                                    style="font-size:11px">
                                    {{ $rapport['total_actes'] }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="py-2 text-end pe-2">
                                <a href="{{ route('rapports.show', ['user'=>$u->id,'date_debut'=>$dateDebut,'date_fin'=>$dateFin]) }}"
                                   class="btn btn-outline-{{ $rc['color'] }} btn-sm py-0 px-2"
                                   style="font-size:11px" title="Voir le rapport complet">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endif

</div>{{-- /container-fluid --}}
@endsection

@push('scripts')
<script>
(function () {
    // ── Données utilisateurs injectées depuis Blade ───────────────────
    // Structure : [ {id, role_id, name}, ... ]
    const allUsers = @json($usersJson);
       

    const roleSelect = document.getElementById('roleSelect');
    const userSelect = document.getElementById('userSelect');
    const selectedUserId = {{ $userId ?? 'null' }};

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
@endpush