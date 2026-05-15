@extends('layouts.admin')
@section('title', 'CMCU | Lancer Stérilisation')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page Heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Lancer une Stérilisation</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Créer un nouveau lot de stérilisation</p>
                </div>
                <a href="{{ route('reusable-products.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            {{-- Error --}}
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i> {{ session('error') }}
            </div>
            @endif

            <div class="tw-max-w-2xl tw-mx-auto">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                    <div class="tw-flex tw-items-center tw-gap-2 tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-sky-50 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-fire tw-text-sky-500 tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700">Nouveau Lot de Stérilisation</h2>
                    </div>

                    <div class="tw-p-6">
                        <form action="{{ route('reusable-products.sterilizations.store') }}" method="POST" class="tw-space-y-5">
                            @csrf

                            {{-- Product Selection --}}
                            <div>
                                <label for="produit_id" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Produit à Stériliser <span class="tw-text-red-500">*</span>
                                </label>
                                <select name="produit_id" id="produit_id" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] @error('produit_id') tw-border-red-400 @enderror">
                                    <option value="">Sélectionner un produit...</option>
                                    @forelse($products as $produit)
                                        <option value="{{ $produit->id }}"
                                                data-qte="{{ $produit->qte_en_sterilisation }}"
                                                data-methode="{{ $produit->methode_sterilisation_recommandee }}"
                                                data-duree="{{ $produit->duree_sterilisation_recommandee }}"
                                                data-temperature="{{ $produit->temperature_sterilisation }}"
                                                {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                                            {{ $produit->designation }} ({{ $produit->qte_en_sterilisation }} unité(s) en attente)
                                        </option>
                                    @empty
                                        <option value="" disabled>Aucun produit en attente de stérilisation</option>
                                    @endforelse
                                </select>
                                @error('produit_id')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                                <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Seuls les produits avec quantité en stérilisation > 0 sont affichés</p>
                            </div>

                            {{-- Quantity --}}
                            <div>
                                <label for="quantite" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Quantité à Stériliser <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="number" name="quantite" id="quantite"
                                       value="{{ old('quantite', 1) }}" min="1" required
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] @error('quantite') tw-border-red-400 @enderror">
                                @error('quantite')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                                <p class="tw-text-xs tw-text-slate-400 tw-mt-1" id="qte_disponible_text"></p>
                            </div>

                            {{-- Method --}}
                            <div>
                                <label for="methode_sterilisation" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Méthode de Stérilisation <span class="tw-text-red-500">*</span>
                                </label>
                                <select name="methode_sterilisation" id="methode_sterilisation" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] @error('methode_sterilisation') tw-border-red-400 @enderror">
                                    <option value="">Sélectionner...</option>
                                    <option value="autoclave" {{ old('methode_sterilisation') == 'autoclave' ? 'selected' : '' }}>Autoclave (Vapeur sous Pression)</option>
                                    <option value="chaleur_seche" {{ old('methode_sterilisation') == 'chaleur_seche' ? 'selected' : '' }}>Chaleur Sèche (Poupinel)</option>
                                    <option value="gaz_eto" {{ old('methode_sterilisation') == 'gaz_eto' ? 'selected' : '' }}>Gaz ETO (Oxyde d'Éthylène)</option>
                                    <option value="plasma" {{ old('methode_sterilisation') == 'plasma' ? 'selected' : '' }}>Plasma (Peroxyde d'Hydrogène)</option>
                                    <option value="chimique" {{ old('methode_sterilisation') == 'chimique' ? 'selected' : '' }}>Chimique (Immersion)</option>
                                    <option value="autre" {{ old('methode_sterilisation') == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('methode_sterilisation')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Date & Time --}}
                            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                        Date de Stérilisation <span class="tw-text-red-500">*</span>
                                    </label>
                                    <input type="date" name="date_sterilisation"
                                           value="{{ old('date_sterilisation', date('Y-m-d')) }}" required
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] @error('date_sterilisation') tw-border-red-400 @enderror">
                                    @error('date_sterilisation')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Heure de Début</label>
                                    <input type="time" name="heure_debut" value="{{ old('heure_debut', date('H:i')) }}"
                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                </div>
                            </div>

                            {{-- Parameters Card --}}
                            <div class="tw-bg-slate-50 tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-3 tw-border-b tw-border-slate-200">
                                    <i class="fas fa-sliders-h tw-text-slate-400 tw-text-xs"></i>
                                    <span class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide">Paramètres de Stérilisation</span>
                                </div>
                                <div class="tw-p-4 tw-space-y-4">
                                    <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Température (°C)</label>
                                            <input type="number" name="temperature" id="temperature" value="{{ old('temperature') }}" min="0"
                                                   class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                            <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Recommandée: <span id="temp_recommandee" class="tw-font-medium tw-text-[#1D4ED8]">-</span></p>
                                        </div>
                                        <div>
                                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Durée (minutes)</label>
                                            <input type="number" name="duree_minutes" id="duree_minutes" value="{{ old('duree_minutes') }}" min="0"
                                                   class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                            <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Recommandée: <span id="duree_recommandee" class="tw-font-medium tw-text-[#1D4ED8]">-</span></p>
                                        </div>
                                    </div>
                                    {{-- Guide --}}
                                    <div class="tw-bg-[#BFDBFE]/20 tw-rounded-lg tw-border tw-border-[#BFDBFE] tw-px-4 tw-py-3 tw-text-xs tw-text-[#1D4ED8]">
                                        <p class="tw-font-semibold tw-mb-1">Guide Rapide :</p>
                                        <p class="tw-mb-0 tw-text-slate-600 tw-leading-relaxed">
                                            <strong>Autoclave :</strong> 121–134°C, 15–30 min ·
                                            <strong>Chaleur Sèche :</strong> 160–170°C, 60–120 min ·
                                            <strong>Gaz ETO :</strong> 37–63°C, 2–12 h ·
                                            <strong>Plasma :</strong> 45–50°C, 45–75 min
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Indicator --}}
                            <div>
                                <label for="type_indicateur" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Type d'Indicateur de Qualité</label>
                                <input type="text" name="type_indicateur" id="type_indicateur" value="{{ old('type_indicateur') }}"
                                       placeholder="Ex: Indicateur biologique, chimique, intégrateur..."
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Spécifier le type d'indicateur utilisé pour vérifier l'efficacité</p>
                            </div>

                            {{-- Observations --}}
                            <div>
                                <label for="observations" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Observations</label>
                                <textarea name="observations" id="observations" rows="3"
                                          class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none">{{ old('observations') }}</textarea>
                            </div>

                            {{-- Buttons --}}
                            <div class="tw-flex tw-justify-center tw-gap-3 tw-pt-2">
                                <button type="submit"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-sky-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-sky-600 tw-transition-colors tw-border-0 tw-cursor-pointer">
                                    <i class="fas fa-fire"></i> Lancer la Stérilisation
                                </button>
                                <a href="{{ route('reusable-products.index') }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
