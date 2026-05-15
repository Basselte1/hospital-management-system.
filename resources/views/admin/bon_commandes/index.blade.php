@extends('layouts.admin')
@section('title', 'CMCU | Bons de Commande')
@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-2xl tw-mx-auto">
                {{-- ── Page Header ──────────────────────────────────────────── --}}
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-6">
                    {{-- ... existing content ... --}}
                    <div>
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                            <span class="tw-text-xs tw-font-semibold tw-tracking-widest tw-text-primary-700 tw-uppercase tw-bg-primary-100 tw-px-2.5 tw-py-1 tw-rounded-full">Stock & Approvisionnement</span>
                        </div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-tracking-tight">Bons de Commande</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-0.5">Gestion des bons de commande fournisseurs</p>
                    </div>

                    @if(in_array(auth()->user()->role_id, [1, 3, 5]))
                    <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                        @if(auth()->user()->role_id === 3 || auth()->user()->role_id === 1)
                        @php $pendingValidation = \App\Models\BonCommande::where('statut', 'envoye')->count(); @endphp
                        <a href="{{ route('bon-commandes.validation') }}"
                        class="tw-relative tw-inline-flex tw-items-center tw-gap-2 tw-bg-amber-500 hover:tw-bg-amber-600 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-rounded-xl tw-transition-all tw-duration-150 tw-no-underline tw-border-0 tw-whitespace-nowrap">
                            <i class="fas fa-clipboard-check tw-text-xs"></i>
                            Valider les commandes
                            @if($pendingValidation > 0)
                            <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-5 tw-h-5 tw-rounded-full tw-bg-red-500 tw-text-white tw-text-[10px] tw-font-bold">{{ $pendingValidation }}</span>
                            @endif
                        </a>
                        @endif
                        <a href="{{ route('bon-commandes.create') }}"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-primary-700 hover:tw-bg-primary-800 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-rounded-xl tw-shadow-blue tw-transition-all tw-duration-150 tw-no-underline tw-whitespace-nowrap">
                            <i class="fas fa-plus tw-text-xs"></i>
                            Nouveau bon
                        </a>
                    </div>
                    @endif
                </div>
                {{-- ... rest of existing content ... --}}
                
            {{-- ── Flash Messages ───────────────────────────────────────── --}}
            @if(session('success'))
            <div class="tw-flex tw-items-start tw-gap-3 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-text-emerald-800 tw-rounded-xl tw-px-5 tw-py-4 tw-mb-5 tw-text-sm">
                <i class="fas fa-check-circle tw-text-emerald-500 tw-mt-0.5 tw-shrink-0"></i>
                <p class="tw-mb-0">{{ session('success') }}</p>
                <button type="button" class="tw-ml-auto tw-text-emerald-400 hover:tw-text-emerald-600 tw-border-0 tw-bg-transparent tw-cursor-pointer" data-bs-dismiss="alert"><i class="fas fa-times tw-text-xs"></i></button>
            </div>
            @endif

            @if(session('error'))
            <div class="tw-flex tw-items-start tw-gap-3 tw-bg-red-50 tw-border tw-border-red-200 tw-text-red-700 tw-rounded-xl tw-px-5 tw-py-4 tw-mb-5 tw-text-sm">
                <i class="fas fa-exclamation-circle tw-text-red-500 tw-mt-0.5 tw-shrink-0"></i>
                <p class="tw-mb-0">{{ session('error') }}</p>
                <button type="button" class="tw-ml-auto tw-text-red-400 hover:tw-text-red-600 tw-border-0 tw-bg-transparent tw-cursor-pointer" data-bs-dismiss="alert"><i class="fas fa-times tw-text-xs"></i></button>
            </div>
            @endif

            {{-- ── Workflow Info ─────────────────────────────────────────── --}}
            <div class="tw-bg-sky-50 tw-border tw-border-sky-200 tw-rounded-xl tw-px-5 tw-py-4 tw-mb-6" id="workflowAlert">
                <div class="tw-flex tw-items-start tw-justify-between tw-gap-3">
                    <div class="tw-flex tw-items-start tw-gap-3">
                        <i class="fas fa-info-circle tw-text-sky-500 tw-mt-0.5 tw-shrink-0"></i>
                        <div>
                            <p class="tw-text-sm tw-font-semibold tw-text-sky-800 tw-mb-2">Workflow des Bons de Commande</p>
                            <ol class="tw-text-sm tw-text-sky-700 tw-space-y-1 tw-pl-4 tw-mb-0">
                                <li><strong>Création :</strong> Logistique crée le bon de commande <span class="tw-font-mono tw-bg-sky-100 tw-px-1 tw-rounded tw-text-xs">BROUILLON</span></li>
                                <li><strong>Validation :</strong> Gestionnaire valide le bon <span class="tw-font-mono tw-bg-sky-100 tw-px-1 tw-rounded tw-text-xs">VALIDÉ</span></li>
                                <li><strong>Impression/Envoi :</strong> Après validation, le PDF peut être généré et envoyé au fournisseur</li>
                                <li><strong>Réception :</strong> Le stock est réceptionné <span class="tw-font-mono tw-bg-sky-100 tw-px-1 tw-rounded tw-text-xs">RÉCEPTIONNÉ</span></li>
                            </ol>
                        </div>
                    </div>
                    <button onclick="document.getElementById('workflowAlert').remove()"
                            class="tw-text-sky-400 hover:tw-text-sky-600 tw-border-0 tw-bg-transparent tw-cursor-pointer tw-shrink-0">
                        <i class="fas fa-times tw-text-xs"></i>
                    </button>
                </div>
            </div>

            {{-- ── KPI Cards ────────────────────────────────────────────── --}}
            <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-mb-6">

                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-file tw-text-slate-500 tw-text-base"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-slate-500 tw-font-medium tw-mb-0.5">Brouillons</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ $bonCommandes->where('statut', 'brouillon')->count() }}</p>
                    </div>
                </div>

                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-amber-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-clock tw-text-amber-500 tw-text-base"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-amber-600 tw-font-medium tw-mb-0.5">En attente validation</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ $bonCommandes->where('statut', 'envoye')->count() }}</p>
                    </div>
                </div>

                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-sky-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-sky-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-check tw-text-sky-500 tw-text-base"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-sky-600 tw-font-medium tw-mb-0.5">Validés</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ $bonCommandes->where('statut', 'valide')->count() }}</p>
                    </div>
                </div>

                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-emerald-100 tw-flex tw-items-center tw-gap-4">
                    <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-check-double tw-text-emerald-500 tw-text-base"></i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-text-emerald-600 tw-font-medium tw-mb-0.5">Réceptionnés</p>
                        <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none">{{ $bonCommandes->where('statut', 'receptionne')->count() }}</p>
                    </div>
                </div>

            </div>

            {{-- ── Table Card ───────────────────────────────────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">

                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-2.5">
                        <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-primary-700"></div>
                        <span class="tw-text-sm tw-font-semibold tw-text-slate-700">Tous les bons de commande</span>
                    </div>
                    <span class="tw-text-xs tw-text-slate-400">{{ $bonCommandes->total() }} résultat(s)</span>
                </div>

                <div class="tw-overflow-x-auto">
                    <table id="myTable" class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-200">
                                <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">N° Bon</th>
                                <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Date</th>
                                <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Fournisseur</th>
                                <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Montant</th>
                                <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Statut</th>
                                <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Validation</th>
                                <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Créé par</th>
                                <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @forelse($bonCommandes as $bon)
                            <tr class="hover:tw-bg-slate-50/70 tw-transition-colors tw-duration-100">

                                {{-- N° Bon --}}
                                <td class="tw-px-5 tw-py-3.5 tw-whitespace-nowrap">
                                    <span class="tw-font-mono tw-font-semibold tw-text-primary-700 tw-text-xs tw-bg-primary-50 tw-px-2 tw-py-1 tw-rounded-lg">{{ $bon->numero_bon }}</span>
                                </td>

                                {{-- Date --}}
                                <td class="tw-px-5 tw-py-3.5 tw-text-sm tw-text-slate-600 tw-whitespace-nowrap">
                                    {{ $bon->date_commande->format('d/m/Y') }}
                                </td>

                                {{-- Fournisseur --}}
                                <td class="tw-px-5 tw-py-3.5 tw-text-sm tw-font-medium tw-text-slate-800">
                                    {{ $bon->fournisseur_nom }}
                                </td>

                                {{-- Montant --}}
                                <td class="tw-px-5 tw-py-3.5 tw-text-right tw-whitespace-nowrap">
                                    <span class="tw-font-semibold tw-text-slate-800">{{ number_format($bon->montant_total, 0, ',', ' ') }}&nbsp;FCFA</span>
                                </td>

                                {{-- Statut --}}
                                <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                    @if($bon->statut === 'brouillon')
                                        <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-slate-600 tw-bg-slate-100 tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                            <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-slate-400 tw-inline-block"></span>Brouillon
                                        </span>
                                    @elseif($bon->statut === 'envoye')
                                        <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-amber-700 tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                            <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-amber-400 tw-inline-block tw-animate-pulse"></span>En attente
                                        </span>
                                    @elseif($bon->statut === 'valide')
                                        <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-sky-700 tw-bg-sky-50 tw-border tw-border-sky-200 tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                            <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-sky-500 tw-inline-block"></span>Validé
                                        </span>
                                    @elseif($bon->statut === 'receptionne')
                                        <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-emerald-700 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                            <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-emerald-500 tw-inline-block"></span>Réceptionné
                                        </span>
                                    @else
                                        <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-red-600 tw-bg-red-50 tw-border tw-border-red-200 tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                            <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-red-500 tw-inline-block"></span>Annulé
                                        </span>
                                    @endif
                                </td>

                                {{-- Validation --}}
                                <td class="tw-px-5 tw-py-3.5">
                                    @if($bon->validated_at)
                                        <p class="tw-text-xs tw-font-semibold tw-text-emerald-700 tw-mb-0">
                                            <i class="fas fa-check-circle tw-mr-1"></i>{{ $bon->validatedBy->name ?? 'N/A' }}
                                        </p>
                                        <p class="tw-text-xs tw-text-slate-400 tw-mb-0">{{ $bon->validated_at->format('d/m/Y') }}</p>
                                    @else
                                        <span class="tw-text-xs tw-text-slate-400 tw-italic">
                                            <i class="fas fa-hourglass-half tw-mr-1"></i>Non validé
                                        </span>
                                    @endif
                                </td>

                                {{-- Créé par --}}
                                <td class="tw-px-5 tw-py-3.5 tw-text-xs tw-text-slate-600">
                                    {{ $bon->createdBy->name ?? 'N/A' }}
                                </td>

                                {{-- Actions --}}
                                <td class="tw-px-5 tw-py-3.5">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1 tw-flex-wrap">

                                        {{-- View --}}
                                        <a href="{{ route('bon-commandes.show', $bon->id) }}"
                                           class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-primary-200 tw-bg-primary-50 hover:tw-bg-primary-100 tw-text-primary-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                           title="Voir détails">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>

                                        {{-- PDF --}}
                                        @if($bon->isValide() || $bon->isEnvoye() || $bon->isReceptionne() || in_array(auth()->user()->role_id, [1, 3]))
                                        <a href="{{ route('bon-commandes.pdf', $bon->id) }}"
                                           class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-red-200 tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                           title="Télécharger PDF">
                                            <i class="fas fa-file-pdf tw-text-xs"></i>
                                        </a>
                                        @else
                                        <button class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-text-slate-300 tw-flex tw-items-center tw-justify-center tw-cursor-not-allowed" disabled title="Validation requise">
                                            <i class="fas fa-lock tw-text-xs"></i>
                                        </button>
                                        @endif

                                        {{-- Edit --}}
                                        @if($bon->canBeEdited() && in_array(auth()->user()->role_id, [1, 5]))
                                        <a href="{{ route('bon-commandes.edit', $bon->id) }}"
                                           class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-amber-200 tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                           title="Modifier">
                                            <i class="fas fa-edit tw-text-xs"></i>
                                        </a>
                                        @endif

                                        {{-- Send for validation --}}
                                        @if($bon->isBrouillon() && in_array(auth()->user()->role_id, [1, 5]))
                                        <form action="{{ route('bon-commandes.send-for-validation', $bon->id) }}" method="POST" class="tw-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-primary-200 tw-bg-primary-50 hover:tw-bg-primary-100 tw-text-primary-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                    onclick="return confirm('Envoyer ce bon pour validation ?')"
                                                    title="Envoyer pour validation">
                                                <i class="fas fa-share tw-text-xs"></i>
                                            </button>
                                        </form>
                                        @endif

                                        {{-- Email --}}
                                        @if(($bon->isValide() || $bon->isEnvoye()) && in_array(auth()->user()->role_id, [1, 3, 5]))
                                        <form action="{{ route('bon-commandes.send', $bon->id) }}" method="POST" class="tw-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-emerald-200 tw-bg-emerald-50 hover:tw-bg-emerald-100 tw-text-emerald-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 {{ !$bon->fournisseur_email ? 'tw-opacity-40 tw-cursor-not-allowed' : '' }}"
                                                    onclick="return confirm('Envoyer ce bon de commande par email au fournisseur ?')"
                                                    title="Envoyer par email"
                                                    {{ !$bon->fournisseur_email ? 'disabled' : '' }}>
                                                <i class="fas fa-envelope tw-text-xs"></i>
                                            </button>
                                        </form>
                                        @endif

                                        {{-- Delete --}}
                                        @if($bon->canBeEdited() && in_array(auth()->user()->role_id, [1, 5]))
                                        <form action="{{ route('bon-commandes.destroy', $bon->id) }}" method="POST" class="tw-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-red-200 tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                    onclick="return confirm('Supprimer ce bon de commande ?')"
                                                    title="Supprimer">
                                                <i class="fas fa-trash-alt tw-text-xs"></i>
                                            </button>
                                        </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="tw-text-center tw-py-16">
                                    <div class="tw-w-16 tw-h-16 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-4">
                                        <i class="fas fa-inbox tw-text-slate-300 tw-text-3xl"></i>
                                    </div>
                                    <p class="tw-text-slate-500 tw-font-medium tw-mb-1">Aucun bon de commande trouvé</p>
                                    <p class="tw-text-sm tw-text-slate-400">Créez votre premier bon de commande</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($bonCommandes->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-bg-slate-50/50">
                    {{ $bonCommandes->links() }}
                </div>
                @endif

            </div>
            </div>
        </main>
    </div>
</div>
@push('scripts')
{{-- ... existing scripts ... --}}
<script>
    $(document).ready(function() {
        $('#bonCommandesTable').DataTable({
            language: { url: "{{ asset('vendor/i18n/fr_fr.json') }}" },
            pageLength: 15,
            responsive: true,
            order: [[0, 'desc']]
        });
    });
</script>
@endpush
@endsection