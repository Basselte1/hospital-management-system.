@extends('layouts.admin')
@section('title', 'CMCU | Surveillance d\'aptitude')

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
                        <i class="fas fa-chart-line tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Surveillance d'aptitude</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">Score ≥ 9/10</p>
                    </div>
                </div>
                <div class="tw-flex tw-items-center tw-gap-2">
                    <a href="{{ route('patients.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30">
                        <i class="fas fa-list-ul tw-text-xs"></i>
                        <span class="tw-hidden sm:tw-inline">Patients</span>
                    </a>
                    <a href="{{ route('patients.show', $patient->id) }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30">
                        <i class="fas fa-arrow-left tw-text-xs"></i>
                        <span class="tw-hidden sm:tw-inline">Dossier</span>
                    </a>
                </div>
            </div>

            {{-- ── Add soin button ──────────────────────────────── --}}
            @can('infirmier', \App\Models\Patient::class)
            <div class="tw-mb-4">
                <button type="button"
                        title="Administrer des soins"
                        data-bs-toggle="modal"
                        data-bs-target="#SurveillanceAptitude"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-border-0 tw-shadow-sm tw-transition-colors">
                    <i class="fas fa-plus tw-text-xs"></i> Nouveau relevé
                </button>
            </div>
            @endcan

            {{-- ── Vitals matrix table ──────────────────────────── --}}
            @can('med_inf_anes', \App\Models\Patient::class)
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-px-5 tw-py-3 tw-bg-[#1D4ED8]">
                    <h3 class="tw-text-xs tw-font-semibold tw-text-white tw-mb-0 tw-uppercase tw-tracking-wide">
                        <i class="fas fa-table tw-mr-1.5 tw-text-white/70"></i>Relevés de surveillance
                    </h3>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm tw-border-collapse tw-min-w-max">
                        <tbody class="tw-divide-y tw-divide-slate-50">

                            {{-- Each row: sticky label column + one td per time point --}}
                            @php
                            $rows = [
                                ['label' => 'HORAIRES',        'field' => 'horaire',       'color' => 'tw-bg-[#1D4ED8]',     'text' => 'tw-text-white',     'bold' => true],
                                ['label' => 'TA s/d',          'field' => 'ta',            'color' => 'tw-bg-slate-50',      'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'FC',              'field' => 'fc',            'color' => 'tw-bg-white',         'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'SPO2',            'field' => 'spo2',          'color' => 'tw-bg-slate-50',      'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'FR',              'field' => 'fr',            'color' => 'tw-bg-white',         'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'DOULEUR (EN/EVA)','field' => 'douleur',       'color' => 'tw-bg-slate-50',      'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'TEMPÉRATURE',     'field' => 'temperature',   'color' => 'tw-bg-white',         'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'GLYCÉMIE',        'field' => 'glycemie',      'color' => 'tw-bg-slate-50',      'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'SÉDATION',        'field' => 'sedation',      'color' => 'tw-bg-white',         'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'NAUSÉES',         'field' => 'nausee',        'color' => 'tw-bg-slate-50',      'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'VOMISSEMENTS',    'field' => 'vomissement',   'color' => 'tw-bg-white',         'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'SAIGNEMENTS',     'field' => 'saignement',    'color' => 'tw-bg-slate-50',      'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'PANSEMENTS',      'field' => 'pansement',     'color' => 'tw-bg-white',         'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'DRAINS',          'field' => 'drains',        'color' => 'tw-bg-slate-50',      'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'MICTION',         'field' => 'miction',       'color' => 'tw-bg-white',         'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'LEVER',           'field' => 'lever',         'color' => 'tw-bg-slate-50',      'text' => 'tw-text-slate-700', 'bold' => false],
                                ['label' => 'SCORE',           'field' => 'score',         'color' => 'tw-bg-[#BFDBFE]',    'text' => 'tw-text-[#1D4ED8]', 'bold' => true],
                            ];
                            @endphp

                            @foreach($rows as $row)
                            <tr class="{{ $row['color'] }}">
                                <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-[11px] tw-font-semibold tw-uppercase tw-tracking-wide tw-whitespace-nowrap tw-border-r tw-border-slate-200 tw-sticky tw-left-0 {{ $row['color'] }} {{ $row['text'] }} tw-min-w-[9rem]">
                                    {{ $row['label'] }}
                                </th>
                                @foreach($surveillance_scores as $surveillance_score)
                                <td class="tw-px-4 tw-py-2.5 tw-text-center tw-text-xs tw-whitespace-nowrap tw-border-r tw-border-slate-100 {{ $row['text'] }}
                                    {{ $row['bold'] ? 'tw-font-semibold' : '' }}">
                                    {{ $surveillance_score->{$row['field']} }}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

        </main>
        @endcan
    </div>
</div>

{{-- Modal zone --}}
@include('admin.modal.surveillance_aptitude_form')

@stop