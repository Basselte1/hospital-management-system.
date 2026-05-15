@extends('layouts.admin')
@section('title', 'CMCU | Produits à Collecter')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Produits à Collecter</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Produits utilisés en attente de collecte pour stérilisation</p>
                </div>
                <a href="{{ route('reusable-products.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            {{-- Flash --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i> {{ session('error') }}
            </div>
            @endif

            @if($pendingUsages->count() == 0)
            {{-- Empty State --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-20">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-box-open tw-text-slate-400 tw-text-xl"></i>
                    </div>
                    <p class="tw-text-slate-400 tw-text-sm tw-font-medium tw-mb-0">Aucun produit en attente de collecte</p>
                </div>
            </div>

            @else

            {{-- Stat Cards --}}
            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4 tw-mb-6">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-clock tw-text-amber-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Usages en Attente</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $pendingUsages->total() }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-cubes tw-text-[#1D4ED8]"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Unités à Collecter</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $pendingUsages->sum('quantite_retournable') }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-red-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-times-circle tw-text-red-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Unités Perdues</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $pendingUsages->sum('quantite_perdue') }}</p>
                    </div>
                </div>
            </div>

            {{-- Usage Cards --}}
            <div class="tw-space-y-4">
                @foreach($pendingUsages as $usage)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                    {{-- Card Header --}}
                    <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-bg-amber-50/50">
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-box tw-text-amber-600 tw-text-sm"></i>
                            </div>
                            <span class="tw-font-semibold tw-text-slate-800">{{ $usage->produit->designation }}</span>
                        </div>
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-slate-100 tw-text-slate-500">
                                #{{ $usage->id }}
                            </span>
                            <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">
                                {{ $usage->getTypeUtilisationLabel() }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="tw-p-6">
                        <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-6">

                            {{-- Usage Info --}}
                            <div>
                                <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-3 tw-flex tw-items-center tw-gap-1.5">
                                    <i class="fas fa-info-circle tw-text-[#1D4ED8]"></i> Informations d'Utilisation
                                </p>
                                <div class="tw-space-y-2 tw-text-sm">
                                    <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Date</span><span class="tw-text-slate-700">{{ $usage->date_utilisation->format('d/m/Y') }}@if($usage->heure_utilisation) à {{ $usage->heure_utilisation }}@endif</span></div>
                                    <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Service</span><span class="tw-text-slate-700">{{ $usage->service ?? 'N/A' }}</span></div>
                                    @if($usage->patient)
                                    <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Patient</span><span class="tw-text-slate-700">{{ $usage->patient->name }} {{ $usage->patient->prenom }}</span></div>
                                    @endif
                                    @if($usage->medecin)
                                    <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Médecin</span><span class="tw-text-slate-700">{{ $usage->medecin->name }}</span></div>
                                    @endif
                                    @if($usage->infirmier)
                                    <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Infirmier(ère)</span><span class="tw-text-slate-700">{{ $usage->infirmier->name }}</span></div>
                                    @endif
                                    <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Enregistré par</span><span class="tw-text-slate-700">{{ $usage->enregistrePar->name }}</span></div>
                                </div>
                            </div>

                            {{-- Quantities --}}
                            <div>
                                <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-3 tw-flex tw-items-center tw-gap-1.5">
                                    <i class="fas fa-calculator tw-text-emerald-500"></i> Quantités
                                </p>
                                <div class="tw-grid tw-grid-cols-3 tw-gap-3 tw-mb-4">
                                    <div class="tw-text-center tw-bg-slate-50 tw-rounded-xl tw-p-3 tw-border tw-border-slate-100">
                                        <p class="tw-text-xl tw-font-bold tw-text-slate-700 tw-mb-0">{{ $usage->quantite }}</p>
                                        <p class="tw-text-xs tw-text-slate-400 tw-mt-0.5 tw-mb-0">Utilisée</p>
                                    </div>
                                    <div class="tw-text-center tw-bg-emerald-50 tw-rounded-xl tw-p-3 tw-border tw-border-emerald-100">
                                        <p class="tw-text-xl tw-font-bold tw-text-emerald-700 tw-mb-0">{{ $usage->quantite_retournable }}</p>
                                        <p class="tw-text-xs tw-text-emerald-500 tw-mt-0.5 tw-mb-0">Retournable</p>
                                    </div>
                                    <div class="tw-text-center tw-bg-red-50 tw-rounded-xl tw-p-3 tw-border tw-border-red-100">
                                        <p class="tw-text-xl tw-font-bold tw-text-red-600 tw-mb-0">{{ $usage->quantite_perdue }}</p>
                                        <p class="tw-text-xs tw-text-red-400 tw-mt-0.5 tw-mb-0">Perdue</p>
                                    </div>
                                </div>
                                @if($usage->motif)
                                <div class="tw-flex tw-items-start tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/20 tw-border tw-border-[#BFDBFE] tw-px-3 tw-py-2.5 tw-text-xs tw-text-slate-600 tw-mb-2">
                                    <i class="fas fa-sticky-note tw-text-[#1D4ED8] tw-shrink-0 tw-mt-0.5"></i>
                                    <span><strong>Motif :</strong> {{ $usage->motif }}</span>
                                </div>
                                @endif
                                @if($usage->observations)
                                <div class="tw-flex tw-items-start tw-gap-2 tw-rounded-xl tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-3 tw-py-2.5 tw-text-xs tw-text-amber-700">
                                    <i class="fas fa-exclamation-circle tw-shrink-0 tw-mt-0.5"></i>
                                    <span><strong>Observations :</strong> {{ $usage->observations }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Collect Button --}}
                        <div class="tw-mt-5 tw-flex tw-justify-end tw-pt-4 tw-border-t tw-border-slate-100">
                            @if($usage->canBeCollected())
                            <form action="{{ route('reusable-products.usages.collect', $usage->id) }}" method="POST"
                                  onsubmit="return confirm('Confirmer la collecte de {{ $usage->quantite_retournable }} unité(s) de {{ $usage->produit->designation }} ?');">
                                @csrf
                                <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-[#14B8A6] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-teal-600 tw-transition-colors tw-border-0 tw-cursor-pointer">
                                    <i class="fas fa-check tw-text-xs"></i> Collecter pour Stérilisation
                                </button>
                            </form>
                            @else
                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-2 tw-rounded-xl tw-bg-slate-100 tw-text-slate-400 tw-text-xs tw-font-medium">
                                <i class="fas fa-ban"></i> Non disponible pour collecte
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="tw-px-6 tw-py-3 tw-bg-slate-50 tw-border-t tw-border-slate-100">
                        <p class="tw-text-xs tw-text-slate-400 tw-mb-0">
                            <i class="fas fa-clock tw-mr-1"></i> Enregistré le {{ $usage->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($pendingUsages->hasPages())
            <div class="tw-mt-5">
                {{ $pendingUsages->links() }}
            </div>
            @endif

            @endif

        </main>
    </div>
</div>
<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection