<div class="modal fade" id="admin_prescription_medicale" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content tw-rounded-2xl tw-overflow-hidden tw-border-0 tw-shadow-xl">

            {{-- Header --}}
            <div class="modal-header tw-bg-[#1D4ED8] tw-border-0 tw-px-5 tw-py-4">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-syringe tw-text-white tw-text-xs"></i>
                    </div>
                    <h5 class="modal-title tw-text-sm tw-font-semibold tw-text-white tw-mb-0">Soins infirmiers administrés</h5>
                </div>
                <button type="button" class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-flex tw-items-center tw-justify-center tw-border-0 tw-transition-colors" data-bs-dismiss="modal">
                    <i class="fas fa-times tw-text-xs"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body tw-p-0">
                <div class="tw-overflow-x-auto">
                    <table id="admin_prescription_medicale_table"
                           class="tw-w-full tw-text-sm tw-border-collapse dt-responsive display nowrap td-responsive"
                           cellspacing="0" width="100%">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th rowspan="2" class="tw-px-4 tw-py-3 tw-text-left tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-align-middle tw-border-r tw-border-slate-100">Date</th>
                                <th rowspan="2" class="tw-px-4 tw-py-3 tw-text-left tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-align-middle tw-border-r tw-border-slate-100">IDE</th>
                                <th colspan="4" class="tw-px-4 tw-py-2 tw-text-center tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-border-b tw-border-slate-100">Périodes</th>
                            </tr>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-200">
                                @foreach(['M', 'AM', 'S', 'N'] as $period)
                                <th class="tw-px-3 tw-py-2 tw-text-center tw-text-[11px] tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">{{ $period }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-50">
                            {{-- Populated dynamically via JS --}}
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer tw-bg-slate-50 tw-border-t tw-border-slate-100 tw-px-5 tw-py-3">
                <button type="button"
                        data-bs-dismiss="modal"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-transition-colors">
                    <i class="fas fa-times tw-text-xs"></i> Fermer
                </button>
            </div>

        </div>
    </div>
</div>