{{-- Partial: resources/views/partials/dashboard/widget_events_today.blade.php --}}
@php $todayEvents = \App\Models\Event::whereDate('start', today())->orderBy('start')->limit(6)->get(); @endphp
<div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
    <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-100">
        <div class="tw-flex tw-items-center tw-gap-2">
            <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center">
                <i class="fas fa-calendar-day tw-text-indigo-500 tw-text-xs"></i>
            </div>
            <h2 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Rendez-vous Aujourd'hui</h2>
            @if($todayEvents->count() > 0)
            <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-5 tw-h-5 tw-rounded-full tw-bg-indigo-500 tw-text-white tw-text-xs tw-font-bold">{{ $todayEvents->count() }}</span>
            @endif
        </div>
        <a href="{{ route('events.index') }}" class="tw-text-xs tw-text-[#1D4ED8] hover:tw-underline tw-no-underline">Calendrier →</a>
    </div>
    <div class="tw-divide-y tw-divide-slate-50">
        @forelse($todayEvents as $ev)
        <div class="tw-flex tw-items-start tw-gap-3 tw-px-6 tw-py-3">
            <div class="tw-shrink-0 tw-text-center">
                <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-12 tw-h-10 tw-rounded-xl tw-bg-indigo-50 tw-text-indigo-600 tw-text-xs tw-font-bold">
                    {{ \Carbon\Carbon::parse($ev->start)->format('H:i') }}
                </span>
            </div>
            <div class="tw-flex-1 tw-min-w-0">
                <p class="tw-text-sm tw-font-medium tw-text-slate-700 tw-mb-0 tw-truncate">{{ $ev->title ?? 'Rendez-vous' }}</p>
                <p class="tw-text-xs tw-text-slate-400 tw-mb-0">
                    @if(isset($ev->patient) && $ev->patient)
                        {{ $ev->patient->prenom ?? '' }} {{ $ev->patient->name ?? '' }}
                    @else
                        <span class="tw-italic">Pas de patient lié</span>
                    @endif
                </p>
            </div>
            @if(\Carbon\Carbon::parse($ev->start)->isPast())
            <span class="tw-inline-flex tw-items-center tw-px-1.5 tw-py-0.5 tw-rounded tw-text-xs tw-text-slate-400 tw-bg-slate-100">Passé</span>
            @else
            <span class="tw-inline-flex tw-items-center tw-px-1.5 tw-py-0.5 tw-rounded tw-text-xs tw-text-indigo-600 tw-bg-indigo-50 tw-font-medium">À venir</span>
            @endif
        </div>
        @empty
        <div class="tw-px-6 tw-py-8 tw-text-center tw-text-slate-400 tw-text-sm">
            <i class="fas fa-calendar-xmark tw-text-2xl tw-block tw-mb-2 tw-text-slate-300"></i>
            Aucun rendez-vous aujourd'hui
        </div>
        @endforelse
    </div>
</div>