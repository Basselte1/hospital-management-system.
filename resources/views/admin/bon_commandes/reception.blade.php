@extends('layouts.admin')
@section('title', 'CMCU | Réception de Stock')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Réception de Stock</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1 tw-font-mono">BC N° {{ $bonCommande->numero_bon }}</p>
                </div>
                <a href="{{ route('bon-commandes.show', $bonCommande->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            {{-- Warning if not validated --}}
            @if($bonCommande->statut !== 'validé')
            <div class="tw-flex tw-items-start tw-gap-3 tw-rounded-xl tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-amber-700">
                <i class="fas fa-exclamation-triangle tw-mt-0.5 tw-shrink-0"></i>
                <div>
                    <strong>Attention :</strong> Ce bon de commande doit être validé avant la réception.
                    Statut actuel : <span class="tw-font-semibold tw-bg-amber-200 tw-rounded-full tw-px-2 tw-py-0.5 tw-text-xs">{{ $bonCommande->statut }}</span>
                </div>
            </div>
            @endif

            {{-- Summary Stats --}}
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-calendar tw-text-[#1D4ED8]"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Date de commande</p>
                        <p class="tw-text-sm tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ \Carbon\Carbon::parse($bonCommande->date_commande)->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-truck tw-text-amber-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Fournisseur</p>
                        <p class="tw-text-sm tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $bonCommande->fournisseur_nom }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-dollar-sign tw-text-[#14B8A6]"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Montant total</p>
                        <p class="tw-text-sm tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ number_format($bonCommande->montant_total, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
            </div>

            {{-- Reception Form --}}
            <form action="{{ route('bon-commandes.store-reception', $bonCommande->id) }}" method="POST" id="receptionForm">
                @csrf

                {{-- Items Table --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-5">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-boxes tw-text-[#1D4ED8]"></i>
                        <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Produits à réceptionner</h2>
                    </div>
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm" id="receptionTable">
                            <thead>
                                <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold tw-w-8">#</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Désignation</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Catégorie</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Qté Cmd.</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Déjà reçue</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Qté reçue aujourd'hui <span class="tw-text-red-400">*</span></th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Notes qualité</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                @foreach($bonCommande->items as $index => $item)
                                <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                    <td class="tw-px-4 tw-py-3 tw-text-slate-400 tw-text-xs">{{ $index + 1 }}</td>
                                    <td class="tw-px-4 tw-py-3">
                                        <p class="tw-font-semibold tw-text-slate-700 tw-mb-0">{{ $item->designation }}</p>
                                        <input type="hidden" name="items[{{ $item->id }}][item_id]" value="{{ $item->id }}">
                                        <input type="hidden" name="items[{{ $item->id }}][produit_id]" value="{{ $item->produit_id }}">
                                    </td>
                                    <td class="tw-px-4 tw-py-3">
                                        <span class="tw-inline-flex tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium">{{ $item->categorie }}</span>
                                    </td>
                                    <td class="tw-px-4 tw-py-3 tw-text-center tw-font-bold tw-text-slate-700">{{ $item->quantite_commandee }}</td>
                                    <td class="tw-px-4 tw-py-3 tw-text-center">
                                        <span class="tw-inline-flex tw-rounded-full tw-bg-[#BFDBFE]/40 tw-text-[#1D4ED8] tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium">{{ $item->quantite_recue ?? 0 }}</span>
                                    </td>
                                    <td class="tw-px-4 tw-py-3">
                                        <input type="number"
                                               name="items[{{ $item->id }}][quantite_recue]"
                                               class="reception-quantity tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-center focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]"
                                               min="0"
                                               max="{{ $item->quantite_commandee - ($item->quantite_recue ?? 0) }}"
                                               value="0"
                                               data-item-id="{{ $item->id }}"
                                               data-max="{{ $item->quantite_commandee - ($item->quantite_recue ?? 0) }}"
                                               required>
                                        <p class="tw-text-[10px] tw-text-slate-400 tw-text-center tw-mt-1 tw-mb-0">
                                            Reste : {{ $item->quantite_commandee - ($item->quantite_recue ?? 0) }}
                                        </p>
                                    </td>
                                    <td class="tw-px-4 tw-py-3">
                                        <textarea name="items[{{ $item->id }}][quality_notes]" rows="2"
                                            placeholder="État du produit..."
                                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-xs focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"></textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Additional Details --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-6 tw-mb-5">
                    <h3 class="tw-text-xs tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold tw-mb-4">Détails de la réception</h3>

                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-5 tw-mb-5">
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Date de réception <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="date" name="reception_date" id="reception_date"
                                value="{{ date('Y-m-d') }}" required
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Livreur / Contact</label>
                            <input type="text" name="delivery_person" id="delivery_person"
                                placeholder="Nom du livreur ou contact fournisseur"
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                        </div>
                    </div>

                    <div class="tw-mb-5">
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Notes générales de réception</label>
                        <textarea name="reception_notes" id="reception_notes" rows="3"
                            placeholder="Commentaires sur la livraison, l'état des produits..."
                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]"></textarea>
                    </div>

                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-2">Type de réception</label>
                        <div class="tw-flex tw-flex-col sm:tw-flex-row tw-gap-3">
                            <label class="tw-flex tw-items-start tw-gap-3 tw-rounded-xl tw-border-2 tw-border-[#BFDBFE] tw-bg-[#BFDBFE]/10 tw-px-4 tw-py-3 tw-cursor-pointer has-[:checked]:tw-border-[#1D4ED8] has-[:checked]:tw-bg-[#BFDBFE]/30 tw-transition-colors">
                                <input type="radio" name="reception_type" value="complete" checked class="tw-mt-0.5 tw-accent-[#1D4ED8]">
                                <div>
                                    <p class="tw-font-semibold tw-text-slate-700 tw-text-sm tw-mb-0">Réception complète</p>
                                    <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Toutes les quantités commandées sont reçues</p>
                                </div>
                            </label>
                            <label class="tw-flex tw-items-start tw-gap-3 tw-rounded-xl tw-border-2 tw-border-slate-200 tw-px-4 tw-py-3 tw-cursor-pointer has-[:checked]:tw-border-amber-400 has-[:checked]:tw-bg-amber-50 tw-transition-colors">
                                <input type="radio" name="reception_type" value="partial" class="tw-mt-0.5 tw-accent-amber-500">
                                <div>
                                    <p class="tw-font-semibold tw-text-slate-700 tw-text-sm tw-mb-0">Réception partielle</p>
                                    <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Certains produits manquent ou quantités incomplètes</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="tw-flex tw-gap-3">
                    <button type="submit" id="submitBtn"
                        class="tw-flex-1 sm:tw-flex-none tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-medium tw-px-8 tw-py-3 tw-transition-colors tw-border-0 tw-text-sm">
                        <i class="fas fa-check tw-text-xs"></i> Enregistrer la Réception
                    </button>
                    <a href="{{ route('bon-commandes.show', $bonCommande->id) }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-6 tw-py-3 tw-transition-colors tw-no-underline tw-text-sm">
                        Annuler
                    </a>
                </div>
            </form>

        </main>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('receptionForm');
    const submitBtn = document.getElementById('submitBtn');
    const quantityInputs = document.querySelectorAll('.reception-quantity');

    form.addEventListener('submit', function(e) {
        let hasQuantity = false;
        let valid = true;
        quantityInputs.forEach(input => {
            const value = parseInt(input.value) || 0;
            const max = parseInt(input.dataset.max);
            if (value > 0) hasQuantity = true;
            if (value > max) {
                e.preventDefault();
                alert(`La quantité pour cet article ne peut pas dépasser ${max}`);
                input.focus();
                valid = false;
            }
        });
        if (valid && !hasQuantity) {
            e.preventDefault();
            alert('Veuillez entrer au moins une quantité reçue');
            return;
        }
        if (valid && hasQuantity) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin tw-mr-1.5"></i> Enregistrement...';
        }
    });

    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const value = parseInt(this.value) || 0;
            const max = parseInt(this.dataset.max);
            if (value > max) {
                this.classList.add('tw-border-red-400', 'tw-ring-2', 'tw-ring-red-200');
                this.classList.remove('tw-border-slate-200');
            } else {
                this.classList.remove('tw-border-red-400', 'tw-ring-2', 'tw-ring-red-200');
                this.classList.add('tw-border-slate-200');
            }
        });
    });
});
</script>
@endpush
@endsection