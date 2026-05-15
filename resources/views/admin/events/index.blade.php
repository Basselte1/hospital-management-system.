{{-- indexEvent.blade.php --}}
@extends('layouts.admin')
@section('title', 'Gestion des Rendez-vous - CMCU')

@section('content')
<div id="app">
    <div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-0">
        @include('partials.side_bar')

        <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
            @include('partials.header')

            <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
                <events-calendar
                    :editable="true"
                    view-mode="timeline"
                    :can-create="{{ json_encode(auth()->user()->can('create', App\Models\Event::class)) }}"
                    :can-update="{{ json_encode(auth()->user()->can('update', App\Models\Event::class)) }}"
                    :can-delete="{{ json_encode(auth()->user()->can('delete', App\Models\Event::class)) }}"
                    :user-role="{{ auth()->user()->role_id }}"
                    @if(auth()->user()->role_id === 2)
                        :medecin-id="{{ auth()->user()->id }}"
                        medecin-name="{{ auth()->user()->name . ' ' . auth()->user()->prenom }}"
                    @endif
                ></events-calendar>
            </main>
        </div>
    </div>
</div>
@endsection