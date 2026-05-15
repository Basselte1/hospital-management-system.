@extends('layouts.admin')
@section('title', 'CMCU | Détails Réception')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6">
                <div class="tw-flex tw-items-center tw-gap-3 tw-flex-wrap">
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-font-mono">{{ $reception->numero_reception }}</h1>
                    @if(!$reception->isValidated())
                    <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-medium tw-bg-amber-50 tw-text-amber-700 tw-border tw-border-amber-200">
                        <i class="fas fa-clock tw-text-xs"></i> En Attente de Validation
                    </span>
                    @else
                    <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-medium tw-bg-emerald-50 tw-text-emerald-700 tw-border tw-border-emerald-200">
                        <i class="fas fa-check-double tw-text-xs"></i> Validée
                    </span>
                    @endif
                </div>
                <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Détails de la réception de stock</p>
            </div>

            {{-- Flash --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> {{ session('success') }}
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-6">
                <a href="{{ route('stock-receptions.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
                <a href="{{ route('stock-receptions.pdf', $reception->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-rounded-xl tw-transition-colors tw-no-underline">
                    <i class="fas fa-file-pdf tw-text-xs"></i> PDF
                </a>
                @if(!$reception->isValidated() && in_array(auth()->user()->role_id, [1, 3]))
                <button type="button" data-bs-toggle="modal" data-bs-target="#validateModal"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-emerald-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-emerald-600 tw-transition-colors tw-border-0 tw-cursor-pointer">
                    <i class="fas fa-check tw-text-xs"></i> Valider et Mettre à Jour le Stock
                </button>
                @endif
            </div>

            {{-- Main Grid --}}
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-5 tw-mb-5">

                {{-- Left Column --}}
                <div class="tw-space-y-4">

                    {{-- Reception Info --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-file-alt tw-text-[#1D4ED8] tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Informations Réception</h2>
                        </div>
                        <div class="tw-p-6 tw-space-y-3">
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">N° Réception</span><span class="tw-font-semibold tw-font-mono tw-text-slate-800">{{ $reception->numero_reception }}</span></div>
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">Bon de Commande</span><span class="tw-text-slate-700">{{ $reception->bonCommande->numero_bon }}</span></div>
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">Date Réception</span><span class="tw-text-slate-700">{{ $reception->date_reception->format('d/m/Y') }}</span></div>
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">Réceptionné par</span><span class="tw-text-slate-700">{{ $reception->receivedBy->name ?? 'N/A' }}</span></div>
                            @if($reception->numero_bl)
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">N° BL</span><span class="tw-font-mono tw-text-slate-700">{{ $reception->numero_bl }}</span></div>
                            @endif
                        </div>
                    </div>

                    {{-- Validation Info --}}
                    @if($reception->isValidated())
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-check-double tw-text-emerald-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Validation</h2>
                        </div>
                        <div class="tw-p-6 tw-space-y-3">
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">Validé par</span><span class="tw-text-slate-700">{{ $reception->validatedBy->name ?? 'N/A' }}</span></div>
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">Date</span><span class="tw-text-slate-700">{{ $reception->validated_at->format('d/m/Y H:i') }}</span></div>
                            @if($reception->validation_notes)
                            <div class="tw-text-sm">
                                <span class="tw-text-slate-500 tw-block tw-mb-1">Notes</span>
                                <p class="tw-text-slate-700 tw-bg-slate-50 tw-rounded-lg tw-px-3 tw-py-2 tw-mb-0">{{ $reception->validation_notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Right Column --}}
                <div class="tw-space-y-4">

                    {{-- Stats --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-amber-50 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-chart-bar tw-text-amber-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Statistiques</h2>
                        </div>
                        <div class="tw-p-6 tw-grid tw-grid-cols-3 tw-gap-4 tw-text-center">
                            <div>
                                <p class="tw-text-2xl tw-font-bold tw-text-[#1D4ED8] tw-mb-0">{{ $reception->getTotalQuantiteRecue() }}</p>
                                <p class="tw-text-xs tw-text-slate-500 tw-mt-1 tw-mb-0">Reçues</p>
                            </div>
                            <div>
                                <p class="tw-text-2xl tw-font-bold tw-text-emerald-600 tw-mb-0">{{ $reception->getTotalQuantiteAcceptee() }}</p>
                                <p class="tw-text-xs tw-text-slate-500 tw-mt-1 tw-mb-0">Acceptées</p>
                            </div>
                            <div>
                                <p class="tw-text-2xl tw-font-bold tw-text-red-500 tw-mb-0">{{ $reception->getTotalQuantiteRefusee() }}</p>
                                <p class="tw-text-xs tw-text-slate-500 tw-mt-1 tw-mb-0">Refusées</p>
                            </div>
                        </div>
                    </div>

                    {{-- Remarques --}}
                    @if($reception->commentaire || $reception->problemes_constates)
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-comment tw-text-slate-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Remarques</h2>
                        </div>
                        <div class="tw-p-6 tw-space-y-3">
                            @if($reception->commentaire)
                            <p class="tw-text-sm tw-text-slate-700 tw-mb-0"><strong>Commentaire :</strong> {{ $reception->commentaire }}</p>
                            @endif
                            @if($reception->problemes_constates)
                            <div class="tw-flex tw-items-start tw-gap-2 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-text-sm tw-text-red-600">
                                <i class="fas fa-exclamation-triangle tw-shrink-0 tw-mt-0.5"></i>
                                <span><strong>Problèmes :</strong> {{ $reception->problemes_constates }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Products Table --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-boxes tw-text-emerald-500 tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Produits Réceptionnés</h2>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Produit</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Commandée</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Reçue</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Acceptée</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Refusée</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">État</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Observation</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($reception->items as $item)
                            <tr class="{{ $item->hasQualityIssues() ? 'tw-bg-amber-50/40' : '' }} hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-6 tw-py-4 tw-font-semibold tw-text-slate-800">{{ $item->bonCommandeItem->designation }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-center tw-text-slate-600">{{ $item->quantite_commandee }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-center tw-text-slate-600">{{ $item->quantite_recue }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-emerald-50 tw-text-emerald-700">{{ $item->quantite_acceptee }}</span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    @if($item->quantite_refusee > 0)
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-red-50 tw-text-red-600">{{ $item->quantite_refusee }}</span>
                                    @else
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-slate-100 tw-text-slate-500">0</span>
                                    @endif
                                </td>
                                <td class="tw-px-6 tw-py-4">
                                    @if($item->etat_produit === 'conforme')
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-emerald-50 tw-text-emerald-700">Conforme</span>
                                    @elseif($item->etat_produit === 'non_conforme')
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-amber-50 tw-text-amber-700">Non Conforme</span>
                                    @else
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-red-50 tw-text-red-600">Endommagé</span>
                                    @endif
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-xs tw-text-slate-500">{{ $item->observation }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- Validation Modal --}}
@if(!$reception->isValidated() && in_array(auth()->user()->role_id, [1, 3]))
<div class="modal fade" id="validateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-emerald-500">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0"><i class="fas fa-check tw-mr-2"></i>Valider la Réception</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('stock-receptions.validate', $reception->id) }}" method="POST">
                @csrf
                <div class="tw-p-6 tw-space-y-4">
                    <p class="tw-text-sm tw-text-slate-600 tw-mb-0">En validant cette réception, le stock des produits sera automatiquement mis à jour.</p>
                    <div class="tw-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-text-sm tw-text-teal-700">
                        <i class="fas fa-info-circle tw-shrink-0"></i>
                        <span><strong>{{ $reception->getTotalQuantiteAcceptee() }}</strong> produit(s) seront ajoutés au stock.</span>
                    </div>
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Notes de validation (optionnel)</label>
                        <textarea name="validation_notes" rows="3"
                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none"></textarea>
                    </div>
                </div>
                <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                    <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-emerald-500 hover:tw-bg-emerald-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-cursor-pointer">
                        <i class="fas fa-check tw-mr-1"></i> Confirmer la Validation
                    </button>
                    <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection