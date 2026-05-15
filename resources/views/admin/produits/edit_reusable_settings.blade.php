@extends('layouts.admin')
@section('title', 'CMCU | Paramètres Produit Réutilisable')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-lg tw-mx-auto">

                {{-- ── Page Header ──────────────────────────────────────────── --}}
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-8">
                    <div>
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                            <span class="tw-text-xs tw-font-semibold tw-tracking-widest tw-text-emerald-700 tw-uppercase tw-bg-emerald-100 tw-px-2.5 tw-py-1 tw-rounded-full">Stock</span>
                        </div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-tracking-tight">Paramètres de Réutilisation</h1>
                        <p class="tw-text-sm tw-font-medium tw-text-slate-500 tw-mt-0.5">{{ $produit->designation }}</p>
                    </div>
                    <a href="{{ route('produits.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2.5 tw-rounded-xl tw-shadow-card tw-border tw-border-slate-200 tw-transition-all tw-duration-150 tw-no-underline tw-whitespace-nowrap">
                        <i class="fas fa-arrow-left tw-text-xs"></i>Retour aux produits
                    </a>
                </div>

                {{-- ── Product Info Card ────────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                    <div class="tw-px-6 tw-py-4 tw-bg-sky-700 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-8 tw-h-8 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-info-circle tw-text-white tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">Informations Produit</h2>
                    </div>
                    <div class="tw-p-6">
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-x-8 tw-gap-y-3 tw-mb-4">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <span class="tw-text-xs tw-font-semibold tw-text-slate-400 tw-w-28 tw-shrink-0">Désignation</span>
                                <span class="tw-text-sm tw-font-semibold tw-text-slate-800">{{ $produit->designation }}</span>
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <span class="tw-text-xs tw-font-semibold tw-text-slate-400 tw-w-28 tw-shrink-0">Catégorie</span>
                                <span class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-bg-slate-100 tw-px-2.5 tw-py-1 tw-rounded-full">{{ $produit->categorie }}</span>
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <span class="tw-text-xs tw-font-semibold tw-text-slate-400 tw-w-28 tw-shrink-0">Stock Total</span>
                                <span class="tw-text-sm tw-font-bold tw-text-slate-800">{{ $produit->qte_stock }}</span>
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <span class="tw-text-xs tw-font-semibold tw-text-slate-400 tw-w-28 tw-shrink-0">Prix Unitaire</span>
                                <span class="tw-text-sm tw-font-bold tw-text-slate-800">{{ number_format($produit->prix_unitaire, 0, ',', ' ') }}&nbsp;FCFA</span>
                            </div>
                        </div>
                        <div class="tw-flex tw-items-center tw-gap-3 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-rounded-xl tw-px-4 tw-py-3">
                            <i class="fas fa-recycle tw-text-emerald-600 tw-shrink-0"></i>
                            <span class="tw-text-sm tw-font-semibold tw-text-emerald-700">Ce produit est marqué comme réutilisable</span>
                        </div>
                    </div>
                </div>

                {{-- ── Settings Form Card ───────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-bg-emerald-700 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-8 tw-h-8 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-sliders-h tw-text-white tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">Paramètres de Stérilisation</h2>
                    </div>

                    <form action="{{ route('produits.update-reusable-settings', $produit->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="tw-p-6 tw-space-y-8">

                            {{-- ── Section: Utilisation ──────────────────────── --}}
                            <div>
                                <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-5 tw-pb-3 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-primary-100 tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-sync-alt tw-text-primary-700 tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-bold tw-text-primary-700 tw-uppercase tw-tracking-wide tw-mb-0">Paramètres d'Utilisation</h3>
                                </div>

                                <div class="tw-space-y-4">
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5" for="nombre_utilisations_max">
                                            Nombre Maximum d'Utilisations
                                            <i class="fas fa-question-circle tw-text-slate-400 tw-ml-1" title="Nombre de fois que le produit peut être réutilisé avant remplacement"></i>
                                        </label>
                                        <input type="number"
                                               class="form-control @error('nombre_utilisations_max') is-invalid @enderror"
                                               name="nombre_utilisations_max"
                                               id="nombre_utilisations_max"
                                               value="{{ old('nombre_utilisations_max', $produit->nombre_utilisations_max) }}"
                                               min="1"
                                               placeholder="Ex: 50"
                                               style="max-width:220px">
                                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1.5">Laisser vide si pas de limite définie</p>
                                        @error('nombre_utilisations_max')
                                            <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5" for="notes_utilisation">Notes d'Utilisation</label>
                                        <textarea class="form-control @error('notes_utilisation') is-invalid @enderror"
                                                  name="notes_utilisation"
                                                  id="notes_utilisation"
                                                  rows="3"
                                                  placeholder="Instructions spéciales, précautions, conditions d'utilisation...">{{ old('notes_utilisation', $produit->notes_utilisation) }}</textarea>
                                        @error('notes_utilisation')
                                            <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ── Section: Stérilisation ────────────────────── --}}
                            <div>
                                <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-5 tw-pb-3 tw-border-b tw-border-slate-100">
                                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-primary-100 tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-fire tw-text-primary-700 tw-text-xs"></i>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-bold tw-text-primary-700 tw-uppercase tw-tracking-wide tw-mb-0">Paramètres de Stérilisation Recommandés</h3>
                                </div>

                                <div class="tw-flex tw-items-start tw-gap-3 tw-bg-sky-50 tw-border tw-border-sky-200 tw-rounded-xl tw-px-4 tw-py-3 tw-mb-5 tw-text-sm">
                                    <i class="fas fa-lightbulb tw-text-sky-500 tw-mt-0.5 tw-shrink-0"></i>
                                    <p class="tw-text-sky-700 tw-mb-0">
                                        <strong>Important :</strong> Ces paramètres seront utilisés par défaut lors de la création d'un nouveau lot de stérilisation.
                                    </p>
                                </div>

                                <div class="tw-space-y-4">
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5" for="methode_sterilisation_recommandee">
                                            Méthode de Stérilisation <span class="tw-text-red-500">*</span>
                                        </label>
                                        <select class="form-select @error('methode_sterilisation_recommandee') is-invalid @enderror"
                                                name="methode_sterilisation_recommandee"
                                                id="methode_sterilisation_recommandee"
                                                required>
                                            <option value="">Sélectionner une méthode...</option>
                                            @php
                                                $methodes = [
                                                    'autoclave'     => 'Autoclave (Vapeur sous pression)',
                                                    'chaleur_seche' => 'Chaleur Sèche (Poupinel)',
                                                    'gaz_eto'       => "Gaz ETO (Oxyde d'éthylène)",
                                                    'plasma'        => "Plasma (Peroxyde d'hydrogène)",
                                                    'chimique'      => 'Chimique (Immersion)',
                                                    'autre'         => 'Autre',
                                                ];
                                                $current = old('methode_sterilisation_recommandee', $produit->methode_sterilisation_recommandee);
                                            @endphp
                                            @foreach($methodes as $val => $label)
                                            <option value="{{ $val }}" {{ $current == $val ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('methode_sterilisation_recommandee')
                                            <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5" for="temperature_sterilisation">
                                                Température Recommandée (°C)
                                            </label>
                                            <input type="number"
                                                   class="form-control @error('temperature_sterilisation') is-invalid @enderror"
                                                   name="temperature_sterilisation"
                                                   id="temperature_sterilisation"
                                                   value="{{ old('temperature_sterilisation', $produit->temperature_sterilisation) }}"
                                                   min="50" max="200"
                                                   placeholder="Ex: 121">
                                            <p id="temp-hint" class="tw-text-xs tw-text-slate-400 tw-mt-1.5">Autoclave: 121-134°C | Chaleur sèche: 160-170°C</p>
                                            @error('temperature_sterilisation')
                                                <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5" for="duree_sterilisation_recommandee">
                                                Durée Recommandée (minutes)
                                            </label>
                                            <input type="number"
                                                   class="form-control @error('duree_sterilisation_recommandee') is-invalid @enderror"
                                                   name="duree_sterilisation_recommandee"
                                                   id="duree_sterilisation_recommandee"
                                                   value="{{ old('duree_sterilisation_recommandee', $produit->duree_sterilisation_recommandee) }}"
                                                   min="1"
                                                   placeholder="Ex: 20">
                                            <p id="duration-hint" class="tw-text-xs tw-text-slate-400 tw-mt-1.5">Autoclave: 15-30 min | Chaleur sèche: 60-120 min</p>
                                            @error('duree_sterilisation_recommandee')
                                                <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Presets ───────────────────────────────────── --}}
                            <div>
                                <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-3">Presets Rapides</p>
                                <div class="tw-flex tw-flex-wrap tw-gap-2">
                                    <button type="button"
                                            class="preset-btn tw-inline-flex tw-items-center tw-gap-2 tw-text-xs tw-font-semibold tw-text-primary-700 tw-bg-primary-50 hover:tw-bg-primary-100 tw-border tw-border-primary-200 tw-px-3.5 tw-py-2 tw-rounded-lg tw-transition-colors tw-duration-150 tw-cursor-pointer"
                                            data-method="autoclave" data-temp="121" data-duration="20">
                                        <i class="fas fa-temperature-high tw-text-xs"></i>Autoclave Standard
                                    </button>
                                    <button type="button"
                                            class="preset-btn tw-inline-flex tw-items-center tw-gap-2 tw-text-xs tw-font-semibold tw-text-primary-700 tw-bg-primary-50 hover:tw-bg-primary-100 tw-border tw-border-primary-200 tw-px-3.5 tw-py-2 tw-rounded-lg tw-transition-colors tw-duration-150 tw-cursor-pointer"
                                            data-method="autoclave" data-temp="134" data-duration="15">
                                        <i class="fas fa-bolt tw-text-xs"></i>Autoclave Rapide
                                    </button>
                                    <button type="button"
                                            class="preset-btn tw-inline-flex tw-items-center tw-gap-2 tw-text-xs tw-font-semibold tw-text-slate-600 tw-bg-slate-100 hover:tw-bg-slate-200 tw-border tw-border-slate-200 tw-px-3.5 tw-py-2 tw-rounded-lg tw-transition-colors tw-duration-150 tw-cursor-pointer"
                                            data-method="chaleur_seche" data-temp="160" data-duration="90">
                                        <i class="fas fa-fire tw-text-xs"></i>Chaleur Sèche
                                    </button>
                                </div>
                            </div>

                        </div>

                        {{-- ── Form Footer ───────────────────────────────────── --}}
                        <div class="tw-px-6 tw-py-4 tw-bg-slate-50 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-justify-between tw-gap-3">
                            <a href="{{ route('produits.index') }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-5 tw-py-2.5 tw-rounded-xl tw-border tw-border-slate-200 tw-transition-all tw-duration-150 tw-no-underline">
                                <i class="fas fa-times tw-text-xs"></i>Annuler
                            </a>
                            <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-emerald-600 hover:tw-bg-emerald-700 tw-text-white tw-text-sm tw-font-semibold tw-px-6 tw-py-2.5 tw-rounded-xl tw-transition-all tw-duration-150 tw-border-0 tw-cursor-pointer"
                                    style="box-shadow:0 4px 14px rgba(5,150,105,.3)">
                                <i class="fas fa-save tw-text-xs"></i>Enregistrer les Paramètres
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.preset-btn').on('click', function() {
        const method   = $(this).data('method');
        const temp     = $(this).data('temp');
        const duration = $(this).data('duration');
        $('#methode_sterilisation_recommandee').val(method).trigger('change');
        $('#temperature_sterilisation').val(temp);
        $('#duree_sterilisation_recommandee').val(duration);
        $('.preset-btn').removeClass('tw-ring-2 tw-ring-primary-400');
        $(this).addClass('tw-ring-2 tw-ring-primary-400');
    });

    const hints = {
        autoclave:     { temp: 'Autoclave: 121-134°C',     duration: 'Autoclave: 15-30 min' },
        chaleur_seche: { temp: 'Chaleur sèche: 160-170°C', duration: 'Chaleur sèche: 60-120 min' },
        gaz_eto:       { temp: 'ETO: 37-63°C',             duration: 'ETO: 2-12 heures' },
        plasma:        { temp: 'Plasma: 45-50°C',          duration: 'Plasma: 45-75 min' },
    };

    $('#methode_sterilisation_recommandee').on('change', function() {
        const h = hints[$(this).val()];
        if (h) {
            $('#temp-hint').text(h.temp);
            $('#duration-hint').text(h.duration);
        }
    });
});
</script>
@endpush

@endsection