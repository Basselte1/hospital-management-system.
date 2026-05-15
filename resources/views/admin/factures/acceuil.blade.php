@extends('layouts.admin')
@section('title', 'CMCU | Facturation — Accueil')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

@include('admin.factures.partials._factures_common')
 
<div class="fc-page">
     
    {{-- ── En-tête ── --}}
    <div class="fc-page-header" style="margin-top:-20px;">
        
        <div class="fc-page-title">
            <div class="fc-title-icon" style="background:#E3F2FD; color:#1565C0;">
                <i class="fas fa-hospital-alt"></i>
            </div>
            Facturation
        </div>
        <div style="font-size:13px; color:var(--fc-text-muted);">
            <i class="fas fa-calendar-day me-1"></i>
            {{ now()->translatedFormat('l d F Y') }}
        </div>
    </div>

    {{-- ── Alertes session ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── KPIs du jour ── --}}
    <div class="fc-kpi-row">
        <div class="fc-kpi blue">
            <div class="fc-kpi-label">Recettes aujourd'hui</div>
            <div class="fc-kpi-value">{{ number_format($statsJour['total_recettes'] ?? 0, 0, ',', ' ') }}</div>
            <div class="fc-kpi-sub">FCFA encaissés</div>
        </div>
        <div class="fc-kpi teal">
            <div class="fc-kpi-label">Factures émises</div>
            <div class="fc-kpi-value">{{ $statsJour['total_factures'] ?? 0 }}</div>
            <div class="fc-kpi-sub">ce jour</div>
        </div>
        <div class="fc-kpi green">
            <div class="fc-kpi-label">Soldées</div>
            <div class="fc-kpi-value">{{ $statsJour['soldees'] ?? 0 }}</div>
            <div class="fc-kpi-sub">payées intégralement</div>
        </div>
        <div class="fc-kpi amber">
            <div class="fc-kpi-label">Non soldées</div>
            <div class="fc-kpi-value">{{ $statsJour['non_soldees'] ?? 0 }}</div>
            <div class="fc-kpi-sub">en attente</div>
        </div>
        <div class="fc-kpi red">
            <div class="fc-kpi-label">Reste à percevoir</div>
            <div class="fc-kpi-value">{{ number_format($statsJour['total_reste'] ?? 0, 0, ',', ' ') }}</div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi purple">
            <div class="fc-kpi-label">Part assurances</div>
            <div class="fc-kpi-value">{{ number_format($statsJour['total_assurance'] ?? 0, 0, ',', ' ') }}</div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
    </div>

    {{-- ── Navigation modules ── --}}
    <div style="margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--fc-text-muted); text-transform: uppercase; letter-spacing: .5px;">
        <i class="fas fa-th-large me-2"></i>Modules de facturation
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 24px;">

        {{-- Consultations --}}
        <a href="{{ route('factures.consultation') }}" class="fc-nav-module" style="text-decoration:none;">
            <div style="background:#fff; border:1px solid #CFD8DC; border-radius:12px; padding:20px 18px; transition:all .2s; cursor:pointer; border-top: 4px solid #1565C0;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                    <div style="width:42px; height:42px; background:#E3F2FD; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; color:#1565C0; flex-shrink:0;">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div>
                        <div style="font-size:15px; font-weight:700; color:#263238;">Consultations</div>
                        <div style="font-size:11px; color:#607D8B;">Factures médicales</div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:12px; color:#607D8B;">
                    <span><strong style="color:#1565C0;">{{ $statsJour['consultations_count'] ?? 0 }}</strong> aujourd'hui</span>
                    <span style="color:#1565C0;">Voir →</span>
                </div>
            </div>
        </a>

        {{-- Examens --}}
        <a href="{{ route('facturation.examens.index') }}" class="fc-nav-module" style="text-decoration:none;">
            <div style="background:#fff; border:1px solid #CFD8DC; border-radius:12px; padding:20px 18px; transition:all .2s; cursor:pointer; border-top: 4px solid #00695C;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                    <div style="width:42px; height:42px; background:#E0F2F1; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; color:#00695C; flex-shrink:0;">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <div>
                        <div style="font-size:15px; font-weight:700; color:#263238;">Examens</div>
                        <div style="font-size:11px; color:#607D8B;">Labo & imagerie</div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:12px; color:#607D8B;">
                    <span><strong style="color:#00695C;">{{ $statsJour['examens_count'] ?? 0 }}</strong> aujourd'hui</span>
                    <span style="color:#00695C;">Voir →</span>
                </div>
            </div>
        </a>

        {{-- Soins infirmiers --}}
        <a href="{{ route('facturation.actes.index') }}" class="fc-nav-module" style="text-decoration:none;">
            <div style="background:#fff; border:1px solid #CFD8DC; border-radius:12px; padding:20px 18px; transition:all .2s; cursor:pointer; border-top: 4px solid #F57F17;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                    <div style="width:42px; height:42px; background:#FFF8E1; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; color:#F57F17; flex-shrink:0;">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div>
                        <div style="font-size:15px; font-weight:700; color:#263238;">Soins infirmiers</div>
                        <div style="font-size:11px; color:#607D8B;">Actes paramédicaux</div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:12px; color:#607D8B;">
                    <span><strong style="color:#F57F17;">{{ $statsJour['soins_count'] ?? 0 }}</strong> aujourd'hui</span>
                    <span style="color:#F57F17;">Voir →</span>
                </div>
            </div>
        </a>

        {{-- Facture finale --}}
        <a href="{{ route('finale.index') }}" class="fc-nav-module" style="text-decoration:none;">
            <div style="background:#fff; border:1px solid #CFD8DC; border-radius:12px; padding:20px 18px; transition:all .2s; cursor:pointer; border-top: 4px solid #4527A0;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                    <div style="width:42px; height:42px; background:#EDE7F6; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; color:#4527A0; flex-shrink:0;">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <div style="font-size:15px; font-weight:700; color:#263238;">Facture finale</div>
                        <div style="font-size:11px; color:#607D8B;">Récapitulatif total</div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:12px; color:#607D8B;">
                    <span><strong style="color:#4527A0;">{{ $statsJour['finales_count'] ?? 0 }}</strong> générées</span>
                    <span style="color:#4527A0;">Voir →</span>
                </div>
            </div>
        </a>

    </div>

    {{-- ── Ventes récentes ── --}}
    <div class="fc-table-card">
        <div class="fc-table-card-header">
            <div class="fc-table-card-header-left">
                <i class="fas fa-history" style="color:var(--fc-blue);"></i>
                <span style="font-size:14px; font-weight:700;">Factures récentes</span>
                <span class="fc-badge fc-badge-info">Toutes catégories</span>
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
                <input type="text" id="searchRecent" class="form-control form-control-sm" placeholder="Rechercher..." style="width:180px; font-size:12px;">
            </div>
        </div>
        <div class="fc-table-responsive">
            <table class="fc-table" id="tableRecent">
                <thead>
                    <tr>
                        <th>N° Vente</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Patient</th>
                        <th>Montant</th>
                        <th>Mode paiement</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturesRecentes ?? [] as $facture)

                       @php
                                /*
                                 * Configuration par type de facture.
                                 * Le champ type_facture est ajouté dans le controller
                                 * via ->each(fn ($f) => $f->type_facture = '...').
                                 *
                                 * On définit ici :
                                 *  - label      : texte affiché dans le badge
                                 *  - couleurs   : classes Tailwind pour le badge
                                 *  - route_pdf  : route pour imprimer
                                 *  - route_view : route pour l'aperçu
                                 */
                                $typeConfig = [
                                    'consultation' => [
                                        'label'      => 'Consultation',
                                        'bg'         => 'bg-blue-100',
                                        'text'       => 'text-blue-700',
                                        'icon'       => 'fa-stethoscope',
                                        'route_pdf'  => 'factures.consultation_pdf',
                                        'route_view' => 'factures.apercu_consultation',
                                    ],
                                    'examen' => [
                                        'label'      => 'Examen',
                                        'bg'         => 'bg-teal-100',
                                        'text'       => 'text-teal-700',
                                        'icon'       => 'fa-microscope',
                                        'route_pdf'  => 'factures.examen_pdf',
                                        'route_view' => 'factures.apercu_examen',
                                    ],
                                    'acte' => [
                                        'label'      => 'Soin infirmier',
                                        'bg'         => 'bg-amber-100',
                                        'text'       => 'text-amber-700',
                                        'icon'       => 'fa-syringe',
                                        'route_pdf'  => 'factures.acte_pdf',
                                        'route_view' => 'factures.apercu_acte',
                                    ],
                                ];
 
                                $cfg      = $typeConfig[$facture->type_facture] ?? $typeConfig['consultation'];
                                $isSoldee = $facture->statut === 'Soldée';
 
                                // Nom patient : relation ou snapshot
                                $nomPatient   = $facture->patient
                                    ? trim(($facture->patient->name ?? '') . ' ' . ($facture->patient->prenom ?? ''))
                                    : ($facture->patient_name ?? '—');
                                $numeroDossier = $facture->patient->numero_dossier ?? null;
 
                                // Montant : le champ est aliasé en montant_affiche dans le controller
                                $montant = $facture->montant_affiche ?? 0;
                            @endphp
                    <tr class="{{ $facture->isSoldee() ? 'soldee' : '' }}">
                        <td><strong>{{ $facture->numero }}</strong></td>
                        <td style="white-space:nowrap; color:var(--fc-text-muted); font-size:12px;">
                            {{ $facture->created_at->format('d/m/Y H:i') }}
                        </td>
                       

                         <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                                        <i class="fas {{ $cfg['icon'] }} text-xs"></i>
                                        {{ $cfg['label'] }}
                                    </span>
                                </td>
                            <!--@php
                                $typeLabel = $facture->lignes->first()->type_acte ?? 'consultation';
                                $typeCfg = [
                                    'consultation'       => ['label'=>'Consultation', 'class'=>'fc-badge-info'],
                                    'consultation_suivi' => ['label'=>'Suivi',        'class'=>'fc-badge-teal'],
                                    'examen_labo'        => ['label'=>'Examen',        'class'=>'fc-badge-purple'],
                                    'soin_infirmier'     => ['label'=>'Soin inf.',     'class'=>'fc-badge-warning'],
                                    'chambre'            => ['label'=>'Chambre',       'class'=>'fc-badge-gray'],
                                ];
                                $cfg = $typeCfg[$typeLabel] ?? ['label'=>ucfirst($typeLabel), 'class'=>'fc-badge-gray'];
                            @endphp
                            <span class="fc-badge {{ $cfg['class'] }}">{{ $cfg['label'] }}</span>
                        </td-->
                        <td>
                            <div style="font-weight:500;">{{ $facture->patient_display_name }}</div>
                            @if($facture->patient)
                                <div style="font-size:11px; color:var(--fc-text-muted);">N°{{ $facture->patient->numero_dossier }}</div>
                            @endif
                        </td>
                        <td style="font-weight:600;">{{ number_format($montant, 0, ',', ' ') }} <span style="font-size:11px;color:var(--fc-text-muted);">FCFA</span></td>
                        <td>
                            <span class="fc-badge fc-badge-gray">
                                <i class="fas fa-money-bill-wave" style="font-size:10px;"></i>
                                {{ ucfirst($facture->mode_paiement ?? 'espèce') }}
                            </span>
                        </td>
                        <td>
                            @if($facture->isSoldee())
                                <div><span class="fc-badge fc-badge-success"><i class="fas fa-check-circle"></i> Soldée</span></div>
                                @if($facture->is_printed)
                                    <div style="font-size:11px; color:var(--fc-text-muted); margin-top:2px;">
                                        <i class="fas fa-print"></i> Imprimée le {{ $facture->printed_at?->format('d/m/Y') }}
                                    </div>
                                @endif
                            @else
                                <div><span class="fc-badge fc-badge-warning"><i class="fas fa-clock"></i> Non soldée</span></div>
                                <div style="font-size:11px; color:#C62828; margin-top:2px;">
                                    <i class="fas fa-file-invoice"></i> Proforma
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fc-actions">
                                @if($facture->isProforma())
                                    <a href="{{ route('factures.consultation_pdf', $facture->id) }}"
                                       class="fc-action-btn print-pf" title="Imprimer PROFORMA">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                @else
                                    <a href="{{ route('factures.consultation_pdf', $facture->id) }}"
                                       class="fc-action-btn print-ok" title="Imprimer facture">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @endif
                                <a href="{{ route('factures.apercu_consultation', $facture->id) }}"
                                   class="fc-action-btn view" title="Aperçu">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:30px; color:var(--fc-text-muted);">
                            <i class="fas fa-inbox" style="font-size:28px; display:block; margin-bottom:8px; opacity:.4;"></i>
                            Aucune facture récente
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

      

    {{-- ── Bilan journalier ── --}}
    <!--div class="fc-bilan-section">
        <div class="fc-bilan-title">
            <i class="fas fa-chart-pie" style="color:var(--fc-blue);"></i>
            Générer un bilan journalier
        </div>
