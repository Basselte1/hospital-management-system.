@extends('layouts.admin')
@section('title', 'CMCU | Fiches de Consommables')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('show', \App\Models\User::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Fiches de Consommables</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Gestion des consommables par patient</p>
                </div>
                <div class="tw-flex tw-gap-2 tw-flex-wrap">
                    <a href="{{ route('patients.index') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                        <i class="fas fa-list-ul tw-text-xs"></i> Liste des patients
                    </a>
                    <a href="{{ route('patients.show', $patient->id) }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                        <i class="fas fa-arrow-left tw-text-xs"></i> Dossier patient
                    </a>
                </div>
            </div>

            {{-- Flash messages --}}
            @if($errors->any())
            <div class="tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-red-600">
                <p class="tw-font-semibold tw-mb-1"><i class="fas fa-exclamation-circle tw-mr-1.5"></i> Erreurs de validation</p>
                <ul class="tw-list-disc tw-list-inside tw-space-y-0.5 tw-text-xs">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6] tw-shrink-0"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-5 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-400 tw-shrink-0"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Patient info banner --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-5 tw-mb-6 tw-flex tw-items-center tw-gap-4">
                <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                    <i class="fas fa-user tw-text-[#1D4ED8]"></i>
                </div>
                <div>
                    <p class="tw-font-bold tw-text-slate-800 tw-mb-0">{{ $patient->name }} {{ $patient->prenom }}</p>
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Dossier N° <span class="tw-font-mono tw-font-semibold tw-text-slate-600">{{ $patient->numero_dossier }}</span></p>
                </div>
            </div>

            {{-- Main table card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-pills tw-text-white tw-text-sm"></i>
                    </div>
                    <h2 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">Liste des Consommables</h2>
                </div>

                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold tw-w-[38%]">Consommable</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold tw-w-[10%]">P (Jour)</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold tw-w-[10%]">G (Nuit)</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold tw-w-[12%]">Date</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold tw-w-[15%]">IDE</th>
                                <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold tw-w-[15%]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">

                            {{-- ── INLINE ADD FORM ROW ─────────────────────── --}}
                            <form method="POST" action="{{ route('fiche_consommable.store') }}" id="formConsommable">
                                @csrf
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                <input type="hidden" name="user_id"    value="{{ $user_id }}">
                                <tr class="tw-bg-[#BFDBFE]/15">
                                    <td class="tw-px-3 tw-py-2.5">
                                        <input type="text"
                                               name="consommable"
                                               id="consommable"
                                               list="consommablesList"
                                               placeholder="Saisir ou sélectionner..."
                                               value="{{ old('consommable') }}"
                                               required
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-[#BFDBFE] tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('consommable') tw-border-red-400 @enderror">
                                        <datalist id="consommablesList">
                                            @foreach($produits as $produit)
                                            <option value="{{ $produit->designation }}">
                                                {{ $produit->designation }}
                                                @if($produit->qte_stock <= 0) (Rupture)
                                                @elseif($produit->qte_stock <= $produit->qte_alerte) (Stock faible: {{ $produit->qte_stock }})
                                                @else (Stock: {{ $produit->qte_stock }})
                                                @endif
                                            </option>
                                            @endforeach
                                        </datalist>
                                        @error('consommable')<p class="tw-text-[10px] tw-text-red-500 tw-mt-0.5">{{ $message }}</p>@enderror
                                    </td>
                                    <td class="tw-px-3 tw-py-2.5">
                                        <input type="number" name="jour" min="0" step="1"
                                               value="{{ old('jour', 0) }}" placeholder="0"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-[#BFDBFE] tw-bg-white tw-px-2 tw-py-2 tw-text-sm tw-text-center focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        @error('jour')<p class="tw-text-[10px] tw-text-red-500 tw-mt-0.5">{{ $message }}</p>@enderror
                                    </td>
                                    <td class="tw-px-3 tw-py-2.5">
                                        <input type="number" name="nuit" min="0" step="1"
                                               value="{{ old('nuit', 0) }}" placeholder="0"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-[#BFDBFE] tw-bg-white tw-px-2 tw-py-2 tw-text-sm tw-text-center focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        @error('nuit')<p class="tw-text-[10px] tw-text-red-500 tw-mt-0.5">{{ $message }}</p>@enderror
                                    </td>
                                    <td class="tw-px-3 tw-py-2.5">
                                        <input type="date" name="date"
                                               value="{{ old('date', \Carbon\Carbon::now()->toDateString()) }}"
                                               required
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-[#BFDBFE] tw-bg-white tw-px-2 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                        @error('date')<p class="tw-text-[10px] tw-text-red-500 tw-mt-0.5">{{ $message }}</p>@enderror
                                    </td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-text-slate-400 tw-italic">
                                        Auto
                                    </td>
                                    <td class="tw-px-3 tw-py-2.5 tw-text-center">
                                        <button type="submit"
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-gap-1.5 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-text-xs tw-font-medium tw-px-3 tw-py-2 tw-border-0 tw-transition-colors tw-w-full">
                                            <i class="fas fa-save tw-text-[9px]"></i> Enregistrer
                                        </button>
                                    </td>
                                </tr>
                            </form>

                            {{-- ── SUB-HEADER for list ─────────────────────── --}}
                            <tr class="tw-bg-slate-100/60">
                                <td class="tw-px-4 tw-py-2 tw-text-[10px] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-400">Consommable</td>
                                <td class="tw-px-4 tw-py-2 tw-text-[10px] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-400 tw-text-center">Jour</td>
                                <td class="tw-px-4 tw-py-2 tw-text-[10px] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-400 tw-text-center">Nuit</td>
                                <td class="tw-px-4 tw-py-2 tw-text-[10px] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-400 tw-text-center">Date</td>
                                <td class="tw-px-4 tw-py-2 tw-text-[10px] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-400">IDE</td>
                                <td class="tw-px-4 tw-py-2 tw-text-[10px] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-400 tw-text-center">Action</td>
                            </tr>

                            {{-- ── RECORDS ─────────────────────────────────── --}}
                            @forelse($consommables as $consommable)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3 tw-font-medium tw-text-slate-700">{{ $consommable->consommable }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <span class="tw-inline-flex tw-items-center tw-justify-center tw-min-w-[28px] tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-xs tw-font-semibold tw-px-2 tw-py-0.5">
                                        {{ $consommable->jour ?? 0 }}
                                    </span>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <span class="tw-inline-flex tw-items-center tw-justify-center tw-min-w-[28px] tw-rounded-full tw-bg-slate-100 tw-text-slate-600 tw-text-xs tw-font-semibold tw-px-2 tw-py-0.5">
                                        {{ $consommable->nuit ?? 0 }}
                                    </span>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center tw-text-sm tw-text-slate-500">
                                    {{ \Carbon\Carbon::parse($consommable->date)->format('d/m/Y') }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-sm tw-text-slate-500">
                                    {{ $consommable->user->name ?? 'N/A' }} {{ $consommable->user->prenom ?? '' }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-center">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-600 tw-border-0 tw-transition-colors"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $consommable->id }}"
                                            title="Modifier">
                                            <i class="fas fa-edit tw-text-xs"></i>
                                        </button>
                                        <form method="POST" action="{{ route('fiche_consommable.destroy', $consommable->id) }}" class="tw-inline"
                                              onsubmit="return confirm('Supprimer ce consommable ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-transition-colors"
                                                title="Supprimer">
                                                <i class="fas fa-trash tw-text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- ── Edit Modal ───────────────────────────────── --}}
                            <div class="modal fade" id="editModal{{ $consommable->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
                                        <div class="tw-px-6 tw-py-4 tw-bg-amber-500 tw-flex tw-items-center tw-justify-between">
                                            <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm">
                                                <i class="fas fa-edit tw-mr-2"></i> Modifier le Consommable
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white tw-text-xs" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('fiche_consommable.update', $consommable->id) }}">
                                            @csrf @method('PUT')
                                            <div class="tw-p-5 tw-space-y-4">
                                                <div>
                                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Consommable</label>
                                                    <input type="text"
                                                           name="consommable"
                                                           id="consommable{{ $consommable->id }}"
                                                           list="consommablesList{{ $consommable->id }}"
                                                           value="{{ $consommable->consommable }}"
                                                           required
                                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                    <datalist id="consommablesList{{ $consommable->id }}">
                                                        @foreach($produits as $produit)
                                                        <option value="{{ $produit->designation }}">{{ $produit->designation }}</option>
                                                        @endforeach
                                                    </datalist>
                                                </div>
                                                <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                                    <div>
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">P (Jour)</label>
                                                        <input type="number" name="jour" min="0"
                                                               value="{{ $consommable->jour }}"
                                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-center focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                    </div>
                                                    <div>
                                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">G (Nuit)</label>
                                                        <input type="number" name="nuit" min="0"
                                                               value="{{ $consommable->nuit }}"
                                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-center focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date <span class="tw-text-red-500">*</span></label>
                                                    <input type="date" name="date" value="{{ $consommable->date }}" required
                                                           class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                                </div>
                                            </div>
                                            <div class="tw-px-5 tw-py-4 tw-border-t tw-border-slate-100 tw-flex tw-justify-end tw-gap-3">
                                                <button type="button" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-4 tw-py-2 tw-border-0 tw-text-sm" data-bs-dismiss="modal">
                                                    Annuler
                                                </button>
                                                <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-px-5 tw-py-2 tw-border-0 tw-text-sm">
                                                    <i class="fas fa-save tw-text-xs"></i> Enregistrer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="6" class="tw-px-4 tw-py-12 tw-text-center">
                                    <div class="tw-w-12 tw-h-12 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-3">
                                        <i class="fas fa-pills tw-text-slate-300 tw-text-xl"></i>
                                    </div>
                                    <p class="tw-text-slate-400 tw-text-sm tw-italic tw-mb-0">Aucun consommable enregistré pour ce patient</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($consommables->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $consommables->links() }}
                </div>
                @endif
            </div>

        </main>
        @endcan
    </div>
</div>

@section('script')
<script>
waitForjQuery(function() {
    $(document).ready(function() {
        $('#formConsommable').on('submit', function(e) {
            var consommable = $('#consommable').val();
            var date        = $('input[name="date"]').val();
            var jour        = $('input[name="jour"]').val();
            var nuit        = $('input[name="nuit"]').val();

            if (!consommable || consommable.trim() === '') {
                e.preventDefault();
                alert('Veuillez sélectionner un consommable');
                return false;
            }
            if (!date) {
                e.preventDefault();
                alert('Veuillez saisir une date');
                return false;
            }
            if ((!jour || jour == 0) && (!nuit || nuit == 0)) {
                if (!confirm("Aucune quantité n'est renseignée. Voulez-vous continuer ?")) {
                    e.preventDefault();
                    return false;
                }
            }
            return true;
        });

        setTimeout(function() { $('.alert').fadeOut('slow'); }, 5000);
    });
});
</script>
@endsection
@endsection