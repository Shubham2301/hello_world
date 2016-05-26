<?php

namespace myocuhub\Listeners;

use myocuhub\Events\RequestPatientAppointment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRequestPatientAppointmentSMS
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RequestPatientAppointment  $event
     * @return void
     */
    public function handle(RequestPatientAppointment $event)
    {
        //
    }
}
