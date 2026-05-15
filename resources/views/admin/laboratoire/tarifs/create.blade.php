{{-- ============================================================
     TARIF CREATE  —  admin.laboratoire.tarifs.create
     Save as: resources/views/admin/laboratoire/tarifs/create.blade.php
     ============================================================ --}}
@extends('layouts.admin')
@section('title', 'CMCU | Nouveau tarif')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="{{ route('tarifs_labo.index') }}" class="hover:tw-text-[#1D4ED8] tw-no-underline">Tarifs laboratoire</a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">Nouveau tarif</span>
            </nav>

            @if(session('error'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-circle-exclamation tw-text-red-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-red-600 tw-mb-0">{{ session('error') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('tarifs_labo.store') }}" class="tw-max-w-xl">
                @csrf

                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-6 tw-space-y-5">

                    <div class="tw-flex tw-items-center tw-justify-between">
                        <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Ajouter un test / tarif</h2>
                        <a href="{{ route('sections_labo.create') }}"
                           class="tw-text-xs tw-text-[#1D4ED8] hover:tw-underline tw-no-underline">
                            <i class="fas fa-plus tw-mr-1"></i>Créer une section
                        </a>
                    </div>

                    {{-- Section (from DB) --}}
                    <div>
                        <label for="section_id" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Section <span class="tw-text-red-500">*</span>
                        </label>
                        <select id="section_id" name="section_id"
                                class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-white focus:tw-outline-none focus:tw-border-[#1D4ED8]"
                                required>
                            <option value="">— Choisir une section —</option>
                            @foreach($sections as $section)
                            <option value="{{ $section->id }}"
                                    {{ old('section_id', request('section_id')) == $section->id ? 'selected' : '' }}>
                                {{ $section->label }}
                            </option>
                            @endforeach
                        </select>
                        @error('section_id')
                        <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Test name --}}
                    <div>
                        <label for="nom_test" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Nom du test <span class="tw-text-red-500">*</span>
                        </label>
                        <input type="text" id="nom_test" name="nom_test"
                               value="{{ old('nom_test') }}"
                               placeholder="ex : Glycémie, NFS, TSH…"
                               class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]"
                               required>
                        @error('nom_test')
                        <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label for="prix_unitaire" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Prix unitaire (FCFA) <span class="tw-text-red-500">*</span>
                        </label>
                        <input type="number" id="prix_unitaire" name="prix_unitaire"
                               value="{{ old('prix_unitaire', 0) }}" min="0" step="100"
                               class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]"
                               required>
                        @error('prix_unitaire')
                        <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Description / note clinique
                            <span class="tw-font-normal tw-text-slate-400">(facultatif)</span>
                        </label>
                        <input type="text" id="description" name="description"
                               value="{{ old('description') }}"
                               placeholder="Indication clinique, note…"
                               class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                    </div>

                    {{-- Active --}}
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <input type="checkbox" id="actif" name="actif" value="1"
                               class="tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-accent-[#1D4ED8]"
                               {{ old('actif', true) ? 'checked' : '' }}>
                        <label for="actif" class="tw-text-sm tw-text-slate-700 tw-cursor-pointer">Test actif (visible sur les formulaires)</label>
                    </div>

                    <div class="tw-flex tw-items-center tw-gap-3 tw-pt-2">
                        <button type="submit"
                                class="tw-px-5 tw-py-2.5 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-blue-700 tw-text-white tw-text-sm tw-font-semibold tw-transition-colors">
                            <i class="fas fa-plus tw-mr-1.5"></i>Ajouter le tarif
                        </button>
                        <a href="{{ route('tarifs_labo.index') }}"
                           class="tw-px-5 tw-py-2.5 tw-rounded-xl tw-border tw-border-slate-200 tw-text-slate-600 hover:tw-bg-slate-50 tw-text-sm tw-font-medium tw-no-underline tw-transition-colors">
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>
@stop