<script src="{{ asset('admin/js/main.js') }}"></script>

@push('scripts')
<script>
$(document).ready(function() {
    $('#produit_id').change(function() {
        const option = $(this).find('option:selected');
        const qte = option.data('qte');
        const methode = option.data('methode');
        const duree = option.data('duree');
        const temperature = option.data('temperature');

        if (qte) { $('#qte_disponible_text').text(`Maximum disponible: ${qte} unité(s)`); $('#quantite').attr('max', qte); }
        else { $('#qte_disponible_text').text(''); $('#quantite').removeAttr('max'); }

        if (methode && methode !== 'non_applicable') $('#methode_sterilisation').val(methode);

        if (temperature) { $('#temperature').val(temperature); $('#temp_recommandee').text(temperature + '°C'); }
        else $('#temp_recommandee').text('-');

        if (duree) { $('#duree_minutes').val(duree); $('#duree_recommandee').text(duree + ' min'); }
        else $('#duree_recommandee').text('-');
    });

    if ($('#produit_id').val()) $('#produit_id').trigger('change');

    $('form').submit(function(e) {
        const qte = parseInt($('#quantite').val());
        const max = parseInt($('#quantite').attr('max'));
        if (max && qte > max) { e.preventDefault(); alert(`La quantité ne peut pas dépasser ${max} unité(s) disponible(s)`); return false; }
    });
});
</script>
@endpush
@endsection