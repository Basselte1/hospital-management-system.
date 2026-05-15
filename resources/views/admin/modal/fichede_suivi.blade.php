<div class="modal fade" id="ficheSuiviAll" tabindex="-1" role="dialog" aria-labelledby="ficheSuiviAll" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-clipboard-check tw-mr-2"></i>Consultation de Suivi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6">
                @if(count($patient->consultationdesuivi))
                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Interrogatoire</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Commentaire</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Date</th>
                                <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Voir</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach($patient->consultationdesuivi as $suivi)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                                <td class="tw-px-4 tw-py-3 tw-text-slate-700 tw-max-w-xs tw-truncate">{{ $suivi->interrogatoire }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-slate-600 tw-max-w-xs tw-truncate">{{ $suivi->commentaire }}</td>
                                <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-400 tw-whitespace-nowrap">{{ $suivi->date_creation }}</td>
                                <td class="tw-px-4 tw-py-3">
                                    <a href="{{ route('consultationsdesuivi.show', $suivi->id) }}" title="Voir"
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                                        <i class="fas fa-eye tw-text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-py-12">
                    <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                        <i class="fas fa-clipboard-check tw-text-slate-400"></i>
                    </div>
                    <p class="tw-text-slate-400 tw-text-sm tw-mb-0">Aucune consultation de suivi enregistrée</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>