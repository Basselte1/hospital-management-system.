{{-- ══════════════════════════════════════════════════════════════
     prescription_medicale_form.blade.php  (Tailwind restyled)
     Bootstrap modal-lg — edit fiche "Informations Importantes"
     Populated on show.bs.modal via JS in index_prescription_medicale
     ══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="prescription_medicale_form" tabindex="-1" role="dialog"
     aria-labelledby="prescription_medicale_formLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-overflow-hidden tw-border-0 tw-shadow-xl">

            {{-- Header --}}
            <div class="modal-header tw-bg-amber-500 tw-border-0 tw-px-5 tw-py-4">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-exclamation-triangle tw-text-white tw-text-xs"></i>
                    </div>
                    <h5 class="modal-title tw-text-sm tw-font-semibold tw-text-white tw-mb-0" id="prescription_medicale_formLabel">
                        Informations importantes
                    </h5>
                </div>
                <button type="button" data-bs-dismiss="modal" aria-label="Close"
                        class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-flex tw-items-center tw-justify-center tw-border-0 tw-transition-colors">
                    <i class="fas fa-times tw-text-xs"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body tw-p-5">
                <form id="prescription_form"
                      action="{{ route('fiche.prescription_medicale.store', $patient->id) }}"
                      method="post"
                      class="tw-space-y-4">
                    @csrf
                    <input type="hidden" id="form_method" name="_method" value="">
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    {{-- Patient name --}}
                    <p class="tw-text-sm tw-font-bold tw-text-red-500 tw-mb-0">
                        {{ $patient->name }} {{ $patient->prenom }}
                    </p>

                    {{-- Allergie --}}
                    <div>
                        <label for="allergie" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                            Allergie
                        </label>
                        <input type="text" id="allergie" name="allergie"
                               value="{{ $fiche_prescription_medicale->allergie ?? '' }}"
                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-amber-200 focus:tw-border-amber-400 tw-transition-all">
                    </div>

                    {{-- Régime & Consultations --}}
                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                        <div>
                            <label for="regime" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                Régime <span class="tw-text-red-500">*</span>
                            </label>
                            <textarea id="regime" name="regime" rows="3" required
                                      class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-amber-200 focus:tw-border-amber-400 tw-transition-all tw-resize-y">{{ $fiche_prescription_medicale->regime ?? '' }}</textarea>
                        </div>
                        <div>
                            <label for="consultation_specialise" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                Consultations spécialisées <span class="tw-text-red-500">*</span>
                            </label>
                            <textarea id="consultation_specialise" name="consultation_specialise" rows="3" required
                                      class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-amber-200 focus:tw-border-amber-400 tw-transition-all tw-resize-y">{{ $fiche_prescription_medicale->consultation_specialise ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- Autre protocole --}}
                    <div>
                        <label for="protocole" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                            Autre protocole
                        </label>
                        <textarea id="protocole" name="protocole" rows="3"
                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-amber-200 focus:tw-border-amber-400 tw-transition-all tw-resize-y">{{ $fiche_prescription_medicale->protocole ?? '' }}</textarea>
                    </div>

                    {{-- Submit --}}
                    <div class="tw-pt-3 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-500 hover:tw-bg-amber-600 tw-text-white tw-font-semibold tw-text-sm tw-px-6 tw-py-2.5 tw-border-0 tw-transition-colors tw-shadow-sm">
                            <i class="fas fa-save tw-text-xs"></i> Enregistrer
                        </button>
                        <button type="button" data-bs-dismiss="modal"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-transition-colors">
                            <i class="fas fa-times tw-text-xs"></i> Annuler
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>