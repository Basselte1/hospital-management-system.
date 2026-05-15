@if($parametre->id)
    <form method="post" action="{{ route('fiche_parametres.update', $parametre->id) }}"
          class="tw-space-y-4">
        @method('put')
        @csrf
@else
    <form method="post" action="{{ route('fiche_parametres.store') }}"
          class="tw-space-y-4">
        @csrf
@endif

    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

    {{-- ── Date de naissance ───────────────────────────────── --}}
    <div>
        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
            Date de naissance <span class="tw-text-red-500">*</span>
        </label>
        @if(isset($dossier) && $dossier && $dossier->date_naissance)
            <div class="tw-flex tw-items-center tw-gap-2">
                <input type="date"
                       name="date_naissance"
                       value="{{ old('date_naissance', $dossier->date_naissance) }}"
                       required
                       class="tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-500 focus:tw-outline-none tw-cursor-default">
                <span class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-teal-100 tw-text-teal-600 tw-flex tw-items-center tw-justify-center tw-shrink-0"
                      title="Valeur récupérée du dossier patient">
                    <i class="fas fa-lock tw-text-xs"></i>
                </span>
            </div>
            <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1"><i class="fas fa-info-circle tw-mr-1"></i>Récupéré du dossier patient</p>
        @else
            <input type="date"
                   name="date_naissance"
                   value="{{ old('date_naissance', $parametre->date_naissance ?? '') }}"
                   required
                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            <p class="tw-text-[11px] tw-text-amber-500 tw-mt-1"><i class="fas fa-exclamation-triangle tw-mr-1"></i>Veuillez compléter le dossier patient pour éviter de saisir à nouveau</p>
        @endif
    </div>

    {{-- ── TA ────────────────────────────────────────────────── --}}
    <div>
        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
            T.A (Tension artérielle) <span class="tw-text-red-500">*</span>
        </label>
        <div class="tw-grid tw-grid-cols-2 tw-gap-3">
            <div>
                <label for="bras_gauche" class="tw-block tw-text-[11px] tw-text-slate-400 tw-mb-1">Bras gauche</label>
                <input type="text" id="bras_gauche" name="bras_gauche"
                       value="{{ old('bras_gauche', $parametre->bras_gauche ?? '') }}"
                       placeholder="mmHg" required
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            </div>
            <div>
                <label for="bras_droit" class="tw-block tw-text-[11px] tw-text-slate-400 tw-mb-1">Bras droit</label>
                <input type="text" id="bras_droit" name="bras_droit"
                       value="{{ old('bras_droit', $parametre->bras_droit ?? '') }}"
                       placeholder="mmHg" required
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            </div>
        </div>
    </div>

    {{-- ── Vitals grid ──────────────────────────────────────── --}}
    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
        @foreach([
            ['name' => 'temperature', 'label' => 'Température',    'placeholder' => '°C',       'type' => 'number', 'required' => true],
            ['name' => 'fr',          'label' => 'F.R',             'placeholder' => 'Mvts/min', 'type' => 'text',   'required' => true],
            ['name' => 'fc',          'label' => 'F.C',             'placeholder' => 'Pls/min',  'type' => 'text',   'required' => true],
            ['name' => 'glycemie',    'label' => 'Glycémie',        'placeholder' => 'g/l',      'type' => 'text',   'required' => false],
            ['name' => 'spo2',        'label' => 'SPO2',            'placeholder' => '%',        'type' => 'text',   'required' => true],
            ['name' => 'poids',       'label' => 'Poids',           'placeholder' => 'kg',       'type' => 'number', 'required' => true],
            ['name' => 'taille',      'label' => 'Taille (en mètre)','placeholder' => '0.00',    'type' => 'number', 'required' => true],
        ] as $field)
        <div>
            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                {{ $field['label'] }}
                @if($field['required'])<span class="tw-text-red-500">*</span>@endif
            </label>
            <input type="{{ $field['type'] }}"
                   name="{{ $field['name'] }}"
                   value="{{ old($field['name'], $parametre->{$field['name']} ?? '') }}"
                   placeholder="{{ $field['placeholder'] }}"
                   {{ $field['required'] ? 'required' : '' }}
                   {{ in_array($field['type'], ['number']) ? 'step="any"' : '' }}
                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
        </div>
        @endforeach
    </div>

    {{-- ── Submit ───────────────────────────────────────────── --}}
    <div class="tw-pt-3">
        <button type="submit"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-6 tw-py-3 tw-border-0 tw-transition-colors tw-shadow-sm">
            <i class="fas fa-save tw-text-xs"></i> Ajouter au dossier
        </button>
    </div>

</form>