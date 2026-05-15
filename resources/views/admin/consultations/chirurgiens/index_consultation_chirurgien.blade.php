@extends('layouts.admin')
@section('title', 'CMCU | Consultations chirurgien')
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
                        <i class="fas fa-notes-medical tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Consultations du patient</h1>
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

                {{-- Patient info card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-5 tw-py-3 tw-border-b tw-border-slate-100 tw-bg-slate-50 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-user-circle tw-text-[#1D4ED8] tw-text-sm"></i>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-0 tw-uppercase tw-tracking-wide">Informations patient</h3>
                    </div>
                    <div class="tw-divide-y tw-divide-slate-50">
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3">
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-32 tw-shrink-0">Nom et prénom</span>
                            <span class="tw-text-sm tw-font-semibold tw-text-slate-700">{{ $patient->name }} {{ $patient->prenom }}</span>
                        </div>
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3">
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-32 tw-shrink-0"><i class="fas fa-phone tw-mr-1"></i>Téléphone</span>
                            <span class="tw-text-sm tw-text-slate-600">{{ $patient->telephone ?? '—' }}</span>
                        </div>
                        <div class="tw-px-5 tw-py-2.5 tw-flex tw-items-center tw-gap-3">
                            <span class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-32 tw-shrink-0"><i class="fas fa-envelope tw-mr-1"></i>E-Mail</span>
                            <span class="tw-text-sm tw-text-slate-600">{{ $patient->email ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Consultation records --}}
                @foreach($consultations as $consultation)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-5 tw-py-3 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                        <span class="tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Consultation du</span>
                        <span class="tw-text-xs tw-font-bold tw-text-white">{{ $consultation->created_at->toFormattedDateString() }}</span>
                    </div>
                    <div class="tw-divide-y tw-divide-slate-50">
                        @foreach([
                            ['MÉDECIN DE RÉFÉRENCE', 'Dr. ' . $consultation->medecin_r],
                            ['NOM DU MÉDECIN', 'Dr. ' . $consultation->user->name],
                            ['MOTIF DE CONSULTATION', $consultation->motif_c],
                            ['INTERROGATOIRE', $consultation->interrogatoire],
                            ['EXAMENS PHYSIQUES', $consultation->examen_p],
                            ['EXAMENS COMPLÉMENTAIRES', $consultation->examen_c],
                            ['PROPOSITIONS THÉRAPEUTIQUES', $consultation->proposition_therapeutique],
                            ['PROPOSITIONS DE SUIVI', $consultation->proposition],
                        ] as [$label, $value])
                        @if(!empty($value))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">{{ $label }}</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1">{{ $value }}</span>
                        </div>
                        @endif
                        @endforeach

                        @if(!empty($consultation->type_intervention))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">TYPE D'INTERVENTION</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1">{{ $consultation->type_intervention }}</span>
                        </div>
                        @endif
                        @if(!empty($consultation->date_intervention))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">DATE INTERVENTION</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1">{{ $consultation->date_intervention }}</span>
                        </div>
                        @endif
                        @if(!empty($consultation->acte))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">ACTES À RÉALISER</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1">{{ $consultation->acte }}</span>
                        </div>
                        @endif
                        @if(!empty($consultation->date_consultation_anesthesiste))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">DATE CONSULT. ANESTHÉSISTE</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1">{{ $consultation->date_consultation_anesthesiste }}</span>
                        </div>
                        @endif
                        @if(!empty($consultation->date_consultation))
                        <div class="tw-px-5 tw-py-3 tw-flex tw-gap-4">
                            <span class="tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-44 tw-shrink-0 tw-pt-0.5">DATE PROCHAINE CONSULT.</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-flex-1">{{ $consultation->date_consultation }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeCheckboxStates();
    });
</script>
@endpush
@stop