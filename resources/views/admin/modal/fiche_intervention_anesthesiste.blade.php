<div class="modal fade" id="FicheInterventionAnesthesiste" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">

            {{-- Header --}}
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-file-medical tw-mr-2"></i>Fiche d'Intervention — Anesthésiste
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="tw-p-6 tw-max-h-[82vh] tw-overflow-y-auto">
                <form action="{{ route('fiche_intervention.store') }}" method="post" class="tw-space-y-5">
                    @csrf
                    <input type="hidden" value="{{ $patient->id }}" name="patient_id">

                    {{-- ── SECTION: PATIENT ── --}}
                    <div>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-user"></i> Patient
                        </h3>
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Nom du patient</label>
                                <input type="text" value="{{ $patient->name }}" name="nom_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Prénom du patient</label>
                                <input type="text" value="{{ $patient->prenom }}" name="prenom_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            @foreach ($patient->dossiers as $dossier)
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Sexe</label>
                                <input type="text" value="{{ $dossier->sexe }}" name="sexe_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date de naissance</label>
                                <input type="date" value="{{ $dossier->date_naissance }}" name="date_naiss_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Téléphone</label>
                                <input type="number" value="{{ $dossier->portable_2 }}" name="portable_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- ── SECTION: INTERVENTION ── --}}
                    <div>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-scalpel-path"></i> Intervention
                        </h3>
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Type <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="text" name="type_intervention" required
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Durée <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="time" name="dure_intervention" required
                                       class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Date intervention <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="date" name="date_intervention" value="{{ old('date_intervention') }}" required
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Chirurgien <span class="tw-text-red-500">*</span>
                                </label>
                                <select name="medecin" id="medecin" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    <option value="">Choisir le médecin</option>
                                    @foreach($medecin as $m)
                                        <option value="{{ $m->name }} {{ $m->prenom }}">{{ $m->name }} {{ $m->prenom }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    {{-- ── SECTION: POSITION DU PATIENT ── --}}
                    <div>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-person-booth"></i> Position du patient <span class="tw-text-red-500">*</span>
                        </h3>
                        <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-p-4 tw-space-y-2 tw-text-sm tw-text-slate-700">

                            {{-- Décubitus --}}
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-font-medium">
                                <input type="radio" name="position_patient[]" value="Décubitus" id="decubitus"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                Décubitus
                            </label>
                            <div class="tw-ml-6 tw-space-y-1.5 tw-border-l-2 tw-border-slate-200 tw-pl-4">
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="radio" name="decubitus[]" value="Latéral" id="lateral"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                    Latéral
                                </label>
                                <div class="tw-ml-6 tw-flex tw-gap-4">
                                    <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                        <input type="checkbox" name="laterale[]" value="Droite" id="laterale_droite"
                                               class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                        Droite
                                    </label>
                                    <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                        <input type="checkbox" name="laterale[]" value="Gauche" id="laterale_gauche"
                                               class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                        Gauche
                                    </label>
                                </div>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="radio" name="decubitus[]" value="Dorsal" id="dorsal"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                    Dorsal
                                </label>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="radio" name="decubitus[]" value="Ventral" id="ventral"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                    Ventral
                                </label>
                            </div>

                            {{-- Lithotomie --}}
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-font-medium">
                                <input type="radio" name="position_patient[]" value="Lithotomie" id="lithotomie"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                Lithotomie
                            </label>

                            {{-- Lombotomie --}}
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-font-medium">
                                <input type="radio" name="position_patient[]" value="Lombotomie" id="lombotomie"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                Lombotomie
                            </label>
                            <div class="tw-ml-6 tw-flex tw-gap-4 tw-border-l-2 tw-border-slate-200 tw-pl-4">
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" name="lombotomie[]" value="Droite" id="lombotomie_droite"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                    Droite
                                </label>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" name="lombotomie[]" value="Gauche" id="lombotomie_gauche"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                    Gauche
                                </label>
                            </div>

                            {{-- Trendelenburg --}}
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-font-medium">
                                <input type="radio" name="position_patient[]" value="Trendelenburg" id="trendelenburg"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                Trendelenburg
                            </label>

                            {{-- Autre --}}
                            <div class="tw-flex tw-items-center tw-gap-3 tw-mt-1">
                                <label class="tw-text-xs tw-font-medium tw-text-slate-500 tw-shrink-0">Autre :</label>
                                <input type="text" name="position_patient[]" id="position_autre"
                                       class="tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                        </div>
                    </div>

                    {{-- ── SECTION: AIDE OPÉRATOIRE & HOSPITALISATION ── --}}
                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">

                        {{-- Aide opératoire --}}
                        <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-p-4">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-3">
                                Aide opératoire <span class="tw-text-red-500">*</span>
                            </p>
                            <div class="tw-space-y-2 tw-text-sm tw-text-slate-700">
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" name="aide_op[]" value="Oui" id="aide_oui"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded"> Oui
                                </label>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" name="aide_op[]" value="Non" id="aide_non"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded"> Non
                                </label>
                            </div>
                        </div>

                        {{-- Hospitalisation --}}
                        <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-p-4">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-3">Hospitalisation</p>
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-text-sm tw-text-slate-700 tw-mb-2">
                                <input type="radio" name="hospitalisation" value="Hospitalisation" id="hosp_oui"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]"> Hospitalisation
                            </label>
                            <input type="text" name="heure" placeholder="Heure"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>

                        {{-- Ambulatoire --}}
                        <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-p-4">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-3">Ambulatoire</p>
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-text-sm tw-text-slate-700">
                                <input type="radio" name="ambulatoire" value="Ambulatoire" id="amb_oui"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]"> Ambulatoire
                            </label>
                        </div>

                    </div>

                    {{-- ── SECTION: ANESTHÉSIE ── --}}
                    <div>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-syringe"></i> Anesthésie <span class="tw-text-red-500">*</span>
                        </h3>
                        <div class="tw-flex tw-flex-wrap tw-gap-3">
                            @foreach(['AL', 'AG', 'LR', 'RA', 'PD', 'ALR'] as $type)
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-px-3 tw-py-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 hover:tw-bg-[#BFDBFE]/30 tw-text-sm tw-text-slate-700 tw-font-medium tw-transition-colors">
                                <input type="checkbox" name="anesthesie[]" value="{{ $type }}" id="anesth_{{ $type }}"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                {{ $type }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── SECTION: RECOMMANDATIONS ── --}}
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                            Recommandation(s)
                        </label>
                        <textarea name="recommendation" rows="4"
                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none">{{ old('recommendation') }}</textarea>
                    </div>

                    {{-- ── ACTIONS ── --}}
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