@extends('layouts.admin')
@section('title', 'CMCU | Historique Laboratoire — ' . $patient->prenom . ' ' . strtoupper($patient->name))

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Breadcrumb --}}
            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="{{ route('laboratoire.index') }}" class="hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">Laboratoire</a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <a href="{{ route('patients.show', $patient->id) }}" class="hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                    {{ $patient->prenom }} {{ strtoupper($patient->name) }}
                </a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">Historique Laboratoire</span>
            </nav>

            {{-- Patient identity card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-5 tw-mb-6 tw-flex tw-items-center tw-gap-4">
                <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                    <span class="tw-text-teal-700 tw-font-bold tw-text-lg">
                        {{ strtoupper(substr($patient->prenom ?? $patient->name, 0, 1)) }}
                    </span>
                </div>
                <div class="tw-flex-1 tw-min-w-0">
                    <h1 class="tw-text-lg tw-font-bold tw-text-slate-800 tw-mb-0">
                        {{ $patient->prenom }} {{ strtoupper($patient->name) }}
                    </h1>
                    <p class="tw-text-xs tw-text-slate-500 tw-mb-0">
                        Dossier n° {{ $patient->numero_dossier ?? '—' }}
                        @if($patient->date_naissance)
                        &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans
                        @endif
                    </p>
                </div>
                @can('laboratoireCreate', \App\Models\Patient::class)
                <a href="{{ route('laboratoire.create', ['patient' => $patient->id]) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-no-underline tw-shadow-sm tw-border-0 tw-shrink-0">
                    <i class="fas fa-plus tw-text-xs"></i>
                    Nouveau bon
                </a>
                @endcan
            </div>

            {{-- Session flash messages --}}
            @if(session('success'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-circle-check tw-text-green-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-green-700 tw-mb-0">{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-circle-exclamation tw-text-red-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-red-600 tw-mb-0">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Timeline of exams --}}
            @if($examens->isEmpty())
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-12 tw-flex tw-flex-col tw-items-center tw-gap-3 tw-text-center">
                <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                    <i class="fas fa-flask tw-text-slate-400 tw-text-lg"></i>
                </div>
                <p class="tw-text-slate-500 tw-text-sm tw-mb-0">Aucun examen de laboratoire enregistré pour ce patient.</p>
                @can('laboratoireCreate', \App\Models\Patient::class)
                <a href="{{ route('laboratoire.create', ['patient' => $patient->id]) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-no-underline tw-mt-1">
                    <i class="fas fa-plus tw-text-xs"></i>
                    Créer le premier bon
                </a>
                @endcan
            </div>
            @else

            {{-- Stats row --}}
            @php
                $statusColors = [
                    'en_attente' => 'tw-bg-yellow-100 tw-text-yellow-700',
                    'en_cours'   => 'tw-bg-blue-100 tw-text-blue-700',
                    'valide'     => 'tw-bg-green-100 tw-text-green-700',
                    'remis'      => 'tw-bg-teal-100 tw-text-teal-700',
                    'archive'    => 'tw-bg-slate-100 tw-text-slate-600',
                ];
                $statusLabels = [
                    'en_attente' => 'En attente',
                    'en_cours'   => 'En cours',
                    'valide'     => 'Validé',
                    'remis'      => 'Résultats remis',
                    'archive'    => 'Archivé',
                ];
                $statusIcons = [
                    'en_attente' => 'fa-clock',
                    'en_cours'   => 'fa-spinner',
                    'valide'     => 'fa-circle-check',
                    'remis'      => 'fa-paper-plane',
                    'archive'    => 'fa-box-archive',
                ];
            @endphp

            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">

                {{-- Table header --}}
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between tw-gap-3 tw-flex-wrap">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-flask tw-text-indigo-600 tw-text-sm"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Historique des examens</h2>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0">{{ $examens->total() }} examen(s) au total</p>
                        </div>
                    </div>
                </div>

                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-5 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-whitespace-nowrap">
                                    N° Bon
                                </th>
                                <th class="tw-px-5 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-whitespace-nowrap">
                                    Statut
                                </th>
                                <th class="tw-px-5 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-whitespace-nowrap">
                                    Laborantin
                                </th>
                                <th class="tw-px-5 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-whitespace-nowrap">
                                    Prélèvement
                                </th>
                                <th class="tw-px-5 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-whitespace-nowrap">
                                    Validation
                                </th>
                                <th class="tw-px-5 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-whitespace-nowrap">
                                    Créé le
                                </th>
                                <th class="tw-px-5 tw-py-3 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($examens as $examen)
                            @php
                                $color = $statusColors[$examen->statut] ?? 'tw-bg-slate-100 tw-text-slate-600';
                                $label = $statusLabels[$examen->statut] ?? ucfirst($examen->statut);
                                $icon  = $statusIcons[$examen->statut] ?? 'fa-circle';
                            @endphp
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">

                                {{-- Bon number --}}
                                <td class="tw-px-5 tw-py-4">
                                    <span class="tw-font-mono tw-text-xs tw-font-semibold tw-text-slate-700 tw-bg-slate-100 tw-px-2.5 tw-py-1 tw-rounded-lg">
                                        {{ $examen->numero_bon ?? '—' }}
                                    </span>
                                </td>

                                {{-- Status badge --}}
                                <td class="tw-px-5 tw-py-4">
                                    <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-2.5 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium {{ $color }}">
                                        <i class="fas {{ $icon }} tw-text-[10px]"></i>
                                        {{ $label }}
                                    </span>
                                </td>

                                {{-- Laborantin --}}
                                <td class="tw-px-5 tw-py-4 tw-text-xs tw-text-slate-600">
                                    @if($examen->laborantin)
                                        {{ $examen->laborantin->prenom }} {{ $examen->laborantin->name }}
                                    @else
                                        <span class="tw-text-slate-300">—</span>
                                    @endif
                                </td>

                                {{-- Date prelevement --}}
                                <td class="tw-px-5 tw-py-4 tw-text-xs tw-text-slate-500 tw-whitespace-nowrap">
                                    {{ $examen->date_prelevement ? $examen->date_prelevement->format('d/m/Y H:i') : '—' }}
                                </td>

                                {{-- Date validation --}}
                                <td class="tw-px-5 tw-py-4 tw-text-xs tw-whitespace-nowrap">
                                    @if($examen->date_validation)
                                        <span class="tw-text-green-600 tw-font-medium">
                                            {{ $examen->date_validation->format('d/m/Y H:i') }}
                                        </span>
                                    @else
                                        <span class="tw-text-slate-300">—</span>
                                    @endif
                                </td>

                                {{-- Created at --}}
                                <td class="tw-px-5 tw-py-4 tw-text-xs tw-text-slate-400 tw-whitespace-nowrap">
                                    {{ $examen->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="tw-px-5 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-justify-end tw-gap-1.5">

                                        {{-- View --}}
                                        <a href="{{ route('laboratoire.show', $examen->id) }}"
                                           title="Voir le détail"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-indigo-50 hover:tw-bg-indigo-100 tw-text-indigo-600 tw-no-underline tw-transition-colors">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>

                                        {{-- Print bon receipt (Secrétaire / Admin) --}}
                                        @can('laboratoireCreate', \App\Models\Patient::class)
                                        <a href="{{ route('laboratoire.bon', $examen->id) }}"
                                           title="Imprimer le bon"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-no-underline tw-transition-colors">
                                            <i class="fas fa-receipt tw-text-xs"></i>
                                        </a>
                                        @endcan

                                        {{-- Export report (validated only) --}}
                                        @if(in_array($examen->statut, ['valide', 'remis']))
                                        <a href="{{ route('laboratoire.rapport', $examen->id) }}"
                                           title="Rapport PDF"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-emerald-50 hover:tw-bg-emerald-100 tw-text-emerald-600 tw-no-underline tw-transition-colors">
                                            <i class="fas fa-file-pdf tw-text-xs"></i>
                                        </a>
                                        @endif

                                        {{-- Edit / enter results (laborantin + admin only) --}}
                                        @can('laboratoireWrite', \App\Models\Patient::class)
                                        @if(in_array($examen->statut, ['en_attente', 'en_cours']))
                                        <a href="{{ route('laboratoire.edit', $examen->id) }}"
                                           title="Saisir les résultats"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-600 tw-no-underline tw-transition-colors">
                                            <i class="fas fa-pen-to-square tw-text-xs"></i>
                                        </a>
                                        @endif
                                        @endcan

                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($examens->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $examens->links() }}
                </div>
                @endif

            </div>
            @endif

            {{-- Back link --}}
            <div class="tw-pb-8">
                <a href="{{ route('patients.show', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    Retour au dossier patient
                </a>
            </div>

        </main>
    </div>
</div>
@stop