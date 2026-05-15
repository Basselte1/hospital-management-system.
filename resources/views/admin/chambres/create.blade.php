@extends('layouts.admin')
@section('title', 'CMCU | Ajouter une Chambre')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8 tw-flex tw-items-start tw-justify-center">
            <div class="tw-w-full tw-max-w-lg">

                {{-- Page heading --}}
                <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                    <div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Ajouter une Chambre</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Enregistrer une nouvelle chambre</p>
                    </div>
                    <a href="{{ route('chambres.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                        <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                    </a>
                </div>

                {{-- Form card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-bed tw-text-white tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">Informations de la Chambre</h2>
                    </div>

                    <form method="POST" action="{{ route('chambres.store') }}" class="tw-p-6 tw-space-y-5">
                        @csrf

                        {{-- Numéro --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Numéro <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="text" name="numero"
                                   value="{{ old('numero') }}"
                                   placeholder="Ex: 101, A-02..."
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('numero') tw-border-red-400 @enderror">
                            @error('numero')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Catégorie --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Catégorie <span class="tw-text-red-500">*</span>
                            </label>
                            <select name="categorie"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('categorie') tw-border-red-400 @enderror">
                                <option value="">— Choisir une catégorie —</option>
                                <option value="Classique" {{ old('categorie') === 'Classique' ? 'selected' : '' }}>Classique</option>
                                <option value="vip"       {{ old('categorie') === 'vip'       ? 'selected' : '' }}>VIP</option>
                                <option value="bloc"      {{ old('categorie') === 'bloc'      ? 'selected' : '' }}>Bloc</option>
                            </select>
                            @error('categorie')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Prix --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Coût de la Chambre (FCFA) <span class="tw-text-red-500">*</span>
                            </label>
                            <select name="prix"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('prix') tw-border-red-400 @enderror">
                                <option value="">— Choisir un tarif —</option>
                                <option value="2500"  {{ old('prix') === '2500'  ? 'selected' : '' }}>2 500 FCFA</option>
                                <option value="5000"  {{ old('prix') === '5000'  ? 'selected' : '' }}>5 000 FCFA</option>
                                <option value="10000" {{ old('prix') === '10000' ? 'selected' : '' }}>10 000 FCFA</option>
                                <option value="0"     {{ old('prix') === '0'     ? 'selected' : '' }}>Gratuit (0 FCFA)</option>
                            </select>
                            @error('prix')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Actions --}}
                        <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-pt-2 tw-border-t tw-border-slate-100">
                            <a href="{{ route('chambres.index') }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline tw-text-sm">
                                Annuler
                            </a>
                            <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-px-6 tw-py-2.5 tw-transition-colors tw-border-0 tw-text-sm">
                                <i class="fas fa-save tw-text-xs"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection