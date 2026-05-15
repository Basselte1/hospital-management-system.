@extends('layouts.admin')
@section('title', 'CMCU | Historique des Permissions')

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
                        <i class="fas fa-history tw-text-[#1D4ED8] tw-mr-2"></i>Historique des Permissions
                    </h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Suivi de toutes les permissions de modification</p>
                </div>
                <div class="tw-flex tw-gap-2 tw-flex-wrap">
                    <a href="{{ route('produits.edit-permissions.pending') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                        <i class="fas fa-clock tw-text-xs"></i> En attente
                    </a>
                    <a href="{{ route('produits.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                        <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                    </a>
                </div>
            </div>

            {{-- Stats cards --}}
            <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-teal-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-check-circle tw-text-teal-600"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Approuvées</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">
                            {{ $editRequests->where('status', 'approved')->count() }}
                        </p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-red-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-red-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-times-circle tw-text-red-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Rejetées</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">
                            {{ $editRequests->where('status', 'rejected')->count() }}
                        </p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-ban tw-text-slate-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Révoquées</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">
                            {{ $editRequests->where('can_edit', false)->where('status', 'approved')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            @if($editRequests->count() == 0)
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-py-16 tw-text-center">
                <div class="tw-w-12 tw-h-12 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-3">
                    <i class="fas fa-history tw-text-slate-300 tw-text-xl"></i>
                </div>
                <p class="tw-text-slate-400 tw-text-sm tw-italic tw-mb-0">Aucune permission traitée</p>
            </div>
            @else

            {{-- Permissions list --}}
            <div class="tw-space-y-4">
                @foreach($editRequests as $request)
                @php
                    $color = $request->getStatusColor();
                    $headerBg = $color === 'success' ? 'tw-from-teal-600 tw-to-teal-500' :
                               ($color === 'danger'  ? 'tw-from-red-600 tw-to-red-500' :
                                                       'tw-from-slate-600 tw-to-slate-500');
                @endphp
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    {{-- Card header --}}
                    <div class="tw-px-6 tw-py-4 tw-bg-gradient-to-r {{ $headerBg }} tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-2">
                        <div>
                            <h5 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">
                                <i class="{{ $request->getStatusIcon() }} tw-mr-2"></i>{{ $request->produit->designation }}
                            </h5>
                            <p class="tw-text-white/70 tw-text-xs tw-mb-0 tw-mt-0.5">
                                Demandé par <strong class="tw-text-white">{{ $request->requestedBy->name }}</strong>
                                le {{ $request->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-white/20 tw-text-white tw-text-xs tw-font-semibold tw-px-3 tw-py-1">
                            {{ $request->getStatusLabel() }}
                        </span>
                    </div>

                    {{-- Card body --}}
                    <div class="tw-p-5">
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-x-8 tw-gap-y-2 tw-mb-4 tw-text-sm">
                            <div class="tw-space-y-1.5">
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Produit :</span> <span class="tw-font-semibold tw-text-slate-700">{{ $request->produit->designation }}</span></p>
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Catégorie :</span> <span class="tw-font-medium tw-text-slate-600">{{ $request->produit->categorie }}</span></p>
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Demandeur :</span> <span class="tw-font-medium tw-text-slate-600">{{ $request->requestedBy->name }} ({{ $request->requestedBy->role->name ?? 'N/A' }})</span></p>
                            </div>
                            <div class="tw-space-y-1.5">
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Traité par :</span> <span class="tw-font-medium tw-text-slate-600">{{ $request->reviewedBy->name ?? 'N/A' }}</span></p>
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Date traitement :</span> <span class="tw-font-medium tw-text-slate-600">{{ $request->reviewed_at ? $request->reviewed_at->format('d/m/Y à H:i') : 'N/A' }}</span></p>
                                @if($request->isRevoked())
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Révoqué par :</span> <span class="tw-font-medium tw-text-red-600">{{ $request->revokedBy->name ?? 'N/A' }}</span></p>
                                <p class="tw-mb-0"><span class="tw-text-slate-400 tw-text-xs">Date révocation :</span> <span class="tw-font-medium tw-text-red-600">{{ $request->revoked_at ? $request->revoked_at->format('d/m/Y à H:i') : 'N/A' }}</span></p>
                                @endif
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="tw-flex tw-items-start tw-gap-3 tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-xl tw-px-4 tw-py-3 tw-mb-3 tw-text-sm">
                            <i class="fas fa-comment tw-text-slate-400 tw-mt-0.5 tw-shrink-0"></i>
                            <div>
                                <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Raison de la demande</p>
                                <p class="tw-text-slate-700 tw-mb-0">{{ $request->reason }}</p>
                            </div>
                        </div>

                        @if($request->review_comment)
                        <div class="tw-flex tw-items-start tw-gap-3 tw-rounded-xl tw-px-4 tw-py-3 tw-mb-3 tw-text-sm {{ $request->status === 'approved' ? 'tw-bg-teal-50 tw-border tw-border-teal-200 tw-text-teal-700' : 'tw-bg-red-50 tw-border tw-border-red-200 tw-text-red-700' }}">
                            <i class="fas fa-comment-dots tw-mt-0.5 tw-shrink-0"></i>
                            <p class="tw-mb-0"><strong>Commentaire :</strong> {{ $request->review_comment }}</p>
                        </div>
                        @endif

                        {{-- Actions --}}
                        <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                            <a href="{{ route('produits.edit', $request->produit->id) }}"
                               class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                                <i class="fas fa-eye tw-text-xs"></i> Voir le produit
                            </a>
                            @if($request->isActive())
                            <form action="{{ route('produits.edit-permissions.revoke', $request->id) }}" method="POST" class="tw-inline"
                                  onsubmit="return confirm('Révoquer cette permission ?')">
                                @csrf
                                <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-border-0 tw-transition-colors">
                                    <i class="fas fa-ban tw-text-xs"></i> Révoquer
                                </button>
                            </form>
                            @endif
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
@endsection