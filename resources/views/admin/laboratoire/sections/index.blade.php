{{-- ============================================================
     SECTIONS INDEX  —  admin.laboratoire.sections.index
     ============================================================ --}}
{{-- Save this file as: resources/views/admin/laboratoire/sections/index.blade.php --}}

@extends('layouts.admin')
@section('title', 'CMCU | Sections du laboratoire')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Breadcrumb --}}
            <nav class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-500 tw-mb-6">
                <a href="{{ route('tarifs_labo.index') }}" class="hover:tw-text-[#1D4ED8] tw-no-underline">Tarifs laboratoire</a>
                <i class="fas fa-chevron-right tw-text-[10px] tw-text-slate-300"></i>
                <span class="tw-text-slate-700 tw-font-medium">Sections</span>
            </nav>

            {{-- Flash --}}
            @if(session('success'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-check-circle tw-text-green-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-green-700 tw-mb-0">{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="tw-mb-4 tw-flex tw-items-center tw-gap-2 tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-xl tw-px-4 tw-py-3">
                <i class="fas fa-circle-exclamation tw-text-red-500 tw-text-sm"></i>
                <p class="tw-text-sm tw-text-red-600 tw-mb-0">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Header row --}}
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
                <div>
                    <h1 class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0">Sections d'examens</h1>
                    <p class="tw-text-sm tw-text-slate-400 tw-mb-0">Gérez les catégories de tests du laboratoire (ex : Biochimie, Hématologie…)</p>
                </div>
                @if(auth()->user()->role_id === 1)
                <a href="{{ route('sections_labo.create') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-blue-700 tw-text-white tw-text-sm tw-font-semibold tw-no-underline tw-transition-colors tw-shadow-sm">
                    <i class="fas fa-plus tw-text-xs"></i> Nouvelle section
                </a>
                @endif
            </div>

            {{-- Sections table --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <table class="tw-w-full tw-text-sm">
                    <thead>
                        <tr class="tw-border-b tw-border-slate-100 tw-text-xs tw-text-slate-500 tw-uppercase tw-tracking-wide">
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-font-semibold tw-w-10">#</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-font-semibold">Section</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-font-semibold">Slug</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-font-semibold">Icône</th>
                            <th class="tw-px-6 tw-py-3 tw-text-center tw-font-semibold">Tests actifs</th>
                            <th class="tw-px-6 tw-py-3 tw-text-center tw-font-semibold">Statut</th>
                            @if(auth()->user()->role_id === 1)
                            <th class="tw-px-6 tw-py-3 tw-text-right tw-font-semibold">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                        <tr class="tw-border-b tw-border-slate-50 hover:tw-bg-slate-50 tw-transition-colors">
                            <td class="tw-px-6 tw-py-3 tw-text-slate-400 tw-font-mono">{{ $section->ordre }}</td>
                            <td class="tw-px-6 tw-py-3">
                                <span class="tw-font-semibold tw-text-slate-800">{{ $section->label }}</span>
                            </td>
                            <td class="tw-px-6 tw-py-3">
                                <code class="tw-text-xs tw-bg-slate-100 tw-text-slate-600 tw-px-2 tw-py-0.5 tw-rounded">{{ $section->slug }}</code>
                            </td>
                            <td class="tw-px-6 tw-py-3">
                                <i class="fas {{ $section->icon }} tw-text-slate-500"></i>
                                <code class="tw-text-xs tw-text-slate-400 tw-ml-1">{{ $section->icon }}</code>
                            </td>
                            <td class="tw-px-6 tw-py-3 tw-text-center">
                                <a href="{{ route('tarifs_labo.index', ['section' => $section->slug]) }}"
                                   class="tw-inline-flex tw-items-center tw-gap-1 tw-text-[#1D4ED8] tw-no-underline hover:tw-underline tw-text-xs tw-font-semibold">
                                    {{ $section->tarifs_actifs_count ?? 0 }} tests
                                    <i class="fas fa-arrow-right tw-text-[9px]"></i>
                                </a>
                            </td>
                            <td class="tw-px-6 tw-py-3 tw-text-center">
                                @if($section->actif)
                                    <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2.5 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-700">
                                        <i class="fas fa-circle tw-text-[7px]"></i> Actif
                                    </span>
                                @else
                                    <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2.5 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium tw-bg-slate-100 tw-text-slate-500">
                                        <i class="fas fa-circle tw-text-[7px]"></i> Désactivé
                                    </span>
                                @endif
                            </td>
                            @if(auth()->user()->role_id === 1)
                            <td class="tw-px-6 tw-py-3 tw-text-right">
                                <div class="tw-flex tw-items-center tw-justify-end tw-gap-2">
                                    <a href="{{ route('sections_labo.edit', $section) }}"
                                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-text-xs tw-font-medium tw-rounded-lg tw-border tw-border-slate-200 tw-text-slate-600 hover:tw-bg-slate-50 tw-no-underline tw-transition-colors">
                                        <i class="fas fa-pen-to-square tw-text-[10px]"></i> Modifier
                                    </a>

                                    <form method="POST" action="{{ route('sections_labo.toggle', $section) }}" class="tw-inline">
                                        @csrf
                                        <button type="submit"
                                                class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-text-xs tw-font-medium tw-rounded-lg tw-border tw-transition-colors
                                                {{ $section->actif
                                                    ? 'tw-border-orange-200 tw-text-orange-600 hover:tw-bg-orange-50'
                                                    : 'tw-border-green-200 tw-text-green-600 hover:tw-bg-green-50' }}">
                                            <i class="fas {{ $section->actif ? 'fa-toggle-off' : 'fa-toggle-on' }} tw-text-[10px]"></i>
                                            {{ $section->actif ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('sections_labo.destroy', $section) }}" class="tw-inline"
                                          onsubmit="return confirm('Supprimer la section « {{ $section->label }} » ? Cette action est irréversible.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-text-xs tw-font-medium tw-rounded-lg tw-border tw-border-red-200 tw-text-red-600 hover:tw-bg-red-50 tw-transition-colors">
                                            <i class="fas fa-trash-alt tw-text-[10px]"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="tw-px-6 tw-py-12 tw-text-center tw-text-slate-400">
                                <i class="fas fa-flask tw-text-3xl tw-text-slate-200 tw-block tw-mb-3"></i>
                                Aucune section configurée.
                                @if(auth()->user()->role_id === 1)
                                <a href="{{ route('sections_labo.create') }}" class="tw-block tw-mt-2 tw-text-[#1D4ED8] hover:tw-underline tw-no-underline tw-text-sm">
                                    + Créer la première section
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Info box --}}
            <div class="tw-mt-5 tw-p-4 tw-bg-blue-50 tw-border tw-border-blue-200 tw-rounded-xl tw-text-xs tw-text-blue-700">
                <i class="fas fa-info-circle tw-mr-1"></i>
                Le <strong>slug</strong> d'une section (ex: <code>biochimie</code>) est défini à la création et ne peut plus être modifié.
                Il est utilisé comme clé dans les bons d'examens et les prescriptions.
                Pour ajouter ou modifier les tests d'une section, allez dans
                <a href="{{ route('tarifs_labo.index') }}" class="tw-font-semibold hover:tw-underline">Tarifs laboratoire</a>.
            </div>

        </main>
    </div>
</div>
@stop