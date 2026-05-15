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
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">Paramètres pré / post-opératoires</p>
                    </div>
                </div>
                <a href="{{ route('surveillance_rapproche.index', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour</span>
                </a>
            </div>

            @php
            $columns = ['PÉRIODES', 'DATE / HEURE', 'T.A', 'F.R', 'POULS', 'SPO2', 'T°', 'DIURÈSE', 'CONSCIENCE', 'DOULEUR', 'OBSERVATIONS / PLAINTES'];
            $fields  = ['', 'date_heure', 'ta', 'fr', 'pouls', 'spo2', 'temperature', 'diurese', 'conscience', 'douleur', 'observation_plainte'];
            @endphp

            {{-- ── Pré-opératoire ──────────────────────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-4">
                <button type="button" onclick="toggleSection('pre')"
                        class="tw-w-full tw-px-5 tw-py-3 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between tw-border-0 tw-cursor-pointer">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-minus-circle tw-text-white/70 tw-text-xs"></i>
                        <span class="tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Pré-opératoire</span>
                    </div>
                    <i id="pre-chevron" class="fas fa-chevron-up tw-text-white/60 tw-text-xs tw-transition-transform"></i>
                </button>
                <div id="pre-section" class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-xs tw-border-collapse tw-min-w-max">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                @foreach($columns as $col)
                                <th class="tw-px-3 tw-py-2.5 tw-text-left tw-text-[10px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-50">
                            @if (!empty($paramPres))
                                @foreach ($paramPres as $paramPre)
                                <tr class="hover:tw-bg-slate-50/60 tw-transition-colors">
                                    <td class="tw-px-3 tw-py-2.5"></td>
                                    <td class="tw-px-3 tw-py-2.5 tw-font-semibold tw-text-slate-700 tw-whitespace-nowrap">{{ $paramPre->date }} / {{ $paramPre->heure }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPre->ta }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPre->fr }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPre->pouls }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPre->spo2 }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPre->temperature }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPre->diurese }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPre->conscience }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPre->douleur }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPre->observation_plainte }}</td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="11" class="tw-px-4 tw-py-6 tw-text-center tw-text-slate-400 tw-text-xs">
                                    Aucun relevé pré-opératoire enregistré
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Post-opératoire ─────────────────────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <button type="button" onclick="toggleSection('post')"
                        class="tw-w-full tw-px-5 tw-py-3 tw-bg-[#14B8A6] tw-flex tw-items-center tw-justify-between tw-border-0 tw-cursor-pointer">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-plus-circle tw-text-white/70 tw-text-xs"></i>
                        <span class="tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Post-opératoire</span>
                    </div>
                    <i id="post-chevron" class="fas fa-chevron-up tw-text-white/60 tw-text-xs tw-transition-transform"></i>
                </button>
                <div id="post-section" class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-xs tw-border-collapse tw-min-w-max">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                @foreach($columns as $col)
                                <th class="tw-px-3 tw-py-2.5 tw-text-left tw-text-[10px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-50">
                            @if (!empty($paramPosts))
                                @foreach ($paramPosts as $paramPost)
                                <tr class="hover:tw-bg-slate-50/60 tw-transition-colors">
                                    <td class="tw-px-3 tw-py-2.5"></td>
                                    <td class="tw-px-3 tw-py-2.5 tw-font-semibold tw-text-slate-700 tw-whitespace-nowrap">{{ $paramPost->date }} / {{ $paramPost->heure }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPost->ta }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPost->fr }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPost->pouls }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPost->spo2 }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPost->temperature }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPost->diurese }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPost->conscience }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPost->douleur }}</td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-600">{{ $paramPost->observation_plainte }}</td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="11" class="tw-px-4 tw-py-6 tw-text-center tw-text-slate-400 tw-text-xs">
                                    Aucun relevé post-opératoire enregistré
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
        @endcan
    </div>
</div>

<script>
function toggleSection(id) {
    const section = document.getElementById(id + '-section');
    const chevron = document.getElementById(id + '-chevron');
    if (section.style.display === 'none') {
        section.style.display = '';
        chevron.classList.remove('tw-rotate-180');
    } else {
        section.style.display = 'none';
        chevron.classList.add('tw-rotate-180');
    }
}
</script>

@stop