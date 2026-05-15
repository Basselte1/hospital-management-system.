@extends('layouts.admin')
@section('title', 'CMCU | Consultations anesthésiques')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Consultations Anesthésiques</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Historique des consultations pré-anesthésiques</p>
                </div>
                <a href="{{ route('patients.show', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-emerald-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-emerald-600 tw-transition-colors tw-no-underline tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour au dossier patient
                </a>
            </div>

            {{-- Patient Card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-user tw-text-[#1D4ED8] tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Informations Patient</h2>
                </div>
                <div class="tw-px-6 tw-py-4 tw-flex tw-flex-wrap tw-gap-6 tw-text-sm">
                    <div>
                        <span class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-block tw-mb-0.5">Nom & Prénom</span>
                        <span class="tw-font-semibold tw-text-slate-800">{{ $patient->name }} {{ $patient->prenom }}</span>
                    </div>
                    <div>
                        <span class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-block tw-mb-0.5">Téléphone</span>
                        <span class="tw-text-slate-700"><i class="fas fa-phone tw-mr-1 tw-text-slate-400"></i>{{ $patient->telephone }}</span>
                    </div>
                    <div>
                        <span class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-block tw-mb-0.5">Email</span>
                        <span class="tw-text-slate-700"><i class="fas fa-envelope tw-mr-1 tw-text-slate-400"></i>{{ $patient->email }}</span>
                    </div>
                </div>
            </div>

            {{-- Consultations --}}
            @forelse($consultationAnesthesistes as $c)
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-4">

                {{-- Header --}}
                <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-bg-[#BFDBFE]/20">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-stethoscope tw-text-[#1D4ED8] tw-text-sm"></i>
                        </div>
                        <span class="tw-font-semibold tw-text-slate-800">Consultation du {{ $c->created_at->toFormattedDateString() }}</span>
                    </div>
                    @if($c->date_intervention)
                    <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">
                        <i class="fas fa-calendar tw-text-xs"></i> Intervention: {{ $c->date_intervention }}
                    </span>
                    @endif
                </div>

                <div class="tw-p-6">
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-x-8 tw-gap-y-3 tw-text-sm">

                        {{-- Personnel --}}
                        <div class="tw-col-span-full">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-1.5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                                <i class="fas fa-users tw-text-[#1D4ED8]"></i> Personnel médical
                            </p>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Spécialité</span>
                            <span class="tw-text-slate-700">{{ $c->specialite }}</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Médecin Traitant</span>
                            <span class="tw-text-slate-700">{{ $c->medecin_traitant }}</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Opérateur</span>
                            <span class="tw-text-slate-700">{{ $c->operateur }}</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Anesthésiste</span>
                            <span class="tw-text-slate-700">{{ $c->user->name }}</span>
                        </div>

                        {{-- Admission --}}
                        <div class="tw-col-span-full tw-mt-2">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-1.5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                                <i class="fas fa-clipboard tw-text-[#1D4ED8]"></i> Admission & Anesthésie
                            </p>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Motif d'Admission</span>
                            <span class="tw-text-slate-700">{{ $c->motif_admission }}</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Anesthésie en Salle</span>
                            <span class="tw-text-slate-700">{{ $c->anesthesi_salle }}</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Date Hospitalisation</span>
                            <span class="tw-text-slate-700">{{ $c->date_hospitalisation }}</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Classe ASA</span>
                            <span class="tw-inline-flex tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-amber-50 tw-text-amber-700">{{ $c->classe_asa }}</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Bénéfices / Risques</span>
                            <span class="tw-text-slate-700">{{ $c->benefice_risque }}</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Risques</span>
                            <span class="tw-text-slate-700">{{ $c->risque }}</span>
                        </div>
                        @if($c->memo)
                        <div class="tw-col-span-full tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Mémo</span>
                            <span class="tw-text-slate-700">{{ $c->memo }}</span>
                        </div>
                        @endif

                        {{-- Jeûne pré-opératoire --}}
                        <div class="tw-col-span-full">
                            <div class="tw-flex tw-items-start tw-gap-2 tw-rounded-xl tw-bg-slate-50 tw-border tw-border-slate-100 tw-px-4 tw-py-3 tw-text-sm">
                                <i class="fas fa-utensils tw-text-slate-400 tw-shrink-0 tw-mt-0.5"></i>
                                <div>
                                    <p class="tw-font-semibold tw-text-slate-600 tw-mb-1">Jeûne Pré-opératoire</p>
                                    <p class="tw-text-slate-600 tw-mb-0.5"><strong>Solide :</strong> {{ $c->solide }}</p>
                                    <p class="tw-text-slate-600 tw-mb-0"><strong>Liquide :</strong> {{ $c->liquide }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Antécédents --}}
                        <div class="tw-col-span-full tw-mt-2">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-1.5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                                <i class="fas fa-notes-medical tw-text-[#1D4ED8]"></i> Bilan clinique
                            </p>
                        </div>
                        <div class="tw-col-span-full tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Antécédents / Traitement</span>
                            <span class="tw-text-slate-700">{{ $c->antecedent_traitement }}</span>
                        </div>
                        <div class="tw-col-span-full tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Allergies</span>
                            <span class="tw-text-slate-700">{{ $c->allergie }}</span>
                        </div>
                        <div class="tw-col-span-full tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Examens Cliniques</span>
                            <span class="tw-text-slate-700">{{ $c->examen_clinique }}</span>
                        </div>

                        {{-- Intubation data --}}
                        <div class="tw-col-span-full">
                            <div class="tw-rounded-xl tw-bg-slate-50 tw-border tw-border-slate-100 tw-p-4 tw-grid tw-grid-cols-2 md:tw-grid-cols-3 tw-gap-3 tw-text-xs">
                                @foreach([
                                    'Intubation' => $c->intubation,
                                    'Mallampati' => $c->mallampati,
                                    'Distance Interincisive' => $c->distance_interincisive,
                                    'Distance Tyromentonière' => $c->distance_tyromentoniere,
                                    'Mobilité Cervicale' => $c->mobilite_servicale,
                                ] as $label => $val)
                                <div>
                                    <span class="tw-text-slate-400 tw-uppercase tw-tracking-wide tw-block tw-mb-0.5">{{ $label }}</span>
                                    <span class="tw-font-semibold tw-text-slate-700">{{ $val ?? '—' }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Technique & Synthèse --}}
                        <div class="tw-col-span-full tw-mt-2">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-1.5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                                <i class="fas fa-syringe tw-text-[#1D4ED8]"></i> Techniques & Synthèse
                            </p>
                        </div>
                        <div class="tw-col-span-full tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Technique d'Anesthésie</span>
                            <span class="tw-text-slate-700">{{ $c->technique_anesthesie1 }}</span>
                        </div>
                        <div class="tw-col-span-full">
                            <span class="tw-text-slate-500 tw-font-medium tw-block tw-mb-2">Technique Envisagée</span>
                            <div class="tw-flex tw-flex-wrap tw-gap-2">
                                @foreach(explode(",", $c->technique_anesthesie) as $technique)
                                <span class="tw-inline-flex tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">{{ trim($technique) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="tw-col-span-full tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50 tw-mt-2">
                            <span class="tw-text-slate-500 tw-font-medium">Antibioprophylaxie</span>
                            <span class="tw-text-slate-700">{{ $c->antibiotique }}</span>
                        </div>
                        <div class="tw-col-span-full tw-flex tw-justify-between tw-py-1.5 tw-border-b tw-border-slate-50">
                            <span class="tw-text-slate-500 tw-font-medium">Synthèse Préopératoire</span>
                            <span class="tw-text-slate-700">{{ $c->synthese_preop }}</span>
                        </div>
                        <div class="tw-col-span-full">
                            <span class="tw-text-slate-500 tw-font-medium tw-block tw-mb-2">Examens Paracliniques</span>
                            <div class="tw-flex tw-flex-wrap tw-gap-2">
                                @foreach(explode(",", $c->examen_paraclinique) as $examen)
                                <span class="tw-inline-flex tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-slate-100 tw-text-slate-600">{{ trim($examen) }}</span>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @empty
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-16">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-stethoscope tw-text-slate-400 tw-text-xl"></i>
                    </div>
                    <p class="tw-text-slate-400 tw-text-sm tw-font-medium tw-mb-0">Aucune consultation anesthésique enregistrée</p>
                </div>
            </div>
            @endforelse

        </main>
    </div>
</div>
<script src="{{ asset('admin/js/main.js') }}"></script>
@stop