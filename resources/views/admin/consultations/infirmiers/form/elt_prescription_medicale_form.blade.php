
<div class="modal fade" id="PrescriptionMedicale" tabindex="-1" aria-labelledby="PrescriptionMedicaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content tw-rounded-2xl tw-overflow-hidden tw-border-0 tw-shadow-xl">

            {{-- Header --}}
            <div class="modal-header tw-bg-[#1D4ED8] tw-border-0 tw-px-5 tw-py-4">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-pills tw-text-white tw-text-xs"></i>
                    </div>
                    <h5 class="modal-title tw-text-sm tw-font-semibold tw-text-white tw-mb-0" id="PrescriptionMedicaleLabel">
                        Nouvelle prescription
                    </h5>
                </div>
                <button type="button" class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-flex tw-items-center tw-justify-center tw-border-0 tw-transition-colors" data-bs-dismiss="modal" aria-label="Fermer">
                    <i class="fas fa-times tw-text-xs"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body tw-p-5">
                <form action="{{ route('prescription_medicale.store') }}" method="POST" class="tw-space-y-4">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    {{-- Patient name --}}
                    <p class="tw-text-sm tw-font-bold tw-text-red-500 tw-mb-0">
                        {{ $patient->name }} {{ $patient->prenom }}
                    </p>

                    {{-- Médicament & Posologie --}}
                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                        <div>
                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                Médicament & Forme <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="text" name="medicament"
                                   placeholder="Ex: Paracétamol 500mg comprimé" required
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                Posologie <span class="tw-text-red-500">*</span>
                            </label>
                            <input type="text" name="posologie"
                                   placeholder="Ex: 1 cp toutes les 8h" required
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                        </div>
                    </div>

                    {{-- Horaires & Voie --}}
                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">

                        {{-- Horaires --}}
                        <div class="sm:tw-col-span-2">
                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                Horaire d'administration <span class="tw-text-red-500">*</span>
                            </label>
                            <p class="tw-text-[11px] tw-text-slate-400 tw-mb-2">Sélectionnez au moins un horaire</p>
                            <div class="tw-grid tw-grid-cols-4 sm:tw-grid-cols-6 tw-gap-2">
                                @foreach(['00H','02H','04H','06H','08H','10H','12H','14H','16H','18H','20H','22H'] as $horaire)
                                <label for="h_{{ $horaire }}"
                                       class="tw-flex tw-flex-col tw-items-center tw-gap-1 tw-cursor-pointer tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-1 tw-py-2 tw-text-xs tw-font-medium tw-text-slate-600 hover:tw-border-[#1D4ED8] hover:tw-bg-[#BFDBFE]/30 tw-transition-colors">
                                    <input type="checkbox" id="h_{{ $horaire }}" name="horaire[]" value="{{ $horaire }}"
                                           class="tw-accent-[#1D4ED8] tw-w-3.5 tw-h-3.5">
                                    {{ $horaire }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Voie --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">
                                Voie <span class="tw-text-red-500">*</span>
                            </label>
                            <select id="voieSelect" name="voie" required
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                                <option value="">-- Sélectionner --</option>
                                <option value="PO">PO (Per Os - Orale)</option>
                                <option value="IV">IV (Intraveineuse)</option>
                                <option value="IM">IM (Intramusculaire)</option>
                                <option value="SC">SC (Sous-cutanée)</option>
                                <option value="Rectale">Rectale</option>
                                <option value="Cutanée">Cutanée</option>
                                <option value="Inhalation">Inhalation</option>
                                <option value="Autre">Autre</option>
                            </select>
                            <input type="text" id="voieAutreInput" name="voie_autre"
                                   placeholder="Précisez la voie"
                                   style="display:none;"
                                   class="tw-w-full tw-mt-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] tw-transition-all">
                        </div>
                    </div>

                    {{-- Footer actions inside form --}}
                    <div class="tw-pt-3 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-gap-3">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-6 tw-py-2.5 tw-border-0 tw-transition-colors tw-shadow-sm">
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const voieSelect     = document.getElementById("voieSelect");
    const voieAutreInput = document.getElementById("voieAutreInput");

    function toggleAutreInput() {
        if (voieSelect && voieAutreInput) {
            if (voieSelect.value === "Autre") {
                voieAutreInput.style.display = "block";
                voieAutreInput.required = true;
            } else {
                voieAutreInput.style.display = "none";
                voieAutreInput.required = false;
                voieAutreInput.value = "";
            }
        }
    }

    if (voieSelect) {
        voieSelect.addEventListener("change", toggleAutreInput);
        toggleAutreInput();
    }
});
</script>