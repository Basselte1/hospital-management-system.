@extends('layouts.admin')

@section('title', 'CMCU | Ajouter un dossier patient')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')


        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

             {{-- ── Modal Examen ──────────────────────────────────── --}}
                    {{--
                        IMPORTANT : Ce modal remplace le <select multiple> commenté.
                        Les checkboxes n'ont PAS de name= car elles ne sont pas soumises
                        directement. C'est le champ hidden "details_motif" qui reçoit
                        la liste des examens cochés (via JS syncDetailsMotif).
                    --}}
                 <div id="examModal"
     class="tw-fixed tw-inset-0 tw-bg-black tw-bg-opacity-50 tw-hidden tw-flex tw-items-center tw-justify-center tw-z-50">
    <div class="tw-bg-white tw-rounded-lg tw-shadow-xl tw-w-[900px] tw-max-w-full tw-max-h-[80vh] tw-overflow-y-auto">

        <!-- Header -->
        <div class="tw-flex tw-justify-between tw-items-center tw-border-b tw-px-6 tw-py-3">
            <h2 class="tw-text-lg tw-font-bold tw-text-gray-800">Sélectionnez les examens</h2>
            <button id="closeModalBtn"
                    class="tw-text-gray-500 hover:tw-text-gray-700 tw-transition">
                ✕
            </button>
        </div>

        <!-- Contenu en grille -->
        <div class="tw-px-6 tw-py-4 tw-grid tw-grid-cols-2 tw-gap-4">

            <!-- Hématologie -->
            <fieldset class="tw-bg-red-50 tw-p-3 tw-rounded-md tw-flex tw-flex-col tw-space-y-2">
                <legend class="tw-font-semibold tw-text-red-700">Hématologie</legend>
                <label><input type="checkbox" value="NFS"> NFS</label>
                <label><input type="checkbox" value="HbA1C"> HbA1C</label>
                <label><input type="checkbox" value="Electrophorese"> Électrophorèse</label>
                <label><input type="checkbox" value="D-Dimeres"> D-Dimères</label>
                <label><input type="checkbox" value="TP"> TP</label>
                <label><input type="checkbox" value="TP/TCK"> TP/TCK</label>
            </fieldset>

            <!-- Biochimie -->
            <fieldset class="tw-bg-green-50 tw-p-3 tw-rounded-md tw-flex tw-flex-col tw-space-y-2">
                <legend class="tw-font-semibold tw-text-green-700">Biochimie</legend>
                <label><input type="checkbox" value="Transaminases"> Transaminases</label>
                <label><input type="checkbox" value="Profil lipidique"> Profil lipidique</label>
                <label><input type="checkbox" value="Creatinine"> Créatinine</label>
                <label><input type="checkbox" value="Acide urique"> Acide urique</label>
                <label><input type="checkbox" value="PAL"> PAL</label>
                <label><input type="checkbox" value="LDH"> LDH</label>
                <label><input type="checkbox" value="Glycemie"> Glycémie</label>
                <label><input type="checkbox" value="Uree"> Urée</label>
                <label><input type="checkbox" value="Albuminemie"> Albuminémie</label>
                <label><input type="checkbox" value="ALPHA GT"> ALPHA GT</label>
            </fieldset>

            <!-- Immuno-Sérologie -->
            <fieldset class="tw-bg-blue-50 tw-p-3 tw-rounded-md tw-flex tw-flex-col tw-space-y-2">
                <legend class="tw-font-semibold tw-text-blue-700">Immuno-Sérologie</legend>
                <label><input type="checkbox" value="Ac HCV"> Ac HCV</label>
                <label><input type="checkbox" value="Chlamydia IgG"> Chlamydia IgG</label>
                <label><input type="checkbox" value="Chlamydia IgM"> Chlamydia IgM</label>
                <label><input type="checkbox" value="HIV/SIDA"> HIV/SIDA</label>
                <label><input type="checkbox" value="TPHA/VDRL"> TPHA/VDRL</label>
                <label><input type="checkbox" value="Herpes"> Herpes 1 et 2</label>
                <label><input type="checkbox" value="G TEST"> G. TEST</label>
                <label><input type="checkbox" value="CRP"> CRP</label>
            </fieldset>

            <!-- Hormonologie -->
            <fieldset class="tw-bg-yellow-50 tw-p-3 tw-rounded-md tw-flex tw-flex-col tw-space-y-2">
                <legend class="tw-font-semibold tw-text-yellow-700">Hormonologie</legend>
                <label><input type="checkbox" value="LH"> LH</label>
                <label><input type="checkbox" value="FSH"> FSH</label>
                <label><input type="checkbox" value="TSH"> TSH</label>
                <label><input type="checkbox" value="Prolactine"> Prolactine</label>
                <label><input type="checkbox" value="PSA TOTAL"> PSA TOTAL</label>
                <label><input type="checkbox" value="PSA LIBRE"> PSA LIBRE</label>
                <label><input type="checkbox" value="Testosterone TOTAL"> Testostérone TOTAL</label>
                <label><input type="checkbox" value="T4"> T4</label>
                <label><input type="checkbox" value="Testosterone LIBRE"> Testostérone LIBRE</label>
            </fieldset>

            <!-- Bactériologie -->
            <fieldset class="tw-bg-red-50 tw-p-3 tw-rounded-md tw-flex tw-flex-col tw-space-y-2">
                <legend class="tw-font-semibold tw-text-red-700">Bactériologie</legend>
                <label><input type="checkbox" value="PU + ATB"> PU + ATB</label>
                <label><input type="checkbox" value="PUS + ATB"> PUS + ATB</label>
                <label><input type="checkbox" value="Coproculture + ATB"> Coproculture + ATB</label>
                <label><input type="checkbox" value="Mycoplasme + ATB"> Mycoplasme + ATB</label>
                <label><input type="checkbox" value="ECBU + ATB"> ECBU</label>
                <label><input type="checkbox" value="Spermoculture + ATB"> Spermoculture</label>
            </fieldset>

            <!-- Parasitologie -->
            <fieldset class="tw-bg-yellow-50 tw-p-3 tw-rounded-md tw-flex tw-flex-col tw-space-y-2">
                <legend class="tw-font-semibold tw-text-blue-700">Parasitologie</legend>
                <label><input type="checkbox" value="Goutte epaisse"> Goutte épaisse</label>
                <label><input type="checkbox" value="Selles/KOAP"> Selles / KOAP</label>
                <label><input type="checkbox" value="TDR"> TDR</label>
                <label><input type="checkbox" value="Spermogramme"> Spermogramme</label>
                <label><input type="checkbox" value="Autre examen"> Autre examen</label>
            </fieldset>
        </div>

        <!-- Résumé -->
        <div id="examResume"
             class="tw-hidden tw-mx-6 tw-my-4 tw-p-3 tw-rounded-md tw-bg-blue-50 tw-border tw-border-blue-200">
            <p class="tw-text-sm tw-font-semibold tw-text-blue-700">Examens sélectionnés :</p>
            <p id="examResumeText" class="tw-text-sm tw-text-blue-600"></p>
            <p id="examResumeTotal" class="tw-text-sm tw-font-bold tw-text-blue-800"></p>
        </div>

        <!-- Footer -->
        <div class="tw-border-t tw-px-6 tw-py-3 tw-flex tw-justify-end">
            <button type="button"
                    class="tw-bg-blue-600 hover:tw-bg-blue-700 tw-text-white tw-px-4 tw-py-2 tw-rounded-md tw-font-medium">
                Confirmer la sélection
            </button>
        </div>
    </div>
