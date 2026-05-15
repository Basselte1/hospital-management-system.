@extends('layouts.admin')
@section('title', 'CMCU | Ajouter un produit')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('create', \App\Models\Produit::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Ajouter un Produit</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Enregistrer un nouveau produit dans le stock</p>
                </div>
                <a href="{{ route('produits.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            {{-- Validation errors --}}
            @if($errors->any())
            <div class="tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-6">
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2">
                    <i class="fas fa-exclamation-circle tw-text-red-500"></i>
                    <span class="tw-font-semibold tw-text-red-700 tw-text-sm">Veuillez corriger les erreurs suivantes :</span>
                </div>
                <ul class="tw-list-disc tw-list-inside tw-text-sm tw-text-red-600 tw-space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="tw-max-w-2xl">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                    {{-- Card header --}}
                    <div class="tw-px-6 tw-py-5 tw-bg-[#1D4ED8]">
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-box-open tw-text-white"></i>
                            </div>
                            <div>
                                <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">Nouveau produit</h2>
                                <p class="tw-text-white/70 tw-text-xs tw-mt-0.5 tw-mb-0">Les champs <span class="tw-text-red-300">*</span> sont obligatoires</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('produits.store') }}" class="tw-p-6 tw-space-y-5">
                        @csrf

                        {{-- Désignation --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Désignation <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="text" name="designation" value="{{ old('designation') }}" required
                                placeholder="Nom du produit"
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('designation') tw-border-red-400 @enderror">
                            @error('designation')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Catégorie --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Catégorie <span class="tw-text-red-500">*</span>
                            </label>
                            <select name="categorie" id="categorie" required
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                <option value="PHARMACEUTIQUE" {{ old('categorie') == 'PHARMACEUTIQUE' ? 'selected' : '' }}>PHARMACEUTIQUE</option>
                                <option value="MATERIEL" {{ old('categorie') == 'MATERIEL' ? 'selected' : '' }}>MATÉRIEL</option>
                                <option value="ANESTHESISTE" {{ old('categorie') == 'ANESTHESISTE' ? 'selected' : '' }}>ANESTHÉSISTE</option>
                            </select>
                        </div>

                        <hr class="tw-border-slate-100">

                        {{-- Quantités --}}
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Quantité en stock <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="number" name="qte_stock" value="{{ old('qte_stock') }}" required min="0"
                                    placeholder="0"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('qte_stock') tw-border-red-400 @enderror">
                                @error('qte_stock')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Quantité d'alerte <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="number" name="qte_alerte" value="{{ old('qte_alerte') }}" required min="0"
                                    placeholder="0"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('qte_alerte') tw-border-red-400 @enderror">
                                <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Seuil minimum avant alerte</p>
                                @error('qte_alerte')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Prix unitaire --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Prix unitaire (FCFA) <span class="tw-text-red-500">*</span>
                            </label>
                            <div class="tw-relative">
                                <input type="number" name="prix_unitaire" value="{{ old('prix_unitaire') }}" required min="0" step="1"
                                    placeholder="0"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-pr-16 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('prix_unitaire') tw-border-red-400 @enderror">
                                <span class="tw-absolute tw-right-3 tw-top-1/2 -tw-translate-y-1/2 tw-text-xs tw-text-slate-400 tw-font-medium">FCFA</span>
                            </div>
                            @error('prix_unitaire')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Actions --}}
                        <div class="tw-flex tw-gap-3 tw-pt-2">
                            <button type="submit"
                                class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-py-2.5 tw-transition-colors tw-border-0">
                                <i class="fas fa-save tw-text-xs"></i> Enregistrer
                            </button>
                            <a href="{{ route('produits.index') }}"
                               class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-transition-colors tw-no-underline">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </main>
        @endcan
    </div>
</div>
@endsection