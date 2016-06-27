<?php

namespace myocuhub\Listeners;

use Auth;
use DateTime;
use Event;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use myocuhub\Events\AppointmentScheduled;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\User;

class SendConfirmPatientAppointmentSMS
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
     * @param  AppointmentScheduled  $event
     * @return void
     */
    public function handle(AppointmentScheduled $event)
    {
        $request = $event->getRequest();
        $appointment = $event->getAppointment();
        
        $appt = [];

        $practice = Practice::find($appointment->practice_id);
        $loggedInUser = Auth::user();
        $network = User::getNetwork($loggedInUser->id);
        $patient = Patient::find($appointment->patient_id);
        $provider = User::find($appointment->provider_id);
        $location = PracticeLocation::find($appointment->location_id);
        $appointmentType = $request->input('appointment_type');
        $apptStartdate = new DateTime($appointment->start_datetime);

        $appt = [
            'user_name' => $loggedInUser->name ?: '',
            'user_network' => $network->name ?: '',
            'user_email' => $loggedInUser->email ?: '',
            'user_phone' => $loggedInUser->cellphone ?: '',
            'appt_type' => $appointmentType ?: '',
            'provider_name' => $provider->title.' '.$provider->firstname.' '.$provider->lastname,
            'location_name' => $location->locationname ?: '',
            'location_address' => ($location->addressline1 ?: '') . ', ' . ($location->addressline2 ?: '') . ', ' . ($location->city ?: '') . ', ' . ($location->state ?: '') . ', ' . ($location->zip ?: ''),
            'practice_name' => $practice->name  ?: '',
            'appt_startdate' => $apptStartdate->format('F d, Y'),
            'appt_starttime' => $apptStartdate->format('h i A'),
			'patient_name' => $patient->title.' '.$patient->firstname.' '.$patient->lastname,
        ];

        $message = $appt['patient_name'] . ', Your appointment has been scheduled for ' . $appt['appt_type'] . ' on ' . $appt['appt_startdate'] . ' at ' . $appt['appt_starttime'] . ' with ' . $appt['provider_name'] . ', ' . $appt['practice_name'] . ', located at'. $appt['location_name'] . ', ' . $appt['location_address'] . '. To know more you can call ' . $appt['user_name'] . ' at ' . $appt['user_phone'];

        $attr = [
            'patient_id' => $patient->id,
            'patient_name' => $patient->title.' '.$patient->firstname.' '.$patient->lastname,
            'phone' => $patient->getPhone(),
            'message' => $message,
        ];

        $this->sendPatientSMS($attr);
    }

    public function sendPatientSMS($attr)
    {

        if($attr['phone'] == null || $attr['phone'] == ''){
            reutn
        }

        try {
            /**
             * Send SMS
             */
            $message = Sms::send($attr['phone'], $attr['message']);

            return true;
        } catch (Exception $e) {
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
