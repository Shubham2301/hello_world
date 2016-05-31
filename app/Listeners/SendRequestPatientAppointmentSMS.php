<?php

namespace myocuhub\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Event;
use Log;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Events\RequestPatientAppointment;
use myocuhub\Facades\Sms;
use myocuhub\Models\MessageTemplate;
use myocuhub\Patient;

class SendRequestPatientAppointmentSMS
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

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
        /**
         * SMS action missing. 
         */
        if(!in_array("sms", $actions)){
            return;
        }

        $patientID = $event->getPatientID();
        $patient = Patient::find($patientID);

        /**
         * Patient not found
         */
        if($patient == null){
            return;
        }

        $attr['phone'] = $patient->email;
        $attr['name'] = $patient->firstname.' '.$patient->lastname;

        $attr['message'] = $event->getMessage();

        if($attr['message'] == ''){
            /**
             * Predefined network message to be sent.
             */
            $attr['message'] = MessageTemplate::getTemplate('sms', 'request_for_appointment', session('network-id'))->message;
        }

        $this->sendRequest($attr);

        return;
    }

    public function sendRequest($attr){
        
        $to = $attr['phone'];
        $message = $attr['message'];

        try {
            /**
             * Send SMS
             */
            $message = Sms::send($to, $message);

            return true;
        } catch (Exception $e) {
            Log::error($e);

            /**
             * Audit, Exception Logging.
             */
            Log::error($e);
            $action = 'Application Exception in sending Appointment Request SMS to patient '. $attr['name'].' on '. $attr['phone'];
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

            return false;
        }
        
        return true;
    }
}
