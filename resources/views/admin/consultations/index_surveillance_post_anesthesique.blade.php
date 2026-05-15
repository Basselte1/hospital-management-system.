@extends('layouts.admin')
@section('title', 'CMCU | Surveillance post-anesthésique')
@section('content')

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            @can('show', \App\Models\User::class)

            {{-- ── Page Heading ─────────────────────────────────── --}}
            <div class="tw-mb-6 tw-rounded-2xl tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-shadow-sm">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-procedures tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Surveillance post-anesthésique</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">{{ $patient->name }} {{ $patient->prenom }}</p>
                    </div>
                </div>
                <a href="{{ route('patients.show', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour au dossier patient</span>
                </a>
            </div>

            <div class="tw-max-w-4xl tw-mx-auto tw-space-y-4">
                @foreach($surveillance_post_anesthesiques as $surveillance_post_anesthesique)
                @if(!empty($surveillance_post_anesthesique->surveillance))
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-5 tw-py-3 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                        <span class="tw-text-xs tw-font-bold tw-text-white tw-flex tw-items-center tw-gap-1.5">
                            <i class="fas fa-calendar-day"></i>
                            DATE : {{ $surveillance_post_anesthesique->date_creation }}
                        </span>
                        <button class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-text-xs tw-font-semibold tw-px-3 tw-py-1.5 tw-border-0 tw-transition-colors tw-cursor-pointer"
                                data-bs-toggle="modal"
                                data-bs-target="#SpostAnesthUpdate{{ $surveillance_post_anesthesique->id }}"
                                title="Apporter des modifications">
                            <i class="far fa-edit"></i> Modifier
                        </button>
                    </div>
                    <div class="tw-divide-y tw-divide-slate-50">
                        @if(!empty($surveillance_post_anesthesique->surveillance))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-36 tw-shrink-0 tw-pt-0.5">SURVEILLANCE</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1 tw-whitespace-pre-line">{{ $surveillance_post_anesthesique->surveillance }}</span>
                        </div>
                        @endif
                        @if(!empty($surveillance_post_anesthesique->traitement))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-36 tw-shrink-0 tw-pt-0.5">TRAITEMENT</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1 tw-whitespace-pre-line">{{ $surveillance_post_anesthesique->traitement }}</span>
                        </div>
                        @endif
                        @if(!empty($surveillance_post_anesthesique->examen_paraclinique))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-36 tw-shrink-0 tw-pt-0.5">EXAMEN</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1 tw-whitespace-pre-line">{{ $surveillance_post_anesthesique->examen_paraclinique }}</span>
                        </div>
                        @endif
                        @if(!empty($surveillance_post_anesthesique->observation))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-36 tw-shrink-0 tw-pt-0.5">OBSERVATION</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1 tw-whitespace-pre-line">{{ $surveillance_post_anesthesique->observation }}</span>
                        </div>
                        @endif
                        @if(!empty($surveillance_post_anesthesique->date_sortie))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-36 tw-shrink-0 tw-pt-0.5">DATE / HEURE SORTIE</span>
                            <div class="tw-flex-1 tw-text-sm tw-text-slate-700">
                                <p class="tw-mb-0.5">Date : {{ $surveillance_post_anesthesique->date_sortie }}</p>
                                <p class="tw-mb-0">Heure : {{ $surveillance_post_anesthesique->heur_sortie }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            @include('admin.modal.surveillance_post_a_update')
            @endcan

        </main>
    </div>
</div>
@stop