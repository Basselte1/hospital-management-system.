@extends('layouts.admin')
@section('title', 'Accueil | Tableau de bord')
@section('breadcrumb', 'Tableau de bord')
@section('page_title', 'Accueil')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    {{-- ── Main Content Area ──────────────────────────────────── --}}
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">

        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6">
                <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Tableau de bord</h1>
                <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Bienvenue, <span class="tw-font-medium tw-text-[#1D4ED8]">{{ Auth::user()->name }}</span> — vue d'ensemble de l'activité</p>
            </div>

            {{-- ── PRIMARY STATS ROW ─────────────────────────────── --}}
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-4 tw-gap-4 tw-mb-6">

                @can('update', \App\Models\User::class)
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-duration-200">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-users tw-text-[#1D4ED8] tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Utilisateurs</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $users }}</p>
                    </div>
                </div>
                @endcan

                @can('create', \App\Models\Patient::class)
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-duration-200">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-user-injured tw-text-[#14B8A6] tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Patients</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $patients }}</p>
                    </div>
                </div>
                @endcan

                @can('create', \App\Models\chambre::class)
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-duration-200">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fa-solid fa-bed tw-text-indigo-500 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Chambres</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">{{ $chambres }}</p>
                    </div>
                </div>
                @endcan

                @can('create', \App\Models\Produit::class)
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow tw-duration-200">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-boxes tw-text-amber-500 tw-text-xl"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Produits en Stock</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none tw-mt-1">
                            @if(auth()->user()->role_id === 1)
                                {{ \App\Models\Produit::count() }}
                            @else
                                {{ \App\Models\Produit::where('status', 'approved')->count() }}
                            @endif
                        </p>
                    </div>
                </div>
                @endcan

            </div>

            {{-- ── APPROVAL STATISTICS ───────────────────────────── --}}
            @can('viewPending', \App\Models\Produit::class)
            <div class="tw-mb-6">
                <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-3 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-chart-line tw-text-[#1D4ED8]"></i>
                    Statistiques d'Approbation
                </h2>
                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-4">
                    @can('approve', \App\Models\Produit::class)
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-border-l-4 tw-border-red-400 tw-shadow-sm tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-red-50 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-times-circle tw-text-red-400 tw-text-lg"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Produits Rejetés</p>
                            <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $rejectedCount }}</p>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
            @endcan

            {{-- ── CLINICAL METRICS (IC Role) ────────────────────── --}}
            @can('create', \App\Models\Patient::class)
            @can('show', \App\Models\User::class)
            <div class="tw-mb-6">
                <h2 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-3 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-stethoscope tw-text-[#14B8A6]"></i>
                    Métriques Cliniques
                </h2>
                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-4">

                    {{-- Rendez-vous --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-border-l-4 tw-border-[#1D4ED8] tw-shadow-sm tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-calendar tw-text-[#1D4ED8] tw-text-lg"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Rendez-vous</p>
                            <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $events }}</p>
                        </div>
                    </div>

                    {{-- Patients suivis --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-border-l-4 tw-border-[#14B8A6] tw-shadow-sm tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-teal-50 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-user-check tw-text-[#14B8A6] tw-text-lg"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Patients Suivis</p>
                            <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $patients_suivis }}</p>
                        </div>
                    </div>

                    {{-- Stock faible --}}
                    @can('verifyStock', \App\Models\Produit::class)
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-border-l-4 tw-border-amber-400 tw-shadow-sm tw-flex tw-items-center tw-gap-4 hover:tw-shadow-md tw-transition-shadow">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-exclamation-triangle tw-text-amber-400 tw-text-lg"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-mb-0">Stock Faible</p>
                            <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">
                                {{ \App\Models\Produit::where('status', 'approved')->whereColumn('qte_stock', '<=', 'qte_alerte')->count() }}
                            </p>
                        </div>
                    </div>
                    @endcan

                </div>
            </div>
            @endcan
            @endcan

        </main>
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>
@stop