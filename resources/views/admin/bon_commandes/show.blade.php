@extends('layouts.admin')
@section('title', 'CMCU | Détails Bon de Commande')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading + status badge --}}
            <div class="tw-mb-6 tw-flex tw-items-start tw-justify-between tw-flex-wrap tw-gap-4">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Bon de Commande</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1 tw-font-mono">{{ $bonCommande->numero_bon }}</p>
                </div>
                @php
                    $statusMap = [
                        'brouillon'   => ['tw-bg-slate-100 tw-text-slate-600',         'fa-file',          'Brouillon'],
                        'envoye'      => ['tw-bg-amber-100 tw-text-amber-700',          'fa-hourglass-half','En attente de validation'],
                        'valide'      => ['tw-bg-[#BFDBFE] tw-text-[#1D4ED8]',         'fa-check',         'Validé — En attente réception'],
                        'receptionne' => ['tw-bg-teal-100 tw-text-teal-700',            'fa-check-double',  'Réceptionné'],
                        'annule'      => ['tw-bg-red-100 tw-text-red-600',              'fa-times',         'Annulé'],
                    ];
                    [$badgeCls, $icon, $label] = $statusMap[$bonCommande->statut] ?? ['tw-bg-slate-100 tw-text-slate-600', 'fa-question', strtoupper($bonCommande->statut)];
                @endphp
                <span class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full {{ $badgeCls }} tw-px-4 tw-py-1.5 tw-text-sm tw-font-semibold">
                    <i class="fas {{ $icon }} tw-text-xs"></i> {{ $label }}
                </span>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-6">
                <a href="{{ route('bon-commandes.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
                @if(in_array(auth()->user()->role_id, [1, 3, 5]))
                <a href="{{ route('bon-commandes.pdf', $bonCommande->id) }}" target="_blank"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-file-pdf tw-text-xs"></i> PDF
                </a>
                @endif
                @if($bonCommande->canBeEdited() && in_array(auth()->user()->role_id, [1, 5]))
                <a href="{{ route('bon-commandes.edit', $bonCommande->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-edit tw-text-xs"></i> Modifier
                </a>
                @endif
                @if($bonCommande->isBrouillon() && in_array(auth()->user()->role_id, [1, 5]))
                <form action="{{ route('bon-commandes.send-for-validation', $bonCommande->id) }}" method="POST" class="tw-inline"
                      onsubmit="return confirm('Soumettre ce bon de commande au gestionnaire ?')">
                    @csrf
                    <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE] hover:tw-bg-[#1D4ED8] hover:tw-text-white tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-border-0">
                        <i class="fas fa-paper-plane tw-text-xs"></i> Soumettre pour validation
                    </button>
                </form>
                @endif
                @if($bonCommande->statut === 'envoye' && in_array(auth()->user()->role_id, [1, 3]))
                <button type="button" data-bs-toggle="modal" data-bs-target="#validateModal"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-500 hover:tw-bg-teal-600 tw-text-white tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-border-0">
                    <i class="fas fa-check-circle tw-text-xs"></i> Valider
                </button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#rejectModal"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-border-0">
                    <i class="fas fa-times-circle tw-text-xs"></i> Rejeter
                </button>
                @endif
                @if(in_array($bonCommande->statut, ['valide', 'receptionne']) && in_array(auth()->user()->role_id, [1, 3, 5]) && $bonCommande->fournisseur_email)
                <form action="{{ route('bon-commandes.send', $bonCommande->id) }}" method="POST" class="tw-inline"
                      onsubmit="return confirm('Envoyer par email à {{ $bonCommande->fournisseur_email }} ?')">
                    @csrf
                    <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-border-0">
                        <i class="fas fa-envelope tw-text-xs"></i> Envoyer par email
                    </button>
                </form>
                @endif
            </div>

            {{-- Info Cards Grid --}}
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-5 tw-mb-6">

                {{-- Supplier Card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                    <div class="tw-px-5 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-truck tw-text-[#1D4ED8] tw-text-xs"></i>
                        </div>
                        <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations Fournisseur</h3>
                    </div>
                    <div class="tw-p-5 tw-space-y-3">
                        @foreach([
                            ['Nom', $bonCommande->fournisseur_nom],
                            ['Email', $bonCommande->fournisseur_email ?? null],
                            ['Téléphone', $bonCommande->fournisseur_telephone ?? null],
                            ['Adresse', $bonCommande->fournisseur_adresse ?? null],
                        ] as [$label, $value])
                        @if($value)
                        <div class="tw-flex tw-gap-3">
                            <span class="tw-text-xs tw-text-slate-400 tw-w-20 tw-shrink-0 tw-pt-0.5">{{ $label }}</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-font-medium">{{ $value }}</span>
                        </div>
                        @if($label === 'Email' && !in_array($bonCommande->statut, ['valide', 'receptionne']))
                        <p class="tw-text-xs tw-text-amber-600 tw-flex tw-items-center tw-gap-1.5 tw-ml-23">
                            <i class="fas fa-exclamation-triangle"></i> L'email ne peut être envoyé qu'après validation
                        </p>
                        @endif
                        @endif
                        @endforeach
                    </div>
                </div>

                {{-- Order Info Card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                    <div class="tw-px-5 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-calendar tw-text-[#1D4ED8] tw-text-xs"></i>
                        </div>
                        <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations Commande</h3>
                    </div>
                    <div class="tw-p-5 tw-space-y-3">
                        @foreach([
                            ['N° Bon', $bonCommande->numero_bon],
                            ['Date', $bonCommande->date_commande->format('d/m/Y')],
                            ['Livraison souhaitée', $bonCommande->date_livraison_souhaitee ? $bonCommande->date_livraison_souhaitee->format('d/m/Y') : null],
                            ['Créé par', $bonCommande->createdBy->name ?? 'N/A'],
                            ['Date création', $bonCommande->created_at->format('d/m/Y H:i')],
                        ] as [$label, $value])
                        @if($value)
                        <div class="tw-flex tw-gap-3">
                            <span class="tw-text-xs tw-text-slate-400 tw-w-28 tw-shrink-0 tw-pt-0.5">{{ $label }}</span>
                            <span class="tw-text-sm tw-text-slate-700 tw-font-medium {{ $label === 'N° Bon' ? 'tw-font-mono' : '' }}">{{ $value }}</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                {{-- Validation Card --}}
                @if($bonCommande->validated_at)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-teal-100">
                    <div class="tw-px-5 tw-py-4 tw-border-b tw-border-teal-100 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-check tw-text-[#14B8A6] tw-text-xs"></i>
                        </div>
                        <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Validation</h3>
                    </div>
                    <div class="tw-p-5 tw-space-y-3">
                        <div class="tw-flex tw-gap-3"><span class="tw-text-xs tw-text-slate-400 tw-w-28 tw-shrink-0 tw-pt-0.5">Validé par</span><span class="tw-text-sm tw-font-medium tw-text-slate-700">{{ $bonCommande->validatedBy->name ?? 'N/A' }}</span></div>
                        <div class="tw-flex tw-gap-3"><span class="tw-text-xs tw-text-slate-400 tw-w-28 tw-shrink-0 tw-pt-0.5">Date</span><span class="tw-text-sm tw-font-medium tw-text-slate-700">{{ $bonCommande->validated_at->format('d/m/Y H:i') }}</span></div>
                        @if($bonCommande->validation_comment)<div class="tw-flex tw-gap-3"><span class="tw-text-xs tw-text-slate-400 tw-w-28 tw-shrink-0 tw-pt-0.5">Commentaire</span><span class="tw-text-sm tw-text-slate-600">{{ $bonCommande->validation_comment }}</span></div>@endif
                    </div>
                </div>
                @endif

                {{-- Reception Card --}}
                @if($bonCommande->received_at)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-teal-100">
                    <div class="tw-px-5 tw-py-4 tw-border-b tw-border-teal-100 tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-box tw-text-[#14B8A6] tw-text-xs"></i>
                        </div>
                        <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Réception</h3>
                    </div>
                    <div class="tw-p-5 tw-space-y-3">
                        <div class="tw-flex tw-gap-3"><span class="tw-text-xs tw-text-slate-400 tw-w-28 tw-shrink-0 tw-pt-0.5">Réceptionné par</span><span class="tw-text-sm tw-font-medium tw-text-slate-700">{{ $bonCommande->receivedBy->name ?? 'N/A' }}</span></div>
                        <div class="tw-flex tw-gap-3"><span class="tw-text-xs tw-text-slate-400 tw-w-28 tw-shrink-0 tw-pt-0.5">Date</span><span class="tw-text-sm tw-font-medium tw-text-slate-700">{{ $bonCommande->received_at->format('d/m/Y H:i') }}</span></div>
                        @if($bonCommande->reception_comment)<div class="tw-flex tw-gap-3"><span class="tw-text-xs tw-text-slate-400 tw-w-28 tw-shrink-0 tw-pt-0.5">Commentaire</span><span class="tw-text-sm tw-text-slate-600">{{ $bonCommande->reception_comment }}</span></div>@endif
                    </div>
                </div>
                @endif

                {{-- Notes Card --}}
                @if($bonCommande->notes)
                <div class="tw-bg-amber-50 tw-rounded-2xl tw-border tw-border-amber-200 tw-p-5">
                    <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2">
                        <i class="fas fa-sticky-note tw-text-amber-500"></i>
                        <h3 class="tw-text-sm tw-font-semibold tw-text-amber-800 tw-mb-0">Notes</h3>
                    </div>
                    <p class="tw-text-sm tw-text-amber-700 tw-mb-0">{{ $bonCommande->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Products Table --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-boxes tw-text-[#1D4ED8]"></i>
                        <h3 class="tw-text-base tw-font-semibold tw-text-slate-700 tw-mb-0">Produits Commandés</h3>
                    </div>
                    <span class="tw-text-xl tw-font-bold tw-text-[#1D4ED8]">
                        {{ number_format($bonCommande->montant_total, 0, ',', ' ') }} <span class="tw-text-sm tw-font-normal tw-text-slate-400">FCFA</span>
                    </span>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-border-b tw-border-slate-100 tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">#</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Désignation</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Catégorie</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Qté Cmd.</th>
                                @if($bonCommande->isReceptionne())
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Qté Reçue</th>
                                @endif
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Prix Unit.</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($bonCommande->items as $i => $item)
                            <tr class="hover:tw-bg-slate-50">
                                <td class="tw-px-4 tw-py-3 tw-text-slate-400">{{ $i + 1 }}</td>
                                <td class="tw-px-4 tw-py-3 tw-font-semibold tw-text-slate-700">{{ $item->designation }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    <span class="tw-inline-flex tw-rounded-full tw-bg-slate-100 tw-text-slate-600 tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium">{{ $item->categorie }}</span>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center tw-font-semibold tw-text-slate-700">{{ $item->quantite_commandee }}</td>
                                @if($bonCommande->isReceptionne())
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <span class="tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-bold {{ $item->isFullyReceived() ? 'tw-bg-teal-50 tw-text-teal-700' : 'tw-bg-amber-100 tw-text-amber-700' }}">
                                        {{ $item->quantite_recue }}
                                    </span>
                                </td>
                                @endif
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-text-slate-600">{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-font-semibold tw-text-slate-700">{{ number_format($item->montant_ligne, 0, ',', ' ') }} <span class="tw-text-xs tw-text-slate-400">FCFA</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="tw-bg-[#BFDBFE]/20 tw-border-t-2 tw-border-[#BFDBFE]">
                                <td colspan="{{ $bonCommande->isReceptionne() ? 6 : 5 }}" class="tw-px-4 tw-py-3 tw-text-right tw-font-bold tw-text-slate-700 tw-text-sm tw-uppercase tw-tracking-wide">Montant Total</td>
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-font-bold tw-text-[#1D4ED8] tw-text-base">{{ number_format($bonCommande->montant_total, 0, ',', ' ') }} <span class="tw-text-xs tw-font-normal tw-text-slate-400">FCFA</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- Validate Modal --}}
<div class="modal fade" id="validateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-px-6 tw-py-4 tw-bg-[#14B8A6] tw-flex tw-items-center tw-justify-between">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-check-circle"></i> Valider le Bon de Commande
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('bon-commandes.validate', $bonCommande->id) }}" method="POST">
                @csrf
                <div class="tw-p-6 tw-space-y-4">
                    <p class="tw-text-slate-600 tw-text-sm">Confirmer la validation de <strong class="tw-text-slate-800">{{ $bonCommande->numero_bon }}</strong> ?</p>
                    <p class="tw-text-xs tw-text-slate-400">Montant : <strong class="tw-text-[#1D4ED8]">{{ number_format($bonCommande->montant_total, 0, ',', ' ') }} FCFA</strong></p>
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Commentaire (optionnel)</label>
                        <textarea name="validation_comment" rows="3" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"></textarea>
                    </div>
                </div>
                <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                    <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-transition-colors">
                        <i class="fas fa-check tw-mr-1.5"></i> Confirmer
                    </button>
                    <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0 tw-transition-colors">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-px-6 tw-py-4 tw-bg-red-500 tw-flex tw-items-center tw-justify-between">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-times-circle"></i> Rejeter le Bon de Commande
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('bon-commandes.reject', $bonCommande->id) }}" method="POST">
                @csrf
                <div class="tw-p-6 tw-space-y-4">
                    <p class="tw-text-slate-600 tw-text-sm">Rejeter <strong class="tw-text-slate-800">{{ $bonCommande->numero_bon }}</strong> ?</p>
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Raison du rejet <span class="tw-text-red-500">*</span></label>
                        <textarea name="rejection_reason" rows="3" required class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-red-200 focus:tw-border-red-400"></textarea>
                    </div>
                </div>
                <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                    <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-transition-colors">
                        <i class="fas fa-times tw-mr-1.5"></i> Confirmer le rejet
                    </button>
                    <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0 tw-transition-colors">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection