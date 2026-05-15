@extends('layouts.admin')
@section('title', 'CMCU | Liste des examens')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            @can('create', \App\Models\Patient::class)

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Examens</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Liste des examens par patient</p>
                </div>
                <a href="{{ route('patients.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-teal-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-lg hover:tw-bg-teal-600 tw-transition-colors tw-duration-150 tw-no-underline tw-shadow-sm">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    Retour à la liste des patients
                </a>
            </div>

            {{-- Table card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-x-ray tw-text-indigo-500 tw-text-xs"></i>
                    </div>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700">Liste des examens</h2>
                </div>

                <div class="tw-overflow-x-auto">
                    <table id="myTable" class="tw-w-full tw-text-sm tw-text-left">
                        <thead class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                            <tr>
                                @can('print', \App\Models\Patient::class)
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Nom du patient</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Type d'examen</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-text-center tw-w-28">Consulter</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-text-center tw-w-28">Ajouter</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($patients as $patient)
                                @foreach($patient->examens as $examen)
                                <tr class="hover:tw-bg-slate-50 tw-transition-colors tw-duration-100">
                                    <td class="tw-px-6 tw-py-4">
                                        <div class="tw-flex tw-items-center tw-gap-2.5">
                                            <div class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                                <i class="fas fa-user-injured tw-text-teal-500 tw-text-[10px]"></i>
                                            </div>
                                            <span class="tw-font-medium tw-text-slate-800">{{ $patient->name }}</span>
                                        </div>
                                    </td>
                                    <td class="tw-px-6 tw-py-4">
                                        <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-2.5 tw-py-0.5 tw-rounded-full tw-bg-indigo-50 tw-text-indigo-700 tw-text-xs tw-font-medium">
                                            <i class="fas fa-x-ray tw-text-[10px]"></i>
                                            {{ $examen->type }}
                                        </span>
                                    </td>
                                    @can('consulter', \App\Models\Patient::class)
                                    <td class="tw-px-6 tw-py-4 tw-text-center">
                                        <a href="{{ route('examens.show', $examen->id) }}"
                                           title="Consulter cet examen"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-text-[#1D4ED8] hover:tw-bg-[#1D4ED8] hover:tw-text-white tw-transition-colors tw-duration-150 tw-no-underline">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-text-center">
                                        <a href="{{ route('examens.create', $patient->id) }}"
                                           title="Ajouter un examen"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-teal-100 tw-text-teal-600 hover:tw-bg-teal-500 hover:tw-text-white tw-transition-colors tw-duration-150 tw-no-underline">
                                            <i class="far fa-calendar-plus tw-text-xs"></i>
                                        </a>
                                    </td>
                                    @endcan
                                </tr>
                                @endforeach
                            @endforeach

                            @if($patients->isEmpty() || $patients->every(fn($p) => $p->examens->isEmpty()))
                            <tr>
                                <td colspan="4" class="tw-px-6 tw-py-12 tw-text-center">
                                    <div class="tw-flex tw-flex-col tw-items-center tw-gap-3">
                                        <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fas fa-x-ray tw-text-slate-400 tw-text-xl"></i>
                                        </div>
                                        <p class="tw-text-slate-500 tw-text-sm tw-mb-0">Aucun examen enregistré</p>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if($examens->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $examens->links("tailwind.blade.php") }}
                </div>
                @endif
            </div>

            @endcan

        </main>
    </div>
</div>
@stop