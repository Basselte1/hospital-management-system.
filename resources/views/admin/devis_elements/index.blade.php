@extends('layouts.admin')
@section('title', 'CMCU | Gestion des éléments de devis')
@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')
        @can('viewAny', \App\Models\DevisElement::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-2xl tw-mx-auto">
                {{-- ── Page Header ──────────────────────────────────────── --}}
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-8">
                    {{-- ... existing content ... --}}
                    <div>
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                            <span class="tw-text-xs tw-font-semibold tw-tracking-widest tw-text-primary-700 tw-uppercase tw-bg-primary-100 tw-px-2.5 tw-py-1 tw-rounded-full">Catalogue</span>
                        </div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-tracking-tight">Éléments de Devis</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-0.5">Gestion des éléments prédéfinis pour les devis</p>
                    </div>
                    <button type="button"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-primary-700 hover:tw-bg-primary-800 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-rounded-xl tw-shadow-blue tw-transition-all tw-duration-150 tw-border-0 tw-cursor-pointer tw-whitespace-nowrap"
                            data-bs-toggle="modal"
                            data-bs-target="#addElementModal">
                        <i class="fas fa-plus tw-text-xs"></i>
                        Nouvel Élément
                    </button>
                </div>
                {{-- ── Table Card ───────────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">

                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                        <div class="tw-flex tw-items-center tw-gap-2.5">
                            <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-primary-700"></div>
                            <span class="tw-text-sm tw-font-semibold tw-text-slate-700">Tous les éléments</span>
                        </div>
                        <span class="tw-text-xs tw-text-slate-400">{{ $elements->total() }} résultat(s)</span>
                    </div>

                    <div class="tw-overflow-x-auto">
                        <table id="elementsTable" class="tw-w-full tw-text-sm">
                            <thead>
                                <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-200">
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Nom</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Code</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Prix unitaire</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Statut</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Créé par</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Date</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                @foreach($elements as $element)
                                <tr class="hover:tw-bg-slate-50/70 tw-transition-colors tw-duration-100">

                                    {{-- Nom --}}
                                    <td class="tw-px-5 tw-py-3.5">
                                        <span class="tw-font-semibold tw-text-slate-800 tw-text-sm">{{ $element->nom }}</span>
                                        @if($element->description)
                                            <p class="tw-text-xs tw-text-slate-400 tw-mt-0.5 tw-mb-0">{{ Str::limit($element->description, 50) }}</p>
                                        @endif
                                    </td>

                                    {{-- Code --}}
                                    <td class="tw-px-5 tw-py-3.5">
                                        @if($element->code)
                                            <span class="tw-font-mono tw-text-xs tw-font-semibold tw-text-primary-700 tw-bg-primary-50 tw-px-2 tw-py-1 tw-rounded-lg">{{ $element->code }}</span>
                                        @else
                                            <span class="tw-text-slate-300 tw-text-xs">—</span>
                                        @endif
                                    </td>

                                    {{-- Prix --}}
                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-whitespace-nowrap">
                                        <span class="tw-font-semibold tw-text-slate-800">{{ number_format($element->prix_unitaire, 0, ',', ' ') }}&nbsp;FCFA</span>
                                    </td>

                                    {{-- Statut --}}
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                        @if($element->actif)
                                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-emerald-700 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-px-2.5 tw-py-1 tw-rounded-full">
                                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-emerald-500 tw-inline-block"></span>
                                                Actif
                                            </span>
                                        @else
                                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-amber-700 tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-2.5 tw-py-1 tw-rounded-full">
                                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-amber-400 tw-inline-block"></span>
                                                Inactif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Créé par --}}
                                    <td class="tw-px-5 tw-py-3.5 tw-text-sm tw-text-slate-600">{{ $element->user->name ?? 'N/A' }}</td>

                                    {{-- Date --}}
                                    <td class="tw-px-5 tw-py-3.5">
                                        <span class="tw-text-xs tw-text-slate-500">{{ $element->created_at->format('d/m/Y') }}</span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="tw-px-5 tw-py-3.5">
                                        <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                            <button type="button"
                                                    class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-primary-200 tw-bg-primary-50 hover:tw-bg-primary-100 tw-text-primary-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editElementModal"
                                                    data-id="{{ $element->id }}"
                                                    data-nom="{{ $element->nom }}"
                                                    data-code="{{ $element->code }}"
                                                    data-prix="{{ $element->prix_unitaire }}"
                                                    data-description="{{ $element->description }}"
                                                    data-actif="{{ $element->actif }}"
                                                    title="Modifier">
                                                <i class="fas fa-edit tw-text-xs"></i>
                                            </button>

                                            <form action="{{ route('devis_elements.destroy', $element->id) }}" method="POST" class="tw-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-red-200 tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash tw-text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($elements->hasPages())
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-bg-slate-50/50">
                        {{ $elements->links() }}
                    </div>
                    @endif

                </div>

                {{-- ... rest of existing content ... --}}
                {{-- ══ Add Element Modal ══════════════════════════════════════════ --}}
        <div class="modal fade" id="addElementModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-card-md tw-overflow-hidden">
                    <form action="{{ route('devis_elements.store') }}" method="POST">
                        @csrf
                        <div class="modal-header tw-bg-primary-900 tw-border-0 tw-px-6 tw-py-4">
                            <div class="tw-flex tw-items-center tw-gap-3">
                                <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                                    <i class="fas fa-plus tw-text-white tw-text-sm"></i>
                                </div>
                                <h5 class="modal-title tw-text-white tw-font-semibold tw-text-base tw-mb-0">Nouvel Élément de Devis</h5>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body tw-p-6 tw-space-y-4">

                            <div>
                                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Nom <span class="tw-text-red-500">*</span></label>
                                <input type="text" class="form-control" name="nom" required placeholder="Ex: CS ANESTHESIQUE EN INTERNE">
                            </div>

                            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Code</label>
                                    <input type="text" class="form-control" name="code" placeholder="Ex: KC, KA">
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Prix Unitaire (FCFA) <span class="tw-text-red-500">*</span></label>
                                    <input type="number" class="form-control" name="prix_unitaire" required min="0" placeholder="Ex: 25000">
                                </div>
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Description optionnelle"></textarea>
                            </div>

                            <div class="tw-flex tw-items-center tw-gap-2.5 tw-pt-1">
                                <input class="form-check-input tw-mt-0" type="checkbox" name="actif" value="1" checked id="actif_add">
                                <label class="tw-text-sm tw-font-medium tw-text-slate-700 tw-cursor-pointer" for="actif_add">Actif</label>
                            </div>
                        </div>
                        <div class="modal-footer tw-border-t tw-border-slate-100 tw-px-6 tw-py-4 tw-bg-slate-50/50">
                            <button type="button" class="tw-px-4 tw-py-2 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-transition-colors tw-duration-150" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="tw-px-5 tw-py-2 tw-rounded-lg tw-bg-primary-700 hover:tw-bg-primary-800 tw-text-white tw-text-sm tw-font-semibold tw-transition-colors tw-duration-150 tw-border-0">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══ Edit Element Modal ═════════════════════════════════════════ --}}
        <div class="modal fade" id="editElementModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-card-md tw-overflow-hidden">
                    <form id="editElementForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header tw-bg-slate-700 tw-border-0 tw-px-6 tw-py-4">
                            <div class="tw-flex tw-items-center tw-gap-3">
                                <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                                    <i class="fas fa-edit tw-text-white tw-text-sm"></i>
                                </div>
                                <h5 class="modal-title tw-text-white tw-font-semibold tw-text-base tw-mb-0">Modifier l'Élément</h5>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body tw-p-6 tw-space-y-4">

                            <div>
                                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5" for="edit_nom">Nom <span class="tw-text-red-500">*</span></label>
                                <input type="text" class="form-control" name="nom" id="edit_nom" required>
                            </div>

                            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5" for="edit_code">Code</label>
                                    <input type="text" class="form-control" name="code" id="edit_code">
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5" for="edit_prix_unitaire">Prix Unitaire (FCFA) <span class="tw-text-red-500">*</span></label>
                                    <input type="number" class="form-control" name="prix_unitaire" id="edit_prix_unitaire" required min="0">
                                </div>
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5" for="edit_description">Description</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                            </div>

                            <div class="tw-flex tw-items-center tw-gap-2.5 tw-pt-1">
                                <input class="form-check-input tw-mt-0" type="checkbox" name="actif" value="1" id="edit_actif">
                                <label class="tw-text-sm tw-font-medium tw-text-slate-700 tw-cursor-pointer" for="edit_actif">Actif</label>
                            </div>
                        </div>
                        <div class="modal-footer tw-border-t tw-border-slate-100 tw-px-6 tw-py-4 tw-bg-slate-50/50">
                            <button type="button" class="tw-px-4 tw-py-2 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-transition-colors tw-duration-150" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="tw-px-5 tw-py-2 tw-rounded-lg tw-bg-primary-700 hover:tw-bg-primary-800 tw-text-white tw-text-sm tw-font-semibold tw-transition-colors tw-duration-150 tw-border-0">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

            </div>
        </main>
        @endcan
    </div>
</div>
@section('script')
{{-- ... existing scripts ... --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editElementModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id          = button.getAttribute('data-id');
        const nom         = button.getAttribute('data-nom');
        const code        = button.getAttribute('data-code');
        const prix        = button.getAttribute('data-prix');
        const description = button.getAttribute('data-description');
        const actif       = button.getAttribute('data-actif');

        const form = editModal.querySelector('#editElementForm');
        form.action = `/admin/devis-elements/${id}`;

        editModal.querySelector('#edit_nom').value          = nom         ?? '';
        editModal.querySelector('#edit_code').value         = code        ?? '';
        editModal.querySelector('#edit_prix_unitaire').value = prix       ?? '';
        editModal.querySelector('#edit_description').value  = description ?? '';
        editModal.querySelector('#edit_actif').checked      = actif == 1;
    });
});
</script>
@endsection