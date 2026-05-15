@extends('layouts.admin')
@section('title', 'CMCU | Enregistrer Utilisation')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Enregistrer Utilisation</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Enregistrer l'utilisation de produits réutilisables</p>
                </div>
                <a href="{{ route('reusable-products.index') }}"
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
            @if($errors->any())
            <div class="tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5">
                <p class="tw-text-sm tw-font-semibold tw-text-red-600 tw-mb-2">Erreurs de validation :</p>
                <ul class="tw-text-xs tw-text-red-500 tw-list-disc tw-pl-4 tw-space-y-0.5 tw-mb-0">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="tw-max-w-3xl tw-mx-auto">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                    <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-clipboard-list tw-text-[#1D4ED8] tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Formulaire d'Utilisation</h2>
                    </div>

                    <div class="tw-p-6">
                        <form action="{{ route('reusable-products.record-usage.store') }}" method="POST" id="usageForm" class="tw-space-y-6">
                            @csrf

                            {{-- Section: Product --}}
                            <div>
                                <h3 class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                                    <i class="fas fa-box tw-text-[#1D4ED8]"></i> Sélection du Produit
                                </h3>
                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">
                                    <div class="sm:tw-col-span-2">
                                        <label for="produit_id" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                            Produit <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select name="produit_id" id="produit_id" required
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                            <option value="">-- Sélectionner un produit --</option>
                                            @php
                                                $reusableProducts = $products->where('is_reusable', true);
                                                $nonReusableProducts = $products->where('is_reusable', false);
                                            @endphp
                                            @if($reusableProducts->count() > 0)
                                            <optgroup label="━━ PRODUITS RÉUTILISABLES ━━">
                                                @foreach($reusableProducts as $produit)
                                                @php $disponible = $produit->qte_stock - $produit->qte_en_utilisation - $produit->qte_en_sterilisation; @endphp
                                                <option value="{{ $produit->id }}" data-stock="{{ $produit->qte_stock }}" data-disponible="{{ $disponible }}" data-is-reusable="true" {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                                                    {{ $produit->designation }} ({{ $produit->categorie }}) - Dispo: {{ $disponible }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endif
                                            @if($nonReusableProducts->count() > 0)
                                            <optgroup label="━━ MATÉRIEL (Non Réutilisables) ━━">
                                                @foreach($nonReusableProducts as $produit)
                                                <option value="{{ $produit->id }}" data-stock="{{ $produit->qte_stock }}" data-disponible="{{ $produit->qte_stock }}" data-is-reusable="false" {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                                                    {{ $produit->designation }} ({{ $produit->categorie }}) - Stock: {{ $produit->qte_stock }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endif
                                        </select>
                                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1"><i class="fas fa-info-circle tw-mr-1"></i>Les produits réutilisables sont affichés en premier</p>
                                    </div>
                                    <div>
                                        <label for="quantite" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                            Quantité <span class="tw-text-red-500">*</span>
                                        </label>
                                        <input type="number" name="quantite" id="quantite" min="1" value="{{ old('quantite', 1) }}" required
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        <p class="tw-text-xs tw-mt-1" id="stock-info">
                                            <span class="tw-text-slate-400">Sélectionnez un produit pour voir le stock</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Details --}}
                            <div>
                                <h3 class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                                    <i class="fas fa-info-circle tw-text-[#1D4ED8]"></i> Détails d'Utilisation
                                </h3>
                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label for="type_utilisation" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                            Type d'Utilisation <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select name="type_utilisation" id="type_utilisation" required
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                            <option value="">-- Sélectionner --</option>
                                            <option value="intervention_chirurgicale" {{ old('type_utilisation') == 'intervention_chirurgicale' ? 'selected' : '' }}>Intervention Chirurgicale</option>
                                            <option value="consultation" {{ old('type_utilisation') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                            <option value="hospitalisation" {{ old('type_utilisation') == 'hospitalisation' ? 'selected' : '' }}>Hospitalisation</option>
                                            <option value="urgence" {{ old('type_utilisation') == 'urgence' ? 'selected' : '' }}>Urgence</option>
                                            <option value="autre" {{ old('type_utilisation') == 'autre' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="service" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Service</label>
                                        <input type="text" name="service" id="service" value="{{ old('service') }}" placeholder="Ex: Bloc opératoire, Urgences..."
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    </div>
                                    <div>
                                        <label for="date_utilisation" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                            Date d'Utilisation <span class="tw-text-red-500">*</span>
                                        </label>
                                        <input type="date" name="date_utilisation" id="date_utilisation" value="{{ old('date_utilisation', date('Y-m-d')) }}" required
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    </div>
                                    <div>
                                        <label for="heure_utilisation" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Heure d'Utilisation</label>
                                        <input type="time" name="heure_utilisation" id="heure_utilisation" value="{{ old('heure_utilisation') }}"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Patient & Staff --}}
                            <div>
                                <h3 class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                                    <i class="fas fa-user-md tw-text-[#1D4ED8]"></i> Patient et Personnel
                                </h3>
                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">
                                    <div>
                                        <label for="patient_id" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Patient</label>
                                        <select name="patient_id" id="patient_id"
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                            <option value="">-- Aucun patient --</option>
                                            @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                {{ $patient->name }} {{ $patient->prenom }} ({{ $patient->numero_dossier }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="medecin_id" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Médecin</label>
                                        <select name="medecin_id" id="medecin_id"
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                            <option value="">-- Sélectionner --</option>
                                            @foreach($medecins as $medecin)
                                            <option value="{{ $medecin->id }}" {{ old('medecin_id') == $medecin->id ? 'selected' : '' }}>Dr. {{ $medecin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="infirmier_id" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Infirmier(ère)</label>
                                        <select name="infirmier_id" id="infirmier_id"
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                            <option value="">-- Sélectionner --</option>
                                            @foreach($infirmiers as $infirmier)
                                            <option value="{{ $infirmier->id }}" {{ old('infirmier_id') == $infirmier->id ? 'selected' : '' }}>{{ $infirmier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Return (reusable only) --}}
                            <div id="return-section" class="tw-hidden">
                                <h3 class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                                    <i class="fas fa-recycle tw-text-emerald-500"></i> Informations de Retour <span class="tw-text-[10px] tw-normal-case tw-text-slate-400 tw-tracking-normal">(Produits Réutilisables)</span>
                                </h3>
                                <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label for="quantite_retournable" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Quantité Retournable</label>
                                        <input type="number" name="quantite_retournable" id="quantite_retournable" min="0" value="{{ old('quantite_retournable') }}"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Laisser vide si toute la quantité est retournable</p>
                                    </div>
                                    <div>
                                        <label for="quantite_perdue" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Quantité Perdue/Endommagée</label>
                                        <input type="number" name="quantite_perdue" id="quantite_perdue" min="0" value="{{ old('quantite_perdue', 0) }}"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Notes --}}
                            <div>
                                <h3 class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                                    <i class="fas fa-comment tw-text-[#1D4ED8]"></i> Informations Complémentaires
                                </h3>
                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label for="motif" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Motif d'Utilisation</label>
                                        <textarea name="motif" id="motif" rows="3" placeholder="Raison de l'utilisation..."
                                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none">{{ old('motif') }}</textarea>
                                    </div>
                                    <div>
                                        <label for="observations" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Observations</label>
                                        <textarea name="observations" id="observations" rows="3" placeholder="Remarques particulières..."
                                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none">{{ old('observations') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="tw-flex tw-justify-center tw-gap-3 tw-pt-2">
                                <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer">
                                    <i class="fas fa-save"></i> Enregistrer l'Utilisation
                                </button>
                                <a href="{{ route('reusable-products.index') }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
<script src="{{ asset('admin/js/main.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const produitSelect = document.getElementById('produit_id');
    const quantiteInput = document.getElementById('quantite');
    const stockInfo = document.getElementById('stock-info');
    const returnSection = document.getElementById('return-section');
    const quantiteRetournableInput = document.getElementById('quantite_retournable');

    produitSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const disponible = selectedOption.dataset.disponible;
            const isReusable = selectedOption.dataset.isReusable === 'true';
            stockInfo.innerHTML = `<span class="${isReusable ? 'tw-text-emerald-600' : 'tw-text-slate-400'}"><i class="fas fa-info-circle tw-mr-1"></i>Stock disponible: <strong>${disponible}</strong> unité(s)</span>`;
            returnSection.classList.toggle('tw-hidden', !isReusable);
            quantiteInput.max = disponible;
        } else {
            stockInfo.innerHTML = '<span class="tw-text-slate-400">Sélectionnez un produit pour voir le stock</span>';
            returnSection.classList.add('tw-hidden');
            quantiteInput.max = '';
        }
    });

    quantiteInput.addEventListener('input', function() {
        if (!returnSection.classList.contains('tw-hidden') && !quantiteRetournableInput.value) {
            const qPerdue = parseInt(document.getElementById('quantite_perdue').value) || 0;
            quantiteRetournableInput.value = Math.max(0, (parseInt(this.value) || 0) - qPerdue);
        }
    });
    document.getElementById('quantite_perdue').addEventListener('input', function() {
        quantiteRetournableInput.value = Math.max(0, (parseInt(quantiteInput.value) || 0) - (parseInt(this.value) || 0));
    });

    if (produitSelect.value) produitSelect.dispatchEvent(new Event('change'));
});
</script>
@endsection