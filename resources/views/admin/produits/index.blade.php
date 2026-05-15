@extends('layouts.admin')
@section('title', 'CMCU | Liste des produits')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('viewAny', \App\Models\Produit::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Gestion des Produits</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">
                        @if($category)
                            Catégorie filtrée : <span class="tw-font-semibold tw-text-[#1D4ED8]">{{ $category }}</span>
                        @else
                            Tous les produits du stock
                        @endif
                    </p>
                </div>
                @can('create', \App\Models\Produit::class)
                <a href="{{ route('produits.create') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-sm tw-font-medium tw-px-4 tw-py-2.5 tw-transition-colors tw-no-underline tw-shadow-sm">
                    <i class="fas fa-plus-circle tw-text-xs"></i> Nouveau Produit
                </a>
                @endcan
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Stats Cards --}}
            <div class="tw-grid tw-grid-cols-2 xl:tw-grid-cols-4 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-boxes tw-text-[#1D4ED8]"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">
                            @if($category) Catégorie @else Total @endif
                        </p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $produitCount }}</p>
                    </div>
                </div>

                <a href="{{ route('produits.reusable-list') }}" class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-teal-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-no-underline">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-recycle tw-text-[#14B8A6]"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Réutilisables</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ \App\Models\Produit::where('is_reusable', true)->count() }}</p>
                    </div>
                </a>

                @can('approveEditRequests', \App\Models\Produit::class)
                <a href="{{ route('produits.edit-permissions.pending') }}" class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-amber-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-no-underline">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-key tw-text-amber-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Permissions</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ \App\Models\ProduitEditRequest::where('status', 'pending')->count() }}</p>
                    </div>
                </a>
                @endcan

                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-red-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-red-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-exclamation-triangle tw-text-red-400"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Stock Faible</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ \App\Models\Produit::whereColumn('qte_stock', '<=', 'qte_alerte')->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- Category Filter Pills --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-4 tw-mb-6">
                <div class="tw-flex tw-flex-wrap tw-gap-2 tw-items-center">
                    <span class="tw-text-xs tw-font-semibold tw-text-slate-400 tw-uppercase tw-tracking-wider tw-mr-1">Filtrer :</span>
                    <a href="{{ route('produits.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-px-4 tw-py-1.5 tw-text-xs tw-font-medium tw-transition-colors tw-no-underline {{ !$category ? 'tw-bg-[#1D4ED8] tw-text-white' : 'tw-bg-slate-100 tw-text-slate-600 hover:tw-bg-[#BFDBFE] hover:tw-text-[#1D4ED8]' }}">
                        <i class="fas fa-th"></i> Tous ({{ $produitCount }})
                    </a>
                    <a href="{{ route('produits.index', ['categorie' => 'PHARMACEUTIQUE']) }}"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-px-4 tw-py-1.5 tw-text-xs tw-font-medium tw-transition-colors tw-no-underline {{ $category === 'PHARMACEUTIQUE' ? 'tw-bg-[#1D4ED8] tw-text-white' : 'tw-bg-slate-100 tw-text-slate-600 hover:tw-bg-[#BFDBFE] hover:tw-text-[#1D4ED8]' }}">
                        <i class="fas fa-pills"></i> Pharmaceutique ({{ $categoryCounts['PHARMACEUTIQUE'] }})
                    </a>
                    <a href="{{ route('produits.index', ['categorie' => 'MATERIEL']) }}"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-px-4 tw-py-1.5 tw-text-xs tw-font-medium tw-transition-colors tw-no-underline {{ $category === 'MATERIEL' ? 'tw-bg-[#1D4ED8] tw-text-white' : 'tw-bg-slate-100 tw-text-slate-600 hover:tw-bg-[#BFDBFE] hover:tw-text-[#1D4ED8]' }}">
                        <i class="fas fa-tools"></i> Matériel ({{ $categoryCounts['MATERIEL'] }})
                    </a>
                    <a href="{{ route('produits.index', ['categorie' => 'ANESTHESISTE']) }}"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-px-4 tw-py-1.5 tw-text-xs tw-font-medium tw-transition-colors tw-no-underline {{ $category === 'ANESTHESISTE' ? 'tw-bg-[#1D4ED8] tw-text-white' : 'tw-bg-slate-100 tw-text-slate-600 hover:tw-bg-[#BFDBFE] hover:tw-text-[#1D4ED8]' }}">
                        <i class="fas fa-syringe"></i> Anesthésiste ({{ $categoryCounts['ANESTHESISTE'] }})
                    </a>
                    <div class="tw-ml-auto tw-flex tw-gap-2">
                        <a href="{{ route('produits.index', array_merge(request()->all(), ['reusable' => '1'])) }}"
                           class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-px-4 tw-py-1.5 tw-text-xs tw-font-medium tw-transition-colors tw-no-underline {{ request('reusable') === '1' ? 'tw-bg-teal-500 tw-text-white' : 'tw-bg-teal-50 tw-text-teal-700 hover:tw-bg-teal-100' }}">
                            <i class="fas fa-recycle"></i> Réutilisables
                        </a>
                    </div>
                </div>
            </div>

            {{-- Products Table --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-boxes tw-text-[#1D4ED8]"></i>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Liste des produits</h2>
                </div>
                <div class="tw-overflow-x-auto">
                    <table id="myTable" class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">ID</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Désignation</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Catégorie</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Stock</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Alerte</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Prix Unitaire</th>
                                @if(in_array(auth()->user()->role_id, [1, 5, 7]))
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Réutilisable</th>
                                @endif
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($produits as $produit)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors {{ $produit->qte_stock <= $produit->qte_alerte ? 'tw-bg-red-50/50' : '' }}">
                                <td class="tw-px-4 tw-py-3 tw-text-slate-500 tw-text-xs tw-font-mono">{{ $produit->id }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    <p class="tw-font-semibold tw-text-slate-700 tw-mb-0">{{ $produit->designation }}</p>
                                    @if($produit->is_reusable)
                                    <span class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-bg-teal-50 tw-text-teal-700 tw-text-[10px] tw-px-2 tw-py-0.5 tw-mt-0.5">
                                        <i class="fas fa-recycle tw-text-[9px]"></i> Réutilisable
                                    </span>
                                    @endif
                                </td>
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
                                    @if($produit->is_reusable && ($produit->qte_en_utilisation > 0 || $produit->qte_en_sterilisation > 0))
                                    <div class="tw-flex tw-gap-1 tw-justify-center tw-mt-1">
                                        @if($produit->qte_en_utilisation > 0)
                                        <span class="tw-text-[10px] tw-bg-amber-100 tw-text-amber-700 tw-rounded-full tw-px-1.5">{{ $produit->qte_en_utilisation }} usage</span>
                                        @endif
                                        @if($produit->qte_en_sterilisation > 0)
                                        <span class="tw-text-[10px] tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-rounded-full tw-px-1.5">{{ $produit->qte_en_sterilisation }} stéril.</span>
                                        @endif
                                    </div>
                                    @endif
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center tw-text-slate-500 tw-text-sm">{{ $produit->qte_alerte }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-font-medium tw-text-slate-700">{{ number_format($produit->prix_unitaire, 0, ',', ' ') }} <span class="tw-text-xs tw-text-slate-400">FCFA</span></td>

                                @if(in_array(auth()->user()->role_id, [1, 5, 7]))
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <div class="form-check form-switch tw-flex tw-justify-center">
                                        <input class="form-check-input reusable-toggle"
                                               type="checkbox"
                                               id="reusable_{{ $produit->id }}"
                                               data-produit-id="{{ $produit->id }}"
                                               data-produit-name="{{ $produit->designation }}"
                                               {{ $produit->is_reusable ? 'checked' : '' }}
                                               style="cursor:pointer; transform:scale(1.2);">
                                    </div>
                                    @if($produit->is_reusable && $produit->methode_sterilisation_recommandee)
                                    <p class="tw-text-[10px] tw-text-slate-400 tw-mt-0.5 tw-mb-0">{{ ucfirst($produit->methode_sterilisation_recommandee) }}</p>
                                    @endif
                                </td>
                                @endif

                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1">
                                        @if($produit->is_reusable && in_array(auth()->user()->role_id, [1, 5, 7]))
                                        <a href="{{ route('produits.edit-reusable-settings', $produit->id) }}"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-600 tw-transition-colors tw-no-underline" title="Paramètres réutilisable">
                                            <i class="fas fa-cog tw-text-xs"></i>
                                        </a>
                                        @endif
                                        @can('update', $produit)
                                        <a href="{{ route('produits.edit', $produit->id) }}"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-600 tw-transition-colors tw-no-underline" title="Modifier">
                                            <i class="fas fa-edit tw-text-xs"></i>
                                        </a>
                                        @endcan
                                        @can('delete', $produit)
                                        <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" class="tw-inline" onsubmit="return confirm('Supprimer ce produit ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-transition-colors" title="Supprimer">
                                                <i class="fas fa-trash tw-text-xs"></i>
                                            </button>
                                        </form>
                                        @endcan
                                        <!-- @can('viewAuditLogs', \App\Models\Produit::class)
                                        <a href="{{ route('produits.audit-logs.show', $produit->id) }}"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline" title="Historique">
                                            <i class="fas fa-history tw-text-xs"></i>
                                        </a>
                                        @endcan -->
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($produits->hasPages()) 
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $produits->appends(request()->query())->links() }}
                </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            @can('approveEditRequests', \App\Models\Produit::class)
            <div class="tw-mt-6 tw-flex tw-flex-wrap tw-gap-3 tw-justify-center">
                <a href="{{ route('produits.edit-permissions.pending') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline tw-text-sm">
                    <i class="fas fa-key"></i> Permissions en Attente
                    <span class="tw-ml-1 tw-rounded-full tw-bg-amber-200 tw-text-amber-800 tw-text-xs tw-px-2 tw-py-0.5">{{ \App\Models\ProduitEditRequest::where('status', 'pending')->count() }}</span>
                </a>
                <a href="{{ route('produits.edit-permissions.history') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline tw-text-sm">
                    <i class="fas fa-history"></i> Historique des Permissions
                </a>
                <a href="{{ route('produits.audit-logs') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline tw-text-sm">
                    <i class="fas fa-clipboard-list"></i> Journal d'Audit
                </a>
            </div>
            @endcan

        </main>
        @endcan
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

@push('scripts')
<script src="{{ asset('admin/js/main.js') }}"></script>
<script>
waitForjQuery(function() {
$(document).ready(function() {
    $('#produitsTable').DataTable({
        language: { url: "{{ asset('vendor/i18n/fr_fr.json') }}" },
        pageLength: 15,
        responsive: true,
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [@if(in_array(auth()->user()->role_id, [1, 5, 7])) 6, 7 @else 6 @endif] }
        ]
    });

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $('.reusable-toggle').on('change', function() {
        const checkbox = $(this);
        const produitId = checkbox.data('produit-id');
        const produitName = checkbox.data('produit-name');
        const newStatus = checkbox.is(':checked');
        const message = newStatus ? `Marquer "${produitName}" comme réutilisable?` : `Retirer "${produitName}" des réutilisables?`;
        if (!confirm(message)) { checkbox.prop('checked', !newStatus); return; }
        checkbox.prop('disabled', true);
        checkbox.closest('tr').css('opacity', '0.6');
        $.ajax({
            url: `/admin/produits/${produitId}/toggle-reusable`,
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if (response.success) { showNotification('success', response.message); setTimeout(() => location.reload(), 1000); }
                else { showNotification('error', response.message || 'Erreur'); checkbox.prop('checked', !newStatus); }
            },
            error: function(xhr) {
                showNotification('error', xhr.responseJSON?.message || 'Erreur');
                checkbox.prop('checked', !newStatus);
            },
            complete: function() { checkbox.prop('disabled', false); checkbox.closest('tr').css('opacity', '1'); }
        });
    });

    function showNotification(type, message) {
        const cls = type === 'success' ? 'tw-bg-teal-50 tw-border-teal-200 tw-text-teal-700' : 'tw-bg-red-50 tw-border-red-200 tw-text-red-600';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const html = `<div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-border ${cls} tw-px-4 tw-py-3 tw-mb-4 tw-text-sm notification-alert">
            <i class="fas ${icon}"></i> ${message}</div>`;
        $('main').prepend(html);
        setTimeout(() => $('.notification-alert').fadeOut('slow', function() { $(this).remove(); }), 4000);
    }
});
});
</script>







@endpush
@endsection