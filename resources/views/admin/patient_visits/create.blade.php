@extends('layouts.admin')

@section('title', 'CMCU | Nouvelle visite patient')

@section('content')

<style>
    /* Select2 custom styling to match brand */
    .select2-container--default .select2-selection--single {
        width: 100% !important;
        height: 38px !important;
        padding: 4px 12px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        background: #fff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        color: #374151 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #1D4ED8 !important;
        box-shadow: 0 0 0 3px rgba(29,78,216,0.1) !important;
    }
    .select2-results__option { padding: 8px 12px !important; }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #1D4ED8 !important;
    }
    .badge-dossier {
        background-color: #1D4ED8;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8em;
        font-weight: 600;
        margin-right: 6px;
    }
    .patient-name { font-weight: 500; color: #334155; }
</style>

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-lg tw-mx-auto">

                  {{-- ── Modal Examen ──────────────────────────────────── --}}


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



<!----------------------------------------------------------------------------------------->

                {{-- ── Page Header ──────────────────────────────────────── --}}
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-6">
                    <div>
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                            <span class="tw-text-xs tw-font-semibold tw-tracking-widest tw-text-emerald-700 tw-uppercase tw-bg-emerald-100 tw-px-2.5 tw-py-1 tw-rounded-full">Nouvelle visite</span>
                        </div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-tracking-tight">Enregistrer une visite</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-0.5">Pour un patient déjà enregistré dans le système</p>
                    </div>
                    <a href="{{ route('patient-visits.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2.5 tw-rounded-xl tw-shadow-card tw-border tw-border-slate-200 tw-transition-all tw-duration-150 tw-no-underline tw-whitespace-nowrap">
                        <i class="fas fa-arrow-left tw-text-xs"></i>
                        Retour à la liste
                    </a>
                </div>

                {{-- ── Info alert ───────────────────────────────────────── --}}
                <div class="tw-flex tw-items-start tw-gap-3 tw-bg-sky-50 tw-border tw-border-sky-200 tw-text-sky-800 tw-rounded-xl tw-px-5 tw-py-4 tw-mb-6 tw-text-sm">
                    <i class="fas fa-info-circle tw-text-sky-500 tw-mt-0.5 tw-shrink-0"></i>
                    <p class="tw-mb-0"><strong>Important :</strong> Ce formulaire est destiné aux patients <strong>déjà enregistrés</strong> dans le système. Pour un nouveau patient, veuillez utiliser le formulaire d'ajout de patient classique.</p>
                </div>

                {{-- ── Form Card ────────────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">

                    {{-- Card Header --}}
                    <div class="tw-px-6 tw-py-4 tw-bg-emerald-700 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-file-medical-alt tw-text-white tw-text-sm"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">Informations de la visite</h2>
                            <p class="tw-text-white/70 tw-text-xs tw-mb-0">Les champs marqués <span class="tw-text-amber-300 tw-font-bold">*</span> sont obligatoires</p>
                        </div>
                    </div>

                    <div class="tw-p-6">
                        <form method="POST" action="{{ route('patient-visits.store') }}" id="visitForm">
                            @csrf

                            {{-- ── Section 1: Patient ───────────────────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-primary-100 tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-user-search tw-text-primary-700 tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Sélection du patient</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 tw-gap-4">
                                    <div>
                                        <label for="patient_id" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Rechercher un patient <span class="tw-text-red-500">*</span>
                                            <span class="tw-text-slate-400 tw-font-normal tw-ml-1">— Tapez le nom, prénom ou numéro de dossier</span>
                                        </label>
                                        <select name="patient_id" id="patient_id" class="form-control" required style="width:100%">
                                            <option value="">-- Sélectionnez un patient --</option>
                                            @if(isset($preselectedPatient) && $preselectedPatient)
                                                <option value="{{ $preselectedPatient->id }}" selected>
                                                    CMCU-{{ $preselectedPatient->numero_dossier }} | {{ $preselectedPatient->name }} {{ $preselectedPatient->prenom }}
                                                </option>
                                            @endif
                                        </select>
                                        <div id="selected_patient_info" style="display:none;" class="tw-mt-2">
                                            <div class="tw-inline-flex tw-items-center tw-gap-2 tw-text-emerald-700 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-px-3 tw-py-2 tw-rounded-lg tw-text-sm tw-font-medium">
                                                <i class="fas fa-check-circle tw-text-emerald-500"></i>
                                                Patient sélectionné : <strong id="patient_display_name"></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="medecin_r" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Médecin traitant <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select class="form-select" name="medecin_r" id="medecin_r" required>
                                            <option value="">Sélectionnez un médecin</option>
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

                                       
                                    </div>
                                </div>
                            </div>

                            <div class="tw-border-t tw-border-slate-100 tw-mb-8"></div>

                            {{-- ── Section 2: Motif ─────────────────────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-stethoscope tw-text-teal-600 tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Motif médical</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label for="motif" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Type de motif <span class="tw-text-red-500">*</span></label>
                                        <select class="form-select" name="motif" id="motif" required>
                                            <option value="">-- Sélectionnez un type --</option>
                                            <option value="Consultation" {{ old('motif') == 'Consultation' ? 'selected' : '' }}>Consultation</option>
                                            <option value="Acte"         {{ old('motif') == 'Acte'         ? 'selected' : '' }}>Acte</option>
                                            <option value="Examen"       {{ old('motif') == 'Examen'       ? 'selected' : '' }}>Examen</option>
                                            <option value="Autres"       {{ old('motif') == 'Autres'       ? 'selected' : '' }}>Autres</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label id="label_details_motif" for="details_motif_consultation" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Détails motif <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select class="form-select motif-field" id="details_motif_consultation" name="details_motif_consultation" style="display:none;">
                                            <option value="">-- Sélectionnez une consultation --</option>
                                            <option value="Consultation générale"      {{ old('details_motif') == 'Consultation générale'      ? 'selected' : '' }}>Consultation générale</option>
                                            <option value="Consultation anesthésiste"  {{ old('details_motif') == 'Consultation anesthésiste'  ? 'selected' : '' }}>Consultation anesthésiste</option>
                                            <option value="Consultation de suivi"      {{ old('details_motif') == 'Consultation de suivi'      ? 'selected' : '' }}>Consultation de suivi</option>
                                           
                                        </select>

                                        <select class="form-select motif-field" id="details_motif_acte" name="details_motif_acte" style="display:none;">
                                            <option value="">-- Sélectionnez un acte --</option>
                                            <optgroup label="Actes Urologiques">
                                                <option value="Cystoscopie"         {{ old('details_motif') == 'Cystoscopie'         ? 'selected' : '' }}>Cystoscopie</option>
                                                <option value="Biopsie prostatique" {{ old('details_motif') == 'Biopsie prostatique' ? 'selected' : '' }}>Biopsie prostatique</option>
                                                <option value="Circoncision"        {{ old('details_motif') == 'Circoncision'        ? 'selected' : '' }}>Circoncision</option>
                                                <option value="Vasectomie"          {{ old('details_motif') == 'Vasectomie'          ? 'selected' : '' }}>Vasectomie</option>
                                                <option value="Lithotripsie"        {{ old('details_motif') == 'Lithotripsie'        ? 'selected' : '' }}>Lithotripsie</option>
                                                <option value="Urétéroscopie"       {{ old('details_motif') == 'Urétéroscopie'       ? 'selected' : '' }}>Urétéroscopie</option>
                                                <option value="Néphrectomie"        {{ old('details_motif') == 'Néphrectomie'        ? 'selected' : '' }}>Néphrectomie</option>
                                                <option value="Prostatectomie"      {{ old('details_motif') == 'Prostatectomie'      ? 'selected' : '' }}>Prostatectomie</option>
                                                <option value="Cure d'hydrocèle"    {{ old('details_motif') == "Cure d'hydrocèle"    ? 'selected' : '' }}>Cure d'hydrocèle</option>
                                                <option value="Pose de sonde JJ"    {{ old('details_motif') == 'Pose de sonde JJ'    ? 'selected' : '' }}>Pose de sonde JJ</option>
                                            </optgroup>
                                            <optgroup label="Actes Chirurgicaux Généraux">
                                                <option value="Laparotomie exploratrice" {{ old('details_motif') == 'Laparotomie exploratrice' ? 'selected' : '' }}>Laparotomie exploratrice</option>
                                                <option value="Appendicectomie"          {{ old('details_motif') == 'Appendicectomie'          ? 'selected' : '' }}>Appendicectomie</option>
                                                <option value="Cholécystectomie"         {{ old('details_motif') == 'Cholécystectomie'         ? 'selected' : '' }}>Cholécystectomie</option>
                                                <option value="Hernioplastie"            {{ old('details_motif') == 'Hernioplastie'            ? 'selected' : '' }}>Hernioplastie</option>
                                                <option value="Cure de varicocèle"       {{ old('details_motif') == 'Cure de varicocèle'       ? 'selected' : '' }}>Cure de varicocèle</option>
                                            </optgroup>
                                            <optgroup label="Actes Endoscopiques">
                                                <option value="Endoscopie digestive" {{ old('details_motif') == 'Endoscopie digestive' ? 'selected' : '' }}>Endoscopie digestive</option>
                                                <option value="Coloscopie"           {{ old('details_motif') == 'Coloscopie'           ? 'selected' : '' }}>Coloscopie</option>
                                                <option value="Gastroscopie"         {{ old('details_motif') == 'Gastroscopie'         ? 'selected' : '' }}>Gastroscopie</option>
                                            </optgroup>
                                            <option value="Autre acte" {{ old('details_motif') == 'Autre acte' ? 'selected' : '' }}>Autre acte (à préciser)</option>
                                        </select>

                                     

                                        <input type="text" class="form-control motif-field" id="details_motif_autres" 
                                               name="details_motif_autres"
                                               placeholder="Précisez le motif" value="{{ old('details_motif') }}" 
                                               style="display:none;">

                                        <input type="hidden" name="details_motif" id="details_motif_hidden" 
                                        value="{{ old('details_motif') }}">

                                          <div id="examen_display"
                                             class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-500 tw-cursor-default tw-min-h-[42px]"
                                             style="display:none;">
                                            <span id="examen_display_text" class="tw-italic">Aucun examen sélectionné — cliquez sur le bouton ci-dessous</span>
                                        </div>

                                         <button type="button" id="btnReopenModal"
                                                class="tw-mt-2 tw-hidden tw-text-xs tw-text-[#1D4ED8] tw-underline tw-bg-transparent tw-border-0 tw-cursor-pointer">
                                            Modifier la sélection des examens
                                        </button>
                                    </div>

                                    <div>
                                        <label for="visit_date" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Date de la visite <span class="tw-text-red-500">*</span>
                                        </label>
                                        <input type="date" class="form-control" id="visit_date" name="visit_date"
                                               value="{{ old('visit_date', date('Y-m-d')) }}" readonly required>
                                        @error('visit_date')
                                            <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!--div>
                                        <label for="demarcheur" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Démarcheur</label>
                                        <select class="form-select" name="demarcheur" id="demarcheur">
                                            <option value="">Aucun</option>
                                            <option {{ old('demarcheur') == 'DMH' ? 'selected' : '' }}>DMH</option>
                                        </select>
                                    </div-->
                                </div>
                            </div>

                            <div class="tw-border-t tw-border-slate-100 tw-mb-8"></div>

                            {{-- ── Section 3: Finances ──────────────────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-money-bill-wave tw-text-teal-600 tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations financières</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label for="mode_paiement" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Mode de paiement <span class="tw-text-red-500">*</span></label>
                                        <select name="mode_paiement" id="mode_paiement" class="form-select">
                                            <optgroup label="Monnaie électronique">
                                                <option value="orange money"      {{ old('mode_paiement') == 'orange money'      ? 'selected' : '' }}>Orange Money</option>
                                                <option value="mtn mobile money"  {{ old('mode_paiement') == 'mtn mobile money'  ? 'selected' : '' }}>MTN Mobile Money</option>
                                            </optgroup>
                                            <optgroup label="Autres moyens">
                                                <option selected value="espèce">Espèce</option>
                                                <option value="chèque"                {{ old('mode_paiement') == 'chèque'                ? 'selected' : '' }}>Chèque</option>
                                                <option value="virement"              {{ old('mode_paiement') == 'virement'              ? 'selected' : '' }}>Virement</option>
                                                <option value="bon de prise en charge"{{ old('mode_paiement') == 'bon de prise en charge'? 'selected' : '' }}>Bon de prise en charge</option>
                                                <option value="autre"                 {{ old('mode_paiement') == 'autre'                 ? 'selected' : '' }}>Autre</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="montant" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Montant total <span class="tw-text-red-500">*</span></label>
                                        <div class="input-group">
                                            <input name="montant" id="montant" class="form-control" value="{{ old('montant', 0) }}" type="number" min="0" step="1" required>
                                            <span class="input-group-text tw-bg-slate-100 tw-text-slate-500 tw-font-medium">FCFA</span>
                                        </div>
                                    </div>

                                    <div class="sm:tw-col-span-2 sm:tw-w-1/2 sm:tw-mx-auto">
                                        <label for="avance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Avance <span class="tw-text-red-500">*</span></label>
                                        <div class="input-group">
                                            <input name="avance" id="avance" class="form-control" value="{{ old('avance', 0) }}" type="number" min="0" step="1" required>
                                            <span class="input-group-text tw-bg-slate-100 tw-text-slate-500 tw-font-medium">FCFA</span>
                                        </div>
                                    </div>

                                    {{-- Cheque fields --}}
                                    <div id="cheque_fields" class="sm:tw-col-span-2" style="display:none;">
                                        <div class="tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-xl tw-p-4">
                                            <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-3">
                                                <i class="fas fa-money-check tw-mr-1.5 tw-text-slate-400"></i>Informations du chèque
                                            </p>
                                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-3">
                                                <div>
                                                    <label class="tw-block tw-text-xs tw-text-slate-500 tw-mb-1">Numéro du chèque <span class="tw-text-red-500">*</span></label>
                                                    <input name="num_cheque" id="num_cheque" class="form-control" value="{{ old('num_cheque') }}" type="text" placeholder="N° du chèque">
                                                </div>
                                                <div>
                                                    <label class="tw-block tw-text-xs tw-text-slate-500 tw-mb-1">Émetteur <span class="tw-text-red-500">*</span></label>
                                                    <input name="emetteur_cheque" id="emetteur_cheque" class="form-control" value="{{ old('emetteur_cheque') }}" type="text" placeholder="Nom émetteur">
                                                </div>
                                                <div>
                                                    <label class="tw-block tw-text-xs tw-text-slate-500 tw-mb-1">Banque <span class="tw-text-red-500">*</span></label>
                                                    <input name="banque_cheque" id="banque_cheque" class="form-control" value="{{ old('banque_cheque') }}" type="text" placeholder="Nom de la banque">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- BPC field --}}
                                    <div id="bpc_field" class="sm:tw-col-span-2" style="display:none;">
                                        <div class="tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-xl tw-p-4">
                                            <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-3">
                                                <i class="fas fa-file-invoice tw-mr-1.5 tw-text-slate-400"></i>Bon de prise en charge
                                            </p>
                                            <div>
                                                <label class="tw-block tw-text-xs tw-text-slate-500 tw-mb-1">Émetteur du bon <span class="tw-text-red-500">*</span></label>
                                                <input name="emetteur_bpc" id="emetteur_bpc" class="form-control" value="{{ old('emetteur_bpc') }}" type="text" placeholder="Nom de l'organisme émetteur">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tw-border-t tw-border-slate-100 tw-mb-8"></div>

                            {{-- ── Section 4: Insurance ─────────────────── --}}
                            <div class="tw-mb-8">
                                <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-violet-100 tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-shield-alt tw-text-violet-600 tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations d'assurance</h3>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div>
                                        <label for="assurance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Assurance</label>
                                        <input name="assurance" id="assurance" class="form-control" value="{{ old('assurance') }}" type="text" placeholder="Nom de l'assurance (si assuré)">
                                    </div>
                                    <div>
                                        <label for="numero_assurance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Numéro d'assurance</label>
                                        <input name="numero_assurance" id="numero_assurance" class="form-control" value="{{ old('numero_assurance') }}" type="text" placeholder="N° d'assurance (si assuré)">
                                    </div>
                                    <div class="sm:tw-col-span-2">
                                        <label for="prise_en_charge" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Taux de prise en charge</label>
                                        <div class="input-group tw-w-48">
                                            <select class="form-select" name="prise_en_charge" id="prise_en_charge" required>
                                                @foreach(range(0, 100) as $taux)
                                                <option {{ old('prise_en_charge') == $taux ? 'selected' : '' }}>{{ $taux }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-text tw-bg-primary-700 tw-text-white">
                                                <i class="fas fa-percent"></i>
                                            </span>
                                        </div>
                                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Pourcentage de prise en charge par l'assurance</p>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Action buttons ───────────────────────── --}}
                            <div class="tw-flex tw-items-center tw-justify-between tw-pt-5 tw-border-t tw-border-slate-100">
                                <a href="{{ route('patients.index') }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-5 tw-py-2.5 tw-rounded-xl tw-border tw-border-slate-200 tw-transition-all tw-duration-150 tw-no-underline">
                                    <i class="fas fa-times tw-text-xs"></i>Annuler
                                </a>
                                <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-emerald-600 hover:tw-bg-emerald-700 tw-text-white tw-text-sm tw-font-semibold tw-px-6 tw-py-2.5 tw-rounded-xl tw-shadow-md tw-transition-all tw-duration-150 tw-border-0 tw-cursor-pointer">
                                    <i class="fas fa-save tw-text-xs"></i>Enregistrer la visite
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
waitForjQuery(function() {
    $(document).ready(function() {
        $('#patient_id').select2({
            placeholder: 'Tapez pour rechercher un patient...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("patient-visits.search-patients") }}',
                dataType: 'json',
                delay: 300,
                data: function (params) { return { q: params.term }; },
                processResults: function (data) { return { results: data.results }; },
                cache: true
            },
            templateResult: formatPatient,
            templateSelection: formatPatientSelection,
            escapeMarkup: function(markup) { return markup; }
        });

        function formatPatient(patient) {
            if (patient.loading) return 'Recherche en cours...';
            if (!patient.id) return patient.text;
            return '<div><span class="badge-dossier">CMCU-' + patient.numero_dossier + '</span><span class="patient-name">' + patient.name + ' ' + patient.prenom + '</span></div>';
        }

        function formatPatientSelection(patient) {
            if (!patient.id) return patient.text;
            if (patient.numero_dossier) {
                $('#patient_display_name').text('CMCU-' + patient.numero_dossier + ' | ' + patient.name + ' ' + patient.prenom);
                $('#selected_patient_info').show();
            }
            return 'CMCU-' + patient.numero_dossier + ' | ' + patient.name + ' ' + patient.prenom;
        }

        $('#patient_id').on('select2:select', function (e) {
            var data = e.params.data;
            $('#patient_display_name').text('CMCU-' + data.numero_dossier + ' | ' + data.name + ' ' + data.prenom);
            $('#selected_patient_info').show();
        });
        $('#patient_id').on('select2:clear', function () { $('#selected_patient_info').hide(); });

        @if(isset($preselectedPatient) && $preselectedPatient)
            $('#patient_display_name').text('CMCU-{{ $preselectedPatient->numero_dossier }} | {{ $preselectedPatient->name }} {{ $preselectedPatient->prenom }}');
            $('#selected_patient_info').show();
        @endif
    });
});


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

    // Sécurité DOM: éviter les erreurs si certains éléments n'existent pas
    const safeEl = (el) => (el ? el : {style: {display: 'none'}, classList: {add(){}, remove(){}}, addEventListener(){}, setAttribute(){}, removeAttribute(){}, value: '', textContent: ''});
    const _motifSelect = motifSelect ? motifSelect : safeEl(null);
    const _labelDetailsMotif = labelDetailsMotif ? labelDetailsMotif : safeEl(null);
    const _detailsConsultation = detailsConsultation ? detailsConsultation : safeEl(null);
    const _detailsActe = detailsActe ? detailsActe : safeEl(null);
    const _detailsAutres = detailsAutres ? detailsAutres : safeEl(null);
    const _detailsHidden = detailsHidden ? detailsHidden : safeEl(null);
    const _examenDisplay = examenDisplay ? examenDisplay : safeEl(null);
    const _examenDisplayText = examenDisplayText ? examenDisplayText : safeEl(null);
    const _btnReopenModal = btnReopenModal ? btnReopenModal : safeEl(null);


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
        [_detailsConsultation, _detailsActe, _detailsAutres].forEach(function (el) {
            if (el && el.style) el.style.display = 'none';
            if (el && el.removeAttribute) el.removeAttribute('required');
        });
        // Cacher aussi la zone d'affichage examen + bouton modifier
        if (_examenDisplay && _examenDisplay.style) _examenDisplay.style.display = 'none';
        if (_btnReopenModal && _btnReopenModal.classList) _btnReopenModal.classList.add('tw-hidden');
        // Fermer le modal si on change de motif
        if (modal) modal.classList.add('tw-hidden');
    }


    function handleMotifChange() {
        hideAllDetailsFields();
        var val = motifSelect && motifSelect.value ? motifSelect.value : '';

        switch (val) {
            case 'Consultation':
                if (labelDetailsMotif) labelDetailsMotif.innerHTML = 'Type de consultation <span class="tw-text-red-500">*</span>';
                if (detailsConsultation) {
                    detailsConsultation.style.display = 'block';
                    detailsConsultation.setAttribute('required', 'required');
                }
                break;

            case 'Acte':
                if (labelDetailsMotif) labelDetailsMotif.innerHTML = "Type d'acte <span class='tw-text-red-500'>*</span>";
                if (detailsActe) {
                    detailsActe.style.display = 'block';
                    detailsActe.setAttribute('required', 'required');
                }
                break;

            case 'Examen':
                if (labelDetailsMotif) labelDetailsMotif.innerHTML = "Type d'examen <span class='tw-text-red-500'>*</span>";
                if (examenDisplay) examenDisplay.style.display = 'block';
                if (modal) modal.classList.remove('tw-hidden');
                break;

            case 'Autres':
                if (labelDetailsMotif) labelDetailsMotif.innerHTML = 'Détails motif <span class="tw-text-red-500">*</span>';
                if (detailsAutres) {
                    detailsAutres.style.display = 'block';
                    detailsAutres.setAttribute('required', 'required');
                }
                break;

            default:
                if (labelDetailsMotif) labelDetailsMotif.innerHTML = 'Détails motif <span class="tw-text-red-500">*</span>';
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

    const patientFormEl = document.getElementById('patientForm');
    if (patientFormEl) {
        patientFormEl.addEventListener('submit', function (e) {
            syncDetailsMotif(); // S'assurer que le hidden est à jour

            if (!_detailsHidden || !_detailsHidden.value === undefined || !String(_detailsHidden.value).trim()) {
                e.preventDefault();
                alert('Veuillez sélectionner ou saisir les détails du motif.');
                return false;
            }
        });
    }


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