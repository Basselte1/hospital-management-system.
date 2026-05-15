@extends('layouts.admin')
@section('title', 'CMCU | Nouvelle Vente Patient')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Header --}}
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-flex tw-items-center tw-gap-2">
                        <span class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-user-injured tw-text-[#1D4ED8] tw-text-base"></i>
                        </span>
                        Nouvelle Vente Patient
                    </h1>
                    <p class="tw-text-sm tw-text-slate-400 tw-mt-1">Sélectionnez un patient et ajoutez les produits à facturer</p>
                </div>
                <a href="{{ route('pharmacie.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-rounded-xl tw-bg-[#14B8A6] tw-text-white tw-text-sm tw-font-medium hover:tw-bg-teal-600 tw-transition-colors tw-duration-150 tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour à la pharmacie
                </a>
            </div>

            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-p-6">

                    {{-- ── PATIENT SEARCH (no patient selected) ──────────── --}}
                    @if(!$patient)
                    <div class="tw-bg-slate-50 tw-rounded-2xl tw-border tw-border-slate-200 tw-p-6 tw-mb-2">
                        <h2 class="tw-text-sm tw-font-semibold tw-uppercase tw-tracking-widest tw-text-slate-400 tw-mb-4 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-search tw-text-[#1D4ED8]"></i> Rechercher un Patient
                        </h2>

                        <div class="tw-relative">
                            <span class="tw-absolute tw-inset-y-0 tw-left-4 tw-flex tw-items-center tw-pointer-events-none">
                                <i class="fas fa-search tw-text-slate-400 tw-text-sm"></i>
                            </span>
                            <input type="text"
                                   id="patient-search"
                                   class="tw-w-full tw-pl-11 tw-pr-4 tw-py-3 tw-rounded-xl tw-bg-white tw-border tw-border-slate-200 focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-outline-none tw-text-slate-700 tw-text-sm tw-transition-all tw-duration-150"
                                   placeholder="Cliquez ici pour voir tous les patients ou tapez pour rechercher..."
                                   autocomplete="off">
                        </div>
                        <p class="tw-text-xs tw-text-slate-400 tw-mt-2">
                            💡 Cliquez dans le champ pour voir tous les patients récents, ou tapez au moins 2 caractères
                        </p>

                        {{-- Loading --}}
                        <div id="search-loading" class="tw-mt-4 tw-text-center tw-hidden">
                            <div class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500">
                                <svg class="tw-animate-spin tw-w-4 tw-h-4 tw-text-[#1D4ED8]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Chargement des patients...
                            </div>
                        </div>

                        {{-- No results --}}
                        <div id="no-results" class="tw-mt-4 tw-hidden tw-bg-amber-50 tw-border tw-border-amber-200 tw-rounded-xl tw-px-4 tw-py-3 tw-text-sm tw-text-amber-700 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-info-circle"></i>
                            Aucun patient trouvé. Vérifiez l'orthographe ou essayez avec le numéro de dossier.
                        </div>

                        {{-- Results container --}}
                        <div id="patient-results-container" class="tw-mt-4 tw-hidden" style="max-height: 480px; overflow-y: auto;">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-3">
                                <span class="tw-text-xs tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-semibold tw-px-2.5 tw-py-1 tw-rounded-full">
                                    <span id="results-count">0</span> patient(s)
                                </span>
                            </div>
                            <div id="patient-results" class="tw-space-y-2"></div>
                            <div id="load-more-indicator" class="tw-text-center tw-py-3 tw-hidden">
                                <div class="tw-inline-flex tw-items-center tw-gap-2 tw-text-xs tw-text-slate-400">
                                    <svg class="tw-animate-spin tw-w-3 tw-h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Chargement de plus de patients...
                                </div>
                            </div>
                        </div>
                    </div>

                    @else

                    {{-- ── PATIENT SELECTED ────────────────────────────── --}}
                    <div class="tw-flex tw-items-center tw-justify-between tw-bg-[#1D4ED8] tw-rounded-2xl tw-px-5 tw-py-4 tw-mb-6">
                        <div class="tw-flex tw-items-center tw-gap-4">
                            <div class="tw-w-12 tw-h-12 tw-rounded-full tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <span class="tw-text-white tw-font-bold tw-text-lg tw-uppercase">{{ mb_substr($patient->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="tw-text-white tw-font-semibold tw-text-base tw-mb-0 tw-leading-tight">
                                    {{ $patient->name }} {{ $patient->prenom }}
                                </p>
                                <p class="tw-text-white/70 tw-text-xs tw-mt-0.5 tw-mb-0">
                                    Dossier N° <span class="tw-text-white tw-font-mono tw-font-semibold">{{ $patient->numero_dossier }}</span>
                                    @if($patient->telephone)
                                    &nbsp;·&nbsp; <i class="fas fa-phone tw-text-xs"></i> {{ $patient->telephone }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('pharmacie.sales.patient.create') }}"
                           class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3.5 tw-py-2 tw-rounded-xl tw-bg-white/20 tw-text-white tw-text-xs tw-font-medium hover:tw-bg-white/30 tw-transition-colors tw-duration-150 tw-no-underline tw-border tw-border-white/30">
                            <i class="fas fa-times tw-text-xs"></i> Changer
                        </a>
                    </div>

                    {{-- ── ORDONNANCES ─────────────────────────────────── --}}
                    @if($patient->ordonances && $patient->ordonances->count() > 0)
                    <div class="tw-mb-6">
                        <h2 class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-slate-400 tw-mb-3 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-file-prescription tw-text-[#1D4ED8]"></i>
                            Ordonnances du Patient
                        </h2>
                        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-3">
                            @foreach($patient->ordonances->take(5) as $ord)
                            <div class="tw-rounded-xl tw-border tw-p-4 tw-transition-all tw-duration-150 {{ $ordonance && $ordonance->id == $ord->id ? 'tw-border-[#1D4ED8] tw-bg-[#BFDBFE]/10' : 'tw-border-slate-200 tw-bg-white hover:tw-border-slate-300' }}">
                                <div class="tw-flex tw-items-start tw-justify-between tw-gap-3">
                                    <div class="tw-min-w-0">
                                        <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">
                                            {{ $ord->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                        <p class="tw-text-xs tw-text-slate-400 tw-mt-0.5 tw-mb-0">
                                            Par : {{ $ord->user->name ?? 'N/A' }}
                                        </p>
                                        @if($ord->description)
                                        <p class="tw-text-xs tw-text-slate-500 tw-mt-1 tw-mb-0 tw-truncate">
                                            {{ \Illuminate\Support\Str::limit($ord->description, 55) }}
                                        </p>
                                        @endif
                                    </div>
                                    @if(!$ordonance || $ordonance->id != $ord->id)
                                    <a href="{{ route('pharmacie.sales.patient.create', ['patient_id' => $patient->id, 'ordonance_id' => $ord->id]) }}"
                                       class="tw-shrink-0 tw-inline-flex tw-items-center tw-gap-1 tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-[#1D4ED8] tw-text-white tw-text-xs tw-font-medium hover:tw-bg-[#1a46c5] tw-transition-colors tw-no-underline">
                                        <i class="fas fa-check tw-text-xs"></i> Utiliser
                                    </a>
                                    @else
                                    <span class="tw-shrink-0 tw-inline-flex tw-items-center tw-gap-1 tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-teal-100 tw-text-teal-700 tw-text-xs tw-font-semibold">
                                        <i class="fas fa-check-circle"></i> Sélectionnée
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="tw-flex tw-items-center tw-gap-3 tw-bg-sky-50 tw-border tw-border-sky-200 tw-rounded-xl tw-px-4 tw-py-3 tw-mb-6">
                        <i class="fas fa-info-circle tw-text-sky-500"></i>
                        <p class="tw-text-sm tw-text-sky-700 tw-mb-0">Ce patient n'a pas d'ordonnance enregistrée. Vous pouvez créer une vente directe.</p>
                    </div>
                    @endif

                    {{-- ── SALE FORM ────────────────────────────────────── --}}
                    <form action="{{ route('pharmacie.sales.patient.store') }}" method="POST" id="sale-form">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        @if($ordonance)
                        <input type="hidden" name="ordonance_id" value="{{ $ordonance->id }}">
                        <div class="tw-flex tw-items-center tw-gap-3 tw-bg-[#BFDBFE]/30 tw-border tw-border-[#BFDBFE] tw-rounded-xl tw-px-4 tw-py-3 tw-mb-4">
                            <i class="fas fa-file-prescription tw-text-[#1D4ED8]"></i>
                            <p class="tw-text-sm tw-text-[#1D4ED8] tw-font-medium tw-mb-0">
                                Ordonnance utilisée : <span class="tw-font-semibold">{{ $ordonance->created_at->format('d/m/Y à H:i') }}</span>
                            </p>
                        </div>
                        @endif

                        {{-- Suggested products banner --}}
                        @if(!empty($suggestedProducts) && count($suggestedProducts) > 0)
                        <div class="tw-flex tw-items-center tw-gap-3 tw-bg-amber-50 tw-border tw-border-amber-200 tw-rounded-xl tw-px-4 tw-py-3 tw-mb-4">
                            <i class="fas fa-lightbulb tw-text-amber-500"></i>
                            <p class="tw-text-sm tw-text-amber-700 tw-font-medium tw-mb-0">
                                <strong>{{ count($suggestedProducts) }} produit(s) suggéré(s)</strong> depuis l'ordonnance
                            </p>
                        </div>
                        @endif

                        {{-- Section title --}}
                        <h2 class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-slate-400 tw-mb-4 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-pills tw-text-[#1D4ED8]"></i> Produits à Vendre
                        </h2>

                        {{-- Items container --}}
                        <div id="items-container" class="tw-space-y-3">
                            @if(!empty($suggestedProducts) && count($suggestedProducts) > 0)
                                @foreach($suggestedProducts as $index => $suggested)
                                <div class="item-row tw-bg-slate-50 tw-border {{ $suggested['status'] === 'out_of_stock' ? 'tw-border-red-300 tw-bg-red-50' : ($suggested['status'] === 'not_found' ? 'tw-border-amber-300 tw-bg-amber-50' : 'tw-border-slate-200') }} tw-rounded-xl tw-p-4 tw-grid tw-grid-cols-12 tw-gap-3 tw-items-end">
                                    <div class="tw-col-span-5">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">
                                            Produit <span class="tw-text-red-500">*</span>
                                            @if($suggested['status'] === 'not_found')
                                                <span class="tw-ml-1 tw-bg-amber-100 tw-text-amber-700 tw-text-[10px] tw-font-bold tw-px-1.5 tw-py-0.5 tw-rounded-md">
                                                    ⚠ "{{ $suggested['medicament_name'] }}" non trouvé
                                                </span>
                                            @elseif($suggested['status'] === 'out_of_stock')
                                                <span class="tw-ml-1 tw-bg-red-100 tw-text-red-700 tw-text-[10px] tw-font-bold tw-px-1.5 tw-py-0.5 tw-rounded-md">
                                                    ⚠ Stock épuisé
                                                </span>
                                            @elseif($suggested['status'] === 'low_stock')
                                                <span class="tw-ml-1 tw-bg-orange-100 tw-text-orange-700 tw-text-[10px] tw-font-bold tw-px-1.5 tw-py-0.5 tw-rounded-md">
                                                    ⚠ Stock faible ({{ $suggested['product']->qte_stock }} dispo)
                                                </span>
                                            @else
                                                <span class="tw-ml-1 tw-bg-amber-100 tw-text-amber-700 tw-text-[10px] tw-font-bold tw-px-1.5 tw-py-0.5 tw-rounded-md">Suggéré</span>
                                            @endif
                                        </label>
                                        <select name="items[{{ $index }}][produit_id]" class="product-select tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-outline-none" required>
                                            <option value="">Sélectionner...</option>
                                            @foreach($produits as $p)
                                            <option value="{{ $p->id }}"
                                                    data-prix="{{ $p->prix_unitaire }}"
                                                    data-stock="{{ $p->qte_stock }}"
                                                    {{ ($suggested['product'] && $suggested['product']->id == $p->id) ? 'selected' : '' }}>
                                                {{ $p->designation }} (Stock: {{ $p->qte_stock }}) — {{ number_format($p->prix_unitaire) }} FCFA
                                            </option>
                                            @endforeach
                                        </select>
                                        @if(!empty($suggested['description']))
                                        <p class="tw-text-[10px] tw-text-slate-400 tw-mt-1 tw-mb-0">
                                            <i class="fas fa-info-circle"></i> {{ $suggested['description'] }}
                                        </p>
                                        @endif
                                    </div>
                                    <div class="tw-col-span-2">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Quantité <span class="tw-text-red-500">*</span></label>
                                        <input type="number" name="items[{{ $index }}][quantite]" class="quantity-input tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-outline-none" value="{{ $suggested['suggested_qty'] }}" min="1" required>
                                        @if($suggested['prescribed_qty'] != $suggested['suggested_qty'])
                                        <p class="tw-text-[10px] tw-text-orange-500 tw-mt-1 tw-mb-0">Prescrit: {{ $suggested['prescribed_qty'] }}</p>
                                        @endif
                                    </div>
                                    <div class="tw-col-span-2">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Prix Unit.</label>
                                        <input type="text" class="prix-display tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500" readonly>
                                    </div>
                                    <div class="tw-col-span-2">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Total</label>
                                        <input type="text" class="total-display tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500 tw-font-semibold" readonly>
                                    </div>
                                    <div class="tw-col-span-1 tw-flex tw-justify-end">
                                        <button type="button" class="remove-item tw-w-8 tw-h-9 tw-flex tw-items-center tw-justify-center tw-rounded-xl tw-bg-red-50 tw-text-red-500 hover:tw-bg-red-100 tw-border-0 tw-transition-colors">
                                            <i class="fas fa-trash tw-text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="item-row tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-xl tw-p-4 tw-grid tw-grid-cols-12 tw-gap-3 tw-items-end">
                                    <div class="tw-col-span-5">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Produit <span class="tw-text-red-500">*</span></label>
                                        <select name="items[0][produit_id]" class="product-select tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-outline-none" required>
                                            <option value="">Sélectionner...</option>
                                            @foreach($produits as $p)
                                            <option value="{{ $p->id }}" data-prix="{{ $p->prix_unitaire }}" data-stock="{{ $p->qte_stock }}">
                                                {{ $p->designation }} (Stock: {{ $p->qte_stock }}) — {{ number_format($p->prix_unitaire) }} FCFA
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tw-col-span-2">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Quantité <span class="tw-text-red-500">*</span></label>
                                        <input type="number" name="items[0][quantite]" class="quantity-input tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-outline-none" value="1" min="1" required>
                                    </div>
                                    <div class="tw-col-span-2">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Prix Unit.</label>
                                        <input type="text" class="prix-display tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500" readonly>
                                    </div>
                                    <div class="tw-col-span-2">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Total</label>
                                        <input type="text" class="total-display tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500 tw-font-semibold" readonly>
                                    </div>
                                    <div class="tw-col-span-1 tw-flex tw-justify-end">
                                        <button type="button" class="remove-item tw-w-8 tw-h-9 tw-flex tw-items-center tw-justify-center tw-rounded-xl tw-bg-red-50 tw-text-red-500 hover:tw-bg-red-100 tw-border-0 tw-transition-colors" disabled>
                                            <i class="fas fa-trash tw-text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Add product button --}}
                        <button type="button" id="add-item"
                                class="tw-w-full tw-mt-3 tw-py-3 tw-rounded-xl tw-border-2 tw-border-dashed tw-border-slate-300 tw-text-slate-400 hover:tw-border-[#1D4ED8] hover:tw-text-[#1D4ED8] tw-transition-colors tw-duration-150 tw-text-sm tw-font-medium tw-bg-transparent">
                            <i class="fas fa-plus tw-mr-2"></i> Ajouter un produit
                        </button>

                        {{-- Grand total --}}
                        <div class="tw-mt-4 tw-bg-[#0F2554] tw-rounded-2xl tw-px-6 tw-py-4 tw-flex tw-items-center tw-justify-between">
                            <span class="tw-text-white/70 tw-text-sm tw-font-medium tw-uppercase tw-tracking-widest">Total de la Vente</span>
                            <span id="grand-total" class="tw-text-white tw-text-2xl tw-font-bold">0 FCFA</span>
                        </div>

                        {{-- Action buttons --}}
                        <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-mt-6 tw-pt-6 tw-border-t tw-border-slate-100">
                            <a href="{{ route('pharmacie.index') }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-rounded-xl tw-bg-slate-100 tw-text-slate-600 hover:tw-bg-slate-200 tw-text-sm tw-font-medium tw-transition-colors tw-no-underline">
                                <i class="fas fa-times tw-text-xs"></i> Annuler
                            </a>
                            <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-rounded-xl tw-bg-[#1D4ED8] tw-text-white hover:tw-bg-[#1a46c5] tw-text-sm tw-font-semibold tw-transition-colors tw-duration-150 tw-border-0">
                                <i class="fas fa-check tw-text-xs"></i> Créer la Facture
                            </button>
                        </div>
                    </form>
                    @endif

                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
waitForjQuery(function() {

$(document).ready(function() {
    console.log('=== Patient Search Script Initialized ===');

    // ============================================
    // VARIABLES
    // ============================================
    let isLoadingPatients = false;
    let patientPage = 1;
    let hasMorePatients = true;
    let searchTimeout = null;
    let itemCounter = {{ !empty($suggestedProducts) ? count($suggestedProducts) : 1 }};

    // ============================================
    // PATIENT SEARCH INITIALIZATION
    // ============================================

    @if(!$patient)
    $('#patient-search').on('focus', function() {
        let currentValue = $(this).val().trim();
        if (currentValue === '' && $('#patient-results').children().length === 0) {
            loadAllPatients();
        } else if (currentValue !== '') {
            $('#patient-results-container').removeClass('tw-hidden');
        }
    });

    $('#patient-search').on('input', function() {
        let query = $(this).val().trim();
        if (searchTimeout) clearTimeout(searchTimeout);
        patientPage = 1;
        hasMorePatients = true;

        if (query === '') {
            searchTimeout = setTimeout(function() { loadAllPatients(); }, 300);
        } else if (query.length >= 2) {
            searchTimeout = setTimeout(function() { performPatientSearch(query); }, 500);
        } else {
            $('#patient-results-container').addClass('tw-hidden');
            $('#no-results').addClass('tw-hidden');
        }
    });

    function loadAllPatients() {
        if (isLoadingPatients) return;
        isLoadingPatients = true;
        $('#search-loading').removeClass('tw-hidden');
        $('#no-results').addClass('tw-hidden');
        if (patientPage === 1) $('#patient-results').empty();

        $.ajax({
            url: '{{ route("pharmacie.search-patient") }}',
            method: 'GET',
            data: { q: '', page: patientPage },
            success: function(response) {
                $('#search-loading').addClass('tw-hidden');
                if (response.length === 0 && patientPage === 1) {
                    $('#no-results').removeClass('tw-hidden');
                    $('#patient-results-container').addClass('tw-hidden');
                } else {
                    displayPatients(response);
                    $('#patient-results-container').removeClass('tw-hidden');
                    if (response.length < 50) hasMorePatients = false;
                }
                $('#load-more-indicator').addClass('tw-hidden');
                isLoadingPatients = false;
            },
            error: function(xhr) {
                $('#search-loading').addClass('tw-hidden');
                $('#load-more-indicator').addClass('tw-hidden');
                let msg = xhr.responseJSON?.message ?? 'Veuillez rafraîchir la page.';
                $('#no-results').html('<i class="fas fa-exclamation-triangle"></i> Erreur: ' + msg).removeClass('tw-hidden');
                isLoadingPatients = false;
            }
        });
    }

    function performPatientSearch(query) {
        if (isLoadingPatients) return;
        isLoadingPatients = true;
        $('#search-loading').removeClass('tw-hidden');
        $('#no-results').addClass('tw-hidden');
        if (patientPage === 1) $('#patient-results').empty();

        $.ajax({
            url: '{{ route("pharmacie.search-patient") }}',
            method: 'GET',
            data: { q: query },
            success: function(response) {
                $('#search-loading').addClass('tw-hidden');
                if (response.length === 0) {
                    $('#no-results').removeClass('tw-hidden');
                    $('#patient-results-container').addClass('tw-hidden');
                } else {
                    displayPatients(response);
                    $('#patient-results-container').removeClass('tw-hidden');
                }
                isLoadingPatients = false;
            },
            error: function(xhr) {
                $('#search-loading').addClass('tw-hidden');
                let msg = xhr.responseJSON?.message ?? 'Veuillez réessayer.';
                $('#no-results').html('<i class="fas fa-exclamation-triangle"></i> Erreur: ' + msg).removeClass('tw-hidden');
                isLoadingPatients = false;
            }
        });
    }

    function displayPatients(patients) {
        if (!Array.isArray(patients)) return;
        let currentCount = $('#patient-results').children().length;
        $('#results-count').text(currentCount + patients.length);

        patients.forEach(function(patient) {
            let ordonancesHtml = patient.ordonances && patient.ordonances.length > 0
                ? `<span class="tw-text-xs tw-text-teal-600 tw-font-medium"><i class="fas fa-file-prescription"></i> ${patient.ordonances.length} ordonnance(s)</span>`
                : `<span class="tw-text-xs tw-text-slate-400"><i class="fas fa-info-circle"></i> Aucune ordonnance</span>`;

            let cardHtml = `
                <div class="patient-card tw-bg-white tw-border tw-border-slate-200 hover:tw-border-[#1D4ED8] tw-rounded-xl tw-px-4 tw-py-3 tw-flex tw-items-center tw-justify-between tw-gap-3 tw-cursor-pointer tw-transition-all tw-duration-150 hover:tw-shadow-sm">
                    <div class="tw-flex tw-items-center tw-gap-3 tw-min-w-0">
                        <div class="tw-w-9 tw-h-9 tw-rounded-full tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <span class="tw-text-[#1D4ED8] tw-font-bold tw-text-sm tw-uppercase">${patient.name.charAt(0)}</span>
                        </div>
                        <div class="tw-min-w-0">
                            <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">${patient.name} ${patient.prenom}</p>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0">
                                <span class="tw-font-mono">Dossier: ${patient.numero_dossier}</span>
                                ${patient.telephone ? ' · ' + patient.telephone : ''}
                            </p>
                            <div class="tw-mt-0.5">${ordonancesHtml}</div>
                        </div>
                    </div>
                    <a href="{{ route('pharmacie.sales.patient.create') }}?patient_id=${patient.id}"
                       class="tw-shrink-0 tw-inline-flex tw-items-center tw-gap-1 tw-px-3.5 tw-py-2 tw-rounded-xl tw-bg-[#1D4ED8] tw-text-white tw-text-xs tw-font-semibold hover:tw-bg-[#1a46c5] tw-transition-colors tw-no-underline">
                        <i class="fas fa-check"></i> Sélectionner
                    </a>
                </div>`;

            $('#patient-results').append(cardHtml);
        });
    }

    $('#patient-results-container').on('scroll', function() {
        let container = $(this);
        if (container.scrollTop() + container.height() >= container[0].scrollHeight - 50
            && hasMorePatients && !isLoadingPatients) {
            patientPage++;
            $('#load-more-indicator').removeClass('tw-hidden');
            let q = $('#patient-search').val().trim();
            q ? performPatientSearch(q) : loadAllPatients();
        }
    });
    @endif

    // ============================================
    // PRODUCT MANAGEMENT
    // ============================================

    $('#add-item').click(function() { addProductRow(); });

    function addProductRow() {
        let html = `
        <div class="item-row tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-xl tw-p-4 tw-grid tw-grid-cols-12 tw-gap-3 tw-items-end">
            <div class="tw-col-span-5">
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Produit <span class="tw-text-red-500">*</span></label>
                <select name="items[${itemCounter}][produit_id]" class="product-select tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-outline-none" required>
                    <option value="">Sélectionner...</option>
                    @foreach($produits as $p)
                    <option value="{{ $p->id }}" data-prix="{{ $p->prix_unitaire }}" data-stock="{{ $p->qte_stock }}">
                        {{ $p->designation }} (Stock: {{ $p->qte_stock }}) — {{ number_format($p->prix_unitaire) }} FCFA
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="tw-col-span-2">
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Quantité <span class="tw-text-red-500">*</span></label>
                <input type="number" name="items[${itemCounter}][quantite]" class="quantity-input tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-outline-none" value="1" min="1" required>
            </div>
            <div class="tw-col-span-2">
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Prix Unit.</label>
                <input type="text" class="prix-display tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500" readonly>
            </div>
            <div class="tw-col-span-2">
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Total</label>
                <input type="text" class="total-display tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500 tw-font-semibold" readonly>
            </div>
            <div class="tw-col-span-1 tw-flex tw-justify-end">
                <button type="button" class="remove-item tw-w-8 tw-h-9 tw-flex tw-items-center tw-justify-center tw-rounded-xl tw-bg-red-50 tw-text-red-500 hover:tw-bg-red-100 tw-border-0 tw-transition-colors">
                    <i class="fas fa-trash tw-text-xs"></i>
                </button>
            </div>
        </div>`;
        $('#items-container').append(html);
        itemCounter++;
    }

    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            calculateTotal();
        } else {
            alert('Il faut au moins un produit');
        }
    });

    $(document).on('change', '.product-select', function() {
        let selected = $(this).find(':selected');
        let prix = selected.data('prix');
        let stock = selected.data('stock');
        let row = $(this).closest('.item-row');
        row.find('.prix-display').val(prix ? prix.toLocaleString() : '');
        row.find('.quantity-input').attr('max', stock);
        calculateRowTotal(row);
    });

    $(document).on('input', '.quantity-input', function() {
        let row = $(this).closest('.item-row');
        let max = parseInt($(this).attr('max'));
        let val = parseInt($(this).val());
        if (max && val > max) {
            alert('Stock insuffisant! Maximum disponible: ' + max);
            $(this).val(max);
        }
        calculateRowTotal(row);
    });

    function calculateRowTotal(row) {
        let prix = parseInt(row.find('.product-select :selected').data('prix')) || 0;
        let qty = parseInt(row.find('.quantity-input').val()) || 0;
        let total = prix * qty;
        row.find('.total-display').val(total.toLocaleString());
        calculateTotal();
    }

    function calculateTotal() {
        let grandTotal = 0;
        $('.item-row').each(function() {
            let prix = parseInt($(this).find('.product-select :selected').data('prix')) || 0;
            let qty = parseInt($(this).find('.quantity-input').val()) || 0;
            grandTotal += prix * qty;
        });
        $('#grand-total').text(grandTotal.toLocaleString() + ' FCFA');
    }

    $('#sale-form').submit(function(e) {
        let hasValidProduct = false;
        $('.product-select').each(function() { if ($(this).val()) hasValidProduct = true; });
        if (!hasValidProduct) {
            e.preventDefault();
            alert('Veuillez sélectionner au moins un produit');
            return false;
        }
        let stockError = false;
        $('.item-row').each(function() {
            let qty = parseInt($(this).find('.quantity-input').val()) || 0;
            let max = parseInt($(this).find('.quantity-input').attr('max')) || 0;
            if (qty > max) stockError = true;
        });
        if (stockError) {
            e.preventDefault();
            alert('Veuillez vérifier les quantités - stock insuffisant');
            return false;
        }
    });

    @if($patient)
    $('.item-row').each(function() {
        let row = $(this);
        let selected = row.find('.product-select :selected');
        row.find('.prix-display').val(selected.data('prix') ? selected.data('prix').toLocaleString() : '');
        row.find('.quantity-input').attr('max', selected.data('stock'));
        calculateRowTotal(row);
    });
    @endif

    console.log('=== Script Fully Loaded ===');
});

});
</script>
@endpush
@endsection