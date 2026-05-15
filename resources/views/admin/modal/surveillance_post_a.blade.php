<div class="modal fade" id="SpostAnesth" tabindex="-1" role="dialog" aria-labelledby="SpostAnesth" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-heartbeat tw-mr-2"></i>Surveillance Post Anesthésique — {{ $patient->name }} {{ $patient->prenom }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6">
                <form action="{{ route('surveillance_post_anesthesise.store') }}" method="post" class="tw-space-y-4">
                    @csrf
                    <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                    <input type="hidden" value="{{ \Carbon\Carbon::now()->toDateString() }}" name="date_creation">

                    @foreach([
                        ['label' => 'Surveillance', 'name' => 'surveillance', 'required' => true],
                        ['label' => 'Traitement(s)', 'name' => 'traitement', 'required' => true],
                        ['label' => 'Examen(s) paraclinique(s) post opératoire', 'name' => 'examen_paraclinique', 'required' => true],
                        ['label' => 'Observation(s)', 'name' => 'observation', 'required' => true],
                    ] as $field)
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                            {{ $field['label'] }} @if($field['required'])<span class="tw-text-red-500">*</span>@endif
                        </label>
                        <textarea name="{{ $field['name'] }}" rows="3" @if($field['required']) required @endif
                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none"></textarea>
                    </div>
                    @endforeach

                    <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date de sortie <span class="tw-text-red-500">*</span></label>
                            <input type="date" name="date_sortie" required
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Heure de sortie <span class="tw-text-red-500">*</span></label>
                            <input type="time" name="heur_sortie" required
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>
                    </div>

                    <div class="tw-flex tw-justify-end tw-pt-2">
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