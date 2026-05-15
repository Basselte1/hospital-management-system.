@extends('layouts.admin')
@section('title', 'CMCU | Observations médicales')
@section('content')

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            @can('show', \App\Models\User::class)

            {{-- ── Page Heading ─────────────────────────────────── --}}
            <div class="tw-mb-6 tw-rounded-2xl tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-shadow-sm">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-notes-medical tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Observations médicales</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">{{ $patient->name }} {{ $patient->prenom }}</p>
                    </div>
                </div>
                <a href="{{ route('patients.show', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour au dossier patient</span>
                </a>
            </div>

            {{-- ── Tabs Navigation ──────────────────────────────────── --}}
            <div class="tw-mb-4">
                <ul class="tw-flex tw-gap-2 tw-list-none tw-p-0 tw-m-0" id="medicalTabs" role="tablist">
                    @can('chirurgien', \App\Models\Patient::class)
                    <li class="tw-list-none" role="presentation">
                        <button class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer active-tab"
                                data-bs-toggle="tab" 
                                data-bs-target="#home" 
                                type="button" 
                                role="tab" 
                                aria-controls="home" 
                                aria-selected="true"
                                id="home-tab">
                            <i class="fas fa-stethoscope tw-text-xs"></i> Observations médicales
                        </button>
                    </li>
                    <li class="tw-list-none" role="presentation">
                        <button class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline hover:tw-bg-slate-50 tw-transition-colors tw-border-0 tw-cursor-pointer"
                                data-bs-toggle="tab" 
                                data-bs-target="#menu1" 
                                type="button" 
                                role="tab" 
                                aria-controls="menu1" 
                                aria-selected="false"
                                id="menu1-tab">
                            <i class="fas fa-heartbeat tw-text-xs"></i> Soins infirmiers
                        </button>
                    </li>
                    @endcan
                    @can('infirmier', \App\Models\Patient::class)
                    <li class="tw-list-none" role="presentation">
                        <button class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#14B8A6] tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline hover:tw-bg-teal-600 tw-transition-colors tw-border-0 tw-cursor-pointer active-tab"
                                data-bs-toggle="tab" 
                                data-bs-target="#menu1" 
                                type="button" 
                                role="tab" 
                                aria-controls="menu1" 
                                aria-selected="true"
                                id="menu1-tab">
                            <i class="fas fa-heartbeat tw-text-xs"></i> Soins infirmiers
                        </button>
                    </li>
                    @endcan
                </ul>
            </div>

            {{-- ── Tab Content ──────────────────────────────────────── --}}
            <div class="tab-content" id="medicalTabsContent">

                {{-- ── OBSERVATIONS MÉDICALES tab ───────────────── --}}
                <div class="tab-pane fade show active" 
                     id="home" 
                     role="tabpanel" 
                     aria-labelledby="home-tab">
                    @can('chirurgien', \App\Models\Patient::class)

                    {{-- Add observation form --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-4">
                        <div class="tw-px-5 tw-py-3 tw-border-b tw-border-slate-100 tw-bg-slate-50 tw-flex tw-items-center tw-gap-2">
                            <div class="tw-w-6 tw-h-6 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                <i class="fas fa-plus tw-text-[#1D4ED8] tw-text-[10px]"></i>
                            </div>
                            <h3 class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-0">Nouvelle observation</h3>
                        </div>
                        <div class="tw-p-4">
                            <form action="{{ route('observations_medicales.store') }}" method="post">
                                @csrf
                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-3">
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date <span class="tw-text-red-500">*</span></label>
                                        <input name="date" type="date" required
                                               value="{{ old('date', \Carbon\Carbon::now()->toDateString()) }}"
                                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                    </div>
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Médecin / Chirurgien <span class="tw-text-red-500">*</span></label>
                                        <select name="user_id" required
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                            <option value="">Médecin / Chirurgien</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} {{ $user->prenom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Anesthésiste <span class="tw-text-red-500">*</span></label>
                                        <select name="anesthesiste" required
                                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                            <option value="">Anesthésiste</option>
                                            @foreach($anesthesistes as $anesthesiste)
                                                <option value="{{ $anesthesiste->name }} {{ $anesthesiste->prenom }}">{{ $anesthesiste->name }} {{ $anesthesiste->prenom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="sm:tw-col-span-3">
                                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Observation <span class="tw-text-red-500">*</span></label>
                                        <textarea name="observation" rows="3" required
                                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-none"></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                <div class="tw-flex tw-justify-end tw-mt-3">
                                    <button type="submit"
                                            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors">
                                        <i class="fas fa-save tw-text-xs"></i> Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @endcan

                    {{-- Observations list --}}
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-overflow-x-auto">
                            <table class="tw-w-full tw-text-sm">
                                <thead>
                                    <tr class="tw-bg-[#1D4ED8]">
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider tw-w-28">Date</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Médecin</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Observations</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider tw-w-24">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tw-divide-y tw-divide-slate-100">
                                    @foreach($observation_medicales as $observation_medicale)
                                    <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                        <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-500 tw-whitespace-nowrap">{{ $observation_medicale->date }}</td>
                                        <td class="tw-px-4 tw-py-3">
                                            <p class="tw-text-xs tw-mb-0.5"><span class="tw-font-semibold tw-text-slate-600">Chirurgien :</span> {{ $observation_medicale->user->name }} {{ $observation_medicale->user->prenom }}</p>
                                            <p class="tw-text-xs tw-mb-0 tw-text-slate-500"><span class="tw-font-semibold">Anesthésiste :</span> {{ $observation_medicale->anesthesiste }}</p>
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-sm tw-text-slate-700">{{ $observation_medicale->observation }}</td>
                                        <td class="tw-px-4 tw-py-3">
                                            <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">
                                                <button type="button"
                                                        data-bs-toggle="modal" data-bs-target="#EditObservationModal{{ $observation_medicale->id }}"
                                                        class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-600 tw-border-0 tw-transition-colors tw-cursor-pointer">
                                                    <i class="fas fa-edit tw-text-xs"></i>
                                                </button>
                                                <form action="{{ route('observations_medicales.destroy', $observation_medicale->id) }}"
                                                      method="POST" class="tw-inline"
                                                      onsubmit="return confirm('Supprimer cette observation ?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-border-0 tw-transition-colors">
                                                        <i class="fas fa-trash tw-text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @include('admin.consultations.observation_medicale_edit', ['observation_medicale' => $observation_medicale])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ── SOINS INFIRMIERS tab ──────────────────────── --}}
                <div class="tab-pane fade" 
                     id="menu1" 
                     role="tabpanel" 
                     aria-labelledby="menu1-tab">
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                        <div class="tw-overflow-x-auto">
                            <table class="tw-w-full tw-text-sm">
                                <thead>
                                    <tr class="tw-bg-[#14B8A6]">
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider tw-w-28">Date</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">Infirmier</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wider">
                                            Observations
                                            @can('infirmier', \App\Models\Patient::class)
                                            <button type="button"
                                                    class="tw-float-right tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-text-xs tw-font-semibold tw-px-3 tw-py-1 tw-border-0 tw-transition-colors tw-cursor-pointer"
                                                    title="Administrer des soins" data-bs-toggle="modal" data-bs-target="#SoinsInfirmier">
                                                <i class="fas fa-heartbeat"></i> Administrer des soins
                                            </button>
                                            @endcan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="tw-divide-y tw-divide-slate-100">
                                    @foreach($soins_infirmiers as $soins_infirmier)
                                    <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                        <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-500 tw-whitespace-nowrap">{{ $soins_infirmier->date }}</td>
                                        <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-600">
                                            <span class="tw-font-semibold">Infirmier :</span> {{ $soins_infirmier->user->name }} {{ $soins_infirmier->user->prenom }}
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-sm tw-text-slate-700">{{ $soins_infirmier->observation }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            @include('admin.modal.soins_infirmier')
            @endcan

        </main>
    </div>
</div>

{{-- ── Tab JavaScript ──────────────────────────────────────────── --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tabs
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all buttons
                document.querySelectorAll('[data-bs-toggle="tab"]').forEach(btn => {
                    btn.classList.remove('tw-bg-[#1D4ED8]', 'tw-bg-[#14B8A6]', 'tw-text-white');
                    btn.classList.add('tw-bg-white', 'tw-text-slate-600', 'tw-border', 'tw-border-slate-200');
                    btn.setAttribute('aria-selected', 'false');
                });
                
                // Add active class to clicked button
                this.classList.remove('tw-bg-white', 'tw-text-slate-600', 'tw-border', 'tw-border-slate-200');
                
                // Determine color based on tab
                const target = this.getAttribute('data-bs-target');
                if(target === '#home') {
                    this.classList.add('tw-bg-[#1D4ED8]', 'tw-text-white');
                } else if(target === '#menu1') {
                    this.classList.add('tw-bg-[#14B8A6]', 'tw-text-white');
                }
                
                this.setAttribute('aria-selected', 'true');
                
                // Hide all tab panes
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                
                // Show target tab pane
                const targetPane = document.querySelector(target);
                if(targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });
    });
</script>
@endpush

@stop