@can('infirmier_secretaire', \App\Models\Patient::class)
<div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-4">

    {{-- Card header --}}
    <div class="tw-px-6 tw-py-4 tw-bg-amber-500 tw-flex tw-items-center tw-gap-3">
        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
            <i class="fas fa-user-edit tw-text-white tw-text-sm"></i>
        </div>
        <h3 class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0">
            Modifier les informations — {{ $patient->name }} {{ $patient->prenom }}
        </h3>
    </div>

    <form action="{{ route('patients.update', $patient->id) }}" method="POST" class="tw-p-6">
        @csrf @method('PATCH')

        <div class="tw-space-y-4">

            {{-- Nom du patient --}}
            <div class="tw-flex tw-flex-col sm:tw-flex-row sm:tw-items-center tw-gap-2">
                <label class="tw-w-48 tw-shrink-0 tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide">
                    Nom du patient
                </label>
                <input name="name" type="text" value="{{ $patient->name }}"
                       class="tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
            </div>

            {{-- Assurance --}}
            <div class="tw-flex tw-flex-col sm:tw-flex-row sm:tw-items-center tw-gap-2">
                <label class="tw-w-48 tw-shrink-0 tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide">
                    Assurance
                </label>
                <input name="assurance" type="text" value="{{ $patient->assurance }}"
                       class="tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
            </div>

            {{-- Numéro d'assurance --}}
            <div class="tw-flex tw-flex-col sm:tw-flex-row sm:tw-items-center tw-gap-2">
                <label class="tw-w-48 tw-shrink-0 tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide">
                    N° d'assurance
                </label>
                <input name="numero_assurance" type="text" value="{{ $patient->numero_assurance }}"
                       class="tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
            </div>

        </div>

        {{-- Actions --}}
        <div class="tw-flex tw-items-center tw-justify-between tw-pt-5 tw-mt-5 tw-border-t tw-border-slate-100">
            <button type="submit"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-500 hover:tw-bg-amber-600 tw-text-white tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-text-sm tw-transition-colors">
                <i class="fas fa-save tw-text-xs"></i> Enregistrer les modifications
            </button>
            <a href="{{ route('dossiers.create', $patient) }}"
               class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-font-medium tw-px-5 tw-py-2.5 tw-text-sm tw-transition-colors tw-no-underline">
                <i class="fas fa-folder-plus tw-text-xs"></i> Compléter le dossier
            </a>
        </div>
    </form>
</div>
@endcan