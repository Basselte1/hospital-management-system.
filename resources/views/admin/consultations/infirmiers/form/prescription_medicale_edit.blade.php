@extends('layouts.admin')
@section('title', 'CMCU | Modifier prescription médicale')

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
                        <i class="fas fa-pills tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Modifier la prescription</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">{{ $patient->name }} {{ $patient->prenom }}</p>
                    </div>
                </div>
                <a href="{{ route('fiche.prescription_medicale.index', $patient) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour à la liste</span>
                </a>
            </div>

            {{-- ── Validation errors ────────────────────────────── --}}
            @if ($errors->any())
            <div class="tw-mb-4 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-100 tw-px-5 tw-py-4">
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2">
                    <i class="fas fa-exclamation-triangle tw-text-red-500 tw-text-sm"></i>
                    <span class="tw-text-sm tw-font-semibold tw-text-red-700">Erreurs de validation</span>
                </div>
                <ul class="tw-list-disc tw-list-inside tw-space-y-0.5">
                    @foreach ($errors->all() as $error)
                    <li class="tw-text-sm tw-text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- ── Form card ────────────────────────────────────── --}}
            <div class="tw-max-w-3xl tw-mx-auto tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-px-5 tw-py-3 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-pills tw-text-white tw-text-sm"></i>
                    <h3 class="tw-text-xs tw-font-semibold tw-text-white tw-mb-0 tw-uppercase tw-tracking-wide">Informations de la prescription</h3>
                </div>

                <div class="tw-p-6">
                    <form action="{{ route('prescription_medicale.update', $prescription_medicale->id) }}" method="POST"
                          class="tw-space-y-0">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        <input type="hidden" name="fiche_prescription_medicale_id" value="{{ $prescription_medicale->fiche_prescription_medicale_id }}">

                        {{-- Shared fields partial --}}
                        @include('admin.consultations.infirmiers.form._prescription_medicale_fields')

                        <div class="tw-pt-5 tw-mt-5 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                            <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-6 tw-py-3 tw-border-0 tw-transition-colors tw-shadow-sm">
                                <i class="fas fa-save tw-text-xs"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('fiche.prescription_medicale.index', $patient) }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-5 tw-py-3 tw-no-underline tw-transition-colors">
                                <i class="fas fa-times tw-text-xs"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </main>
        @endcan
    </div>
</div>

@endsection