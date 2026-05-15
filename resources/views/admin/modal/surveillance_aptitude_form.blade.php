<div class="modal fade" id="SurveillanceAptitude" tabindex="-1" role="dialog" aria-labelledby="SurveillanceAptitude" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-start tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <p class="tw-text-white tw-text-xs tw-font-medium tw-mb-0 tw-leading-relaxed tw-pr-6">
                    Fréquence de surveillance jusqu'à l'obtention d'un score d'aptitude d'au moins 9/10.
                    Lorsque le patient <strong>{{ $patient->name }} {{ $patient->prenom }}</strong> est habillé
                    et sorti de la chambre, arrêt de la surveillance.
                </p>
                <button type="button" class="btn-close btn-close-white tw-shrink-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6 tw-max-h-[80vh] tw-overflow-y-auto">
                <form action="{{ route('surveillance_score.store') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $patient->id }}" name="patient_id">

                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">

                        @foreach([
                            ['label' => 'Horaires', 'name' => 'horaire', 'type' => 'datetime-local', 'required' => true, 'value' => old('horaire')],
                            ['label' => 'T.A s/d', 'name' => 'ta', 'type' => 'text', 'required' => true, 'value' => old('ta')],
                            ['label' => 'F.C', 'name' => 'fc', 'type' => 'number', 'required' => true, 'value' => old('fc')],
                            ['label' => 'SPO2', 'name' => 'spo2', 'type' => 'number', 'required' => true, 'value' => old('spo2')],
                            ['label' => 'F.R', 'name' => 'fr', 'type' => 'number', 'required' => true, 'value' => old('fr')],
                            ['label' => 'Douleur (EN/EVA)', 'name' => 'douleur', 'type' => 'text', 'required' => false, 'value' => old('douleur')],
                            ['label' => 'T°', 'name' => 'temperature', 'type' => 'number', 'required' => true, 'value' => old('temperature'), 'extra' => 'min="0" step="any"'],
                            ['label' => 'Glycémie', 'name' => 'glycemie', 'type' => 'text', 'required' => false, 'value' => old('glycemie')],
                            ['label' => 'Sédation', 'name' => 'sedation', 'type' => 'text', 'required' => false, 'value' => old('sedation')],
                            ['label' => 'Nausées', 'name' => 'nausee', 'type' => 'text', 'required' => false, 'value' => old('nausee')],
                            ['label' => 'Vomissements', 'name' => 'vomissement', 'type' => 'text', 'required' => false, 'value' => old('vomissement')],
                            ['label' => 'Saignements', 'name' => 'saignement', 'type' => 'text', 'required' => false, 'value' => old('saignement')],
                            ['label' => 'Pansements', 'name' => 'pansement', 'type' => 'text', 'required' => false, 'value' => old('pansement')],
                            ['label' => 'Conscience', 'name' => 'conscience', 'type' => 'text', 'required' => false, 'value' => old('conscience')],
                            ['label' => 'Drains', 'name' => 'drains', 'type' => 'text', 'required' => false, 'value' => old('drains')],
                        ] as $field)
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                {{ $field['label'] }} @if($field['required'])<span class="tw-text-red-500">*</span>@endif
                            </label>
                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                                   value="{{ $field['value'] }}"
                                   @if($field['required']) required @endif
                                   {{ $field['extra'] ?? '' }}
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>
                        @endforeach

                        {{-- Miction --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Miction</label>
                            <select name="miction"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                <option value="">Miction</option>
                                <option value="Oui">Oui</option>
                                <option value="Non">Non</option>
                            </select>
                        </div>

                        {{-- Lever --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Lever</label>
                            <select name="lever"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                <option value="">Lever</option>
                                <option value="Oui">Oui</option>
                                <option value="Non">Non</option>
                            </select>
                        </div>

                        {{-- Score --}}
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Score d'Aptitude</label>
                            <input type="text" name="score" value="{{ old('score') }}"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>

                    </div>

                    <div class="tw-flex tw-justify-end tw-mt-6 tw-pt-4 tw-border-t tw-border-slate-100">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-border-0 tw-cursor-pointer tw-transition-colors">
                            <i class="fas fa-save tw-text-xs"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>