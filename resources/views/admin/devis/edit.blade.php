@extends('layouts.admin')
@section('title', 'CMCU | Édition du devis')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('create', \App\Models\Patient::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Édition du Devis</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Patient : <span class="tw-font-semibold tw-text-[#1D4ED8]">{{ $devi->nom }}</span></p>
                </div>
                <a href="{{ route('devis.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour aux devis
                </a>
            </div>

            <div class="tw-max-w-xl">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                    {{-- Card Header --}}
                    <div class="tw-px-6 tw-py-5 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-file-invoice tw-text-white"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">{{ $devi->nom }}</h2>
                            <p class="tw-text-white/70 tw-text-xs tw-mt-0.5 tw-mb-0">Impression du devis pour un patient</p>
                        </div>
                    </div>

                    <div class="tw-p-6">
                        <form class="tw-space-y-5" action="{{ route('devis.pdf', $devi->id) }}" method="POST">
                            @csrf

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Nom du patient <span class="tw-text-red-500">*</span>
                                </label>
                                <select name="patient_id" id="patient_id" required
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                    <option value="">Sélectionnez un patient</option>
                                    @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }} {{ $patient->prenom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="tw-flex tw-gap-3 tw-pt-2">
                                <button type="submit"
                                    class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-py-2.5 tw-transition-colors tw-border-0">
                                    <i class="fas fa-print tw-text-xs"></i> Imprimer le devis
                                </button>
                                <a href="{{ route('devis.index') }}"
                                   class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-transition-colors tw-no-underline">
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
        @endcan
    </div>
</div>
@endsection