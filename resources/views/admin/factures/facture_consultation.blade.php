@extends('layouts.admin')
@section('title', 'CMCU | Factures Consultation')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('view', \App\Models\User::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
                <div class="tw-flex tw-items-center tw-justify-between tw-mb-4 tw-flex-wrap tw-gap-2">
              
                    {{-- Page heading --}}
                    <div class="tw-mb-6">
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Factures de Consultation</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Suivi des paiements et règlements patients</p>
                    </div>
                            <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-sm tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors">
                                <a href="{{ route('facturation.dashboard') }}">
                                    <i class="fas fa-arrow-left"></i> Accueil
                                </a>
                            </button>
                </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6] tw-shrink-0"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-triangle tw-text-red-400 tw-shrink-0"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Date filter card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-bg-slate-50 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-filter tw-text-[#1D4ED8] tw-text-sm"></i>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Filtres de Recherche</h2>
                </div>
                <div class="tw-p-5">
                    <form action="{{ route('search.date') }}" method="POST" class="tw-flex tw-items-end tw-flex-wrap tw-gap-4">
                        @csrf
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date de début</label>
                            <input type="date" name="start-date" id="start-date"
                                   value="{{ request('start-date', $startDate->format('Y-m-d')) }}"
                                   class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date de fin</label>
                            <input type="date" name="end-date" id="end-date1"
                                   value="{{ request('end-date', $endDate->format('Y-m-d')) }}"
                                   class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                        </div>
                        <div>
                            <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-sm tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors">
                                <i class="fas fa-search tw-text-xs"></i> Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($factureConsultations))

            {{-- Period summary --}}
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-4 tw-flex-wrap tw-gap-2">
                <p class="tw-text-sm tw-text-slate-500 tw-mb-0">
                    Période du <strong class="tw-text-slate-700">{{ $startDate->format('d/m/Y') }}</strong>
                    au <strong class="tw-text-slate-700">{{ $endDate->format('d/m/Y') }}</strong>
                </p>
                <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-px-3 tw-py-1 tw-text-xs tw-font-semibold">
                    <i class="fas fa-file-invoice tw-text-[9px]"></i>
                    {{ $factureConsultations->total() }} facture(s)
                </span>
            </div>

            {{-- Table card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                <div class="tw-px-5 tw-py-3 tw-border-b tw-border-slate-100 tw-bg-slate-50">
                    <p class="tw-text-[10px] tw-text-slate-400 tw-italic tw-mb-0">
                        Les montants sont exprimés en <strong>FCFA</strong>
                    </p>
                </div>
                
                <div class="tw-overflow-x-auto">
                    <table id="myTable" class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-3 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">N°</th>
                                <th class="tw-px-3 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Actions</th>
                                <th class="tw-px-3 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Patient</th>
                                <th class="tw-px-3 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Motif</th>
                                <th class="tw-px-3 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Montant</th>
                                <th class="tw-px-3 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Part Ass.</th>
                                <th class="tw-px-3 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Part Pat.</th>
                                <th class="tw-px-3 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Avancé</th>
                                <th class="tw-px-3 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Reste</th>
                                <th class="tw-px-3 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Paiement</th>
                                <th class="tw-px-3 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Médecin</th>
                                <th class="tw-px-3 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold tw-whitespace-nowrap">Date</th>
                                <th class="tw-px-3 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">
                                    Statut
                                    <br>
                                    <select id="statut-filter" class="tw-mt-1 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white tw-px-2 tw-py-0.5 tw-text-[10px] tw-text-slate-600 focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-[#BFDBFE] tw-normal-case tw-font-normal tw-tracking-normal">
                                        <option value="">Tous</option>
                                        <option value="soldée">Soldées</option>
                                        <option value="non soldée">Non soldées</option>
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($factureConsultations as $facture)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors {{ $facture->isSoldee() ? 'tw-bg-teal-50/30' : '' }}">

                                {{-- N° --}}
                                <td class="tw-px-3 tw-py-2.5 tw-font-mono tw-font-semibold tw-text-xs tw-text-slate-700">
                                    {{ $facture->numero }}
                                </td>

                                {{-- Actions --}}
                                <td class="tw-px-3 tw-py-2.5">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1">
                                        @if($facture->isImprimable())
                                        <a href="{{ route('factures.consultation_pdf', $facture->id) }}"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-600 tw-transition-colors tw-no-underline"
                                           data-bs-toggle="tooltip" title="Imprimer la facture soldée">
                                            <i class="fas fa-print tw-text-xs"></i>

                                        </a>
                                        @elseif($facture->isProforma())
                                            <a href="{{ route('factures.consultation_pdf', $facture->id) }}"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-blue-50 hover:tw-bg-blue-100 tw-text-blue-600 tw-transition-colors tw-no-underline"
                                                data-bs-toggle="tooltip" title="Imprimer la proforma">
                                                <i class="fas fa-print tw-text-xs"></i>
                                            </a>
                                        @else
                                        <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-slate-100 tw-text-slate-300 tw-cursor-not-allowed"
                                              data-bs-toggle="tooltip" title="Impression disponible une fois soldée">
                                            <i class="fas fa-print tw-text-xs"></i>
                                        </span>
                                        @endif
                                          {{-- Aperçu --}}
                                            <a href="{{ route('factures.apercu_consultation', $facture->id) }}"
                                            class="fc-action-btn view" title="Voir l'aperçu">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                        @can('update', $facture)
                                            @if($facture->isModifiable())
                                            <button type="button"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-border-0 tw-transition-colors"
                                                data-bs-toggle="modal" data-bs-target="#edit_facture_modal"
                                                title="Modifier la facture"
                                                data-id-facture="{{ $facture->id }}"
                                                data-nom="{{ $facture->patient_display_name }}"
                                                data-montant="{{ $facture->montant }}"
                                                data-reste="{{ $facture->reste }}"
                                                data-mode_paiement="{{ $facture->mode_paiement }}"
                                                data-prise_en_charge="{{ optional($facture->patient)->prise_en_charge ?? 0 }}">
                                                <i class="fas fa-edit tw-text-xs"></i>
                                            </button>

                                            <form action="{{ route('factures.destroy', $facture->id) }}" method="POST" class="tw-inline"
                                                  onsubmit="return confirm('Supprimer cette facture ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-transition-colors"
                                                    data-bs-toggle="tooltip" title="Supprimer la facture">
                                                    <i class="fas fa-trash tw-text-xs"></i>
                                                </button>
                                            </form>

                                            @else
                                            <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-slate-100 tw-text-slate-300 tw-cursor-not-allowed" title="Facture soldée">
                                                <i class="fas fa-edit tw-text-xs"></i>
                                            </span>
                                            <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-slate-100 tw-text-slate-300 tw-cursor-not-allowed" title="Facture soldée">
                                                <i class="fas fa-trash tw-text-xs"></i>
                                            </span>
                                            @endif
                                        @endcan
                                    </div>
                                </td>

                                {{-- Patient --}}
                                <td class="tw-px-3 tw-py-2.5 tw-font-medium tw-text-slate-700 tw-text-xs">
                                    {{ $facture->patient_display_name }}
                                </td>

                                {{-- Motif --}}
                                <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-text-slate-500">
                                    {{ $facture->details_motif ?? 'Consultation' }}
                                </td>

                                {{-- Financials --}}
                                <td class="tw-px-3 tw-py-2.5 tw-text-right tw-font-semibold tw-text-slate-700 tw-text-xs tw-whitespace-nowrap">
                                    {{ number_format($facture->montant, 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-3 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-whitespace-nowrap">
                                    {{ number_format($facture->assurancec, 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-3 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-whitespace-nowrap">
                                    {{ number_format($facture->assurec, 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-3 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-whitespace-nowrap">
                                    {{ number_format($facture->avance, 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-3 tw-py-2.5 tw-text-right tw-text-xs tw-font-bold tw-whitespace-nowrap {{ $facture->reste > 0 ? 'tw-text-red-500' : 'tw-text-teal-600' }}">
                                    {{ number_format($facture->reste, 0, ',', ' ') }}
                                </td>

                                {{-- Mode paiement --}}
                                <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-text-slate-500">
                                    {{ $facture->mode_paiement === 'bon de prise en charge' ? 'BPC' : ucfirst($facture->mode_paiement) }}
                                    @if($facture->mode_paiement_info_sup)
                                        @foreach(preg_split("/[\/]{2} /", $facture->mode_paiement_info_sup, 0, PREG_SPLIT_NO_EMPTY) as $info_sup)
                                        <br><span class="tw-text-[10px] tw-text-slate-400">{{ $info_sup }}</span>
                                        @endforeach
                                    @endif
                                </td>

                                {{-- Médecin --}}
                                <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-text-slate-500">{{ $facture->medecin_r }}</td>

                                {{-- Date --}}
                                <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-text-slate-500 tw-whitespace-nowrap">
                                    {{ $facture->created_at->format('d/m/Y H:i') }}
                                </td>

                                {{-- Statut --}}
                                <td class="tw-px-3 tw-py-2.5 tw-text-center">
                                    @if($facture->isSoldee())
                                    <span class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-bg-teal-100 tw-text-teal-700 tw-px-2.5 tw-py-0.5 tw-text-[10px] tw-font-semibold tw-whitespace-nowrap">
                                        <i class="fas fa-check-circle tw-text-[8px]"></i> Soldée
                                    </span>
                                    @if($facture->is_printed)
                                    <br><span class="tw-text-[9px] tw-text-slate-400">Imprimée le {{ $facture->printed_at->format('d/m/Y') }}</span>
                                    @endif
                                    @else
                                    <span class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-bg-amber-100 tw-text-amber-700 tw-px-2.5 tw-py-0.5 tw-text-[10px] tw-font-semibold tw-whitespace-nowrap">
                                        <i class="fas fa-exclamation-circle tw-text-[8px]"></i> Non soldée
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="tw-bg-[#BFDBFE]/20 tw-border-t-2 tw-border-[#BFDBFE]">
                                <td colspan="4" class="tw-px-3 tw-py-3 tw-text-right tw-font-bold tw-text-slate-700 tw-text-xs tw-uppercase tw-tracking-wide">Total</td>
                                <td class="tw-px-3 tw-py-3 tw-text-right tw-font-bold tw-text-[#1D4ED8] tw-text-xs tw-whitespace-nowrap">
                                    {{ number_format($factureConsultations->sum('montant'), 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-3 tw-py-3 tw-text-right tw-font-bold tw-text-slate-600 tw-text-xs tw-whitespace-nowrap">
                                    {{ number_format($factureConsultations->sum('assurancec'), 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-3 tw-py-3 tw-text-right tw-font-bold tw-text-slate-600 tw-text-xs tw-whitespace-nowrap">
                                    {{ number_format($factureConsultations->sum('assurec'), 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-3 tw-py-3 tw-text-right tw-font-bold tw-text-slate-600 tw-text-xs tw-whitespace-nowrap">
                                    {{ number_format($factureConsultations->sum('avance'), 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-3 tw-py-3 tw-text-right tw-font-bold tw-text-red-500 tw-text-xs tw-whitespace-nowrap">
                                    {{ number_format($factureConsultations->sum('reste'), 0, ',', ' ') }}
                                </td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($factureConsultations->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $factureConsultations->links() }}
                </div>
                @endif
            </div>

            {{-- Bilan PDF generator --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-file-pdf tw-text-white tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">Générer un Bilan Journalier</h2>
                </div>
                <form class="tw-p-5" method="POST" action="{{ route('bilan_consultation.pdf') }}">
                    @csrf
                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4 tw-items-end">
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date <span class="tw-text-red-500">*</span></label>
                            <select name="day" id="day" required
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                <option value="">— Choisir une date —</option>
                                @foreach($lists as $list)
                                <option value="{{ $list }}">{{ Carbon\Carbon::parse($list)->format('d/m/Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Service <span class="tw-text-red-500">*</span></label>
                            <select name="service" id="service" required
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                <option value="Tout" selected>Tous les services</option>
                                <option value="Consultation">Consultation</option>
                                <option value="Acte">Acte</option>
                                <option value="Examen">Examen</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit"
                                class="tw-w-full tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-sm tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors">
                                <i class="fas fa-print tw-text-xs"></i> Imprimer le bilan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @endif
        </main>

        {{-- ── Edit Facture Modal ──────────────────────────────────────── --}}
        <div id="edit_facture_modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-bg-[#14B8A6] tw-flex tw-items-center tw-justify-between">
                        <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm">
                            <i class="fas fa-cash-register tw-mr-2"></i> Nouveau Versement
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="edit_facture_form" method="POST">
                        @csrf @method('PUT')
                        <div class="tw-p-6">
                            <div class="tw-flex tw-items-start tw-gap-3 tw-rounded-xl tw-bg-[#BFDBFE]/30 tw-border tw-border-[#BFDBFE] tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-[#1D4ED8]">
                                <i class="fas fa-info-circle tw-shrink-0 tw-mt-0.5"></i>
                                <span><strong>Important :</strong> Cette facture n'est pas encore soldée. Vous pouvez la modifier.</span>
                            </div>

                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-5">
                                {{-- Left column --}}
                                <div class="tw-space-y-4">
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Montant total</label>
                                        <input type="number" name="montant" id="montant" min="0" required
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    </div>
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Part patient</label>
                                        <input type="number" name="part_patient" id="part_patient" min="0" readonly
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-500 tw-cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Reste à payer</label>
                                        <input type="number" name="reste" id="reste" readonly
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-500 tw-cursor-not-allowed">
                                    </div>
                                </div>

                                {{-- Right column --}}
                                <div class="tw-space-y-4">
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                            Montant versé <span class="tw-text-red-500">*</span>
                                        </label>
                                        <input type="number" name="percu" id="percu" min="0" placeholder="0" required
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        <p class="tw-text-[10px] tw-text-slate-400 tw-mt-1">Montant versé par le patient maintenant</p>
                                    </div>
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                            Mode de paiement <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select name="mode_paiement" id="mode_paiement" required
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                            <optgroup label="Monnaie électronique">
                                                <option value="orange money">Orange Money</option>
                                                <option value="mtn mobile money">MTN Mobile Money</option>
                                            </optgroup>
                                            <optgroup label="Autres moyens">
                                                <option value="espèce">Espèce</option>
                                                <option value="chèque">Chèque</option>
                                                <option value="virement">Virement</option>
                                                <option value="bon de prise en charge">Bon de prise en charge</option>
                                                <option value="autre">Autre</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    {{-- Cheque fields --}}
                                    <div id="cheque_fields" class="tw-hidden tw-space-y-3">
                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">N° Chèque</label>
                                            <input type="text" name="num_cheque" id="num_cheque"
                                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        </div>
                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Émetteur</label>
                                            <input type="text" name="emetteur_cheque" id="emetteur_cheque"
                                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        </div>
                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Banque</label>
                                            <input type="text" name="banque_cheque" id="banque_cheque"
                                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        </div>
                                    </div>

                                    {{-- BPC field --}}
                                    <div id="bpc_fields" class="tw-hidden">
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Émetteur BPC</label>
                                        <input type="text" name="emetteur_bpc" id="emetteur_bpc"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-flex tw-justify-end tw-gap-3">
                            <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-text-sm" data-bs-dismiss="modal">
                                <i class="fas fa-times tw-text-xs"></i> Fermer
                            </button>
                            <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-medium tw-px-6 tw-py-2.5 tw-border-0 tw-text-sm">
                                <i class="fas fa-save tw-text-xs"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endcan
    </div>
</div>

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ === 'undefined') { console.error('jQuery not loaded'); return; }

    $(document).ready(function() {
        // Tooltips
        $('[data-bs-toggle="tooltip"]').each(function() { new bootstrap.Tooltip(this); });

        // Modal: populate fields
        $('#edit_facture_modal').on('show.bs.modal', function(event) {
            var btn             = $(event.relatedTarget);
            var id              = btn.data('id-facture');
            var montant         = btn.data('montant');
            var reste           = btn.data('reste');
            var mode_paiement   = btn.data('mode_paiement');
            var prise_en_charge = btn.data('prise_en_charge');

            $('#montant').val(montant);
            $('#mode_paiement').val(mode_paiement);
            $('#reste').val(reste);

            var pec = isNaN(prise_en_charge) ? 0 : prise_en_charge;
            $('#montant').attr('data-prise_en_charge', pec);
            $('#part_patient').val(montant * (100 - pec) / 100);

            $('#edit_facture_form').attr('action', "{{ url('admin/factures-consultation') }}" + '/' + id);
        });

        // Auto-calc part patient
        $('#montant').on('change', function() {
            var pec     = $(this).data('prise_en_charge') || 0;
            var montant = $(this).val();
            $('#part_patient').val(montant * (100 - pec) / 100);
        });

        // Toggle conditional payment fields
        $('#mode_paiement').on('change', function() {
            var v = $(this).val();
            $('#cheque_fields').addClass('tw-hidden');
            $('#bpc_fields').addClass('tw-hidden');
            if (v === 'chèque')                  $('#cheque_fields').removeClass('tw-hidden');
            else if (v === 'bon de prise en charge') $('#bpc_fields').removeClass('tw-hidden');
        });

        // Auto-dismiss alerts
        setTimeout(function() { $('.tw-bg-teal-50, .tw-bg-red-50').fadeOut('slow'); }, 5000);

        // Statut filter
        $('#statut-filter').on('change', function() {
            var sel  = $(this).val();
            var rows = $('#myTable tbody tr');
            rows.each(function() {
                var badge      = $(this).find('td:last-child .tw-rounded-full');
                var isSoldee   = badge.hasClass('tw-bg-teal-100');
                var isNonSol   = badge.hasClass('tw-bg-amber-100');

                if (!sel)                                  $(this).show();
                else if (sel === 'soldée'    && isSoldee)  $(this).show();
                else if (sel === 'non soldée'&& isNonSol)  $(this).show();
                else                                       $(this).hide();
            });
        });
    });
});
</script>
@endsection
@endsection