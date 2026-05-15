@extends('layouts.admin')
@section('title', 'CMCU | Résultats par discipline — ' . ($sections[$section] ?? $section))

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">
                        Résultats par discipline
                    </h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">
                        Classifiés par date de prise en charge · examen · patient · résultat
                    </p>
                </div>
                <a href="{{ route('laboratoire.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    Retour à la liste
                </a>
            </div>

            {{-- ── Discipline selector tabs ─────────────────────────── --}}
            @php
                $sectionColors = [
                    'hematologie'   => 'tw-bg-red-500',
                    'hemostase'     => 'tw-bg-orange-500',
                    'biochimie'     => 'tw-bg-yellow-500',
                    'hormonologie'  => 'tw-bg-lime-500',
                    'marqueurs'     => 'tw-bg-teal-500',
                    'bacteriologie' => 'tw-bg-cyan-500',
                    'spermiologie'  => 'tw-bg-sky-500',
                    'urines'        => 'tw-bg-blue-500',
                    'serologie'     => 'tw-bg-indigo-500',
                    'parasitologie' => 'tw-bg-violet-500',
                ];
                $sectionIcons = [
                    'hematologie'   => 'fa-droplet',
                    'hemostase'     => 'fa-tint',
                    'biochimie'     => 'fa-flask',
                    'hormonologie'  => 'fa-dna',
                    'marqueurs'     => 'fa-microscope',
                    'bacteriologie' => 'fa-bacterium',
                    'spermiologie'  => 'fa-circle-dot',
                    'urines'        => 'fa-toilet-paper',
                    'serologie'     => 'fa-shield-virus',
                    'parasitologie' => 'fa-bug',
                ];
            @endphp

            <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-6">
                @foreach($sections as $key => $label)
                <a href="{{ route('laboratoire.results_by_discipline', array_merge(request()->query(), ['section' => $key])) }}"
                   class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-rounded-lg tw-text-xs tw-font-semibold tw-no-underline tw-transition-all tw-border
                          {{ $section === $key
                             ? 'tw-text-white tw-border-transparent tw-shadow-sm ' . ($sectionColors[$key] ?? 'tw-bg-[#1D4ED8]')
                             : 'tw-bg-white tw-text-slate-600 tw-border-slate-200 hover:tw-border-slate-300 hover:tw-bg-slate-50' }}">
                    <i class="fas {{ $sectionIcons[$key] ?? 'fa-vial' }} tw-text-[10px]"></i>
                    {{ $label }}
                </a>
                @endforeach
            </div>

            {{-- ── Filter bar ───────────────────────────────────────── --}}
            <form method="GET" action="{{ route('laboratoire.results_by_discipline') }}"
                  class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-4 tw-mb-5
                         tw-flex tw-flex-wrap tw-gap-3 tw-items-end">

                <input type="hidden" name="section" value="{{ $section }}">

                <div class="tw-flex-1 tw-min-w-[180px]">
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Rechercher un patient
                    </label>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                           placeholder="Nom, prénom, n° dossier…"
                           class="tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-slate-50
                                  focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10">
                </div>

                <div>
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Du
                    </label>
                    <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}"
                           class="tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-slate-50
                                  focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                </div>

                <div>
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Au
                    </label>
                    <input type="date" name="date_to" value="{{ $dateTo ?? '' }}"
                           class="tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-slate-50
                                  focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                </div>

                <div>
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Par page
                    </label>
                    <select name="per_page"
                            class="tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-slate-50 focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        @foreach([15, 25, 50] as $n)
                        <option value="{{ $n }}" {{ ($perPage ?? 25) == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="tw-px-5 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl
                               hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer">
                    <i class="fas fa-search tw-mr-1.5 tw-text-xs"></i>
                    Filtrer
                </button>

                @if($search || $dateFrom || $dateTo)
                <a href="{{ route('laboratoire.results_by_discipline', ['section' => $section]) }}"
                   class="tw-px-4 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium
                          tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                    <i class="fas fa-times tw-mr-1 tw-text-xs"></i>
                    Réinitialiser
                </a>
                @endif
            </form>

            {{-- ── Active discipline header card ───────────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">

                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-shrink-0
                                {{ $sectionColors[$section] ?? 'tw-bg-[#1D4ED8]' }} tw-bg-opacity-10">
                        <i class="fas {{ $sectionIcons[$section] ?? 'fa-vial' }}
                                  {{ str_replace('tw-bg-', 'tw-text-', $sectionColors[$section] ?? 'tw-text-[#1D4ED8]') }}
                                  tw-text-sm"></i>
                    </div>
                    <div>
                        <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">
                            {{ $sections[$section] ?? $section }}
                        </h2>
                        <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">
                            {{ $examens->total() }} dossier{{ $examens->total() > 1 ? 's' : '' }} validé{{ $examens->total() > 1 ? 's' : '' }} trouvé{{ $examens->total() > 1 ? 's' : '' }}
                        </p>
                    </div>
                </div>

                {{-- ── Result table ──────────────────────────────────── --}}
                @forelse($examens as $examen)
                @php
                    $results  = $examen->getAttribute("{$section}_resultats") ?? [];
                    $datePC   = $examen->date_prelevement
                        ? $examen->date_prelevement->format('d/m/Y')
                        : ($examen->created_at ? $examen->created_at->format('d/m/Y') : '—');
                    $dateHeure= $examen->date_prelevement
                        ? $examen->date_prelevement->format('H:i')
                        : '—';
                @endphp

                {{-- Patient block --}}
                <div class="tw-border-b tw-border-slate-100 last:tw-border-b-0">

                    {{-- Patient / bon header row --}}
                    <div class="tw-px-6 tw-py-3 tw-bg-slate-50/60 tw-flex tw-items-center tw-gap-4 tw-flex-wrap">
                        {{-- Avatar --}}
                        <div class="tw-w-8 tw-h-8 tw-rounded-full tw-bg-teal-200 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <span class="tw-text-teal-700 tw-font-bold tw-text-xs">
                                {{ strtoupper(substr($examen->patient->prenom ?? $examen->patient->name ?? '?', 0, 1)) }}
                            </span>
                        </div>

                        {{-- Patient name + dossier --}}
                        <div class="tw-flex-1 tw-min-w-0">
                            <p class="tw-font-semibold tw-text-slate-800 tw-mb-0 tw-text-sm tw-leading-tight">
                                {{ $examen->patient->prenom ?? '' }}
                                {{ strtoupper($examen->patient->name ?? '—') }}
                                <span class="tw-font-normal tw-text-slate-400 tw-text-xs tw-ml-1">
                                    — N° {{ $examen->patient->numero_dossier ?? '—' }}
                                </span>
                            </p>
                        </div>

                        {{-- Date of prise en charge --}}
                        <div class="tw-flex tw-items-center tw-gap-1.5 tw-shrink-0">
                            <div class="tw-flex tw-items-center tw-gap-1 tw-px-2.5 tw-py-1 tw-bg-blue-50 tw-border tw-border-blue-200 tw-rounded-lg">
                                <i class="fas fa-calendar-check tw-text-blue-400 tw-text-[10px]"></i>
                                <span class="tw-text-xs tw-font-semibold tw-text-blue-700">{{ $datePC }}</span>
                                @if($dateHeure !== '—')
                                <span class="tw-text-[10px] tw-text-blue-400 tw-ml-0.5">{{ $dateHeure }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Bon number --}}
                        <a href="{{ route('laboratoire.show', $examen->id) }}"
                           class="tw-font-mono tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-bg-[#BFDBFE]/40
                                  tw-px-2 tw-py-1 tw-rounded-md tw-no-underline hover:tw-bg-[#BFDBFE] tw-transition-colors tw-shrink-0">
                            {{ $examen->numero_bon ?? '—' }}
                        </a>

                        {{-- Status badge --}}
                        <span class="tw-px-2 tw-py-0.5 tw-rounded-full tw-text-[10px] tw-font-semibold {{ $examen->statut_color }} tw-shrink-0">
                            {{ $examen->statut_label }}
                        </span>
                    </div>

                    {{-- Results table --}}
                    @if(count($results) > 0)
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm">
                            <thead class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <tr>
                                    <th class="tw-px-6 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[30%]">
                                        Examen / Test
                                    </th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[12%]">
                                        Résultat
                                    </th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[8%]">
                                        Unité
                                    </th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[10%]">
                                        Prix unitaire
                                    </th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[20%]">
                                        Valeurs de référence
                                    </th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[12%]">
                                        Interprétation
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="tw-divide-y tw-divide-slate-50">
                                @foreach($results as $row)
                                @php
                                    $flag = $row['flag'] ?? 'normal';
                                    $flagCfg = match($flag) {
                                        'bas'      => ['label' => '▼ Bas',      'cls' => 'tw-bg-blue-100 tw-text-blue-700'],
                                        'eleve'    => ['label' => '▲ Élevé',    'cls' => 'tw-bg-orange-100 tw-text-orange-700'],
                                        'critique' => ['label' => '⚠ Critique', 'cls' => 'tw-bg-red-100 tw-text-red-700 tw-font-bold'],
                                        default    => ['label' => 'Normal',     'cls' => 'tw-bg-green-100 tw-text-green-700'],
                                    };
                                    $rowBg = $flag === 'critique'
                                        ? 'tw-bg-red-50/50'
                                        : ($flag !== 'normal' ? 'tw-bg-amber-50/30' : '');
                                @endphp
                                <tr class="hover:tw-bg-slate-50/70 tw-transition-colors {{ $rowBg }}">
                                    <td class="tw-px-6 tw-py-2.5 tw-text-sm tw-font-medium tw-text-slate-700">
                                        {{ $row['test'] ?? '—' }}
                                    </td>
                                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-font-bold
                                               {{ $flag === 'critique' ? 'tw-text-red-600' : ($flag !== 'normal' ? 'tw-text-amber-700' : 'tw-text-slate-800') }}">
                                        {{ $row['valeur'] ?? '—' }}
                                    </td>
                                    <td class="tw-px-4 tw-py-2.5 tw-text-xs tw-text-slate-500">
                                        {{ $row['unite'] ?? '' }}
                                    </td>
                                    @php
                                        $priceKey = $section . '|' . ($row['test'] ?? '');
                                        $price = optional($tarifs[$priceKey] ?? null)->prix_unitaire;
                                        
                                    @endphp
                                    <td class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-font-mono tw-text-slate-600">
                                        @if($price)
                                            {{ number_format($price, 0, ',', ' ') }} FCFA
                                        @else
                                            <span class="tw-text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="tw-px-4 tw-py-2.5 tw-text-xs tw-text-slate-500">
                                        @if(!empty($row['ref_min']) || !empty($row['ref_max']))
                                            {{ $row['ref_min'] ?? '' }}
                                            @if(!empty($row['ref_min']) && !empty($row['ref_max'])) – @endif
                                            {{ $row['ref_max'] ?? '' }}
                                        @else
                                            <span class="tw-text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="tw-px-4 tw-py-2.5 tw-text-center">
                                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-[10px] tw-font-semibold {{ $flagCfg['cls'] }}">
                                            {{ $flagCfg['label'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="tw-px-6 tw-py-4 tw-text-xs tw-text-slate-400 tw-italic">
                        Aucun résultat enregistré pour cette section sur ce bon.
                    </div>
                    @endif
                </div>

                @empty
                <div class="tw-px-6 tw-py-14 tw-text-center">
                    <div class="tw-flex tw-flex-col tw-items-center tw-gap-3">
                        <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-flask tw-text-slate-400 tw-text-xl"></i>
                        </div>
                        <p class="tw-text-slate-500 tw-text-sm tw-mb-0">
                            Aucun résultat validé pour la section
                            <strong>{{ $sections[$section] ?? $section }}</strong>
                            @if($search) avec le critère « {{ $search }} » @endif
                        </p>
                    </div>
                </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            @if($examens->hasPages())
            <div class="tw-mt-4">
                {{ $examens->appends(request()->query())->links() }}
            </div>
            @endif

        </main>
    </div>
</div>
@stop