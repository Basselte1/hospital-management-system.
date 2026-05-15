@extends('layouts.admin')
@section('title', 'CMCU | ' . (isset($tarif) ? 'Modifier le tarif' : 'Nouveau tarif'))

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Breadcrumb --}}
            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="{{ route('tarifs_labo.index') }}" class="hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                    Tarifs de laboratoire
                </a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">
                    {{ isset($tarif) ? 'Modifier — ' . $tarif->nom_test : 'Nouveau tarif' }}
                </span>
            </nav>

            @if(session('error'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-circle-exclamation tw-text-red-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-red-600 tw-mb-0">{{ session('error') }}</p>
            </div>
            @endif

            <div class="tw-max-w-2xl">
                <form method="POST"
                      action="{{ isset($tarif) ? route('tarifs_labo.update', $tarif->id) : route('tarifs_labo.store') }}">
                    @csrf
                    @if(isset($tarif)) @method('PATCH') @endif

                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">

                        <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-tag tw-text-indigo-600 tw-text-sm"></i>
                            </div>
                            <div>
                                <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">
                                    {{ isset($tarif) ? 'Modifier le tarif' : 'Ajouter un tarif' }}
                                </h2>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">
                                    Réservé à l'administrateur
                                </p>
                            </div>
                        </div>

                        <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-5">

                            {{-- Section --}}
                            <div class="sm:tw-col-span-2">
                                <label for="section" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                    Section / Discipline <span class="tw-text-red-500">*</span>
                                </label>
                                <select id="section" name="section" required
                                        class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl
                                               tw-bg-white focus:tw-outline-none focus:tw-border-[#1D4ED8]
                                               @error('section') tw-border-red-400 @enderror">
                                    <option value="">— Choisir une section —</option>
                                    @foreach($sections as $key => $label)
                                    <option value="{{ $key }}"
                                            {{ old('section', $tarif->section ?? request('section')) === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('section')
                                <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Test name --}}
                            <div class="sm:tw-col-span-2">
                                <label for="nom_test" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                    Nom du test / examen <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="text" id="nom_test" name="nom_test"
                                       value="{{ old('nom_test', $tarif->nom_test ?? '') }}"
                                       required maxlength="255"
                                       placeholder="Ex : Glycémie, NFS, TSH…"
                                       class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl
                                              focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10
                                              @error('nom_test') tw-border-red-400 @enderror">
                                @error('nom_test')
                                <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="tw-mt-1 tw-text-[11px] tw-text-slate-400">
                                    <i class="fas fa-info-circle tw-mr-1"></i>
                                    Doit correspondre exactement au nom utilisé lors de la saisie des résultats.
                                </p>
                            </div>

                            {{-- Unit price --}}
                            <div>
                                <label for="prix_unitaire" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                    Prix unitaire (FCFA) <span class="tw-text-red-500">*</span>
                                </label>
                                <div class="tw-relative">
                                    <span class="tw-absolute tw-left-3 tw-top-1/2 -tw-translate-y-1/2 tw-text-slate-400 tw-text-sm tw-font-medium">
                                        FCFA
                                    </span>
                                    <input type="number" id="prix_unitaire" name="prix_unitaire"
                                           value="{{ old('prix_unitaire', $tarif->prix_unitaire ?? 0) }}"
                                           min="0" step="100" required
                                           class="tw-block tw-w-full tw-pl-14 tw-pr-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl
                                                  focus:tw-outline-none focus:tw-border-[#1D4ED8] tw-font-mono
                                                  @error('prix_unitaire') tw-border-red-400 @enderror">
                                </div>
                                @error('prix_unitaire')
                                <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Active toggle --}}
                            <div class="tw-flex tw-items-center tw-gap-3 tw-pt-6">
                                <label class="tw-flex tw-items-center tw-gap-2.5 tw-cursor-pointer tw-select-none">
                                    <input type="hidden"   name="actif" value="0">
                                    <input type="checkbox" name="actif" value="1"
                                           id="actif"
                                           {{ old('actif', $tarif->actif ?? true) ? 'checked' : '' }}
                                           class="tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-text-green-600 focus:tw-ring-green-500">
                                    <span class="tw-text-sm tw-font-medium tw-text-slate-700">Test actif / disponible</span>
                                </label>
                            </div>

                            {{-- Description --}}
                            <div class="sm:tw-col-span-2">
                                <label for="description" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                    Note / Description
                                    <span class="tw-font-normal tw-text-slate-400">(facultatif)</span>
                                </label>
                                <textarea id="description" name="description"
                                          rows="2" maxlength="500"
                                          placeholder="Indications cliniques, conditions pré-analytiques spécifiques…"
                                          class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl
                                                 focus:tw-outline-none focus:tw-border-[#1D4ED8] resize-none">{{ old('description', $tarif->description ?? '') }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="tw-flex tw-items-center tw-gap-3 tw-pb-8">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white
                                       tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors
                                       tw-border-0 tw-cursor-pointer tw-shadow-sm">
                            <i class="fas fa-save tw-text-xs"></i>
                            {{ isset($tarif) ? 'Mettre à jour' : 'Enregistrer le tarif' }}
                        </button>
                        <a href="{{ route('tarifs_labo.index') }}"
                           class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600
                                  tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                            <i class="fas fa-times tw-text-xs"></i>
                            Annuler
                        </a>
                    </div>

                </form>
            </div>

        </main>
    </div>
</div>
@stop