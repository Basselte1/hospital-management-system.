@extends('layouts.admin')
@section('title', 'CMCU | Vente Externe')
@section('breadcrumb', 'Pharmacie / Nouvelle Vente Externe')
@section('page_title', 'Vente Externe')

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
                        <i class="fas fa-hospital tw-text-[#14B8A6]"></i> Nouvelle Vente Externe
                    </h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Vente à un autre hôpital ou client externe</p>
                </div>
                <a href="{{ route('pharmacie.index') }}"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour à la pharmacie
                </a>
            </div>

            <form action="{{ route('pharmacie.sales.external.store') }}" method="POST" id="external-sale-form">
                @csrf

                {{-- Client Information --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-6">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-user tw-text-[#1D4ED8]"></i>
                        <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Informations Client</h2>
                    </div>
                    <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Nom <span class="tw-text-red-500">*</span></label>
                            <input type="text" name="client_nom" required
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Prénom</label>
                            <input type="text" name="client_prenom"
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Institution / Hôpital</label>
                            <input type="text" name="client_institution" placeholder="Ex: Hôpital Central de Yaoundé"
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                        </div>
                    </div>
                </div>

                {{-- Products Section --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-6">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-pills tw-text-[#14B8A6]"></i>
                        <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Produits à Vendre</h2>
                    </div>
                    <div class="tw-p-6">
                        <div id="items-container" class="tw-space-y-3">
                            <div class="item-row tw-grid tw-grid-cols-12 tw-gap-3 tw-items-end tw-bg-slate-50 tw-rounded-xl tw-p-4">
                                <div class="tw-col-span-12 sm:tw-col-span-5">
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Produit <span class="tw-text-red-500">*</span></label>
                                    <select name="items[0][produit_id]" class="product-select tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]" required>
                                        <option value="">Sélectionner un produit...</option>
                                        @foreach($produits as $p)
                                        <option value="{{ $p->id }}" data-prix="{{ $p->prix_unitaire }}" data-stock="{{ $p->qte_stock }}">
                                            {{ $p->designation }} (Stock: {{ $p->qte_stock }}) — {{ number_format($p->prix_unitaire) }} FCFA
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="tw-col-span-4 sm:tw-col-span-2">
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Quantité <span class="tw-text-red-500">*</span></label>
                                    <input type="number" name="items[0][quantite]" class="quantity-input tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]" value="1" min="1" required>
                                </div>
                                <div class="tw-col-span-4 sm:tw-col-span-2">
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Prix Unit.</label>
                                    <input type="text" class="prix-display tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500" readonly>
                                </div>
                                <div class="tw-col-span-4 sm:tw-col-span-2">
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Total</label>
                                    <input type="text" class="total-display tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-font-semibold tw-text-slate-700" readonly>
                                </div>
                                <div class="tw-col-span-12 sm:tw-col-span-1 tw-flex tw-justify-end">
                                    <button type="button" class="remove-item tw-flex tw-items-center tw-justify-center tw-w-9 tw-h-9 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-transition-colors">
                                        <i class="fas fa-trash tw-text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-item"
                            class="tw-mt-4 tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border-2 tw-border-dashed tw-border-[#BFDBFE] tw-bg-transparent hover:tw-bg-[#BFDBFE]/20 tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-border-0">
                            <i class="fas fa-plus tw-text-xs"></i> Ajouter un Produit
                        </button>
                    </div>
                </div>

                {{-- Notes and Total --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-mb-6">
                    <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-6 tw-items-end">
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Notes / Remarques</label>
                            <textarea name="notes" rows="3" placeholder="Ajouter des notes sur cette vente..."
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]"></textarea>
                        </div>
                        <div class="tw-text-right">
                            <p class="tw-text-sm tw-text-slate-500 tw-mb-1">Montant Total</p>
                            <p class="tw-text-3xl tw-font-bold tw-text-[#1D4ED8] tw-mb-0" id="grand-total">0 FCFA</p>
                        </div>
                    </div>
                </div>

                <div class="tw-bg-[#BFDBFE]/20 tw-border tw-border-[#BFDBFE] tw-rounded-xl tw-px-4 tw-py-3 tw-mb-6 tw-flex tw-items-start tw-gap-3 tw-text-sm tw-text-[#1D4ED8]">
                    <i class="fas fa-info-circle tw-mt-0.5 tw-shrink-0"></i>
                    <span>Une facture sera générée et devra être payée à la caisse avant la remise des produits.</span>
                </div>

                {{-- Actions --}}
                <div class="tw-flex tw-justify-end tw-gap-3">
                    <a href="{{ route('pharmacie.index') }}"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-6 tw-py-2.5 tw-transition-colors tw-no-underline">
                        <i class="fas fa-times tw-text-xs"></i> Annuler
                    </a>
                    <button type="submit"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-medium tw-px-6 tw-py-2.5 tw-transition-colors tw-border-0">
                        <i class="fas fa-save tw-text-xs"></i> Créer la Vente
                    </button>
                </div>
            </form>

        </main>
    </div>
</div>

@push('scripts')
<script>
waitForjQuery(function() {
    $(document).ready(function() {
        let itemCounter = 1;

        // Template for new row
        function buildRow(idx) {
            return `
            <div class="item-row tw-grid tw-grid-cols-12 tw-gap-3 tw-items-end tw-bg-slate-50 tw-rounded-xl tw-p-4">
                <div class="tw-col-span-12 sm:tw-col-span-5">
                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Produit *</label>
                    <select name="items[${idx}][produit_id]" class="product-select tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none" required>
                        <option value="">Sélectionner...</option>
                        @foreach($produits as $p)
                        <option value="{{ $p->id }}" data-prix="{{ $p->prix_unitaire }}" data-stock="{{ $p->qte_stock }}">
                            {{ $p->designation }} (Stock: {{ $p->qte_stock }}) — {{ number_format($p->prix_unitaire) }} FCFA
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="tw-col-span-4 sm:tw-col-span-2">
                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Quantité *</label>
                    <input type="number" name="items[${idx}][quantite]" class="quantity-input tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none" value="1" min="1" required>
                </div>
                <div class="tw-col-span-4 sm:tw-col-span-2">
                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Prix Unit.</label>
                    <input type="text" class="prix-display tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500" readonly>
                </div>
                <div class="tw-col-span-4 sm:tw-col-span-2">
                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Total</label>
                    <input type="text" class="total-display tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-font-semibold tw-text-slate-700" readonly>
                </div>
                <div class="tw-col-span-12 sm:tw-col-span-1 tw-flex tw-justify-end">
                    <button type="button" class="remove-item tw-flex tw-items-center tw-justify-center tw-w-9 tw-h-9 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-transition-colors">
                        <i class="fas fa-trash tw-text-xs"></i>
                    </button>
                </div>
            </div>`;
        }

        $('#add-item').click(function() {
            $('#items-container').append(buildRow(itemCounter++));
        });

        $(document).on('click', '.remove-item', function() {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
                calculateTotal();
            } else {
                alert('Il faut au moins un produit');
            }
        });

        $(document).on('change', '.product-select', function() {
            let sel = $(this).find(':selected');
            let row = $(this).closest('.item-row');
            row.find('.prix-display').val(sel.data('prix') ? sel.data('prix').toLocaleString() : '');
            row.find('.quantity-input').attr('max', sel.data('stock'));
            calculateRowTotal(row);
        });

        $(document).on('input', '.quantity-input', function() {
            let row = $(this).closest('.item-row');
            let max = parseInt($(this).attr('max'));
            let val = parseInt($(this).val());
            if (max && val > max) { alert('Stock insuffisant! Maximum: ' + max); $(this).val(max); }
            calculateRowTotal(row);
        });

        function calculateRowTotal(row) {
            let prix = parseInt(row.find('.product-select :selected').data('prix')) || 0;
            let qty = parseInt(row.find('.quantity-input').val()) || 0;
            row.find('.total-display').val((prix * qty).toLocaleString());
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            $('.item-row').each(function() {
                let prix = parseInt($(this).find('.product-select :selected').data('prix')) || 0;
                let qty = parseInt($(this).find('.quantity-input').val()) || 0;
                total += prix * qty;
            });
            $('#grand-total').text(total.toLocaleString() + ' FCFA');
        }

        $('#external-sale-form').submit(function(e) {
            if (!$('input[name="client_nom"]').val().trim()) {
                e.preventDefault(); alert('Veuillez entrer le nom du client'); return false;
            }
            let hasProduct = false;
            $('.product-select').each(function() { if ($(this).val()) hasProduct = true; });
            if (!hasProduct) { e.preventDefault(); alert('Veuillez sélectionner au moins un produit'); return false; }
        });

        calculateTotal();
    });
});
</script>
@endpush
@endsection