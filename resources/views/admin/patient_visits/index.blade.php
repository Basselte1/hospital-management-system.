@extends('layouts.admin')
@section('title', 'CMCU | Historique des visites')
@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-2xl tw-mx-auto">
                <!-- All existing page content goes here -->
                <div class="tw-min-h-screen tw-bg-slate-50">
                    <div class="tw-max-w-screen-2xl tw-mx-auto tw-px-4 tw-py-8 sm:tw-px-6 lg:tw-px-8">

                        {{-- ── Page Header ──────────────────────────────────────── --}}
                        <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-8">
                            <div>
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                    <span class="tw-text-xs tw-font-semibold tw-tracking-widest tw-text-primary-700 tw-uppercase tw-bg-primary-100 tw-px-2.5 tw-py-1 tw-rounded-full">Visites</span>
                                </div>
                                <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-tracking-tight">
                                    @if(auth()->user()->role_id === 2)
                                        Mes visites patients
                                    @else
                                        Historique des visites patients
                                    @endif
                                </h1>
                                @if(auth()->user()->role_id === 2)
                                <p class="tw-text-sm tw-text-slate-500 tw-mt-0.5">
                                    <i class="fas fa-user-md tw-mr-1.5"></i>{{ auth()->user()->name }} {{ auth()->user()->prenom }}
                                </p>
                                @endif
                            </div>
                            @can('createVisit', \App\Models\Patient::class)
                            <a href="{{ route('patient-visits.create') }}"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-emerald-600 hover:tw-bg-emerald-700 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-rounded-xl tw-transition-all tw-duration-150 tw-no-underline tw-whitespace-nowrap">
                                <i class="fas fa-plus tw-text-xs"></i>Nouvelle visite
                            </a>
                            @endcan
                        </div>

                        {{-- ── KPI Cards ────────────────────────────────────────── --}}
                        <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-mb-6">

                            <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                                <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-primary-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                    <i class="fas fa-calendar-check tw-text-primary-700 tw-text-base"></i>
                                </div>
                                <div>
                                    <p class="tw-text-xs tw-text-slate-500 tw-font-medium tw-mb-0.5">Total visites</p>
                                    <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ number_format($stats['total_visits']) }}</p>
                                </div>
                            </div>

                            <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-amber-100 tw-flex tw-items-center tw-gap-4">
                                <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                    <i class="fas fa-clock tw-text-amber-500 tw-text-base"></i>
                                </div>
                                <div>
                                    <p class="tw-text-xs tw-text-amber-600 tw-font-medium tw-mb-0.5">Aujourd'hui</p>
                                    <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ number_format($stats['today_visits']) }}</p>
                                </div>
                            </div>

                            <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-sky-100 tw-flex tw-items-center tw-gap-4">
                                <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-sky-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                    <i class="fas fa-hourglass-half tw-text-sky-500 tw-text-base"></i>
                                </div>
                                <div>
                                    <p class="tw-text-xs tw-text-sky-600 tw-font-medium tw-mb-0.5">En attente</p>
                                    <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ number_format($stats['pending_visits']) }}</p>
                                </div>
                            </div>

                            <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-emerald-100 tw-flex tw-items-center tw-gap-4">
                                <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                    <i class="fas fa-money-bill-wave tw-text-emerald-500 tw-text-base"></i>
                                </div>
                                <div>
                                    <p class="tw-text-xs tw-text-emerald-600 tw-font-medium tw-mb-0.5">Revenu total</p>
                                    <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ number_format($stats['total_revenue']) }}&nbsp;F</p>
                                </div>
                            </div>

                        </div>

                        {{-- ── Filter Card ──────────────────────────────────────── --}}
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                            <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2.5">
                                <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-slate-300"></div>
                                <span class="tw-text-sm tw-font-semibold tw-text-slate-600">
                                    <i class="fas fa-filter tw-mr-2 tw-text-slate-400"></i>Filtres
                                </span>
                            </div>
                            <div class="tw-p-5">
                                <form action="{{ route('patient-visits.index') }}" method="GET">
                                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-6 tw-gap-3 tw-items-end">

                                        <div class="lg:tw-col-span-2">
                                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Recherche</label>
                                            <input type="text" name="search" class="form-control"
                                                value="{{ $search }}" placeholder="Nom ou numéro dossier">
                                        </div>

                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Statut</label>
                                            <select name="status" class="form-control">
                                                <option value="">Tous</option>
                                                <option value="en_attente" {{ $status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                <option value="en_cours"   {{ $status == 'en_cours'   ? 'selected' : '' }}>En cours</option>
                                                <option value="terminee"   {{ $status == 'terminee'   ? 'selected' : '' }}>Terminée</option>
                                                <option value="annulee"    {{ $status == 'annulee'    ? 'selected' : '' }}>Annulée</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date début</label>
                                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                                        </div>

                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date fin</label>
                                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                                        </div>

                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Par page</label>
                                            <div class="tw-flex tw-gap-2">
                                                <select name="per_page" class="form-control">
                                                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                                </select>
                                                <button type="submit"
                                                        class="tw-px-3 tw-py-2 tw-rounded-lg tw-bg-primary-700 hover:tw-bg-primary-800 tw-text-white tw-text-sm tw-font-medium tw-border-0 tw-cursor-pointer tw-transition-colors tw-duration-150 tw-shrink-0">
                                                    <i class="fas fa-filter tw-text-xs"></i>
                                                </button>
                                                <a href="{{ route('patient-visits.index') }}"
                                                class="tw-px-3 tw-py-2 tw-rounded-lg tw-bg-white hover:tw-bg-slate-50 tw-text-slate-500 tw-text-sm tw-border tw-border-slate-200 tw-cursor-pointer tw-transition-colors tw-duration-150 tw-shrink-0 tw-flex tw-items-center">
                                                    <i class="fas fa-redo tw-text-xs"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- ── Table Card ───────────────────────────────────────── --}}
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">

                            <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                                <div class="tw-flex tw-items-center tw-gap-2.5">
                                    <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-primary-700"></div>
                                    <span class="tw-text-sm tw-font-semibold tw-text-slate-700">Résultats</span>
                                </div>
                                @if($visits->total())
                                <span class="tw-text-xs tw-text-slate-400">{{ $visits->total() }} visite(s)</span>
                                @endif
                            </div>

                            <div class="tw-overflow-x-auto">
                                <table class="tw-w-full tw-text-sm">
                                    <thead>
                                        <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-200">
                                            <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Date</th>
                                            <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Patient</th>
                                            <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Motif</th>
                                            @if(auth()->user()->role_id !== 2)
                                            <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Médecin</th>
                                            @endif
                                            @if(auth()->user()->role_id === 1 || auth()->user()->role_id === 6)
                                            <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Montant</th>
                                            <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Avance</th>
                                            <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Reste</th>
                                            @endif
                                            <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Statut</th>
                                            <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tw-divide-y tw-divide-slate-100">
                                        @forelse($visits as $visit)
                                        <tr class="hover:tw-bg-slate-50/70 tw-transition-colors tw-duration-100">

                                            {{-- Date --}}
                                            <td class="tw-px-5 tw-py-3.5 tw-whitespace-nowrap">
                                                <span class="tw-font-semibold tw-text-slate-800 tw-text-sm">{{ $visit->visit_date->format('d/m/Y') }}</span>
                                                @if($visit->isToday())
                                                    <br><span class="tw-inline-flex tw-items-center tw-text-[10px] tw-font-semibold tw-text-sky-700 tw-bg-sky-50 tw-border tw-border-sky-200 tw-px-2 tw-py-0.5 tw-rounded-full tw-mt-0.5">Aujourd'hui</span>
                                                @endif
                                            </td>

                                            {{-- Patient --}}
                                            <td class="tw-px-5 tw-py-3.5">
                                                <p class="tw-font-semibold tw-text-slate-800 tw-mb-0 tw-text-sm">{{ $visit->patient_display_name }}</p>
                                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">
                                                    @if($visit->patient_numero_dossier)
                                                        CMCU-{{ $visit->patient_numero_dossier }}
                                                    @else
                                                        <em class="tw-text-amber-500">Patient supprimé</em>
                                                    @endif
                                                </p>
                                            </td>

                                            {{-- Motif --}}
                                            <td class="tw-px-5 tw-py-3.5 tw-text-sm tw-text-slate-600" title="{{ $visit->details_motif }}">
                                                {{ $visit->motif ? \Illuminate\Support\Str::limit($visit->motif, 30) : '—' }}
                                            </td>

                                            @if(auth()->user()->role_id !== 2)
                                            <td class="tw-px-5 tw-py-3.5 tw-text-sm tw-text-slate-600">{{ $visit->medecin_r ?? '—' }}</td>
                                            @endif

                                            @if(auth()->user()->role_id === 1 || auth()->user()->role_id === 6)
                                            <td class="tw-px-5 tw-py-3.5 tw-text-right tw-text-sm tw-font-semibold tw-text-slate-800 tw-whitespace-nowrap">{{ number_format($visit->montant) }}&nbsp;F</td>
                                            <td class="tw-px-5 tw-py-3.5 tw-text-right tw-text-sm tw-text-slate-600 tw-whitespace-nowrap">{{ number_format($visit->avance) }}&nbsp;F</td>
                                            <td class="tw-px-5 tw-py-3.5 tw-text-right tw-whitespace-nowrap tw-font-semibold {{ $visit->reste > 0 ? 'tw-text-red-600' : 'tw-text-emerald-600' }}">
                                                {{ number_format($visit->reste) }}&nbsp;F
                                            </td>
                                            @endif

                                            {{-- Statut --}}
                                            <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                                <span class="badge bg-{{ $visit->status_badge_color }} tw-text-xs tw-px-2.5 tw-py-1 tw-rounded-full">
                                                    {{ $visit->status_label }}
                                                </span>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="tw-px-5 tw-py-3.5">
                                                <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                                    <a href="{{ route('patient-visits.show', $visit) }}"
                                                    class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-primary-200 tw-bg-primary-50 hover:tw-bg-primary-100 tw-text-primary-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                    title="Voir détails">
                                                        <i class="fas fa-eye tw-text-xs"></i>
                                                    </a>
                                                    @if($visit->patient)
                                                    <a href="{{ route('patients.show', $visit->patient) }}"
                                                    class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-sky-200 tw-bg-sky-50 hover:tw-bg-sky-100 tw-text-sky-600 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                    title="Dossier patient">
                                                        <i class="fas fa-folder-open tw-text-xs"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="tw-text-center tw-py-16">
                                                <div class="tw-w-16 tw-h-16 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-4">
                                                    <i class="fas fa-inbox tw-text-slate-300 tw-text-3xl"></i>
                                                </div>
                                                <p class="tw-text-slate-500 tw-font-medium tw-mb-1">Aucune visite trouvée</p>
                                                <p class="tw-text-sm tw-text-slate-400">Modifiez vos filtres ou créez une nouvelle visite</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if($visits->hasPages())
                            <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-bg-slate-50/50">
                                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-center tw-justify-between tw-gap-3">
                                    
                                    {{ $visits->appends(request()->query())->links() }}
                                </div>
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