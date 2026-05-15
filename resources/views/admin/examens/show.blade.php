@extends('layouts.admin')
@section('title', 'CMCU | Dossier examen patient')

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
                <span class="tw-text-slate-700 tw-font-medium">Visualisation</span>
            </nav>

            <div class="tw-max-w-3xl">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                    {{-- Card header --}}
                    <div class="tw-px-6 tw-py-5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-x-ray tw-text-indigo-500 tw-text-sm"></i>
                        </div>
                        <div>
                            <h1 class="tw-text-base tw-font-bold tw-text-slate-800 tw-mb-0">{{ $examens->type }}</h1>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0 tw-mt-0.5">Type d'examen</p>
                        </div>
                        <div class="tw-ml-auto">
                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1 tw-rounded-full tw-bg-indigo-50 tw-text-indigo-700 tw-text-xs tw-font-medium">
                                <i class="fas fa-x-ray tw-text-[10px]"></i>
                                {{ $examens->type }}
                            </span>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="tw-p-6">
                        <div class="tw-bg-slate-50 tw-rounded-xl tw-border tw-border-slate-100 tw-overflow-hidden tw-flex tw-items-center tw-justify-center tw-min-h-[300px] tw-p-4">
                            <img
                                src="{{ asset('images/' . $examens->image) }}"
                                alt="Examen : {{ $examens->type }}"
                                class="tw-max-w-full tw-h-auto tw-rounded-lg tw-shadow-sm"
                                style="max-height: 600px;"
                            >
                        </div>

                        {{-- Actions --}}
                        <div class="tw-mt-5 tw-flex tw-items-center tw-gap-3">
                            <a href="{{ asset('images/' . $examens->image) }}"
                               target="_blank"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-no-underline tw-shadow-sm">
                                <i class="fas fa-expand tw-text-xs"></i>
                                Ouvrir en plein écran
                            </a>
                            <a href="{{ route('examens.index') }}"
                               class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                                <i class="fas fa-arrow-left tw-text-xs"></i>
                                Retour à la liste
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </div>
</div>
@stop