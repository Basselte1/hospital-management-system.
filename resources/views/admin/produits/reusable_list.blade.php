@extends('layouts.admin')
@section('title', 'CMCU | Produits Réutilisables')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-2xl tw-mx-auto">

                {{-- ── Page Header ──────────────────────────────────────── --}}
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-8">
                    <div>
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                            <span class="tw-text-xs tw-font-semibold tw-tracking-widest tw-text-teal-700 tw-uppercase tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-2.5 tw-py-1 tw-rounded-full">
                                Stock
                            </span>
                        </div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-tracking-tight tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-recycle tw-text-[#14B8A6]"></i>
                            Produits Réutilisables
                        </h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-0.5">Liste complète des produits marqués comme réutilisables</p>
                    </div>

                    <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                        <a href="{{ route('produits.index') }}"
                           class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-rounded-xl tw-border tw-border-slate-200 tw-transition-all tw-duration-150 tw-no-underline">
                            <i class="fas fa-arrow-left tw-text-xs"></i>
                            Liste générale
                        </a>
                        @if(in_array(auth()->user()->role_id, [1, 4, 5, 7]))
                        <a href="{{ route('reusable-products.index') }}"
                           class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-[#14B8A6] hover:tw-bg-[#0d9488] tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-rounded-xl tw-transition-all tw-duration-150 tw-no-underline">
                            <i class="fas fa-recycle tw-text-xs"></i>
                            Gestion des réutilisables
                        </a>
                        @endif
                    </div>
                </div>

                {{-- ── Flash message ────────────────────────────────────── --}}
                @if(session('success'))
                <div class="tw-flex tw-items-start tw-gap-3 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-text-emerald-800 tw-rounded-xl tw-px-5 tw-py-4 tw-mb-6 tw-text-sm">
                    <i class="fas fa-check-circle tw-text-emerald-500 tw-mt-0.5 tw-shrink-0"></i>
                    <p class="tw-mb-0">{{ session('success') }}</p>
                    <button type="button"
                            class="tw-ml-auto tw-text-emerald-400 hover:tw-text-emerald-600 tw-border-0 tw-bg-transparent tw-cursor-pointer"
                            data-bs-dismiss="alert">
                        <i class="fas fa-times tw-text-xs"></i>
                    </button>
                </div>
                @endif

                {{-- ── KPI Cards ─────────────────────────────────────────── --}}
                <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-mb-8">

                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-teal-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-recycle tw-text-[#14B8A6] tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-500 tw-font-medium tw-mb-0.5">Total Réutilisables</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ $stats['total'] }}</p>
                        </div>
                    </div>

                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-emerald-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-check-circle tw-text-emerald-500 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-emerald-600 tw-font-medium tw-mb-0.5">Disponibles</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ $stats['disponible'] }}</p>
                        </div>
                    </div>

                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-amber-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-procedures tw-text-amber-500 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-amber-600 tw-font-medium tw-mb-0.5">En Utilisation</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ $stats['en_utilisation'] }}</p>
                        </div>
                    </div>

                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-sky-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-sky-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-flask tw-text-sky-500 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-sky-600 tw-font-medium tw-mb-0.5">En Stérilisation</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ $stats['en_sterilisation'] }}</p>
                        </div>
                    </div>

                </div>

                {{-- ── Table Card ────────────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">

                    {{-- Card header --}}
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                        <div class="tw-flex tw-items-center tw-gap-2.5">
                            <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-[#14B8A6]"></div>
                            <span class="tw-text-sm tw-font-semibold tw-text-slate-700">Tous les produits réutilisables</span>
                        </div>
                        <span class="tw-text-xs tw-text-slate-400">{{ $reusableProducts->total() }} produit(s)</span>
                    </div>

                    {{-- Table --}}
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm">
                            <thead>
                                <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-200">
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Produit</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Catégorie</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Stock Total</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Disponible</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">En Utilisation</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">En Stérilisation</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Méthode Stéril.</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Temp.</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Durée</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                @forelse($reusableProducts as $produit)
                                @php
                                    $disponible = $produit->qte_stock - $produit->qte_en_utilisation - $produit->qte_en_sterilisation;

                                    $catColors = [
                                        'PHARMACEUTIQUE' => 'tw-bg-[#BFDBFE] tw-text-[#1D4ED8]',
                                        'MATERIEL'       => 'tw-bg-slate-100 tw-text-slate-600',
                                        'ANESTHESISTE'   => 'tw-bg-teal-100 tw-text-teal-700',
                                    ];
                                @endphp
                                <tr class="hover:tw-bg-slate-50/70 tw-transition-colors tw-duration-100">

                                    {{-- Produit --}}
                                    <td class="tw-px-5 tw-py-3.5">
                                        <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $produit->designation }}</p>
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-text-[10px] tw-font-semibold tw-text-teal-700 tw-bg-teal-50 tw-rounded-full tw-px-2 tw-py-0.5 tw-mt-0.5">
                                            <i class="fas fa-recycle tw-text-[9px]"></i> Réutilisable
                                        </span>
                                    </td>

                                    {{-- Catégorie --}}
                                    <td class="tw-px-5 tw-py-3.5">
                                        <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium {{ $catColors[$produit->categorie] ?? 'tw-bg-slate-100 tw-text-slate-600' }}">
                                            {{ $produit->categorie }}
                                        </span>
                                    </td>

                                    {{-- Stock Total --}}
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                        <span class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-slate-800 tw-text-white tw-text-xs tw-font-bold tw-px-2.5 tw-py-0.5 tw-min-w-[2rem]">
                                            {{ $produit->qte_stock }}
                                        </span>
                                    </td>

                                    {{-- Disponible --}}
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                        <span class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-text-xs tw-font-bold tw-px-2.5 tw-py-0.5 tw-min-w-[2rem] {{ $disponible > 0 ? 'tw-bg-emerald-100 tw-text-emerald-700' : 'tw-bg-red-100 tw-text-red-600' }}">
                                            {{ $disponible }}
                                        </span>
                                    </td>

                                    {{-- En Utilisation --}}
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                        <span class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-amber-100 tw-text-amber-700 tw-text-xs tw-font-bold tw-px-2.5 tw-py-0.5 tw-min-w-[2rem]">
                                            {{ $produit->qte_en_utilisation }}
                                        </span>
                                    </td>

                                    {{-- En Stérilisation --}}
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                        <span class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-sky-100 tw-text-sky-700 tw-text-xs tw-font-bold tw-px-2.5 tw-py-0.5 tw-min-w-[2rem]">
                                            {{ $produit->qte_en_sterilisation }}
                                        </span>
                                    </td>

                                    {{-- Méthode Stérilisation --}}
                                    <td class="tw-px-5 tw-py-3.5">
                                        @if($produit->methode_sterilisation_recommandee)
                                            <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-xs tw-font-semibold tw-px-2.5 tw-py-0.5 tw-whitespace-nowrap">
                                                {{ ucfirst(str_replace('_', ' ', $produit->methode_sterilisation_recommandee)) }}
                                            </span>
                                        @else
                                            <span class="tw-text-slate-400 tw-text-xs tw-italic">Non défini</span>
                                        @endif
                                    </td>

                                    {{-- Température --}}
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center tw-whitespace-nowrap">
                                        @if($produit->temperature_sterilisation)
                                            <span class="tw-text-sm tw-font-semibold tw-text-slate-700">{{ $produit->temperature_sterilisation }}<span class="tw-text-xs tw-text-slate-400">°C</span></span>
                                        @else
                                            <span class="tw-text-slate-300">—</span>
                                        @endif
                                    </td>

                                    {{-- Durée --}}
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center tw-whitespace-nowrap">
                                        @if($produit->duree_sterilisation_recommandee)
                                            <span class="tw-text-sm tw-font-semibold tw-text-slate-700">{{ $produit->duree_sterilisation_recommandee }}<span class="tw-text-xs tw-text-slate-400"> min</span></span>
                                        @else
                                            <span class="tw-text-slate-300">—</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="tw-px-5 tw-py-3.5">
                                        <div class="tw-flex tw-items-center tw-justify-center">
                                            @if(in_array(auth()->user()->role_id, [1, 5, 7]))
                                            <a href="{{ route('produits.edit-reusable-settings', $produit->id) }}"
                                               title="Modifier les paramètres"
                                               class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-teal-200 tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 tw-no-underline">
                                                <i class="fas fa-cog tw-text-xs"></i>
                                            </a>
                                            @else
                                            <span class="tw-text-slate-300 tw-text-xs">—</span>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="tw-text-center tw-py-16">
                                        <div class="tw-w-16 tw-h-16 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-4">
                                            <i class="fas fa-recycle tw-text-slate-300 tw-text-3xl"></i>
                                        </div>
                                        <p class="tw-text-slate-500 tw-font-medium tw-mb-1">Aucun produit réutilisable trouvé</p>
                                        <p class="tw-text-sm tw-text-slate-400">Marquez des produits comme réutilisables depuis la liste générale</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($reusableProducts->hasPages())
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-bg-slate-50/50">
                        {{ $reusableProducts->links() }}
                    </div>
                    @endif

                </div>
                {{-- /Table Card --}}

            </div>
        </main>
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection