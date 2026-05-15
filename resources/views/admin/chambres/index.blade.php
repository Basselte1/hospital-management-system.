@extends('layouts.admin')
@section('title', 'CMCU | Liste des Chambres')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('create', \App\Models\chambre::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Liste des Chambres</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Les montants sont exprimés en <strong>FCFA</strong></p>
                </div>
                @can('update', \App\Models\User::class)
                <a href="{{ route('chambres.create') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors">
                    <i class="fas fa-plus-circle tw-text-xs"></i> Ajouter une chambre
                </a>
                @endcan
            </div>

            {{-- Category filter bar --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-4 tw-mb-5 tw-flex tw-items-center tw-flex-wrap tw-gap-2">
                <span class="tw-text-xs tw-font-medium tw-text-slate-500 tw-mr-1">Filtrer :</span>
                <a href="{{ url('/admin/chambres/?categorie=VIP') }}"
                   class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-font-medium tw-text-sm tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                    <i class="fas fa-star tw-text-[10px]"></i> VIP
                </a>
                <a href="{{ url('/admin/chambres/?categorie=CLASSIQUE') }}"
                   class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-text-sm tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                    <i class="fas fa-bed tw-text-[10px]"></i> Classique
                </a>
                <a href="{{ url('/admin/chambres/?categorie=BLOC OPERATOIRE') }}"
                   class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-text-sm tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                    <i class="fas fa-hospital tw-text-[10px]"></i> Bloc
                </a>
                <a href="{{ url('/admin/chambres') }}"
                   class="tw-inline-flex tw-items-center tw-justify-center tw-w-9 tw-h-9 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-500 tw-no-underline tw-transition-colors"
                   title="Réinitialiser le filtre">
                    <i class="fas fa-rotate tw-text-sm"></i>
                </a>
            </div>

            {{-- Table card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-overflow-x-auto">
                    <table id="myTable" class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-[#1D4ED8]">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">ID</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Numéro</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Catégorie</th>
                                <th class="tw-px-4 tw-py-3 tw-text-right tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Prix</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Statut</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Patient</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Durée</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($chambres as $chambre)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                {{-- ID --}}
                                <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-400 tw-font-mono">{{ $chambre->id }}</td>

                                {{-- Numéro --}}
                                <td class="tw-px-4 tw-py-3 tw-font-semibold tw-text-slate-700">{{ $chambre->numero }}</td>

                                {{-- Catégorie --}}
                                <td class="tw-px-4 tw-py-3">
                                    @php
                                        $catColor = match(strtolower($chambre->categorie)) {
                                            'vip'  => 'tw-bg-amber-100 tw-text-amber-700',
                                            'bloc', 'bloc operatoire' => 'tw-bg-slate-200 tw-text-slate-700',
                                            default => 'tw-bg-[#BFDBFE] tw-text-[#1D4ED8]',
                                        };
                                    @endphp
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-semibold {{ $catColor }}">
                                        {{ $chambre->categorie }}
                                    </span>
                                </td>

                                {{-- Prix --}}
                                <td class="tw-px-4 tw-py-3 tw-text-right tw-font-medium tw-text-slate-600 tw-text-sm">
                                    {{ number_format($chambre->prix) }} F
                                </td>

                                {{-- Statut --}}
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    @if($chambre->statut == 'occupé')
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-red-100 tw-text-red-600 tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-semibold">
                                        <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-red-500 tw-mr-1.5"></span>
                                        Occupé
                                    </span>
                                    @elseif($chambre->statut == 'libre')
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-teal-100 tw-text-teal-700 tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-semibold">
                                        <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-teal-500 tw-mr-1.5"></span>
                                        Libre
                                    </span>
                                    @endif
                                </td>

                                {{-- Patient --}}
                                <td class="tw-px-4 tw-py-3 tw-text-sm">
                                    @if($chambre->patient && $chambre->patient != 'null')
                                    <span class="tw-font-semibold tw-text-slate-700">{{ $chambre->patient }}</span>
                                    @else
                                    <span class="tw-text-slate-400 tw-text-xs tw-italic">—</span>
                                    @endif
                                </td>

                                {{-- Durée --}}
                                <td class="tw-px-4 tw-py-3 tw-text-center tw-text-sm tw-text-slate-500">
                                    @if($chambre->jour && $chambre->jour != 'null')
                                    <span class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-bg-slate-100 tw-text-slate-600 tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-medium">
                                        <i class="fas fa-clock tw-text-[9px]"></i> {{ $chambre->jour }} j
                                    </span>
                                    @else
                                    <span class="tw-text-slate-400 tw-text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="tw-px-4 tw-py-3">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                        @can('update', \App\Models\User::class)
                                        <a href="{{ route('chambres.edit', $chambre->id) }}"
                                           title="Modifier"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-600 tw-transition-colors tw-no-underline">
                                            <i class="far fa-edit tw-text-xs"></i>
                                        </a>
                                        @endcan

                                        @if($chambre->statut == 'occupé')
                                        <form style="display: inline-flex;" action="{{ route('chambres_minus.update', $chambre->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="patient" value="null">
                                            <input type="hidden" name="statut"  value="libre">
                                            <input type="hidden" name="jour"    value="null">
                                            <button type="submit"
                                                title="Libérer la chambre"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-transition-colors">
                                                <i class="fas fa-minus tw-text-xs"></i>
                                            </button>
                                        </form>
                                        @endif

                                        @if($chambre->statut == 'libre')
                                        <a href="{{ route('chambres.attribute', $chambre->id) }}"
                                           title="Attribuer à un patient"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-600 tw-transition-colors tw-no-underline">
                                            <i class="fas fa-plus tw-text-xs"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($chambres->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $chambres->links() }}
                </div>
                @endif
            </div>

        </main>
        @endcan
    </div>
</div>
@endsection