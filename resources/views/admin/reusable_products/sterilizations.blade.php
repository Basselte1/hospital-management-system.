@extends('layouts.admin')
@section('title', 'CMCU | Stérilisations')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6">
                <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Historique des Stérilisations</h1>
                <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Suivi de tous les lots de stérilisation</p>
            </div>

            {{-- Flash --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('warning'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-amber-700">
                <i class="fas fa-exclamation-triangle tw-text-amber-500"></i> {{ session('warning') }}
            </div>
            @endif

            {{-- Filters --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-filter tw-text-slate-500 tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Filtres</h2>
                </div>
                <div class="tw-p-6">
                    <form action="{{ route('reusable-products.sterilizations.index') }}" method="GET">
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4">
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Statut</label>
                                <select name="statut"
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    <option value="">Tous</option>
                                    <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En Cours</option>
                                    <option value="termine_en_attente" {{ request('statut') == 'termine_en_attente' ? 'selected' : '' }}>Terminé — En Attente</option>
                                    <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                                    <option value="retourne" {{ request('statut') == 'retourne' ? 'selected' : '' }}>Retourné au Stock</option>
                                    <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                                </select>
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date Début</label>
                                <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date Fin</label>
                                <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div class="tw-flex tw-items-end tw-gap-2">
                                <button type="submit"
                                        class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer">
                                    <i class="fas fa-search tw-text-xs"></i> Filtrer
                                </button>
                                <a href="{{ route('reusable-products.sterilizations.index') }}"
                                   class="tw-inline-flex tw-items-center tw-justify-center tw-w-10 tw-h-10 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-500 tw-no-underline tw-transition-colors tw-shrink-0">
                                    <i class="fas fa-times tw-text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Stat Cards --}}
            @php
                $stats = [
                    'en_cours'    => $sterilizations->where('statut', 'en_cours')->count(),
                    'en_attente'  => $sterilizations->where('statut', 'termine_en_attente')->count(),
                    'valide'      => $sterilizations->where('statut', 'valide')->count(),
                    'retourne'    => $sterilizations->where('statut', 'retourne')->count(),
                ];
            @endphp
            <div class="tw-grid tw-grid-cols-2 xl:tw-grid-cols-4 tw-gap-4 tw-mb-5">
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-sky-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-fire tw-text-sky-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">En Cours</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $stats['en_cours'] }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-clock tw-text-amber-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">En Attente</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $stats['en_attente'] }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-check-double tw-text-emerald-500"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Validés</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $stats['valide'] }}</p>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-undo tw-text-[#1D4ED8]"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-400 tw-uppercase tw-tracking-wide tw-font-medium tw-mb-0">Retournés</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-mt-0.5">{{ $stats['retourne'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Table or Empty State --}}
            @if($sterilizations->count() == 0)
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-16">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-fire tw-text-slate-400 tw-text-xl"></i>
                    </div>
                    <p class="tw-text-slate-400 tw-text-sm tw-font-medium tw-mb-0">Aucune stérilisation trouvée</p>
                </div>
            </div>
            @else
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">N° Lot</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Produit</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Quantité</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Méthode</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Date</th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Statut</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Stérilisé par</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($sterilizations as $sterilization)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-6 tw-py-4 tw-font-semibold tw-font-mono tw-text-slate-800">{{ $sterilization->numero_lot }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-slate-700">{{ $sterilization->produit->designation }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-slate-100 tw-text-slate-600">
                                        {{ $sterilization->quantite }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-xs tw-text-slate-500">{{ $sterilization->getMethodeLabel() }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-slate-600">{{ $sterilization->date_sterilisation->format('d/m/Y') }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    {{-- Dynamic Bootstrap badge kept as-is (runtime value) --}}
                                    <span class="badge bg-{{ $sterilization->getStatutBadgeColor() }}">
                                        {{ $sterilization->getStatutLabel() }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-xs tw-text-slate-500">{{ $sterilization->sterilisePar->name }}</td>
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-gap-2">
                                        <a href="{{ route('reusable-products.sterilizations.show', $sterilization->id) }}"
                                           title="Voir détails"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>
                                        @if($sterilization->isRetourne())
                                        <a href="{{ route('reusable-products.sterilizations.certificate', $sterilization->id) }}"
                                           title="Certificat PDF"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-transition-colors tw-no-underline">
                                            <i class="fas fa-file-pdf tw-text-xs"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($sterilizations->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $sterilizations->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
            @endif

            {{-- Bottom Actions --}}
            <div class="tw-flex tw-justify-center tw-gap-3 tw-mt-6">
                <a href="{{ route('reusable-products.sterilizations.create.form') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-sky-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-sky-600 tw-transition-colors tw-no-underline">
                    <i class="fas fa-plus tw-text-xs"></i> Nouvelle Stérilisation
                </a>
                <a href="{{ route('reusable-products.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Tableau de Bord
                </a>
            </div>

        </main>
    </div>
</div>
<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection