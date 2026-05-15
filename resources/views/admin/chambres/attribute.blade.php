@extends('layouts.admin')
@section('title', 'CMCU | Attribution de Chambre')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8 tw-flex tw-items-start tw-justify-center">
            <div class="tw-w-full tw-max-w-lg">

                {{-- Page heading --}}
                <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                    <div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Attribution de Chambre</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Assigner une chambre à un patient</p>
                    </div>
                    <a href="{{ route('chambres.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                        <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                    </a>
                </div>

                {{-- Form card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-bed tw-text-white tw-text-sm"></i>
                        </div>
                        <h2 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">Informations de la Chambre</h2>
                    </div>

                    <form method="POST" action="{{ route('chambres_status.update', $chambre->id) }}" class="tw-p-6 tw-space-y-5">
                        @method('PATCH')
                        @csrf

                        {{-- Numéro (disabled) --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Numéro</label>
                            <input type="text" name="numero" value="{{ $chambre->numero }}" disabled
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-400 tw-cursor-not-allowed">
                        </div>

                        {{-- Catégorie (disabled) --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Catégorie</label>
                            <select name="categorie" disabled
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-400 tw-cursor-not-allowed">
                                <option value="{{ $chambre->categorie }}" selected>{{ $chambre->categorie }}</option>
                            </select>
                        </div>

                        <input type="hidden" name="statut" value="occupé">

                        {{-- Patient --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Patient <span class="tw-text-red-500">*</span>
                            </label>
                            <select name="patient" id="patient" required
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                <option value="">— Sélectionner un patient —</option>
                                @foreach($patients as $patient)
                                <option value="{{ $patient->name }}">{{ $patient->name }} {{ $patient->prenom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nombre de jours --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                Nombre de jours <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="number" name="jour" value="{{ request('jour') }}" min="1"
                                   placeholder="Ex: 3"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                        </div>

                        {{-- Actions --}}
                        <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-pt-2 tw-border-t tw-border-slate-100">
                            <a href="{{ route('chambres.index') }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-5 tw-py-2.5 tw-transition-colors tw-no-underline tw-text-sm">
                                Annuler
                            </a>
                            <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-px-6 tw-py-2.5 tw-border-0 tw-text-sm tw-transition-colors">
                                <i class="fas fa-save tw-text-xs"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection