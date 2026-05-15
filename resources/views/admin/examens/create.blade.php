@extends('layouts.admin')
@section('title', 'CMCU | Ajouter un examen')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Breadcrumb --}}
            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="{{ route('examens.index') }}" class="hover:tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">Examens</a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">Importer un examen</span>
            </nav>

            <div class="tw-max-w-xl">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                    {{-- Card header --}}
                    <div class="tw-px-6 tw-py-5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-x-ray tw-text-indigo-500 tw-text-sm"></i>
                        </div>
                        <div>
                            <h1 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">Importer un examen</h1>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">Patient : <span class="tw-font-semibold tw-text-slate-600">{{ $patient->name }} {{ $patient->prenom }}</span></p>
                        </div>
                    </div>

                    {{-- Form body --}}
                    <form class="tw-p-6 tw-space-y-5" method="POST" action="{{ route('examens.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                        {{-- Info notice --}}
                        <div class="tw-flex tw-items-start tw-gap-2.5 tw-bg-[#eff6ff] tw-border tw-border-[#BFDBFE] tw-rounded-xl tw-px-4 tw-py-3">
                            <i class="fas fa-info-circle tw-text-[#1D4ED8] tw-text-sm tw-mt-0.5 tw-shrink-0"></i>
                            <p class="tw-text-xs tw-text-[#1D4ED8] tw-mb-0 tw-leading-relaxed">
                                Tous les champs sont obligatoires. Les formats d'image acceptés sont JPG, PNG, PDF.
                            </p>
                        </div>

                        {{-- Type --}}
                        <div>
                            <label for="type" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Type d'examen <span class="tw-text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="type"
                                name="type"
                                value="{{ old('type') }}"
                                placeholder="Ex: Radiographie, Scanner, Prise de sang..."
                                required
                                class="tw-block tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-800 tw-bg-white tw-border tw-border-slate-200 tw-rounded-xl tw-transition-colors tw-duration-150 focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10"
                            >
                            @error('type')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- File upload --}}
                        <div>
                            <label for="image" class="tw-block tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-1.5">
                                Fichier / Image <span class="tw-text-red-500">*</span>
                            </label>
                            <label for="image"
                                   class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-w-full tw-min-h-[120px] tw-border-2 tw-border-dashed tw-border-slate-200 tw-rounded-xl tw-bg-slate-50 hover:tw-bg-slate-100 hover:tw-border-[#1D4ED8]/50 tw-transition-all tw-duration-150 tw-cursor-pointer tw-px-4 tw-py-6">
                                <i class="fas fa-cloud-upload-alt tw-text-3xl tw-text-slate-400 tw-mb-2"></i>
                                <p class="tw-text-sm tw-text-slate-500 tw-mb-0">Cliquez pour sélectionner un fichier</p>
                                <p class="tw-text-xs tw-text-slate-400 tw-mt-1 tw-mb-0">JPG, PNG, PDF acceptés</p>
                                <input type="file" id="image" name="image" required class="tw-hidden"
                                       onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? ''">
                            </label>
                            <p id="file-name" class="tw-mt-1.5 tw-text-xs tw-text-[#1D4ED8] tw-font-medium"></p>
                            @error('image')
                            <p class="tw-mt-1 tw-text-xs tw-text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="tw-flex tw-items-center tw-gap-3 tw-pt-2">
                            <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-duration-150 tw-shadow-sm tw-border-0 tw-cursor-pointer">
                                <i class="fas fa-upload tw-text-xs"></i>
                                Importer l'examen
                            </button>
                            <a href="{{ route('examens.index') }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-duration-150 tw-no-underline">
                                <i class="fas fa-times tw-text-xs"></i>
                                Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </main>
    </div>
</div>
@stop