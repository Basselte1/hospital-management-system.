@extends('layouts.admin')
@section('title', 'CMCU | Historique Pharmacie')
@section('breadcrumb', 'Pharmacie / Historique')
@section('page_title', 'Historique')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-history tw-text-[#1D4ED8]"></i> Historique des Ventes
                    </h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Rapport détaillé des transactions pharmaceutiques</p>
                </div>
                <a href="{{ route('pharmacie.index') }}"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            {{-- Stats cards --}}
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-4 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-shopping-cart tw-text-[#1D4ED8] tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Total Ventes</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $stats['total_ventes'] }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-green-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-money-bill-wave tw-text-green-500 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Montant Total</p>
                        <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ number_format($stats['total_montant']) }} <span class="tw-text-sm tw-font-normal tw-text-slate-500">FCFA</span></p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-check-circle tw-text-[#14B8A6] tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Soldées</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $stats['total_soldees'] }}
                            <span class="tw-text-xs tw-font-normal tw-text-slate-400 tw-ml-1">{{ $stats['total_ventes'] > 0 ? number_format(($stats['total_soldees'] / $stats['total_ventes']) * 100, 1) : 0 }}%</span>
                        </p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-clock tw-text-amber-500 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">En Attente</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $stats['total_en_attente'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Répartition + Période --}}
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-5">
                    <p class="tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-400 tw-font-semibold tw-mb-3">Répartition par Type</p>
                    <div class="tw-flex tw-gap-6">
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">Patient</span>
                            <span class="tw-font-bold tw-text-slate-700">{{ $stats['ventes_patients'] }}</span>
                        </div>
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-teal-100 tw-text-teal-700">Externe</span>
                            <span class="tw-font-bold tw-text-slate-700">{{ $stats['ventes_externes'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-5">
                    <p class="tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-400 tw-font-semibold tw-mb-3">Période</p>
                    <div class="tw-flex tw-items-center tw-justify-between tw-text-sm">
                        <div><span class="tw-text-slate-500">Du</span> <span class="tw-font-semibold tw-text-slate-700">{{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }}</span></div>
                        <div class="tw-text-slate-300">—</div>
                        <div><span class="tw-text-slate-500">Au</span> <span class="tw-font-semibold tw-text-slate-700">{{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</span></div>
                        <div class="tw-text-[#1D4ED8] tw-font-semibold">{{ \Carbon\Carbon::parse($dateFrom)->diffInDays(\Carbon\Carbon::parse($dateTo)) + 1 }} jours</div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-6">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-filter tw-text-[#1D4ED8]"></i>
                        <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Filtres de Recherche</h2>
                    </div>
                    <button class="tw-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-500 tw-border-0 tw-transition-colors" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="fas fa-chevron-down tw-text-xs"></i>
                    </button>
                </div>
                <div class="collapse show" id="filterCollapse">
                    <div class="tw-p-6">
                        <form method="GET" action="{{ route('pharmacie.history') }}" id="filter-form">
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-4">
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date Début</label>
                                    <input type="date" name="date_from" value="{{ $dateFrom }}" max="{{ now()->toDateString() }}"
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date Fin</label>
                                    <input type="date" name="date_to" value="{{ $dateTo }}" max="{{ now()->toDateString() }}"
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Type de Vente</label>
                                    <select name="type_vente" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                        <option value="">Tous</option>
                                        <option value="patient" {{ request('type_vente') == 'patient' ? 'selected' : '' }}>Patient</option>
                                        <option value="client_externe" {{ request('type_vente') == 'client_externe' ? 'selected' : '' }}>Externe</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Statut Paiement</label>
                                    <select name="statut_paiement" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                        <option value="">Tous</option>
                                        <option value="soldee" {{ request('statut_paiement') == 'soldee' ? 'selected' : '' }}>Soldée</option>
                                        <option value="en_attente" {{ request('statut_paiement') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    </select>
                                </div>
                                <div class="tw-flex tw-items-end">
                                    <button type="submit"
                                        class="tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-sm tw-font-medium tw-py-2 tw-transition-colors tw-border-0">
                                        <i class="fas fa-search tw-text-xs"></i> Filtrer
                                    </button>
                                </div>
                            </div>

                            {{-- Quick date filters --}}
                            <div class="tw-mt-4 tw-flex tw-flex-wrap tw-gap-2">
                                <button type="button" class="quick-date tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-xs tw-font-medium tw-border-0 tw-transition-colors" data-period="today">Aujourd'hui</button>
                                <button type="button" class="quick-date tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-xs tw-font-medium tw-border-0 tw-transition-colors" data-period="yesterday">Hier</button>
                                <button type="button" class="quick-date tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-xs tw-font-medium tw-border-0 tw-transition-colors" data-period="week">Cette semaine</button>
                                <button type="button" class="quick-date tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-xs tw-font-medium tw-border-0 tw-transition-colors" data-period="month">Ce mois</button>
                                <button type="button" class="quick-date tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-xs tw-font-medium tw-border-0 tw-transition-colors" data-period="last-month">Mois dernier</button>
                                <button type="button" id="reset-filters" class="tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-xs tw-font-medium tw-border-0 tw-transition-colors">Réinitialiser</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sales table --}}
            @if($ventes->count() > 0)
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-6">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-table tw-text-[#1D4ED8]"></i>
                        <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Liste des Ventes <span class="tw-text-slate-400 tw-text-sm tw-font-normal">({{ $ventes->count() }} résultats)</span></h2>
                    </div>
                    <div class="tw-flex tw-gap-2">
                        <button onclick="window.print()"
                            class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-xs tw-font-medium tw-px-3 tw-py-1.5 tw-border-0 tw-transition-colors">
                            <i class="fas fa-print tw-text-xs"></i> Imprimer
                        </button>
                        <button onclick="exportToExcel()"
                            class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-green-50 hover:tw-bg-green-100 tw-text-green-700 tw-text-xs tw-font-medium tw-px-3 tw-py-1.5 tw-border-0 tw-transition-colors">
                            <i class="fas fa-file-excel tw-text-xs"></i> Excel
                        </button>
                    </div>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm" id="history-table">
                        <thead>
                            <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">N° Vente</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Date/Heure</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Type</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Client</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Pharmacien</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Montant</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Statut</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Caissier</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($ventes as $vente)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3 tw-font-semibold tw-text-slate-700">{{ $vente->numero_vente }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    <p class="tw-text-slate-700 tw-mb-0 tw-text-xs">{{ $vente->created_at->format('d/m/Y') }}</p>
                                    <p class="tw-text-slate-400 tw-mb-0 tw-text-xs">{{ $vente->created_at->format('H:i') }}</p>
                                </td>
                                <td class="tw-px-4 tw-py-3">
                                    @if($vente->isPatientSale())
                                        <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">Patient</span>
                                    @else
                                        <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-teal-100 tw-text-teal-700">Externe</span>
                                    @endif
                                </td>
                                <td class="tw-px-4 tw-py-3">
                                    @if($vente->isPatientSale())
                                        <p class="tw-font-medium tw-text-slate-700 tw-mb-0 tw-text-xs">{{ $vente->patient->name ?? 'N/A' }}</p>
                                        <p class="tw-text-slate-400 tw-mb-0 tw-text-xs">{{ $vente->patient->numero_dossier ?? '' }}</p>
                                    @else
                                        <p class="tw-font-medium tw-text-slate-700 tw-mb-0 tw-text-xs">{{ $vente->client->nom ?? 'N/A' }}</p>
                                        <p class="tw-text-slate-400 tw-mb-0 tw-text-xs">{{ $vente->client->telephone ?? '' }}</p>
                                    @endif
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-500 tw-text-xs">{{ $vente->pharmacien->name ?? 'N/A' }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-font-semibold tw-text-slate-800 tw-text-xs">{{ number_format($vente->montant_total) }} FCFA</td>
                                <td class="tw-px-4 tw-py-3">
                                    @if($vente->isSoldee())
                                        <span class="tw-inline-flex tw-rounded-full tw-px-2 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-700">Soldée</span>
                                        @if($vente->date_paiement)
                                        <p class="tw-text-slate-400 tw-mb-0 tw-text-xs tw-mt-0.5">{{ $vente->date_paiement->format('d/m/Y') }}</p>
                                        @endif
                                    @else
                                        <span class="tw-inline-flex tw-rounded-full tw-px-2 tw-py-0.5 tw-text-xs tw-font-medium tw-bg-amber-100 tw-text-amber-700">En attente</span>
                                    @endif
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-500 tw-text-xs">{{ $vente->caissier ? $vente->caissier->name : '—' }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <a href="{{ route('pharmacie.sales.show', $vente->id) }}"
                                        class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline" title="Voir détails">
                                        <i class="fas fa-eye tw-text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="tw-bg-slate-50 tw-border-t tw-border-slate-200 tw-font-semibold">
                                <td colspan="5" class="tw-px-4 tw-py-3 tw-text-right tw-text-slate-700">TOTAL :</td>
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-text-[#1D4ED8]">{{ number_format($ventes->sum('montant_total')) }} FCFA</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Top products --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-chart-bar tw-text-[#1D4ED8]"></i>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Top 10 Produits Vendus</h2>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Produit</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Quantité</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">CA</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @php
                            $topProducts = collect();
                            foreach($ventes as $vente) {
                                foreach($vente->items as $item) {
                                    $key = $item->designation;
                                    if ($topProducts->has($key)) {
                                        $existing = $topProducts->get($key);
                                        $topProducts->put($key, ['designation' => $item->designation, 'quantite' => $existing['quantite'] + $item->quantite, 'montant' => $existing['montant'] + $item->montant_ligne]);
                                    } else {
                                        $topProducts->put($key, ['designation' => $item->designation, 'quantite' => $item->quantite, 'montant' => $item->montant_ligne]);
                                    }
                                }
                            }
                            $topProducts = $topProducts->sortByDesc('montant')->take(10);
                            @endphp
                            @foreach($topProducts as $product)
                            <tr class="hover:tw-bg-slate-50">
                                <td class="tw-px-4 tw-py-2.5 tw-text-slate-700">{{ $product['designation'] }}</td>
                                <td class="tw-px-4 tw-py-2.5 tw-text-center tw-text-slate-600">{{ $product['quantite'] }}</td>
                                <td class="tw-px-4 tw-py-2.5 tw-text-right tw-font-semibold tw-text-slate-800">{{ number_format($product['montant']) }} FCFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @else
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-py-16 tw-text-center">
                <i class="fas fa-inbox tw-text-4xl tw-text-slate-300 tw-mb-3 tw-block"></i>
                <h3 class="tw-text-lg tw-font-semibold tw-text-slate-500">Aucune vente trouvée</h3>
                <p class="tw-text-slate-400 tw-text-sm">Essayez de modifier les filtres de recherche</p>
            </div>
            @endif

        </main>
    </div>
</div>

@section('scripts')
<script>
waitForjQuery(function() {
$(document).ready(function() {
    $('.quick-date').click(function() {
        let period = $(this).data('period');
        let today = new Date();
        let dateFrom, dateTo;
        switch(period) {
            case 'today': dateFrom = dateTo = formatDate(today); break;
            case 'yesterday':
                let y = new Date(today); y.setDate(y.getDate() - 1);
                dateFrom = dateTo = formatDate(y); break;
            case 'week':
                let ws = new Date(today); ws.setDate(today.getDate() - today.getDay());
                dateFrom = formatDate(ws); dateTo = formatDate(today); break;
            case 'month':
                dateFrom = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
                dateTo = formatDate(today); break;
            case 'last-month':
                dateFrom = formatDate(new Date(today.getFullYear(), today.getMonth() - 1, 1));
                dateTo = formatDate(new Date(today.getFullYear(), today.getMonth(), 0)); break;
        }
        $('input[name="date_from"]').val(dateFrom);
        $('input[name="date_to"]').val(dateTo);
        $('#filter-form').submit();
    });
    $('#reset-filters').click(function() {
        window.location.href = '{{ route("pharmacie.history") }}';
    });
    function formatDate(d) {
        return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    }
});

function exportToExcel() {
    let table = document.getElementById('history-table');
    if (!table) { alert('Aucune donnée à exporter'); return; }
    let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(table.outerHTML);
    let a = document.createElement('a');
    a.href = url;
    a.download = 'historique_pharmacie_' + new Date().toISOString().split('T')[0] + '.xls';
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
}
});
</script>
<style>
@media print {
    aside, header, .tw-space-y-4 > div:first-child { display: none !important; }
    .tw-bg-white { box-shadow: none !important; border: none !important; }
    table { font-size: 9pt; }
}
</style>
@endsection
@endsection