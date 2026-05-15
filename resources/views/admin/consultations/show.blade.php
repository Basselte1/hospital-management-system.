@extends('layouts.admin')
@section('title', 'CMCU | Dossier patient')

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
                        <i class="fas fa-folder-open tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Dossier patient</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">Résumé du dossier médical</p>
                    </div>
                </div>
            </div>

            <div class="tw-max-w-3xl tw-mx-auto tw-space-y-4">

                {{-- ── Patient summary card ──────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-5 tw-py-3 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-user-circle tw-text-white tw-text-sm"></i>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-white tw-mb-0 tw-uppercase tw-tracking-wide">Informations médicales</h3>
                    </div>
                    <div class="tw-divide-y tw-divide-slate-50">
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">Médecin</span>
                            <span class="tw-text-sm tw-text-slate-700">{{ $consultations->medecin_r }}</span>
                        </div>
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">Groupe sanguin</span>
                            <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-red-100 tw-text-red-700 tw-text-xs tw-font-bold tw-px-2.5 tw-py-1">
                                {{ $consultations->groupe ?: '—' }}
                            </span>
                        </div>
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">Allergie</span>
                            <span class="tw-text-sm tw-text-slate-700">{{ $consultations->allergie ?: 'Aucune' }}</span>
                        </div>
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">Antécédent médical</span>
                            <span class="tw-text-sm tw-text-slate-700">{{ $consultations->antecedent_m ?: '—' }}</span>
                        </div>
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">Antécédent chirurgicaux</span>
                            <span class="tw-text-sm tw-text-slate-700">{{ $consultations->antecedent_c ?: '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- ── Callout sections ──────────────────────────── --}}
                @foreach([
                    ['icon' => 'fas fa-microscope', 'color' => 'tw-bg-[#BFDBFE] tw-text-[#1D4ED8]', 'title' => 'Diagnostique', 'value' => $consultations->diagnostic],
                    ['icon' => 'fas fa-comments', 'color' => 'tw-bg-teal-100 tw-text-teal-700', 'title' => 'Interrogatoire', 'value' => $consultations->interrogatoire],
                    ['icon' => 'fas fa-clipboard-check', 'color' => 'tw-bg-amber-100 tw-text-amber-700', 'title' => 'Proposition', 'value' => $consultations->proposition],
                    ['icon' => 'fas fa-file-medical', 'color' => 'tw-bg-indigo-100 tw-text-indigo-700', 'title' => 'Motif de la consultation', 'value' => $consultations->motif_c],
                ] as $block)
                @if(!empty($block['value']))
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-5 tw-py-3 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg {{ $block['color'] }} tw-flex tw-items-center tw-justify-center tw-shrink-0 tw-bg-opacity-100">
                            <i class="{{ $block['icon'] }} tw-text-xs"></i>
                        </div>
                        <h4 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0 tw-uppercase tw-tracking-wide">{{ $block['title'] }}</h4>
                    </div>
                    <div class="tw-px-5 tw-py-4">
                        <p class="tw-text-sm tw-text-slate-700 tw-leading-relaxed tw-mb-0 tw-whitespace-pre-line">{{ $block['value'] }}</p>
                    </div>
                </div>
                @endif
                @endforeach

            </div>
        </main>
    </div>
</div>

@stop