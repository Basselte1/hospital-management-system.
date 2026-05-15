@extends('layouts.admin')

@section('title', 'CMCU | Ajouter un dossier patient')

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
                                        <label for="medecin_r" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Médecin traitant <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select name="medecin_r" id="medecin_r" required
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                            <option value="">Sélectionnez un médecin</option>
                                            @foreach ($users as $specialite => $medecins)
                                            <optgroup label="{{ $specialite }}">
                                                @foreach ($medecins as $user)
                                                @if(is_object($user))
                                                <option value="{{ $user->name }} {{ $user->prenom }}"
                                                    {{ old("medecin_r") == "$user->name $user->prenom" ? "selected" : "" }}>
                                                    Dr. {{ $user->name }} {{ $user->prenom }}
                                                </option>
                                                @endif
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
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
                                            <option value="Acte" {{ old('motif') == 'Acte' ? 'selected' : '' }}>Acte</option>
                                            <option value="Examen" {{ old('motif') == 'Examen' ? 'selected' : '' }}>Examen</option>
                                            <option value="Autres" {{ old('motif') == 'Autres' ? 'selected' : '' }}>Autres</option>
                                        </select>
                                    </div>

                                    {{-- Détails motif --}}
                                    <div>
                                        <label id="label_details_motif" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Détails motif <span class="tw-text-red-500">*</span>
                                        </label>

                                        <select class="motif-field tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all"
                                                id="details_motif_consultation" name="details_motif_consultation" style="display:none;">
                                            <option value="">-- Sélectionnez une consultation --</option>
                                            <option value="Consultation générale" {{ old('details_motif') == 'Consultation ' ? 'selected' : '' }}>Consultation</option>
                                    
                                            <option value="Consultation de suivi" {{ old('details_motif') == 'Consultation de suivi' ? 'selected' : '' }}>Consultation de suivi</option>
                                           
                                        </select>

                                        <select class="motif-field tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all"
                                                id="details_motif_acte" name="details_motif_acte" style="display:none;">
                                            <option value="">-- Sélectionnez un acte --</option>
                                            <optgroup label="Actes Urologiques">
                                                <option value="Cystoscopie" {{ old('details_motif') == 'Cystoscopie' ? 'selected' : '' }}>Cystoscopie</option>
                                                <option value="Biopsie prostatique" {{ old('details_motif') == 'Biopsie prostatique' ? 'selected' : '' }}>Biopsie prostatique</option>
                                                <option value="Circoncision" {{ old('details_motif') == 'Circoncision' ? 'selected' : '' }}>Circoncision</option>
                                                <option value="Vasectomie" {{ old('details_motif') == 'Vasectomie' ? 'selected' : '' }}>Vasectomie</option>
                                                <option value="Lithotripsie" {{ old('details_motif') == 'Lithotripsie' ? 'selected' : '' }}>Lithotripsie</option>
                                                <option value="Urétéroscopie" {{ old('details_motif') == 'Urétéroscopie' ? 'selected' : '' }}>Urétéroscopie</option>
                                                <option value="Néphrectomie" {{ old('details_motif') == 'Néphrectomie' ? 'selected' : '' }}>Néphrectomie</option>
                                                <option value="Prostatectomie" {{ old('details_motif') == 'Prostatectomie' ? 'selected' : '' }}>Prostatectomie</option>
                                                <option value="Cure d'hydrocèle" {{ old('details_motif') == "Cure d'hydrocèle" ? 'selected' : '' }}>Cure d'hydrocèle</option>
                                                <option value="Pose de sonde JJ" {{ old('details_motif') == 'Pose de sonde JJ' ? 'selected' : '' }}>Pose de sonde JJ</option>
                                            </optgroup>
                                            <optgroup label="Actes Chirurgicaux Généraux">
                                                <option value="Laparotomie exploratrice" {{ old('details_motif') == 'Laparotomie exploratrice' ? 'selected' : '' }}>Laparotomie exploratrice</option>
                                                <option value="Appendicectomie" {{ old('details_motif') == 'Appendicectomie' ? 'selected' : '' }}>Appendicectomie</option>
                                                <option value="Cholécystectomie" {{ old('details_motif') == 'Cholécystectomie' ? 'selected' : '' }}>Cholécystectomie</option>
                                                <option value="Hernioplastie" {{ old('details_motif') == 'Hernioplastie' ? 'selected' : '' }}>Hernioplastie</option>
                                                <option value="Cure de varicocèle" {{ old('details_motif') == 'Cure de varicocèle' ? 'selected' : '' }}>Cure de varicocèle</option>
                                            </optgroup>
                                            <optgroup label="Actes Endoscopiques">
                                                <option value="Endoscopie digestive" {{ old('details_motif') == 'Endoscopie digestive' ? 'selected' : '' }}>Endoscopie digestive</option>
                                                <option value="Coloscopie" {{ old('details_motif') == 'Coloscopie' ? 'selected' : '' }}>Coloscopie</option>
                                                <option value="Gastroscopie" {{ old('details_motif') == 'Gastroscopie' ? 'selected' : '' }}>Gastroscopie</option>
                                            </optgroup>
                                            <option value="Autre acte" {{ old('details_motif') == 'Autre acte' ? 'selected' : '' }}>Autre acte (à préciser)</option>
                                        </select>

                                        <select class="motif-field tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all"
                                                id="details_motif_examen" name="details_motif_examen" style="display:none;">
                                            <option value="">-- Sélectionnez un examen --</option>
                                            <optgroup label="Examens de Biologie">
                                                <option value="Hématologie" {{ old('details_motif') == 'Hématologie' ? 'selected' : '' }}>Hématologie (NFS, Hémogramme)</option>
                                                <option value="Biochimie" {{ old('details_motif') == 'Biochimie' ? 'selected' : '' }}>Biochimie (Glycémie, Créatinine, Urée)</option>
                                                <option value="Hémostase" {{ old('details_motif') == 'Hémostase' ? 'selected' : '' }}>Hémostase (TP, TCA, INR)</option>
                                                <option value="Hormonologie" {{ old('details_motif') == 'Hormonologie' ? 'selected' : '' }}>Hormonologie (PSA, Testostérone)</option>
                                                <option value="Marqueurs tumoraux" {{ old('details_motif') == 'Marqueurs tumoraux' ? 'selected' : '' }}>Marqueurs tumoraux</option>
                                                <option value="Bactériologie" {{ old('details_motif') == 'Bactériologie' ? 'selected' : '' }}>Bactériologie (ECBU, Hémoculture)</option>
                                                <option value="Sérologie" {{ old('details_motif') == 'Sérologie' ? 'selected' : '' }}>Sérologie (VIH, Hépatites)</option>
                                                <option value="Analyse d'urines" {{ old('details_motif') == "Analyse d'urines" ? 'selected' : '' }}>Analyse d'urines</option>
                                                <option value="Spermiologie" {{ old('details_motif') == 'Spermiologie' ? 'selected' : '' }}>Spermiologie (Spermogramme)</option>
                                            </optgroup>
                                            <optgroup label="Examens d'Imagerie">
                                                <option value="Radiographie" {{ old('details_motif') == 'Radiographie' ? 'selected' : '' }}>Radiographie (Radio standard)</option>
                                                <option value="Échographie" {{ old('details_motif') == 'Échographie' ? 'selected' : '' }}>Échographie (Abdominale, Pelvienne, Rénale)</option>
                                                <option value="Scanner" {{ old('details_motif') == 'Scanner' ? 'selected' : '' }}>Scanner (TDM, CT-Scan)</option>
                                                <option value="IRM" {{ old('details_motif') == 'IRM' ? 'selected' : '' }}>IRM (Imagerie par Résonance Magnétique)</option>
                                                <option value="Scintigraphie" {{ old('details_motif') == 'Scintigraphie' ? 'selected' : '' }}>Scintigraphie</option>
                                                <option value="Échographie Doppler" {{ old('details_motif') == 'Échographie Doppler' ? 'selected' : '' }}>Échographie Doppler</option>
                                            </optgroup>
                                            <optgroup label="Examens Radiologiques spécifiques">
                                                <option value="UIV" {{ old('details_motif') == 'UIV' ? 'selected' : '' }}>UIV (Urographie Intraveineuse)</option>
                                                <option value="Urétrographie" {{ old('details_motif') == 'Urétrographie' ? 'selected' : '' }}>Urétrographie</option>
                                                <option value="Cystographie" {{ old('details_motif') == 'Cystographie' ? 'selected' : '' }}>Cystographie</option>
                                            </optgroup>
                                            <option value="Autre examen" {{ old('details_motif') == 'Autre examen' ? 'selected' : '' }}>Autre examen (à préciser)</option>
                                        </select>

                                        <input type="text"
                                               class="motif-field tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all"
                                               id="details_motif_autres" name="details_motif_autres"
                                               placeholder="Précisez le motif"
                                               value="{{ old('details_motif') }}"
                                               style="display:none;">

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
                                                <option value="orange money" {{ old('mode_paiement') == 'orange money' ? 'selected' : '' }}>Orange Money</option>
                                                <option value="mtn mobile money" {{ old('mode_paiement') == 'mtn mobile money' ? 'selected' : '' }}>MTN Mobile Money</option>
                                            </optgroup>
                                            <optgroup label="Autres moyens">
                                                <option value="espèce" selected>Espèce</option>
                                                <option value="chèque" {{ old('mode_paiement') == 'chèque' ? 'selected' : '' }}>Chèque</option>
                                                <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                                                <option value="bon de prise en charge" {{ old('mode_paiement') == 'bon de prise en charge' ? 'selected' : '' }}>Bon de prise en charge</option>
                                                <option value="autre" {{ old('mode_paiement') == 'autre' ? 'selected' : '' }}>Autre</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    {{-- Montant total --}}
                                    <div>
                                        <label for="montant" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Montant total <span class="tw-text-red-500">*</span>
                                        </label>
                                        <div class="tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE] focus-within:tw-border-[#1D4ED8] tw-transition-all">
                                            <input name="montant" id="montant" type="number" min="0" step="0.01" required
                                                   value="{{ old('montant', 0) }}"
                                                   class="tw-flex-1 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-border-0 focus:tw-outline-none focus:tw-bg-white tw-transition-all">
                                            <span class="tw-bg-slate-100 tw-px-3 tw-flex tw-items-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-border-l tw-border-slate-200">FCFA</span>
                                        </div>
                                    </div>

                                    {{-- Avance --}}
                                    <div>
                                        <label for="avance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Avance <span class="tw-text-red-500">*</span>
                                        </label>
                                        <div class="tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE] focus-within:tw-border-[#1D4ED8] tw-transition-all">
                                            <input name="avance" id="avance" type="number" min="0" step="0.01" required
                                                   value="{{ old('avance', 0) }}"
                                                   class="tw-flex-1 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-border-0 focus:tw-outline-none focus:tw-bg-white tw-transition-all">
                                            <span class="tw-bg-slate-100 tw-px-3 tw-flex tw-items-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-border-l tw-border-slate-200">FCFA</span>
                                        </div>
                                    </div>

                                    {{-- Champs conditionnels: Chèque --}}
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

                                    {{-- Champs conditionnels: Bon de prise en charge --}}
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
                                    {{-- Assurance --}}
                                    <div>
                                        <label for="assurance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Assurance
                                        </label>
                                        <input name="assurance" id="assurance" type="text"
                                               value="{{ old('assurance') }}"
                                               placeholder="Nom de l'assurance (si assuré)"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>

                                    {{-- Numéro d'assurance --}}
                                    <div>
                                        <label for="numero_assurance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Numéro d'assurance
                                        </label>
                                        <input name="numero_assurance" id="numero_assurance" type="text"
                                               value="{{ old('numero_assurance') }}"
                                               placeholder="N° d'assurance (si assuré)"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>

                                    {{-- Taux de prise en charge --}}
                                    <div class="sm:tw-col-span-2">
                                        <label for="prise_en_charge" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                            Taux de prise en charge
                                        </label>
                                        <div class="tw-flex tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200 focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE] focus-within:tw-border-[#1D4ED8] tw-transition-all">
                                            <select name="prise_en_charge" id="prise_en_charge" required
                                                    class="tw-flex-1 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-border-0 focus:tw-outline-none focus:tw-bg-white tw-transition-all">
                                                @foreach(range(0, 100) as $taux)
                                                <option {{ old('prise_en_charge') == $taux ? 'selected' : '' }}>{{ $taux }}</option>
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
     * Système de sélection en cascade pour les motifs
     */
    document.addEventListener('DOMContentLoaded', function() {
        const motifSelect = document.getElementById('motif');
        const labelDetailsMotif = document.getElementById('label_details_motif');

        const detailsConsultation = document.getElementById('details_motif_consultation');
        const detailsActe = document.getElementById('details_motif_acte');
        const detailsExamen = document.getElementById('details_motif_examen');
        const detailsAutres = document.getElementById('details_motif_autres');
        const detailsHidden = document.getElementById('details_motif_hidden');

        function hideAllDetailsFields() {
            detailsConsultation.style.display = 'none';
            detailsActe.style.display = 'none';
            detailsExamen.style.display = 'none';
            detailsAutres.style.display = 'none';
            detailsConsultation.removeAttribute('required');
            detailsActe.removeAttribute('required');
            detailsExamen.removeAttribute('required');
            detailsAutres.removeAttribute('required');
        }

        function handleMotifChange() {
            const motifValue = motifSelect.value;
            hideAllDetailsFields();

            switch(motifValue) {
                case 'Consultation':
                    labelDetailsMotif.innerHTML = 'Type de consultation <span class="tw-text-red-500">*</span>';
                    detailsConsultation.style.display = 'block';
                    detailsConsultation.setAttribute('required', 'required');
                    break;
                case 'Acte':
                    labelDetailsMotif.innerHTML = 'Type d\'acte <span class="tw-text-red-500">*</span>';
                    detailsActe.style.display = 'block';
                    detailsActe.setAttribute('required', 'required');
                    break;
                case 'Examen':
                    labelDetailsMotif.innerHTML = 'Type d\'examen <span class="tw-text-red-500">*</span>';
                    detailsExamen.style.display = 'block';
                    detailsExamen.setAttribute('required', 'required');
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

        function syncDetailsMotif() {
            const motifValue = motifSelect.value;
            let finalValue = '';
            switch(motifValue) {
                case 'Consultation': finalValue = detailsConsultation.value; break;
                case 'Acte':         finalValue = detailsActe.value;         break;
                case 'Examen':       finalValue = detailsExamen.value;       break;
                case 'Autres':       finalValue = detailsAutres.value;       break;
            }
            detailsHidden.value = finalValue;
        }

        motifSelect.addEventListener('change', handleMotifChange);
        detailsConsultation.addEventListener('change', syncDetailsMotif);
        detailsActe.addEventListener('change', syncDetailsMotif);
        detailsExamen.addEventListener('change', syncDetailsMotif);
        detailsAutres.addEventListener('input', syncDetailsMotif);

        document.getElementById('patientForm').addEventListener('submit', function(e) {
            syncDetailsMotif();
            if (!detailsHidden.value) {
                e.preventDefault();
                alert('Veuillez sélectionner ou saisir les détails du motif.');
                return false;
            }
        });

        @if(old('motif'))
            handleMotifChange();
            syncDetailsMotif();
        @endif
    });

    /**
     * Gérer l'affichage des champs selon le mode de paiement
     */
    document.addEventListener('DOMContentLoaded', function() {
        const modePaiementSelect = document.getElementById('mode_paiement');
        const chequeFields = document.getElementById('cheque_fields');
        const bpcField = document.getElementById('bpc_field');

        function togglePaymentFields() {
            const modePaiement = modePaiementSelect.value;

            chequeFields.style.display = 'none';
            bpcField.style.display = 'none';

            if (modePaiement !== 'chèque') {
                document.getElementById('num_cheque').value = '';
                document.getElementById('emetteur_cheque').value = '';
                document.getElementById('banque_cheque').value = '';
                document.getElementById('num_cheque').removeAttribute('required');
                document.getElementById('emetteur_cheque').removeAttribute('required');
                document.getElementById('banque_cheque').removeAttribute('required');
            }

            if (modePaiement !== 'bon de prise en charge') {
                document.getElementById('emetteur_bpc').value = '';
                document.getElementById('emetteur_bpc').removeAttribute('required');
            }

            if (modePaiement === 'chèque') {
                chequeFields.style.display = 'block';
                document.getElementById('num_cheque').setAttribute('required', 'required');
                document.getElementById('emetteur_cheque').setAttribute('required', 'required');
                document.getElementById('banque_cheque').setAttribute('required', 'required');
            } else if (modePaiement === 'bon de prise en charge') {
                bpcField.style.display = 'block';
                document.getElementById('emetteur_bpc').setAttribute('required', 'required');
            }
        }

        modePaiementSelect.addEventListener('change', togglePaymentFields);
        togglePaymentFields();
    });
</script>
@stop