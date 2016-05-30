<?php

namespace myocuhub\Listeners;


use Event;
use Exception;
use Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Events\RequestPatientAppointment;

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
        /**
         * Email action missing. 
         */
        if(!in_array("email", $action)){
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
        $data['email'] = $patient->email;
        $data['name'] = $patient->firstname.' '.$patient->lastname;

        $user = Auth::user();
        $data['network_id'] = session('network-id');
        $data['sent_by_name'] = $user->name;
        $data['sent_by_email'] = $user->email;

        $data['message'] = $event->getMessage();

        if($data['message'] == ''){
            /**
             * Predefined network message to be sent.
             */
            $data['message'] = MessageTemplate::getTemplate('email', 'request_for_appointment', session('network-id'))->message;
        }

        $this->sendRequest($data);

        return;
    }
    private function sendRequest($data){
        
        /**
         * Invalid Email ID
         */
        if(Validator::make($data, ['email' => 'email'])->fails()){
            return false;
        }

        try {

            /**
             * Send Mail
             */
            $mailToProvider = Mail::send('emails.appt-request-patient', ['appt' => $appt], function ($m) use ($patient) {
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to($patient->email, $patient->firstname.' '.$patient->lastname)->subject('Request for Appointment');
            });

        } catch (Exception $e) {
            
            /**
             * Audit, Exception Logging.
             */
            Log::error($e);
            $action = 'Application Exception in sending Appointment Request email to patient '. $patient->email;
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

            return false;
        }

        return true;
    }
}
