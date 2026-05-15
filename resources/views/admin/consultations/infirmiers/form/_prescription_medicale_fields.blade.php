@php
    $horaires_selected = isset($prescription_medicale)
        ? ($prescription_medicale->horaire ?? [])
        : (old('horaire') ?? []);

    $all_horaires = ['00H', '02H', '04H', '06H', '08H', '10H', '12H', '14H', '16H', '18H', '20H', '22H'];
@endphp

{{-- ── Médicament & Posologie ───────────────────────────────── --}}
<div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4 tw-mb-4">
    <div>
        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
            Médicament & Forme <span class="tw-text-red-500">*</span>
        </label>
        <input type="text"
               name="medicament"
               value="{{ old('medicament', (string) ($prescription_medicale->medicament ?? '')) }}"
               placeholder="Ex: Paracétamol 500mg comprimé"
               required
               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all
                      @error('medicament') tw-border-red-400 tw-bg-red-50 @enderror">
        @error('medicament')
            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ (string) $message }}</p>
        @enderror
    </div>
    <div>
        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
            Posologie <span class="tw-text-red-500">*</span>
        </label>
        <input type="text"
               name="posologie"
               value="{{ old('posologie', (string) ($prescription_medicale->posologie ?? '')) }}"
               placeholder="Ex: 1 comprimé toutes les 8h"
               required
               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all
                      @error('posologie') tw-border-red-400 tw-bg-red-50 @enderror">
        @error('posologie')
            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ (string) $message }}</p>
        @enderror
    </div>
</div>

{{-- ── Horaire & Voie ───────────────────────────────────────── --}}
<div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">

    {{-- Horaires --}}
    <div class="sm:tw-col-span-2">
        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
            Horaire d'administration <span class="tw-text-red-500">*</span>
        </label>
        <p class="tw-text-[11px] tw-text-slate-400 tw-mb-2">Sélectionnez au moins un horaire</p>
        <div class="tw-grid tw-grid-cols-4 sm:tw-grid-cols-6 tw-gap-2">
            @foreach($all_horaires as $horaire)
            <label for="horaire_{{ $horaire }}"
                   class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 tw-cursor-pointer tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-2 tw-text-xs tw-font-medium tw-text-slate-600 hover:tw-border-[#1D4ED8] hover:tw-bg-[#BFDBFE]/30 tw-transition-colors
                          has-[:checked]:tw-border-[#1D4ED8] has-[:checked]:tw-bg-[#BFDBFE]/40 has-[:checked]:tw-text-[#1D4ED8]">
                <input type="checkbox"
                       id="horaire_{{ $horaire }}"
                       name="horaire[]"
                       value="{{ $horaire }}"
                       {{ in_array($horaire, $horaires_selected) ? 'checked' : '' }}
                       class="tw-accent-[#1D4ED8] tw-w-3.5 tw-h-3.5">
                {{ $horaire }}
            </label>
            @endforeach
        </div>
        @error('horaire')
            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ (string) $message }}</p>
        @enderror
    </div>

    {{-- Voie --}}
    <div>
        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
            Voie d'administration <span class="tw-text-red-500">*</span>
        </label>
        <select name="voie" required
                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all
                       @error('voie') tw-border-red-400 tw-bg-red-50 @enderror">
            <option value="">-- Sélectionner --</option>
            @foreach([
                'PO'         => 'PO (Per Os - Orale)',
                'IV'         => 'IV (Intraveineuse)',
                'IM'         => 'IM (Intramusculaire)',
                'SC'         => 'SC (Sous-cutanée)',
                'Rectale'    => 'Rectale',
                'Cutanée'    => 'Cutanée',
                'Inhalation' => 'Inhalation',
                'Autre'      => 'Autre',
            ] as $val => $label)
            <option value="{{ $val }}"
                {{ old('voie', (string) ($prescription_medicale->voie ?? '')) == $val ? 'selected' : '' }}>
                {{ $label }}
            </option>
            @endforeach
        </select>
        @error('voie')
            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ (string) $message }}</p>
        @enderror
    </div>

</div>