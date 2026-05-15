@extends('layouts.admin')
@section('title', 'CMCU | Liste des Patients')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')
  
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('create', \App\Models\Patient::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-rounded-2xl tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-shadow-sm">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-users tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Liste des Patients</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">Rechercher et gérer les dossiers patients</p>
                    </div>
                </div>
                @can('print', \App\Models\Patient::class)
                <a href="{{ route('patients.create') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white tw-text-[#1D4ED8] tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline hover:tw-bg-[#BFDBFE] tw-transition-colors tw-shrink-0">
                    <i class="fas fa-plus-circle tw-text-xs"></i> Ajouter un patient
                </a>
                @endcan
            </div>

            {{-- Search card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-6">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-bg-slate-50 tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-search tw-text-[#1D4ED8] tw-text-sm"></i>
                    <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Rechercher un Patient</h2>
                </div>
                <div class="tw-p-5">
                    <form action="{{ route('search.results') }}" method="GET">
                        <!--@csrf-->
                        <div class="tw-flex tw-flex-col sm:tw-flex-row tw-gap-3">
                            <div class="tw-flex-1">
                                <label for="name" class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Nom ou prénom du patient
                                </label>
                                <input type="text" name="name" id="name" required
                                       placeholder="Entrez le nom ou prénom du patient..."
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-3 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                            </div>
                            <div class="tw-flex tw-items-end">
                                <button type="submit"
                                    class="tw-w-full sm:tw-w-auto tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-px-6 tw-py-3 tw-border-0 tw-text-sm tw-transition-colors">
                                    <i class="fas fa-search tw-text-xs"></i> Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Search results --}}
            @if(isset($patients))
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                {{-- Results info banner --}}
                <div class="tw-px-5 tw-py-3 tw-bg-[#BFDBFE]/20 tw-border-b tw-border-[#BFDBFE]/40 tw-flex tw-items-center tw-gap-2.5">
                    <i class="fas fa-info-circle tw-text-[#1D4ED8] tw-text-sm tw-shrink-0"></i>
                    <p class="tw-text-sm tw-text-[#1D4ED8] tw-mb-0">
                        Résultats pour <strong class="tw-font-bold">"{{ $name }}"</strong>
                        <span class="tw-ml-2 tw-inline-flex tw-items-center tw-rounded-full tw-bg-[#1D4ED8] tw-text-white tw-px-2 tw-py-0.5 tw-text-[10px] tw-font-semibold">
                            {{ $patients->count() }} résultat(s)
                        </span>
                    </p>
                </div>

                <div class="tw-overflow-x-auto">
                    <table id="myTable" class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-[#1D4ED8]">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Numéro</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Nom</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Prénom</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Assurance</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Date de création</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($patients as $patient)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3">
                                    <span class="tw-inline-flex tw-items-center tw-rounded-lg tw-bg-slate-600 tw-text-white tw-px-2.5 tw-py-1 tw-text-xs tw-font-mono tw-font-semibold">
                                        {{ $patient->numero_dossier }}
                                    </span>
                                    @if($patient->isNew())
                                    <span class="tw-ml-1.5 tw-inline-flex tw-items-center tw-rounded-full tw-bg-teal-100 tw-text-teal-700 tw-px-2 tw-py-0.5 tw-text-[10px] tw-font-bold tw-uppercase tw-animate-pulse">
                                        Nouveau
                                    </span>
                                    @endif
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-font-semibold tw-text-slate-700">{{ $patient->name }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600">{{ $patient->prenom }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    @if($patient->prise_en_charge)
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-px-2.5 tw-py-0.5 tw-text-xs tw-font-semibold">
                                        {{ $patient->prise_en_charge }}
                                    </span>
                                    @else
                                    <span class="tw-text-slate-400 tw-text-xs tw-italic">Aucune</span>
                                    @endif
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-sm tw-text-slate-500 tw-whitespace-nowrap">{{ $patient->date_insertion }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                        @can('consulter', \App\Models\Patient::class)
                                        <a href="{{ route('patients.show', $patient->id) }}"
                                           title="Consulter le dossier"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>
                                        @endcan

                                        @can('print', \App\Models\Patient::class)
                                            <a href="{{ route('patient.generer.facture', $patient->id) }}"
                                            title="Générer la facture"
                                            onclick="if(this.disabled){ return false; } else { this.disabled = true; }"
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-600 tw-transition-colors tw-no-underline">
                                                <i class="fas fa-file-invoice tw-text-xs"></i>
                                            </a>
                                        @endcan

                                        @can('delete', \App\Models\Patient::class)
                                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="tw-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Supprimer le dossier" onclick="return myFunction()"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-transition-colors">
                                                <i class="fas fa-trash-alt tw-text-xs"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                @if($patients->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $patients->links() }}
                </div>
                @endif
            </div>
            @endif

        </main>
        @endcan
    </div>
</div>

<script>
function myFunction() {
    if (!confirm("Veuillez confirmer la suppression du dossier patient"))
        event.preventDefault();
}
</script>
@stop