@extends('layouts.admin')
@section('title', 'CMCU | Saisie des résultats — ' . ($laboratoire->numero_bon ?? ''))

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Breadcrumb --}}
            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="{{ route('laboratoire.index') }}" class="hover:tw-text-[#1D4ED8] tw-no-underline">Laboratoire</a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">Saisie résultats — {{ $laboratoire->numero_bon }}</span>
            </nav>

            {{-- Phase indicator --}}
            {{-- <div class="tw-flex tw-items-center tw-gap-3 tw-mb-6">
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-green-100 tw-text-green-700 tw-rounded-xl tw-text-sm tw-font-semibold">
                    <i class="fas fa-check tw-text-[8px]"></i>
                    Phase 1 — Pré-analytique (terminée)
                </div>
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-rounded-xl tw-text-sm tw-font-semibold tw-shadow-sm">
                    <i class="fas fa-circle tw-text-[8px]"></i>
                    Phase 2 — Analytique
                </div>
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-rounded-xl tw-text-sm tw-font-semibold tw-shadow-sm">
                    <i class="fas fa-circle tw-text-[8px]"></i>
                    Phase 3 — Post-analytique
                </div>
            </div> --}}

            {{-- Patient summary banner --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-px-6 tw-py-4 tw-flex tw-items-center tw-gap-4 tw-mb-5">
                <div class="tw-w-11 tw-h-11 tw-rounded-full tw-bg-teal-200 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                    <span class="tw-font-bold tw-text-teal-700 tw-text-base">{{ strtoupper(substr($laboratoire->patient->prenom ?? $laboratoire->patient->name ?? '?', 0, 1)) }}</span>
                </div>
                <div class="tw-flex-1">
                    <p class="tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-tight">
                        {{ $laboratoire->patient->prenom ?? '' }} {{ strtoupper($laboratoire->patient->name ?? '—') }}
                    </p>
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-0">N° {{ $laboratoire->patient->numero_dossier ?? '—' }}</p>
                </div>
                <div class="tw-text-right tw-shrink-0">
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Bon de laboratoire</p>
                    <p class="tw-font-mono tw-font-bold tw-text-[#1D4ED8] tw-text-sm tw-mb-0">{{ $laboratoire->numero_bon }}</p>
                </div>
                <span class="tw-ml-4 tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-semibold {{ $laboratoire->statut_color }}">
                    {{ $laboratoire->statut_label }}
                </span>
            </div>

            <form method="POST" action="{{ route('laboratoire.update', $laboratoire->id) }}">
                @csrf
                @method('PATCH')

                

                 {{-- ══ CARD 2 : Prélèvement ══════════════════════════════ --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">

                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-vial tw-text-indigo-500 tw-text-sm"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Prélèvement du spécimen</h2>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">Étapes 5 & 7 — Collection et validation du spécimen</p>
                        </div>
                    </div>

                    <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-5">

                        <div>
                            <label for="date_prelevement" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Date du prélèvement</label>
                            <input type="datetime-local" id="date_prelevement" name="date_prelevement"
                                   value="{{ old('date_prelevement', now()->format('Y-m-d\TH:i')) }}"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>

                        <div>
                            <label for="technicien_prelevement" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Technicien préleveur</label>
                            <input type="text" id="technicien_prelevement" name="technicien_prelevement"
                                   value="{{ old('technicien_prelevement', Auth::user()->prenom . ' ' . Auth::user()->name) }}"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>

                        <div>
                            <label for="tube_type" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Type de tube / contenant</label>
                            <select id="tube_type" name="tube_type"
                                    class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                <option value="">— Sélectionner —</option>
                                @foreach(['EDTA (tube violet)', 'Héparine (tube vert)', 'Tube sec (rouge/jaune)', 'Citrate (tube bleu)', 'Fluo-oxalate (tube gris)', 'Urine stérile', 'Selles (coproculture)', 'LCR', 'Frottis (lame)', 'Autre'] as $tube)
                                <option value="{{ $tube }}" {{ old('tube_type') === $tube ? 'selected' : '' }}>{{ $tube }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="site_prelevement" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Site de prélèvement</label>
                            <input type="text" id="site_prelevement" name="site_prelevement"
                                   value="{{ old('site_prelevement') }}"
                                   placeholder="Ex: Veine cubitale gauche, 2ème jet urine…"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>

                        {{-- Statut spécimen --}}
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-2">Statut du spécimen</label>
                            <div class="tw-flex tw-gap-4">
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="radio" name="statut_specimen" value="accepté"
                                           {{ old('statut_specimen', 'accepté') === 'accepté' ? 'checked' : '' }}
                                           class="tw-accent-[#1D4ED8]" onchange="toggleRejectField(this)">
                                    <span class="tw-text-sm tw-text-green-700 tw-font-medium">✓ Accepté</span>
                                </label>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="radio" name="statut_specimen" value="rejeté"
                                           {{ old('statut_specimen') === 'rejeté' ? 'checked' : '' }}
                                           class="tw-accent-red-500" onchange="toggleRejectField(this)">
                                    <span class="tw-text-sm tw-text-red-600 tw-font-medium">✗ Rejeté</span>
                                </label>
                            </div>
                        </div>

                        {{-- Motif rejet — uses max-height animation instead of display:none
                             so it slides in/out without causing layout shifts --}}
                        <div id="motif-rejet-wrap"
                             class="tw-overflow-hidden tw-transition-all tw-duration-200"
                             style="{{ old('statut_specimen') === 'rejeté' ? 'max-height:120px;opacity:1;' : 'max-height:0;opacity:0;pointer-events:none;' }}">
                            <label for="motif_rejet" class="tw-block tw-text-sm tw-font-medium tw-text-red-600 tw-mb-1.5">
                                Motif du rejet <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="text" id="motif_rejet" name="motif_rejet"
                                   value="{{ old('motif_rejet') }}"
                                   placeholder="Ex: Hémolyse, volume insuffisant, tube non étiqueté…"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-red-300 tw-rounded-xl focus:tw-outline-none focus:tw-border-red-500">
                        </div>

                    </div>
                </div>


                {{-- ══ CARD: Instrument & CQI ════════════════════════════ --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">

                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-sky-100 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-microscope tw-text-sky-600 tw-text-sm"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Instrument & Contrôle Qualité (CQI)</h2>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">Traçabilité ISO 15189 — étape 10</p>
                        </div>
                    </div>

                    <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-5">
                        <div>
                            <label for="instrument_utilise" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Instrument utilisé</label>
                            <input type="text" id="instrument_utilise" name="instrument_utilise"
                                   value="{{ old('instrument_utilise', $laboratoire->instrument_utilise) }}"
                                   placeholder="Ex: Sysmex XN-350, Cobas 6000…"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>
                        <div>
                            <label for="lot_reactif" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">N° de lot réactif</label>
                            <input type="text" id="lot_reactif" name="lot_reactif"
                                   value="{{ old('lot_reactif', $laboratoire->lot_reactif) }}"
                                   placeholder="Ex: LOT2024-0481"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-2">Statut CQI</label>
                            <div class="tw-flex tw-gap-3">
                                @foreach(['conforme' => ['label' => 'Conforme', 'color' => 'tw-text-green-700'], 'non_conforme' => ['label' => 'Non conforme', 'color' => 'tw-text-red-600'], 'non_effectue' => ['label' => 'Non effectué', 'color' => 'tw-text-slate-500']] as $val => $cfg)
                                <label class="tw-flex tw-items-center tw-gap-1.5 tw-cursor-pointer">
                                    <input type="radio" name="cqi_status" value="{{ $val }}"
                                           {{ old('cqi_status', $laboratoire->cqi_status ?? 'non_effectue') === $val ? 'checked' : '' }}
                                           class="tw-accent-[#1D4ED8]">
                                    <span class="tw-text-xs {{ $cfg['color'] }} tw-font-medium">{{ $cfg['label'] }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="sm:tw-col-span-3">
                            <label for="cqi_note" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Note CQI (déviation, action corrective…)</label>
                            <input type="text" id="cqi_note" name="cqi_note"
                                   value="{{ old('cqi_note', $laboratoire->cqi_note) }}"
                                   placeholder="Facultatif — décrivez toute déviation ou action corrective"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>
                    </div>
                </div>

                {{-- ══ RESULT TABLES per active section ════════════════ --}}
                @php
                    $activeSections = [];
                    foreach(array_keys($sections) as $sk) {
                        if (!empty($laboratoire->$sk)) {
                            $activeSections[] = $sk;
                        }
                    }
                    // Fallback: ensure $prescribedTestsBySection is always defined
                    $prescribedTestsBySection = $prescribedTestsBySection ?? [];
                @endphp

                @forelse($activeSections as $sectionKey)
                @php
                    $sectionLabel = $sections[$sectionKey] ?? ucfirst($sectionKey);
                    $existingResults = $laboratoire->getAttribute("{$sectionKey}_resultats") ?? [];
                    $sectionTests   = $testsBySection[$sectionKey] ?? [];
                @endphp

                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">

                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between tw-gap-3">
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-vial tw-text-indigo-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">{{ strtoupper($sectionLabel) }}</h2>
                        </div>
                        <button type="button"
                                onclick="addResultRow('{{ $sectionKey }}')"
                                class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-bg-indigo-50 tw-text-indigo-700 tw-text-xs tw-font-semibold tw-rounded-lg hover:tw-bg-indigo-100 tw-transition-colors tw-border tw-border-indigo-200 tw-cursor-pointer">
                            <i class="fas fa-plus tw-text-[9px]"></i>
                            Ajouter une ligne
                        </button>
                    </div>

                    <div class="tw-overflow-x-auto">
                        {{-- Prescription badge — only shown when tests were pre-filled from a Prescription --}}
                        @if(!empty($prescribedTestsBySection[$sectionKey]) && empty($existingResults))
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-blue-50 tw-border-b tw-border-blue-100 tw-text-xs tw-text-blue-700">
                            <i class="fas fa-file-medical tw-shrink-0"></i>
                            <span>Tests pré-remplis depuis la prescription du médecin — saisissez les résultats ci-dessous.</span>
                        </div>
                        @endif
                        <table class="tw-w-full tw-text-sm">
                            <thead class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <tr>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[30%]">Examen / Test</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[15%]">Résultat</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[10%]">Unité</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[10%]">Réf. min</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[10%]">Réf. max</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[15%]">Interprétation</th>
                                    <th class="tw-px-4 tw-py-2.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[10%]">Suppr.</th>
                                </tr>
                            </thead>
                            <tbody id="results-body-{{ $sectionKey }}" class="tw-divide-y tw-divide-slate-50">

                                @if(!empty($existingResults))
                                    @foreach($existingResults as $i => $row)
                                    <tr class="result-row hover:tw-bg-slate-50">
                                        <td class="tw-px-4 tw-py-2">
                                            <input list="tests-{{ $sectionKey }}"
                                                   name="resultats_{{ $sectionKey }}[{{ $i }}][test]"
                                                   value="{{ $row['test'] ?? '' }}"
                                                   placeholder="Nom du test"
                                                   class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                        </td>
                                        <td class="tw-px-4 tw-py-2">
                                            <input type="text" name="resultats_{{ $sectionKey }}[{{ $i }}][valeur]"
                                                   value="{{ $row['valeur'] ?? '' }}" placeholder="Valeur"
                                                   class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                        </td>
                                        <td class="tw-px-4 tw-py-2">
                                            <input type="text" name="resultats_{{ $sectionKey }}[{{ $i }}][unite]"
                                                   value="{{ $row['unite'] ?? '' }}" placeholder="g/L"
                                                   class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                        </td>
                                        <td class="tw-px-4 tw-py-2">
                                            <input type="text" name="resultats_{{ $sectionKey }}[{{ $i }}][ref_min]"
                                                   value="{{ $row['ref_min'] ?? '' }}" placeholder="0"
                                                   class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                        </td>
                                        <td class="tw-px-4 tw-py-2">
                                            <input type="text" name="resultats_{{ $sectionKey }}[{{ $i }}][ref_max]"
                                                   value="{{ $row['ref_max'] ?? '' }}" placeholder="∞"
                                                   class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                        </td>
                                        <td class="tw-px-4 tw-py-2">
                                            <select name="resultats_{{ $sectionKey }}[{{ $i }}][flag]"
                                                    class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                                <option value="normal"   {{ ($row['flag'] ?? 'normal') === 'normal'   ? 'selected' : '' }}>Normal</option>
                                                <option value="bas"      {{ ($row['flag'] ?? '') === 'bas'      ? 'selected' : '' }}>▼ Bas</option>
                                                <option value="eleve"    {{ ($row['flag'] ?? '') === 'eleve'    ? 'selected' : '' }}>▲ Élevé</option>
                                                <option value="critique" {{ ($row['flag'] ?? '') === 'critique' ? 'selected' : '' }}>⚠ Critique</option>
                                            </select>
                                        </td>
                                        <td class="tw-px-4 tw-py-2 tw-text-center">
                                            <button type="button" onclick="removeRow(this)"
                                                    class="tw-w-6 tw-h-6 tw-rounded tw-bg-red-50 tw-text-red-400 hover:tw-bg-red-500 hover:tw-text-white tw-transition-colors tw-border-0 tw-cursor-pointer tw-text-xs">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    @php
                                        // Pre-fill rows from the médecin's prescription if available,
                                        // otherwise fall back to a single blank starter row.
                                        $prescribedTests = $prescribedTestsBySection[$sectionKey] ?? [];
                                    @endphp

                                    @if(!empty($prescribedTests))
                                        {{-- ── One pre-filled row per prescribed test ── --}}
                                        {{-- The "Examen / Test" cell is locked (readonly + pill style)  --}}
                                        {{-- so the laborantin can see what was prescribed at a glance.  --}}
                                        {{-- All other cells remain editable.                            --}}
                                        @foreach($prescribedTests as $pi => $prescribedTest)
                                        <tr class="result-row hover:tw-bg-blue-50/40">
                                            <td class="tw-px-4 tw-py-2">
                                                {{-- Hidden real input keeps the name/value for form submission --}}
                                                <input type="hidden"
                                                       name="resultats_{{ $sectionKey }}[{{ $pi }}][test]"
                                                       value="{{ $prescribedTest }}">
                                                {{-- Visual readonly pill shows the prescribed test name --}}
                                                <span class="tw-flex tw-items-center tw-gap-1.5 tw-px-2.5 tw-py-1.5 tw-bg-blue-50 tw-border tw-border-blue-200 tw-rounded-lg tw-text-xs tw-font-medium tw-text-blue-800">
                                                    <i class="fas fa-flask-vial tw-text-[9px] tw-text-blue-400 tw-shrink-0"></i>
                                                    {{ $prescribedTest }}
                                                </span>
                                            </td>
                                            <td class="tw-px-4 tw-py-2">
                                                <input type="text"
                                                       name="resultats_{{ $sectionKey }}[{{ $pi }}][valeur]"
                                                       placeholder="Valeur"
                                                       class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                            </td>
                                            <td class="tw-px-4 tw-py-2">
                                                <input type="text"
                                                       name="resultats_{{ $sectionKey }}[{{ $pi }}][unite]"
                                                       placeholder="g/L"
                                                       class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                            </td>
                                            <td class="tw-px-4 tw-py-2">
                                                <input type="text"
                                                       name="resultats_{{ $sectionKey }}[{{ $pi }}][ref_min]"
                                                       placeholder="0"
                                                       class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                            </td>
                                            <td class="tw-px-4 tw-py-2">
                                                <input type="text"
                                                       name="resultats_{{ $sectionKey }}[{{ $pi }}][ref_max]"
                                                       placeholder="∞"
                                                       class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                            </td>
                                            <td class="tw-px-4 tw-py-2">
                                                <select name="resultats_{{ $sectionKey }}[{{ $pi }}][flag]"
                                                        class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                                    <option value="normal">Normal</option>
                                                    <option value="bas">▼ Bas</option>
                                                    <option value="eleve">▲ Élevé</option>
                                                    <option value="critique">⚠ Critique</option>
                                                </select>
                                            </td>
                                            <td class="tw-px-4 tw-py-2 tw-text-center">
                                                <button type="button" onclick="removeRow(this)"
                                                        class="tw-w-6 tw-h-6 tw-rounded tw-bg-red-50 tw-text-red-400 hover:tw-bg-red-500 hover:tw-text-white tw-transition-colors tw-border-0 tw-cursor-pointer tw-text-xs">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        {{-- No prescription found — blank starter row --}}
                                        <tr class="result-row">
                                            <td class="tw-px-4 tw-py-2">
                                                <input list="tests-{{ $sectionKey }}"
                                                       name="resultats_{{ $sectionKey }}[0][test]"
                                                       placeholder="Nom du test"
                                                       class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                            </td>
                                            <td class="tw-px-4 tw-py-2"><input type="text" name="resultats_{{ $sectionKey }}[0][valeur]" placeholder="Valeur" class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]"></td>
                                            <td class="tw-px-4 tw-py-2"><input type="text" name="resultats_{{ $sectionKey }}[0][unite]"  placeholder="g/L"   class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]"></td>
                                            <td class="tw-px-4 tw-py-2"><input type="text" name="resultats_{{ $sectionKey }}[0][ref_min]" placeholder="0"    class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]"></td>
                                            <td class="tw-px-4 tw-py-2"><input type="text" name="resultats_{{ $sectionKey }}[0][ref_max]" placeholder="∞"    class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]"></td>
                                            <td class="tw-px-4 tw-py-2">
                                                <select name="resultats_{{ $sectionKey }}[0][flag]"
                                                        class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                                    <option value="normal">Normal</option>
                                                    <option value="bas">▼ Bas</option>
                                                    <option value="eleve">▲ Élevé</option>
                                                    <option value="critique">⚠ Critique</option>
                                                </select>
                                            </td>
                                            <td class="tw-px-4 tw-py-2 tw-text-center"><button type="button" onclick="removeRow(this)" class="tw-w-6 tw-h-6 tw-rounded tw-bg-red-50 tw-text-red-400 hover:tw-bg-red-500 hover:tw-text-white tw-transition-colors tw-border-0 tw-cursor-pointer tw-text-xs"><i class="fas fa-times"></i></button></td>
                                        </tr>
                                    @endif
                                @endif

                            </tbody>
                        </table>
                    </div>

                    {{-- Quick-fill datalist --}}
                    <datalist id="tests-{{ $sectionKey }}">
                        @foreach($testsBySection[$sectionKey] ?? [] as $testName)
                        <option value="{{ $testName }}">
                        @endforeach
                    </datalist>

                    {{-- Antibiogramme (bacteriologie only) --}}
                    @if($sectionKey === 'bacteriologie')
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                        <label for="antibiogramme" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                            Antibiogramme
                        </label>
                        <textarea id="antibiogramme" name="antibiogramme" rows="3"
                                  placeholder="Germe isolé, antibiotiques testés, CMI, résistances…"
                                  class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">{{ old('antibiogramme', $laboratoire->antibiogramme) }}</textarea>
                    </div>
                    @endif

                </div>
                @empty
                <div class="tw-bg-amber-50 tw-border tw-border-amber-200 tw-rounded-2xl tw-px-5 tw-py-4 tw-flex tw-items-center tw-gap-3 tw-mb-5 tw-text-sm tw-text-amber-800">
                    <i class="fas fa-circle-info tw-text-amber-500 tw-shrink-0"></i>
                    Aucune section active sur ce bon. Revenez à l'étape de création pour sélectionner des sections.
                </div>
                @endforelse

                {{-- ══ CARD: Observations & Post-analytique ════════════ --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">

                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-green-100 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-clipboard-check tw-text-green-600 tw-text-sm"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Validation & Post-analytique</h2>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">Étapes 11-17 — interprétation, signature, remise</p>
                        </div>
                    </div>

                    <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-5">

                        <div class="sm:tw-col-span-2">
                            <label for="observations" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Observations & interprétation (biologiste)
                            </label>
                            <textarea id="observations" name="observations" rows="3"
                                      placeholder="Commentaires cliniques, corrélations, recommandations…"
                                      class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">{{ old('observations', $laboratoire->observations) }}</textarea>
                        </div>

                        <div>
                            <label for="valide_par" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Validé médicalement par</label>
                            <input type="text" id="valide_par" name="valide_par"
                                   value="{{ old('valide_par', $laboratoire->valide_par) }}"
                                   placeholder="Nom du biologiste / chef de laboratoire"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>

                        <div>
                            <label for="clinicien_notifie" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Clinicien notifié (valeurs critiques)</label>
                            <input type="text" id="clinicien_notifie" name="clinicien_notifie"
                                   value="{{ old('clinicien_notifie', $laboratoire->clinicien_notifie) }}"
                                   placeholder="Nom du médecin contacté"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>

                        <div>
                            <label for="date_notification" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Date & heure de notification</label>
                            <input type="datetime-local" id="date_notification" name="date_notification"
                                   value="{{ old('date_notification', $laboratoire->date_notification?->format('Y-m-d\TH:i')) }}"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>

                        <div>
                            <label for="date_remise_resultat" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">Date de remise des résultats</label>
                            <input type="datetime-local" id="date_remise_resultat" name="date_remise_resultat"
                                   value="{{ old('date_remise_resultat', $laboratoire->date_remise_resultat?->format('Y-m-d\TH:i')) }}"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>

                    </div>
                </div>

                {{-- ══ Actions ══════════════════════════════════════════ --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-5 tw-flex tw-flex-wrap tw-items-center tw-gap-3">

                    {{-- Hidden action selector --}}
                    <input type="hidden" id="action-field" name="action" value="save">

                    <button type="submit" onclick="document.getElementById('action-field').value='save'"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-slate-700 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-800 tw-transition-colors tw-border-0 tw-cursor-pointer">
                        <i class="fas fa-save tw-text-xs"></i>
                        Enregistrer (brouillon)
                    </button>

                    <button type="submit" onclick="document.getElementById('action-field').value='validate'"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-green-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-green-700 tw-transition-colors tw-border-0 tw-cursor-pointer tw-shadow-sm">
                        <i class="fas fa-check-circle tw-text-xs"></i>
                        Valider les résultats
                    </button>

                    <button type="submit" onclick="document.getElementById('action-field').value='remise'"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-teal-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-teal-700 tw-transition-colors tw-border-0 tw-cursor-pointer tw-shadow-sm">
                        <i class="fas fa-paper-plane tw-text-xs"></i>
                        Marquer comme remis
                    </button>

                    <a href="{{ route('laboratoire.show', $laboratoire->id) }}"
                       class="tw-ml-auto tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                        <i class="fas fa-eye tw-text-xs"></i>
                        Prévisualiser
                    </a>
                </div>

            </form>
        </main>
    </div>
</div>

<script>
// Row counter per section
const rowCounters = {};

function addResultRow(sectionKey) {
    const tbody = document.getElementById('results-body-' + sectionKey);
    rowCounters[sectionKey] = (rowCounters[sectionKey] ?? tbody.querySelectorAll('tr').length);
    const i = rowCounters[sectionKey]++;
    const row = document.createElement('tr');
    row.className = 'result-row hover:tw-bg-slate-50';
    row.innerHTML = `
        <td class="tw-px-4 tw-py-2"><input list="tests-${sectionKey}" name="resultats_${sectionKey}[${i}][test]" placeholder="Nom du test" class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-blue-600"></td>
        <td class="tw-px-4 tw-py-2"><input type="text" name="resultats_${sectionKey}[${i}][valeur]" placeholder="Valeur" class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-blue-600"></td>
        <td class="tw-px-4 tw-py-2"><input type="text" name="resultats_${sectionKey}[${i}][unite]"  placeholder="g/L"   class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-blue-600"></td>
        <td class="tw-px-4 tw-py-2"><input type="text" name="resultats_${sectionKey}[${i}][ref_min]" placeholder="0"   class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-blue-600"></td>
        <td class="tw-px-4 tw-py-2"><input type="text" name="resultats_${sectionKey}[${i}][ref_max]" placeholder="∞"   class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-blue-600"></td>
        <td class="tw-px-4 tw-py-2">
            <select name="resultats_${sectionKey}[${i}][flag]" class="tw-w-full tw-px-3 tw-py-1.5 tw-text-xs tw-border tw-border-slate-200 tw-rounded-lg focus:tw-outline-none focus:tw-border-blue-600">
                <option value="normal">Normal</option>
                <option value="bas">▼ Bas</option>
                <option value="eleve">▲ Élevé</option>
                <option value="critique">⚠ Critique</option>
            </select>
        </td>
        <td class="tw-px-4 tw-py-2 tw-text-center">
            <button type="button" onclick="removeRow(this)" class="tw-w-6 tw-h-6 tw-rounded tw-bg-red-50 tw-text-red-400 hover:tw-bg-red-500 hover:tw-text-white tw-transition-colors tw-border-0 tw-cursor-pointer tw-text-xs">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    row.querySelector('input').focus();
}

function removeRow(btn) {
    btn.closest('tr').remove();
}
</script>
@stop