@extends('layouts.admin')
@section('title', 'CMCU | Modifier le dossier du patient')

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
                        <i class="fas fa-user-edit tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Modifier le dossier</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">
                            Patient : <span class="tw-font-semibold tw-text-white tw-underline tw-underline-offset-2">{{ $patient->name }} {{ $patient->prenom }}</span>
                        </p>
                    </div>
                </div>
                <a href="{{ route('patients.show', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour au dossier</span>
                </a>
            </div>

            <div class="tw-max-w-4xl tw-mx-auto tw-space-y-4">

                {{-- ── Main Form Card ──────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-file-medical tw-text-white tw-text-sm"></i>
                        <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">
                            Dossier : {{ $patient->name }} {{ $patient->prenom }}
                        </h2>
                    </div>

                    <div class="tw-p-6">
                        <form method="POST" action="{{ route('dossiers.update', $dossier->id) }}">
                            {{ method_field('PATCH') }}
                            @csrf
                            <input type="hidden" value="{{ $dossier->patient_id }}" name="patient_id">

                            {{-- ── Section 1: Informations personnelles ──────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                        <i class="fas fa-user tw-text-[#1D4ED8] tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations personnelles</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    {{-- Sexe --}}
                                    <div class="sm:tw-col-span-2">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-2">Sexe</label>
                                        <div class="tw-flex tw-gap-6">
                                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-text-sm tw-text-slate-700">
                                                <input type="radio" name="sexe" id="sexe_m" value="Masculin"
                                                       {{ old('sexe', $dossier->sexe) === 'Masculin' ? 'checked' : '' }}
                                                       class="tw-accent-[#1D4ED8]">
                                                <i class="fas fa-mars tw-text-[#1D4ED8] tw-text-xs"></i> Masculin
                                            </label>
                                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-text-sm tw-text-slate-700">
                                                <input type="radio" name="sexe" id="sexe_f" value="Féminin"
                                                       {{ old('sexe', $dossier->sexe) === 'Féminin' ? 'checked' : '' }}
                                                       class="tw-accent-red-500">
                                                <i class="fas fa-venus tw-text-red-400 tw-text-xs"></i> Féminin
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Date de naissance --}}
                                    <div>
                                        <label for="date_naissance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date de naissance</label>
                                        <input type="date" id="date_naissance" name="date_naissance"
                                               value="{{ old('date_naissance', $dossier->date_naissance) }}"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>

                                    {{-- Lieu de naissance --}}
                                    <div>
                                        <label for="lieu_naissance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Lieu de naissance</label>
                                        <input type="text" id="lieu_naissance" name="lieu_naissance"
                                               value="{{ old('lieu_naissance', $dossier->lieu_naissance) }}"
                                               placeholder="Ville / Région de naissance"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>

                                    {{-- Profession --}}
                                    <div class="sm:tw-col-span-2">
                                        <label for="profession" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Profession</label>
                                        <input type="text" id="profession" name="profession"
                                               value="{{ old('profession', $dossier->profession) }}"
                                               placeholder="Profession du patient"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 2: Coordonnées ─────────────────────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                        <i class="fas fa-phone tw-text-[#14B8A6] tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Coordonnées</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label for="portable_1" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            <i class="fas fa-mobile-alt tw-mr-1 tw-text-slate-400"></i>Téléphone principal
                                        </label>
                                        <input type="text" id="portable_1" name="portable_1"
                                               value="{{ old('portable_1', $dossier->portable_1) }}"
                                               placeholder="Ex: 6 XX XX XX XX"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>
                                    <div>
                                        <label for="portable_2" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            <i class="fas fa-mobile-alt tw-mr-1 tw-text-slate-400"></i>Téléphone secondaire
                                        </label>
                                        <input type="text" id="portable_2" name="portable_2"
                                               value="{{ old('portable_2', $dossier->portable_2) }}"
                                               placeholder="Ex: 6 XX XX XX XX"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>
                                    <div class="sm:tw-col-span-2">
                                        <label for="adresse" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            <i class="fas fa-map-marker-alt tw-mr-1 tw-text-slate-400"></i>Adresse complète
                                        </label>
                                        <input type="text" id="adresse" name="adresse"
                                               value="{{ old('adresse', $dossier->adresse) }}"
                                               placeholder="Quartier, ville"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 3: Personne à contacter ───────────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-red-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                        <i class="fas fa-user-friends tw-text-red-500 tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Personne à contacter en cas d'urgence</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label for="personne_contact" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Nom complet</label>
                                        <input type="text" id="personne_contact" name="personne_contact"
                                               value="{{ old('personne_contact', $dossier->personne_contact) }}"
                                               placeholder="Nom de la personne à contacter"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>
                                    <div>
                                        <label for="tel_personne_contact" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Téléphone</label>
                                        <input type="text" id="tel_personne_contact" name="tel_personne_contact"
                                               value="{{ old('tel_personne_contact', $dossier->tel_personne_contact) }}"
                                               placeholder="Ex: 6 XX XX XX XX"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>
                                </div>
                                <p class="tw-text-[11px] tw-text-slate-400 tw-mt-2 tw-flex tw-items-center tw-gap-1">
                                    <i class="fas fa-info-circle"></i> Cette personne sera contactée en cas d'urgence médicale
                                </p>
                            </div>

                            {{-- ── Action Buttons ───────────────────────────── --}}
                            <div class="tw-flex tw-items-center tw-justify-between tw-pt-5 tw-border-t tw-border-slate-100 tw-gap-3">
                                <a href="{{ route('patients.show', $patient->id) }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-5 tw-py-3 tw-no-underline tw-transition-colors">
                                    <i class="fas fa-times tw-text-xs"></i> Annuler
                                </a>
                                <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-600 hover:tw-bg-teal-700 tw-text-white tw-font-semibold tw-text-sm tw-px-6 tw-py-3 tw-border-0 tw-transition-colors tw-shadow-sm">
                                    <i class="fas fa-check tw-text-xs"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── Info tip card ────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-5 tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-3">
                    <div class="tw-flex tw-items-start tw-gap-3">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0 tw-mt-0.5">
                            <i class="fas fa-lightbulb tw-text-amber-500 tw-text-xs"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-700 tw-mb-0.5">Astuce</p>
                            <p class="tw-text-xs tw-text-slate-500 tw-mb-0">Assurez-vous que toutes les informations sont correctes avant d'enregistrer. Les coordonnées de la personne à contacter sont importantes en cas d'urgence.</p>
                        </div>
                    </div>
                    <p class="tw-text-[11px] tw-text-slate-400 tw-shrink-0 tw-flex tw-items-center tw-gap-1">
                        <i class="fas fa-calendar-alt"></i>
                        Dernière modification : {{ $dossier->updated_at ? $dossier->updated_at->format('d/m/Y à H:i') : 'N/A' }}
                    </p>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection