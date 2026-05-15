<?php

namespace App\Providers;


use App\Models\Patient;
use App\Models\PatientVisit;
use App\Observers\PatientObserver;
use App\Observers\PatientVisitObserver;
use App\Models\FactureLigne;
use App\Observers\FactureLigneObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Patient::observe(PatientObserver::class);
        PatientVisit::observe(PatientVisitObserver::class);
        FactureLigne::observe(FactureLigneObserver::class);

        //
    }
}
