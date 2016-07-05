<?php

namespace myocuhub\Listeners;


use Auth;
use Event;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Log;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Events\RequestPatientAppointment;
use myocuhub\Jobs\PatientEngagement\RequestAppointmentPatientMail;
use myocuhub\Models\MessageTemplate;
use myocuhub\Patient;

class SendRequestPatientAppointmentEmail
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
        
        $actions = $event->getAction();

        if(!in_array("email", $actions)){
            return;
        }

        $patient = Patient::find($event->getPatientID());

        if($patient == null){
            return;
        }

        dispatch((new RequestAppointmentPatientMail($patient, $appt))->onQueue('mail'));

        return;
    }
}