<form action="{{ route('facturation.dashboard.bilan_pdf') }}" method="POST" class="d-flex align-items-end flex-wrap gap-3">
            @csrf
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Date</span>
                <select name="day" class="form-select" style="min-width:200px; height:36px; font-size:13px;">
                    @foreach($lists ?? [] as $date)
                        <option value="{{ $date }}" {{ $date === now()->format('Y-m-d') ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Service</span>
                <select name="service" class="form-select" style="min-width:180px; height:36px; font-size:13px;">
                    <option value="">Tous les services</option>
                    <option value="consultation">Consultations</option>
                    <option value="examen">Examens</option>
                    <option value="soins">Soins infirmiers</option>
                </select>
            </div>
            <button type="submit" class="fc-btn fc-btn-primary">
                <i class="fas fa-file-pdf"></i> Imprimer le bilan
            </button>
        </form>
    </div-->

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Recherche live dans le tableau récent
    const searchInput = document.getElementById('searchRecent');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const val = this.value.toLowerCase();
            document.querySelectorAll('#tableRecent tbody tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
            });
        });
    }

    // Hover effect sur les cartes modules
    document.querySelectorAll('.fc-nav-module > div').forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 16px rgba(0,0,0,.12)';
        });
        card.addEventListener('mouseleave', function () {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});
</script>
@endpush

@endsection