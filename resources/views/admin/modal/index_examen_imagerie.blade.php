<div class="modal fade" id="imagerieAll" tabindex="-1" role="dialog" aria-labelledby="imagerieAll" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-sky-500">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-x-ray tw-mr-2"></i>Examens Imagerie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6">
                @if(count($patient->imageries))
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Description</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Date</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Imprimer</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($patient->imageries as $imagerie)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600 tw-text-xs">
                                    {{ implode(' ', array_filter([
                                        $imagerie->radiographie, $imagerie->echographie,
                                        $imagerie->scanner, $imagerie->irm,
                                        $imagerie->scintigraphie, $imagerie->autre
                                    ])) }}
                                </td>
                                <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-400 tw-whitespace-nowrap">{{ $imagerie->created_at->toFormattedDateString() }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    <a href="{{ route('imageries_examens.pdf', $imagerie->id) }}" title="Imprimer"
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-emerald-50 hover:tw-bg-emerald-100 tw-text-emerald-600 tw-no-underline tw-transition-colors">
                                        <i class="fas fa-print tw-text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tw-mt-4">{{ $ordonances->links() }}</div>
                @else
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-12">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-x-ray tw-text-slate-400"></i>
                    </div>
                    <p class="tw-text-slate-400 tw-text-sm tw-mb-0">Aucun examen d'imagerie enregistré</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>