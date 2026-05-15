@extends('layouts.admin')
@section('title', 'CMCU | Historique des Transactions')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Historique des Transactions</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Traçabilité complète des mouvements de stock</p>
                </div>
                <div class="tw-flex tw-items-center tw-gap-2">
                    <a href="{{ route('history.dashboard') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-white tw-text-slate-700 tw-text-sm tw-font-medium tw-rounded-lg hover:tw-bg-slate-50 tw-transition-colors tw-border tw-border-slate-200 tw-no-underline tw-shadow-sm">
                        <i class="fas fa-chart-bar tw-text-[#1D4ED8] tw-text-xs"></i>
                        Tableau de bord
                    </a>
                    {{-- Export dropdown --}}
                    <div class="tw-relative" id="exportWrapper">
                        <button id="exportBtn"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-lg hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer tw-shadow-sm">
                            <i class="fas fa-download tw-text-xs"></i>
                            Exporter
                            <i class="fas fa-chevron-down tw-text-[10px]"></i>
                        </button>
                        <div id="exportMenu"
                             class="tw-absolute tw-right-0 tw-top-full tw-mt-1 tw-w-44 tw-bg-white tw-rounded-xl tw-shadow-lg tw-border tw-border-slate-200 tw-py-1 tw-hidden tw-z-50">
                            <a href="{{ route('history.export-pdf') }}?{{ http_build_query(request()->except('page')) }}"
                               target="_blank"
                               class="tw-flex tw-items-center tw-gap-2.5 tw-px-4 tw-py-2.5 tw-text-sm tw-text-red-600 hover:tw-bg-red-50 tw-no-underline tw-transition-colors">
                                <i class="fas fa-file-pdf tw-text-xs"></i> PDF
                            </a>
                            <a href="{{ route('history.export-excel') }}?{{ http_build_query(request()->except('page')) }}"
                               class="tw-flex tw-items-center tw-gap-2.5 tw-px-4 tw-py-2.5 tw-text-sm tw-text-green-700 hover:tw-bg-green-50 tw-no-underline tw-transition-colors">
                                <i class="fas fa-file-excel tw-text-xs"></i> Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── STAT CARDS ─────────────────────────────────────────── --}}
            <div class="tw-grid tw-grid-cols-2 xl:tw-grid-cols-4 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-green-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-arrow-down tw-text-green-500 tw-text-sm"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Total Entrées</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $totalEntries ?? 0 }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-red-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-arrow-up tw-text-red-500 tw-text-sm"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Total Sorties</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $totalExits ?? 0 }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-exchange-alt tw-text-[#1D4ED8] tw-text-sm"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Transactions</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $totalTransactions ?? 0 }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-boxes tw-text-amber-500 tw-text-sm"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Produits distincts</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $distinctProducts ?? 0 }}</p>
                    </div>
                </div>
            </div>

            {{-- ── FILTERS ────────────────────────────────────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-6 tw-overflow-hidden" id="filterCard">
                <button type="button" id="filterToggle"
                        class="tw-w-full tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-text-sm tw-font-semibold tw-text-slate-700 tw-border-b tw-border-slate-100 hover:tw-bg-slate-50 tw-transition-colors tw-border-0 tw-bg-transparent tw-cursor-pointer tw-text-left">
                    <i class="fas fa-filter tw-text-[#1D4ED8] tw-text-xs"></i>
                    Filtres avancés
                    <i id="filterChevron" class="fas fa-chevron-down tw-text-xs tw-ml-auto tw-text-slate-400 tw-transition-transform tw-duration-200"></i>
                </button>

                <div id="filterBody" class="tw-p-6">
                    <form method="GET" action="{{ route('history.index') }}">
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-4 tw-mb-4">
                            {{-- Type --}}
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Type de transaction</label>
                                <select name="transaction_type"
                                        class="tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                    <option value="">Tous</option>
                                    <option value="reception"       {{ request('transaction_type') === 'reception' ? 'selected' : '' }}>Réception Stock</option>
                                    <option value="vente_pharmacie" {{ request('transaction_type') === 'vente_pharmacie' ? 'selected' : '' }}>Vente Pharmacie</option>
                                    <option value="vente_externe"   {{ request('transaction_type') === 'vente_externe' ? 'selected' : '' }}>Vente Externe</option>
                                    <option value="retour_reusable" {{ request('transaction_type') === 'retour_reusable' ? 'selected' : '' }}>Retour Réutilisable</option>
                                    <option value="adjustment"      {{ request('transaction_type') === 'adjustment' ? 'selected' : '' }}>Ajustement</option>
                                </select>
                            </div>
                            {{-- Direction --}}
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Direction</label>
                                <select name="direction"
                                        class="tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                    <option value="">Toutes</option>
                                    <option value="in"  {{ request('direction') === 'in' ? 'selected' : '' }}>Entrée</option>
                                    <option value="out" {{ request('direction') === 'out' ? 'selected' : '' }}>Sortie</option>
                                </select>
                            </div>
                            {{-- Date from --}}
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date début</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                       class="tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                            </div>
                            {{-- Date to --}}
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date fin</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                       class="tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                            </div>
                            {{-- Product search --}}
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Produit</label>
                                <input type="text" name="product_search" value="{{ request('product_search') }}"
                                       placeholder="Nom du produit..."
                                       class="tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                            </div>
                        </div>

                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4">
                            {{-- User --}}
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Utilisateur</label>
                                <select name="user_id"
                                        class="tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                    <option value="">Tous</option>
                                    @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Categorie --}}
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Catégorie</label>
                                <select name="categorie"
                                        class="tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                    <option value="">Toutes</option>
                                    <option value="MEDICAMENT"  {{ request('categorie') === 'MEDICAMENT' ? 'selected' : '' }}>Médicament</option>
                                    <option value="MATERIEL"    {{ request('categorie') === 'MATERIEL' ? 'selected' : '' }}>Matériel</option>
                                    <option value="CONSOMMABLE" {{ request('categorie') === 'CONSOMMABLE' ? 'selected' : '' }}>Consommable</option>
                                </select>
                            </div>
                            {{-- Actions --}}
                            <div class="sm:tw-col-span-2 tw-flex tw-items-end tw-gap-3">
                                <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-lg hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer tw-shadow-sm">
                                    <i class="fas fa-search tw-text-xs"></i>
                                    Rechercher
                                </button>
                                <a href="{{ route('history.index') }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-lg hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                                    <i class="fas fa-redo tw-text-xs"></i>
                                    Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── TABLE ──────────────────────────────────────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-history tw-text-[#1D4ED8] tw-text-xs"></i>
                    </div>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700">Transactions</h2>
                </div>

                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm tw-text-left">
                        <thead class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                            <tr>
                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-32">Date</th>
                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Type</th>
                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Produit</th>
                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-text-center tw-w-20">Qté</th>
                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-text-center tw-w-20">Dir.</th>
                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Référence</th>
                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Utilisateur</th>
                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-text-center">Stock</th>
                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-text-center tw-w-16"></th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @forelse($transactions ?? [] as $transaction)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors tw-duration-100">
                                {{-- Date --}}
                                <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-500 tw-whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}<br>
                                    <span class="tw-text-slate-400">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('H:i') }}</span>
                                </td>
                                {{-- Type badge --}}
                                <td class="tw-px-4 tw-py-3">
                                    @if($transaction->transaction_type === 'reception')
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2 tw-py-0.5 tw-rounded-full tw-text-[11px] tw-font-medium tw-bg-green-100 tw-text-green-700">
                                            <i class="fas fa-truck tw-text-[9px]"></i> Réception
                                        </span>
                                    @elseif($transaction->transaction_type === 'vente_pharmacie')
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2 tw-py-0.5 tw-rounded-full tw-text-[11px] tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">
                                            <i class="fas fa-prescription tw-text-[9px]"></i> Pharmacie
                                        </span>
                                    @elseif($transaction->transaction_type === 'vente_externe')
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2 tw-py-0.5 tw-rounded-full tw-text-[11px] tw-font-medium tw-bg-teal-100 tw-text-teal-700">
                                            <i class="fas fa-hospital tw-text-[9px]"></i> Externe
                                        </span>
                                    @elseif($transaction->transaction_type === 'retour_reusable')
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2 tw-py-0.5 tw-rounded-full tw-text-[11px] tw-font-medium tw-bg-amber-100 tw-text-amber-700">
                                            <i class="fas fa-recycle tw-text-[9px]"></i> Retour
                                        </span>
                                    @else
                                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-[11px] tw-font-medium tw-bg-slate-100 tw-text-slate-600">
                                            {{ $transaction->transaction_type }}
                                        </span>
                                    @endif
                                </td>
                                {{-- Product --}}
                                <td class="tw-px-4 tw-py-3">
                                    <p class="tw-font-medium tw-text-slate-800 tw-mb-0 tw-text-sm">{{ $transaction->produit->designation ?? 'N/A' }}</p>
                                    <p class="tw-text-xs tw-text-slate-400 tw-mb-0">{{ $transaction->produit->categorie ?? '' }}</p>
                                </td>
                                {{-- Qty --}}
                                <td class="tw-px-4 tw-py-3 tw-text-center tw-font-bold {{ $transaction->direction === 'in' ? 'tw-text-green-600' : 'tw-text-red-500' }}">
                                    {{ $transaction->direction === 'in' ? '+' : '-' }}{{ abs($transaction->quantite) }}
                                </td>
                                {{-- Direction --}}
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    @if($transaction->direction === 'in')
                                    <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-6 tw-h-6 tw-rounded-full tw-bg-green-100" title="Entrée">
                                        <i class="fas fa-arrow-down tw-text-green-600 tw-text-[10px]"></i>
                                    </span>
                                    @else
                                    <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-6 tw-h-6 tw-rounded-full tw-bg-red-100" title="Sortie">
                                        <i class="fas fa-arrow-up tw-text-red-500 tw-text-[10px]"></i>
                                    </span>
                                    @endif
                                </td>
                                {{-- Reference --}}
                                <td class="tw-px-4 tw-py-3 tw-text-xs">
                                    @if($transaction->related_model && $transaction->related_id)
                                    <p class="tw-text-slate-400 tw-mb-0">{{ $transaction->related_model }}</p>
                                    <p class="tw-font-semibold tw-text-slate-700 tw-mb-0">#{{ $transaction->related_id }}</p>
                                    @else
                                    <span class="tw-text-slate-300">—</span>
                                    @endif
                                </td>
                                {{-- User --}}
                                <td class="tw-px-4 tw-py-3 tw-text-sm tw-text-slate-700">{{ $transaction->user->name ?? 'Système' }}</td>
                                {{-- Stock before/after --}}
                                <td class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-text-slate-500">
                                    <span class="tw-font-medium tw-text-slate-600">{{ $transaction->stock_before ?? 0 }}</span>
                                    <i class="fas fa-arrow-right tw-text-[9px] tw-mx-1 tw-text-slate-300"></i>
                                    <span class="tw-font-medium tw-text-slate-600">{{ $transaction->stock_after ?? 0 }}</span>
                                </td>
                                {{-- Action --}}
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <a href="{{ route('history.show', $transaction->id) }}"
                                       title="Voir les détails"
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-text-[#1D4ED8] hover:tw-bg-[#1D4ED8] hover:tw-text-white tw-transition-colors tw-no-underline">
                                        <i class="fas fa-eye tw-text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="tw-px-6 tw-py-12 tw-text-center">
                                    <div class="tw-flex tw-flex-col tw-items-center tw-gap-3">
                                        <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fas fa-inbox tw-text-slate-400 tw-text-xl"></i>
                                        </div>
                                        <p class="tw-text-slate-500 tw-text-sm tw-mb-0">Aucune transaction trouvée</p>
                                        <a href="{{ route('history.index') }}" class="tw-text-[#1D4ED8] tw-text-sm tw-no-underline hover:tw-underline">
                                            Réinitialiser les filtres
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(isset($transactions) && $transactions->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $transactions->appends(request()->except('page'))->links() }}
                </div>
                @endif

            </div>

        </main>
    </div>
</div>

<script>
(function() {
    var btn    = document.getElementById('exportBtn');
    var menu   = document.getElementById('exportMenu');
    var wrap   = document.getElementById('exportWrapper');
    if (btn && menu) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('tw-hidden');
        });
        document.addEventListener('click', function(e) {
            if (!wrap.contains(e.target)) menu.classList.add('tw-hidden');
        });
    }

    var toggle  = document.getElementById('filterToggle');
    var body    = document.getElementById('filterBody');
    var chevron = document.getElementById('filterChevron');
    if (toggle && body) {
        toggle.addEventListener('click', function() {
            var hidden = body.style.display === 'none';
            body.style.display = hidden ? '' : 'none';
            chevron.style.transform = hidden ? 'rotate(180deg)' : '';
        });
    }
})();
</script>
@stop