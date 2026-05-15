@extends('layouts.admin')
@section('title', 'CMCU | Fiche de paramètres patient')
@section('content')

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- ── Page Heading ─────────────────────────────────── --}}
            <div class="tw-mb-6 tw-rounded-2xl tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-shadow-sm">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-heartbeat tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Paramètres patient</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">{{ $patient->name }} {{ $patient->prenom }}</p>
                    </div>
                </div>
                <a href="{{ route('patients.show', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour au dossier</span>
                </a>
            </div>

            <div class="tw-max-w-3xl tw-mx-auto tw-space-y-5">

                {{-- ── Patient info card ──────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-5 tw-py-3 tw-border-b tw-border-slate-100 tw-bg-slate-50 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-user-circle tw-text-[#1D4ED8] tw-text-sm"></i>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-0 tw-uppercase tw-tracking-wide">Informations patient</h3>
                    </div>
                    <div class="tw-divide-y tw-divide-slate-50">
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3">
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-32 tw-shrink-0">Nom et prénom</span>
                            <span class="tw-text-sm tw-font-semibold tw-text-slate-700">{{ $patient->name }} {{ $patient->prenom }}</span>
                        </div>
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3">
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-32 tw-shrink-0"><i class="fas fa-phone tw-mr-1"></i>Téléphone</span>
                            <span class="tw-text-sm tw-text-slate-600">{{ $patient->portable_1 ?? '—' }}</span>
                        </div>
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3">
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-32 tw-shrink-0"><i class="fas fa-envelope tw-mr-1"></i>E-Mail</span>
                            <span class="tw-text-sm tw-text-slate-600">{{ $patient->email ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- ── Parameter records ──────────────────────────── --}}
                @foreach($parametres as $parametre)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    {{-- Record header --}}
                    <div class="tw-px-5 tw-py-3 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-calendar-alt tw-text-white/70 tw-text-xs"></i>
                            <span class="tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Relevé du</span>
                        </div>
                        <span class="tw-text-xs tw-font-bold tw-text-white tw-bg-white/20 tw-rounded-lg tw-px-2.5 tw-py-1">
                            {{ $parametre->created_at->toFormattedDateString() }}
                        </span>
                    </div>

                    {{-- Vitals grid --}}
                    <div class="tw-grid tw-grid-cols-2 sm:tw-grid-cols-3 lg:tw-grid-cols-4">
                        @foreach([
                            ['Date de naissance', $parametre->date_naissance,                              'fas fa-birthday-cake',     'tw-bg-[#BFDBFE] tw-text-[#1D4ED8]'],
                            ['Âge',               ($parametre->age ?? '—') . ' ans',                       'fas fa-user',              'tw-bg-teal-100 tw-text-teal-700'],
                            ['Poids',             ($parametre->poids ?? '—') . ' kg',                      'fas fa-weight',            'tw-bg-amber-100 tw-text-amber-700'],
                            ['Taille',            ($parametre->taille ?? '—') . ' m',                      'fas fa-ruler-vertical',    'tw-bg-indigo-100 tw-text-indigo-600'],
                            ['Température',       ($parametre->temperature ?? '—') . ' °C',                'fas fa-thermometer-half',  'tw-bg-red-100 tw-text-red-500'],
                            ['Glycémie',          ($parametre->glycemie ?? '—') . ' g/l',                  'fas fa-tint',              'tw-bg-pink-100 tw-text-pink-600'],
                            ['SPO2',              ($parametre->spo2 ?? '—') . ' %',                        'fas fa-lungs',             'tw-bg-sky-100 tw-text-sky-600'],
                            ['IMC / BMI',         $parametre->inc_bmi ?? '—',                              'fas fa-calculator',        'tw-bg-green-100 tw-text-green-600'],
                            ['FR',                ($parametre->fr ?? '—') . ' Mvts/min',                   'fas fa-wind',              'tw-bg-violet-100 tw-text-violet-600'],
                            ['FC',                ($parametre->fc ?? '—') . ' Pls/min',                    'fas fa-heartbeat',         'tw-bg-rose-100 tw-text-rose-600'],
                        ] as [$label, $value, $icon, $colors])
                        <div class="tw-px-4 tw-py-3 tw-flex tw-items-center tw-gap-3 tw-border-b tw-border-r tw-border-slate-50">
                            <div class="tw-w-7 tw-h-7 tw-rounded-lg {{ $colors }} tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="{{ $icon }} tw-text-[10px]"></i>
                            </div>
                            <div class="tw-min-w-0">
                                <p class="tw-text-[10px] tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0 tw-truncate">{{ $label }}</p>
                                <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">{{ $value }}</p>
                            </div>
                        </div>
                        @endforeach

                        {{-- T.A — spans full width to accommodate two values --}}
                        <div class="tw-col-span-2 sm:tw-col-span-3 lg:tw-col-span-4 tw-px-4 tw-py-3 tw-flex tw-items-center tw-gap-3 tw-border-b tw-border-slate-50">
                            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-red-100 tw-text-red-500 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-stethoscope tw-text-[10px]"></i>
                            </div>
                            <div>
                                <p class="tw-text-[10px] tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">T.A (Tension artérielle)</p>
                                <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">
                                    <span class="tw-text-slate-500 tw-font-normal tw-text-xs">Bras gauche&nbsp;:</span>
                                    {{ $parametre->bras_gauche ?? '—' }} mmHg
                                    <span class="tw-mx-2 tw-text-slate-300">·</span>
                                    <span class="tw-text-slate-500 tw-font-normal tw-text-xs">Bras droit&nbsp;:</span>
                                    {{ $parametre->bras_droit ?? '—' }} mmHg
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </main>
    </div>
</div>

@stop