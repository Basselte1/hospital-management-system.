@if($compteRenduBlocOperatoire->id)
    <form method="POST" action="{{ route('compte_rendu_bloc.update', $compteRenduBlocOperatoire->id) }}"
          class="tw-space-y-6">
        @csrf
        @method('PUT')
@else
    <form method="POST" action="{{ route('compte_rendu_bloc.store') }}"
          class="tw-space-y-6">
        @csrf
@endif

    {{-- ── SECTION 1 : Entrée / Sortie ──────────────────────────── --}}
    <div>
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                <i class="fas fa-door-open tw-text-[#1D4ED8] tw-text-xs"></i>
            </div>
            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Entrée / Sortie patient</h3>
        </div>

        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Date d'entrée <span class="tw-text-red-500">*</span>
                </label>
                <input type="date" name="date_e"
                       value="{{ old('date_e', $compteRenduBlocOperatoire->date_e ?? '') }}"
                       required
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Type d'entrée <span class="tw-text-red-500">*</span>
                </label>
                <select name="type_e" required
                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                    <option value="">Motif d'entrée</option>
                    @foreach(['Urgence', 'Hospitalisation', 'Ambulatoire'] as $type)
                        <option value="{{ $type }}" {{ old('type_e', $compteRenduBlocOperatoire->type_e ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Date de sortie <span class="tw-text-red-500">*</span>
                </label>
                <input type="date" name="date_s"
                       value="{{ old('date_s', $compteRenduBlocOperatoire->date_s ?? '') }}"
                       required
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Type de sortie <span class="tw-text-red-500">*</span>
                </label>
                <select name="type_s" required
                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                    <option value="">Motif de sortie</option>
                    @foreach(['Retour au domicile', 'Transfert', 'Convalescence', 'Décédé'] as $type)
                        <option value="{{ $type }}" {{ old('type_s', $compteRenduBlocOperatoire->type_s ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ── SECTION 2 : Équipe médicale ──────────────────────────── --}}
    <div>
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                <i class="fas fa-user-md tw-text-[#14B8A6] tw-text-xs"></i>
            </div>
            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Équipe médicale</h3>
        </div>

        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Chirurgien <span class="tw-text-red-500">*</span>
                </label>
                <select name="chirurgien" id="chirurgien" required
                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                    <option value="">Nom du chirurgien</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->name }} {{ $user->prenom }}"
                            {{ old('chirurgien', $compteRenduBlocOperatoire->chirurgien ?? '') == ($user->name . ' ' . $user->prenom) ? 'selected' : '' }}>
                            {{ $user->name }} {{ $user->prenom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Aide opératoire <span class="tw-text-red-500">*</span>
                </label>
                <select name="aide_op" id="aide_op" required
                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                    <option value="">Nom de l'aide opératoire</option>
                    <option value="Aucun" {{ old('aide_op', $compteRenduBlocOperatoire->aide_op ?? '') == 'Aucun' ? 'selected' : '' }}>Aucun</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->name }} {{ $user->prenom }}"
                            {{ old('aide_op', $compteRenduBlocOperatoire->aide_op ?? '') == ($user->name . ' ' . $user->prenom) ? 'selected' : '' }}>
                            {{ $user->name }} {{ $user->prenom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Anesthésiste <span class="tw-text-red-500">*</span>
                </label>
                <select name="anesthesiste" id="anesthesiste" required
                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                    <option value="">Nom de l'anesthésiste</option>
                    @foreach ($anesthesistes as $anesthesiste)
                        <option value="{{ $anesthesiste->name }} {{ $anesthesiste->prenom ?? '' }}"
                            {{ old('anesthesiste', $compteRenduBlocOperatoire->anesthesiste ?? '') == ($anesthesiste->name . ' ' . ($anesthesiste->prenom ?? '')) ? 'selected' : '' }}>
                            {{ $anesthesiste->name }} {{ $anesthesiste->prenom ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Infirmier anesthésiste <span class="tw-text-red-500">*</span>
                </label>
                <input type="text" name="infirmier_anesthesiste"
                       value="{{ old('infirmier_anesthesiste', $compteRenduBlocOperatoire->infirmier_anesthesiste ?? '') }}"
                       placeholder="Nom de l'infirmier anesthésiste"
                       required
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            </div>
        </div>
    </div>

    {{-- ── SECTION 3 : Détails opération ────────────────────────── --}}
    <div>
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                <i class="fas fa-scalpel tw-text-amber-600 tw-text-xs"></i>
            </div>
            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Détails de l'opération</h3>
        </div>

        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
            <div class="sm:tw-col-span-2">
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Titre de l'intervention <span class="tw-text-red-500">*</span>
                </label>
                <input type="text" name="titre_intervention"
                       value="{{ old('titre_intervention', $compteRenduBlocOperatoire->titre_intervention ?? '') }}"
                       placeholder="Titre de l'intervention"
                       required
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            </div>
            <div class="sm:tw-col-span-2">
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Type d'intervention <span class="tw-text-red-500">*</span>
                </label>
                <input type="text" name="type_intervention"
                       value="{{ old('type_intervention', $compteRenduBlocOperatoire->type_intervention ?? '') }}"
                       placeholder="Type d'intervention"
                       required
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Date de l'intervention <span class="tw-text-red-500">*</span>
                </label>
                <input type="date" name="date_intervention"
                       value="{{ old('date_intervention', $compteRenduBlocOperatoire->date_intervention ?? '') }}"
                       required
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Durée de l'intervention <span class="tw-text-red-500">*</span>
                </label>
                <input type="time" name="dure_intervention"
                       value="{{ old('dure_intervention', $compteRenduBlocOperatoire->dure_intervention ?? '') }}"
                       required
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            </div>
        </div>
    </div>

    {{-- ── SECTION 4 : Contenu médical ──────────────────────────── --}}
    <div>
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                <i class="fas fa-file-medical-alt tw-text-indigo-600 tw-text-xs"></i>
            </div>
            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Contenu médical</h3>
        </div>

        <div class="tw-space-y-4">
            @foreach([
                ['name' => 'indication_operatoire',  'label' => 'Indications opératoires',         'required' => true,  'rows' => 4],
                ['name' => 'compte_rendu_o',          'label' => 'Compte-rendu opératoire',          'required' => true,  'rows' => 4],
                ['name' => 'resultat_histo',          'label' => 'Résultats histo-pathologiques',    'required' => false, 'rows' => 4],
                ['name' => 'suite_operatoire',        'label' => 'Suites opératoires',               'required' => true,  'rows' => 4],
                ['name' => 'traitement_propose',      'label' => 'Traitement proposé',               'required' => false, 'rows' => 4],
                ['name' => 'soins',                   'label' => 'Soins et examens à réaliser',      'required' => false, 'rows' => 4],
                ['name' => 'proposition_suivi',       'label' => 'Proposition de suivi',             'required' => false, 'rows' => 3],
                ['name' => 'conclusion',              'label' => 'Conclusions',                      'required' => true,  'rows' => 4],
            ] as $field)
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    {{ $field['label'] }}
                    @if($field['required'])<span class="tw-text-red-500">*</span>@endif
                </label>
                <textarea name="{{ $field['name'] }}"
                          rows="{{ $field['rows'] }}"
                          {{ $field['required'] ? 'required' : '' }}
                          class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old($field['name'], $compteRenduBlocOperatoire->{$field['name']} ?? '') }}</textarea>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Hidden + Submit ──────────────────────────────────────────── --}}
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

    <div class="tw-flex tw-items-center tw-justify-between tw-pt-5 tw-border-t tw-border-slate-100">
        <a href="{{ route('patients.show', $patient->id) }}"
           class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-5 tw-py-3 tw-no-underline tw-transition-colors">
            <i class="fas fa-times tw-text-xs"></i> Annuler
        </a>
        <button type="submit"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-6 tw-py-3 tw-border-0 tw-transition-colors tw-shadow-sm">
            <i class="fas fa-save tw-text-xs"></i> Enregistrer
        </button>
    </div>

</form>