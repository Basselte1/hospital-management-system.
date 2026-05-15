@extends('layouts.admin')
@section('title', 'CMCU | Renseignement du dossier patient')
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
                        <i class="fas fa-file-medical-alt tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Compte-rendu opératoire</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">{{ $patient->name }} {{ $patient->prenom }}</p>
                    </div>
                </div>
                <a href="{{ route('patients.show', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour au dossier patient</span>
                </a>
            </div>

            <div class="tw-max-w-4xl tw-mx-auto">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-bg-slate-50 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-edit tw-text-amber-500 tw-text-xs"></i>
                        </div>
                        <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Modifier le compte-rendu opératoire</h2>
                    </div>
                    <div class="tw-p-4 tw-bg-amber-50 tw-border-b tw-border-amber-100 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-exclamation-triangle tw-text-amber-500 tw-text-xs tw-shrink-0"></i>
                        <p class="tw-text-xs tw-text-amber-700 tw-font-semibold tw-mb-0">Espace réservé au médecin</p>
                    </div>
                    <div class="tw-p-6">
                        @include('admin.consultations.chirurgiens.form.compte_rendu_operatoire_form')
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@stop