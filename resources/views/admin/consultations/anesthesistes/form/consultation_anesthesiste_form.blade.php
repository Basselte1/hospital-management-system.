{{--
    Partial: consultation_anesthesiste_form.blade.php
    Embeds inside a <table> row context — keeps <tr>/<td> structure.
    All Bootstrap classes replaced with tw- utilities.
--}}

@php
$inputCls    = 'tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-800 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10 tw-transition-colors';
$textareaCls = 'tw-block tw-w-full tw-px-3 tw-py-2 tw-text-sm tw-text-slate-800 tw-bg-white tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10 tw-transition-colors tw-resize-y';
$labelCls    = 'tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-whitespace-nowrap';
$radioGroupCls = 'tw-space-y-1.5';
$radioCls    = 'tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-700 tw-cursor-pointer';
$subLabelCls = 'tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mt-3 tw-mb-1';
@endphp

@if($consultation_anesthesiste->id)
    {!! Html::model($consultation_anesthesiste)->form()->method('PUT')->action(route('consultation_anesthesiste.update', $consultation_anesthesiste->id))->class('')->open() !!}
@else
    {!! Html::form()->method('POST')->action(route('consultation_anesthesiste.store'))->class('')->open() !!}
@endif
@csrf

{{-- ── SECTION: CONSULTATION ──────────────────────────────── --}}
<tr>
    <td colspan="2" class="tw-px-3 tw-pt-5 tw-pb-2">
        <div class="tw-flex tw-items-center tw-gap-2">
            <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-[#1D4ED8]"></div>
            <h5 class="tw-text-sm tw-font-bold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wider tw-mb-0">Consultation</h5>
        </div>
    </td>
</tr>

<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Spécialité <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::input('text', 'specialite', null)->class($inputCls)->required() !!}</td>
</tr>
<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Médecin traitant <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::input('text', 'medecin_traitant', null)->class($inputCls)->required() !!}</td>
</tr>
<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Opérateur <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::input('text', 'operateur', null)->class($inputCls)->required() !!}</td>
</tr>
<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Date d'intervention <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::input('date', 'date_intervention', null)->class($inputCls.' tw-w-48')->required() !!}</td>
</tr>
<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Motif d'admission <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::input('text', 'motif_admission', null)->class($inputCls)->required() !!}</td>
</tr>
<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Mémo</td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::textarea('memo', null)->class($textareaCls)->rows(3) !!}</td>
</tr>

{{-- Anesthésie en salle --}}
<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">
        Anesthésie en salle d'opération <span class="tw-text-red-500">*</span>
    </td>
    <td class="tw-px-3 tw-py-2.5">
        <div class="{{ $radioGroupCls }}">
            @foreach([
                'Ambulatoire',
                'Urgence',
                "Entrée le jour de l'intervention",
                'Hospit < 10 jours',
            ] as $opt)
            <label class="{{ $radioCls }}">
                <input type="radio" name="anesthesi_salle[]" value="{{ $opt }}"
                       class="tw-accent-[#1D4ED8] tw-w-4 tw-h-4 tw-shrink-0"
                       {{ old('anesthesi_salle', $consultation_anesthesiste->anesthesi_salle) == $opt ? 'checked' : '' }}>
                {{ $opt }}
            </label>
            @endforeach
        </div>
    </td>
</tr>

<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Date d'hospitalisation <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::input('date', 'date_hospitalisation', null)->class($inputCls.' tw-w-48') !!}</td>
</tr>
<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Service <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::input('text', 'service', null)->class($inputCls)->placeholder('Ex: Urologie')->required() !!}</td>
</tr>
<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Classe ASA <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::input('text', 'classe_asa', null)->class($inputCls.' tw-w-32')->placeholder('Classe ASA')->required() !!}</td>
</tr>
<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Antécédents / Traitements <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::textarea('antecedent_traitement', null)->class($textareaCls)->rows(4)->required() !!}</td>
</tr>
<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Examens cliniques <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::textarea('examen_clinique', null)->class($textareaCls)->rows(4)->required() !!}</td>
</tr>
<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Allergies</td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::textarea('allergie', null)->class($textareaCls)->rows(3) !!}</td>
</tr>

{{-- Intubation sub-fields --}}
<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Intubation</td>
    <td class="tw-px-3 tw-py-2.5 tw-space-y-2">
        @foreach([
            ['intubation',              'Intubation'],
            ['mallampati',              'Mallampati'],
            ['distance_interincisive',  'Distance inter-incisive'],
            ['distance_thyromentoniere','Distance thyromentonière'],
            ['mobilite_servicale',      'Mobilité cervicale'],
        ] as [$name, $lbl])
        <div>
            <span class="{{ $subLabelCls }}">{{ $lbl }} :</span>
            {!! Html::input('text', $name, null)->class($inputCls) !!}
        </div>
        @endforeach
    </td>
</tr>

<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Traitement en cours <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::textarea('traitement_en_cours', null)->class($textareaCls)->rows(4)->required() !!}</td>
</tr>
<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Risque(s) <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::textarea('risque', null)->class($textareaCls)->rows(4)->required() !!}</td>
</tr>

