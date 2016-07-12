<?php

namespace myocuhub\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'myocuhub\Events\MakeAuditEntry' => [],
        'myocuhub\Events\PatientEngagementSuccess' => [
            'myocuhub\Listeners\AuditListener',
        ],
        'myocuhub\Events\PatientEngagementFailure' => [
            'myocuhub\Listeners\AuditListener',
        ],
        'myocuhub\Events\AppointmentScheduled' => [
            'myocuhub\Listeners\RequestFPCAppointment',
            'myocuhub\Listeners\SendAppointmentRequestEmail',
        ],
        'myocuhub\Events\RequestPatientAppointment' => [
            'myocuhub\Listeners\SendRequestPatientAppointmentEmail',
            'myocuhub\Listeners\SendRequestPatientAppointmentSMS',
        ],
        'myocuhub\Events\Patient\PatientRecordCreation' => [
            'myocuhub\Listeners\AuditListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events) {
        parent::boot($events);
    }
}
