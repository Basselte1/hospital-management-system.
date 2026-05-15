@extends('layouts.admin')

@section('title', 'CMCU | Détails de la visite')

@section('content')

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-xl tw-mx-auto">

                {{-- ── Page Header ──────────────────────────────────────── --}}
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-8">
                    <div>
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                            <span class="tw-text-xs tw-font-semibold tw-tracking-widest tw-text-primary-700 tw-uppercase tw-bg-primary-100 tw-px-2.5 tw-py-1 tw-rounded-full">Visites</span>
                        </div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-tracking-tight">Détails de la visite</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-0.5">Visite du {{ $visit->visit_date->format('d/m/Y') }}</p>
                    </div>
                    <a href="{{ route('patient-visits.index', $visit->patient) }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2.5 tw-rounded-xl tw-shadow-card tw-border tw-border-slate-200 tw-transition-all tw-duration-150 tw-no-underline tw-whitespace-nowrap">
                        <i class="fas fa-arrow-left tw-text-xs"></i>
                        Retour à la liste
                    </a>
                </div>

                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-6">

                    {{-- ══ LEFT COLUMN ══════════════════════════════════════ --}}
                    <div class="tw-space-y-5">

                        {{-- Patient card --}}
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">
                            <div class="tw-px-5 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-3">
                                <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                                    <i class="fas fa-user tw-text-white tw-text-sm"></i>
                                </div>
                                <h2 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">Information patient</h2>
                            </div>
                            <div class="tw-p-5">
                                <h3 class="tw-text-lg tw-font-bold tw-text-slate-800 tw-mb-1">{{ $visit->patient_display_name }}</h3>
                                <p class="tw-text-xs tw-text-slate-500 tw-mb-0.5">
                                    <i class="fas fa-hashtag tw-mr-1"></i>
                                    @if($visit->patient_numero_dossier)
                                        CMCU-{{ $visit->patient_numero_dossier }}
                                    @else
                                        <em>N° dossier indisponible</em>
                                    @endif
                                </p>
                                @if($visit->patient && $visit->patient->dossiers->first())
                                <p class="tw-text-xs tw-text-slate-500 tw-mb-0.5">
                                    <i class="fas fa-phone tw-mr-1"></i>{{ $visit->patient->dossiers->first()->portable_1 ?? 'N/A' }}
                                </p>
                                <p class="tw-text-xs tw-text-slate-500 tw-mb-0">
                                    <i class="fas fa-envelope tw-mr-1"></i>{{ $visit->patient->dossiers->first()->email ?? 'N/A' }}
                                </p>
                                @endif

                                @if($visit->patient)
                                <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-4">
                                    <a href="{{ route('patients.show', $visit->patient) }}"
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-text-xs tw-font-semibold tw-text-primary-700 tw-bg-primary-50 hover:tw-bg-primary-100 tw-border tw-border-primary-200 tw-px-3 tw-py-2 tw-rounded-lg tw-transition-colors tw-duration-150 tw-no-underline">
                                        <i class="fas fa-folder-open"></i>Voir dossier complet
                                    </a>
                                    <a href="{{ route('patient-visits.patient-history', $visit->patient) }}"
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-text-xs tw-font-semibold tw-text-emerald-700 tw-bg-emerald-50 hover:tw-bg-emerald-100 tw-border tw-border-emerald-200 tw-px-3 tw-py-2 tw-rounded-lg tw-transition-colors tw-duration-150 tw-no-underline">
                                        <i class="fas fa-history"></i>Historique des visites
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Visit Status card --}}
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-p-5">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-3">Statut de la visite</p>
                            <div class="tw-flex tw-justify-center tw-mb-4">
                                <span class="badge bg-{{ $visit->status_badge_color }} tw-text-sm tw-px-4 tw-py-2 tw-rounded-full">
                                    {{ $visit->status_label }}
                                </span>
                            </div>
                            @can('updateVisitStatus', \App\Models\Patient::class)
                            <form action="{{ route('patient-visits.update-status', $visit) }}" method="POST">
                                @csrf @method('PATCH')
                                <div class="tw-flex tw-gap-2">
                                    <select name="status" class="form-select form-select-sm tw-flex-1">
                                        <option value="en_attente" {{ $visit->status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                        <option value="en_cours"   {{ $visit->status == 'en_cours'   ? 'selected' : '' }}>En cours</option>
                                        <option value="terminee"   {{ $visit->status == 'terminee'   ? 'selected' : '' }}>Terminée</option>
                                        <option value="annulee"    {{ $visit->status == 'annulee'    ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                    <button type="submit"
                                            class="tw-w-9 tw-h-9 tw-rounded-lg tw-bg-primary-700 hover:tw-bg-primary-800 tw-text-white tw-flex tw-items-center tw-justify-center tw-border-0 tw-transition-colors tw-duration-150">
                                        <i class="fas fa-check tw-text-xs"></i>
                                    </button>
                                </div>
                            </form>
                            @endcan
                        </div>

                    </div>

                    {{-- ══ RIGHT COLUMN ═════════════════════════════════════ --}}
                    <div class="lg:tw-col-span-2 tw-space-y-5">

                        {{-- Medical info --}}
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">
                            <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                                <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-primary-700"></div>
                                <span class="tw-text-sm tw-font-semibold tw-text-slate-700">
                                    <i class="fas fa-notes-medical tw-mr-2 tw-text-primary-600"></i>Informations médicales
                                </span>
                            </div>
                            <div class="tw-p-6">
                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Date de la visite</p>
                                        <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $visit->visit_date->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Médecin traitant</p>
                                        <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $visit->medecin_r ?? 'Non spécifié' }}</p>
                                    </div>
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4 sm:tw-col-span-2">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Motif de consultation</p>
                                        <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $visit->motif ?? 'Non spécifié' }}</p>
                                    </div>
                                    @if($visit->details_motif)
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4 sm:tw-col-span-2">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Détails du motif</p>
                                        <p class="tw-text-slate-700 tw-mb-0">{{ $visit->details_motif }}</p>
                                    </div>
                                    @endif
                                    @if($visit->notes)
                                    <div class="tw-bg-sky-50 tw-border tw-border-sky-200 tw-rounded-xl tw-p-4 sm:tw-col-span-2">
                                        <p class="tw-text-xs tw-text-sky-500 tw-font-semibold tw-mb-1">Notes</p>
                                        <p class="tw-text-slate-700 tw-mb-0">{{ $visit->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Financial info --}}
                        @if(in_array(auth()->user()->role_id, [1, 6]))
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">
                            <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                                <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-teal-500"></div>
                                <span class="tw-text-sm tw-font-semibold tw-text-slate-700">
                                    <i class="fas fa-money-bill-wave tw-mr-2 tw-text-teal-500"></i>Informations financières
                                </span>
                            </div>
                            <div class="tw-p-6">
                                <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-mb-4">
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4 tw-text-center">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Montant total</p>
                                        <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0">{{ number_format($visit->montant) }}&nbsp;F</p>
                                    </div>
                                    <div class="tw-bg-emerald-50 tw-rounded-xl tw-p-4 tw-text-center">
                                        <p class="tw-text-xs tw-text-emerald-600 tw-mb-1">Avance</p>
                                        <p class="tw-text-xl tw-font-bold tw-text-emerald-700 tw-mb-0">{{ number_format($visit->avance) }}&nbsp;F</p>
                                    </div>
                                    <div class="tw-rounded-xl tw-p-4 tw-text-center {{ $visit->reste > 0 ? 'tw-bg-red-50' : 'tw-bg-emerald-50' }}">
                                        <p class="tw-text-xs tw-mb-1 {{ $visit->reste > 0 ? 'tw-text-red-500' : 'tw-text-emerald-600' }}">Reste</p>
                                        <p class="tw-text-xl tw-font-bold tw-mb-0 {{ $visit->reste > 0 ? 'tw-text-red-600' : 'tw-text-emerald-700' }}">{{ number_format($visit->reste) }}&nbsp;F</p>
                                    </div>
                                </div>
                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Mode de paiement</p>
                                        <span class="tw-inline-flex tw-items-center tw-text-xs tw-font-semibold tw-text-slate-600 tw-bg-slate-200 tw-px-2.5 tw-py-1 tw-rounded-full">
                                            {{ ucfirst($visit->mode_paiement ?? 'espèce') }}
                                        </span>
                                    </div>
                                    @if($visit->mode_paiement_info_sup)
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Informations supplémentaires</p>
                                        <p class="tw-text-sm tw-text-slate-700 tw-mb-0">{{ $visit->mode_paiement_info_sup }}</p>
                                    </div>
                                    @endif
                                    @if($visit->demarcheur)
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Démarcheur</p>
                                        <p class="tw-text-sm tw-text-slate-700 tw-mb-0">{{ $visit->demarcheur }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Insurance --}}
                        @if($visit->assurance)
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">
                            <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                                <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-violet-500"></div>
                                <span class="tw-text-sm tw-font-semibold tw-text-slate-700">
                                    <i class="fas fa-shield-alt tw-mr-2 tw-text-violet-500"></i>Informations d'assurance
                                </span>
                            </div>
                            <div class="tw-p-6">
                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Assurance</p>
                                        <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $visit->assurance }}</p>
                                    </div>
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">N° d'assurance</p>
                                        <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $visit->numero_assurance ?? 'N/A' }}</p>
                                    </div>
                                    <div class="tw-bg-slate-50 tw-rounded-xl tw-p-4">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Prise en charge</p>
                                        <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $visit->prise_en_charge ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Metadata --}}
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-p-5">
                            <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2 tw-text-xs tw-text-slate-400">
                                <span><i class="fas fa-user tw-mr-1.5"></i>Enregistré par&nbsp;: <span class="tw-font-medium tw-text-slate-600">{{ $visit->user->name ?? 'N/A' }} {{ $visit->user->prenom ?? '' }}</span></span>
                                <span><i class="fas fa-clock tw-mr-1.5"></i>Le {{ $visit->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @if($visit->updated_at->ne($visit->created_at))
                            <div class="tw-flex tw-justify-end tw-mt-2 tw-text-xs tw-text-slate-400">
                                <span><i class="fas fa-edit tw-mr-1.5"></i>Modifié le {{ $visit->updated_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@endsection