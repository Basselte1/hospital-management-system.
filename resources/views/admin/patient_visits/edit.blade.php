@extends('layouts.admin')
@section('title', 'CMCU | Modifier la visite')
@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-lg tw-mx-auto">
                <!-- All existing page content goes here -->
                <div class="tw-min-h-screen tw-bg-slate-50">
                    <div class="tw-max-w-screen-lg tw-mx-auto tw-px-4 tw-py-8 sm:tw-px-6 lg:tw-px-8">

                        {{-- ── Page Header ──────────────────────────────────────── --}}
                        <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-6">
                            <div>
                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                    <span class="tw-text-xs tw-font-semibold tw-tracking-widest tw-text-amber-700 tw-uppercase tw-bg-amber-100 tw-px-2.5 tw-py-1 tw-rounded-full">Modification</span>
                                </div>
                                <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-tracking-tight">Modifier la visite</h1>
                                <p class="tw-text-sm tw-text-slate-500 tw-mt-0.5">
                                    Patient : <strong class="tw-text-slate-700">{{ $visit->patient->name }} {{ $visit->patient->prenom }}</strong>
                                    — Visite du {{ $visit->visit_date->format('d/m/Y') }}
                                </p>
                            </div>
                            <a href="{{ route('patient-visits.show', $visit) }}"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2.5 tw-rounded-xl tw-shadow-card tw-border tw-border-slate-200 tw-transition-all tw-duration-150 tw-no-underline tw-whitespace-nowrap">
                                <i class="fas fa-arrow-left tw-text-xs"></i>
                                Retour aux détails
                            </a>
                        </div>

                        {{-- ── Form Card ────────────────────────────────────────── --}}
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">

                            <div class="tw-px-6 tw-py-4 tw-bg-amber-600 tw-flex tw-items-center tw-gap-3">
                                <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                                    <i class="fas fa-file-medical-alt tw-text-white tw-text-sm"></i>
                                </div>
                                <div>
                                    <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">Modifier les informations de la visite</h2>
                                    <p class="tw-text-white/70 tw-text-xs tw-mb-0">Les champs marqués <span class="tw-text-white tw-font-bold">*</span> sont obligatoires</p>
                                </div>
                            </div>

                            <div class="tw-p-6">
                                <form method="POST" action="{{ route('patient-visits.update', $visit) }}">
                                    @csrf
                                    @method('PATCH')

                                    {{-- ── Section 1: Patient (read-only) ──────── --}}
                                    <div class="tw-mb-8">
                                        <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center">
                                                <i class="fas fa-user tw-text-amber-600 tw-text-xs"></i>
                                            </div>
                                            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Information patient <span class="tw-text-xs tw-font-normal tw-text-slate-400">(non modifiable)</span></h3>
                                        </div>
                                        <div class="tw-flex tw-items-center tw-gap-3 tw-bg-sky-50 tw-border tw-border-sky-200 tw-rounded-xl tw-px-5 tw-py-4">
                                            <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-sky-200 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                                <span class="tw-text-sky-800 tw-font-bold tw-text-sm tw-uppercase">{{ mb_substr($visit->patient->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="tw-font-semibold tw-text-slate-800 tw-mb-0">{{ $visit->patient->name }} {{ $visit->patient->prenom }}</p>
                                                <p class="tw-text-xs tw-text-slate-500 tw-mb-0">CMCU-{{ $visit->patient->numero_dossier }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tw-border-t tw-border-slate-100 tw-mb-8"></div>

                                    {{-- ── Section 2: Date & Status ─────────────── --}}
                                    <div class="tw-mb-8">
                                        <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center">
                                                <i class="fas fa-calendar tw-text-amber-600 tw-text-xs"></i>
                                            </div>
                                            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Date et statut</h3>
                                        </div>
                                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                            <div>
                                                <label for="visit_date" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date de la visite <span class="tw-text-red-500">*</span></label>
                                                <input type="date" class="form-control" id="visit_date" name="visit_date"
                                                    value="{{ old('visit_date', $visit->visit_date->format('Y-m-d')) }}" required>
                                                @error('visit_date')
                                                    <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="status" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Statut <span class="tw-text-red-500">*</span></label>
                                                <select class="form-control" id="status" name="status" required>
                                                    <option value="en_attente" {{ old('status', $visit->status) == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                    <option value="en_cours"   {{ old('status', $visit->status) == 'en_cours'   ? 'selected' : '' }}>En cours</option>
                                                    <option value="terminee"   {{ old('status', $visit->status) == 'terminee'   ? 'selected' : '' }}>Terminée</option>
                                                    <option value="annulee"    {{ old('status', $visit->status) == 'annulee'    ? 'selected' : '' }}>Annulée</option>
                                                </select>
                                                @error('status')
                                                    <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tw-border-t tw-border-slate-100 tw-mb-8"></div>

                                    {{-- ── Section 3: Medical reason ────────────── --}}
                                    <div class="tw-mb-8">
                                        <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center">
                                                <i class="fas fa-notes-medical tw-text-teal-600 tw-text-xs"></i>
                                            </div>
                                            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Motif médical</h3>
                                        </div>
                                        <div class="tw-grid tw-grid-cols-1 tw-gap-4">
                                            <div>
                                                <label for="motif" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Motif de consultation</label>
                                                <input type="text" class="form-control" id="motif" name="motif"
                                                    value="{{ old('motif', $visit->motif) }}" placeholder="Ex: Douleur abdominale">
                                                @error('motif')
                                                    <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="details_motif" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Détails du motif</label>
                                                <textarea class="form-control" id="details_motif" name="details_motif" rows="3">{{ old('details_motif', $visit->details_motif) }}</textarea>
                                                @error('details_motif')
                                                    <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tw-border-t tw-border-slate-100 tw-mb-8"></div>

                                    {{-- ── Section 4: Finances ──────────────────── --}}
                                    <div class="tw-mb-8">
                                        <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center">
                                                <i class="fas fa-money-bill-wave tw-text-teal-600 tw-text-xs"></i>
                                            </div>
                                            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations financières</h3>
                                        </div>
                                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">
                                            <div>
                                                <label for="montant" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Montant (FCFA)</label>
                                                <input type="number" class="form-control" id="montant" name="montant"
                                                    value="{{ old('montant', $visit->montant) }}" min="0" step="1">
                                                @error('montant')
                                                    <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="avance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Avance (FCFA)</label>
                                                <input type="number" class="form-control" id="avance" name="avance"
                                                    value="{{ old('avance', $visit->avance) }}" min="0" step="1">
                                                @error('avance')
                                                    <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Reste (FCFA)</label>
                                                <input type="text" class="form-control tw-bg-slate-50" id="reste_display" readonly
                                                    value="{{ number_format($visit->reste) }} FCFA">
                                                <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Calculé automatiquement</p>
                                            </div>
                                            <div>
                                                <label for="mode_paiement" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Mode de paiement</label>
                                                <select class="form-control" id="mode_paiement" name="mode_paiement">
                                                    <option value="espèce"   {{ old('mode_paiement', $visit->mode_paiement) == 'espèce'   ? 'selected' : '' }}>Espèce</option>
                                                    <option value="carte"    {{ old('mode_paiement', $visit->mode_paiement) == 'carte'    ? 'selected' : '' }}>Carte bancaire</option>
                                                    <option value="mobile"   {{ old('mode_paiement', $visit->mode_paiement) == 'mobile'   ? 'selected' : '' }}>Mobile Money</option>
                                                    <option value="virement" {{ old('mode_paiement', $visit->mode_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                                                    <option value="cheque"   {{ old('mode_paiement', $visit->mode_paiement) == 'cheque'   ? 'selected' : '' }}>Chèque</option>
                                                </select>
                                            </div>
                                            <div class="sm:tw-col-span-2">
                                                <label for="mode_paiement_info_sup" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Informations supplémentaires</label>
                                                <input type="text" class="form-control" id="mode_paiement_info_sup" name="mode_paiement_info_sup"
                                                    value="{{ old('mode_paiement_info_sup', $visit->mode_paiement_info_sup) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tw-border-t tw-border-slate-100 tw-mb-8"></div>

                                    {{-- ── Section 5: Insurance ─────────────────── --}}
                                    <div class="tw-mb-8">
                                        <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-violet-100 tw-flex tw-items-center tw-justify-center">
                                                <i class="fas fa-shield-alt tw-text-violet-600 tw-text-xs"></i>
                                            </div>
                                            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Informations d'assurance</h3>
                                        </div>
                                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">
                                            <div>
                                                <label for="assurance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Assurance</label>
                                                <input type="text" class="form-control" id="assurance" name="assurance"
                                                    value="{{ old('assurance', $visit->assurance) }}">
                                            </div>
                                            <div>
                                                <label for="numero_assurance" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">N° d'assurance</label>
                                                <input type="text" class="form-control" id="numero_assurance" name="numero_assurance"
                                                    value="{{ old('numero_assurance', $visit->numero_assurance) }}">
                                            </div>
                                            <div>
                                                <label for="prise_en_charge" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Prise en charge</label>
                                                <input type="text" class="form-control" id="prise_en_charge" name="prise_en_charge"
                                                    value="{{ old('prise_en_charge', $visit->prise_en_charge) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tw-border-t tw-border-slate-100 tw-mb-8"></div>

                                    {{-- ── Section 6: Doctor & notes ────────────── --}}
                                    <div class="tw-mb-8">
                                        <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-4">
                                            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-primary-100 tw-flex tw-items-center tw-justify-center">
                                                <i class="fas fa-user-md tw-text-primary-700 tw-text-xs"></i>
                                            </div>
                                            <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Médecin et informations complémentaires</h3>
                                        </div>
                                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                            <div>
                                                <label for="medecin_r" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Médecin traitant</label>
                                                <select class="form-control" id="medecin_r" name="medecin_r">
                                                    <option value="">-- Sélectionner un médecin --</option>
                                                    @foreach($medecins as $medecin)
                                                        <option value="{{ $medecin->name }} {{ $medecin->prenom }}"
                                                            {{ old('medecin_r', $visit->medecin_r) == ($medecin->name . ' ' . $medecin->prenom) ? 'selected' : '' }}>
                                                            Dr. {{ $medecin->name }} {{ $medecin->prenom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="demarcheur" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Démarcheur</label>
                                                <input type="text" class="form-control" id="demarcheur" name="demarcheur"
                                                    value="{{ old('demarcheur', $visit->demarcheur) }}">
                                            </div>
                                            <div class="sm:tw-col-span-2">
                                                <label for="notes" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Notes</label>
                                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $visit->notes) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ── Action buttons ───────────────────────── --}}
                                    <div class="tw-flex tw-items-center tw-justify-between tw-pt-5 tw-border-t tw-border-slate-100">
                                        <a href="{{ route('patient-visits.show', $visit) }}"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-5 tw-py-2.5 tw-rounded-xl tw-border tw-border-slate-200 tw-transition-all tw-duration-150 tw-no-underline">
                                            <i class="fas fa-times tw-text-xs"></i>Annuler
                                        </a>
                                        <button type="submit"
                                                class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-amber-500 hover:tw-bg-amber-600 tw-text-white tw-text-sm tw-font-semibold tw-px-6 tw-py-2.5 tw-rounded-xl tw-shadow-md tw-transition-all tw-duration-150 tw-border-0 tw-cursor-pointer">
                                            <i class="fas fa-save tw-text-xs"></i>Enregistrer les modifications
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Metadata --}}
                        <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-px-6 tw-py-4 tw-mt-4">
                            <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2 tw-text-xs tw-text-slate-400">
                                <span><i class="fas fa-user tw-mr-1.5"></i>Créé par : <span class="tw-font-medium tw-text-slate-600">{{ $visit->user->name ?? 'N/A' }} {{ $visit->user->prenom ?? '' }}</span></span>
                                <span><i class="fas fa-clock tw-mr-1.5"></i>Le {{ $visit->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

    <script>
        function calculateReste() {
            const montant = parseInt(document.getElementById('montant').value) || 0;
            const avance  = parseInt(document.getElementById('avance').value)  || 0;
            document.getElementById('reste_display').value = (montant - avance).toLocaleString('fr-FR') + ' FCFA';
        }
        document.getElementById('montant').addEventListener('input', calculateReste);
        document.getElementById('avance').addEventListener('input', calculateReste);
        calculateReste();
    </script>
@endsection