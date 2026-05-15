@extends('layouts.admin')
@section('title', 'CMCU | Détails Stérilisation')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6">
                <div class="tw-flex tw-items-center tw-gap-3 tw-flex-wrap">
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-font-mono">{{ $sterilization->numero_lot }}</h1>
                    <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-medium tw-bg-{{ $sterilization->getStatutBadgeColor() }}-50 tw-text-{{ $sterilization->getStatutBadgeColor() }}-700 tw-border tw-border-{{ $sterilization->getStatutBadgeColor() }}-200">
                        {{ $sterilization->getStatutLabel() }}
                    </span>
                </div>
                <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Détails du lot de stérilisation</p>
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
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-6">
                <a href="{{ route('reusable-products.sterilizations.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
                @if($sterilization->isRetourne())
                <a href="{{ route('reusable-products.sterilizations.certificate', $sterilization->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-rounded-xl tw-transition-colors tw-no-underline">
                    <i class="fas fa-file-pdf tw-text-xs"></i> Certificat PDF
                </a>
                @endif
                @if($sterilization->isEnCours())
                <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-amber-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-amber-600 tw-transition-colors tw-border-0 tw-cursor-pointer" data-bs-toggle="modal" data-bs-target="#completeModal">
                    <i class="fas fa-check tw-text-xs"></i> Marquer comme Terminé
                </button>
                @endif
                @if($sterilization->isTermine() && in_array(auth()->user()->role_id, [1, 7]))
                <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-emerald-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-emerald-600 tw-transition-colors tw-border-0 tw-cursor-pointer" data-bs-toggle="modal" data-bs-target="#validateModal">
                    <i class="fas fa-check-double tw-text-xs"></i> Valider
                </button>
                <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-rounded-xl tw-transition-colors tw-border-0 tw-cursor-pointer" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times tw-text-xs"></i> Rejeter
                </button>
                @endif
                @if($sterilization->isValide() && in_array(auth()->user()->role_id, [1, 7]))
                <form action="{{ route('reusable-products.sterilizations.return-to-stock', $sterilization->id) }}" method="POST" class="tw-inline"
                      onsubmit="return confirm('Confirmer le retour de {{ $sterilization->quantite }} unité(s) au stock ?')">
                    @csrf
                    <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer">
                        <i class="fas fa-undo tw-text-xs"></i> Retourner au Stock
                    </button>
                </form>
                @endif
            </div>

            {{-- Content Grid --}}
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-5">

                {{-- Left Column --}}
                <div class="tw-space-y-5">

                    {{-- Product Info --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-sky-50 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-box tw-text-sky-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Produit</h2>
                        </div>
                        <div class="tw-p-6 tw-space-y-3">
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">Désignation</span><span class="tw-font-semibold tw-text-slate-800">{{ $sterilization->produit->designation }}</span></div>
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">Catégorie</span><span class="tw-inline-flex tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-slate-100 tw-text-slate-600">{{ $sterilization->produit->categorie }}</span></div>
                            <div class="tw-flex tw-justify-between tw-text-sm"><span class="tw-text-slate-500">Quantité stérilisée</span><span class="tw-inline-flex tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">{{ $sterilization->quantite }} unité(s)</span></div>
                        </div>
                    </div>

                    {{-- Parameters --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-sliders-h tw-text-[#1D4ED8] tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Paramètres</h2>
                        </div>
                        <div class="tw-p-6 tw-space-y-3 tw-text-sm">
                            <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">N° Lot</span><span class="tw-font-semibold tw-font-mono tw-text-slate-800">{{ $sterilization->numero_lot }}</span></div>
                            <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Méthode</span><span class="tw-text-slate-700">{{ $sterilization->getMethodeLabel() }}</span></div>
                            <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Date</span><span class="tw-text-slate-700">{{ $sterilization->date_sterilisation->format('d/m/Y') }}</span></div>
                            @if($sterilization->heure_debut)<div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Heure début</span><span class="tw-text-slate-700">{{ $sterilization->heure_debut }}</span></div>@endif
                            @if($sterilization->heure_fin)<div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Heure fin</span><span class="tw-text-slate-700">{{ $sterilization->heure_fin }}</span></div>@endif
                            @if($sterilization->temperature)<div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Température</span><span class="tw-text-slate-700">{{ $sterilization->temperature }}°C</span></div>@endif
                            @if($sterilization->duree_minutes)<div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Durée</span><span class="tw-text-slate-700">{{ $sterilization->duree_minutes }} min</span></div>@endif
                            @if($sterilization->type_indicateur)<div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Indicateur</span><span class="tw-text-slate-700">{{ $sterilization->type_indicateur }}</span></div>@endif
                        </div>
                    </div>

                    {{-- Quality Control --}}
                    @if(!$sterilization->isEnCours())
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-amber-50 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-check-circle tw-text-amber-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Contrôle Qualité</h2>
                        </div>
                        <div class="tw-p-6">
                            <div class="tw-flex tw-justify-between tw-text-sm">
                                <span class="tw-text-slate-500">Résultat Test</span>
                                @if($sterilization->resultat_test === 'conforme')
                                <span class="tw-inline-flex tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-emerald-50 tw-text-emerald-700">Conforme</span>
                                @elseif($sterilization->resultat_test === 'non_conforme')
                                <span class="tw-inline-flex tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-red-50 tw-text-red-600">Non Conforme</span>
                                @else
                                <span class="tw-inline-flex tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-slate-100 tw-text-slate-600">En Attente</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Right Column --}}
                <div class="tw-space-y-5">

                    {{-- Personnel --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-users tw-text-emerald-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Personnel</h2>
                        </div>
                        <div class="tw-p-6 tw-space-y-3 tw-text-sm">
                            <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Stérilisé par</span><span class="tw-text-slate-700">{{ $sterilization->sterilisePar->name }}</span></div>
                            @if($sterilization->verifie_par)<div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Vérifié par</span><span class="tw-text-slate-700">{{ $sterilization->verifiePar->name }}</span></div>@endif
                            @if($sterilization->retourne_par)
                            <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Retourné par</span><span class="tw-text-slate-700">{{ $sterilization->retournePar->name }}</span></div>
                            <div class="tw-flex tw-justify-between"><span class="tw-text-slate-500">Date retour</span><span class="tw-text-slate-700">{{ $sterilization->retourne_at->format('d/m/Y H:i') }}</span></div>
                            @endif
                        </div>
                    </div>

                    {{-- Observations / Rejet --}}
                    @if($sterilization->observations || $sterilization->raison_rejet)
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-sticky-note tw-text-slate-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Remarques</h2>
                        </div>
                        <div class="tw-p-6 tw-space-y-3">
                            @if($sterilization->observations)
                            <div>
                                <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-mb-1">Observations</p>
                                <p class="tw-text-sm tw-text-slate-700 tw-mb-0">{{ $sterilization->observations }}</p>
                            </div>
                            @endif
                            @if($sterilization->raison_rejet)
                            <div class="tw-flex tw-items-start tw-gap-2 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3">
                                <i class="fas fa-exclamation-circle tw-text-red-500 tw-mt-0.5 tw-shrink-0"></i>
                                <div>
                                    <p class="tw-text-xs tw-font-semibold tw-text-red-600 tw-mb-1">Raison du Rejet</p>
                                    <p class="tw-text-sm tw-text-red-600 tw-mb-0">{{ $sterilization->raison_rejet }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Timeline --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                            <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-history tw-text-slate-500 tw-text-sm"></i>
                            </div>
                            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Chronologie</h2>
                        </div>
                        <div class="tw-p-6">
                            <div class="tw-space-y-4">
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-sky-100 tw-flex tw-items-center tw-justify-center tw-shrink-0"><i class="fas fa-plus-circle tw-text-sky-500 tw-text-xs"></i></div>
                                    <div><p class="tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-0">Créée</p><p class="tw-text-xs tw-text-slate-400 tw-mb-0">{{ $sterilization->created_at->format('d/m/Y H:i') }}</p></div>
                                </div>
                                @if(!$sterilization->isEnCours())
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0"><i class="fas fa-check tw-text-amber-500 tw-text-xs"></i></div>
                                    <div><p class="tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-0">Terminée</p><p class="tw-text-xs tw-text-slate-400 tw-mb-0">{{ $sterilization->updated_at->format('d/m/Y H:i') }}</p></div>
                                </div>
                                @endif
                                @if($sterilization->verifie_par)
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-emerald-100 tw-flex tw-items-center tw-justify-center tw-shrink-0"><i class="fas fa-check-double tw-text-emerald-500 tw-text-xs"></i></div>
                                    <div><p class="tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-0">{{ $sterilization->isRejete() ? 'Rejetée' : 'Validée' }}</p><p class="tw-text-xs tw-text-slate-400 tw-mb-0">Par {{ $sterilization->verifiePar->name }}</p></div>
                                </div>
                                @endif
                                @if($sterilization->retourne_at)
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0"><i class="fas fa-undo tw-text-[#1D4ED8] tw-text-xs"></i></div>
                                    <div><p class="tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-0">Retournée au Stock</p><p class="tw-text-xs tw-text-slate-400 tw-mb-0">{{ $sterilization->retourne_at->format('d/m/Y H:i') }}</p></div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- Complete Modal --}}
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-amber-500">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0">Marquer comme Terminé</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('reusable-products.sterilizations.complete', $sterilization->id) }}" method="POST">
                @csrf
                <div class="tw-p-6 tw-space-y-4">
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Heure de Fin</label>
                        <input type="time" name="heure_fin" value="{{ date('H:i') }}" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-amber-200">
                    </div>
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Résultat du Test <span class="tw-text-red-500">*</span></label>
                        <select name="resultat_test" required class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-amber-200">
                            <option value="">Sélectionner...</option>
                            <option value="conforme">Conforme</option>
                            <option value="non_conforme">Non Conforme</option>
                            <option value="en_attente">En Attente</option>
                        </select>
                    </div>
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Observations</label>
                        <textarea name="observations" rows="3" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-amber-200 tw-resize-none"></textarea>
                    </div>
                </div>
                <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                    <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-amber-500 hover:tw-bg-amber-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-cursor-pointer"><i class="fas fa-check tw-mr-1"></i> Confirmer</button>
                    <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Validate Modal --}}
<div class="modal fade" id="validateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#14B8A6]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0"><i class="fas fa-check-double tw-mr-2"></i>Valider la Stérilisation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('reusable-products.sterilizations.validate', $sterilization->id) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="valider">
                <div class="tw-p-6 tw-space-y-4">
                    <p class="tw-text-sm tw-text-slate-600">Confirmer que la stérilisation du lot <strong class="tw-font-mono">{{ $sterilization->numero_lot }}</strong> est conforme ?</p>
                    <div class="tw-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-text-sm tw-text-teal-700">
                        <i class="fas fa-info-circle tw-shrink-0"></i>
                        <span><strong>{{ $sterilization->quantite }}</strong> unité(s) seront prêtes pour retour au stock.</span>
                    </div>
                </div>
                <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                    <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-cursor-pointer">Confirmer</button>
                    <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-red-500">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0"><i class="fas fa-times-circle tw-mr-2"></i>Rejeter la Stérilisation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('reusable-products.sterilizations.validate', $sterilization->id) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="rejeter">
                <div class="tw-p-6 tw-space-y-4">
                    <p class="tw-text-sm tw-text-slate-600">La stérilisation a échoué. Les produits seront marqués comme perdus.</p>
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Raison du Rejet <span class="tw-text-red-500">*</span></label>
                        <textarea name="raison_rejet" rows="3" required class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-red-200 tw-resize-none"></textarea>
                    </div>
                    <div class="tw-flex tw-items-start tw-gap-2 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-text-sm tw-text-red-600">
                        <i class="fas fa-exclamation-triangle tw-shrink-0 tw-mt-0.5"></i>
                        <span><strong>Attention :</strong> Les {{ $sterilization->quantite }} unité(s) ne seront PAS retournées au stock.</span>
                    </div>
                </div>
                <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                    <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-cursor-pointer">Confirmer le Rejet</button>
                    <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('admin/js/main.js') }}"></script>
@endsection