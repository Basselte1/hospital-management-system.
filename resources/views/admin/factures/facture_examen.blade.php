@extends('layouts.admin')
@section('title', 'CMCU | Factures Examens')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

@include('admin.factures.partials._factures_common')



@include('admin.factures.partials.create_facture_examen')
<div class="fc-page">

    {{-- ── En-tête ── --}}
    <div class="fc-page-header">
        <div class="fc-page-title">
            <div class="fc-title-icon" style="background:#E0F2F1; color:#00695C;">
                <i class="fas fa-microscope"></i>
            </div>
            Factures d'Examens
        </div>
        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">

            @can('print', \App\Models\Patient::class)
         
            <button type="button"
                    onclick="ouvrirModalFactureExamen()"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white tw-text-[#1D4ED8] tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 hover:tw-bg-[#BFDBFE] tw-transition-colors tw-shrink-0 tw-border-0 tw-cursor-pointer">
                <i class="fas fa-plus-circle tw-text-xs"></i> Ajouter un examen
            </button>
            @endcan

            <a href="{{ route('facturation.dashboard') }}" class="fc-btn fc-btn-light">
                <i class="fas fa-arrow-left"></i> Accueil
            </a>
        </div>
    </div>

    {{-- ── Alertes ── --}}
    @if(session('success'))
       
        <div class="tw-flex tw-items-center tw-gap-2 tw-rounded-lg tw-bg-green-50 tw-border tw-border-green-200 tw-px-4 tw-py-3 tw-mb-3 tw-text-green-800 tw-text-sm" role="alert" id="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" onclick="this.parentElement.remove()" class="tw-ml-auto tw-text-green-600 hover:tw-text-green-800 tw-border-0 tw-bg-transparent tw-cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="tw-flex tw-items-center tw-gap-2 tw-rounded-lg tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-3 tw-text-red-800 tw-text-sm" role="alert" id="alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('error') }}
            <button type="button" onclick="this.parentElement.remove()" class="tw-ml-auto tw-text-red-600 hover:tw-text-red-800 tw-border-0 tw-bg-transparent tw-cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- ── KPIs ── --}}
    @if(isset($factureExamens))
    <div class="fc-kpi-row">
        <div class="fc-kpi teal">
            <div class="fc-kpi-label">Total examens</div>
            <div class="fc-kpi-value">{{ $factureExamens->total() }}</div>
            <div class="fc-kpi-sub">sur la période</div>
        </div>
        <div class="fc-kpi blue">
            <div class="fc-kpi-label">Montant total</div>
            <div class="fc-kpi-value">{{ number_format($factureExamens->sum('montant_total'), 0, ',', ' ') }}</div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi green">
            <div class="fc-kpi-label">Encaissé</div>
            <div class="fc-kpi-value">{{ number_format($factureExamens->sum('avance'), 0, ',', ' ') }}</div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi red">
            <div class="fc-kpi-label">Reste</div>
            <div class="fc-kpi-value">{{ number_format($factureExamens->sum('reste'), 0, ',', ' ') }}</div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
        <div class="fc-kpi amber">
            <div class="fc-kpi-label">Part assurances</div>
            <div class="fc-kpi-value">{{ number_format($factureExamens->sum('assurancec'), 0, ',', ' ') }}</div>
            <div class="fc-kpi-sub">FCFA</div>
        </div>
    </div>
    @endif

    {{-- ── Toolbar filtres ── --}}
    <form action="{{ route('facturation.examens.index') }}" method="POST">
        @csrf
        <div class="fc-toolbar">
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Date de début</span>
                <input type="date" name="start-date" class="form-control"
                       value="{{ request('start-date', $startDate->format('Y-m-d')) }}" style="width:160px;">
            </div>
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Date de fin</span>
                <input type="date" name="end-date" class="form-control"
                       value="{{ request('end-date', $endDate->format('Y-m-d')) }}" style="width:160px;">
            </div>
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Type d'examen</span>
                <select name="type_acte" class="form-select" style="width:160px;">
                    <option value="">Tous types</option>
                    <option value="Hématologie"    {{ request('type_acte') === 'Hématologie'    ? 'selected' : '' }}>Hématologie</option>
                    <option value="Biochimie"      {{ request('type_acte') === 'Biochimie'      ? 'selected' : '' }}>Biochimie</option>
                    <option value="Hémostase"      {{ request('type_acte') === 'Hémostase'      ? 'selected' : '' }}>Hémostase</option>
                    <option value="Hormonologie"   {{ request('type_acte') === 'Hormonologie'   ? 'selected' : '' }}>Hormonologie</option>
                    <option value="Bactériologie"  {{ request('type_acte') === 'Bactériologie'  ? 'selected' : '' }}>Bactériologie</option>
                    <option value="Immuno-Sérologie" {{ request('type_acte') === 'Immuno-Sérologie' ? 'selected' : '' }}>Sérologie</option>
                </select>
            </div>
            <div class="fc-toolbar-sep"></div>
            <button type="submit" class="fc-btn fc-btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
            <a href="{{ route('facturation.examens.index') }}" class="fc-btn fc-btn-light">
                <i class="fas fa-undo"></i> Réinitialiser
            </a>
        </div>
    </form>

    {{-- ── Tableau ── --}}
    @if(isset($factureExamens))

    <div class="fc-proforma-info">
        <i class="fas fa-info-circle"></i>
        Les factures avec un reste &gt; 0 sont des <strong>PROFORMAS</strong>. Elles deviennent finales une fois soldées.
        Vous pouvez ajouter plusieurs examens à une même facture avec le bouton <strong>+</strong>.
    </div>

    <div class="fc-table-card">
        <div class="fc-table-card-header">
            <div class="fc-table-card-header-left">
                <i class="fas fa-flask" style="color:var(--fc-teal);"></i>
                <span style="font-size:14px; font-weight:700;">
                    Période du <strong>{{ $startDate->format('d/m/Y') }}</strong> au <strong>{{ $endDate->format('d/m/Y') }}</strong>
                </span>
                <span class="fc-badge fc-badge-teal">{{ $factureExamens->total() }} facture(s)</span>
            </div>
            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <span style="font-size:11px; color:var(--fc-text-muted); font-style:italic;">Montants en FCFA</span>
                <select id="statutFilterExamen" class="form-select form-select-sm" style="width:150px; font-size:12px;">
                    <option value="">Tous les statuts</option>
                    <option value="soldee">Soldées</option>
                    <option value="proforma">Non soldées</option>
                </select>
                <input type="text" id="searchExamen" class="form-control form-control-sm"
                       placeholder="Rechercher patient..." style="width:180px; font-size:12px;">
            </div>
        </div>

        <div class="fc-table-responsive">
            <table class="fc-table" id="tableExamen">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Actions</th>
                        <th>Patient</th>
                        <th>Examens réalisés</th>
                        <th>Montant</th>
                        <th>Part Ass.</th>
                        <th>Part Patient</th>
                        <th>Avancé</th>
                        <th>Reste</th>
                        <th>Mode paiement</th>
                        <th>Médecin</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factureExamens as $facture)
                    <tr class="{{ $facture->isSoldee() ? 'soldee' : '' }}"
                        data-statut="{{ $facture->isSoldee() ? 'soldee' : 'proforma' }}">
                        <td><strong>{{ $facture->numero }}</strong></td>

                        {{-- Actions --}}
                        <td>
                            <div class="fc-actions">
                                @if($facture->isProforma())
                                    <a href="{{ route('facturation.examen_pdf', $facture->id) }}"
                                       class="fc-action-btn print-pf"
                                       title="Imprimer PROFORMA — reste: {{ number_format($facture->reste,0,',',' ') }} FCFA">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    @can('delete', $facture)
                                    <form action="{{ route('facturation.examens.destroy', $facture->id) }}"
                                          method="POST" style="display:inline;"
                                          onsubmit="return confirm('Supprimer cette facture ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="fc-action-btn delete"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan

                                @else
                                    <a href="{{ route('facturation.examen_pdf', $facture->id) }}"
                                       class="fc-action-btn print-ok" title="Imprimer la facture soldée">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @endif

                                {{-- Aperçu --}}
                                <a href="{{ route('facturation.factures.apercu_examen', $facture->id) }}"
                                   class="fc-action-btn view" title="Aperçu">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Modifier (si non soldée) --}}
                                @can('update', $facture)
                                @if($facture->isModifiable())
                                    <button type="button"
                                            class="fc-action-btn edit btn-open-modal-edit-examen"
                                            title="Modifier le paiement"
                                            data-id-facture="{{ $facture->id }}"
                                            data-mode_paiement="{{ $facture->mode_paiement }}"
                                            data-montant="{{ $facture->montant_total }}"
                                            data-reste="{{ $facture->reste }}"
                                            data-prise_en_charge="{{ $facture->patient->prise_en_charge ?? 0 }}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                @endif
                                @endcan

                            </div>
                        </td>

                        <td>
                            <div style="font-weight:500;">{{ $facture->patient_display_name }}</div>
                            @if($facture->patient)
                                <div style="font-size:11px; color:var(--fc-text-muted);">N°{{ $facture->patient->numero_dossier }}</div>
                            @endif
                        </td>
                        <td>
                            @foreach($facture->lignes as $ligne)
                                <div style="display:flex; align-items:center; gap:5px; margin-bottom:3px;">
                                    <span class="fc-badge fc-badge-gray" style="font-size:10px;">{{ $ligne->type_sous }}</span>
                                    {{ substr($ligne->libelle, 0, 35) }}{{ strlen($ligne->libelle) > 35 ? '...' : '' }}
                                </div>
                            @endforeach
                        </td>
                        <td style="font-weight:600; text-align:right;">
                            {{ number_format($facture->montant_total, 0, ',', ' ') }}
                        </td>
                        <td style="text-align:right; color:var(--fc-teal);">{{ number_format($facture->assurancec, 0, ',', ' ') }}</td>
                        <td style="text-align:right;">{{ number_format($facture->assurec, 0, ',', ' ') }}</td>
                        <td style="text-align:right; color:var(--fc-green); font-weight:600;">
                            {{ number_format($facture->avance, 0, ',', ' ') }}
                        </td>
                        <td style="text-align:right;">
                            <span class="{{ $facture->reste == 0 ? 'fc-reste-zero' : 'fc-reste-nonzero' }}">
                                {{ number_format($facture->reste, 0, ',', ' ') }}
                            </span>
                        </td>
                        <td><span class="fc-badge fc-badge-gray">{{ ucfirst($facture->mode_paiement ?? 'espèce') }}</span></td>
                        <td style="font-size:12px;">
                            @foreach($facture->lignes as $ligne)
                                {{ $ligne->technicien ?? '—' }}
                            @endforeach
                        </td>
                        <td style="white-space:nowrap; font-size:12px; color:var(--fc-text-muted);">
                            {{ $facture->created_at->format('d/m/Y') }}<br>
                            <span style="font-size:10px;">{{ $facture->created_at->format('H:i') }}</span>
                        </td>
                        <td>
                            @if($facture->isSoldee())
                                <span class="fc-badge fc-badge-success"><i class="fas fa-check-circle"></i> Soldée</span>
                                @if($facture->is_printed)
                                    <div style="font-size:10px; color:var(--fc-text-muted); margin-top:3px;">
                                        <i class="fas fa-print"></i> Imprimée le {{ $facture->printed_at?->format('d/m/Y') }}
                                    </div>
                                @endif
                            @else
                                <span class="fc-badge fc-badge-warning"><i class="fas fa-clock"></i> Non soldée</span>
                                <div style="font-size:10px; color:#C62828; margin-top:3px;">
                                    <i class="fas fa-file-invoice"></i> Proforma
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right; text-transform:uppercase; letter-spacing:.5px;">TOTAL :</td>
                        <td style="text-align:right;">{{ number_format($factureExamens->sum('montant_total'), 0, ',', ' ') }}</td>
                        <td style="text-align:right; color:var(--fc-teal);">{{ number_format($factureExamens->sum('assurancec'), 0, ',', ' ') }}</td>
                        <td style="text-align:right;">{{ number_format($factureExamens->sum('assurec'), 0, ',', ' ') }}</td>
                        <td style="text-align:right; color:var(--fc-green);">{{ number_format($factureExamens->sum('avance'), 0, ',', ' ') }}</td>
                        <td style="text-align:right; color:var(--fc-red);">{{ number_format($factureExamens->sum('reste'), 0, ',', ' ') }}</td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="padding:14px 16px; border-top:1px solid var(--fc-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
            <div style="font-size:12px; color:var(--fc-text-muted);">
                Affichage de {{ $factureExamens->firstItem() }} à {{ $factureExamens->lastItem() }}
                sur {{ $factureExamens->total() }} résultats
            </div>
            {{ $factureExamens->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif

    {{-- ── Bilan journalier ── --}}
    <div class="fc-bilan-section">
        <div class="fc-bilan-title">
            <i class="fas fa-file-pdf" style="color:var(--fc-teal);"></i>
            Générer un bilan journalier — Examens
        </div>
        <form action="{{ route('facturation.examens.bilan_pdf') }}" method="POST" class="d-flex align-items-end flex-wrap gap-3">
            @csrf
            <div class="fc-toolbar-group">
                <span class="fc-toolbar-label">Sélectionner la date</span>
                <select name="day" class="form-select" style="min-width:200px; height:36px; font-size:13px;">
                    @foreach($lists ?? [] as $date)
                        <option value="{{ $date }}" {{ $date === now()->format('Y-m-d') ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="fc-btn fc-btn-info">
                <i class="fas fa-file-pdf"></i> Imprimer le bilan
            </button>
        </form>
    </div>
</div>

    </div>{{-- fin #content --}}
</div>{{-- fin .wrapper --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Filtres tableau ────────────────────────────────────────────────────
    document.getElementById('statutFilterExamen')?.addEventListener('change', function () {
        const val = this.value;
        document.querySelectorAll('#tableExamen tbody tr').forEach(function (row) {
            if (!val) { row.style.display = ''; return; }
            row.style.display = row.dataset.statut === val ? '' : 'none';
        });
    });

    document.getElementById('searchExamen')?.addEventListener('input', function () {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#tableExamen tbody tr').forEach(function (row) {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    // ── Disparition automatique des alertes ───────────────────────────────
    setTimeout(function () {
        document.getElementById('alert-success')?.remove();
        document.getElementById('alert-error')?.remove();
    }, 5000);

   
});
</script>
@endpush

@endsection