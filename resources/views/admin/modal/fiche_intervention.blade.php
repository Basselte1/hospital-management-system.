<div class="modal fade" id="FicheIntervention" tabindex="-1" role="dialog" aria-labelledby="FicheIntervention" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#14B8A6]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-file-medical tw-mr-2"></i>Fiche d'Intervention
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6 tw-max-h-[80vh] tw-overflow-y-auto">
                <form action="" method="post">
                    @csrf

                    {{-- Patient info header --}}
                    <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mb-5">
                        <div class="tw-space-y-1 tw-text-sm">
                            <div class="tw-flex tw-gap-2"><span class="tw-text-slate-500 tw-font-medium">Nom et Prénom :</span> <span class="tw-text-slate-700">—</span></div>
                            <div class="tw-flex tw-gap-2"><span class="tw-text-slate-500 tw-font-medium">Date de naissance :</span> <span class="tw-text-slate-700">—</span></div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1">Type d'intervention</label>
                                <input type="text" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                        </div>
                        <div class="tw-space-y-1 tw-text-sm tw-text-slate-600">
                            <div><span class="tw-font-medium tw-text-slate-500">Opérateur :</span> —</div>
                            <div><span class="tw-font-medium tw-text-slate-500">Aide :</span> —</div>
                            <div><span class="tw-font-medium tw-text-slate-500">Anesthésie :</span> —</div>
                            <div><span class="tw-font-medium tw-text-slate-500">Salle :</span> —</div>
                        </div>
                    </div>

                    <div class="tw-border-t tw-border-slate-100 tw-pt-4 tw-mb-5">
                        <div class="tw-grid tw-grid-cols-3 tw-gap-4">
                            <div>
                                <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-2">Anesthésiste :</p>
                                <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-2 tw-mt-3">Position :</p>
                            </div>
                            <div class="tw-space-y-2 tw-text-sm tw-text-slate-700">
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded"> Locale
                                </label>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded"> Rachi-anesthésie
                                </label>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded"> Gynécologique
                                </label>
                            </div>
                            <div class="tw-space-y-2 tw-text-sm tw-text-slate-700">
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded"> Générale
                                </label>
                                <div class="tw-mt-3">
                                    <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                        <input type="checkbox" class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded"> Décubitus
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Materials Table --}}
                    <div class="tw-bg-white tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-mb-5">
                        <div class="tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-border-b tw-border-slate-100">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wider tw-text-center tw-mb-0">Matériels et Consommables</p>
                        </div>
                        <div class="tw-overflow-x-auto">
                            <table class="tw-w-full tw-text-sm">
                                <tbody class="tw-divide-y tw-divide-slate-100">
                                    @foreach([
                                        ['Instillagel', 'Graine Hartmann', 'Colonne Endoscopie'],
                                        ['Compresses', 'Poche à urines', 'Caméra'],
                                        ['Bétadine', "Trousse d'irrigation", 'Resecteur'],
                                        ['Seringue 10 cc', 'Champ opératoire', "Lame d'urétrotome"],
                                        ['Gants stériles', 'Optique 30%', 'Câble électrique'],
                                        ['Sonde Dufour', '', ''],
                                    ] as $row)
                                    <tr class="hover:tw-bg-slate-50/50">
                                        @foreach($row as $item)
                                        @if($item)
                                        <td class="tw-px-4 tw-py-2.5 tw-text-slate-600 tw-w-[40%]">{{ $item }}</td>
                                        <td class="tw-px-2 tw-py-2.5 tw-w-[10%]">
                                            <input type="number" class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1 tw-text-sm tw-text-center focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-[#BFDBFE]">
                                        </td>
                                        @else
                                        <td class="tw-px-4 tw-py-2.5 tw-w-[40%]"></td>
                                        <td class="tw-w-[10%]"></td>
                                        @endif
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                    <div class="tw-flex tw-justify-end">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-[#14B8A6] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-teal-600 tw-border-0 tw-cursor-pointer tw-transition-colors">
                            <i class="fas fa-save tw-text-xs"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>