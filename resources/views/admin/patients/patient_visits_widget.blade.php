{{-- Composant à insérer dans patients.show.blade.php --}}
<div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden tw-mb-4">

    {{-- Collapsible header --}}
    <div class="tw-px-5 tw-py-3.5 tw-bg-[#14B8A6] tw-flex tw-items-center tw-justify-between tw-cursor-pointer"
         data-bs-toggle="collapse"
         data-bs-target="#historiqueVisites"
         aria-expanded="true">
        <div class="tw-flex tw-items-center tw-gap-2.5">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                <i class="fas fa-history tw-text-white tw-text-xs"></i>
            </div>
            <span class="tw-text-white tw-font-semibold tw-text-sm">Historique des visites</span>
            <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-white tw-text-teal-600 tw-px-2 tw-py-0.5 tw-text-xs tw-font-bold">
                {{ $patient->visits()->count() }}
            </span>
        </div>
        <i class="fas fa-chevron-up tw-text-white tw-text-xs tw-transition-transform" id="historiqueIcon"></i>
    </div>

    <div class="collapse show" id="historiqueVisites">
        <div class="tw-p-4">
            @if($patient->visits()->count() > 0)

            <div class="tw-overflow-x-auto">
                <table class="tw-w-full tw-text-sm">
                    <thead>
                        <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                            <th class="tw-px-3 tw-py-2.5 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Date</th>
                            <th class="tw-px-3 tw-py-2.5 tw-text-left tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Motif</th>
                            @if(in_array(auth()->user()->role_id, [1, 6]))
                            <th class="tw-px-3 tw-py-2.5 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Montant</th>
                            <th class="tw-px-3 tw-py-2.5 tw-text-right tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Reste</th>
                            @endif
                            <th class="tw-px-3 tw-py-2.5 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Statut</th>
                            <th class="tw-px-3 tw-py-2.5 tw-text-center tw-text-xs tw-uppercase tw-tracking-wider tw-text-slate-500 tw-font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tw-divide-y tw-divide-slate-100">
                        @foreach($patient->visits()->latest('visit_date')->limit(5)->get() as $visit)
                        <tr class="hover:tw-bg-slate-50 tw-transition-colors">
                            <td class="tw-px-3 tw-py-2.5 tw-whitespace-nowrap">
                                <span class="tw-font-semibold tw-text-slate-700 tw-text-xs">
                                    {{ $visit->visit_date->format('d/m/Y') }}
                                </span>
                                @if($visit->isToday())
                                <span class="tw-ml-1.5 tw-inline-flex tw-items-center tw-rounded-full tw-bg-teal-100 tw-text-teal-700 tw-px-2 tw-py-0.5 tw-text-[10px] tw-font-bold">
                                    Aujourd'hui
                                </span>
                                @endif
                            </td>
                            <td class="tw-px-3 tw-py-2.5 tw-text-xs tw-text-slate-500">
                                {{ $visit->motif ? str($visit->motif)->limit(25) : '—' }}
                            </td>
                            @if(in_array(auth()->user()->role_id, [1, 6]))
                            <td class="tw-px-3 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-600 tw-whitespace-nowrap">
                                {{ number_format($visit->montant) }} F
                            </td>
                            <td class="tw-px-3 tw-py-2.5 tw-text-right tw-text-xs tw-font-semibold tw-whitespace-nowrap {{ $visit->reste > 0 ? 'tw-text-red-500' : 'tw-text-teal-600' }}">
                                {{ number_format($visit->reste) }} F
                            </td>
                            @endif
                            <td class="tw-px-3 tw-py-2.5 tw-text-center">
                                <span class="tw-inline-flex tw-items-center tw-rounded-full tw-px-2.5 tw-py-0.5 tw-text-[10px] tw-font-semibold tw-bg-{{ $visit->status_badge_color }}-100 tw-text-{{ $visit->status_badge_color }}-700">
                                    {{ $visit->status_label }}
                                </span>
                            </td>
                            <td class="tw-px-3 tw-py-2.5 tw-text-center">
                                <a href="{{ route('patient-visits.patient-history', $visit->patient) }}"
                                   title="Voir l'historique"
                                   class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE]/50 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-transition-colors tw-no-underline">
                                    <i class="fas fa-eye tw-text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- See all link --}}
            @if($patient->visits()->count() > 5)
            <div class="tw-mt-3 tw-text-center">
                <a href="{{ route('patient-visits.patient-history', $patient) }}"
                   class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-border tw-border-teal-200 tw-text-teal-600 hover:tw-bg-teal-50 tw-px-4 tw-py-2 tw-text-xs tw-font-medium tw-transition-colors tw-no-underline">
                    <i class="fas fa-list tw-text-[9px]"></i>
                    Voir toutes les visites ({{ $patient->visits()->count() }})
                </a>
            </div>
            @endif

            {{-- Financial summary (admin/role 1 & 6 only) --}}
            @if(in_array(auth()->user()->role_id, [1, 6]))
            <div class="tw-mt-4 tw-pt-4 tw-border-t tw-border-slate-100 tw-grid tw-grid-cols-3 tw-gap-3">
                <div class="tw-text-center">
                    <p class="tw-text-[10px] tw-text-slate-400 tw-mb-1 tw-uppercase tw-tracking-wide">Total dépensé</p>
                    <p class="tw-font-bold tw-text-teal-600 tw-text-sm tw-mb-0">
                        {{ number_format($patient->visits()->sum('montant')) }} F
                    </p>
                </div>
                <div class="tw-text-center tw-border-x tw-border-slate-100">
                    <p class="tw-text-[10px] tw-text-slate-400 tw-mb-1 tw-uppercase tw-tracking-wide">Total payé</p>
                    <p class="tw-font-bold tw-text-[#1D4ED8] tw-text-sm tw-mb-0">
                        {{ number_format($patient->visits()->sum('avance')) }} F
                    </p>
                </div>
                <div class="tw-text-center">
                    <p class="tw-text-[10px] tw-text-slate-400 tw-mb-1 tw-uppercase tw-tracking-wide">Reste à payer</p>
                    <p class="tw-font-bold tw-text-sm tw-mb-0 {{ $patient->visits()->sum('reste') > 0 ? 'tw-text-red-500' : 'tw-text-teal-600' }}">
                        {{ number_format($patient->visits()->sum('reste')) }} F
                    </p>
                </div>
            </div>
            @endif

            @else
            {{-- Empty state --}}
            <div class="tw-py-8 tw-text-center">
                <div class="tw-w-12 tw-h-12 tw-rounded-2xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-3">
                    <i class="fas fa-inbox tw-text-slate-300 tw-text-xl"></i>
                </div>
                <p class="tw-text-slate-400 tw-text-sm tw-mb-3">Aucune visite enregistrée via le nouveau système</p>
                <a href="{{ route('patient-visits.create') }}?patient_id={{ $patient->id }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-font-medium tw-text-sm tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                    <i class="fas fa-plus tw-text-xs"></i> Enregistrer une visite
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.getElementById("historiqueVisites").addEventListener("hide.bs.collapse", function () {
        document.getElementById("historiqueIcon").style.transform = 'rotate(180deg)';
    });
    document.getElementById("historiqueVisites").addEventListener("show.bs.collapse", function () {
        document.getElementById("historiqueIcon").style.transform = 'rotate(0deg)';
    });
</script>