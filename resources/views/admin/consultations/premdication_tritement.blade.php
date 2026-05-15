@extends('layouts.admin')
@section('title', 'CMCU | Prémédication / Traitement')
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
                        <i class="fas fa-pills tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Prémédication / Traitement</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">{{ $patient->name }} {{ $patient->prenom }}</p>
                    </div>
                </div>
                <div class="tw-flex tw-items-center tw-gap-2">
                    @can('infirmier', \App\Models\Patient::class)
                    <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-border tw-border-white/30 tw-cursor-pointer tw-transition-colors"
                            data-bs-toggle="modal" data-bs-target="#DetailPremedication" title="Détails prémédication / préparation">
                        <i class="fas fa-eye tw-text-xs"></i> Consignes IDE / Préparations
                    </button>
                    @endcan
                    @can('anesthesiste', \App\Models\Patient::class)
                    <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-500/80 hover:tw-bg-teal-500 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-border-0 tw-cursor-pointer tw-transition-colors"
                            data-bs-toggle="modal" data-bs-target="#DetailPremedication" title="Détails prémédication / préparation">
                        <i class="fas fa-eye tw-text-xs"></i> Détails
                    </button>
                    @endcan
                    <a href="{{ route('patients.show', $patient->id) }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                        <i class="fas fa-arrow-left tw-text-xs"></i>
                        <span class="tw-hidden sm:tw-inline">Retour au dossier</span>
                    </a>
                </div>
            </div>

            {{-- ── PRÉMEDICATION (anesthésiste only) ──────────────── --}}
            @can('anesthesiste', \App\Models\Patient::class)
            <div class="tw-mb-6">
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-3">
                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-syringe tw-text-[#1D4ED8] tw-text-xs"></i>
                    </div>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Prémédication</h2>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <form method="post" action="{{ route('premedication_consigne_preparation.store') }}">
                        @csrf
                        <div class="tw-overflow-x-auto">
                            <table class="tw-w-full tw-text-sm">
                                <thead>
                                    <tr class="tw-bg-[#1D4ED8]">
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Médicament</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Consigne IDE</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Préparation</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tw-border-b tw-border-slate-100">
                                        <td class="tw-px-4 tw-py-3">
                                            <input type="search" name="medicament" id="search" autocomplete="off" required
                                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all typeahead tt-query">
                                        </td>
                                        <td class="tw-px-4 tw-py-3">
                                            <input type="text" name="consigne_ide" value="{{ old('consigne_ide') }}" required
                                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                        </td>
                                        <td class="tw-px-4 tw-py-3">
                                            <input type="text" name="preparation" value="{{ old('premedication') }}" required
                                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-center">
                                            <button type="submit"
                                                    class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-xs tw-px-4 tw-py-2 tw-border-0 tw-transition-colors">
                                                <i class="fas fa-save"></i> Enregistrer
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <input name="patient_id" value="{{ $patient->id }}" type="hidden">
                    </form>
                </div>
            </div>
            @endcan

            {{-- ── TRAITEMENT À L'HOSPITALISATION ──────────────────── --}}
            <div class="tw-mb-6">
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-3">
                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-hospital tw-text-[#14B8A6] tw-text-xs"></i>
                    </div>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Traitement à l'hospitalisation</h2>
                </div>

                @can('med_inf_anes', \App\Models\Patient::class)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-3">
                    <form method="post" id="dynamic_form" action="{{ route('traitement_hospitalisation.store') }}">
                        @csrf
                        <span id="result"></span>
                        <div class="tw-overflow-x-auto">
                            <table class="tw-w-full tw-text-sm">
                                <thead>
                                    <tr class="tw-bg-[#14B8A6]">
                                        <th class="tw-px-3 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white">Médicament, dosage, posologie</th>
                                        @foreach(['Durée (j)', 'J(-1)', 'J(0)', 'J(1)', 'J(2)', 'M', 'MI', 'S', 'N', 'M+1', 'MI+1', 'S+1', 'N+1', 'Date / Heure'] as $h)
                                        <th class="tw-px-2 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-whitespace-nowrap">{{ $h }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tw-border-b tw-border-slate-100">
                                        <td class="tw-px-3 tw-py-3">
                                            <textarea name="medicament_posologie_dosage" cols="30" rows="2" readonly
                                                      class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1.5 tw-text-xs tw-text-slate-700 tw-resize-none focus:tw-outline-none">@if(!empty($medicament)){{ $medicament->medicament }}@endif</textarea>
                                            <p class="tw-text-[10px] tw-text-slate-400 tw-mt-1 tw-mb-0">Pré-rempli avec le dernier médicament</p>
                                        </td>
                                        <td class="tw-px-2 tw-py-3 tw-text-center"><input type="number" name="duree" min="0" class="tw-w-14 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1 tw-text-xs tw-text-center focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"></td>
                                        @foreach(['j', 'j0', 'j1', 'j2', 'm', 'mi', 's', 'n', 'm1', 'mi1', 's1', 'n1'] as $day)
                                        <td class="tw-px-2 tw-py-3 tw-text-center"><input type="checkbox" value="Ok" name="{{ $day }}" class="tw-accent-[#14B8A6]"></td>
                                        @endforeach
                                        <td class="tw-px-2 tw-py-3">
                                            <input type="datetime-local" name="date" required
                                                   value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}"
                                                   class="tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1 tw-text-xs focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tw-flex tw-justify-end tw-p-3 tw-border-t tw-border-slate-100">
                            <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-semibold tw-text-xs tw-px-4 tw-py-2 tw-border-0 tw-transition-colors">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                        <input name="patient_id" value="{{ $patient->id }}" type="hidden">
                    </form>
                </div>
                @endcan

                {{-- Traitement list --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-xs">
                            <thead>
                                <tr class="tw-bg-slate-100">
                                    <th class="tw-px-3 tw-py-2.5 tw-text-left tw-text-[11px] tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide">Médicament, dosage, posologie</th>
                                    @foreach(['Durée', 'J(-1)', 'J(0)', 'J(1)', 'J(2)', 'M', 'MI', 'S', 'N', 'M+1', 'MI+1', 'S+1', 'N+1', 'Date', 'IDE'] as $h)
                                    <th class="tw-px-2 tw-py-2.5 tw-text-center tw-text-[11px] tw-font-semibold tw-text-slate-600 tw-uppercase tw-whitespace-nowrap">{{ $h }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-50">
                                @foreach($TraitementHospitalisations as $t)
                                <tr class="hover:tw-bg-slate-50">
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-700">{{ $t->medicament_posologie_dosage }}</td>
                                    <td class="tw-px-2 tw-py-2.5 tw-text-center tw-text-slate-600">{{ $t->duree }} j</td>
                                    @foreach(['j', 'j0', 'j1', 'j2', 'm', 'mi', 's', 'n', 'm1', 'mi1', 's1', 'n1'] as $day)
                                    <td class="tw-px-2 tw-py-2.5 tw-text-center">
                                        @if($t->$day)
                                        <i class="fas fa-check tw-text-[#14B8A6] tw-text-[10px]"></i>
                                        @else
                                        <span class="tw-text-slate-300">—</span>
                                        @endif
                                    </td>
                                    @endforeach
                                    <td class="tw-px-2 tw-py-2.5 tw-text-center tw-text-slate-500 tw-whitespace-nowrap">{{ $t->date }}</td>
                                    <td class="tw-px-2 tw-py-2.5 tw-text-slate-600 tw-whitespace-nowrap">{{ $t->user->name }} {{ $t->user->prenom }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── ADAPTATION DU TRAITEMENT PERSONNEL ──────────────── --}}
            <div class="tw-mb-6">
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-3">
                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-exchange-alt tw-text-indigo-500 tw-text-xs"></i>
                    </div>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Adaptation du traitement personnel</h2>
                </div>

                @can('med_inf_anes', \App\Models\Patient::class)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-3">
                    <form method="post" action="{{ route('adaptation_traitement.store') }}">
                        @csrf
                        <div class="tw-overflow-x-auto">
                            <table class="tw-w-full tw-text-sm">
                                <thead>
                                    <tr class="tw-bg-indigo-600">
                                        <th class="tw-px-3 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white">Médicament, dosage, posologie</th>
                                        @foreach(['Arrêt', 'Poursuivre jusqu\'à la veille', 'Continuer le matin', 'J(-1)', 'J(0)', 'J(1)', 'J(2)', 'M', 'MI', 'S', 'N', 'M+1', 'MI+1', 'S+1', 'N+1', 'Date / Heure'] as $h)
                                        <th class="tw-px-2 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-whitespace-nowrap">{{ $h }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tw-border-b tw-border-slate-100">
                                        <td class="tw-px-3 tw-py-3">
                                            <textarea name="medicament_posologie_dosage" cols="30" rows="2" required
                                                      class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1.5 tw-text-xs tw-text-slate-700 tw-resize-none focus:tw-outline-none">{{ old('medicament_posologie_dosage') }}</textarea>
                                        </td>
                                        @foreach([['arret', 'Oui'], ['poursuivre', 'Oui'], ['continuer', 'Oui']] as [$name, $val])
                                        <td class="tw-px-2 tw-py-3 tw-text-center"><input type="checkbox" value="{{ $val }}" name="{{ $name }}" class="tw-accent-indigo-500"></td>
                                        @endforeach
                                        @foreach(['j', 'j0', 'j1', 'j2', 'm', 'mi', 's', 'n', 'm1', 'mi1', 's1', 'n1'] as $day)
                                        <td class="tw-px-2 tw-py-3 tw-text-center"><input type="checkbox" value="Ok" name="{{ $day }}" class="tw-accent-indigo-500"></td>
                                        @endforeach
                                        <td class="tw-px-2 tw-py-3">
                                            <input type="datetime-local" name="date" required
                                                   value="{{ \Carbon\Carbon::now()->toDateString() }}"
                                                   class="tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1 tw-text-xs focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-200">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tw-flex tw-justify-end tw-p-3 tw-border-t tw-border-slate-100">
                            <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-indigo-600 hover:tw-bg-indigo-700 tw-text-white tw-font-semibold tw-text-xs tw-px-4 tw-py-2 tw-border-0 tw-transition-colors">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                        <input name="patient_id" value="{{ $patient->id }}" type="hidden">
                    </form>
                </div>
                @endcan

                {{-- Adaptation list --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-xs">
                            <thead>
                                <tr class="tw-bg-slate-100">
                                    <th class="tw-px-3 tw-py-2.5 tw-text-left tw-text-[11px] tw-font-semibold tw-text-slate-600 tw-uppercase">Médicament, dosage, posologie</th>
                                    @foreach(['Arrêt', 'Poursuivre', 'Continuer', 'J(-1)', 'J(0)', 'J(1)', 'J(2)', 'M', 'MI', 'S', 'N', 'M+1', 'MI+1', 'S+1', 'N+1', 'Date', 'IDE'] as $h)
                                    <th class="tw-px-2 tw-py-2.5 tw-text-center tw-text-[11px] tw-font-semibold tw-text-slate-600 tw-uppercase tw-whitespace-nowrap">{{ $h }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-50">
                                @foreach($AdaptationTraitements as $a)
                                <tr class="hover:tw-bg-slate-50">
                                    <td class="tw-px-3 tw-py-2.5 tw-text-slate-700">{{ $a->medicament_posologie_dosage }}</td>
                                    @foreach(['arret', 'poursuivre', 'continuer'] as $field)
                                    <td class="tw-px-2 tw-py-2.5 tw-text-center">
                                        @if($a->$field)
                                        <i class="fas fa-check tw-text-indigo-500 tw-text-[10px]"></i>
                                        @else
                                        <span class="tw-text-slate-300">—</span>
                                        @endif
                                    </td>
                                    @endforeach
                                    @foreach(['j', 'j0', 'j1', 'j2', 'm', 'mi', 's', 'n', 'm1', 'mi1', 's1', 'n1'] as $day)
                                    <td class="tw-px-2 tw-py-2.5 tw-text-center">
                                        @if($a->$day)
                                        <i class="fas fa-check tw-text-indigo-500 tw-text-[10px]"></i>
                                        @else
                                        <span class="tw-text-slate-300">—</span>
                                        @endif
                                    </td>
                                    @endforeach
                                    <td class="tw-px-2 tw-py-2.5 tw-text-center tw-text-slate-500 tw-whitespace-nowrap">{{ $a->date }}</td>
                                    <td class="tw-px-2 tw-py-2.5 tw-text-slate-600 tw-whitespace-nowrap">{{ $a->user->name }} {{ $a->user->prenom }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @include('admin.modal.detail_premedication_preparation')
            @endcan

        </main>
    </div>
</div>
@stop