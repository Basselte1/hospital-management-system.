@extends('layouts.admin')
@section('title', 'CMCU | Historique des Modifications')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('viewAuditLogs', \App\Models\Produit::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Historique des Modifications</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Journal complet de toutes les modifications des produits</p>
                </div>
                <a href="{{ route('produits.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            {{-- Filters --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-filter tw-text-[#1D4ED8] tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Filtres</h2>
                </div>
                <div class="tw-p-6">
                    <form method="GET" action="{{ route('produits.audit-logs') }}">
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4">
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Action</label>
                                <select name="action"
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    <option value="">Toutes les actions</option>
                                    @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $action)) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date début</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date fin</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div class="tw-flex tw-items-end tw-gap-2">
                                <button type="submit"
                                        class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer">
                                    <i class="fas fa-search tw-text-xs"></i> Filtrer
                                </button>
                                <a href="{{ route('produits.audit-logs') }}"
                                   class="tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                                    <i class="fas fa-redo tw-text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Audit Logs Table --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-history tw-text-[#1D4ED8] tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Logs d'Audit</h2>
                    </div>
                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-slate-100 tw-text-slate-600">
                        {{ $auditLogs->total() }} entrées
                    </span>
                </div>

                @if($auditLogs->count() == 0)
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-16">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-search tw-text-slate-400 tw-text-xl"></i>
                    </div>
                    <p class="tw-text-slate-400 tw-text-sm tw-font-medium tw-mb-0">Aucun log trouvé avec ces critères</p>
                </div>
                @else
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm" id="myTable">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Date/Heure</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Action</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Produit</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Utilisateur</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Description</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Détails</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($auditLogs as $log)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-6 tw-py-4 tw-text-xs tw-text-slate-500 tw-whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td class="tw-px-6 tw-py-4">
                                    {{-- Dynamic Bootstrap badge kept as-is (runtime value) --}}
                                    <span class="badge bg-{{ $log->getActionColor() }}">
                                        <i class="{{ $log->getActionIcon() }}"></i>
                                        {{ $log->getActionLabel() }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4">
                                    @if($log->produit)
                                    <a href="{{ route('produits.audit-logs.show', $log->produit_id) }}"
                                       class="tw-text-[#1D4ED8] tw-font-medium hover:tw-underline tw-no-underline">
                                        {{ $log->produit->designation }}
                                    </a>
                                    @else
                                    <span class="tw-text-slate-400 tw-italic">Produit supprimé</span>
                                    @endif
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-xs tw-text-slate-500">
                                    <i class="fas fa-user tw-mr-1"></i>{{ $log->user->name ?? 'N/A' }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-slate-600 tw-max-w-xs tw-truncate">{{ $log->description }}</td>
                                <td class="tw-px-6 tw-py-4">
                                    @if($log->old_values || $log->new_values)
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $log->id }}"
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-border-0 tw-cursor-pointer tw-transition-colors">
                                        <i class="fas fa-eye tw-text-xs"></i>
                                    </button>

                                    {{-- Details Modal (Bootstrap shell, Tailwind inner content) --}}
                                    <div class="modal fade" id="detailsModal{{ $log->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
                                                <div class="modal-header bg-{{ $log->getActionColor() }} text-white">
                                                    <h5 class="modal-title">
                                                        <i class="{{ $log->getActionIcon() }}"></i>
                                                        Détails — {{ $log->getActionLabel() }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="tw-p-6 tw-space-y-4">
                                                    <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-text-sm">
                                                        <div><span class="tw-text-slate-500 tw-block tw-text-xs tw-mb-0.5">Date</span><span class="tw-font-medium tw-text-slate-700">{{ $log->created_at->format('d/m/Y H:i:s') }}</span></div>
                                                        <div><span class="tw-text-slate-500 tw-block tw-text-xs tw-mb-0.5">Utilisateur</span><span class="tw-font-medium tw-text-slate-700">{{ $log->user->name ?? 'N/A' }}</span></div>
                                                        <div><span class="tw-text-slate-500 tw-block tw-text-xs tw-mb-0.5">Adresse IP</span><span class="tw-font-mono tw-text-slate-700 tw-text-xs">{{ $log->ip_address ?? 'N/A' }}</span></div>
                                                    </div>

                                                    @if($log->getFormattedChanges())
                                                    <div>
                                                        <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-3">Modifications</p>
                                                        <div class="tw-overflow-x-auto">
                                                            <table class="tw-w-full tw-text-sm">
                                                                <thead>
                                                                    <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                                                        <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Champ</th>
                                                                        <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Ancienne Valeur</th>
                                                                        <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">Nouvelle Valeur</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="tw-divide-y tw-divide-slate-100">
                                                                    @foreach($log->getFormattedChanges() as $change)
                                                                    <tr>
                                                                        <td class="tw-px-4 tw-py-2.5 tw-font-medium tw-text-slate-700">{{ $change['field'] }}</td>
                                                                        <td class="tw-px-4 tw-py-2.5 tw-text-slate-500">{{ $change['old'] ?? 'N/A' }}</td>
                                                                        <td class="tw-px-4 tw-py-2.5 tw-font-semibold tw-text-[#1D4ED8]">{{ $change['new'] }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <p class="tw-text-sm tw-text-slate-400 tw-italic tw-mb-0">Aucun détail de modification disponible</p>
                                                    @endif
                                                </div>
                                                <div class="tw-px-6 tw-pb-6">
                                                    <button type="button" data-bs-dismiss="modal"
                                                            class="tw-w-full tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0 tw-cursor-pointer">
                                                        Fermer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="tw-text-slate-300">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($auditLogs->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $auditLogs->appends(request()->query())->links() }}
                </div>
                @endif
                @endif
            </div>

        </main>
        @endcan
    </div>
</div>
<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection