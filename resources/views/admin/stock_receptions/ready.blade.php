@extends('layouts.admin')
@section('title', 'CMCU | Commandes Prêtes pour Réception')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Commandes Prêtes pour Réception</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Bons de commande validés en attente de réception du stock</p>
                </div>
                <a href="{{ route('bon-commandes.index') }}"
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

            {{-- Empty State --}}
            @if($ordersReady->count() == 0)
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-20">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-inbox tw-text-slate-400 tw-text-xl"></i>
                    </div>
                    <p class="tw-text-slate-400 tw-text-sm tw-font-medium tw-mb-0">Aucune commande en attente de réception</p>
                </div>
            </div>

            @else

            {{-- Table --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-box tw-text-[#1D4ED8] tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Commandes à Réceptionner</h2>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">N° Bon</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Fournisseur</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Date Commande</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Montant</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Validé par</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Date Validation</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($ordersReady as $bon)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-6 tw-py-4 tw-font-semibold tw-text-slate-800 tw-font-mono">{{ $bon->numero_bon }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-slate-700">{{ $bon->fournisseur_nom }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-slate-600">{{ $bon->date_commande->format('d/m/Y') }}</td>
                                <td class="tw-px-6 tw-py-4 tw-font-medium tw-text-slate-700">{{ number_format($bon->montant_total, 0, ',', ' ') }} FCFA</td>
                                <td class="tw-px-6 tw-py-4 tw-text-xs tw-text-slate-500">{{ $bon->validatedBy->name ?? 'N/A' }}</td>
                                <td class="tw-px-6 tw-py-4 tw-text-xs tw-text-slate-500">{{ $bon->validated_at ? $bon->validated_at->format('d/m/Y') : 'N/A' }}</td>
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-gap-2">
                                        <a href="{{ route('bon-commandes.show', $bon->id) }}"
                                           title="Voir détails"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>
                                        <a href="{{ route('stock-receptions.create', $bon->id) }}"
                                           title="Réceptionner"
                                           class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-emerald-500 hover:tw-bg-emerald-600 tw-text-white tw-text-xs tw-font-medium tw-transition-colors tw-no-underline">
                                            <i class="fas fa-box tw-text-xs"></i> Réceptionner
                                        </a>
                                        <a href="{{ route('bon-commandes.pdf', $bon->id) }}"
                                           title="Télécharger PDF"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-transition-colors tw-no-underline">
                                            <i class="fas fa-file-pdf tw-text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($ordersReady->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $ordersReady->links() }}
                </div>
                @endif
            </div>
            @endif

        </main>
    </div>
</div>
<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection