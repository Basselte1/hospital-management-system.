@extends('layouts.admin')
@section('title', 'CMCU | Produits Réutilisables')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6">
                <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Produits Réutilisables</h1>
                <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Gestion du cycle de vie des instruments réutilisables</p>
            </div>

            {{-- Flash --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> {{ session('success') }}
            </div>
            @endif

            {{-- Stat Cards --}}
            <div class="tw-grid tw-grid-cols-2 xl:tw-grid-cols-4 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-recycle tw-text-[#1D4ED8]"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Total Réutilisables</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $stats['total_reusable'] }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-hand-holding-medical tw-text-amber-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">En Utilisation</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $stats['en_utilisation'] }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-sky-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-fire tw-text-sky-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">En Stérilisation</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $stats['en_sterilisation'] }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-red-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-box tw-text-red-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">À Collecter</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $stats['usages_en_attente'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-tasks tw-text-[#1D4ED8] tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Actions Rapides</h2>
                </div>
                <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-3">
                    <a href="{{ route('reusable-products.record-usage.form') }}"
                       class="tw-flex tw-flex-col tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/30 hover:tw-bg-[#BFDBFE]/60 tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-4 tw-py-5 tw-transition-colors tw-no-underline tw-text-center tw-border tw-border-[#BFDBFE]">
                        <i class="fas fa-clipboard tw-text-xl"></i>
                        Enregistrer Utilisation
                    </a>
                    <a href="{{ route('reusable-products.usages.pending') }}"
                       class="tw-flex tw-flex-col tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-font-medium tw-text-sm tw-px-4 tw-py-5 tw-transition-colors tw-no-underline tw-text-center tw-border tw-border-amber-200 tw-relative">
                        <i class="fas fa-box tw-text-xl"></i>
                        Collecter Produits
                        @if($stats['usages_en_attente'] > 0)
                        <span class="tw-absolute tw-top-2 tw-right-2 tw-inline-flex tw-items-center tw-justify-center tw-w-5 tw-h-5 tw-rounded-full tw-bg-amber-500 tw-text-white tw-text-xs tw-font-bold">{{ $stats['usages_en_attente'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('reusable-products.sterilizations.create.form') }}"
                       class="tw-flex tw-flex-col tw-items-center tw-gap-2 tw-rounded-xl tw-bg-sky-50 hover:tw-bg-sky-100 tw-text-sky-700 tw-font-medium tw-text-sm tw-px-4 tw-py-5 tw-transition-colors tw-no-underline tw-text-center tw-border tw-border-sky-200">
                        <i class="fas fa-fire tw-text-xl"></i>
                        Lancer Stérilisation
                    </a>
                    <a href="{{ route('reusable-products.sterilizations.index') }}"
                       class="tw-flex tw-flex-col tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-50 hover:tw-bg-slate-100 tw-text-slate-600 tw-font-medium tw-text-sm tw-px-4 tw-py-5 tw-transition-colors tw-no-underline tw-text-center tw-border tw-border-slate-200">
                        <i class="fas fa-history tw-text-xl"></i>
                        Historique
                    </a>
                </div>
            </div>

            {{-- Products Table --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-recycle tw-text-[#1D4ED8] tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Produits Réutilisables</h2>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Produit</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Catégorie</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Stock Total</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">En Utilisation</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">En Stérilisation</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Disponible</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Méthode Stérilisation</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @forelse($reusableProducts as $produit)
                            @php $disponible = $produit->qte_stock - $produit->qte_en_utilisation - $produit->qte_en_sterilisation; @endphp
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-6 tw-py-4 tw-font-semibold tw-text-slate-800">{{ $produit->designation }}</td>
                                <td class="tw-px-6 tw-py-4">
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-slate-100 tw-text-slate-600">
                                        {{ $produit->categorie }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-center tw-font-medium tw-text-slate-700">{{ $produit->qte_stock }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-amber-50 tw-text-amber-700">
                                        {{ $produit->qte_en_utilisation }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-sky-50 tw-text-sky-700">
                                        {{ $produit->qte_en_sterilisation }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium {{ $disponible > 0 ? 'tw-bg-emerald-50 tw-text-emerald-700' : 'tw-bg-red-50 tw-text-red-600' }}">
                                        {{ $disponible }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-xs tw-text-slate-500">{{ $produit->methode_sterilisation_recommandee ?? 'Non spécifié' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="tw-px-6 tw-py-16 tw-text-center">
                                    <div class="tw-flex tw-flex-col tw-items-center tw-gap-3">
                                        <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fas fa-recycle tw-text-slate-400 tw-text-xl"></i>
                                        </div>
                                        <p class="tw-text-slate-400 tw-text-sm tw-font-medium tw-mb-0">Aucun produit réutilisable trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>
<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection