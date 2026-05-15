<div class="modal fade" id="EditObservationModal{{ $observation_medicale->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-overflow-hidden tw-border-0 tw-shadow-lg">

            {{-- Modal header --}}
            <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <i class="fas fa-edit tw-text-white tw-text-sm"></i>
                    <h5 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">Modifier Observation Médicale</h5>
                </div>
                <button type="button" class="tw-bg-transparent tw-border-0 tw-text-white/70 hover:tw-text-white tw-text-xl tw-leading-none tw-cursor-pointer tw-transition-colors"
                        data-bs-dismiss="modal" aria-label="Fermer">&times;</button>
            </div>

            {{-- Modal body --}}
            <div class="tw-p-6 tw-bg-white">
                <form action="{{ route('observations_medicales.update', $observation_medicale->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="tw-grid tw-grid-cols-1 tw-gap-4">
                        <div>
                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date <span class="tw-text-red-500">*</span></label>
                            <input type="date" name="date" required
                                   value="{{ $observation_medicale->date }}"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                        </div>

                        <div>
                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Anesthésiste <span class="tw-text-red-500">*</span></label>
                            <input type="text" name="anesthesiste" required
                                   value="{{ $observation_medicale->anesthesiste }}"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                        </div>

                        <div>
                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Observation <span class="tw-text-red-500">*</span></label>
                            <textarea name="observation" rows="4" required
                                      class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all tw-resize-none">{{ $observation_medicale->observation }}</textarea>
                        </div>
                    </div>

                    <div class="tw-flex tw-justify-end tw-mt-5 tw-pt-4 tw-border-t tw-border-slate-100">
                        <button type="button" data-bs-dismiss="modal"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-mr-2 tw-transition-colors tw-cursor-pointer">
                            Annuler
                        </button>
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors tw-cursor-pointer">
                            <i class="fas fa-save tw-text-xs"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>