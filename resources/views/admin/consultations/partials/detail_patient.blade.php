{{--
    Partial: detail_patient.blade.php
    Used inside a <table> — keep the <tbody> wrapper.
    Already using tw- classes; this version cleans up consistency.
--}}
<tbody style="display: none;" id="myDIV">

    {{-- ── Patient identity ─────────────────────────────────────────── --}}
    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-52 tw-whitespace-nowrap">
            Nom et Prénom
        </td>
        <td class="tw-px-3 tw-py-2.5 tw-font-semibold tw-text-slate-800 tw-text-sm">
            {{ $patient->name }} {{ $patient->prenom }}
        </td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-52 tw-whitespace-nowrap">
            Numéro de Dossier
        </td>
        <td class="tw-px-3 tw-py-2.5">
            <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-xs tw-font-bold tw-px-2.5 tw-py-0.5 tw-tracking-wide">
                {{ $patient->numero_dossier }}
            </span>
        </td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">
            Frais de {{ strtoupper($patient->details_motif ?? 'Consultation') }}
        </td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-font-bold tw-text-[#1D4ED8]">
            {{ number_format($patient->montant, 0, ',', ' ') }}&nbsp;FCFA
        </td>
    </tr>

    {{-- ── Dossier details ──────────────────────────────────────────── --}}
    @foreach($patient->dossiers as $dossier)

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Genre</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700">{{ $dossier->sexe }}</td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Profession</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700">{{ $dossier->profession ?: '—' }}</td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Adresse</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700">{{ $dossier->adresse ?: '—' }}</td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Portable</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-space-y-0.5">
            <div>{{ $dossier->portable_1 ?: '—' }}</div>
            @if($dossier->portable_2)
            <div class="tw-text-slate-500">{{ $dossier->portable_2 }}</div>
            @endif
        </td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Fax</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700">{{ $dossier->fax ?: '—' }}</td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Email</td>
        <td class="tw-px-3 tw-py-2.5">
            @if($dossier->email)
            <a href="mailto:{{ $dossier->email }}"
               class="tw-text-[#1D4ED8] hover:tw-underline tw-no-underline tw-text-sm">
                {{ $dossier->email }}
            </a>
            @else
            <span class="tw-text-slate-400">—</span>
            @endif
        </td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Lieu de naissance</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700">{{ $dossier->lieu_naissance ?: '—' }}</td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Date de naissance</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700">{{ $dossier->date_naissance ?: '—' }}</td>
    </tr>

    <tr>
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Personne à contacter</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700">{{ $dossier->personne_contact ?: '—' }}</td>
    </tr>

    <tr class="tw-bg-slate-50">
        <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Tél. personne à contacter</td>
        <td class="tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700">{{ $dossier->tel_personne_contact ?: '—' }}</td>
    </tr>

    @endforeach

</tbody>