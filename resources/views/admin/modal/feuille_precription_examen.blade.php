<div class="modal fade" id="ordonanceModal" tabindex="-1" role="dialog" aria-labelledby="ordonanceModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-flask tw-mr-2"></i>Feuille de Prescription Examens
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6">
                {{-- Tab Nav --}}
                <ul class="nav nav-tabs tw-mb-5 tw-border-b tw-border-slate-100 -tw-mt-1">
                    <li class="nav-item">
                        <a data-bs-toggle="tab" href="#menu1"
                           class="nav-link active tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-rounded-t-lg tw-px-4 tw-py-2">
                            <i class="fas fa-vial tw-mr-1.5 tw-text-[#1D4ED8]"></i>Biologies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="tab" href="#menu2"
                           class="nav-link tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-rounded-t-lg tw-px-4 tw-py-2">
                            <i class="fas fa-x-ray tw-mr-1.5 tw-text-sky-500"></i>Imageries
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <form id="menu1" class="tab-pane fade in active show" action="{{ route('prescriptions.store') }}" method="POST">
                        @csrf
                        <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-text-center tw-mb-4 tw-uppercase tw-tracking-wide">Biologie</h3>
                        @include('admin.consultations.partials.feuille_examen_biologie')
                        <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                        <div class="tw-flex tw-justify-end tw-gap-3 tw-mt-5 tw-pt-4 tw-border-t tw-border-slate-100">
                            <button type="button" data-bs-dismiss="modal"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-border-0 tw-cursor-pointer tw-transition-colors">
                                Fermer
                            </button>
                            <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-border-0 tw-cursor-pointer tw-transition-colors">
                                <i class="fas fa-save tw-text-xs"></i> Enregistrer
                            </button>
                        </div>
                    </form>

                    <form id="menu2" class="tab-pane fade" action="{{ route('imageries.store') }}" method="post">
                        @csrf
                        <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-text-center tw-mb-4 tw-uppercase tw-tracking-wide">Imagerie</h3>
                        @include('admin.consultations.partials.feuille_examen_imagerie')
                        <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                        <div class="tw-flex tw-justify-end tw-gap-3 tw-mt-5 tw-pt-4 tw-border-t tw-border-slate-100">
                            <button type="button" data-bs-dismiss="modal"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-border-0 tw-cursor-pointer tw-transition-colors">
                                Fermer
                            </button>
                            <button type="submit"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-border-0 tw-cursor-pointer tw-transition-colors">
                                <i class="fas fa-save tw-text-xs"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>