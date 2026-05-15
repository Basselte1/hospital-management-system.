@extends('layouts.admin')
@section('title', 'CMCU | Résultats — ' . ($laboratoire->numero_bon ?? ''))

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
                <span class="tw-text-slate-700 tw-font-medium">{{ $laboratoire->numero_bon }}</span>
            </nav>

            {{-- Actions bar --}}
            <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-3 tw-mb-6">
                @can('laboratoireWrite', \App\Models\Patient::class)
                @if(in_array($laboratoire->statut, ['en_attente', 'en_cours']))
                <a href="{{ route('laboratoire.edit', $laboratoire->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-no-underline tw-shadow-sm">
                    <i class="fas fa-pen tw-text-xs"></i>
                    Saisir / Modifier les résultats
                </a>
                @endif
                @endcan
                @can('laboratoire', \App\Models\Patient::class)
                <a href="{{ route('laboratoire.bon', $laboratoire->id) }}"
                target="_blank"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-teal-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-teal-700 tw-transition-colors tw-no-underline tw-shadow-sm">
                    <i class="fas fa-receipt tw-text-xs"></i>
                    Imprimer le bon
                </a>
                @endcan

                @if(in_array($laboratoire->statut, ['valide', 'remis']))
                @can('laboratoireWrite', \App\Models\Patient::class)
                <a href="{{ route('laboratoire.export', $laboratoire->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-green-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-green-700 tw-transition-colors tw-no-underline tw-shadow-sm">
                    <i class="fas fa-file-pdf tw-text-xs"></i>
                    Exporter le rapport PDF
                </a>
                <a href="{{ route('laboratoire.rapport', $laboratoire->id) }}"
                   target="_blank"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-700 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-800 tw-transition-colors tw-no-underline">
                    <i class="fas fa-print tw-text-xs"></i>
                    Aperçu impression
                </a>
                @endcan
                @endif

                <a href="{{ route('laboratoire.patient.history', $laboratoire->patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-teal-100 tw-text-teal-700 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-teal-200 tw-transition-colors tw-no-underline">
                    <i class="fas fa-history tw-text-xs"></i>
                    Historique patient
                </a>

                <a href="{{ route('laboratoire.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline tw-ml-auto">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    Retour à la liste
                </a>
            </div>

            <div class="tw-max-w-4xl">

                {{-- ── Header card ──────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">

                    <div class="tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                        <div class="tw-flex tw-items-center tw-gap-4">
                            <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-flask tw-text-white tw-text-xl"></i>
                            </div>
                            <div>
                                <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0.5">Bon de laboratoire</p>
                                <h1 class="tw-text-white tw-font-bold tw-text-lg tw-mb-0 tw-font-mono">{{ $laboratoire->numero_bon }}</h1>
                            </div>
                        </div>
                        <div class="tw-text-right">
                            <span class="tw-px-3 tw-py-1.5 tw-rounded-full tw-text-sm tw-font-semibold {{ $laboratoire->statut_color }}">
                                {{ $laboratoire->statut_label }}
                            </span>
                            <p class="tw-text-[#BFDBFE] tw-text-xs tw-mt-1.5 tw-mb-0">
                                Créé le {{ $laboratoire->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>

                    {{-- Patient + Prescripteur info --}}
                    <div class="tw-grid tw-grid-cols-2 sm:tw-grid-cols-4 tw-divide-x tw-divide-slate-100 tw-divide-y sm:tw-divide-y-0">
                        @foreach([
                            ['label' => 'Patient', 'value' => ($laboratoire->patient->prenom ?? '') . ' ' . strtoupper($laboratoire->patient->name ?? '—'), 'icon' => 'fa-user-injured'],
                            ['label' => 'N° Dossier', 'value' => $laboratoire->patient->numero_dossier ?? '—', 'icon' => 'fa-folder'],
                            ['label' => 'Médecin prescripteur', 'value' => $laboratoire->prescripteur ? 'Dr ' . $laboratoire->prescripteur->prenom . ' ' . $laboratoire->prescripteur->name : '—', 'icon' => 'fa-user-doctor'],
                            ['label' => 'Laborantin', 'value' => ($laboratoire->laborantin->prenom ?? '') . ' ' . ($laboratoire->laborantin->name ?? '—'), 'icon' => 'fa-flask'],
                        ] as $item)
                        <div class="tw-px-5 tw-py-4">
                            <p class="tw-text-[10px] tw-font-semibold tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-1 tw-flex tw-items-center tw-gap-1.5">
                                <i class="fas {{ $item['icon'] }} tw-text-slate-300"></i>
                                {{ $item['label'] }}
                            </p>
                            <p class="tw-text-sm tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $item['value'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Specimen info ─────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                    <div class="tw-px-6 tw-py-3.5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center"><i class="fas fa-vial tw-text-indigo-500 tw-text-xs"></i></div>
                        <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Spécimen & Pré-analytique</h2>
                    </div>
                    <div class="tw-grid tw-grid-cols-2 sm:tw-grid-cols-3 tw-gap-x-6 tw-gap-y-3 tw-px-6 tw-py-4 tw-text-sm">
                        @foreach([
                            ['Service', $laboratoire->prescription_source],
                            ['Date prélèvement', $laboratoire->date_prelevement?->format('d/m/Y à H:i')],
                            ['Technicien préleveur', $laboratoire->technicien_prelevement],
                            ['Type de tube', $laboratoire->tube_type],
                            ['Site de prélèvement', $laboratoire->site_prelevement],
                            ['Préparation requise', $laboratoire->preparation_requise],
                        ] as [$lbl, $val])
                        <div>
                            <p class="tw-text-[10px] tw-font-semibold tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0.5">{{ $lbl }}</p>
                            <p class="tw-text-slate-700 tw-mb-0">{{ $val ?: '—' }}</p>
                        </div>
                        @endforeach
                        <div>
                            <p class="tw-text-[10px] tw-font-semibold tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0.5">Statut spécimen</p>
                            @if($laboratoire->statut_specimen === 'rejeté')
                                <span class="tw-text-red-600 tw-font-semibold">✗ Rejeté — {{ $laboratoire->motif_rejet ?? '' }}</span>
                            @else
                                <span class="tw-text-green-700 tw-font-semibold">✓ Accepté</span>
                            @endif
                        </div>
                        <div>
                            <p class="tw-text-[10px] tw-font-semibold tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0.5">CQI</p>
                            <span class="tw-font-medium {{ $laboratoire->cqi_status === 'conforme' ? 'tw-text-green-700' : ($laboratoire->cqi_status === 'non_conforme' ? 'tw-text-red-600' : 'tw-text-slate-400') }}">
                                {{ ['conforme' => '✓ Conforme', 'non_conforme' => '✗ Non conforme', 'non_effectue' => 'Non effectué'][$laboratoire->cqi_status ?? 'non_effectue'] }}
                            </span>
                            @if($laboratoire->cqi_note)
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">{{ $laboratoire->cqi_note }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── Critical values alert ─────────────────────────── --}}
                @if($laboratoire->hasCriticalValues())
                <div class="tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-2xl tw-px-5 tw-py-4 tw-mb-5">
                    <p class="tw-text-sm tw-font-bold tw-text-red-700 tw-mb-2 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-triangle-exclamation"></i>
                        VALEURS CRITIQUES — Notification immédiate requise
                    </p>
                    <ul class="tw-list-disc tw-pl-5 tw-space-y-1">
                        @foreach($laboratoire->valeurs_critiques as $vc)
                        <li class="tw-text-sm tw-text-red-700">
                            <strong>{{ $vc['section'] ?? '' }} — {{ $vc['test'] ?? '' }} :</strong>
                            {{ $vc['valeur'] ?? '' }} {{ $vc['unite'] ?? '' }}
                        </li>
                        @endforeach
                    </ul>
                    @if($laboratoire->clinicien_notifie)
                    <p class="tw-text-xs tw-text-red-600 tw-mt-2 tw-mb-0">
                        Clinicien notifié : <strong>{{ $laboratoire->clinicien_notifie }}</strong>
                        @if($laboratoire->date_notification)
                            le {{ $laboratoire->date_notification->format('d/m/Y à H:i') }}
                        @endif
                    </p>
                    @endif
                </div>
                @endif

                {{-- ── Results by section ────────────────────────────── --}}
                @php $sectionsActives = $laboratoire->getSectionsActives(); @endphp

                @forelse($sectionsActives as $sectionKey)
                @php
                    $resultats   = $laboratoire->getAttribute("{$sectionKey}_resultats") ?? [];
                    $sectionLabel = $sections[$sectionKey] ?? ucfirst($sectionKey);
                @endphp

                @if(!empty($resultats))
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-4">

                    <div class="tw-px-5 tw-py-3 tw-bg-indigo-50 tw-border-b tw-border-indigo-100 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-2 tw-h-5 tw-rounded-full tw-bg-[#1D4ED8] tw-shrink-0"></div>
                        <h3 class="tw-text-sm tw-font-bold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wide tw-mb-0">{{ $sectionLabel }}</h3>
                    </div>

                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm">
                            <thead class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <tr>
                                    <th class="tw-px-5 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[35%]">Test</th>
                                    <th class="tw-px-5 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[18%]">Résultat</th>
                                    <th class="tw-px-5 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[12%]">Unité</th>
                                    <th class="tw-px-5 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[22%]">Valeurs de référence</th>
                                    <th class="tw-px-5 tw-py-2.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-[13%]">Interprétation</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-50">
                                @foreach($resultats as $row)
                                @php
                                    $flag = $row['flag'] ?? 'normal';
                                    $flagClass = match($flag) {
                                        'bas'      => 'tw-text-blue-700 tw-font-bold',
                                        'eleve', 'élevé' => 'tw-text-amber-600 tw-font-bold',
                                        'critique' => 'tw-text-red-600 tw-font-bold',
                                        default    => 'tw-text-green-700',
                                    };
                                    $flagLabel = match($flag) {
                                        'bas'      => '▼ BAS',
                                        'eleve', 'élevé' => '▲ ÉLEVÉ',
                                        'critique' => '⚠ CRITIQUE',
                                        default    => 'Normal',
                                    };
                                    $rowBg = $flag === 'critique' ? 'tw-bg-red-50' : ($flag !== 'normal' ? 'tw-bg-amber-50/40' : '');
                                @endphp
                                <tr class="{{ $rowBg }} hover:tw-bg-slate-50 tw-transition-colors">
                                    <td class="tw-px-5 tw-py-3 tw-font-medium tw-text-slate-800">{{ $row['test'] ?? '—' }}</td>
                                    <td class="tw-px-5 tw-py-3 tw-font-bold {{ $flag === 'critique' ? 'tw-text-red-600' : 'tw-text-slate-800' }}">
                                        {{ $row['valeur'] ?? '—' }}
                                    </td>
                                    <td class="tw-px-5 tw-py-3 tw-text-slate-500">{{ $row['unite'] ?? '—' }}</td>
                                    <td class="tw-px-5 tw-py-3 tw-text-slate-500 tw-text-xs">
                                        @if(!empty($row['ref_min']) || !empty($row['ref_max']))
                                            {{ $row['ref_min'] ?? '' }} – {{ $row['ref_max'] ?? '' }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="tw-px-5 tw-py-3 tw-text-center tw-text-xs {{ $flagClass }}">{{ $flagLabel }}</td>
                                </tr>
                                @endforeach

                                @if($sectionKey === 'bacteriologie' && $laboratoire->antibiogramme)
                                <tr>
                                    <td colspan="5" class="tw-px-5 tw-py-3 tw-bg-amber-50 tw-text-xs tw-text-amber-800">
                                        <strong>Antibiogramme :</strong> {{ $laboratoire->antibiogramme }}
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @empty
                <div class="tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-2xl tw-px-5 tw-py-8 tw-text-center tw-text-slate-400 tw-mb-5">
                    <i class="fas fa-flask tw-text-3xl tw-mb-3 tw-block tw-text-slate-300"></i>
                    <p class="tw-text-sm tw-mb-0">Aucun résultat saisi sur ce bon.</p>
                    @can('laboratoireWrite', \App\Models\Patient::class)
                    <a href="{{ route('laboratoire.edit', $laboratoire->id) }}" class="tw-text-[#1D4ED8] tw-text-sm tw-no-underline hover:tw-underline">
                        Saisir les résultats →
                    </a>
                    @endcan
                </div>
                @endforelse

                {{-- ── Observations ──────────────────────────────────── --}}
                @if($laboratoire->observations)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                    <div class="tw-px-6 tw-py-3.5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-slate-100 tw-flex tw-items-center tw-justify-center"><i class="fas fa-comment-medical tw-text-slate-500 tw-text-xs"></i></div>
                        <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Observations & interprétation</h2>
                    </div>
                    <div class="tw-px-6 tw-py-4 tw-text-sm tw-text-slate-600 tw-leading-relaxed tw-whitespace-pre-line">{{ $laboratoire->observations }}</div>
                </div>
                @endif

                {{-- ── Validation block ──────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-3.5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-green-100 tw-flex tw-items-center tw-justify-center"><i class="fas fa-stamp tw-text-green-600 tw-text-xs"></i></div>
                        <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Validation (Phase post-analytique)</h2>
                    </div>
                    <div class="tw-grid tw-grid-cols-2 sm:tw-grid-cols-3 tw-gap-x-6 tw-gap-y-3 tw-px-6 tw-py-4 tw-text-sm">
                        @foreach([
                            ['Validé techniquement par', ($laboratoire->laborantin->prenom ?? '') . ' ' . ($laboratoire->laborantin->name ?? '—')],
                            ['Validé médicalement par', $laboratoire->valide_par],
                            ['Date de validation', $laboratoire->date_validation?->format('d/m/Y à H:i')],
                            ['Résultats remis le', $laboratoire->date_remise_resultat?->format('d/m/Y à H:i')],
                            ['Instrument', $laboratoire->instrument_utilise],
                            ['Lot réactif', $laboratoire->lot_reactif],
                        ] as [$lbl, $val])
                        <div>
                            <p class="tw-text-[10px] tw-font-semibold tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0.5">{{ $lbl }}</p>
                            <p class="tw-text-slate-700 tw-mb-0">{{ $val ?: '—' }}</p>
                        </div>
                        @endforeach
                    </div>
                    @if($laboratoire->tat_minutes !== null)
                    <div class="tw-px-6 tw-py-3 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-clock tw-text-slate-400 tw-text-xs"></i>
                        <p class="tw-text-xs tw-text-slate-500 tw-mb-0">
                            Délai d'exécution (TAT) :
                            <strong class="tw-text-slate-700">
                                {{ floor($laboratoire->tat_minutes / 60) }}h {{ $laboratoire->tat_minutes % 60 }}min
                            </strong>
                        </p>
                    </div>
                    @endif
                </div>

            </div>{{-- /.max-w-4xl --}}
        </main>
    </div>
</div>
@stop