</div>

            {{-- ── Page Heading ─────────────────────────────────── --}}
            <div class="tw-mb-6 tw-rounded-2xl tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-shadow-sm">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-user-plus tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Nouveau Dossier Patient</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">Enregistrement d'un nouveau patient</p>
                    </div>
                </div>
                <a href="{{ route('patients.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-shrink-0 tw-border tw-border-white/30">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour à la liste</span>
                </a>
            </div>

            {{-- ── Main Form Card ───────────────────────────────── --}}
            <div class="tw-max-w-4xl tw-mx-auto">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                    {{-- Card Header --}}
                    <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-file-medical tw-text-white tw-text-sm"></i>
                            <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">Informations du patient</h2>
                        </div>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">
                            <i class="fas fa-info-circle tw-mr-1"></i>Les champs <span class="tw-text-yellow-300 tw-font-bold">*</span> sont obligatoires
                        </p>
                    </div>

               

                    <div class="tw-p-6">
                        <form action="{{ route('patients.store') }}" method="POST" id="patientForm">
                            @csrf

                            {{-- ── Section 1: Informations personnelles ──────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                        <i class="fas fa-user tw-text-[#1D4ED8] tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations personnelles</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    {{-- Nom --}}
                                    <div>
                                        <label for="name" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Nom <span class="tw-text-red-500">*</span>
                                        </label>
                                        <input name="name" id="name" type="text"
                                               value="{{ old('name') }}"
                                               placeholder="Entrez le nom"
                                               required
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>

                                    {{-- Prénom --}}
                                    <div>
                                        <label for="prenom" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Prénom <span class="tw-text-red-500">*</span>
                                        </label>
                                        <input name="prenom" id="prenom" type="text"
                                               value="{{ old('prenom') }}"
                                               placeholder="Entrez le prénom"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>

                                    {{-- Médecin traitant --}}
                                    <div class="sm:tw-col-span-2">
                                        <label for="assigne_a" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Médecin traitant / Infirmier(e) <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select name="assigne_a" id="assigne_a" required
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                            <option value="">Sélectionnez un médecin ou un(e) infirmier(e)</option>
                                            @foreach ($users as $group => $groupUsers)
                                                <optgroup label="{{ $group }}">
                                                    @foreach ($groupUsers as $user)
                                                        <option value="{{ $user->id }}" {{ old('assigne_a') == $user->id ? 'selected' : '' }}>
                                                            {{ $user->role_id == 2 ? 'Dr.' : '' }} {{ $user->name }} {{ $user->prenom }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('assigne_a')
                                            <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 2: Motif médical ──────────────────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                        <i class="fas fa-stethoscope tw-text-[#14B8A6] tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Motif Médical</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    {{-- Type de motif --}}
                                    <div>
                                        <label for="motif" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Type de motif <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select name="motif" id="motif" required
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                            <option value="">-- Sélectionnez un type --</option>
                                            <option value="Consultation" {{ old('motif') == 'Consultation' ? 'selected' : '' }}>Consultation</option>
                                            <option value="Acte"         {{ old('motif') == 'Acte'         ? 'selected' : '' }}>Acte</option>
                                            <option value="Examen"       {{ old('motif') == 'Examen'       ? 'selected' : '' }}>Examen</option>
                                            <option value="Autres"       {{ old('motif') == 'Autres'       ? 'selected' : '' }}>Autres</option>
                                        </select>
                                    </div>

                                    {{-- Détails motif --}}
                                    <div>
                                        <label id="label_details_motif" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Détails motif <span class="tw-text-red-500">*</span>
                                        </label>

                                        {{-- Consultation --}}
                                        <select class="motif-field tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all"
                                                id="details_motif_consultation" name="details_motif_consultation" style="display:none;">
                                            <option value="">-- Sélectionnez une consultation --</option>
                                            <option value="Consultation générale"    {{ old('details_motif_consultation') == 'Consultation générale'    ? 'selected' : '' }}>Consultation générale</option>
                                            <option value="Consultation anesthesique" {{ old('details_motif_consultation') == 'Consultation anesthesique' ? 'selected' : '' }}>Consultation anesthesique</option>
                                            <option value="Consultation de suivi"    {{ old('details_motif_consultation') == 'Consultation de suivi'    ? 'selected' : '' }}>Consultation de suivi</option>
                                        </select>

                                        {{-- Acte --}}
                                        <select class="motif-field tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all"
                                                id="details_motif_acte" name="details_motif_acte" style="display:none;">
                                            <option value="">-- Sélectionnez un acte --</option>
                                            <optgroup label="Actes Urologiques">
                                                <option value="Cystoscopie"         {{ old('details_motif_acte') == 'Cystoscopie'         ? 'selected' : '' }}>Cystoscopie</option>
                                                <option value="Biopsie prostatique" {{ old('details_motif_acte') == 'Biopsie prostatique' ? 'selected' : '' }}>Biopsie prostatique</option>
                                                <option value="Circoncision"        {{ old('details_motif_acte') == 'Circoncision'        ? 'selected' : '' }}>Circoncision</option>
                                                <option value="Vasectomie"          {{ old('details_motif_acte') == 'Vasectomie'          ? 'selected' : '' }}>Vasectomie</option>
                                                <option value="Lithotripsie"        {{ old('details_motif_acte') == 'Lithotripsie'        ? 'selected' : '' }}>Lithotripsie</option>
                                                <option value="Urétéroscopie"       {{ old('details_motif_acte') == 'Urétéroscopie'       ? 'selected' : '' }}>Urétéroscopie</option>
                                                <option value="Néphrectomie"        {{ old('details_motif_acte') == 'Néphrectomie'        ? 'selected' : '' }}>Néphrectomie</option>
                                                <option value="Prostatectomie"      {{ old('details_motif_acte') == 'Prostatectomie'      ? 'selected' : '' }}>Prostatectomie</option>
                                                <option value="Cure d'hydrocèle"    {{ old('details_motif_acte') == "Cure d'hydrocèle"    ? 'selected' : '' }}>Cure d'hydrocèle</option>
                                                <option value="Pose de sonde JJ"    {{ old('details_motif_acte') == 'Pose de sonde JJ'    ? 'selected' : '' }}>Pose de sonde JJ</option>
                                            </optgroup>
                                            <optgroup label="Actes Chirurgicaux Généraux">
                                                <option value="Laparotomie exploratrice" {{ old('details_motif_acte') == 'Laparotomie exploratrice' ? 'selected' : '' }}>Laparotomie exploratrice</option>
                                                <option value="Appendicectomie"          {{ old('details_motif_acte') == 'Appendicectomie'          ? 'selected' : '' }}>Appendicectomie</option>
                                                <option value="Cholécystectomie"         {{ old('details_motif_acte') == 'Cholécystectomie'         ? 'selected' : '' }}>Cholécystectomie</option>
                                                <option value="Hernioplastie"            {{ old('details_motif_acte') == 'Hernioplastie'            ? 'selected' : '' }}>Hernioplastie</option>
                                                <option value="Cure de varicocèle"       {{ old('details_motif_acte') == 'Cure de varicocèle'       ? 'selected' : '' }}>Cure de varicocèle</option>
                                            </optgroup>
                                            <optgroup label="Actes Endoscopiques">
                                                <option value="Endoscopie digestive" {{ old('details_motif_acte') == 'Endoscopie digestive' ? 'selected' : '' }}>Endoscopie digestive</option>
                                                <option value="Coloscopie"           {{ old('details_motif_acte') == 'Coloscopie'           ? 'selected' : '' }}>Coloscopie</option>
                                                <option value="Gastroscopie"         {{ old('details_motif_acte') == 'Gastroscopie'         ? 'selected' : '' }}>Gastroscopie</option>
                                            </optgroup>
                                            <option value="Autre acte" {{ old('details_motif_acte') == 'Autre acte' ? 'selected' : '' }}>Autre acte (à préciser)</option>
                                        </select>

                                     
                                        <input type="text"
                                               class="motif-field tw-w-full tw-rounded-xl tw-border 
                                               tw-border-slate-200 tw-bg-slate-50 tw-px-4 
                                               tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none f
                                               ocus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] 
                                               focus:tw-border-[#1D4ED8] tw-transition-all"
                                               id="details_motif_autres" name="details_motif_autres"
                                               placeholder="Précisez le motif"
                                               value="{{ old('details_motif_autres') }}"
                                               style="display:none;">

                                      
                                        <div id="examen_display"
                                             class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-500 tw-cursor-default tw-min-h-[42px]"
                                             style="display:none;">
                                            <span id="examen_display_text" class="tw-italic">Aucun examen sélectionné — cliquez sur le bouton ci-dessous</span>
                                        </div>

                                        {{--
                                            Bouton pour rouvrir le modal Examen après une première sélection
                                        --}}
                                        <button type="button" id="btnReopenModal"
                                                class="tw-mt-2 tw-hidden tw-text-xs tw-text-[#1D4ED8] tw-underline tw-bg-transparent tw-border-0 tw-cursor-pointer">
                                            Modifier la sélection des examens
                                        </button>

                                        {{--
                                            ⚠️ CHAMP CLÉ pour le controller store().
                                            Reçoit la valeur finale quel que soit le motif :
                                            - Consultation → valeur du select consultation
                                            - Acte         → valeur du select acte
                                            - Examen       → liste CSV des examens cochés
                                            - Autres       → valeur du champ texte
                                            name="details_motif" → $request->input('details_motif')
                                        --}}
                                        <input type="hidden" name="details_motif" id="details_motif_hidden" value="{{ old('details_motif') }}">
                                    </div>

                                    {{-- Date de création --}}
                                    <div>
                                        <label for="date_insertion" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Date de création <span class="tw-text-red-500">*</span>
                                        </label>
                                        <input type="date" name="date_insertion" id="date_insertion"
                                               value="{{ old('date_insertion', date('Y-m-d')) }}"
                                               readonly required
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-500 tw-cursor-not-allowed">
                                        <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1 tw-flex tw-items-center tw-gap-1">
                                            <i class="fas fa-info-circle"></i> Date générée automatiquement
                                        </p>
                                    </div>

                                    {{-- Démarcheur --}}
                                    <div>
                                        <label for="demarcheur" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Démarcheur
                                        </label>
                                        <select name="demarcheur" id="demarcheur"
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                            <option value="">Aucun</option>
                                            <option {{ old('demarcheur') == 'DMH' ? 'selected' : '' }}>DMH</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 3: Informations financières ────────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                        <i class="fas fa-money-bill-wave tw-text-amber-500 tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations financières</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    {{-- Mode de paiement --}}
                                    <div>
                                        <label for="mode_paiement" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Mode de paiement <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select name="mode_paiement" id="mode_paiement"
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                            <optgroup label="Monnaie électronique">
                                                <option value="orange money"      {{ old('mode_paiement') == 'orange money'      ? 'selected' : '' }}>Orange Money</option>
                                                <option value="mtn mobile money"  {{ old('mode_paiement') == 'mtn mobile money'  ? 'selected' : '' }}>MTN Mobile Money</option>
                                            </optgroup>
                                            <optgroup label="Autres moyens">
                                                <option value="espèce" {{ old('mode_paiement', 'espèce') == 'espèce' ? 'selected' : '' }}>Espèce</option>
                                                <option value="chèque"                  {{ old('mode_paiement') == 'chèque'                  ? 'selected' : '' }}>Chèque</option>
                                                <option value="virement"                {{ old('mode_paiement') == 'virement'                ? 'selected' : '' }}>Virement</option>
                                                <option value="bon de prise en charge"  {{ old('mode_paiement') == 'bon de prise en charge'  ? 'selected' : '' }}>Bon de prise en charge</option>
                                                <option value="autre"                   {{ old('mode_paiement') == 'autre'                   ? 'selected' : '' }}>Autre</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    {{-- Devise --}}
                                    <div>
                                        <label for="devise" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Devise de paiement
                                        </label>
                                        <select name="devise" id="devise"
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all"
                                                onchange="toggleTaux()">
                                            <option value="XAF" selected>FCFA (XAF)</option>
                                            {{-- <option value="EUR">Euro (EUR)</option> --}}
                                            {{-- <option value="DOLLAR">Dollar</option> --}}
                                            {{-- <option value="GBP">Livre Sterling (GBP)</option> --}}
                                        </select>
                                    </div>

                                    {{-- Montant --}}
                                    <div>
                                        <label for="montant" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Montant des soins <span class="tw-text-red-500">*</span>
                                        </label>
                                        <div class="tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE] focus-within:tw-border-[#1D4ED8] tw-transition-all">
                                            <input name="montant" id="montant"
                                                   value="{{ old('montant', 0) }}" type="number" min="0" step="1" required
                                                   class="tw-flex-1 tw-rounded-l-xl tw-border-0 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white tw-transition-all">
                                            <span class="tw-bg-[#1D4ED8] tw-px-4 tw-flex tw-items-center tw-text-white tw-text-sm tw-rounded-r-xl">
                                                FCFA
                                            </span>
                                        </div>
                                        <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1 tw-flex tw-items-center tw-gap-1">
                                            <i class="fas fa-info-circle"></i> Toujours en FCFA
                                        </p>
                                    </div>

                                    {{-- Bloc taux (devise étrangère) --}}
                                    <div class="sm:tw-col-span-2" id="taux_block" style="display:none;">
                                        <div class="tw-rounded-xl tw-bg-slate-50 tw-border-0 tw-p-4 tw-mt-3">
                                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-3">
                                                <div>
                                                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1">
                                                        Taux du jour
                                                        <span class="tw-text-[11px] tw-text-slate-400 tw-font-normal tw-block">(1 <span id="taux_devise_lbl">EUR</span> = ? FCFA)</span>
                                                        <span class="tw-text-red-500">*</span>
                                                    </label>
                                                    <div class="tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE] focus-within:tw-border-[#1D4ED8] tw-transition-all">
                                                        <input name="taux_conversion" id="taux_conversion"
                                                               value="{{ old('taux_conversion') }}" type="number"
                                                               min="1" step="0.01" placeholder="ex: 655"
                                                               class="tw-flex-1 tw-rounded-l-xl tw-border-0 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white tw-transition-all">
                                                        <span class="tw-bg-[#1D4ED8] tw-px-4 tw-flex tw-items-center tw-text-white tw-text-sm tw-rounded-r-xl">FCFA</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1">
                                                        Montant remis par le patient
                                                        <span class="tw-text-[11px] tw-text-slate-400 tw-font-normal tw-block">(<span id="remis_devise_lbl">EUR</span>)</span>
                                                        <span class="tw-text-red-500">*</span>
                                                    </label>
                                                    <div class="tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE] focus-within:tw-border-[#1D4ED8] tw-transition-all">
                                                        <input name="montant_devise" id="montant_devise"
                                                               value="{{ old('montant_devise') }}" type="number"
                                                               min="0" step="0.01" placeholder="ex: 200"
                                                               class="tw-flex-1 tw-rounded-l-xl tw-border-0 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white tw-transition-all">
                                                        <span class="tw-bg-[#1D4ED8] tw-px-4 tw-flex tw-items-center tw-text-white tw-text-sm tw-rounded-r-xl" id="remis_addon">EUR</span>
                                                    </div>
                                                </div>
                                                <div class="tw-flex tw-items-end">
                                                    <div id="recap_live" class="tw-w-full tw-p-2 tw-rounded-xl tw-bg-white tw-border tw-border-slate-200 tw-text-sm">
                                                        <small class="tw-text-slate-400">Résumé apparaîtra ici</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Avance --}}
                                    <div>
                                        <label for="avance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Avance encaissée <span class="tw-text-red-500">*</span>
                                        </label>
                                        <div class="tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE] focus-within:tw-border-[#1D4ED8] tw-transition-all">
                                            <input name="avance" id="avance"
                                                   value="{{ old('avance', 0) }}" type="number" min="0" step="1" required
                                                   class="tw-flex-1 tw-rounded-l-xl tw-border-0 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white tw-transition-all">
                                            <span class="tw-bg-[#1D4ED8] tw-px-4 tw-flex tw-items-center tw-text-white tw-text-sm tw-rounded-r-xl">FCFA</span>
                                        </div>
                                        <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1" id="avance_hint">
                                            Montant réellement encaissé en FCFA (max = montant des soins)
                                        </p>
                                    </div>

                                    {{-- Rendu monnaie (informatif, non soumis) --}}
                                    <div id="rendu_block" style="display:none;">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-400 tw-mb-1.5">Rendu monnaie</label>
                                        <div class="tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 tw-bg-slate-100 tw-transition-all">
                                            <input id="rendu_display" type="text" readonly
                                                   placeholder="Calculé automatiquement"
                                                   class="tw-flex-1 tw-rounded-l-xl tw-border-0 tw-bg-slate-100 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-500 tw-cursor-not-allowed">
                                            <span class="tw-bg-slate-300 tw-px-4 tw-flex tw-items-center tw-text-slate-600 tw-text-sm tw-rounded-r-xl">FCFA</span>
                                        </div>
                                        <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1">Informatif — non enregistré en base</p>
                                    </div>

                                    {{-- Chèque --}}
                                    <div id="cheque_fields" class="sm:tw-col-span-2" style="display: none;">
                                        <div class="tw-rounded-xl tw-bg-slate-50 tw-border tw-border-slate-200 tw-p-4">
                                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-3">
                                                <i class="fas fa-money-check tw-text-slate-400 tw-text-sm"></i>
                                                <h4 class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-0">Informations du chèque</h4>
                                            </div>
                                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-3">
                                                <div>
                                                    <label for="num_cheque" class="tw-block tw-text-xs tw-font-medium tw-text-slate-500 tw-mb-1">N° du chèque <span class="tw-text-red-500">*</span></label>
                                                    <input name="num_cheque" id="num_cheque" type="text"
                                                           value="{{ old('num_cheque') }}" placeholder="N° du chèque"
                                                           class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                                </div>
                                                <div>
                                                    <label for="emetteur_cheque" class="tw-block tw-text-xs tw-font-medium tw-text-slate-500 tw-mb-1">Émetteur <span class="tw-text-red-500">*</span></label>
                                                    <input name="emetteur_cheque" id="emetteur_cheque" type="text"
                                                           value="{{ old('emetteur_cheque') }}" placeholder="Nom de l'émetteur"
                                                           class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                                </div>
                                                <div>
                                                    <label for="banque_cheque" class="tw-block tw-text-xs tw-font-medium tw-text-slate-500 tw-mb-1">Banque <span class="tw-text-red-500">*</span></label>
                                                    <input name="banque_cheque" id="banque_cheque" type="text"
                                                           value="{{ old('banque_cheque') }}" placeholder="Nom de la banque"
                                                           class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Bon de prise en charge --}}
                                    <div id="bpc_field" class="sm:tw-col-span-2" style="display: none;">
                                        <div class="tw-rounded-xl tw-bg-slate-50 tw-border tw-border-slate-200 tw-p-4">
                                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-3">
                                                <i class="fas fa-file-invoice tw-text-slate-400 tw-text-sm"></i>
                                                <h4 class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-0">Bon de prise en charge</h4>
                                            </div>
                                            <div>
                                                <label for="emetteur_bpc" class="tw-block tw-text-xs tw-font-medium tw-text-slate-500 tw-mb-1">Émetteur du bon <span class="tw-text-red-500">*</span></label>
                                                <input name="emetteur_bpc" id="emetteur_bpc" type="text"
                                                       value="{{ old('emetteur_bpc') }}" placeholder="Nom de l'organisme émetteur"
                                                       class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 4: Informations d'assurance ─────────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4 tw-pb-2 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                        <i class="fas fa-shield-alt tw-text-indigo-500 tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations d'assurance</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label for="assurance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Assurance
                                        </label>
                                        <input name="assurance" id="assurance" type="text"
                                               value="{{ old('assurance') }}"
                                               placeholder="Nom de l'assurance (si assuré)"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>

                                    <div>
                                        <label for="numero_assurance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Numéro d'assurance
                                        </label>
                                        <input name="numero_assurance" id="numero_assurance" type="text"
                                               value="{{ old('numero_assurance') }}"
                                               placeholder="N° d'assurance (si assuré)"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>

                                    <div class="sm:tw-col-span-2">
                                        <label for="prise_en_charge" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Taux de prise en charge
                                        </label>
                                        <div class="tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE] focus-within:tw-border-[#1D4ED8] tw-transition-all">
                                            <select name="prise_en_charge" id="prise_en_charge" required
                                                    class="tw-flex-1 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-border-0 focus:tw-outline-none focus:tw-bg-white tw-transition-all">
                                                @foreach(range(0, 100) as $taux)
                                                <option {{ old('prise_en_charge', 0) == $taux ? 'selected' : '' }}>{{ $taux }}</option>
                                                @endforeach
                                            </select>
                                            <span class="tw-bg-[#1D4ED8] tw-px-4 tw-flex tw-items-center tw-text-white tw-text-sm">
                                                <i class="fas fa-percent"></i>
                                            </span>
                                        </div>
                                        <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1">Pourcentage de prise en charge par l'assurance</p>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Action Buttons ───────────────────────────────── --}}
                            <div class="tw-flex tw-items-center tw-justify-between tw-pt-5 tw-border-t tw-border-slate-100 tw-gap-3">
                                <a href="{{ route('patients.index') }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-5 tw-py-3 tw-no-underline tw-transition-colors">
                                    <i class="fas fa-times tw-text-xs"></i> Annuler
                                </a>
                                <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-6 tw-py-3 tw-border-0 tw-transition-colors tw-shadow-sm">
                                    <i class="fas fa-save tw-text-xs"></i> Enregistrer le patient
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

