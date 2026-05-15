{{--
    ============================================================
    FICHIER : resources/views/admin/factures/partials/apercu_examen.blade.php
--}}

@extends('layouts.admin')

@section('title', 'CMCU | Aperçu Facture Examen — ' . $facture->numero)

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')
    <div class="container py-4">

        {{-- ══════════════════════════════════════════════════
             BARRE D'ACTIONS (en haut)
        ══════════════════════════════════════════════════ --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

            <a href="{{ route('facturation.examens.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>

            <div class="d-flex gap-2 flex-wrap align-items-center">

                {{-- Badge statut --}}
                @if($isProforma)
                    <span class="badge bg-warning text-dark fs-6 py-2 px-3">
                        <i class="fas fa-clock me-1"></i> PROFORMA
                    </span>
                @else
                    <span class="badge bg-success fs-6 py-2 px-3">
                        <i class="fas fa-check-circle me-1"></i> SOLDÉE
                    </span>
                @endif

                {{-- BUG 1 CORRIGÉ : route facturation.examen_pdf  --}}
                    <a href= "{{route('facturation.examen_pdf', $facture->id) }}"
                   title="{{ $isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture' }}">
                    <i class="fas fa-print me-1"></i>
                    {{ $isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture' }}
                </a>

            </div>
        </div>

        {{-- Info 1ère impression --}}
        @if(!$isProforma && $facture->is_printed && $facture->printed_at)
            <div class="alert alert-info py-2 mb-3" style="max-width:860px; margin:0 auto;">
                <i class="fas fa-info-circle me-1"></i>
                Imprimée le <strong>{{ $facture->printed_at->format('d/m/Y à H:i') }}</strong>
                @if($facture->printer)
                    par <strong>{{ trim(($facture->printer->prenom ?? '') . ' ' . ($facture->printer->name ?? '')) }}</strong>
                @endif
            </div>
        @endif

        {{-- ══════════════════════════════════════════════════
             CARTE PRINCIPALE
        ══════════════════════════════════════════════════ --}}
        <div class="card shadow-sm" style="max-width: 860px; margin: 0 auto; position: relative; overflow: hidden;">

            {{-- Watermark PROFORMA --}}
            @if($isProforma)
                <div style="
                    position: absolute; top: 50%; left: 50%;
                    transform: translate(-50%, -50%) rotate(-35deg);
                    font-size: 72px; font-weight: 900;
                    color: rgba(200, 30, 30, 0.07);
                    white-space: nowrap; pointer-events: none; z-index: 0;
                    letter-spacing: 12px;
                ">PROFORMA</div>
            @endif

            <div class="card-body p-4" style="position: relative; z-index: 1;">

                {{-- ── EN-TÊTE ── --}}
                <div class="text-center border-bottom pb-3 mb-3">
                    <strong>CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE</strong><br>
                    <small class="text-muted">
                        Vallée Manga Bell — Douala-Bali<br>
                        Tél : (+237) 233 423 389 / 674 068 988 / 698 873 945<br>
                        www.cmcu-cm.com
                    </small>
                </div>

                {{-- ── TITRE ── --}}
                <div class="text-center mb-3">
                    <h5 class="fw-bold mb-0">
                        @if($isProforma) FACTURE PROFORMA — @else REÇU — @endif
                        EXAMEN 
                    </h5>
                    <span class="text-muted">
                        N° {{ $facture->numero }} | Dossier : {{ $facture->patient->numero_dossier ?? $facture->patient_numero_dossier ?? '—' }}
                    </span>
                </div>

                {{-- ── INFOS PATIENT / FACTURE ── --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold text-muted" style="width:40%">Patient</td>
                                <td>{{ $facture->patient_display_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">N° Facture</td>
                                <td>{{ $facture->numero }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Date</td>
                                <td>{{ $facture->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            @if($facture->assurance && $facture->assurancec > 0)
                            <tr>
                                <td class="fw-semibold text-muted" style="width:40%">Assurance</td>
                                <td>{{ $facture->numero_assurance ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Prise en charge</td>
                                <td>{{ $facture->prise_en_charge ?? 0 }} %</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="fw-semibold text-muted">Mode paiement</td>
                                <td>{{ ucfirst($facture->mode_paiement ?? '—') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- ── TABLEAU DES LIGNES ACTES ── --}}
                @php
                    $lignes = $facture->lignes;
                @endphp

                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:38%">Examen</th>
                            <th style="width:22%">Intervenant</th>
                            <th class="text-center" style="width:10%">Qté</th>
                            <th class="text-end" style="width:15%">P.U.</th>
                            <th class="text-end" style="width:15%">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lignes as $ligne)
                            <tr>
                                <td>
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning me-1" style="font-size:10px;">
                                        Soin
                                    </span>
                                    {{ $ligne->libelle }}
                                    @if($ligne->date_acte)
                                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($ligne->date_acte)->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size:12px;">
                                    <!--{{ $ligne->medecin ?? $ligne->infirmiere ?? '—' }}-->

                                    {{ $ligne->technicien ?? $ligne->medecin ?? '—' }}
                                </td>
                                    
                                </td>
                                <td class="text-center">{{ $ligne->quantite ?? 1 }}</td>
                                <td class="text-end">{{ number_format((int)$ligne->montant, 0, ',', ' ') }} FCFA</td>
                                <td class="text-end fw-semibold">
                                    {{ number_format((int)$ligne->montant * ($ligne->quantite ?? 1), 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted fst-italic">Aucune ligne enregistrée</td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot>
                        {{-- Assurance (conditionnelle) --}}
                        @if($facture->assurance && $facture->assurancec > 0)
                        <tr class="table-light">
                            <td colspan="4" class="text-end text-muted">
                                <small>Part assurance ({{ $facture->prise_en_charge ?? 0 }}%) :</small>
                            </td>
                            <td class="text-end text-muted">
                                <small>{{ number_format($facture->assurancec, 0, ',', ' ') }} FCFA</small>
                            </td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="4" class="text-end text-muted">
                                <small>Part patient :</small>
                            </td>
                            <td class="text-end text-muted">
                                <small>{{ number_format($facture->assurec ?? 0, 0, ',', ' ') }} FCFA</small>
                            </td>
                        </tr>
                        @endif

                        {{-- BUG 4 CORRIGÉ : montant_total au lieu de montant --}}
                        <tr class="table-dark">
                            <td colspan="4" class="text-end fw-bold">TOTAL GÉNÉRAL</td>
                            <td class="text-end fw-bold fs-6">
                                {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>

                        @if(($facture->patient->avance ?? 0) > 0)
                        <tr class="table-success bg-opacity-50">
                            <td colspan="4" class="text-end">Avance perçue :</td>
                            <td class="text-end text-success fw-semibold">
                                − {{ number_format($facture->avance, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @endif

                        <tr class="{{ ($facture->reste ?? 0) > 0 ? 'table-danger' : 'table-success' }} bg-opacity-25">
                            <td colspan="4" class="text-end fw-bold">Reste à payer :</td>
                            <td class="text-end fw-bold {{ ($facture->reste ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($facture->reste ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    </tfoot>
                </table>

                {{-- ── PIED DE CARTE ── --}}
                <div class="d-flex justify-content-between mt-3 text-muted small">
                    <span>
                        Caissier(e) :
                        <strong>{{ trim(($facture->user->prenom ?? '') . ' ' . ($facture->user->name ?? '—')) }}</strong>
                    </span>
                    <span>Douala, le {{ $facture->created_at->format('d/m/Y') }}</span>
                </div>

                @if($isProforma)
                    <div class="alert alert-warning mt-3 mb-0 py-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Document PROFORMA</strong> — Cette facture n'est pas encore soldée.
                    </div>
                @endif

            </div>{{-- fin card-body --}}
        </div>{{-- fin card --}}

        {{-- ── BARRE D'ACTIONS BAS --}}
        <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
            <a href="{{ route('facturation.examens.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>

            {{-- BUG 1 CORRIGÉ ici aussi --}}
            <a href="{{ route('facturation.examen_pdf', $facture->id) }}"
               class="btn {{ $isProforma ? 'btn-outline-primary' : 'btn-success' }} btn-lg">
                <i class="fas fa-print me-1"></i>
                {{ $isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture' }}
            </a>
        </div>

    </div>{{-- fin container --}}

</div>{{-- fin wrapper --}}

<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection