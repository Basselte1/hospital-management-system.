@extends('layouts.admin')
@section('title', 'CMCU | Vérification des stocks')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('verifyStock', \App\Models\Produit::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6">
                <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Vérification des Stocks</h1>
                <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Vérifiez que les stocks correspondent aux réceptions de l'hôpital</p>
            </div>

            {{-- Quick Stats --}}
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-teal-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-boxes tw-text-[#14B8A6] tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Produits Disponibles</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $recentProduits->total() }}</p>
                    </div>
                </div>

                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-amber-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-clock tw-text-amber-500 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">En Attente</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ \App\Models\Produit::where('status', 'pending')->count() }}</p>
                    </div>
                </div>

                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-red-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-red-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-exclamation-triangle tw-text-red-400 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Stock Faible</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">
                            {{ \App\Models\Produit::where('qte_stock', '<=', \DB::raw('qte_alerte'))->where('status', 'approved')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Products Table --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-boxes tw-text-[#1D4ED8]"></i>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Produits en Stock</h2>
                </div> 
                <div class="tw-overflow-x-auto">
                    <table id="myTable" class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">ID</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Désignation</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Catégorie</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Stock Reçu</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Seuil Alerte</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Prix Unitaire</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Ajouté par</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Date d'ajout</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($recentProduits as $produit)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors {{ $produit->qte_stock <= $produit->qte_alerte ? 'tw-bg-red-50/40' : '' }}">
                                <td class="tw-px-4 tw-py-3 tw-text-slate-400 tw-text-xs tw-font-mono">{{ $produit->id }}</td>
                                <td class="tw-px-4 tw-py-3 tw-font-semibold tw-text-slate-700">{{ $produit->designation }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    @php
                                        $catColors = [
                                            'PHARMACEUTIQUE' => 'tw-bg-[#BFDBFE] tw-text-[#1D4ED8]',
                                            'MATERIEL'       => 'tw-bg-slate-100 tw-text-slate-600',
                                            'ANESTHESISTE'   => 'tw-bg-teal-100 tw-text-teal-700',
                                        ];
                                    @endphp
                                    <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium {{ $catColors[$produit->categorie] ?? 'tw-bg-slate-100 tw-text-slate-600' }}">
                                        {{ $produit->categorie }}
                                    </span>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-bold {{ $produit->qte_stock <= $produit->qte_alerte ? 'tw-bg-red-100 tw-text-red-600' : 'tw-bg-teal-50 tw-text-teal-700' }}">
                                        {{ $produit->qte_stock }}
                                    </span>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center tw-text-slate-500">{{ $produit->qte_alerte }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-font-medium tw-text-slate-700">
                                    {{ number_format($produit->prix_unitaire, 0, ',', ' ') }} <span class="tw-text-xs tw-text-slate-400">FCFA</span>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-500 tw-text-xs">
                                    <i class="fas fa-user tw-text-slate-300 tw-mr-1"></i>{{ $produit->createdBy->name ?? 'N/A' }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-400 tw-text-xs">{{ $produit->created_at->format('d/m/Y H:i') }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    @if($produit->qte_stock <= $produit->qte_alerte)
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-bg-red-100 tw-text-red-600 tw-text-xs tw-font-medium tw-px-2.5 tw-py-1">
                                            <i class="fas fa-exclamation-triangle tw-text-[9px]"></i> Faible
                                        </span>
                                    @elseif($produit->qte_stock <= ($produit->qte_alerte * 1.5))
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-bg-amber-100 tw-text-amber-700 tw-text-xs tw-font-medium tw-px-2.5 tw-py-1">
                                            <i class="fas fa-exclamation-circle tw-text-[9px]"></i> À surveiller
                                        </span>
                                    @else
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-bg-teal-50 tw-text-teal-700 tw-text-xs tw-font-medium tw-px-2.5 tw-py-1">
                                            <i class="fas fa-check-circle tw-text-[9px]"></i> Bon
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($recentProduits->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $recentProduits->links() }}
                </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="tw-flex tw-flex-wrap tw-gap-3 tw-justify-center tw-mt-6">
                <a href="{{ route('produits.edit-permissions.pending') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline tw-text-sm">
                    <i class="fas fa-clock"></i> Produits en Attente
                    <span class="tw-ml-1 tw-rounded-full tw-bg-amber-200 tw-text-amber-800 tw-text-xs tw-px-2 tw-py-0.5">{{ \App\Models\Produit::where('status', 'pending')->count() }}</span>
                </a>
                <a href="{{ route('produits.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline tw-text-sm">
                    <i class="fas fa-list"></i> Tous les Produits
                </a>
            </div>

        </main>
        @endcan
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>

@push('scripts')
<script>
$(document).ready(function() {
    $('#stockTable').DataTable({
        language: { url: "{{ asset('vendor/i18n/fr_fr.json') }}" },
        pageLength: 15,
        responsive: true,
        order: [[7, 'desc']],
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>
@endpush
@endsection