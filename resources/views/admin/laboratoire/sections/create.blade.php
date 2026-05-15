{{-- ============================================================
     SECTION CREATE  —  admin.laboratoire.sections.create
     Save as: resources/views/admin/laboratoire/sections/create.blade.php
     ============================================================ --}}
@extends('layouts.admin')
@section('title', 'CMCU | Nouvelle section')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="{{ route('sections_labo.index') }}" class="hover:tw-text-[#1D4ED8] tw-no-underline">Sections</a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">Nouvelle section</span>
            </nav>

            <form method="POST" action="{{ route('sections_labo.store') }}" class="tw-max-w-xl">
                @csrf

                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-6 tw-space-y-5">

                    <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Créer une nouvelle section</h2>

                    {{-- Label --}}
                    <div>
                        <label for="label" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Nom de la section <span class="tw-text-red-500">*</span>
                        </label>
                        <input type="text" id="label" name="label"
                               value="{{ old('label') }}"
                               placeholder="ex : Hématologie, Biochimie…"
                               class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10"
                               required>
                        @error('label')
                        <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1">
                            Le <strong>slug</strong> (identifiant technique) sera généré automatiquement et ne pourra pas être modifié après création.
                        </p>
                    </div>

                    {{-- Slug (optional override) --}}
                    <div>
                        <label for="slug" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Slug <span class="tw-font-normal tw-text-slate-400">(facultatif — laissez vide pour générer automatiquement)</span>
                        </label>
                        <input type="text" id="slug" name="slug"
                               value="{{ old('slug') }}"
                               placeholder="ex : hematologie (minuscules, underscores uniquement)"
                               class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-font-mono tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        @error('slug')
                        <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon --}}
                    <div>
                        <label for="icon" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Icône Font Awesome
                            <span class="tw-font-normal tw-text-slate-400">(classe FA, ex : fa-flask)</span>
                        </label>
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <span id="iconPreview" class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-flask tw-text-slate-400"></i>
                            </span>
                            <input type="text" id="icon" name="icon"
                                   value="{{ old('icon', 'fa-flask') }}"
                                   placeholder="fa-flask"
                                   class="tw-flex-1 tw-px-4 tw-py-2.5 tw-text-sm tw-font-mono tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]"
                                   oninput="updateIconPreview(this.value)">
                        </div>
                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1">
                            Parcourez les icônes sur
                            <a href="https://fontawesome.com/icons" target="_blank" class="tw-text-[#1D4ED8] hover:tw-underline tw-no-underline">fontawesome.com</a>.
                        </p>
                    </div>

                    {{-- Color classes --}}
                    <div>
                        <label for="color_classes" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Classes de couleur Tailwind
                            <span class="tw-font-normal tw-text-slate-400">(pour la carte de section)</span>
                        </label>
                        <input type="text" id="color_classes" name="color_classes"
                               value="{{ old('color_classes', 'tw-bg-slate-50 tw-border-slate-300') }}"
                               class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-font-mono tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1">
                            Exemples : <code>tw-bg-red-50 tw-border-red-300</code> · <code>tw-bg-teal-50 tw-border-teal-300</code>
                        </p>
                    </div>

                    {{-- Ordre --}}
                    <div>
                        <label for="ordre" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Ordre d'affichage <span class="tw-text-red-500">*</span>
                        </label>
                        <input type="number" id="ordre" name="ordre"
                               value="{{ old('ordre', $nextOrdre) }}" min="0"
                               class="tw-block tw-w-32 tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]"
                               required>
                    </div>

                    {{-- Active --}}
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <input type="checkbox" id="actif" name="actif" value="1"
                               class="tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-accent-[#1D4ED8]"
                               {{ old('actif', true) ? 'checked' : '' }}>
                        <label for="actif" class="tw-text-sm tw-font-medium tw-text-slate-700 tw-cursor-pointer tw-select-none">
                            Section active (visible sur les formulaires)
                        </label>
                    </div>

                    {{-- Buttons --}}
                    <div class="tw-flex tw-items-center tw-gap-3 tw-pt-2">
                        <button type="submit"
                                class="tw-px-5 tw-py-2.5 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-blue-700 tw-text-white tw-text-sm tw-font-semibold tw-transition-colors">
                            <i class="fas fa-plus tw-mr-1.5"></i>Créer la section
                        </button>
                        <a href="{{ route('sections_labo.index') }}"
                           class="tw-px-5 tw-py-2.5 tw-rounded-xl tw-border tw-border-slate-200 tw-text-slate-600 hover:tw-bg-slate-50 tw-text-sm tw-font-medium tw-no-underline tw-transition-colors">
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
function updateIconPreview(value) {
    var preview = document.getElementById('iconPreview');
    if (preview) {
        preview.innerHTML = '<i class="fas ' + value + ' tw-text-slate-500"></i>';
    }
}
</script>
@stop


{{-- ============================================================
     SECTION EDIT  —  admin.laboratoire.sections.edit
     Save as: resources/views/admin/laboratoire/sections/edit.blade.php
     ============================================================ --}}
{{-- NOTE: This block is a SEPARATE file. In production, split into two files.
     The content below the separator is the edit view. --}}
{{--
@extends('layouts.admin')
@section('title', 'CMCU | Modifier la section')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="{{ route('sections_labo.index') }}" class="hover:tw-text-[#1D4ED8] tw-no-underline">Sections</a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">{{ $section->label }}</span>
            </nav>

            <form method="POST" action="{{ route('sections_labo.update', $section) }}" class="tw-max-w-xl">
                @csrf @method('PATCH')
                ... (same fields as create, except slug is read-only)
            </form>
        </main>
    </div>
</div>
@stop
--}}