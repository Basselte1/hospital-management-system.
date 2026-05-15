@extends('layouts.admin')
@section('title', 'CMCU | Demandes de Permission en Attente')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">
                        <i class="fas fa-key tw-text-amber-500 tw-mr-2"></i>Demandes de Permission
                    </h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Validation des demandes de modification de produits</p>
                </div>
                <div class="tw-flex tw-gap-2 tw-flex-wrap">
                    <a href="{{ route('produits.edit-permissions.history') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                        <i class="fas fa-history tw-text-xs"></i> Historique
                    </a>
                    <a href="{{ route('produits.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                        <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                    </a>
                </div>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6] tw-shrink-0"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-400 tw-shrink-0"></i> {{ session('error') }}
            </div>
            @endif

            @if($editRequests->count() == 0)
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-py-16 tw-text-center">
                <div class="tw-w-12 tw-h-12 tw-rounded-2xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-3">
                    <i class="fas fa-check-double tw-text-amber-400 tw-text-xl"></i>
                </div>
                <p class="tw-text-slate-700 tw-font-semibold tw-mb-1">Tout est traité !</p>
                <p class="tw-text-slate-400 tw-text-sm tw-italic tw-mb-0">Aucune demande de permission en attente</p>
            </div>
            @else

            {{-- Batch actions banner --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-amber-200 tw-overflow-hidden tw-mb-6">
                <div class="tw-px-6 tw-py-4 tw-bg-amber-500 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                    <div>
                        <h3 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">
                            <i class="fas fa-tasks tw-mr-2"></i> Actions Groupées
                        </h3>
                        <p class="tw-text-white/80 tw-text-xs tw-mb-0 tw-mt-0.5">
                            {{ $editRequests->total() }} demande(s) en attente
                        </p>
                    </div>
                    <div class="tw-flex tw-gap-2 tw-flex-wrap">
                        <form action="{{ route('produits.edit-permissions.batch.approve') }}" method="POST" class="tw-inline"
                              onsubmit="return confirm('Approuver toutes les demandes en attente ?')">
                            @csrf
                            <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white hover:tw-bg-slate-50 tw-text-teal-600 tw-text-sm tw-font-semibold tw-px-4 tw-py-2 tw-border-0 tw-transition-colors">
                                <i class="fas fa-check-double tw-text-xs"></i> Approuver Toutes
                            </button>
                        </form>
                        <button type="button"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2 tw-border-0 tw-transition-colors"
                                data-bs-toggle="modal" data-bs-target="#batchRejectModal">
                            <i class="fas fa-times-circle tw-text-xs"></i> Rejeter Toutes
                        </button>
                    </div>
                </div>
            </div>

            {{-- Requests list --}}
            <div class="tw-space-y-4">
                @foreach($editRequests as $request)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    {{-- Card header --}}
                    <div class="tw-px-6 tw-py-4 tw-bg-amber-500 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-2">
                        <div>
                            <h5 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">
                                <i class="fas fa-key tw-mr-2"></i>Permission pour : {{ $request->produit->designation }}
                            </h5>
                            <p class="tw-text-white/80 tw-text-xs tw-mb-0 tw-mt-0.5">
                                Demandé par <strong class="tw-text-white">{{ $request->requestedBy->name }}</strong>
                                ({{ $request->requestedBy->role->name ?? 'N/A' }})
                            </p>
                        </div>
                        <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-white/20 tw-text-white tw-text-xs tw-font-semibold tw-px-3 tw-py-1">
                            {{ $request->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    {{-- Card body --}}
                    <div class="tw-p-5">
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-x-8 tw-gap-y-2 tw-mb-4 tw-text-sm">
                            <div class="tw-space-y-1.5">
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Produit :</span> <span class="tw-font-semibold tw-text-slate-700">{{ $request->produit->designation }}</span></p>
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Catégorie :</span> <span class="tw-font-medium tw-text-slate-600">{{ $request->produit->categorie }}</span></p>
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Stock actuel :</span>
                                    <span class="tw-inline-flex tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-xs tw-font-bold tw-px-2.5 tw-py-0.5 tw-ml-1">{{ $request->produit->qte_stock }}</span>
                                </p>
                            </div>
                            <div class="tw-space-y-1.5">
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Demandé il y a :</span> <span class="tw-font-medium tw-text-amber-600">{{ $request->getTimeSinceRequest() }}</span></p>
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Rôle :</span> <span class="tw-font-medium tw-text-slate-600">{{ $request->requestedBy->role->name ?? 'N/A' }}</span></p>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="tw-flex tw-items-start tw-gap-3 tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-xl tw-px-4 tw-py-3 tw-mb-4 tw-text-sm">
                            <i class="fas fa-comment tw-text-slate-400 tw-mt-0.5 tw-shrink-0"></i>
                            <div>
                                <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Raison</p>
                                <p class="tw-text-slate-700 tw-mb-0">{{ $request->reason }}</p>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                            <form action="{{ route('produits.edit-permissions.approve', $request->id) }}" method="POST" class="tw-inline">
                                @csrf
                                <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-500 hover:tw-bg-teal-600 tw-text-white tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-border-0 tw-transition-colors">
                                    <i class="fas fa-check tw-text-xs"></i> Approuver
                                </button>
                            </form>
                            <button type="button"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-border-0 tw-transition-colors"
                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                <i class="fas fa-times tw-text-xs"></i> Rejeter
                            </button>
                            <a href="{{ route('produits.edit', $request->produit->id) }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                                <i class="fas fa-eye tw-text-xs"></i> Voir Produit
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Reject modal --}}
                <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
                            <div class="tw-px-6 tw-py-4 tw-bg-red-600 tw-flex tw-items-center tw-justify-between">
                                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm">
                                    <i class="fas fa-times-circle tw-mr-2"></i> Rejeter la Permission
                                </h5>
                                <button type="button" class="btn-close btn-close-white tw-text-xs" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('produits.edit-permissions.reject', $request->id) }}" method="POST">
                                @csrf
                                <div class="tw-p-5 tw-space-y-4">
                                    <p class="tw-text-sm tw-text-slate-600 tw-mb-0">Êtes-vous sûr de vouloir rejeter cette demande ?</p>
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                            Raison du rejet <span class="tw-text-red-500">*</span>
                                        </label>
                                        <textarea name="review_comment" rows="3" required
                                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"></textarea>
                                    </div>
                                </div>
                                <div class="tw-px-5 tw-pb-5 tw-flex tw-justify-end tw-gap-3">
                                    <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-4 tw-py-2 tw-border-0 tw-text-sm" data-bs-dismiss="modal">
                                        Annuler
                                    </button>
                                    <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-font-medium tw-px-5 tw-py-2 tw-border-0 tw-text-sm">
                                        <i class="fas fa-times tw-text-xs"></i> Confirmer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="tw-mt-5">
                {{ $editRequests->links() }}
            </div>
            @endif

        </main>
    </div>
