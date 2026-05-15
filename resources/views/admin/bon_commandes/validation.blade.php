@extends('layouts.admin')
@section('title', 'CMCU | Validation des Bons de Commande')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6">
                <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Validation des Bons de Commande</h1>
                <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Bons en attente de validation par le Gestionnaire</p>
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

            @if($pendingOrders->count() == 0)
            {{-- Empty state --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-12 tw-text-center">
                <div class="tw-w-16 tw-h-16 tw-rounded-2xl tw-bg-teal-50 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-4">
                    <i class="fas fa-check-double tw-text-[#14B8A6] tw-text-2xl"></i>
                </div>
                <p class="tw-text-slate-500 tw-text-sm">Aucun bon de commande en attente de validation</p>
            </div>

            @else

            {{-- Batch Actions Bar --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-5 tw-mb-5">
                <form action="{{ route('bon-commandes.batch-validate') }}" method="POST" id="batchForm">
                    @csrf
                    <div class="tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                        <div>
                            <p class="tw-font-semibold tw-text-slate-700 tw-text-sm tw-mb-0">
                                <i class="fas fa-tasks tw-text-[#1D4ED8] tw-mr-1.5"></i> Actions groupées
                            </p>
                            <p class="tw-text-xs tw-text-slate-400 tw-mt-0.5 tw-mb-0">
                                <span id="selectedCount">0</span> bon(s) sélectionné(s)
                            </p>
                        </div>
                        <button type="submit" id="batchValidateBtn" disabled
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#14B8A6] tw-text-white tw-text-sm tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-transition-opacity disabled:tw-opacity-40">
                            <i class="fas fa-check-double"></i> Valider la sélection
                        </button>
                    </div>
                    <div id="batchCommentSection" class="tw-mt-4 tw-hidden">
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Commentaire (optionnel)</label>
                        <textarea name="batch_comment" rows="2" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"></textarea>
                    </div>
                </form>
            </div>

            {{-- Orders List --}}
            @foreach($pendingOrders as $bon)
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-4">

                {{-- Card Header --}}
                <div class="tw-px-5 tw-py-4 tw-bg-amber-50 tw-border-b tw-border-amber-100 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <input class="form-check-input order-checkbox tw-cursor-pointer"
                               type="checkbox"
                               name="order_ids[]"
                               value="{{ $bon->id }}"
                               form="batchForm"
                               style="width:1.1rem;height:1.1rem;">
                        <div>
                            <p class="tw-font-bold tw-text-slate-800 tw-mb-0 tw-font-mono">{{ $bon->numero_bon }}</p>
                            <p class="tw-text-xs tw-text-slate-500 tw-mb-0">{{ $bon->date_commande->format('d/m/Y') }} · {{ $bon->items->count() }} articles</p>
                        </div>
                    </div>
                    <span class="tw-font-bold tw-text-[#1D4ED8] tw-text-base">
                        {{ number_format($bon->montant_total, 0, ',', ' ') }} <span class="tw-text-xs tw-font-normal tw-text-slate-400">FCFA</span>
                    </span>
                </div>

                <div class="tw-p-5">
                    {{-- Order Meta --}}
                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-3 tw-mb-4">
                        <div class="tw-space-y-1.5">
                            <div class="tw-flex tw-gap-2 tw-text-sm"><span class="tw-text-slate-400 tw-w-28 tw-shrink-0">Fournisseur</span><span class="tw-font-medium tw-text-slate-700">{{ $bon->fournisseur_nom }}</span></div>
                            @if($bon->date_livraison_souhaitee)
                            <div class="tw-flex tw-gap-2 tw-text-sm"><span class="tw-text-slate-400 tw-w-28 tw-shrink-0">Livraison</span><span class="tw-text-slate-600">{{ $bon->date_livraison_souhaitee->format('d/m/Y') }}</span></div>
                            @endif
                        </div>
                        <div class="tw-space-y-1.5">
                            <div class="tw-flex tw-gap-2 tw-text-sm"><span class="tw-text-slate-400 tw-w-28 tw-shrink-0">Créé par</span><span class="tw-text-slate-600">{{ $bon->createdBy->name ?? 'N/A' }}</span></div>
                            <div class="tw-flex tw-gap-2 tw-text-sm"><span class="tw-text-slate-400 tw-w-28 tw-shrink-0">Date création</span><span class="tw-text-slate-600">{{ $bon->created_at->format('d/m/Y H:i') }}</span></div>
                        </div>
                    </div>

                    @if($bon->notes)
                    <div class="tw-flex tw-items-start tw-gap-2 tw-rounded-xl tw-bg-amber-50 tw-border tw-border-amber-100 tw-px-4 tw-py-3 tw-mb-4 tw-text-sm tw-text-amber-700">
                        <i class="fas fa-sticky-note tw-mt-0.5 tw-shrink-0"></i>
                        <span>{{ $bon->notes }}</span>
                    </div>
                    @endif

                    {{-- Items Table --}}
                    <div class="tw-overflow-x-auto tw-rounded-xl tw-border tw-border-slate-100 tw-mb-4">
                        <table class="tw-w-full tw-text-sm">
                            <thead class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <tr>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-text-slate-500 tw-font-semibold tw-uppercase">Désignation</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-text-slate-500 tw-font-semibold tw-uppercase">Catégorie</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-center tw-text-xs tw-text-slate-500 tw-font-semibold tw-uppercase">Quantité</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-font-semibold tw-uppercase">Prix Unit.</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-font-semibold tw-uppercase">Montant</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                @foreach($bon->items as $item)
                                <tr class="hover:tw-bg-slate-50">
                                    <td class="tw-px-4 tw-py-2.5 tw-text-slate-700">{{ $item->designation }}</td>
                                    <td class="tw-px-4 tw-py-2.5">
                                        <span class="tw-inline-flex tw-rounded-full tw-bg-slate-100 tw-text-slate-600 tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium">{{ $item->categorie }}</span>
                                    </td>
                                    <td class="tw-px-4 tw-py-2.5 tw-text-center tw-font-medium tw-text-slate-700">{{ $item->quantite_commandee }}</td>
                                    <td class="tw-px-4 tw-py-2.5 tw-text-right tw-text-slate-600">{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                                    <td class="tw-px-4 tw-py-2.5 tw-text-right tw-font-semibold tw-text-slate-700">{{ number_format($item->montant_ligne, 0, ',', ' ') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="tw-border-t-2 tw-border-[#BFDBFE] tw-bg-[#BFDBFE]/10">
                                <tr>
                                    <td colspan="4" class="tw-px-4 tw-py-2.5 tw-text-right tw-font-bold tw-text-slate-700 tw-text-xs tw-uppercase">Total</td>
                                    <td class="tw-px-4 tw-py-2.5 tw-text-right tw-font-bold tw-text-[#1D4ED8]">{{ number_format($bon->montant_total, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="tw-flex tw-flex-wrap tw-gap-2">
                        <a href="{{ route('bon-commandes.pdf', $bon->id) }}" target="_blank"
                           class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                            <i class="fas fa-file-pdf tw-text-xs"></i> PDF
                        </a>
                        <a href="{{ route('bon-commandes.show', $bon->id) }}"
                           class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                            <i class="fas fa-eye tw-text-xs"></i> Détails
                        </a>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#validateModal{{ $bon->id }}"
                            class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-teal-500 hover:tw-bg-teal-600 tw-text-white tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-border-0">
                            <i class="fas fa-check tw-text-xs"></i> Valider
                        </button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $bon->id }}"
                            class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-border-0">
                            <i class="fas fa-times tw-text-xs"></i> Rejeter
                        </button>
                    </div>
                </div>
            </div>

            {{-- Validate Modal --}}
            <div class="modal fade" id="validateModal{{ $bon->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
                        <div class="tw-px-6 tw-py-4 tw-bg-[#14B8A6] tw-flex tw-items-center tw-justify-between">
                            <h5 class="tw-text-white tw-font-semibold tw-mb-0"><i class="fas fa-check-circle tw-mr-2"></i>Valider le Bon</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('bon-commandes.validate', $bon->id) }}" method="POST">
                            @csrf
                            <div class="tw-p-6 tw-space-y-4">
                                <p class="tw-text-slate-600 tw-text-sm">Valider <strong class="tw-font-mono">{{ $bon->numero_bon }}</strong> — <strong class="tw-text-[#1D4ED8]">{{ number_format($bon->montant_total, 0, ',', ' ') }} FCFA</strong></p>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Commentaire (optionnel)</label>
                                    <textarea name="validation_comment" rows="3" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"></textarea>
                                </div>
                            </div>
                            <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                                <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-transition-colors">Confirmer</button>
                                <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Reject Modal --}}
            <div class="modal fade" id="rejectModal{{ $bon->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
                        <div class="tw-px-6 tw-py-4 tw-bg-red-500 tw-flex tw-items-center tw-justify-between">
                            <h5 class="tw-text-white tw-font-semibold tw-mb-0"><i class="fas fa-times-circle tw-mr-2"></i>Rejeter le Bon</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('bon-commandes.reject', $bon->id) }}" method="POST">
                            @csrf
                            <div class="tw-p-6 tw-space-y-4">
                                <p class="tw-text-slate-600 tw-text-sm">Rejeter <strong class="tw-font-mono">{{ $bon->numero_bon }}</strong> ?</p>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Raison du rejet <span class="tw-text-red-500">*</span></label>
                                    <textarea name="rejection_reason" rows="3" required class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-red-200"></textarea>
                                </div>
                            </div>
                            <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                                <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-transition-colors">Confirmer</button>
                                <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Pagination --}}
            @if($pendingOrders->hasPages())
            <div class="tw-mt-4">{{ $pendingOrders->links() }}</div>
            @endif
            @endif

            <div class="tw-text-center tw-mt-6">
                <a href="{{ route('bon-commandes.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour à la liste
                </a>
            </div>

        </main>
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>

@push('scripts')
<script>
$(document).ready(function() {
    $('.order-checkbox').change(function() {
        const count = $('.order-checkbox:checked').length;
        $('#selectedCount').text(count);
        $('#batchValidateBtn').prop('disabled', count === 0);
        count > 0 ? $('#batchCommentSection').removeClass('tw-hidden') : $('#batchCommentSection').addClass('tw-hidden');
    });
    $('#batchForm').submit(function(e) {
        const count = $('.order-checkbox:checked').length;
        if (!confirm(`Valider ${count} bon(s) sélectionné(s) ?`)) e.preventDefault();
    });
});
</script>
@endpush
@endsection