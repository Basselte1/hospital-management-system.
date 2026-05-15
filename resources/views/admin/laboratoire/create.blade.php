@extends('layouts.admin')
@section('title', 'CMCU | Nouveau bon de laboratoire')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Breadcrumb --}}
            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="{{ route('laboratoire.index') }}" class="hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">Laboratoire</a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">Nouveau bon</span>
            </nav>

            {{-- Role guard --}}
            @if(!in_array(auth()->user()->role_id, [1, 6]))
            <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-16 tw-text-center">
                <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-red-100 tw-flex tw-items-center tw-justify-center">
                    <i class="fas fa-ban tw-text-red-500 tw-text-lg"></i>
                </div>
                <p class="tw-text-slate-600 tw-text-sm tw-mb-0">Seule la secrétaire peut créer un bon après encaissement du paiement patient.</p>
                <a href="{{ route('laboratoire.index') }}" class="tw-text-[#1D4ED8] tw-text-sm tw-no-underline hover:tw-underline">← Retour</a>
            </div>
            @else

            @if(session('error'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-circle-exclamation tw-text-red-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-red-600 tw-mb-0">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Workflow banner --}}
            <div class="tw-flex tw-items-center tw-gap-3 tw-mb-6 tw-p-4 tw-bg-blue-50 tw-border tw-border-blue-200 tw-rounded-xl">
                <i class="fas fa-info-circle tw-text-blue-500 tw-text-base tw-shrink-0"></i>
                <p class="tw-text-sm tw-text-blue-700 tw-mb-0">
                    <strong>Procédure :</strong> Le patient a réglé ses examens en caisse.
                    Remplissez ce bon, encaissez le montant, puis remettez le bon imprimé au patient pour qu'il se présente au laboratoire.
                </p>
            </div>

            <form method="POST" action="{{ route('laboratoire.store') }}" class="tw-max-w-4xl" id="bonForm">
                @csrf

                {{-- ══ CARD 1 : Patient ════════════════════════════════════════ --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-visible tw-mb-5">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-user-injured tw-text-teal-600 tw-text-sm"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Identification du patient</h2>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">Recherchez et sélectionnez le patient</p>
                        </div>
                    </div>

                    <div class="tw-p-6 tw-overflow-visible">
                        @if(isset($patient) && $patient)
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                            <div class="tw-flex tw-items-center tw-gap-3 tw-bg-teal-50 tw-border tw-border-teal-200 tw-rounded-xl tw-px-4 tw-py-3">
                                <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-teal-200 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                    <span class="tw-text-teal-700 tw-font-bold tw-text-base">{{ strtoupper(substr($patient->prenom ?? $patient->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $patient->prenom }} {{ strtoupper($patient->name) }}</p>
                                    <p class="tw-text-xs tw-text-slate-500 tw-mb-0">N° dossier : {{ $patient->numero_dossier }}</p>
                                </div>
                                <a href="{{ route('laboratoire.create') }}" class="tw-ml-auto tw-text-xs tw-text-slate-400 hover:tw-text-[#1D4ED8] tw-no-underline">Changer</a>
                            </div>
                        @else
                            @php
                                $allPatients = \App\Models\Patient::select('id','name','prenom','numero_dossier','medecin_r')
                                    ->latest()->limit(300)->get();

                                $medecinMap = [];
                                foreach ($allPatients as $p) {
                                    if (!empty($p->medecin_r)) {
                                        $parts   = preg_split('/\s+/', trim($p->medecin_r), 2);
                                        $keyword = $parts[0] ?? '';
                                        $matched = $medecins->first(function($m) use ($keyword) {
                                            return stripos($m->name,  $keyword) !== false
                                                || stripos($m->prenom, $keyword) !== false;
                                        });
                                        if ($matched) {
                                            $medecinMap[$p->id] = $matched->id;
                                        }
                                    }
                                }

                                // Section slugs from the DB-driven collection
                                $sectionSlugs = $sectionObjects->keys()->all();

                                // Pre-load all latest prescriptions in ONE query
                                $latestPrescriptions = \App\Models\Prescription::select(
                                        array_merge(['id','patient_id'], $sectionSlugs)
                                    )
                                    ->whereIn('patient_id', $allPatients->pluck('id'))
                                    ->latest()
                                    ->get()
                                    ->groupBy('patient_id')
                                    ->map(fn($g) => $g->first());

                                $patientJsonData = $allPatients->map(function($p) use ($medecinMap, $latestPrescriptions, $sectionSlugs) {
                                    $fullName = trim($p->prenom . ' ' . strtoupper($p->name));

                                    $prescribedTests = [];
                                    $prescription = $latestPrescriptions->get($p->id);
                                    if ($prescription) {
                                        foreach ($sectionSlugs as $field) {
                                            $raw = trim($prescription->$field ?? '');
                                            if ($raw !== '') {
                                                $tests = array_values(array_filter(array_map('trim', explode(',', $raw))));
                                                if (!empty($tests)) $prescribedTests[$field] = $tests;
                                            }
                                        }
                                    }

                                    $doneTests = [];
                                    $resultColumns = array_map(fn($s) => "{$s}_resultats", $sectionSlugs);
                                    $previousBons = \App\Models\ExamenLaboratoire::where('patient_id', $p->id)
                                        ->where('is_valide', true)
                                        ->select(array_merge(['id'], $sectionSlugs, $resultColumns))
                                        ->get();
                                    foreach ($previousBons as $bon) {
                                        foreach ($sectionSlugs as $sec) {
                                            $results = $bon->{"{$sec}_resultats"} ?? [];
                                            if (!empty($results)) {
                                                foreach (array_keys($results) as $testName) {
                                                    $doneTests[$sec][] = $testName;
                                                }
                                            }
                                        }
                                    }
                                    foreach ($doneTests as $s => $t) {
                                        $doneTests[$s] = array_values(array_unique($t));
                                    }

                                    return [
                                        'id'              => $p->id,
                                        'label'           => $fullName . ' — N° ' . $p->numero_dossier,
                                        'name'            => $fullName,
                                        'dossier'         => 'N° ' . $p->numero_dossier,
                                        'initial'         => strtoupper(substr($p->prenom ?: $p->name, 0, 1)),
                                        'medecin_id'      => $medecinMap[$p->id] ?? null,
                                        'sections'        => array_keys($prescribedTests),
                                        'prescribedTests' => $prescribedTests,
                                        'doneTests'       => $doneTests,
                                    ];
                                })->values()->all();
                            @endphp

                            <label class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Patient <span class="tw-text-red-500">*</span>
                            </label>

                            <input type="hidden" id="patient_id" name="patient_id"
                                   value="{{ old('patient_id') }}" required>

                            <div id="patientAC" class="patient-ac" style="position:relative;">
                                <div style="position:relative;">
                                    <input type="text"
                                           id="patientSearch"
                                           autocomplete="off"
                                           placeholder="Tapez le nom ou le numéro de dossier…"
                                           style="width:100%;padding:0.625rem 2.5rem 0.625rem 1rem;font-size:0.875rem;
                                                  border:1px solid #e2e8f0;border-radius:0.75rem;outline:none;
                                                  background:#fff;color:#1e293b;box-sizing:border-box;">
                                    <span id="patientACClear"
                                          style="display:none;position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);
                                                 cursor:pointer;color:#94a3b8;font-size:1rem;line-height:1;"
                                          onclick="clearPatientAC()">✕</span>
                                </div>

                                <div id="patientACList"
                                     style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;
                                            background:#fff;border:1px solid #e2e8f0;border-radius:0.75rem;
                                            box-shadow:0 10px 25px rgba(0,0,0,0.12);z-index:99999;
                                            max-height:260px;overflow-y:auto;">
                                    <div id="patientACEmpty"
                                         style="padding:0.75rem 1rem;font-size:0.8rem;color:#94a3b8;display:none;">
                                        Aucun patient trouvé
                                    </div>
                                </div>
                            </div>

                            <div id="patientSelectedBadge"
                                 style="display:none;margin-top:0.5rem;padding:0.625rem 1rem;
                                        background:#f0fdfa;border:1px solid #99f6e4;border-radius:0.75rem;
                                        align-items:center;gap:0.75rem;">
                                <div style="width:2rem;height:2rem;border-radius:50%;background:#ccfbf1;
                                            display:flex;align-items:center;justify-content:center;
                                            font-weight:700;color:#0d9488;font-size:0.875rem;flex-shrink:0;"
                                     id="patientBadgeInitial">?</div>
                                <div>
                                    <p id="patientBadgeName" style="margin:0;font-weight:600;font-size:0.875rem;color:#1e293b;"></p>
                                    <p id="patientBadgeDossier" style="margin:0;font-size:0.75rem;color:#64748b;"></p>
                                </div>
                                <button type="button" onclick="clearPatientAC()"
                                        style="margin-left:auto;background:none;border:none;cursor:pointer;
                                               font-size:0.75rem;color:#94a3b8;padding:0;">Changer</button>
                            </div>

                            @error('patient_id')
                            <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                            @enderror

                            <script id="patientDataJson" type="application/json">{!! json_encode($patientJsonData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
                        @endif

                        <div id="patient-info-notice" class="tw-hidden tw-mt-3 tw-flex tw-items-start tw-gap-2 tw-bg-blue-50 tw-border tw-border-blue-200 tw-rounded-xl tw-px-4 tw-py-3">
                            <i class="fas fa-magic tw-text-blue-500 tw-text-sm tw-mt-0.5 tw-shrink-0"></i>
                            <div>
                                <p class="tw-text-xs tw-font-semibold tw-text-blue-700 tw-mb-0.5">Informations pré-remplies depuis le dossier patient</p>
                                <p id="patient-info-detail" class="tw-text-xs tw-text-blue-600 tw-mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ CARD 2 : Médecin traitant & Source ═══════════════════════ --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-user-md tw-text-indigo-600 tw-text-sm"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Médecin & Prescription</h2>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">Médecin traitant et origine de la demande</p>
                        </div>
                    </div>

                    <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-5">
                        <div>
                            <label for="prescripteur_id" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Médecin traitant / prescripteur
                            </label>
                            <select id="prescripteur_id" name="prescripteur_id"
                                    class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-white focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                <option value="">— Aucun / Externe —</option>
                                @foreach($medecins as $m)
                                <option value="{{ $m->id }}"
                                        data-nom="{{ strtolower($m->prenom . ' ' . $m->name) }}"
                                        {{ old('prescripteur_id') == $m->id ? 'selected' : '' }}>
                                    Dr {{ $m->prenom }} {{ $m->name }}{{ $m->specialite ? ' — ' . $m->specialite : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="prescription_source" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Origine de la demande
                            </label>
                            <input type="text" id="prescription_source" name="prescription_source"
                                   value="{{ old('prescription_source') }}"
                                   placeholder="Consultation interne, Médecin externe, Urgences…"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10">
                        </div>

                        <div>
                            <label for="date_prescription" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Date de prescription / du bon
                            </label>
                            <input type="date" id="date_prescription" name="date_prescription"
                                   value="{{ old('date_prescription', now()->toDateString()) }}"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>

                        <div>
                            <label for="preparation_requise" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Instructions de préparation
                                <span class="tw-font-normal tw-text-slate-400">(facultatif)</span>
                            </label>
                            <input type="text" id="preparation_requise" name="preparation_requise"
                                   value="{{ old('preparation_requise') }}"
                                   placeholder="Ex : Jeûne 8h, Midstream urine…"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>
                    </div>
                </div>

                {{-- ══ CARD 3 : Examens demandés — DB-DRIVEN ═══════════════════ --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">

                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-list-check tw-text-amber-500 tw-text-sm"></i>
                        </div>
                        <div class="tw-flex-1">
                            <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Examens à réaliser</h2>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">
                                Sélectionnez les tests selon l'ordonnance. Désélectionnez ceux que le patient ne fera pas.
                            </p>
                        </div>
                        <div class="tw-text-right tw-shrink-0">
                            <p class="tw-text-[10px] tw-text-slate-400 tw-mb-0">Total estimé</p>
                            <p id="totalPrice" class="tw-text-lg tw-font-bold tw-text-green-600 tw-mb-0">0 FCFA</p>
                        </div>
                    </div>

                    {{-- Auto-fill notice --}}
                    <div id="sections-auto-notice"
                         class="tw-hidden tw-mx-6 tw-mt-4 tw-flex tw-items-start tw-gap-2 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-xl tw-px-4 tw-py-3">
                        <i class="fas fa-file-medical tw-text-green-500 tw-text-sm tw-mt-0.5 tw-shrink-0"></i>
                        <p class="tw-text-xs tw-text-green-700 tw-mb-0">
                            Tests pré-cochés d'après la dernière prescription. <strong>Vérifiez avec l'ordonnance et modifiez si nécessaire.</strong>
                        </p>
                    </div>

                    @error('tests')
                    <div class="tw-mx-6 tw-mt-4 tw-flex tw-items-center tw-gap-2 tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-xl tw-px-4 tw-py-2.5">
                        <i class="fas fa-circle-exclamation tw-text-red-500 tw-text-xs"></i>
                        <p class="tw-text-xs tw-text-red-600 tw-mb-0">{{ $message }}</p>
                    </div>
                    @enderror

                    {{--
                        Pass tariffs as JSON for the JS price calculator.
                        $tarifsBySection shape: ['biochimie' => ['Glycémie' => 3000, ...], ...]
                    --}}
                    <script id="laboTarifsJson" type="application/json">{!! json_encode($tarifsBySection, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>

                    {{--
                        DB-DRIVEN SECTIONS GRID
                        $sectionObjects — Collection of SectionLaboratoire keyed by slug
                        $tarifsBySection — slug → [test → price]
                        $prescribedTestsBySection — slug → [test, ...]
                        $doneTestsBySection       — slug → [test, ...]
                    --}}
                    <div class="tw-p-6" id="laboSectionsGrid">

                        @if($sectionObjects->isEmpty())
                            <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-10 tw-text-center">
                                <i class="fas fa-flask tw-text-4xl tw-text-slate-200"></i>
                                <p class="tw-text-sm tw-text-slate-400">Aucune section d'examens configurée.</p>
                                @if(auth()->user()->role_id === 1)
                                <a href="{{ route('sections_labo.create') }}"
                                   class="tw-text-xs tw-text-[#1D4ED8] hover:tw-underline tw-no-underline">
                                    + Créer des sections depuis l'administration
                                </a>
                                @endif
                            </div>
                        @else
                            @foreach($sectionObjects as $sectionKey => $sectionObj)
                            @php
                                $sectionTarifs = $tarifsBySection[$sectionKey] ?? [];
                                $prescribed    = $prescribedTestsBySection[$sectionKey] ?? [];
                                $done          = $doneTestsBySection[$sectionKey] ?? [];
                                $hasTests      = !empty($sectionTarifs);
                                $hasPrescribed = !empty($prescribed);
                            @endphp

                            <div class="tw-border tw-rounded-xl tw-overflow-hidden tw-mb-4 {{ $sectionObj->color_classes }}"
                                 data-section-panel="{{ $sectionKey }}">

                                {{-- Section header row --}}
                                <div class="tw-flex tw-items-center tw-gap-3 tw-px-4 tw-py-3 tw-cursor-pointer tw-bg-white/60 hover:tw-bg-white tw-transition-colors tw-select-none section-header"
                                     onclick="toggleSectionPanel('{{ $sectionKey }}', this)">

                                    <input type="checkbox"
                                           id="master_{{ $sectionKey }}"
                                           class="section-master-cb tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-cursor-pointer"
                                           data-section="{{ $sectionKey }}"
                                           onclick="event.stopPropagation(); masterToggle('{{ $sectionKey }}', this.checked)"
                                           {{ $hasPrescribed ? 'checked' : '' }}>

                                    <i class="fas {{ $sectionObj->icon }} tw-text-sm tw-text-slate-500 tw-w-4 tw-text-center"></i>

                                    {{-- Label comes from DB --}}
                                    <span class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-flex-1">
                                        {{ $sectionObj->label }}
                                    </span>

                                    <span id="subtotal_{{ $sectionKey }}" class="tw-text-xs tw-font-mono tw-text-slate-500 tw-mr-2">—</span>

                                    <i class="fas fa-chevron-down tw-text-xs tw-text-slate-400 section-chevron tw-transition-transform tw-duration-200
                                        {{ $hasPrescribed ? '' : 'tw-rotate-[-90deg]' }}"
                                       id="chevron_{{ $sectionKey }}"></i>
                                </div>

                                {{-- Tests grid (collapsible) --}}
                                <div id="panel_{{ $sectionKey }}"
                                     class="tw-border-t tw-border-slate-100 {{ $hasPrescribed ? '' : 'tw-hidden' }}">

                                    @if($hasTests)
                                        <div class="tw-p-4 tw-grid tw-grid-cols-2 sm:tw-grid-cols-3 md:tw-grid-cols-4 lg:tw-grid-cols-6 tw-gap-3">
                                            @foreach($sectionTarifs as $testName => $prix)
                                            @php
                                                $isPrescribed = in_array($testName, $prescribed);
                                                $isDone       = in_array($testName, $done);
                                            @endphp
                                            <div class="tw-relative tw-bg-white tw-border tw-border-slate-200 tw-rounded-xl tw-p-3 tw-shadow-sm hover:tw-shadow-md tw-transition-shadow
                                                        {{ $isDone ? 'tw-opacity-60 tw-bg-slate-50' : '' }}">
                                                <label class="tw-flex tw-flex-col tw-gap-1 tw-cursor-pointer">
                                                    <div class="tw-flex tw-items-start tw-gap-2">
                                                        <input type="checkbox"
                                                               name="tests[{{ $sectionKey }}][]"
                                                               value="{{ $testName }}"
                                                               class="test-checkbox tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-mt-0.5 tw-shrink-0"
                                                               data-section="{{ $sectionKey }}"
                                                               data-price="{{ $prix }}"
                                                               {{ $isPrescribed && !$isDone ? 'checked' : '' }}
                                                               onchange="recalcTotal()"
                                                               {{ $isDone ? 'disabled' : '' }}>
                                                        <span class="tw-text-sm tw-font-medium tw-text-slate-700 tw-break-words">{{ $testName }}</span>
                                                    </div>
                                                    <div class="tw-text-right tw-text-xs tw-font-mono tw-text-green-600 tw-mt-1">
                                                        {{ number_format($prix, 0, ',', ' ') }} FCFA
                                                    </div>
                                                    @if($isDone)
                                                        <div class="tw-absolute tw-top-2 tw-right-2 tw-inline-flex tw-items-center tw-gap-1 tw-px-1.5 tw-py-0.5 tw-rounded tw-text-[10px] tw-font-medium tw-bg-green-100 tw-text-green-700">
                                                            <i class="fas fa-check tw-text-[9px]"></i> Fait
                                                        </div>
                                                    @elseif($isPrescribed)
                                                        <div class="tw-absolute tw-top-2 tw-right-2 tw-inline-flex tw-items-center tw-gap-1 tw-px-1.5 tw-py-0.5 tw-rounded tw-text-[10px] tw-font-medium tw-bg-blue-100 tw-text-blue-700">
                                                            <i class="fas fa-file-prescription tw-text-[9px]"></i> Prescrit
                                                        </div>
                                                    @endif
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        {{-- Section exists but has no tests yet --}}
                                        <div class="tw-px-5 tw-py-4 tw-flex tw-items-center tw-justify-between">
                                            <p class="tw-text-xs tw-text-slate-400 tw-italic tw-mb-0">
                                                Aucun test configuré pour cette section.
                                            </p>
                                            @if(auth()->user()->role_id === 1)
                                            <a href="{{ route('tarifs_labo.create') }}"
                                               class="tw-text-xs tw-text-[#1D4ED8] hover:tw-underline tw-no-underline tw-shrink-0">
                                                <i class="fas fa-plus tw-mr-1"></i>Ajouter des tests
                                            </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @endif

                    </div>

                    {{-- Grand total bar --}}
                    <div class="tw-mx-6 tw-mb-6 tw-p-4 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-xl tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Total des tests sélectionnés</p>
                            <p class="tw-text-[11px] tw-text-slate-400 tw-mb-0" id="selectedTestsCount">0 test(s) sélectionné(s)</p>
                        </div>
                        <div class="tw-text-right">
                            <p class="tw-text-2xl tw-font-bold tw-text-green-700 tw-mb-0 tw-font-mono" id="totalPriceBar">0 FCFA</p>
                        </div>
                    </div>
                </div>

                {{-- ══ CARD 4 : Paiement ════════════════════════════════════════ --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-green-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-money-bill-wave tw-text-green-600 tw-text-sm"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Paiement</h2>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">Montant encaissé en caisse avant remise du bon</p>
                        </div>
                    </div>

                    <div class="tw-p-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-5">
                        <div>
                            <label for="montant_paye" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Montant encaissé (FCFA) <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="number" id="montant_paye" name="montant_paye"
                                   value="{{ old('montant_paye', 0) }}" min="0" step="100"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                            @error('montant_paye')
                            <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mode_paiement" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Mode de paiement <span class="tw-text-red-500">*</span>
                            </label>
                            <select id="mode_paiement" name="mode_paiement"
                                    class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-white focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                                @foreach(['espèces' => 'Espèces', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte bancaire', 'cheque' => 'Chèque', 'virement' => 'Virement', 'assurance' => 'Assurance'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('mode_paiement', 'espèces') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="reference_paiement" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Référence / N° reçu
                                <span class="tw-font-normal tw-text-slate-400">(facultatif)</span>
                            </label>
                            <input type="text" id="reference_paiement" name="reference_paiement"
                                   value="{{ old('reference_paiement') }}"
                                   placeholder="N° transaction, reçu…"
                                   class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        </div>

                        <div class="sm:tw-col-span-3">
                            <label class="tw-flex tw-items-center tw-gap-3 tw-cursor-pointer">
                                <input type="checkbox" name="paiement_confirme" value="1"
                                       class="tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-accent-[#1D4ED8]"
                                       {{ old('paiement_confirme') ? 'checked' : '' }}>
                                <span class="tw-text-sm tw-text-slate-700">
                                    Je confirme avoir encaissé le paiement. <span class="tw-text-red-500">*</span>
                                </span>
                            </label>
                            @error('paiement_confirme')
                            <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="tw-flex tw-items-center tw-gap-3 tw-pb-8">
                    <button type="submit"
                            class="tw-px-6 tw-py-2.5 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-blue-700 tw-text-white tw-text-sm tw-font-semibold tw-transition-colors tw-shadow-sm">
                        <i class="fas fa-print tw-mr-2"></i>Enregistrer et imprimer le bon
                    </button>
                    <a href="{{ route('laboratoire.index') }}"
                       class="tw-px-6 tw-py-2.5 tw-rounded-xl tw-border tw-border-slate-200 tw-text-slate-600 hover:tw-bg-slate-50 tw-text-sm tw-font-medium tw-no-underline tw-transition-colors">
                        Annuler
                    </a>
                </div>

            </form>

            @endif
        </main>
    </div>
</div>

@push('scripts')
<script>
// ═══════════════════════════════════════════════════════════════
// 1. PATIENT AUTOCOMPLETE
// ═══════════════════════════════════════════════════════════════
(function() {
    var dataEl     = document.getElementById('patientDataJson');
    if (!dataEl) return;   // patient was pre-selected server-side

    var patients   = [];
    try { patients = JSON.parse(dataEl.textContent || '[]'); } catch(e) {}

    var searchEl   = document.getElementById('patientSearch');
    var listEl     = document.getElementById('patientACList');
    var emptyEl    = document.getElementById('patientACEmpty');
    var hiddenEl   = document.getElementById('patient_id');
    var badgeEl    = document.getElementById('patientSelectedBadge');
    var clearBtn   = document.getElementById('patientACClear');
    var prescEl    = document.getElementById('prescripteur_id');

    if (!searchEl) return;

    searchEl.addEventListener('input', function() {
        var q = this.value.trim().toLowerCase();
        clearBtn.style.display = q ? 'block' : 'none';

        var filtered = q
            ? patients.filter(p => p.label.toLowerCase().includes(q))
            : patients.slice(0, 20);

        renderList(filtered);
        listEl.style.display = 'block';
    });

    searchEl.addEventListener('focus', function() {
        renderList(patients.slice(0, 20));
        listEl.style.display = 'block';
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#patientAC')) {
            listEl.style.display = 'none';
        }
    });

    function renderList(items) {
        // Remove existing items (keep emptyEl)
        Array.from(listEl.children).forEach(function(c) {
            if (c.id !== 'patientACEmpty') c.remove();
        });

        if (!items.length) {
            emptyEl.style.display = 'block';
            return;
        }
        emptyEl.style.display = 'none';

        items.forEach(function(p) {
            var div = document.createElement('div');
            div.style.cssText = 'padding:0.6rem 1rem;cursor:pointer;font-size:0.85rem;color:#1e293b;border-bottom:1px solid #f1f5f9;';
            div.textContent = p.label;
            div.addEventListener('mousedown', function(e) {
                e.preventDefault();
                selectPatient(p);
            });
            div.addEventListener('mouseover', function() { this.style.background = '#f0f9ff'; });
            div.addEventListener('mouseout',  function() { this.style.background = ''; });
            listEl.appendChild(div);
        });
    }

    function selectPatient(p) {
        hiddenEl.value = p.id;
        searchEl.value = '';
        searchEl.style.display = 'none';
        clearBtn.style.display = 'none';
        listEl.style.display = 'none';

        document.getElementById('patientBadgeInitial').textContent  = p.initial || '?';
        document.getElementById('patientBadgeName').textContent      = p.name;
        document.getElementById('patientBadgeDossier').textContent   = p.dossier;
        badgeEl.style.display = 'flex';

        // Auto-fill prescripteur
        if (p.medecin_id && prescEl) {
            prescEl.value = p.medecin_id;
        }

        // Auto-check prescribed tests
        applyPatientTests(p);
    }

    window.clearPatientAC = function() {
        hiddenEl.value = '';
        searchEl.value = '';
        searchEl.style.display = '';
        clearBtn.style.display = 'none';
        badgeEl.style.display = 'none';
        listEl.style.display = 'none';
        // Reset all test checkboxes
        document.querySelectorAll('.test-checkbox').forEach(function(cb) { cb.checked = false; });
        document.querySelectorAll('[id^="panel_"]').forEach(function(p) { p.classList.add('tw-hidden'); });
        recalcTotal();
    };
})();

// ═══════════════════════════════════════════════════════════════
// 2. PRICE CALCULATOR
// ═══════════════════════════════════════════════════════════════
function recalcTotal() {
    var total = 0, count = 0;
    document.querySelectorAll('.test-checkbox:checked').forEach(function(cb) {
        total += parseInt(cb.dataset.price || 0, 10);
        count++;
    });

    var fmt = total.toLocaleString('fr-FR') + ' FCFA';

    var totalEl    = document.getElementById('totalPrice');
    var totalBarEl = document.getElementById('totalPriceBar');
    var countEl    = document.getElementById('selectedTestsCount');

    if (totalEl)    totalEl.textContent    = fmt;
    if (totalBarEl) totalBarEl.textContent = fmt;
    if (countEl)    countEl.textContent    = count + ' test(s) sélectionné(s)';

    var montantInput = document.getElementById('montant_paye');
    if (montantInput && (montantInput.value === '' || montantInput.value === '0')) {
        montantInput.value = total;
    }
}

function masterToggle(section, checked) {
    document.querySelectorAll('.test-checkbox[data-section="' + section + '"]').forEach(function(cb) {
        cb.checked = checked;
    });
    var panel = document.getElementById('panel_' + section);
    if (panel && checked) panel.classList.remove('tw-hidden');
    recalcTotal();
}

function toggleSectionPanel(section, headerEl) {
    var panel   = document.getElementById('panel_' + section);
    var chevron = document.getElementById('chevron_' + section);
    if (!panel) return;
    var isHidden = panel.classList.contains('tw-hidden');
    panel.classList.toggle('tw-hidden', !isHidden);
    if (chevron) chevron.classList.toggle('tw-rotate-[-90deg]', !isHidden);
}

// ═══════════════════════════════════════════════════════════════
// 3. AUTO-APPLY PRESCRIBED TESTS WHEN PATIENT IS SELECTED
// ═══════════════════════════════════════════════════════════════
function applyPatientTests(p) {
    document.querySelectorAll('.test-checkbox').forEach(function(cb) {
        cb.checked = false;
    });

    var prescribed    = p.prescribedTests || {};
    var done          = p.doneTests       || {};
    var hasPrescribed = Object.keys(prescribed).length > 0;

    Object.keys(prescribed).forEach(function(section) {
        var tests           = prescribed[section] || [];
        var doneSectionTests = done[section] || [];

        tests.forEach(function(testName) {
            if (doneSectionTests.indexOf(testName) !== -1) return; // skip done tests
            var cb = document.querySelector(
                '.test-checkbox[data-section="' + section + '"][value="' + CSS.escape(testName) + '"]'
            );
            if (cb) cb.checked = true;
        });

        // Expand the section panel
        var panel = document.getElementById('panel_' + section);
        if (panel) panel.classList.remove('tw-hidden');
        var chevron = document.getElementById('chevron_' + section);
        if (chevron) chevron.classList.remove('tw-rotate-[-90deg]');

        // Tick the master checkbox
        var master = document.getElementById('master_' + section);
        if (master) master.checked = true;
    });

    var autoNotice = document.getElementById('sections-auto-notice');
    if (autoNotice) {
        autoNotice.style.display = hasPrescribed ? 'flex' : 'none';
        autoNotice.classList.toggle('tw-hidden', !hasPrescribed);
    }

    recalcTotal();
}

// ═══════════════════════════════════════════════════════════════
// 4. MONTANT_PAYE SYNC
// ═══════════════════════════════════════════════════════════════
var montantPayeEl = document.getElementById('montant_paye');
if (montantPayeEl) {
    montantPayeEl.addEventListener('focus', function() {
        if (!this.value || this.value === '0') {
            var totalEl = document.getElementById('totalPriceBar');
            if (totalEl) {
                var num = parseInt((totalEl.textContent || '0').replace(/\D/g,''), 10);
                if (num > 0) this.value = num;
            }
        }
    });
    montantPayeEl.addEventListener('blur', function() {
        if (this.value && !isNaN(this.value)) {
            this.value = Math.round(parseFloat(this.value) / 100) * 100;
        }
    });
}

// ═══════════════════════════════════════════════════════════════
// 5. INITIAL RECALC (handles old() repopulation after validation failure)
// ═══════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
    recalcTotal();
});
</script>
@endpush

@stop