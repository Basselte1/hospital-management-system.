@foreach($surveillance_post_anesthesiques as $spa)
<div class="modal fade" id="SpostAnesthUpdate{{ $spa->id }}" tabindex="-1" role="dialog"
     aria-labelledby="SpostAnesthUpdate{{ $spa->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-amber-500">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-edit tw-mr-2"></i>Modifier Surveillance Post Anesthésique
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6">
                <form action="{{ route('surveillance_post_anesthesise.update', $spa->id) }}" method="post" class="tw-space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" value="{{ \Carbon\Carbon::now()->toDateString() }}" name="date_creation">

                    @foreach([
                        ['label' => 'Surveillance', 'name' => 'surveillance', 'value' => $spa->surveillance, 'required' => true],
                        ['label' => 'Traitement(s)', 'name' => 'traitement', 'value' => $spa->traitement, 'required' => false],
                        ['label' => 'Examen(s) paraclinique(s) post opératoire', 'name' => 'examen_paraclinique', 'value' => $spa->examen_paraclinique, 'required' => false],
                        ['label' => 'Observation(s)', 'name' => 'observation', 'value' => $spa->observation, 'required' => true],
                    ] as $field)
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                            {{ $field['label'] }} @if($field['required'])<span class="tw-text-red-500">*</span>@endif
                        </label>
                        <textarea name="{{ $field['name'] }}" rows="3" @if($field['required']) required @endif
                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none">{{ $field['value'] }}</textarea>
                    </div>
                    @endforeach

                    <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date de sortie</label>
                            <input type="date" name="date_sortie" value="{{ $spa->date_sortie }}"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Heure de sortie</label>
                            <input type="time" name="heur_sortie" value="{{ $spa->heur_sortie }}"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>
                    </div>

                    <div class="tw-flex tw-justify-end tw-pt-2">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-amber-500 tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-amber-600 tw-border-0 tw-cursor-pointer tw-transition-colors">
                            <i class="fas fa-save tw-text-xs"></i> Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach