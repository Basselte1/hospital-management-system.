@extends('layouts.admin')

@section('title', 'CMCU | Historique des visites - ' . $patient->name . ' ' . $patient->prenom)

@section('content')

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-2xl tw-mx-auto">

                {{-- ── Patient Hero Card ────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                    <div class="tw-bg-primary-900 tw-px-6 tw-py-5">
                        <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4">
                            <div class="tw-flex tw-items-center tw-gap-4">
                                <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                    <span class="tw-text-white tw-font-bold tw-text-xl tw-uppercase">{{ mb_substr($patient->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="tw-flex tw-items-center tw-gap-2 tw-mb-0.5">
                                        <span class="tw-text-xs tw-font-semibold tw-text-white/60 tw-uppercase tw-tracking-widest">Historique patient</span>
                                    </div>
                                    <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">{{ $patient->name }} {{ $patient->prenom }}</h1>
                                    <p class="tw-text-white/70 tw-text-sm tw-mb-0">
                                        <i class="fas fa-hashtag tw-mr-1"></i>CMCU-{{ $patient->numero_dossier }}
                                        @if($patient->dossiers->first())
                                            <span class="tw-mx-2">·</span>
                                            <i class="fas fa-phone tw-mr-1"></i>{{ $patient->dossiers->first()->portable_1 ?? 'N/A' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                                <a href="{{ route('patients.show', $patient) }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-text-xs tw-font-semibold tw-px-3.5 tw-py-2 tw-rounded-lg tw-transition-colors tw-duration-150 tw-no-underline tw-border tw-border-white/30">
                                    <i class="fas fa-folder-open tw-text-xs"></i>Dossier complet
                                </a>
                                @can('createVisit', \App\Models\Patient::class)
                                <a href="{{ route('patient-visits.create') }}?patient_id={{ $patient->id }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-emerald-500 hover:tw-bg-emerald-600 tw-text-white tw-text-xs tw-font-semibold tw-px-3.5 tw-py-2 tw-rounded-lg tw-transition-colors tw-duration-150 tw-no-underline tw-border-0">
                                    <i class="fas fa-plus tw-text-xs"></i>Nouvelle visite
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── KPI Cards ────────────────────────────────────────── --}}
                <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-mb-6">

                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-primary-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-calendar-check tw-text-primary-700 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-500 tw-font-medium tw-mb-0.5">Nb de visites</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ number_format($stats['total_visits']) }}</p>
                        </div>
                    </div>

                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-money-bill-wave tw-text-slate-500 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-500 tw-font-medium tw-mb-0.5">Total dépensé</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ number_format($stats['total_spent']) }}&nbsp;F</p>
                        </div>
                    </div>

                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-emerald-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-check-circle tw-text-emerald-500 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-emerald-600 tw-font-medium tw-mb-0.5">Total payé</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ number_format($stats['total_paid']) }}&nbsp;F</p>
                        </div>
                    </div>

                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border {{ $stats['total_remaining'] > 0 ? 'tw-border-red-100' : 'tw-border-emerald-100' }} tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl {{ $stats['total_remaining'] > 0 ? 'tw-bg-red-50' : 'tw-bg-emerald-50' }} tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-exclamation-circle tw-text-base {{ $stats['total_remaining'] > 0 ? 'tw-text-red-500' : 'tw-text-emerald-500' }}"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-font-medium tw-mb-0.5 {{ $stats['total_remaining'] > 0 ? 'tw-text-red-500' : 'tw-text-emerald-600' }}">Reste à payer</p>
                            <p class="tw-text-2xl tw-font-bold tw-leading-none {{ $stats['total_remaining'] > 0 ? 'tw-text-red-600' : 'tw-text-emerald-700' }}">{{ number_format($stats['total_remaining']) }}&nbsp;F</p>
                        </div>
                    </div>

                </div>

                {{-- ── Visits Table ─────────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-primary-700"></div>
                        <span class="tw-text-sm tw-font-semibold tw-text-slate-700">
                            <i class="fas fa-history tw-mr-2 tw-text-slate-400"></i>Historique des visites
                        </span>
                    </div>

                    @if($visits->count() > 0)
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm">
                            <thead>
                                <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-200">
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Date</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Motif</th>
                                    @if(auth()->user()->role_id !== 2)
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Médecin</th>
                                    @endif
                                    <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Montant</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Avance</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Reste</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Paiement</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Statut</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                @foreach($visits as $visit)
                                <tr class="hover:tw-bg-slate-50/70 tw-transition-colors tw-duration-100">

                                    <td class="tw-px-5 tw-py-3.5 tw-whitespace-nowrap">
                                        <span class="tw-font-semibold tw-text-slate-800 tw-text-sm">{{ $visit->visit_date->format('d/m/Y') }}</span>
                                        @if($visit->isToday())
                                            <br><span class="tw-inline-flex tw-items-center tw-text-[10px] tw-font-semibold tw-text-sky-700 tw-bg-sky-50 tw-border tw-border-sky-200 tw-px-2 tw-py-0.5 tw-rounded-full tw-mt-0.5">Aujourd'hui</span>
                                        @elseif($visit->isRecent())
                                            <br><span class="tw-inline-flex tw-items-center tw-text-[10px] tw-font-semibold tw-text-amber-700 tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-2 tw-py-0.5 tw-rounded-full tw-mt-0.5">Récent</span>
                                        @endif
                                    </td>

                                    <td class="tw-px-5 tw-py-3.5">
                                        <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $visit->motif ?? 'Non spécifié' }}</p>
                                        @if($visit->details_motif)
                                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">{{ str($visit->details_motif)->limit(50) }}</p>
                                        @endif
                                    </td>

                                    @if(auth()->user()->role_id !== 2)
                                    <td class="tw-px-5 tw-py-3.5 tw-text-sm tw-text-slate-600">{{ $visit->medecin_r ?? '—' }}</td>
                                    @endif

                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-whitespace-nowrap tw-font-semibold tw-text-slate-800">{{ number_format($visit->montant) }}&nbsp;F</td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-whitespace-nowrap tw-text-slate-600">{{ number_format($visit->avance) }}&nbsp;F</td>

                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-whitespace-nowrap tw-font-semibold {{ $visit->reste > 0 ? 'tw-text-red-600' : 'tw-text-emerald-600' }}">
                                        {{ number_format($visit->reste) }}&nbsp;F
                                    </td>

                                    <td class="tw-px-5 tw-py-3.5">
                                        <span class="tw-inline-flex tw-items-center tw-text-xs tw-font-medium tw-text-slate-600 tw-bg-slate-100 tw-px-2.5 tw-py-1 tw-rounded-full">
                                            {{ $visit->mode_paiement ?? 'espèce' }}
                                        </span>
                                    </td>

                                    <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                        <span class="badge bg-{{ $visit->status_badge_color }} tw-text-xs tw-px-2.5 tw-py-1 tw-rounded-full">
                                            {{ $visit->status_label }}
                                        </span>
                                    </td>

                                    <td class="tw-px-5 tw-py-3.5">
                                        <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                            <a href="{{ route('patient-visits.show', $visit) }}"
                                               class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-primary-200 tw-bg-primary-50 hover:tw-bg-primary-100 tw-text-primary-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                               title="Voir détails">
                                                <i class="fas fa-eye tw-text-xs"></i>
                                            </a>
                                            @can('delete', \App\Models\Patient::class)
                                            <form action="{{ route('patient-visits.destroy', $visit) }}" method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette visite ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-red-200 tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash tw-text-xs"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="tw-bg-slate-100 tw-border-t-2 tw-border-slate-300">
                                    <td colspan="{{ auth()->user()->role_id === 2 ? 2 : 3 }}" class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-bold tw-text-slate-600 tw-uppercase tw-tracking-wide">Totaux</td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-font-bold tw-text-slate-800">{{ number_format($stats['total_spent']) }}&nbsp;F</td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-font-bold tw-text-emerald-700">{{ number_format($stats['total_paid']) }}&nbsp;F</td>
                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-font-bold {{ $stats['total_remaining'] > 0 ? 'tw-text-red-600' : 'tw-text-emerald-700' }}">{{ number_format($stats['total_remaining']) }}&nbsp;F</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($visits->hasPages())
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-bg-slate-50/50">
                        {{ $visits->links() }}
                    </div>
                    @endif

                    @else
                    <div class="tw-text-center tw-py-16">
                        <div class="tw-w-16 tw-h-16 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-4">
                            <i class="fas fa-inbox tw-text-slate-300 tw-text-3xl"></i>
                        </div>
                        <h5 class="tw-text-slate-500 tw-font-semibold tw-mb-1">Aucune visite enregistrée</h5>
                        <p class="tw-text-sm tw-text-slate-400 tw-mb-4">Ce patient n'a pas encore d'historique de visites</p>
                        @can('create', \App\Models\Patient::class)
                        <a href="{{ route('patient-visits.create') }}?patient_id={{ $patient->id }}"
                           class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-emerald-600 hover:tw-bg-emerald-700 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-rounded-xl tw-no-underline tw-transition-colors tw-duration-150">
                            <i class="fas fa-plus tw-text-xs"></i>Enregistrer la première visite
                        </a>
                        @endcan
                    </div>
                    @endif
                </div>

                {{-- ── Timeline ─────────────────────────────────────────── --}}
                @if($visits->count() > 0)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-teal-500"></div>
                        <span class="tw-text-sm tw-font-semibold tw-text-slate-700">
                            <i class="fas fa-stream tw-mr-2 tw-text-slate-400"></i>Vue chronologique
                        </span>
                    </div>
                    <div class="tw-p-6">
                        <div class="tw-space-y-4">
                            @foreach($visits as $visit)
                            <div class="tw-flex tw-gap-4">
                                {{-- Timeline dot --}}
                                <div class="tw-flex tw-flex-col tw-items-center tw-shrink-0">
                                    <div class="tw-w-8 tw-h-8 tw-rounded-full badge bg-{{ $visit->status_badge_color }} tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-check tw-text-white tw-text-xs"></i>
                                    </div>
                                    @if(!$loop->last)
                                    <div class="tw-w-px tw-flex-1 tw-bg-slate-200 tw-mt-1"></div>
                                    @endif
                                </div>
                                {{-- Content --}}
                                <div class="tw-flex-1 tw-pb-4">
                                    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-border-l-4 tw-border-l-primary-400 hover:tw-shadow-card-md tw-transition-shadow tw-duration-150">
                                        <div class="tw-px-5 tw-py-4">
                                            <div class="tw-flex tw-items-start tw-justify-between tw-gap-3 tw-mb-2">
                                                <h6 class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $visit->motif ?? 'Visite médicale' }}</h6>
                                                <span class="tw-text-xs tw-text-slate-400 tw-shrink-0">{{ $visit->visit_date->format('d/m/Y') }}</span>
                                            </div>
                                            <p class="tw-text-xs tw-text-slate-500 tw-mb-3">
                                                <i class="fas fa-user-md tw-mr-1.5"></i>{{ $visit->medecin_r ?? 'Non spécifié' }}
                                            </p>
                                            <div class="tw-flex tw-items-center tw-gap-4 tw-text-xs tw-text-slate-600">
                                                <span><i class="fas fa-money-bill tw-mr-1 tw-text-slate-400"></i>{{ number_format($visit->montant) }}&nbsp;F</span>
                                                <span><i class="fas fa-hand-holding-usd tw-mr-1 tw-text-slate-400"></i>{{ number_format($visit->avance) }}&nbsp;F</span>
                                                @if($visit->reste > 0)
                                                <span class="tw-text-red-500 tw-font-semibold">
                                                    <i class="fas fa-exclamation-circle tw-mr-1"></i>Reste : {{ number_format($visit->reste) }}&nbsp;F
                                                </span>
                                                @else
                                                <span class="tw-text-emerald-600 tw-font-semibold">
                                                    <i class="fas fa-check-circle tw-mr-1"></i>Soldé
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </main>
    </div>
</div>

@endsection