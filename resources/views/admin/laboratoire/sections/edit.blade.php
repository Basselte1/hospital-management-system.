{{-- ============================================================
     SECTION EDIT  —  admin.laboratoire.sections.edit
     Save as: resources/views/admin/laboratoire/sections/edit.blade.php
     ============================================================ --}}
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
                <span class="tw-text-slate-700 tw-font-medium">Modifier — {{ $section->label }}</span>
            </nav>

            @if(session('success'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-check-circle tw-text-green-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-green-700 tw-mb-0">{{ session('success') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('sections_labo.update', $section) }}" class="tw-max-w-xl">
                @csrf @method('PATCH')

                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-6 tw-space-y-5">

                    <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Modifier la section</h2>

                    {{-- Slug (read-only — cannot be changed after creation) --}}
                    <div class="tw-p-3 tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-xl">
                        <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-0.5">Slug (non modifiable)</p>
                        <code class="tw-text-sm tw-font-mono tw-text-slate-700">{{ $section->slug }}</code>
                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1 tw-mb-0">
                            Le slug est utilisé comme clé dans les bons d'examens et prescriptions.
                            Le modifier briserait les données existantes.
                        </p>
                    </div>

                    {{-- Label --}}
                    <div>
                        <label for="label" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Nom de la section <span class="tw-text-red-500">*</span>
                        </label>
                        <input type="text" id="label" name="label"
                               value="{{ old('label', $section->label) }}"
                               class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10"
                               required>
                        @error('label')
                        <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon --}}
                    <div>
                        <label for="icon" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Icône Font Awesome <span class="tw-font-normal tw-text-slate-400">(ex : fa-flask)</span>
                        </label>
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <span id="iconPreview" class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                <i class="fas {{ $section->icon }} tw-text-slate-500"></i>
                            </span>
                            <input type="text" id="icon" name="icon"
                                   value="{{ old('icon', $section->icon) }}"
                                   class="tw-flex-1 tw-px-4 tw-py-2.5 tw-text-sm tw-font-mono tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]"
                                   oninput="updateIconPreview(this.value)">
                        </div>
                    </div>

                    {{-- Color classes --}}
                    <div>
                        <label for="color_classes" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Classes de couleur Tailwind
                        </label>
                        <input type="text" id="color_classes" name="color_classes"
                               value="{{ old('color_classes', $section->color_classes) }}"
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
                               value="{{ old('ordre', $section->ordre) }}" min="0"
                               class="tw-block tw-w-32 tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]"
                               required>
                    </div>

                    {{-- Active --}}
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <input type="checkbox" id="actif" name="actif" value="1"
                               class="tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-accent-[#1D4ED8]"
                               {{ old('actif', $section->actif) ? 'checked' : '' }}>
                        <label for="actif" class="tw-text-sm tw-font-medium tw-text-slate-700 tw-cursor-pointer tw-select-none">
                            Section active
                        </label>
                    </div>

                    {{-- Buttons --}}
                    <div class="tw-flex tw-items-center tw-gap-3 tw-pt-2">
                        <button type="submit"
                                class="tw-px-5 tw-py-2.5 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-blue-700 tw-text-white tw-text-sm tw-font-semibold tw-transition-colors">
                            <i class="fas fa-save tw-mr-1.5"></i>Enregistrer les modifications
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