@extends('layouts.admin')
@section('title', 'CMCU | Renseigner un dossier patient')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading with gradient --}}
            <div class="tw-mb-6 tw-rounded-2xl tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-shadow-sm">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-folder-open tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Renseigner un dossier patient</h1>
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

            {{-- Flash messages (optional, can be added) --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i> {{ session('error') }}
            </div>
            @endif

            <div class="tw-max-w-4xl tw-mx-auto tw-space-y-4">

                {{-- Main Form Card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-file-medical tw-text-white tw-text-sm"></i>
                        <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">
                            Dossier de {{ $patient->name }} {{ $patient->prenom }}
                        </h2>
                    </div>

                    <div class="tw-p-6">
                        <form method="POST" action="{{ route('dossiers.store') }}">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                            {{-- Section 1: Informations personnelles --}}
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
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-2">
                                            Sexe <span class="tw-text-red-500">*</span>
                                        </label>
                                        <div class="tw-flex tw-gap-6">
                                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-text-sm tw-text-slate-700">
                                                <input type="radio" name="sexe" value="Masculin"
                                                       {{ old('sexe') === 'Masculin' ? 'checked' : '' }}
                                                       class="tw-accent-[#1D4ED8]">
                                                <i class="fas fa-mars tw-text-[#1D4ED8] tw-text-xs"></i> Masculin
                                            </label>
                                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-text-sm tw-text-slate-700">
                                                <input type="radio" name="sexe" value="Féminin"
                                                       {{ old('sexe') === 'Féminin' ? 'checked' : '' }}
                                                       class="tw-accent-red-500">
                                                <i class="fas fa-venus tw-text-red-400 tw-text-xs"></i> Féminin
                                            </label>
                                        </div>
                                        @error('sexe')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Date de naissance --}}
                                    <div>
                                        <label for="date_naissance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date de naissance</label>
                                        <input type="date" id="date_naissance" name="date_naissance"
                                               value="{{ old('date_naissance') }}"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('date_naissance') tw-border-red-400 @enderror">
                                        @error('date_naissance')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Lieu de naissance --}}
                                    <div>
                                        <label for="lieu_naissance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Lieu de naissance</label>
                                        <input type="text" id="lieu_naissance" name="lieu_naissance"
                                               value="{{ old('lieu_naissance') }}"
                                               placeholder="Ville / Région de naissance"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('lieu_naissance') tw-border-red-400 @enderror">
                                        @error('lieu_naissance')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Profession --}}
                                    <div class="sm:tw-col-span-2">
                                        <label for="profession" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Profession</label>
                                        <input type="text" id="profession" name="profession"
                                               value="{{ old('profession') }}"
                                               placeholder="Profession du patient"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('profession') tw-border-red-400 @enderror">
                                        @error('profession')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Section 2: Coordonnées --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                        <i class="fas fa-phone tw-text-[#14B8A6] tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Coordonnées</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    {{-- Téléphone principal --}}
                                    <div>
                                        <label for="portable_1" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            <i class="fas fa-mobile-alt tw-mr-1 tw-text-slate-400"></i>Téléphone principal
                                        </label>
                                        <input type="text" id="portable_1" name="portable_1"
                                               value="{{ old('portable_1') }}"
                                               placeholder="Ex: 6 XX XX XX XX"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('portable_1') tw-border-red-400 @enderror">
                                        @error('portable_1')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Téléphone secondaire --}}
                                    <div>
                                        <label for="portable_2" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            <i class="fas fa-mobile-alt tw-mr-1 tw-text-slate-400"></i>Téléphone secondaire
                                        </label>
                                        <input type="text" id="portable_2" name="portable_2"
                                               value="{{ old('portable_2') }}"
                                               placeholder="Ex: 6 XX XX XX XX"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('portable_2') tw-border-red-400 @enderror">
                                        @error('portable_2')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label for="email" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            <i class="fas fa-envelope tw-mr-1 tw-text-slate-400"></i>Adresse email
                                        </label>
                                        <input type="email" id="email" name="email"
                                               value="{{ old('email') }}"
                                               placeholder="exemple@email.com"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('email') tw-border-red-400 @enderror">
                                        @error('email')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Fax --}}
                                    <div>
                                        <label for="fax" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            <i class="fas fa-fax tw-mr-1 tw-text-slate-400"></i>Fax
                                        </label>
                                        <input type="text" id="fax" name="fax"
                                               value="{{ old('fax') }}"
                                               placeholder="Numéro de fax"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('fax') tw-border-red-400 @enderror">
                                        @error('fax')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Adresse complète --}}
                                    <div class="sm:tw-col-span-2">
                                        <label for="adresse" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            <i class="fas fa-map-marker-alt tw-mr-1 tw-text-slate-400"></i>Adresse complète
                                        </label>
                                        <input type="text" id="adresse" name="adresse"
                                               value="{{ old('adresse') }}"
                                               placeholder="Quartier, ville"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('adresse') tw-border-red-400 @enderror">
                                        @error('adresse')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Section 3: Personne à contacter --}}
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
                                               value="{{ old('personne_contact') }}"
                                               placeholder="Nom de la personne à contacter"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('personne_contact') tw-border-red-400 @enderror">
                                        @error('personne_contact')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="tel_personne_contact" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Téléphone</label>
                                        <input type="text" id="tel_personne_contact" name="tel_personne_contact"
                                               value="{{ old('tel_personne_contact') }}"
                                               placeholder="Ex: 6 XX XX XX XX"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all @error('tel_personne_contact') tw-border-red-400 @enderror">
                                        @error('tel_personne_contact')
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <p class="tw-text-[11px] tw-text-slate-400 tw-mt-2 tw-flex tw-items-center tw-gap-1">
                                    <i class="fas fa-info-circle"></i> Cette personne sera contactée en cas d'urgence médicale
                                </p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="tw-flex tw-items-center tw-justify-between tw-pt-5 tw-border-t tw-border-slate-100 tw-gap-3">
                                <a href="{{ route('patients.show', $patient->id) }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-5 tw-py-3 tw-no-underline tw-transition-colors">
                                    <i class="fas fa-times tw-text-xs"></i> Annuler
                                </a>
                                <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-600 hover:tw-bg-teal-700 tw-text-white tw-font-semibold tw-text-sm tw-px-6 tw-py-3 tw-border-0 tw-transition-colors tw-shadow-sm">
                                    <i class="fas fa-save tw-text-xs"></i> Enregistrer le dossier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Info tip card --}}
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
                </div>

            </div>
        </main>
    </div>
</div>
@endsection