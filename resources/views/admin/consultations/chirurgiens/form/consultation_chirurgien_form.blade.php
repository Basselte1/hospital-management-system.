@if($consultation->id)
    <form method="POST" action="{{ route('consultation_chirurgien.update', $consultation->id) }}"
          class="tw-space-y-6">
        @csrf
        @method('PUT')
@else
    <form method="POST" action="{{ route('consultation_chirurgien.store') }}"
          class="tw-space-y-6">
        @csrf
@endif

    {{-- Hidden patient id ────────────────────────────────────────── --}}
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

    {{-- ── SECTION 1 : Consultation ─────────────────────────────── --}}
    <div>
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                <i class="fas fa-stethoscope tw-text-[#1D4ED8] tw-text-xs"></i>
            </div>
            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Consultation</h3>
        </div>

        <div class="tw-space-y-4">
            <div>
                <label for="medecin_r" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Médecin de référence <span class="tw-text-red-500">*</span>
                </label>
                <input type="text" id="medecin_r" name="medecin_r"
                       value="{{ old('medecin_r', $consultation->medecin_r ?? Auth::user()->name . ' ' . Auth::user()->prenom) }}"
                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all splitLines">
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Motif de consultation <span class="tw-text-red-500">*</span>
                </label>
                <textarea name="motif_c" rows="4" required
                          class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('motif_c', $consultation->motif_c ?? '') }}</textarea>
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Interrogatoire <span class="tw-text-red-500">*</span>
                </label>
                <textarea name="interrogatoire" rows="5" required
                          class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('interrogatoire', $consultation->interrogatoire ?? '') }}</textarea>
            </div>

            {{-- Antécédents + allergies ────────── --}}
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                <div>
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Antécédents médicaux</label>
                    <textarea name="antecedent_m" rows="3"
                              class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('antecedent_m', $consultation->antecedent_m ?? '') }}</textarea>
                </div>
                <div>
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Antécédents chirurgicaux</label>
                    <textarea name="antecedent_c" rows="3"
                              class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('antecedent_c', $consultation->antecedent_c ?? '') }}</textarea>
                </div>
                <div>
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Allergies</label>
                    <textarea name="allergie" rows="3"
                              placeholder="Laisser vide si aucune allergie"
                              class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('allergie', $consultation->allergie ?? '') }}</textarea>
                </div>
                <div>
                    <label for="groupe" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Groupe sanguin</label>
                    <select id="groupe" name="groupe"
                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                        <option value="">Groupe sanguin</option>
                        @foreach(['O-', 'O+', 'B-', 'B+', 'A-', 'A+', 'AB-', 'AB+'] as $group)
                            <option value="{{ $group }}" {{ old('groupe', $consultation->groupe ?? '') == $group ? 'selected' : '' }}>{{ $group }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SECTION 2 : Examens ───────────────────────────────────── --}}
    <div>
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                <i class="fas fa-microscope tw-text-[#14B8A6] tw-text-xs"></i>
            </div>
            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Examens</h3>
        </div>

        <div class="tw-space-y-4">
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Examens physiques <span class="tw-text-red-500">*</span>
                </label>
                <textarea name="examen_p" rows="4" required
                          class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('examen_p', $consultation->examen_p ?? '') }}</textarea>
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Examens complémentaires <span class="tw-text-red-500">*</span>
                </label>
                <textarea name="examen_c" rows="4" required
                          class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('examen_c', $consultation->examen_c ?? '') }}</textarea>
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Diagnostic médical <span class="tw-text-red-500">*</span>
                </label>
                <textarea name="diagnostic" rows="4" required
                          class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('diagnostic', $consultation->diagnostic ?? '') }}</textarea>
            </div>
            <div>
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                    Proposition thérapeutique <span class="tw-text-red-500">*</span>
                </label>
                <textarea name="proposition_therapeutique" rows="4" required
                          class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('proposition_therapeutique', $consultation->proposition_therapeutique ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- ── SECTION 3 : Proposition de suivi (checkboxes) ────────── --}}
    <div>
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                <i class="fas fa-clipboard-check tw-text-amber-600 tw-text-xs"></i>
            </div>
            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">
                Proposition de suivi <span class="tw-text-red-500">*</span>
            </h3>
        </div>

        @php
            $propositions = [];
            if (isset($consultation->proposition)) {
                if (is_string($consultation->proposition)) {
                    $propositions = array_map('trim', explode(',', $consultation->proposition));
                } elseif (is_array($consultation->proposition)) {
                    $propositions = $consultation->proposition;
                }
            }
            if (old('proposition')) {
                $propositions = old('proposition');
            }
        @endphp

        <div class="tw-bg-slate-50 tw-rounded-xl tw-border tw-border-slate-200 tw-p-4 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-3">
            @foreach([
                ['id' => 'decision1', 'value' => 'Hospitalisation'],
                ['id' => 'decision2', 'value' => 'Intervention chirurgicale'],
                ['id' => 'decision3', 'value' => 'Consultation'],
                ['id' => 'decision4', 'value' => 'Actes à réaliser'],
                ['id' => 'decision5', 'value' => "Consultation d'anesthésiste"],
            ] as $check)
            <label for="{{ $check['id'] }}"
                   class="tw-flex tw-items-center tw-gap-2.5 tw-cursor-pointer tw-rounded-lg tw-bg-white tw-border tw-border-slate-200 tw-px-3 tw-py-2.5 hover:tw-border-[#1D4ED8] tw-transition-colors">
                <input type="checkbox"
                       id="{{ $check['id'] }}"
                       name="proposition[]"
                       value="{{ $check['value'] }}"
                       onClick="ckChange(this)"
                       {{ in_array($check['value'], $propositions) ? 'checked' : '' }}
                       class="tw-accent-[#1D4ED8] tw-w-4 tw-h-4 tw-shrink-0">
                <span class="tw-text-sm tw-text-slate-700">{{ $check['value'] }}</span>
            </label>
            @endforeach
        </div>

        {{-- ── Conditional rows (shown/hidden by ckChange JS) ─────── --}}

        {{-- Intervention chirurgicale --}}
        <div id="type_intervention" style="display:none;" class="tw-mt-4">
            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Type d'intervention</label>
            <textarea name="type_intervention" rows="4"
                      class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-y splitLines">{{ old('type_intervention', $consultation->type_intervention ?? '') }}</textarea>
        </div>

        {{-- Date intervention (always visible) --}}
        <div id="type_intervention_date" class="tw-mt-4">
            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date intervention</label>
            @php
                $dateIntervention = old('date_intervention', $consultation->date_intervention ?? '');
                if ($dateIntervention && $dateIntervention instanceof \Carbon\Carbon) {
                    $dateIntervention = $dateIntervention->format('Y-m-d\TH:i');
                } elseif ($dateIntervention) {
                    try { $dateIntervention = \Carbon\Carbon::parse($dateIntervention)->format('Y-m-d\TH:i'); }
                    catch (\Exception $e) { $dateIntervention = ''; }
                }
            @endphp
            <input type="datetime-local" name="date_intervention" value="{{ $dateIntervention }}"
                   class="tw-w-full sm:tw-w-64 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1 tw-mb-0">Sélectionnez la date et l'heure de l'intervention</p>
        </div>

        {{-- Actes à réaliser --}}
        <div id="type_acte" style="display:none;" class="tw-mt-4">
            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-2">Type d'actes à réaliser</label>
            @php
                $actes = [];
                if (isset($consultation->acte)) {
                    if (is_string($consultation->acte)) {
                        $actes = array_map('trim', explode(',', $consultation->acte));
                    } elseif (is_array($consultation->acte)) {
                        $actes = $consultation->acte;
                    }
                }
                if (old('acte')) { $actes = old('acte'); }
            @endphp
            <div class="tw-bg-slate-50 tw-rounded-xl tw-border tw-border-slate-200 tw-p-4 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-3">
                @foreach([
                    ['id' => 'acte1', 'value' => "Echographie de l'arbre urinaire"],
                    ['id' => 'acte2', 'value' => 'Cystoscopie'],
                    ['id' => 'acte3', 'value' => 'Biopsie prostatique'],
                    ['id' => 'acte4', 'value' => 'Débitimétrie'],
                    ['id' => 'acte5', 'value' => 'Echographie prostatique sous rectale'],
                ] as $acte)
                <label for="{{ $acte['id'] }}"
                       class="tw-flex tw-items-center tw-gap-2.5 tw-cursor-pointer tw-rounded-lg tw-bg-white tw-border tw-border-slate-200 tw-px-3 tw-py-2.5 hover:tw-border-[#1D4ED8] tw-transition-colors">
                    <input type="checkbox"
                           id="{{ $acte['id'] }}"
                           name="acte[]"
                           value="{{ $acte['value'] }}"
                           {{ in_array($acte['value'], $actes) ? 'checked' : '' }}
                           class="tw-accent-[#1D4ED8] tw-w-4 tw-h-4 tw-shrink-0">
                    <span class="tw-text-sm tw-text-slate-700">{{ $acte['value'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Consultation d'anesthésiste --}}
        <div id="anesthesiste" style="display:none;" class="tw-mt-4">
            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date consultation anesthésiste</label>
            @php
                $dateConsultAnesthesiste = old('date_consultation_anesthesiste', $consultation->date_consultation_anesthesiste ?? '');
                if ($dateConsultAnesthesiste && $dateConsultAnesthesiste instanceof \Carbon\Carbon) {
                    $dateConsultAnesthesiste = $dateConsultAnesthesiste->format('Y-m-d\TH:i');
                } elseif ($dateConsultAnesthesiste) {
                    try { $dateConsultAnesthesiste = \Carbon\Carbon::parse($dateConsultAnesthesiste)->format('Y-m-d\TH:i'); }
                    catch (\Exception $e) { $dateConsultAnesthesiste = ''; }
                }
            @endphp
            <input type="datetime-local" name="date_consultation_anesthesiste" value="{{ $dateConsultAnesthesiste }}"
                   class="tw-w-full sm:tw-w-64 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1 tw-mb-0">Sélectionnez la date et l'heure de la consultation</p>
        </div>

        {{-- Consultation de suivi --}}
        <div id="consultation" style="display:none;" class="tw-mt-4">
            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date de prochaine consultation</label>
            @php
                $dateConsultation = old('date_consultation', $consultation->date_consultation ?? '');
                if ($dateConsultation && $dateConsultation instanceof \Carbon\Carbon) {
                    $dateConsultation = $dateConsultation->format('Y-m-d\TH:i');
                } elseif ($dateConsultation) {
                    try { $dateConsultation = \Carbon\Carbon::parse($dateConsultation)->format('Y-m-d\TH:i'); }
                    catch (\Exception $e) { $dateConsultation = ''; }
                }
            @endphp
            <input type="datetime-local" name="date_consultation" value="{{ $dateConsultation }}"
                   class="tw-w-full sm:tw-w-64 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
            <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1 tw-mb-0">Sélectionnez la date et l'heure de la prochaine consultation</p>
        </div>
    </div>

    {{-- ── Submit ────────────────────────────────────────────────── --}}
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