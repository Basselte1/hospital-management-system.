{{--
    Partial: feuille_examen_imagerie.blade.php
    Examination checklist grid — imagery exams.
    Green accent (teal-500) to differentiate from biology (blue).
--}}

@php
$sectionHeaderCls = 'tw-text-xs tw-font-bold tw-text-white tw-uppercase tw-tracking-wider tw-text-center tw-py-2.5 tw-px-4 tw-rounded-t-xl tw-bg-[#14B8A6] tw--mx-px tw--mt-px';
$checkItemCls     = 'tw-flex tw-items-center tw-gap-2.5 tw-py-1.5 tw-text-sm tw-text-slate-700 tw-cursor-pointer hover:tw-text-[#14B8A6] tw-transition-colors tw-group';
$checkboxCls      = 'tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-accent-teal-500 tw-cursor-pointer tw-shrink-0';
$inputCls         = 'tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-800 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#14B8A6] focus:tw-ring-2 focus:tw-ring-teal-500/10 tw-transition-colors tw-mt-1';
$labelCls         = 'tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mt-4 tw-mb-0.5';
@endphp

<div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-4 tw-p-4">

    {{-- RADIOGRAPHIE --}}
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="{{ $sectionHeaderCls }}">Radiographie</div>
        <div class="tw-p-4 tw-flex-1">
            @foreach(['Thorax', 'Abdomen sans préparation'] as $item)
            <label class="{{ $checkItemCls }}">
                <input type="checkbox" name="radiographie[]" value="{{ $item }}" class="{{ $checkboxCls }}">
                {{ $item }}
            </label>
            @endforeach
            <span class="{{ $labelCls }}">Autres :</span>
            <input type="text" name="radiographie[]" id="radiographie_autres" class="{{ $inputCls }}" placeholder="Préciser…">
        </div>
    </div>

    {{-- ECHOGRAPHIE --}}
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="{{ $sectionHeaderCls }}">Échographie</div>
        <div class="tw-p-4 tw-flex-1">
            @foreach(['Reins et vessie', 'Scrotum'] as $item)
            <label class="{{ $checkItemCls }}">
                <input type="checkbox" name="echographie[]" value="{{ $item }}" class="{{ $checkboxCls }}">
                {{ $item }}
            </label>
            @endforeach
            <span class="{{ $labelCls }}">Autres :</span>
            <input type="text" name="echographie[]" id="echographie_autres" class="{{ $inputCls }}" placeholder="Préciser…">
        </div>
    </div>

    {{-- SCANNER --}}
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="{{ $sectionHeaderCls }}">Scanner</div>
        <div class="tw-p-4 tw-flex-1">
            @foreach([
                'Abdomen-pelvis',
                'Cérébral',
                'Rachis Cervical',
                'Rachis dorso-lombaire',
            ] as $item)
            <label class="{{ $checkItemCls }}">
                <input type="checkbox" name="scanner[]" value="{{ $item }}" class="{{ $checkboxCls }}">
                {{ $item }}
            </label>
            @endforeach
            <span class="{{ $labelCls }}">Autres :</span>
            <input type="text" name="scanner[]" id="scanner_autres" class="{{ $inputCls }}" placeholder="Préciser…">
        </div>
    </div>

    {{-- IRM --}}
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="{{ $sectionHeaderCls }}">IRM</div>
        <div class="tw-p-4 tw-flex-1">
            @foreach(['Abdomen-pelvis', 'Prostate', 'Moelle osseuse'] as $item)
            <label class="{{ $checkItemCls }}">
                <input type="checkbox" name="irm[]" value="{{ $item }}" class="{{ $checkboxCls }}">
                {{ $item }}
            </label>
            @endforeach
            <span class="{{ $labelCls }}">Autres :</span>
            <input type="text" name="irm[]" id="irm_autres" class="{{ $inputCls }}" placeholder="Préciser…">
        </div>
    </div>

    {{-- SCINTIGRAPHIE --}}
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="{{ $sectionHeaderCls }}">Scintigraphie</div>
        <div class="tw-p-4 tw-flex-1">
            @foreach([
                'Rénale Mag3 lasix',
                'Rénale DTPA',
                'Rénale DMSA',
                'Osseuse',
            ] as $item)
            <label class="{{ $checkItemCls }}">
                <input type="checkbox" name="scintigraphie[]" value="{{ $item }}" class="{{ $checkboxCls }}">
                {{ $item }}
            </label>
            @endforeach
            <span class="{{ $labelCls }}">Autres :</span>
            <input type="text" name="scintigraphie[]" id="scintigraphie_autres" class="{{ $inputCls }}" placeholder="Préciser…">
        </div>
    </div>

    {{-- AUTRES --}}
    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-flex tw-flex-col tw-shadow-sm">
        <div class="{{ $sectionHeaderCls }}">Autres examens</div>
        <div class="tw-p-4 tw-flex-1">
            <span class="{{ $labelCls }}">Précisez :</span>
            <textarea name="autre" id="autre" rows="6"
                      placeholder="Spécifiez d'autres examens d'imagerie..."
                      class="{{ $inputCls }} tw-resize-none tw-mt-2"></textarea>
        </div>
    </div>

</div>