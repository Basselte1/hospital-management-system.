@extends('layouts.admin')
@section('title', 'CMCU | Modifier une Chambre')

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
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Modifier la Chambre</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Chambre N° <span class="tw-font-mono tw-font-semibold tw-text-slate-700">{{ $chambre->numero }}</span></p>
                    </div>
                    <a href="{{ route('chambres.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                        <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                    </a>
                </div>

                {{-- Form card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-bg-500 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-bed tw-text-white tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">Modifier les Informations</h2>
                    </div>

                    <form method="POST" action="{{ route('chambres.update', $chambre->id) }}" class="tw-p-6 tw-space-y-5">
                        @method('PATCH')
                        @csrf

                        {{-- Numéro --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Numéro <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="text" name="numero" value="{{ $chambre->numero }}" required
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('numero') tw-border-red-400 @enderror">
                            @error('numero')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Catégorie --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Catégorie <span class="tw-text-red-500">*</span>
                            </label>
                            <select name="categorie" required
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                <option value="classique" {{ $chambre->categorie == 'classique' ? 'selected' : '' }}>Classique</option>
                                <option value="mvp"       {{ $chambre->categorie == 'mvp'       ? 'selected' : '' }}>MVP</option>
                                <option value="vip"       {{ $chambre->categorie == 'vip'       ? 'selected' : '' }}>VIP</option>
                            </select>
                        </div>

                        {{-- Prix --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Prix (FCFA) <span class="tw-text-red-500">*</span>
                            </label>
                            <select name="prix" required
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                <option value="2500"  {{ $chambre->prix == '2500'  ? 'selected' : '' }}>2 500 FCFA</option>
                                <option value="5000"  {{ $chambre->prix == '5000'  ? 'selected' : '' }}>5 000 FCFA</option>
                                <option value="10000" {{ $chambre->prix == '10000' ? 'selected' : '' }}>10 000 FCFA</option>
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-pt-2 tw-border-t tw-border-slate-100">
                            <a href="{{ route('chambres.index') }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline tw-text-sm">
                                Annuler
                            </a>
                            <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-500 hover:tw-bg-amber-600 tw-text-white tw-font-medium tw-px-6 tw-py-2.5 tw-border-0 tw-text-sm tw-transition-colors">
                                <i class="fas fa-save tw-text-xs"></i> Modifier
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection