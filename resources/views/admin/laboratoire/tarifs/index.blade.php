@extends('layouts.admin')
@section('title', 'CMCU | Tarifs de laboratoire')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Tarifs de laboratoire</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">
                        Grille tarifaire des examens biologiques par discipline — prix en FCFA
                        @if(auth()->user()->role_id !== 1)
                        <span class="tw-ml-2 tw-text-xs tw-bg-slate-100 tw-text-slate-500 tw-px-2 tw-py-0.5 tw-rounded-full">
                            <i class="fas fa-eye tw-mr-1 tw-text-[10px]"></i>Lecture seule
                        </span>
                        @endif
                    </p>
                </div>
                @if(auth()->user()->role_id === 1)
                <a href="{{ route('tarifs_labo.create') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white
                          tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors
                          tw-no-underline tw-shadow-sm tw-border-0">
                    <i class="fas fa-plus tw-text-xs"></i>
                    Nouveau tarif
                </a>
                @endif
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-check-circle tw-text-green-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-green-700 tw-mb-0">{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-circle-exclamation tw-text-red-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-red-600 tw-mb-0">{{ session('error') }}</p>
            </div>
            @endif

            {{-- ── Filter bar ───────────────────────────────────────── --}}
            <form method="GET" action="{{ route('tarifs_labo.index') }}"
                  class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-4 tw-mb-5
                         tw-flex tw-flex-wrap tw-gap-3 tw-items-end">

                <div class="tw-flex-1 tw-min-w-[180px]">
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Rechercher un test
                    </label>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                           placeholder="Nom du test, section…"
                           class="tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl
                                  tw-bg-slate-50 focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10">
                </div>

                <div class="tw-min-w-[170px]">
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Section
                    </label>
                    <select name="section"
                            class="tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl
                                   tw-bg-slate-50 focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        <option value="">Toutes les sections</option>
                        @foreach($sections as $key => $label)
                        <option value="{{ $key }}" {{ ($section ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                @if(auth()->user()->role_id === 1)
                <div class="tw-min-w-[140px]">
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Afficher
                    </label>
                    <select name="show"
                            class="tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl
                                   tw-bg-slate-50 focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        <option value="actif" {{ ($showOnly ?? 'actif') === 'actif' ? 'selected' : '' }}>Actifs seulement</option>
                        <option value="all"   {{ ($showOnly ?? '') === 'all'   ? 'selected' : '' }}>Tous (actifs + désactivés)</option>
                    </select>
                </div>
                @endif

                <button type="submit"
                        class="tw-px-5 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl
                               hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer">
                    <i class="fas fa-search tw-mr-1.5 tw-text-xs"></i>
                    Filtrer
                </button>

                @if($search || $section)
                <a href="{{ route('tarifs_labo.index') }}"
                   class="tw-px-4 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium
                          tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                    <i class="fas fa-times tw-mr-1 tw-text-xs"></i>
                    Réinitialiser
                </a>
                @endif
            </form>

            {{-- ── Tariff cards per section ─────────────────────────── --}}
            @php
                $sectionColors = [
                    'hematologie'   => ['bg' => 'tw-bg-red-50',    'border' => 'tw-border-red-200',    'head' => 'tw-bg-red-500',    'icon' => 'fa-droplet',      'text' => 'tw-text-red-700'],
                    'hemostase'     => ['bg' => 'tw-bg-orange-50', 'border' => 'tw-border-orange-200', 'head' => 'tw-bg-orange-500', 'icon' => 'fa-tint',         'text' => 'tw-text-orange-700'],
                    'biochimie'     => ['bg' => 'tw-bg-yellow-50', 'border' => 'tw-border-yellow-200', 'head' => 'tw-bg-yellow-500', 'icon' => 'fa-flask',        'text' => 'tw-text-yellow-700'],
                    'hormonologie'  => ['bg' => 'tw-bg-lime-50',   'border' => 'tw-border-lime-200',   'head' => 'tw-bg-lime-600',   'icon' => 'fa-dna',          'text' => 'tw-text-lime-700'],
                    'marqueurs'     => ['bg' => 'tw-bg-teal-50',   'border' => 'tw-border-teal-200',   'head' => 'tw-bg-teal-500',   'icon' => 'fa-microscope',   'text' => 'tw-text-teal-700'],
                    'bacteriologie' => ['bg' => 'tw-bg-cyan-50',   'border' => 'tw-border-cyan-200',   'head' => 'tw-bg-cyan-600',   'icon' => 'fa-bacterium',    'text' => 'tw-text-cyan-700'],
                    'spermiologie'  => ['bg' => 'tw-bg-sky-50',    'border' => 'tw-border-sky-200',    'head' => 'tw-bg-sky-500',    'icon' => 'fa-circle-dot',   'text' => 'tw-text-sky-700'],
                    'urines'        => ['bg' => 'tw-bg-blue-50',   'border' => 'tw-border-blue-200',   'head' => 'tw-bg-blue-500',   'icon' => 'fa-toilet-paper', 'text' => 'tw-text-blue-700'],
                    'serologie'     => ['bg' => 'tw-bg-indigo-50', 'border' => 'tw-border-indigo-200', 'head' => 'tw-bg-indigo-500', 'icon' => 'fa-shield-virus', 'text' => 'tw-text-indigo-700'],
                    'parasitologie' => ['bg' => 'tw-bg-violet-50', 'border' => 'tw-border-violet-200', 'head' => 'tw-bg-violet-500', 'icon' => 'fa-bug',          'text' => 'tw-text-violet-700'],
                ];
            @endphp

            @if($tarifs->isEmpty())
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-px-6 tw-py-14 tw-text-center">
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-tags tw-text-slate-400 tw-text-xl"></i>
                    </div>
                    <p class="tw-text-slate-500 tw-text-sm tw-mb-0">Aucun tarif enregistré.</p>
                    @if(auth()->user()->role_id === 1)
                    <a href="{{ route('tarifs_labo.create') }}" class="tw-text-[#1D4ED8] tw-text-sm tw-no-underline hover:tw-underline">
                        Ajouter le premier tarif →
                    </a>
                    @endif
                </div>
            </div>
            @else

            {{-- Loop through sections that have data --}}
            @foreach($sections as $sKey => $sLabel)
                @if(!$tarifs->has($sKey)) @continue @endif
                @php $cfg = $sectionColors[$sKey] ?? ['bg'=>'tw-bg-slate-50','border'=>'tw-border-slate-200','head'=>'tw-bg-slate-500','icon'=>'fa-vial','text'=>'tw-text-slate-700']; @endphp

                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">

                    {{-- Section header --}}
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between tw-gap-3">
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-shrink-0 {{ $cfg['head'] }}">
                                <i class="fas {{ $cfg['icon'] }} tw-text-white tw-text-sm"></i>
                            </div>
                            <div>
                                <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">{{ $sLabel }}</h2>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">
                                    {{ $tarifs[$sKey]->count() }} test{{ $tarifs[$sKey]->count() > 1 ? 's' : '' }}
                                    · Total section :
                                    {{-- -<strong>{{ number_format($tarifs[$sKey]->sum('prix_unitaire'), 0, ',', ' ') }} FCFA</strong> --}}
                                </p>
                            </div>
                        </div>

                        @if(auth()->user()->role_id === 1)
                        <a href="{{ route('tarifs_labo.create', ['section' => $sKey]) }}"
                           class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-bg-slate-100
                                  tw-text-slate-600 tw-text-xs tw-font-medium tw-rounded-lg hover:tw-bg-slate-200
                                  tw-transition-colors tw-no-underline">
                            <i class="fas fa-plus tw-text-[10px]"></i>
                            Ajouter test
                        </a>
                        @endif
                    </div>

                    {{-- Tests table --}}
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm">
                            <thead class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <tr>
                                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[45%]">
                                        Nom du test / Examen
                                    </th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[30%]">
                                        Description / Note
                                    </th>
                                    <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[12%]">
                                        Prix unitaire
                                    </th>
                                    @if(auth()->user()->role_id === 1)
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[13%]">
                                        Actions
                                    </th>
                                    @else
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-[13%]">
                                        Statut
                                    </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-50">
                                @foreach($tarifs[$sKey] as $tarif)
                                <tr class="hover:tw-bg-slate-50/70 tw-transition-colors {{ !$tarif->actif ? 'tw-opacity-50' : '' }}">

                                    {{-- Test name --}}
                                    <td class="tw-px-6 tw-py-3">
                                        <div class="tw-flex tw-items-center tw-gap-2">
                                            @if(!$tarif->actif)
                                            <span class="tw-inline-flex tw-px-1.5 tw-py-0.5 tw-bg-slate-100 tw-text-slate-400 tw-text-[9px] tw-font-semibold tw-rounded tw-uppercase tw-shrink-0">
                                                Inactif
                                            </span>
                                            @endif
                                            <span class="tw-font-medium tw-text-slate-800">{{ $tarif->nom_test }}</span>
                                        </div>
                                    </td>

                                    {{-- Description --}}
                                    <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-500">
                                        {{ $tarif->description ?? '—' }}
                                    </td>

                                    {{-- Price --}}
                                    <td class="tw-px-4 tw-py-3 tw-text-right">
                                        <span class="tw-font-mono tw-font-bold tw-text-slate-800 tw-text-sm">
                                            {{ number_format($tarif->prix_unitaire, 0, ',', ' ') }}
                                        </span>
                                        <span class="tw-text-xs tw-text-slate-400 tw-ml-1">FCFA</span>
                                    </td>

                                    {{-- Actions (admin) / Status badge (others) --}}
                                    @if(auth()->user()->role_id === 1)
                                    <td class="tw-px-4 tw-py-3">
                                        <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">

                                            {{-- Toggle active --}}
                                            <form method="POST"
                                                  action="{{ route('tarifs_labo.toggle', $tarif->id) }}"
                                                  class="tw-inline">
                                                @csrf
                                                <button type="submit"
                                                        title="{{ $tarif->actif ? 'Désactiver' : 'Réactiver' }}"
                                                        class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg
                                                               {{ $tarif->actif ? 'tw-bg-green-100 tw-text-green-600 hover:tw-bg-green-600' : 'tw-bg-slate-100 tw-text-slate-400 hover:tw-bg-slate-500' }}
                                                               hover:tw-text-white tw-transition-colors tw-border-0 tw-cursor-pointer">
                                                    <i class="fas {{ $tarif->actif ? 'fa-toggle-on' : 'fa-toggle-off' }} tw-text-xs"></i>
                                                </button>
                                            </form>

                                            {{-- Edit --}}
                                            <a href="{{ route('tarifs_labo.edit', $tarif->id) }}"
                                               title="Modifier"
                                               class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg
                                                      tw-bg-[#BFDBFE] tw-text-[#1D4ED8] hover:tw-bg-[#1D4ED8] hover:tw-text-white
                                                      tw-transition-colors tw-no-underline">
                                                <i class="fas fa-pen tw-text-xs"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <form method="POST"
                                                  action="{{ route('tarifs_labo.destroy', $tarif->id) }}"
                                                  onsubmit="return confirm('Supprimer ce tarif ?')"
                                                  class="tw-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        title="Supprimer"
                                                        class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg
                                                               tw-bg-red-50 tw-text-red-400 hover:tw-bg-red-500 hover:tw-text-white
                                                               tw-transition-colors tw-border-0 tw-cursor-pointer">
                                                    <i class="fas fa-trash tw-text-xs"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                    @else
                                    <td class="tw-px-4 tw-py-3 tw-text-center">
                                        @if($tarif->actif)
                                        <span class="tw-inline-flex tw-px-2 tw-py-0.5 tw-bg-green-100 tw-text-green-700 tw-text-[10px] tw-font-semibold tw-rounded-full">
                                            Disponible
                                        </span>
                                        @else
                                        <span class="tw-inline-flex tw-px-2 tw-py-0.5 tw-bg-slate-100 tw-text-slate-500 tw-text-[10px] tw-font-semibold tw-rounded-full">
                                            Non disponible
                                        </span>
                                        @endif
                                    </td>
                                    @endif

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @endforeach

            @endif

        </main>
    </div>
</div>
@stop