@endsection

@section('script')
<script>
/**
 * ================================================================
 * Script unifié — Formulaire création patient
 * ================================================================
 */
document.addEventListener('DOMContentLoaded', function () {

    // ── Références DOM ──────────────────────────────────────────
    const motifSelect           = document.getElementById('motif');
    const labelDetailsMotif     = document.getElementById('label_details_motif');
    const detailsConsultation   = document.getElementById('details_motif_consultation');
    const detailsActe           = document.getElementById('details_motif_acte');
    const detailsAutres         = document.getElementById('details_motif_autres');
    const detailsHidden         = document.getElementById('details_motif_hidden');
    const examenDisplay         = document.getElementById('examen_display');
    const examenDisplayText     = document.getElementById('examen_display_text');
    const btnReopenModal        = document.getElementById('btnReopenModal');

    // Modal Examen
    const modal                 = document.getElementById('examModal');
    const closeModalBtn         = document.getElementById('closeModalBtn');
    const examResume            = document.getElementById('examResume');
    const examResumeText        = document.getElementById('examResumeText');
    const examResumeTotal       = document.getElementById('examResumeTotal');
    const montantInput          = document.getElementById('montant');

    // Paiement
    const modePaiementSelect    = document.getElementById('mode_paiement');
    const chequeFields          = document.getElementById('cheque_fields');
    const bpcField              = document.getElementById('bpc_field');

    // ── Tarifs examens (FCFA) ───────────────────────────────────
    const TARIFS_EXAMENS = {
        'NFS': 7000, 'HbA1C': 18000, 'Electrophorese': 15000,
        'D-Dimeres': 25000, 'TP': 5000, 'TP/TCK': 10000,
        'Transaminases': 8000, 'Profil lipidique': 18000,
        'Creatinine': 4000, 'Acide urique': 5000, 'PAL': 7000,
        'LDH': 6000, 'Glycemie': 6000, 'Uree': 4000,
        'Albuminemie': 7000, 'ALPHA GT': 7500,
        'Ac HCV': 10000, 'Chlamydia IgG': 15000, 'Chlamydia IgM': 15000,
        'HIV/SIDA': 3000, 'TPHA/VDRL': 7000, 'Herpes': 30000,
        'G TEST': 5000, 'CRP': 5000,
        'LH': 20000, 'FSH': 20000, 'TSH': 20000, 'Prolactine': 20000,
        'PSA TOTAL': 20500, 'PSA LIBRE': 21000,
        'Testosterone TOTAL': 20500, 'T4': 20000, 'Testosterone LIBRE': 21000,
        'PU + ATB': 15000, 'PUS + ATB': 20000, 'Coproculture + ATB': 15000,
        'Mycoplasme + ATB': 30000, 'ECBU + ATB': 14000, 'Spermoculture + ATB': 15000,
        'Goutte epaisse': 2500, 'Selles/KOAP': 3500,
        'TDR': 2000, 'Spermogramme': 15000,
    };

    // ══════════════════════════════════════════════════════════════
    // 1. GESTION MOTIF — affiche le bon champ selon la sélection
    // ══════════════════════════════════════════════════════════════

    /**
     * Cache tous les champs "détail motif" et retire leur required.
     * ⚠️  On n'inclut plus detailsExamen (select commenté = null).
     */
    function hideAllDetailsFields() {
        [detailsConsultation, detailsActe, detailsAutres].forEach(function (el) {
            el.style.display = 'none';
            el.removeAttribute('required');
        });
        // Cacher aussi la zone d'affichage examen + bouton modifier
        examenDisplay.style.display   = 'none';
        btnReopenModal.classList.add('tw-hidden');
        // Fermer le modal si on change de motif
        if (modal) modal.classList.add('tw-hidden');
    }

    function handleMotifChange() {
        hideAllDetailsFields();
        var val = motifSelect.value;

        switch (val) {
            case 'Consultation':
                labelDetailsMotif.innerHTML = 'Type de consultation <span class="tw-text-red-500">*</span>';
                detailsConsultation.style.display = 'block';
                detailsConsultation.setAttribute('required', 'required');
                break;

            case 'Acte':
                labelDetailsMotif.innerHTML = "Type d'acte <span class='tw-text-red-500'>*</span>";
                detailsActe.style.display = 'block';
                detailsActe.setAttribute('required', 'required');
                break;

            case 'Examen':
                labelDetailsMotif.innerHTML = "Type d'examen <span class='tw-text-red-500'>*</span>";
                // Afficher la zone de résumé des examens cochés
                examenDisplay.style.display = 'block';
                // Ouvrir le modal de sélection
                if (modal) modal.classList.remove('tw-hidden');
                break;

            case 'Autres':
                labelDetailsMotif.innerHTML = 'Détails motif <span class="tw-text-red-500">*</span>';
                detailsAutres.style.display = 'block';
                detailsAutres.setAttribute('required', 'required');
                break;

            default:
                labelDetailsMotif.innerHTML = 'Détails motif <span class="tw-text-red-500">*</span>';
        }
    }

    // ── syncDetailsMotif ────────────────────────────────────────
    /**
     * Lit la valeur du champ visible selon le motif courant
     * et la pousse dans details_motif (hidden).
     * C'est ce hidden que store() lit via $request->input('details_motif').
     */
    function syncDetailsMotif() {
        var val = motifSelect.value;
        var finalValue = '';

        switch (val) {
            case 'Consultation':
                finalValue = detailsConsultation.value;
                break;
            case 'Acte':
                finalValue = detailsActe.value;
                break;
            case 'Examen':
                // Récupérer tous les examens cochés dans le modal
                var checked = modal
                    ? Array.from(modal.querySelectorAll('input[type=checkbox]:checked'))
                    : [];
                finalValue = checked.map(function (cb) { return cb.value; }).join(', ');
                break;
            case 'Autres':
                finalValue = detailsAutres.value;
                break;
        }

        detailsHidden.value = finalValue;
    }

    // Événements changement motif
    motifSelect.addEventListener('change', handleMotifChange);
    detailsConsultation.addEventListener('change', syncDetailsMotif);
    detailsActe.addEventListener('change', syncDetailsMotif);
    detailsAutres.addEventListener('input', syncDetailsMotif);

    // ══════════════════════════════════════════════════════════════
    // 2. GESTION MODAL EXAMEN
    // ══════════════════════════════════════════════════════════════

    /**
     * Met à jour le résumé dans le modal et la zone d'affichage
     * sous le label, puis synchro le hidden.
     */
    function updateExamenResume() {
        if (!modal) return;
        var checked = Array.from(modal.querySelectorAll('input[type=checkbox]:checked'));
        var noms    = checked.map(function (cb) { return cb.value; });
        var total   = checked.reduce(function (sum, cb) {
            return sum + (TARIFS_EXAMENS[cb.value] || 0);
        }, 0);

        // Mise à jour du montant
        montantInput.value = total;

        // Résumé dans le modal
        if (checked.length > 0) {
            examResume.classList.remove('tw-hidden');
            examResumeText.textContent  = noms.join(', ');
            examResumeTotal.textContent = 'Total : ' + total.toLocaleString('fr-FR') + ' FCFA';
        } else {
            examResume.classList.add('tw-hidden');
        }

        // Zone d'affichage sous le label (lecture seule)
        if (checked.length > 0) {
            examenDisplayText.textContent = noms.join(', ') + ' — ' + total.toLocaleString('fr-FR') + ' FCFA';
        } else {
            examenDisplayText.textContent = 'Aucun examen sélectionné — cliquez sur le bouton ci-dessous';
        }

        syncDetailsMotif();
    }

    // Calcul en temps réel quand on coche/décoche
    if (modal) {
        modal.addEventListener('change', function (e) {
            if (e.target.type !== 'checkbox') return;
            updateExamenResume();
        });
    }

    // Bouton "Confirmer la sélection" — ferme le modal
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            modal.classList.add('tw-hidden');
            syncDetailsMotif();
            // Afficher le bouton "Modifier la sélection" si des examens sont cochés
            var checked = modal.querySelectorAll('input[type=checkbox]:checked');
            if (checked.length > 0) {
                btnReopenModal.classList.remove('tw-hidden');
            }
        });
    }

    // Bouton "Modifier la sélection" — rouvre le modal
    if (btnReopenModal) {
        btnReopenModal.addEventListener('click', function () {
            if (modal) modal.classList.remove('tw-hidden');
        });
    }

    // ══════════════════════════════════════════════════════════════
    // 3. VALIDATION À LA SOUMISSION DU FORMULAIRE
    // ══════════════════════════════════════════════════════════════

    document.getElementById('patientForm').addEventListener('submit', function (e) {
        syncDetailsMotif(); // S'assurer que le hidden est à jour

        if (!detailsHidden.value.trim()) {
            e.preventDefault();
            alert('Veuillez sélectionner ou saisir les détails du motif.');
            return false;
        }
    });

    // ══════════════════════════════════════════════════════════════
    // 4. GESTION MODE DE PAIEMENT
    // ══════════════════════════════════════════════════════════════

    function togglePaymentFields() {
        var mode = modePaiementSelect.value;

        chequeFields.style.display = 'none';
        bpcField.style.display     = 'none';

        // Retirer required et vider les champs chèque
        ['num_cheque', 'emetteur_cheque', 'banque_cheque'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) { el.removeAttribute('required'); }
        });

        // Retirer required BPC
        var bpcEl = document.getElementById('emetteur_bpc');
        if (bpcEl) { bpcEl.removeAttribute('required'); }

        if (mode === 'chèque') {
            chequeFields.style.display = 'block';
            ['num_cheque', 'emetteur_cheque', 'banque_cheque'].forEach(function (id) {
                document.getElementById(id).setAttribute('required', 'required');
            });
        } else if (mode === 'bon de prise en charge') {
            bpcField.style.display = 'block';
            if (bpcEl) bpcEl.setAttribute('required', 'required');
        }
    }

    modePaiementSelect.addEventListener('change', togglePaymentFields);
    togglePaymentFields(); // Init au chargement

    // ══════════════════════════════════════════════════════════════
    // 5. RESTAURATION APRÈS ERREUR DE VALIDATION Laravel (old())
    //    Si le formulaire a été resoumis avec des erreurs, on
    //    réaffiche le bon champ selon le motif précédemment saisi.
    // ══════════════════════════════════════════════════════════════
    @if(old('motif'))
        handleMotifChange();
        @if(old('motif') === 'Examen' && old('details_motif'))
            // Ré-afficher la valeur des examens dans la zone lecture seule
            examenDisplayText.textContent = '{{ old('details_motif') }}';
        @endif
        syncDetailsMotif();
    @endif

});
</script>
@stop