{{-- ── SECTION: DÉCISION / PRESCRIPTIONS ────────────────────── --}}
<tr>
    <td colspan="2" class="tw-px-3 tw-pt-6 tw-pb-2">
        <div class="tw-flex tw-items-center tw-gap-2">
            <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-[#14B8A6]"></div>
            <h5 class="tw-text-sm tw-font-bold tw-text-[#14B8A6] tw-uppercase tw-tracking-wider tw-mb-0">Décision / Prescriptions</h5>
        </div>
    </td>
</tr>

{{-- Informations patient --}}
<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Informations données au patient</td>
    <td class="tw-px-3 tw-py-2.5 tw-space-y-3">
        <div>
            <span class="{{ $subLabelCls }}">Technique d'anesthésie <span class="tw-text-red-500">*</span></span>
            {!! Html::input('text', 'technique_anesthesie1', null)->class($inputCls)->required() !!}
        </div>
        <div>
            <span class="{{ $subLabelCls }}">Bénéfice / Risque <span class="tw-text-red-500">*</span></span>
            {!! Html::textarea('benefice_risque', null)->class($textareaCls)->rows(4)->required() !!}
        </div>
        <div>
            <span class="{{ $subLabelCls }}">Jeûne préopératoire <span class="tw-text-red-500">*</span></span>
            <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-1">
                <div class="tw-flex tw-items-center tw-gap-3 tw-text-sm tw-text-slate-600">
                    <span class="tw-w-24 tw-shrink-0">Solides :</span>
                    {!! Html::input('text', 'solide', null)->class($inputCls.' tw-w-32')->placeholder('H-') !!}
                </div>
                <div class="tw-flex tw-items-center tw-gap-3 tw-text-sm tw-text-slate-600">
                    <span class="tw-w-24 tw-shrink-0">Liquides clairs :</span>
                    {!! Html::input('text', 'liquide', null)->class($inputCls.' tw-w-32')->placeholder('H-') !!}
                </div>
            </div>
        </div>
        <div>
            <span class="{{ $subLabelCls }}">Adaptation au traitement personnel :</span>
            {!! Html::textarea('adaptation_traitement', null)->class($textareaCls)->rows(2) !!}
        </div>
        <div>
            <span class="{{ $subLabelCls }}">Autre :</span>
            {!! Html::textarea('autre1', null)->class($textareaCls)->rows(2) !!}
        </div>
    </td>
</tr>

{{-- Technique anesthésie --}}
<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Technique d'anesthésie envisagée <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">
        <div class="{{ $radioGroupCls }}">
            @foreach(['Anesthésie générale','Sédation','Rachidienne','Péridurale','ALR','Locale'] as $opt)
            <label class="{{ $radioCls }}">
                <input type="radio" name="technique_anesthesie[]" value="{{ $opt }}"
                       class="tw-accent-[#1D4ED8] tw-w-4 tw-h-4 tw-shrink-0"
                       {{ old('technique_anesthesie', $consultation_anesthesiste->technique_anesthesie) == $opt.',' ? 'checked' : '' }}>
                {{ $opt }}
            </label>
            @endforeach
        </div>
        <span class="{{ $subLabelCls }}">Autres :</span>
        <input type="text" name="technique_anesthesie[]" class="{{ $inputCls }} tw-mt-1" value="{{ old('technique_anesthesie') }}">
    </td>
</tr>

<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-middle {{ $labelCls }}">Antibioprophylaxie</td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::input('text', 'antibiotique', null)->class($inputCls) !!}</td>
</tr>
<tr>
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Synthèse pré-opératoire <span class="tw-text-red-500">*</span></td>
    <td class="tw-px-3 tw-py-2.5">{!! Html::textarea('synthese_preop', null)->class($textareaCls)->rows(3)->required() !!}</td>
</tr>

{{-- Examens paracliniques --}}
<tr class="tw-bg-slate-50">
    <td class="tw-px-3 tw-py-2.5 tw-align-top {{ $labelCls }}">Examens paracliniques</td>
    <td class="tw-px-3 tw-py-2.5 tw-space-y-2">
        @if(!empty($prescriptions->hematologie))
            <div><span class="{{ $subLabelCls }}">Gr / Rh :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
            <div><span class="{{ $subLabelCls }}">NFS :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
        @endif
        @if(!empty($prescriptions->hemostase))
            <div><span class="{{ $subLabelCls }}">TCK :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
            <div><span class="{{ $subLabelCls }}">TP / INR :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
        @endif
        @if(!empty($prescriptions->biochimie))
            <div><span class="{{ $subLabelCls }}">Créatinémie :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
            <div><span class="{{ $subLabelCls }}">Ionogramme :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
            <div><span class="{{ $subLabelCls }}">Urée :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
            <div><span class="{{ $subLabelCls }}">Glycémie :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
        @endif
        @if(!empty($prescriptions->urines))
            <div><span class="{{ $subLabelCls }}">ECBU :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
        @endif
        @if(!empty($prescriptions->serologie))
            <div><span class="{{ $subLabelCls }}">VIH :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
        @endif
        @if(!empty($prescriptions->examen))
            <div><span class="{{ $subLabelCls }}">E.C.G :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
            <div><span class="{{ $subLabelCls }}">Échographie cardiaque :</span><input type="text" name="examen_paraclinique[]" class="{{ $inputCls }}" value="{{ old('examen_paraclinique') }}"></div>
        @endif
        <div><span class="{{ $subLabelCls }}">Autres :</span>{!! Html::input('text', 'examen_paraclinique[]', null)->class($inputCls) !!}</div>
    </td>
</tr>

{{-- Hidden + submit --}}
<tr>
    {!! Html::hidden('patient_id', $patient->id) !!}
</tr>
<tr>
    <td colspan="2" class="tw-px-3 tw-py-4">
        <button type="submit"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-shadow-sm tw-border-0 tw-cursor-pointer">
            <i class="fas fa-check tw-text-xs"></i>
            Enregistrer
        </button>
    </td>
</tr>

{!! Html::form()->close() !!}