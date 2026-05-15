@extends('layouts.admin')
@section('title', 'CMCU | Prescriptions médicales')

@section('content')

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('show', \App\Models\User::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- ── Page Heading ─────────────────────────────────── --}}
            <div class="tw-mb-6 tw-rounded-2xl tw-bg-[#1D4ED8] tw-px-6 tw-py-5 tw-flex tw-items-center tw-justify-between tw-shadow-sm">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-pills tw-text-white"></i>
                    </div>
                    <div>
                        <h1 class="tw-text-xl tw-font-bold tw-text-white tw-mb-0">Prescriptions médicales</h1>
                        <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0">{{ $patient->name }} {{ $patient->prenom }}</p>
                    </div>
                </div>
                <a href="{{ route('patients.show', $patient->id) }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors tw-border tw-border-white/30 tw-shrink-0">
                    <i class="fas fa-arrow-left tw-text-xs"></i>
                    <span class="tw-hidden sm:tw-inline">Retour au dossier</span>
                </a>
            </div>

            {{-- ── Validation errors ────────────────────────────── --}}
            @if ($errors->any())
            <div class="tw-mb-4 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-100 tw-px-5 tw-py-4">
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2">
                    <i class="fas fa-exclamation-triangle tw-text-red-500 tw-text-sm"></i>
                    <span class="tw-text-sm tw-font-semibold tw-text-red-700">Erreurs de validation</span>
                </div>
                <ul class="tw-list-disc tw-list-inside tw-space-y-0.5">
                    @foreach ($errors->all() as $error)
                    <li class="tw-text-sm tw-text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- ── Prescriptions table ──────────────────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-5">
                <div class="tw-px-5 tw-py-3 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-list tw-text-white/70 tw-text-xs"></i>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-white tw-mb-0 tw-uppercase tw-tracking-wide">Liste des prescriptions</h3>
                    </div>
                    @can('medecin', \App\Models\Patient::class)
                    <button type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#PrescriptionMedicale"
                            class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-font-semibold tw-text-xs tw-px-3 tw-py-1.5 tw-border tw-border-white/30 tw-transition-colors">
                        <i class="fas fa-plus tw-text-[10px]"></i> Nouveau
                    </button>
                    @endcan
                </div>

                <div class="tw-overflow-x-auto">
                    <table id="myTable" class="tw-w-full tw-text-sm tw-border-collapse dt-responsive display nowrap td-responsive" cellspacing="0">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                @foreach(['DATE', 'MÉDICAMENT', 'POSOLOGIE', 'HORAIRE', 'VOIE', 'ACTIONS'] as $th)
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">{{ $th }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-50">
                            @forelse ($prescription_medicales as $prescription_medicale)
                            <tr class="hover:tw-bg-slate-50/60 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600 tw-whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($prescription_medicale->date)->format('d/m/Y') }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-font-medium tw-text-slate-700">
                                    {{ $prescription_medicale->medicament }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600">
                                    {{ $prescription_medicale->posologie }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600 tw-text-xs">
                                    {{ $prescription_medicale->formatted_time_slots }}
                                </td>
                                <td class="tw-px-4 tw-py-3">
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-xs tw-font-semibold tw-px-2.5 tw-py-1">
                                        {{ $prescription_medicale->voie }}
                                    </span>
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-whitespace-nowrap">
                                    <div class="tw-flex tw-items-center tw-gap-1.5">
                                        {{-- Details button --}}
                                        <button title="Afficher les soins administrés"
                                                class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-lg tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-xs tw-font-medium tw-px-2.5 tw-py-1.5 tw-border-0 tw-transition-colors btn_admin_prescription_medicale"
                                                data-bs-toggle="modal"
                                                data-bs-admin_list="{{ json_encode($prescription_medicale->adminPrescriptionMedicales) }}"
                                                data-bs-target="#admin_prescription_medicale">
                                            <i class="fas fa-eye tw-text-[10px]"></i> Détails
                                        </button>

                                        @can('infirmier', \App\Models\Patient::class)
                                        <button title="Saisir un nouveau soin"
                                                class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#1D4ED8] tw-text-[#1D4ED8] hover:tw-text-white tw-flex tw-items-center tw-justify-center tw-border-0 tw-transition-colors btn_admin_prescription_medicale_form"
                                                data-bs-toggle="modal"
                                                data-bs-prescription_medicale_id="{{ $prescription_medicale->id }}"
                                                data-bs-target="#admin_prescription_medicale_form">
                                            <i class="fas fa-plus tw-text-[10px]"></i>
                                        </button>
                                        @endcan

                                        @can('medecin', \App\Models\Patient::class)
                                        <a href="{{ route('prescription_medicale.edit', $prescription_medicale->id) }}?patient={{ $patient->id }}"
                                           title="Modifier la prescription"
                                           class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 hover:tw-bg-amber-500 tw-text-amber-600 hover:tw-text-white tw-flex tw-items-center tw-justify-center tw-no-underline tw-transition-colors">
                                            <i class="fas fa-edit tw-text-[10px]"></i>
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="tw-px-4 tw-py-8 tw-text-center tw-text-slate-400 tw-text-sm">
                                    <i class="fas fa-inbox tw-text-2xl tw-block tw-mb-2 tw-text-slate-300"></i>
                                    Aucune prescription enregistrée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($prescription_medicales instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="tw-px-5 tw-py-3 tw-border-t tw-border-slate-100 tw-flex tw-justify-center">
                    {{ $prescription_medicales->links() }}
                </div>
                @endif

                
            </div>

            {{-- ── Informations importantes banner ─────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-amber-100 tw-overflow-hidden">
                <div class="tw-px-5 tw-py-3 tw-bg-amber-50 tw-border-b tw-border-amber-100 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-exclamation-triangle tw-text-amber-600 tw-text-xs"></i>
                        </div>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-amber-700 tw-mb-0 tw-uppercase tw-tracking-wide">Informations importantes</h3>
                    </div>
                    @can('medecin', \App\Models\Patient::class)
                    <button title="Modifier"
                            data-bs-toggle="modal"
                            data-bs-target="#prescription_medicale_form"
                            class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-amber-100 hover:tw-bg-amber-200 tw-text-amber-600 tw-flex tw-items-center tw-justify-center tw-border-0 tw-transition-colors">
                        <i class="fas fa-edit tw-text-xs"></i>
                    </button>
                    @endcan
                </div>

                <div class="tw-px-5 tw-py-4">
                    {{-- Patient name + allergy badge --}}
                    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-3 tw-mb-4">
                        <span class="tw-text-base tw-font-bold tw-text-slate-700">
                            {{ $patient->name }} {{ $patient->prenom }}
                        </span>
                        <span class="tw-inline-flex tw-items-center tw-rounded-full tw-px-3 tw-py-1 tw-text-xs tw-font-semibold tw-text-white
                            {{ $fiche_prescription_medicale->allergie ? 'tw-bg-red-500' : 'tw-bg-[#1D4ED8]' }}">
                            <i class="fas fa-allergies tw-mr-1.5 tw-text-[10px]"></i>
                            {{ $fiche_prescription_medicale->allergie ?: 'Aucune allergie déclarée' }}
                        </span>
                    </div>

                    {{-- Info cards row --}}
                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-3">
                        @foreach([
                            ['icon' => 'fas fa-utensils',          'color' => 'tw-bg-teal-100 tw-text-teal-700',    'title' => 'Régime',                            'value' => $fiche_prescription_medicale->regime,                    'empty' => 'Aucun régime spécifique'],
                            ['icon' => 'fas fa-user-md',            'color' => 'tw-bg-indigo-100 tw-text-indigo-600', 'title' => 'Consultations spécialisées',        'value' => $fiche_prescription_medicale->consultation_specialise,   'empty' => 'Aucune consultation requise'],
                            ['icon' => 'fas fa-shield-alt',         'color' => 'tw-bg-violet-100 tw-text-violet-600', 'title' => 'Protocoles / Surveillance',         'value' => $fiche_prescription_medicale->protocole,                 'empty' => 'Aucun protocole spécifique'],
                        ] as $card)
                        <div class="tw-rounded-xl tw-border tw-border-slate-100 tw-p-4">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2">
                                <div class="tw-w-6 tw-h-6 tw-rounded-lg {{ $card['color'] }} tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                    <i class="{{ $card['icon'] }} tw-text-[10px]"></i>
                                </div>
                                <span class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide">{{ $card['title'] }}</span>
                            </div>
                            <p class="tw-text-sm tw-text-slate-700 tw-mb-0 tw-leading-relaxed">
                                {{ $card['value'] ?: $card['empty'] }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </main>
        @endcan
    </div>
</div>

{{-- ── Modals ────────────────────────────────────────────────────── --}}
@include('admin.consultations.infirmiers.form.elt_prescription_medicale_form')
@include('admin.consultations.infirmiers.form.prescription_medicale_form')
@include('admin.consultations.infirmiers.admin_prescription_medicale')
@include('admin.consultations.infirmiers.form.admin_prescription_medicale_form')

@endsection

@section('script')
<script>
    function waitForjQuery(callback) {
        if (typeof jQuery !== 'undefined') { callback(); }
        else { setTimeout(function() { waitForjQuery(callback); }, 100); }
    }

    waitForjQuery(function() {
        $(document).ready(function() {

            const infirmieres = @json($infirmieres);

            $(document).on("click", ".btn_admin_prescription_medicale_form", function(e) {
                const prescription_medicale_id = $(this).data('bs-prescription_medicale_id');
                if (!prescription_medicale_id) { console.error('ID prescription non trouvé !'); return; }
                const url = '/admin/prescriptions-medicales/' + prescription_medicale_id + '/Admin-PM';
                $("#apm_form").attr('action', url);
            });

            $(document).on("click", ".btn_admin_prescription_medicale", function() {
                let table_body = $('<tbody></tbody>');
                let data = $(this).data('bs-admin_list');
                $('#admin_prescription_medicale_table tbody').empty();
                if (!data || data.length === 0) {
                    table_body.append('<tr><td colspan="6" class="tw-text-center tw-py-4 tw-text-slate-400">Aucune administration enregistrée</td></tr>');
                    $('#admin_prescription_medicale_table tbody').html(table_body.html());
                    return;
                }
                $.each(data, function(index, value) {
                    let dmatin     = value.matin     == null ? '' : value.matin;
                    let dapre_midi = value.apre_midi == null ? '' : value.apre_midi;
                    let dsoir      = value.soir      == null ? '' : value.soir;
                    let dnuit      = value.nuit      == null ? '' : value.nuit;
                    let dinfirmiere = infirmieres.find(el => el.id == value.user_id);
                    let ddate = value.created_at ? value.created_at.substring(0, 10) : '';
                    table_body.append(
                        '<tr>' +
                            '<td>' + ddate + '</td>' +
                            '<td>' + (dinfirmiere ? dinfirmiere.name : 'N/A') + '</td>' +
                            '<td>' + dmatin + '</td>' +
                            '<td>' + dapre_midi + '</td>' +
                            '<td>' + dsoir + '</td>' +
                            '<td>' + dnuit + '</td>' +
                        '</tr>'
                    );
                });
                $('#admin_prescription_medicale_table tbody').html(table_body.html());
            });

            $("#prescription_medicale_form").on('show.bs.modal', function () {
                $('#allergie').val('{{ $fiche_prescription_medicale->allergie ?? '' }}');
                $('#regime').val('{{ $fiche_prescription_medicale->regime ?? '' }}');
                $('#consultation_specialise').val('{{ $fiche_prescription_medicale->consultation_specialise ?? '' }}');
                $('#protocole').val('{{ $fiche_prescription_medicale->protocole ?? '' }}');
            });
        });
    });
</script>
@endsection