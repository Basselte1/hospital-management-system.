{{-- Partial: resources/views/partials/dashboard/widget_recent_patients.blade.php --}}
@php $recentPatients = \App\Models\Patient::select(['id','name','prenom','numero_dossier','created_at'])->latest()->limit(6)->get(); @endphp
<div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
    <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
        <div class="tw-flex tw-items-center tw-gap-2">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-teal-100 tw-flex tw-items-center tw-justify-center">
                <i class="fas fa-user-injured tw-text-[#14B8A6] tw-text-xs"></i>
            </div>
            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Patients Récemment Enregistrés</h2>
        </div>
        <a href="{{ route('patients.index') }}" class="tw-text-xs tw-text-[#1D4ED8] hover:tw-underline tw-no-underline">Voir tout →</a>
    </div>
    <div class="tw-divide-y tw-divide-slate-50">
        @forelse($recentPatients as $p)
        <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-3">
            <div class="tw-flex tw-items-center tw-gap-3">
                <div class="tw-w-8 tw-h-8 tw-rounded-full tw-bg-gradient-to-br tw-from-teal-400 tw-to-teal-600 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                    <span class="tw-text-white tw-text-xs tw-font-bold">{{ strtoupper(substr($p->prenom ?? $p->name, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-0">{{ $p->prenom }} {{ $p->name }}</p>
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-0">N° {{ $p->numero_dossier }}</p>
                </div>
            </div>
            <div class="tw-text-right">
                @if($p->created_at >= now()->subHours(48))
                <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-amber-100 tw-text-amber-700">NOUVEAU</span>
                @else
                <span class="tw-text-xs tw-text-slate-400">{{ $p->created_at->diffForHumans() }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="tw-px-6 tw-py-8 tw-text-center tw-text-slate-400 tw-text-sm">Aucun patient enregistré</div>
        @endforelse
    </div>
</div>