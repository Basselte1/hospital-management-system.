@extends('layouts.admin')
@section('title', 'CMCU | Réceptions de Stock')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    {{-- ── Main Content Area ──────────────────────────────────── --}}
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">

        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- ── Page Heading ──────────────────────────────────── --}}
            <div class="tw-mb-6 tw-flex tw-flex-col sm:tw-flex-row sm:tw-items-center sm:tw-justify-between tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Réceptions de Stock</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Historique complet des réceptions de commandes</p>
                </div>

                {{-- Optional: Add a "New" button here if a create route exists --}}
                {{-- <a href="{{ route('stock-receptions.create') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-duration-150 tw-no-underline tw-shrink-0">
                    <i class="fas fa-plus tw-text-xs"></i>
                    Nouvelle Réception
                </a> --}}
            </div>

            {{-- ── Table Card ────────────────────────────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                {{-- Card Header --}}
                <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-truck-loading tw-text-[#1D4ED8] tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Historique des Réceptions</h2>
                    </div>
                    <span class="tw-text-xs tw-text-slate-400 tw-font-medium">
                        {{ $receptions->total() }} entrée{{ $receptions->total() !== 1 ? 's' : '' }}
                    </span>
                </div>

                {{-- Table --}}
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm" id="myTable">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">
                                    N° Réception
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">
                                    Bon de Commande
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">
                                    Date
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">
                                    Réceptionné par
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">
                                    Statut
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @forelse($receptions as $reception)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors tw-duration-100">

                                {{-- N° Réception --}}
                                <td class="tw-px-6 tw-py-4">
                                    <span class="tw-font-semibold tw-text-[#1D4ED8]">
                                        {{ $reception->numero_reception }}
                                    </span>
                                </td>

                                {{-- Bon Commande --}}
                                <td class="tw-px-6 tw-py-4 tw-text-slate-600">
                                    {{ $reception->bonCommande->numero_bon }}
                                </td>

                                {{-- Date --}}
                                <td class="tw-px-6 tw-py-4 tw-text-slate-600 tw-whitespace-nowrap">
                                    <div class="tw-flex tw-items-center tw-gap-1.5">
                                        <i class="far fa-calendar tw-text-slate-400 tw-text-xs"></i>
                                        {{ $reception->date_reception->format('d/m/Y') }}
                                    </div>
                                </td>

                                {{-- Réceptionné par --}}
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-gap-2">
                                        <div class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                            <i class="far fa-user tw-text-slate-400 tw-text-xs"></i>
                                        </div>
                                        <span class="tw-text-slate-600 tw-text-sm">
                                            {{ $reception->receivedBy->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Statut --}}
                                <td class="tw-px-6 tw-py-4">
                                    @if($reception->isValidated())
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-emerald-50 tw-text-emerald-700">
                                            <i class="fas fa-check-circle tw-text-xs"></i>
                                            Validée
                                        </span>
                                    @else
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-amber-50 tw-text-amber-700">
                                            <i class="fas fa-clock tw-text-xs"></i>
                                            En attente
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-justify-end tw-gap-2">
                                        <a href="{{ route('stock-receptions.show', $reception->id) }}"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-transition-colors tw-duration-150 tw-no-underline"
                                           title="Voir les détails">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>
                                        <a href="{{ route('stock-receptions.pdf', $reception->id) }}"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-transition-colors tw-duration-150 tw-no-underline"
                                           title="Télécharger PDF">
                                            <i class="fas fa-file-pdf tw-text-xs"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="tw-px-6 tw-py-16 tw-text-center">
                                    <div class="tw-flex tw-flex-col tw-items-center tw-gap-3">
                                        <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fas fa-inbox tw-text-slate-400 tw-text-xl"></i>
                                        </div>
                                        <p class="tw-text-slate-400 tw-text-sm tw-font-medium tw-mb-0">Aucune réception trouvée</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($receptions->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $receptions->links() }}
                </div>
                @endif

            </div>{{-- /card --}}

        </main>
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection