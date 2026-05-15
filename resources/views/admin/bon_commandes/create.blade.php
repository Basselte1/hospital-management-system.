@extends('layouts.admin')
@section('title', 'CMCU | Nouveau Bon de Commande')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Nouveau Bon de Commande</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Créer un nouveau bon de commande</p>
                </div>
                <a href="{{ route('bon-commandes.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            {{-- Flash --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i> {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('bon-commandes.store') }}" method="POST" id="bonCommandeForm">
                @csrf

                <div class="tw-space-y-5">

                    {{-- Supplier Information --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-truck tw-text-[#1D4ED8] tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Informations Fournisseur</h2>
                        </div>
                        <div class="tw-p-6">
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <label for="fournisseur_nom" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                        Nom du Fournisseur <span class="tw-text-red-500">*</span>
                                    </label>
                                    <input type="text" id="fournisseur_nom" name="fournisseur_nom"
                                           value="{{ old('fournisseur_nom') }}" required
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] @error('fournisseur_nom') tw-border-red-400 @enderror">
                                    @error('fournisseur_nom')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="fournisseur_email" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Email</label>
                                    <input type="email" id="fournisseur_email" name="fournisseur_email"
                                           value="{{ old('fournisseur_email') }}"
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] @error('fournisseur_email') tw-border-red-400 @enderror">
                                    @error('fournisseur_email')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="fournisseur_telephone" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Téléphone</label>
                                    <input type="text" id="fournisseur_telephone" name="fournisseur_telephone"
                                           value="{{ old('fournisseur_telephone') }}"
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                </div>
                                <div>
                                    <label for="fournisseur_adresse" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Adresse</label>
                                    <input type="text" id="fournisseur_adresse" name="fournisseur_adresse"
                                           value="{{ old('fournisseur_adresse') }}"
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Information --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-sky-50 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-calendar tw-text-sky-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Informations Commande</h2>
                        </div>
                        <div class="tw-p-6">
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <label for="date_commande" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                        Date de Commande <span class="tw-text-red-500">*</span>
                                    </label>
                                    <input type="date" id="date_commande" name="date_commande" required
                                           value="{{ old('date_commande', date('Y-m-d')) }}"
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] @error('date_commande') tw-border-red-400 @enderror">
                                    @error('date_commande')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="date_livraison_souhaitee" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date de Livraison Souhaitée</label>
                                    <input type="date" id="date_livraison_souhaitee" name="date_livraison_souhaitee"
                                           value="{{ old('date_livraison_souhaitee') }}"
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                </div>
                                <div class="sm:tw-col-span-2">
                                    <label for="notes" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Notes / Remarques</label>
                                    <textarea id="notes" name="notes" rows="3"
                                              class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Products List --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center">
                                    <i class="fas fa-boxes tw-text-emerald-500 tw-text-sm"></i>
                                </div>
                                <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Produits à Commander</h2>
                            </div>
                            <button type="button" id="addItemBtn"
                                    class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-bg-emerald-50 hover:tw-bg-emerald-100 tw-text-emerald-700 tw-text-xs tw-font-medium tw-rounded-lg tw-border tw-border-emerald-200 tw-cursor-pointer tw-transition-colors">
                                <i class="fas fa-plus tw-text-xs"></i> Ajouter un produit
                            </button>
                        </div>
                        <div class="tw-overflow-x-auto">
                            <table class="tw-w-full tw-text-sm" id="itemsTable">
                                <thead>
                                    <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[30%]">Désignation <span class="tw-text-red-500">*</span></th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[15%]">Catégorie <span class="tw-text-red-500">*</span></th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[15%]">Quantité <span class="tw-text-red-500">*</span></th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[15%]">Prix Unitaire <span class="tw-text-red-500">*</span></th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[15%]">Montant</th>
                                        <th class="tw-px-4 tw-py-3 tw-w-[10%]"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody" class="tw-divide-y tw-divide-slate-100">
                                    <!-- Items will be added dynamically -->
                                </tbody>
                                <tfoot>
                                    <tr class="tw-bg-[#BFDBFE]/20 tw-border-t tw-border-[#BFDBFE]">
                                        <td colspan="4" class="tw-px-4 tw-py-3 tw-text-right tw-font-semibold tw-text-slate-700">TOTAL :</td>
                                        <td colspan="2" class="tw-px-4 tw-py-3 tw-font-bold tw-text-[#1D4ED8]" id="totalAmount">0 FCFA</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="tw-flex tw-justify-center tw-gap-3 tw-pb-4">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-amber-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-amber-600 tw-transition-colors tw-border-0 tw-cursor-pointer">
                            <i class="fas fa-save"></i> Enregistrer le Bon de Commande
                        </button>
                        <a href="{{ route('bon-commandes.index') }}"
                           class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>

                </div>
            </form>

        </main>
    </div>
</div>

{{-- Product Selection Modal --}}
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#0F2554]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0"><i class="fas fa-search tw-mr-2"></i>Sélectionner un Produit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="tw-p-6">
                <input type="text" id="searchProduct" placeholder="Rechercher un produit..."
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-mb-4 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm" id="productsTable">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Désignation</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Catégorie</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Stock</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Prix</th>
                                <th class="tw-px-4 tw-py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($produits as $produit)
                            <tr class="hover:tw-bg-slate-50">
                                <td class="tw-px-4 tw-py-3 tw-font-medium tw-text-slate-700">{{ $produit->designation }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    <span class="tw-inline-flex tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-bg-slate-100 tw-text-slate-600">{{ $produit->categorie }}</span>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600">{{ $produit->qte_stock }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600">{{ number_format($produit->prix_unitaire, 0, ',', ' ') }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    <button type="button" class="select-product tw-inline-flex tw-items-center tw-px-3 tw-py-1.5 tw-bg-[#1D4ED8] tw-text-white tw-text-xs tw-font-medium tw-rounded-lg hover:tw-bg-[#1a46c5] tw-border-0 tw-cursor-pointer tw-transition-colors"
                                            data-id="{{ $produit->id }}"
                                            data-designation="{{ $produit->designation }}"
                                            data-categorie="{{ $produit->categorie }}"
                                            data-prix="{{ $produit->prix_unitaire }}">
                                        Sélectionner
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>

@push('scripts')
<script>
waitForjQuery(function() {

let itemCounter = 0;
let currentRow = null;

$(document).ready(function() {
    // Add first item automatically
    addItem();

    $('#addItemBtn').click(function() { addItem(); });

    $(document).on('click', '.designation-input', function() {
        currentRow = $(this).closest('tr');
        $('#productModal').modal('show');
    });

    $(document).on('click', '.select-product', function() {
        const produitId = $(this).data('id');
        const designation = $(this).data('designation');
        const categorie = $(this).data('categorie');
        const prix = $(this).data('prix');
        if (currentRow) {
            currentRow.find('.produit-id').val(produitId);
            currentRow.find('.designation-input').val(designation);
            currentRow.find('.categorie-select').val(categorie);
            currentRow.find('.prix-input').val(prix);
            calculateLineTotal(currentRow);
            calculateGrandTotal();
        }
        $('#productModal').modal('hide');
    });

    $('#searchProduct').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#productsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $(document).on('input', '.quantite-input, .prix-input', function() {
        calculateLineTotal($(this).closest('tr'));
        calculateGrandTotal();
    });

    $(document).on('click', '.remove-item', function() {
        if ($('#itemsBody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        } else {
            alert('Vous devez avoir au moins un produit dans la commande');
        }
    });
});

function addItem() {
    itemCounter++;
    const row = `
        <tr class="hover:tw-bg-slate-50/50 tw-border-b tw-border-slate-100">
            <td class="tw-px-4 tw-py-3">
                <input type="hidden" name="items[${itemCounter}][produit_id]" class="produit-id">
                <input type="text" class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-cursor-pointer designation-input"
                       name="items[${itemCounter}][designation]" placeholder="Cliquez pour sélectionner..." required readonly>
            </td>
            <td class="tw-px-4 tw-py-3">
                <select class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] categorie-select" name="items[${itemCounter}][categorie]" required>
                    <option value="PHARMACEUTIQUE">PHARMACEUTIQUE</option>
                    <option value="MATERIEL">MATERIEL</option>
                    <option value="ANESTHESISTE">ANESTHESISTE</option>
                </select>
            </td>
            <td class="tw-px-4 tw-py-3">
                <input type="number" class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] quantite-input"
                       name="items[${itemCounter}][quantite]" min="1" value="1" required>
            </td>
            <td class="tw-px-4 tw-py-3">
                <input type="number" class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] prix-input"
                       name="items[${itemCounter}][prix_unitaire]" min="0" value="0" required>
            </td>
            <td class="tw-px-4 tw-py-3 tw-font-medium tw-text-slate-700 montant-cell">0 FCFA</td>
            <td class="tw-px-4 tw-py-3 tw-text-center">
                <button type="button" class="remove-item tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-cursor-pointer tw-transition-colors">
                    <i class="fas fa-trash tw-text-xs"></i>
                </button>
            </td>
        </tr>
    `;
    $('#itemsBody').append(row);
}

function calculateLineTotal(row) {
    const quantite = parseInt(row.find('.quantite-input').val()) || 0;
    const prix = parseInt(row.find('.prix-input').val()) || 0;
    row.find('.montant-cell').text((quantite * prix).toLocaleString('fr-FR') + ' FCFA');
}

function calculateGrandTotal() {
    let grandTotal = 0;
    $('#itemsBody tr').each(function() {
        const q = parseInt($(this).find('.quantite-input').val()) || 0;
        const p = parseInt($(this).find('.prix-input').val()) || 0;
        grandTotal += (q * p);
    });
    $('#totalAmount').text(grandTotal.toLocaleString('fr-FR') + ' FCFA');
}

});
</script>
@endpush

@endsection