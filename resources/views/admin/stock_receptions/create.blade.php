@extends('layouts.admin')
@section('title', 'CMCU | Réception de Stock')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6 tw-flex tw-items-start tw-justify-between tw-flex-wrap tw-gap-4">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Réceptionner Bon de Commande</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1 tw-font-mono">{{ $bonCommande->numero_bon }}</p>
                </div>
                <a href="{{ route('stock-receptions.ready') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            {{-- Bon Commande Info Card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-info-circle tw-text-[#1D4ED8] tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Informations du Bon de Commande</h2>
                </div>
                <div class="tw-p-6">
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Fournisseur</p>
                            <p class="tw-text-sm tw-font-medium tw-text-slate-700">{{ $bonCommande->fournisseur_nom }}</p>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Date Commande</p>
                            <p class="tw-text-sm tw-font-medium tw-text-slate-700">{{ $bonCommande->date_commande->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Montant Total</p>
                            <p class="tw-text-sm tw-font-bold tw-text-[#1D4ED8]">{{ number_format($bonCommande->montant_total, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Statut</p>
                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-bg-emerald-100 tw-text-emerald-700 tw-px-3 tw-py-1 tw-text-xs tw-font-semibold">
                                <i class="fas fa-check-circle tw-text-xs"></i> Validé
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reception Form --}}
            <form action="{{ route('stock-receptions.store') }}" method="POST" id="receptionForm">
                @csrf
                <input type="hidden" name="bon_commande_id" value="{{ $bonCommande->id }}">

                {{-- Reception Details Card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                    <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-sky-50 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-clipboard-list tw-text-sky-500 tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Détails de la Réception</h2>
                    </div>
                    <div class="tw-p-6">
                        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Date de Réception <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="date" name="date_reception"
                                       value="{{ old('date_reception', date('Y-m-d')) }}" required
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Numéro BL</label>
                                <input type="text" name="numero_bl" value="{{ old('numero_bl') }}"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Nom du Livreur</label>
                                <input type="text" name="livreur_nom" value="{{ old('livreur_nom') }}"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Téléphone Livreur</label>
                                <input type="text" name="livreur_telephone" value="{{ old('livreur_telephone') }}"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Condition de Livraison</label>
                                <select name="condition_livraison"
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    <option value="">Sélectionner...</option>
                                    <option value="excellente">Excellente</option>
                                    <option value="bonne">Bonne</option>
                                    <option value="acceptable">Acceptable</option>
                                    <option value="mauvaise">Mauvaise</option>
                                </select>
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Commentaire Général</label>
                                <textarea name="commentaire" rows="2"
                                          class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none">{{ old('commentaire') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Products Reception Card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                    <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-amber-50 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-boxes tw-text-amber-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Produits à Réceptionner</h2>
                        </div>
                    </div>
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm">
                            <thead>
                                <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Désignation</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Catégorie</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Qté Cmd.</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Qté Reçue</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">État</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Produit</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                @foreach($bonCommande->items as $index => $item)
                                <tr class="hover:tw-bg-slate-50 product-row">
                                    <td class="tw-px-4 tw-py-3">
                                        <span class="tw-font-medium tw-text-slate-700">{{ $item->designation }}</span>
                                        <input type="hidden" name="items[{{ $index }}][bon_commande_item_id]" value="{{ $item->id }}">
                                    </td>
                                    <td class="tw-px-4 tw-py-3">
                                        <span class="tw-inline-flex tw-rounded-full tw-bg-slate-100 tw-text-slate-600 tw-px-2.5 tw-py-0.5 tw-text-xs">{{ $item->categorie }}</span>
                                    </td>
                                    <td class="tw-px-4 tw-py-3 tw-text-center tw-font-semibold tw-text-slate-700">{{ $item->quantite_commandee }}</td>
                                    <td class="tw-px-4 tw-py-3">
                                        <input type="number"
                                               name="items[{{ $index }}][quantite_recue]"
                                               class="tw-w-24 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] qte-recue"
                                               min="0"
                                               max="{{ $item->quantite_commandee }}"
                                               value="{{ $item->quantite_commandee }}"
                                               required>
                                    </td>
                                    <td class="tw-px-4 tw-py-3">
                                        <select name="items[{{ $index }}][etat_produit]"
                                                class="tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] etat-produit">
                                            <option value="conforme">Conforme</option>
                                            <option value="non_conforme">Non Conforme</option>
                                            <option value="endommage">Endommagé</option>
                                        </select>
                                    </td>
                                    <td class="tw-px-4 tw-py-3">
                                        @if($productsStatus[$item->id]['exists'])
                                            <div class="tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-rounded-lg tw-p-2">
                                                <div class="tw-flex tw-items-center tw-gap-1.5 tw-text-emerald-700">
                                                    <i class="fas fa-check-circle tw-text-xs"></i>
                                                    <span class="tw-text-xs tw-font-medium">{{ $productsStatus[$item->id]['product']->designation }}</span>
                                                </div>
                                                <div class="tw-text-xs tw-text-slate-500 tw-mt-1">Stock: {{ $productsStatus[$item->id]['product']->qte_stock }}</div>
                                                <input type="hidden" name="items[{{ $index }}][existing_product_id]" value="{{ $productsStatus[$item->id]['product']->id }}">
                                                <input type="hidden" name="items[{{ $index }}][create_new_product]" value="0">
                                            </div>
                                        @else
                                            <div class="tw-bg-amber-50 tw-border tw-border-amber-200 tw-rounded-lg tw-p-2">
                                                <div class="tw-flex tw-items-center tw-gap-1.5 tw-text-amber-700">
                                                    <i class="fas fa-exclamation-triangle tw-text-xs"></i>
                                                    <span class="tw-text-xs tw-font-medium">Nouveau produit</span>
                                                </div>
                                                <input type="hidden" name="items[{{ $index }}][create_new_product]" value="1">
                                            </div>
                                        @endif
                                    </td>
                                    <td class="tw-px-4 tw-py-3 tw-text-center">
                                        <button type="button"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-transition-colors tw-border-0 tw-cursor-pointer"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailsModal{{ $index }}">
                                            <i class="fas fa-edit tw-text-xs"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Details Modal --}}
                                <div class="modal fade" id="detailsModal{{ $index }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
                                            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#0F2554]">
                                                <h5 class="tw-text-white tw-font-semibold tw-mb-0"><i class="fas fa-boxes tw-mr-2"></i>Détails: {{ $item->designation }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="tw-p-6">
                                                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                                                    <div>
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Quantité Acceptée</label>
                                                        <input type="number" name="items[{{ $index }}][quantite_acceptee]" min="0"
                                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Laisser vide si toute la quantité reçue est acceptée</p>
                                                    </div>
                                                    <div>
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Quantité Refusée</label>
                                                        <input type="number" name="items[{{ $index }}][quantite_refusee]" min="0" value="0"
                                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                    </div>
                                                    <div>
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date de Péremption</label>
                                                        <input type="date" name="items[{{ $index }}][date_peremption]"
                                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                    </div>
                                                    <div>
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Numéro de Lot</label>
                                                        <input type="text" name="items[{{ $index }}][numero_lot]"
                                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                    </div>
                                                    <div class="md:tw-col-span-2">
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Observation</label>
                                                        <textarea name="items[{{ $index }}][observation]" rows="2"
                                                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none"></textarea>
                                                    </div>
                                                </div>

                                                @if(!$productsStatus[$item->id]['exists'])
                                                <hr class="tw-my-4">
                                                <h6 class="tw-text-sm tw-font-semibold tw-text-amber-600 tw-mb-3">
                                                    <i class="fas fa-plus-circle tw-mr-1"></i> Informations du Nouveau Produit
                                                </h6>
                                                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                                                    <div>
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Catégorie <span class="tw-text-red-500">*</span></label>
                                                        <select name="items[{{ $index }}][new_product_categorie]"
                                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]" required>
                                                            <option value="">Sélectionner...</option>
                                                            <option value="PHARMACEUTIQUE" {{ $item->categorie == 'PHARMACEUTIQUE' ? 'selected' : '' }}>PHARMACEUTIQUE</option>
                                                            <option value="MATERIEL" {{ $item->categorie == 'MATERIEL' ? 'selected' : '' }}>MATERIEL MEDICAL</option>
                                                            <option value="ANESTHESISTE" {{ $item->categorie == 'ANESTHESISTE' ? 'selected' : '' }}>ANESTHESISTE</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Prix Unitaire (FCFA) <span class="tw-text-red-500">*</span></label>
                                                        <input type="number" name="items[{{ $index }}][new_product_prix_unitaire]" min="0"
                                                               value="{{ $item->prix_unitaire }}" required
                                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                    </div>
                                                    <div>
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Quantité d'Alerte <span class="tw-text-red-500">*</span></label>
                                                        <input type="number" name="items[{{ $index }}][new_product_qte_alerte]" min="0"
                                                               value="10" required
                                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="tw-px-6 tw-pb-6 tw-flex tw-justify-end">
                                                <button type="button" class="tw-px-4 tw-py-2 tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl tw-border-0 tw-cursor-pointer" data-bs-dismiss="modal">
                                                    Fermer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="tw-flex tw-justify-center tw-gap-3 tw-pb-4">
                    <button type="submit"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-amber-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-amber-600 tw-transition-colors tw-border-0 tw-cursor-pointer">
                        <i class="fas fa-save"></i> Enregistrer la Réception
                    </button>
                    <a href="{{ route('stock-receptions.ready') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>

        </main>
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>

@push('scripts')
<script>
waitForjQuery(function() {
    $(document).ready(function() {
        $('#receptionForm').submit(function(e) {
            let valid = true;
            $('.qte-recue').each(function() {
                const qty = parseInt($(this).val());
                if (isNaN(qty) || qty < 0) {
                    valid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            if (!valid) {
                e.preventDefault();
                alert('Veuillez vérifier les quantités saisies');
            }
        });

        $('.qte-recue').change(function() {
            const row = $(this).closest('.product-row');
            const etat = row.find('.etat-produit').val();
            // Optional auto-fill logic
        });
    });
});
</script>
@endpush

@endsection