</div>

{{-- Batch Reject Modal --}}
<div class="modal fade" id="batchRejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-px-6 tw-py-4 tw-bg-red-600 tw-flex tw-items-center tw-justify-between">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm">
                    <i class="fas fa-times-circle tw-mr-2"></i> Rejeter Toutes les Demandes
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('produits.edit-permissions.batch.reject') }}" method="POST">
                @csrf
                <div class="tw-p-5 tw-space-y-4">
                    <div class="tw-flex tw-items-start tw-gap-3 tw-bg-amber-50 tw-border tw-border-amber-200 tw-rounded-xl tw-px-4 tw-py-3 tw-text-sm tw-text-amber-700">
                        <i class="fas fa-exclamation-triangle tw-shrink-0 tw-mt-0.5"></i>
                        <p class="tw-mb-0">Vous êtes sur le point de rejeter <strong>toutes</strong> les demandes en attente.</p>
                    </div>
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                            Raison du rejet (commune à toutes) <span class="tw-text-red-500">*</span>
                        </label>
                        <textarea name="batch_comment" rows="3" required
                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"></textarea>
                    </div>
                </div>
                <div class="tw-px-5 tw-pb-5 tw-flex tw-justify-end tw-gap-3">
                    <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-4 tw-py-2 tw-border-0 tw-text-sm" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-font-medium tw-px-5 tw-py-2 tw-border-0 tw-text-sm">
                        <i class="fas fa-times tw-text-xs"></i> Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection