@extends('layouts.admin')
@section('title', 'CMCU | Facturation Pharmacie')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Facturation</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Panier produits pharmacie</p>
                </div>
            </div>

            @if(Session::has('cart'))

            {{-- Cart table card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-shopping-cart tw-text-white tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">Produits sélectionnés</h2>
                    <span class="tw-text-xs tw-text-slate-400 tw-italic tw-ml-2">Les montants sont en FCFA</span>
                </div>

                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold tw-w-[40%]">Produit</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Quantité</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Prix Unitaire</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Total</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Réduire</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Ajouter</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Retirer</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($produits as $produit)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors" data-product-id="{{ $produit['item']['id'] }}">
                                <td class="tw-px-4 tw-py-3 tw-font-medium tw-text-slate-700">
                                    {{ $produit['item']['designation'] }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <input
                                        type="number"
                                        class="quantity-input tw-w-20 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1.5 tw-text-sm tw-text-center focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"
                                        min="1"
                                        value="{{ $produit['quantite'] }}"
                                        data-product-id="{{ $produit['item']['id'] }}"
                                        data-old-qty="{{ $produit['quantite'] }}">
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-font-semibold tw-text-slate-700">
                                    {{ number_format($produit['prix_unitaire'], 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-font-bold tw-text-[#1D4ED8] item-total">
                                    {{ number_format($produit['quantite'] * $produit['prix_unitaire'], 0, ',', ' ') }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <a href="{{ route('facturation.reduire', ['id' => $produit['item']['id']]) }}"
                                       class="quantity-action tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-[#BFDBFE] tw-text-slate-600 hover:tw-text-[#1D4ED8] tw-transition-colors tw-no-underline"
                                       title="Réduire la quantité">
                                        <i class="fas fa-minus tw-text-xs"></i>
                                    </a>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <a href="{{ route('pharmaceutique.cart', $produit['item']['id']) }}"
                                       class="quantity-action tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-600 tw-transition-colors tw-no-underline"
                                       title="Ajouter une unité">
                                        <i class="fas fa-plus tw-text-xs"></i>
                                    </a>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <a href="{{ route('facturation.supprimer', ['id' => $produit['item']['id']]) }}"
                                       class="quantity-action tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-transition-colors tw-no-underline"
                                       title="Retirer du panier">
                                        <i class="fas fa-trash-alt tw-text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="tw-bg-[#BFDBFE]/20 tw-border-t-2 tw-border-[#BFDBFE]">
                                <td colspan="3" class="tw-px-4 tw-py-4 tw-text-right tw-font-bold tw-text-slate-700 tw-text-sm tw-uppercase tw-tracking-wide">
                                    Total
                                </td>
                                <td class="tw-px-4 tw-py-4 tw-text-right tw-text-xl tw-font-extrabold tw-text-[#1D4ED8] grand-total" colspan="4">
                                    {{ number_format($totalPrix, 0, ',', ' ') }}
                                    <span class="tw-text-xs tw-font-normal tw-text-slate-400">FCFA</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Print form / patient select --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-bg-slate-50 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-print tw-text-[#1D4ED8] tw-text-sm"></i>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Imprimer la Facture</h2>
                </div>
                <form action="{{ route('pharmacie.pdf') }}" method="POST" class="tw-p-5">
                    @csrf
                    <div class="tw-flex tw-items-end tw-flex-wrap tw-gap-4">
                        <div class="tw-flex-1 tw-min-w-[200px]">
                            <label for="patient" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Nom du patient
                            </label>
                            <select name="patient" id="patient"
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                <option value="">— Sélectionner un patient —</option>
                                @foreach($patient as $patients)
                                <option value="{{ $patients->name }}">{{ $patients->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                            @can('update', \App\Models\Produit::class)
                            <a href="{{ route('produits.pharmaceutique') }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-plus tw-text-xs"></i> Ajouter Pharmaceutique
                            </a>
                            @endcan

                            @can('anesthesiste', \App\Models\Produit::class)
                            <a href="{{ route('produits.anesthesiste') }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2.5 tw-transition-colors tw-no-underline">
                                <i class="fas fa-plus tw-text-xs"></i> Ajouter Anesthésiste
                            </a>
                            @endcan

                            <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-sm tw-font-medium tw-px-6 tw-py-2.5 tw-border-0 tw-transition-colors">
                                <i class="fas fa-print tw-text-xs"></i> Imprimer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @else
            {{-- Empty cart --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-py-20 tw-text-center">
                <div class="tw-w-14 tw-h-14 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-4">
                    <i class="fas fa-shopping-cart tw-text-slate-300 tw-text-2xl"></i>
                </div>
                <p class="tw-text-slate-700 tw-font-semibold tw-mb-1">Panier vide</p>
                <p class="tw-text-slate-400 tw-text-sm tw-mb-4">Ajoutez des produits depuis la liste</p>
                @can('update', \App\Models\Produit::class)
                <a href="{{ route('produits.pharmaceutique') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-sm tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline">
                    <i class="fas fa-plus tw-text-xs"></i> Produits Pharmaceutiques
                </a>
                @endcan
            </div>
            @endif

        </main>
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Persist selected patient
    const patientSelect = document.getElementById('patient');
    if (patientSelect) {
        const savedPatient = localStorage.getItem('selectedPatient');
        if (savedPatient) patientSelect.value = savedPatient;
        patientSelect.addEventListener('change', function() {
            localStorage.setItem('selectedPatient', this.value);
        });
    }

    // Handle quantity input changes
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const newQty = parseInt(this.value) || 1;
            const oldQty = parseInt(this.dataset.oldQty) || 1;
            if (newQty < 1) { this.value = 1; return; }
            updateQuantity(productId, newQty, oldQty, this);
        });
    });

    // Handle add/reduce buttons with AJAX
    document.querySelectorAll('.quantity-action').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            fetch(url, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => { data.success ? updateCartDisplay(data) : alert(data.message || 'Erreur'); })
                .catch(() => { window.location.href = url; });
        });
    });

    function updateQuantity(productId, newQty, oldQty, inputEl) {
        const diff = newQty - oldQty;
        const url = diff > 0 ? `/admin/pharmaceutiques/${productId}` : `/admin/reduire/${productId}`;
        const requests = Math.abs(diff);
        let completed = 0;
        for (let i = 0; i < requests; i++) {
            fetch(url, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    completed++;
                    if (completed === requests && data.success) {
                        updateCartDisplay(data);
                        if (inputEl) inputEl.dataset.oldQty = newQty;
                    }
                })
                .catch(() => { if (inputEl) inputEl.value = oldQty; });
        }
    }

    function updateCartDisplay(data) {
        if (data.cartEmpty) { window.location.reload(); return; }
        Object.keys(data.items).forEach(id => {
            const item = data.items[id];
            const row = document.querySelector(`tr[data-product-id="${id}"]`);
            if (row) {
                const qtyInput  = row.querySelector('.quantity-input');
                const itemTotal = row.querySelector('.item-total');
                if (qtyInput)  { qtyInput.value = item.qty; qtyInput.dataset.oldQty = item.qty; }
                if (itemTotal)   itemTotal.textContent = item.price;
            }
        });
        const grandTotal = document.querySelector('.grand-total');
        if (grandTotal) grandTotal.textContent = data.totalPrix;
        const badge = document.querySelector('.badge p');
        if (badge) badge.textContent = data.totalQte;
    }
});
</script>
@endsection