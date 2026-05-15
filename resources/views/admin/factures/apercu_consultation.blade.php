{{--
    ============================================================
    FICHIER : resources/views/admin/factures/apercu_consultation.blade.php
--}}

@extends('layouts.admin')

@section('title', 'CMCU | Aperçu Facture — ' . $facture->numero)

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

            {{-- Bouton Retour --}}
            <a href="{{ route('factures.consultation') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>

            <div class="d-flex gap-2 flex-wrap align-items-center">

                {{-- ── Badge statut ── --}}
                @if($isProforma)
                    <span class="badge bg-warning text-dark fs-6 py-2 px-3">
                        <i class="fas fa-clock me-1"></i> PROFORMA
                    </span>
                @else
                    <span class="badge bg-success fs-6 py-2 px-3">
                        <i class="fas fa-check-circle me-1"></i> SOLDÉE
                    </span>
                @endif

             
                <a href="{{ route('factures.consultation_pdf', $facture->id) }}"
                   class="btn {{ $isProforma ? 'btn-outline-primary' : 'btn-success' }}"
                   title="{{ $isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture' }}">
                    <i class="fas fa-print me-1"></i>
                    {{ $isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture' }}
                </a>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════
             INFO DATE DE 1ÈRE IMPRESSION (correction 3)
             Visible uniquement si la facture a déjà été imprimée
             au moins une fois ET qu'elle est soldée.
        ══════════════════════════════════════════════════ --}}
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
             CARTE PRINCIPALE — APERÇU DE LA FACTURE
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

                {{-- ── EN-TÊTE ÉTABLISSEMENT ── --}}
                <div class="text-center border-bottom pb-3 mb-3">
                    <strong>CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE</strong><br>
                    <small class="text-muted">
                        Vallée Manga Bell — Douala-Bali<br>
                        Tél : (+237) 233 423 389 / 674 068 988 / 698 873 945<br>
                        www.cmcu-cm.com
                    </small>
                </div>

                {{-- ── TITRE FACTURE ── --}}
                <div class="text-center mb-3">
                    <h5 class="fw-bold mb-0">
                        @if($isProforma)
                            FACTURE PROFORMA —
                        @else
                            REÇU —
                        @endif
                        {{ strtoupper($facture->details_motif ?? $facture->motif ?? 'CONSULTATION') }}
                    </h5>
                    <span class="text-muted">N° Dossier : {{ $facture->patient->numero_dossier ?? '—' }}</span>
                </div>

                {{-- ── INFORMATIONS PATIENT / FACTURE ── --}}
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
                            <tr>
                                <td class="fw-semibold text-muted">Motif</td>
                                <td>{{ $facture->motif ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold text-muted" style="width:40%">Médecin</td>
                                <td>{{ $facture->medecin_r ?? '—' }}</td>
                            </tr>
                            @if(!empty($facture->assurance))
                            <tr>
                                <td class="fw-semibold text-muted">Assurance</td>
                                <td>{{ $facture->assurance }}</td>
                            </tr>
                            @endif
                            @if(!empty($facture->demarcheur))
                            <tr>
                                <td class="fw-semibold text-muted">Démarcheur</td>
                                <td>{{ $facture->demarcheur }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="fw-semibold text-muted">Mode paiement</td>
                                <td>{{ ucfirst($facture->mode_paiement ?? '—') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- ── TABLEAU DES ACTES ── --}}
                {{--
                    CORRECTION 4 (aperçu) :
                    La colonne Montant affiche :
                    - Ligne consultation : montant de la 1ère ligne de type "consultation"
                      si elle existe (snapshot), sinon montant total - totalLignes.
                    - Chaque ligne ajoutée : son propre montant.
                --}}
                @php
                    $lignesCollection = $facture->lignes;

                    // Chercher la ligne consultation snapshotée
                    $ligneConsBase = $lignesCollection->firstWhere('type_acte', 'consultation');
                    $montantConsBase = $ligneConsBase
                        ? (int) $ligneConsBase->montant
                        : (int) ($facture->montant - $totalLignes);

                    // Les lignes à afficher en détail = tout sauf la ligne consultation de base
                    $autresLignes = $ligneConsBase
                        ? $lignesCollection->where('type_acte', '!=', 'consultation')
                        : $lignesCollection;
                @endphp

                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:18%">Type</th>
                            <th style="width:42%">Description</th>
                            <th style="width:20%">Médecin</th>
                            <th class="text-end" style="width:20%">Montant</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- ① Ligne consultation de base --}}
                        <tr>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                    {{$facture->motif}}
                                </span>
                            </td>
                            <td>
                                {{ $facture->motif ?? 'Consultation médicale' }}
                                @if(!empty($facture->details_motif))
                                    <br><small class="text-muted">{{ $facture->details_motif }}</small>
                                @endif
                            </td>
                            <td class="text-muted">{{ $facture->medecin_r ?? '—' }}</td>
                            <td class="text-end fw-semibold">
                                {{ number_format($montantConsBase, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>

                        {{-- ② Lignes ajoutées --}}
                        @foreach($autresLignes as $ligne)
                            @php
                                $badgeClass = match($ligne->type_acte) {
                                    'examen_labo'    => 'bg-info bg-opacity-10 text-info border border-info',
                                    'soin_infirmier' => 'bg-warning bg-opacity-10 text-warning border border-warning',
                                    default          => 'bg-secondary bg-opacity-10 text-secondary border border-secondary',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $ligne->label_type }}
                                    </span>
                                </td>
                                <td>{{ $ligne->libelle }}</td>
                                <td class="text-muted">
                                    {{ $ligne->medecin ?? $ligne->infirmniere ?? '—' }}
                                </td>
                                <td class="text-end fw-semibold">
                                    {{-- CORRECTION 4 : afficher le montant propre de la ligne --}}
                                    {{ number_format((int)$ligne->montant, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                    {{-- Totaux --}}
                    <tfoot>

                        @if(!empty($facture->assurancec) && $facture->assurancec > 0)
                        <tr class="table-light">
                            <td colspan="3" class="text-end text-muted">
                                <small>Part assurance ({{ $facture->patient->prise_en_charge ?? 0 }}%) :</small>
                            </td>
                            <td class="text-end text-muted">
                                <small>{{ number_format($facture->assurancec, 0, ',', ' ') }} FCFA</small>
                            </td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="3" class="text-end text-muted">
                                <small>Part patient :</small>
                            </td>
                            <td class="text-end text-muted">
                                <small>{{ number_format($facture->assurec ?? 0, 0, ',', ' ') }} FCFA</small>
                            </td>
                        </tr>
                        @endif

                        <tr class="table-dark">
                            <td colspan="3" class="text-end fw-bold">TOTAL GÉNÉRAL</td>
                            <td class="text-end fw-bold fs-6">
                                {{ number_format($facture->montant, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>

                        @if(($facture->avance ?? 0) > 0)
                        <tr class="table-success bg-opacity-50">
                            <td colspan="3" class="text-end">Avance perçue :</td>
                            <td class="text-end text-success fw-semibold">
                                − {{ number_format($facture->avance, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @endif

                        <tr class="{{ ($facture->reste ?? 0) > 0 ? 'table-danger' : 'table-success' }} bg-opacity-25">
                            <td colspan="3" class="text-end fw-bold">Reste à payer :</td>
                            <td class="text-end fw-bold {{ ($facture->reste ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($facture->reste ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>

                    </tfoot>
                </table>

                {{-- ── PIED DE FACTURE ── --}}
                <div class="d-flex justify-content-between mt-3 text-muted small">
                    <span>
                        Caissier(e) :
                        <strong>{{ trim(($facture->user->prenom ?? '') . ' ' . ($facture->user->name ?? '—')) }}</strong>
                    </span>
                    <span>Douala, le {{ $facture->created_at->format('d/m/Y') }}</span>
                </div>

                {{-- Message PROFORMA --}}
                @if($isProforma)
                    <div class="alert alert-warning mt-3 mb-0 py-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Document PROFORMA</strong> — Cette facture n'est pas encore soldée.
                        Le document imprimé portera la mention <strong>PROFORMA</strong>.
                    </div>
                @endif

            </div>{{-- fin card-body --}}
        </div>{{-- fin card --}}

        {{-- ── DEUXIÈME BARRE D'ACTIONS (en bas) ── --}}
        <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
            <a href="{{ route('factures.consultation') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>

           

            <a href="{{ route('factures.consultation_pdf', $facture->id) }}"
               class="btn {{ $isProforma ? 'btn-outline-primary' : 'btn-success' }} btn-lg">
                <i class="fas fa-print me-1"></i>
                {{ $isProforma ? 'Imprimer le PROFORMA' : 'Imprimer la Facture' }}
            </a>
        </div>

    </div>{{-- fin container --}}

</div>{{-- fin wrapper --}}

<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection