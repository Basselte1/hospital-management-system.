@extends('layouts.admin')
@section('title', 'CMCU | Factures Examens')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

@include('admin.factures.partials._factures_common')

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
            <button type="button" class="fc-btn fc-btn-success"
                    data-bs-toggle="modal" data-bs-target="#modalNouvelleFactureExamen">
                <i class="fas fa-plus"></i> Nouvelle facture examen
            </button>
            <a href="{{ route('facturation.dashboard') }}" class="fc-btn fc-btn-light">
                <i class="fas fa-arrow-left"></i> Accueil
            </a>
        </div>
    </div>

    {{-- ── Alertes ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
            <div class="fc-kpi-value">{{ number_format($factureExamens->sum('montant'), 0, ',', ' ') }}</div>
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
                    <option value="Hématologie" {{ request('type_acte') === 'Hématologie' ? 'selected' : '' }}>Hématologie (NFS, Hémogramme)</option>
                    <option value="Biochimie" {{ request('type_acte') === 'Biochimie' ? 'selected' : '' }}>Biochimie (Glycémie, Créatinine, Urée)</option>
                    <option value="Parasitologie" {{ request('type_acte') === 'Hémostase' ? 'selected' : '' }}>Hémostase (TP, TCA, INR)</option>
                    <option value="Hormonologie" {{ request('type_acte') === 'Hormonologie' ? 'selected' : '' }}>Hormonologie (PSA, Testostérone)</option>
                    <option value="Bactériologie" {{ request('type_acte') === 'Bactériologie' ? 'selected' : '' }}>Bactériologie (ECBU, Hémoculture)</option>
                    <option value="Immuno-Sérologie" {{ request('type_acte') === 'Sérologie' ? 'selected' : '' }}>Sérologie (VIH, Hépatites)</option>
                
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
                                        <button type="button"
                                                class="fc-action-btn delete btn-confirm-delete-examen"
                                                title="Supprimer"
                                                data-facture-id="{{ $facture->id }}"
                                                data-facture-numero="{{ $facture->numero }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                @endcan
                                @else
                                    <a href="{{ route('factures.examen_pdf', $facture->id) }}"
                                       class="fc-action-btn print-ok" title="Imprimer la facture soldée">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @endif
                                <a href="{{ route('factures.apercu_examen', $facture->id) }}"
                                   class="fc-action-btn view" title="Aperçu">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Ajouter un examen supplémentaire --}}
                                <button type="button"
                                        class="fc-action-btn add btn-open-modal-ajout-examen"
                                        title="Ajouter un examen à cette facture"
                                        data-facture-id="{{ $facture->id }}"
                                        data-facture-numero="{{ $facture->numero }}"
                                        data-patient-id="{{ $facture->patient_id }}"
                                        data-medecin="{{ $facture->medecin_r ?? '' }}">
                                    <i class="fas fa-plus"></i>
                                </button>


                                {{-- Modifier (si non soldée) --}}
                                @can('update', $facture)
                                @if($facture->isModifiable())
                                    <button type="button"
                                            class="fc-action-btn edit btn-open-modal-edit-examen"
                                            title="Modifier le paiement"
                                            data-id-facture="{{ $facture->id }}"
                                            data-mode_paiement="{{ $facture->mode_paiement }}"
                                            data-montant="{{ $facture->montant }}"
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
                            {{-- Afficher les lignes d'examens  Labo et Radio uniquement --}}
                            @foreach($facture->lignes as $ligne)
                                <div style="display:flex; align-items:center; gap:5px; margin-bottom:3px;">
                                    @if($ligne->type_acte === 'examen_labo')
                                        <span class="fc-badge fc-badge-teal" style="font-size:10px;">Labo</span>
                                    @elseif($ligne->type_acte === 'examen_radio')
                                        <span class="fc-badge fc-badge-purple" style="font-size:10px;">Radio</span>
                                    @else
                                        <span class="fc-badge fc-badge-gray" style="font-size:10px;">Acte</span>
                                    @endif
{{ substr($ligne->libelle, 0, 35) }}{{ strlen($ligne->libelle) > 35 ? '...' : '' }}
                                </div>
                            @endforeach
                        </td>
                        <td style="font-weight:600; text-align:right;">{{ number_format($facture->montant, 0, ',', ' ') }}</td>
                        <td style="text-align:right; color:var(--fc-teal);">{{ number_format($facture->assurancec, 0, ',', ' ') }}</td>
                        <td style="text-align:right;">{{ number_format($facture->assurec, 0, ',', ' ') }}</td>
                        <td style="text-align:right; color:var(--fc-green); font-weight:600;">{{ number_format($facture->avance, 0, ',', ' ') }}</td>
                        <td style="text-align:right;">
                            <span class="{{ $facture->reste == 0 ? 'fc-reste-zero' : 'fc-reste-nonzero' }}">
                                {{ number_format($facture->reste, 0, ',', ' ') }}
                            </span>
                        </td>
                        <td><span class="fc-badge fc-badge-gray">{{ ucfirst($facture->mode_paiement ?? 'espèce') }}</span></td>
                        <td style="font-size:12px;">{{ $facture->medecin_r ?? '—' }}</td>
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
                        <td style="text-align:right;">{{ number_format($factureExamens->sum('montant'), 0, ',', ' ') }}</td>
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

{{-- ══════════════════════════════════════════════════════════════
     MODAL — Nouvelle facture examen (création)
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade fc-modal" id="modalNouvelleFactureExamen" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#00695C,#00897B);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-microscope me-2"></i> Nouvelle facture d'examen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNouvelleFactureExamen" method="POST" action="{{ route('facturation.examens.store_direct') }}">
                @csrf
                <div class="modal-body">

                    {{-- Patient --}}
                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="fc-field-group">
                                <label>Patient <span style="color:red">*</span></label>
                                <select name="patient_id" id="fe-patient-select" class="form-select" required
                                        style="height:38px; font-size:13px;">
                                    <option value="">-- Rechercher un patient (nom, prénom, n° dossier) --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="fc-field-group">
                                <label>N° Dossier</label>
                                <input type="text" id="fe-numero-dossier" class="form-control" readonly
                                       style="background:#f5f5f5; font-size:13px;">
                            </div>
                        </div>
                    </div>

                    {{-- Prescriptions disponibles --}}
                    <div id="fe-prescriptions-card" class="d-none" style="background:var(--fc-blue-light); border:1px solid #90CAF9; border-radius:var(--fc-radius); padding:12px 14px; margin-bottom:14px;">
                        <div style="font-size:12px; font-weight:600; color:var(--fc-blue); margin-bottom:6px;">
                            <i class="fas fa-clipboard-list me-1"></i> Prescriptions disponibles
                        </div>
                        <select id="fe-prescription-select" class="form-select form-select-sm">
                            <option value="">-- Saisir manuellement --</option>
                        </select>
                    </div>

                    {{-- Type + Libellé --}}
                    <div class="row">
                        <div class="col-4">
                            <div class="fc-field-group">
                                <label>Type d'examen <span style="color:red">*</span></label>
                                <select name="type_acte" id="fe-type-acte" class="form-select" required>
                                    <option value="examen_labo">Laboratoire</option>
                                    <option value="examen_radio">Imagerie / Radio</option>
                                    <option value="autre">Autre acte</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="fc-field-group">
                                <label>Description / Libellé <span style="color:red">*</span></label>
                                <input type="text" name="libelle" id="fe-libelle" class="form-control"
                                       placeholder="Ex : NFS + CRP, Échographie rénale..." required>
                            </div>
                        </div>
                    </div>

                    {{-- Financier --}}
                    <div class="row">
                        <div class="col-4">
                            <div class="fc-field-group">
                                <label>Montant (FCFA) <span style="color:red">*</span></label>
                                <input type="number" name="montant" id="fe-montant" class="form-control"
                                       min="1" step="100" required placeholder="0">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="fc-field-group">
                                <label>Avance (FCFA)</label>
                                <input type="number" name="avance" id="fe-avance" class="form-control"
                                       min="0" step="100" placeholder="0">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="fc-field-group">
                                <label>Reste calculé</label>
                                <input type="text" id="fe-reste-display" class="form-control fc-reste-input"
                                       value="0 FCFA" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Médecin + Mode paiement --}}
                    <div class="row">
                        <div class="col-6">
                            <div class="fc-field-group">
                                <label>Médecin prescripteur</label>
                                <input type="text" name="medecin_r" id="fe-medecin" class="form-control"
                                       placeholder="Nom du médecin">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fc-field-group">
                                <label>Mode de paiement</label>
                                <select name="mode_paiement" class="form-select">
                                    <option value="espèce">Espèce</option>
                                    <option value="mobile">Mobile Money</option>
                                    <option value="chèque">Chèque</option>
                                    <option value="virement">Virement</option>
                                    <option value="bon de prise en charge">Bon de prise en charge</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Résumé --}}
                    <div id="fe-resume" class="fc-resume-box d-none mt-2">
                        <i class="fas fa-check-circle me-1"></i>
                        <span id="fe-resume-text"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="fc-btn fc-btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" id="fe-submit-btn" class="fc-btn fc-btn-success">
                        <i class="fas fa-save"></i> Créer la facture examen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL — Ajouter un examen à une facture existante
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade fc-modal" id="modalAjoutExamen" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#00695C,#00897B);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-plus-circle me-2"></i>
                    Ajouter un examen — Facture <span id="ajout-ex-numero"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAjoutExamen" method="POST">
                @csrf
                <input type="hidden" name="facture_id" id="ajout-ex-facture-id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="fc-field-group">
                                <label>Type d'examen</label>
                                <select name="type_acte" class="form-select">
                                    <option value="examen_labo">Laboratoire</option>
                                    <option value="examen_radio">Imagerie / Radio</option>
                                    <option value="autre">Autre acte</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fc-field-group">
                                <label>Libellé <span style="color:red">*</span></label>
                                <input type="text" name="libelle" class="form-control"
                                       placeholder="Ex: Scanner, Biopsie..." required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="fc-field-group">
                                <label>Montant (FCFA) <span style="color:red">*</span></label>
                                <input type="number" name="montant" id="ajout-ex-montant" class="form-control"
                                       min="0" step="100" required placeholder="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fc-field-group">
                                <label>Médecin</label>
                                <input type="text" name="medecin" id="ajout-ex-medecin" class="form-control"
                                       placeholder="Nom du médecin">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="fc-btn fc-btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="fc-btn fc-btn-success">
                        <i class="fas fa-save"></i> Ajouter et recalculer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

    // ── Calcul reste dans modal nouvelle facture ───────────────────────────
    function calcResteExamen() {
        const m = parseFloat(document.getElementById('fe-montant')?.value) || 0;
        const a = parseFloat(document.getElementById('fe-avance')?.value) || 0;
        const r = Math.max(0, m - a);
        const el = document.getElementById('fe-reste-display');
        if (el) {
            el.value = r.toLocaleString('fr-FR') + ' FCFA';
            el.classList.toggle('zero',    r === 0);
            el.classList.toggle('nonzero', r > 0);
        }
        updateResumeExamen();
    }

    document.getElementById('fe-montant')?.addEventListener('input', calcResteExamen);
    document.getElementById('fe-avance')?.addEventListener('input', calcResteExamen);

    // ── Résumé temps réel ──────────────────────────────────────────────────
    function updateResumeExamen() {
        const patientOpt = document.querySelector('#fe-patient-select option:checked');
        const patientText = patientOpt ? patientOpt.textContent : '';
        const m = parseFloat(document.getElementById('fe-montant')?.value) || 0;
        const a = parseFloat(document.getElementById('fe-avance')?.value) || 0;
        const resume = document.getElementById('fe-resume');
        if (patientText && patientText !== '-- Rechercher un patient --' && m > 0) {
            document.getElementById('fe-resume-text').textContent =
                patientText + ' — ' + m.toLocaleString('fr-FR') + ' FCFA' +
                ' | Avance: ' + a.toLocaleString('fr-FR') + ' FCFA' +
                ' | Reste: ' + Math.max(0,m-a).toLocaleString('fr-FR') + ' FCFA';
            resume?.classList.remove('d-none');
        } else {
            resume?.classList.add('d-none');
        }
    }

    document.getElementById('fe-patient-select')?.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        document.getElementById('fe-numero-dossier').value = opt.dataset?.numeroDossier || '';
        updateResumeExamen();
    });

    // ── Select2 patient (si disponible) ───────────────────────────────────
    if (typeof $.fn !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
        $('#fe-patient-select').select2({
            dropdownParent: $('#modalNouvelleFactureExamen'),
            placeholder: 'Rechercher un patient...',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("patient-visits.search-patients") }}',
                dataType: 'json', delay: 300,
                data: function (p) { return { q: p.term }; },
                processResults: function (d) { return { results: d.results }; },
            },
        });
        $('#fe-patient-select').on('select2:select', function (e) {
            document.getElementById('fe-numero-dossier').value = e.params.data.numero_dossier || '';
            updateResumeExamen();
        });
    }

    // ── Modal ajout examen (sur facture existante) ─────────────────────────
    document.querySelectorAll('.btn-open-modal-ajout-examen').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('ajout-ex-facture-id').value = this.dataset.factureId;
            document.getElementById('ajout-ex-numero').textContent = '#' + this.dataset.factureNumero;
            document.getElementById('ajout-ex-medecin').value = this.dataset.medecin || '';
            document.getElementById('formAjoutExamen').action =
                '/admin/factures-consultation/' + this.dataset.factureId + '/add-ligne';
            new bootstrap.Modal(document.getElementById('modalAjoutExamen')).show();
        });
    });

    // ── Prévention double soumission ───────────────────────────────────────
    document.getElementById('formNouvelleFactureExamen')?.addEventListener('submit', function (e) {
        const libelle = document.getElementById('fe-libelle')?.value.trim();
        const montant = parseFloat(document.getElementById('fe-montant')?.value) || 0;
        if (!libelle) { e.preventDefault(); alert('Veuillez saisir la description des examens.'); return; }
        if (montant <= 0) { e.preventDefault(); alert('Le montant doit être supérieur à 0.'); return; }
        const btn = document.getElementById('fe-submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enregistrement...';
    });

    // Réinitialiser modal à la fermeture
    document.getElementById('modalNouvelleFactureExamen')?.addEventListener('hidden.bs.modal', function () {
        this.querySelector('form').reset();
        document.getElementById('fe-numero-dossier').value = '';
        document.getElementById('fe-prescriptions-card')?.classList.add('d-none');
        document.getElementById('fe-reste-display').value = '0 FCFA';
        document.getElementById('fe-reste-display').className = 'form-control fc-reste-input';
        document.getElementById('fe-resume')?.classList.add('d-none');
        const btn = document.getElementById('fe-submit-btn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Créer la facture examen';
    });

    setTimeout(function () { document.querySelectorAll('.alert').forEach(function (a) { a.style.opacity = '0'; }); }, 5000);
});
</script>
@endpush

@endsection