@extends('layouts.admin')
@section('title', 'CMCU | Surveillance rapprochée des paramètres')

@section('content')

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('show', \App\Models\User::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- ── Page Heading ─────────────────────────────────── --}}
            <div class="tw-mb-6 tw-rounded-2xl tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-shadow-sm">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-monitor-heart-rate tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Surveillance rapprochée</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">Détail des paramètres</p>
                    </div>
                </div>
                <a href="{{ route('patients.show', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour au dossier</span>
                </a>
            </div>

            @php
            $paramFields = [
                ['label' => 'T.A',                   'field' => 'ta',                  'icon' => 'fas fa-stethoscope',     'color' => 'tw-bg-red-100 tw-text-red-500'],
                ['label' => 'F.R',                   'field' => 'fr',                  'icon' => 'fas fa-wind',            'color' => 'tw-bg-violet-100 tw-text-violet-600'],
                ['label' => 'Pouls',                 'field' => 'pouls',               'icon' => 'fas fa-heartbeat',       'color' => 'tw-bg-rose-100 tw-text-rose-600'],
                ['label' => 'SPO2',                  'field' => 'spo2',                'icon' => 'fas fa-lungs',           'color' => 'tw-bg-sky-100 tw-text-sky-600'],
                ['label' => 'Température',           'field' => 'temperature',         'icon' => 'fas fa-thermometer-half','color' => 'tw-bg-amber-100 tw-text-amber-600'],
                ['label' => 'Diurèse',               'field' => 'diurese',             'icon' => 'fas fa-tint',            'color' => 'tw-bg-blue-100 tw-text-blue-600'],
                ['label' => 'Conscience',            'field' => 'conscience',          'icon' => 'fas fa-brain',           'color' => 'tw-bg-indigo-100 tw-text-indigo-600'],
                ['label' => 'Douleur',               'field' => 'douleur',             'icon' => 'fas fa-bolt',            'color' => 'tw-bg-orange-100 tw-text-orange-600'],
                ['label' => 'Observations / Plaintes','field' => 'observation_plainte','icon' => 'fas fa-comment-medical', 'color' => 'tw-bg-teal-100 tw-text-teal-600'],
            ];
            @endphp

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-5">

                {{-- ── Pré-opératoire card ─────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <a href="{{ route('surveillance_rapproche', $patient->id) }}" class="tw-no-underline">
                        <div class="tw-px-5 tw-py-3 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <i class="fas fa-hourglass-start tw-text-white/70 tw-text-xs"></i>
                                <span class="tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Pré-opératoire</span>
                            </div>
                            @if (!empty($paramPre))
                            <span class="tw-text-xs tw-text-white/80 tw-font-medium">
                                {{ $paramPre->date }}
                            </span>
                            @endif
                        </div>
                    </a>
                    @if (!empty($paramPre))
                    <div class="tw-divide-y tw-divide-slate-50">
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3 tw-bg-slate-50">
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-36 tw-shrink-0">Heure</span>
                            <span class="tw-text-sm tw-font-semibold tw-text-slate-700">{{ $paramPre->heure }}</span>
                        </div>
                        @foreach($paramFields as $pf)
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-6 tw-h-6 tw-rounded-lg {{ $pf['color'] }} tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="{{ $pf['icon'] }} tw-text-[9px]"></i>
                            </div>
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-28 tw-shrink-0">{{ $pf['label'] }}</span>
                            <span class="tw-text-sm tw-text-slate-700">{{ $paramPre->{$pf['field']} ?: '—' }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="tw-px-5 tw-py-8 tw-text-center">
                        <i class="fas fa-inbox tw-text-2xl tw-text-slate-200 tw-block tw-mb-2"></i>
                        <p class="tw-text-sm tw-text-slate-400 tw-mb-0">Aucun relevé pré-opératoire</p>
                    </div>
                    @endif
                </div>

                {{-- ── Post-opératoire card ─────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <a href="{{ route('surveillance_rapproche', $patient->id) }}" class="tw-no-underline">
                        <div class="tw-px-5 tw-py-3 tw-bg-[#14B8A6] tw-flex tw-items-center tw-justify-between">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <i class="fas fa-hourglass-end tw-text-white/70 tw-text-xs"></i>
                                <span class="tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Post-opératoire</span>
                            </div>
                            @if (!empty($paramPost))
                            <span class="tw-text-xs tw-text-white/80 tw-font-medium">
                                {{ $paramPost->date }}
                            </span>
                            @endif
                        </div>
                    </a>
                    @if (!empty($paramPost))
                    <div class="tw-divide-y tw-divide-slate-50">
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3 tw-bg-slate-50">
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-36 tw-shrink-0">Heure</span>
                            <span class="tw-text-sm tw-font-semibold tw-text-slate-700">{{ $paramPost->heure }}</span>
                        </div>
                        @foreach($paramFields as $pf)
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-6 tw-h-6 tw-rounded-lg {{ $pf['color'] }} tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="{{ $pf['icon'] }} tw-text-[9px]"></i>
                            </div>
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-28 tw-shrink-0">{{ $pf['label'] }}</span>
                            <span class="tw-text-sm tw-text-slate-700">{{ $paramPost->{$pf['field']} ?: '—' }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="tw-px-5 tw-py-8 tw-text-center">
                        <i class="fas fa-inbox tw-text-2xl tw-text-slate-200 tw-block tw-mb-2"></i>
                        <p class="tw-text-sm tw-text-slate-400 tw-mb-0">Aucun relevé post-opératoire</p>
                    </div>
                    @endif
                </div>

            </div>

            {{-- Include form modal --}}
            @include('admin.modal.surveillance_rapproche_param')

        </main>
        @endcan
    </div>
</div>

@stop