<div class="modal fade" id="VisiteAnesthesiste" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">

            {{-- Header --}}
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-stethoscope tw-mr-2"></i>Visite Pré-Anesthésique
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="tw-p-6">
                <form action="{{ route('visite_preanesthesique.store') }}" method="post" class="tw-space-y-4">
                    @csrf
                    <input type="hidden" value="{{ $patient->id }}" name="patient_id">

                    {{-- Patient info --}}
                    <div class="tw-rounded-xl tw-bg-[#BFDBFE]/20 tw-border tw-border-[#BFDBFE] tw-p-4">
                        <p class="tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-widest tw-mb-3 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-user"></i> Patient
                        </p>
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Nom du patient</label>
                                <input type="text" value="{{ $patient->name }}" name="nom_patient" disabled
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500">
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Prénom du patient</label>
                                <input type="text" value="{{ $patient->prenom }}" name="prenom_patient" disabled
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500">
                            </div>

                            @foreach ($patient->dossiers as $dossier)
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Sexe</label>
                                <input type="text" value="{{ $dossier->sexe }}" name="sexe_patient" disabled
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-500">
                            </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- Date --}}
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                            Date <span class="tw-text-red-500">*</span>
                        </label>
                        <input type="date" value="{{ Carbon\Carbon::now()->ToDateString() }}"
                               name="date_visite" required
                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                    </div>

                    {{-- VPA / Éléments nouveaux --}}
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                            VPA / Éléments nouveaux (MAR) <span class="tw-text-red-500">*</span>
                        </label>
                        <textarea name="element_nouveaux" id="vpa" rows="8" required
                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none">{{ old('element_nouveaux') }}</textarea>
                    </div>

                    {{-- Actions --}}
                    <div class="tw-flex tw-justify-end tw-pt-2 tw-border-t tw-border-slate-100">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-border-0 tw-cursor-pointer tw-transition-colors">
                            <i class="fas fa-save tw-text-xs"></i> Ajouter au dossier
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>