<?php

namespace myocuhub\Listeners;

use Auth;
use DateTime;
use Event;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Log;
use myocuhub\Events\AppointmentScheduled;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\User;

class SendAppointmentRequestEmail
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
        $patientInsurance = PatientInsurance::where('patient_id', $appointment->patient_id)->first();
        $appointmentType = $request->input('appointment_type');
        $appointmentTypeKey = $request->input('appointment_type_key');
        $apptStartdate = new DateTime($appointment->start_datetime);
        $patientDob = new DateTime($patient->birthdate);
        
        $appt = [
            'user_name' => $loggedInUser->name ?: '',
            'user_network' => $network->name ?: '',
            'user_email' => $loggedInUser->email ?: '',
            'user_phone' => $loggedInUser->cellphone ?: '',
            'appt_type' => $appointmentType ?: '',
            'provider_name' => $provider->firstname ?: '',
            'location_name' => $location->locationname ?: '',
            'location_address' => ($location->addressline1 ?: '') . ', ' . ($location->addressline2 ?: '') . ', ' . ($location->city ?: '') . ', ' . ($location->state ?: '') . ', ' . ($location->zip ?: ''),
            'practice_name' => $practice->name  ?: '',
            'practice_phone' => $location->phone  ?: '',
            'appt_startdate' => $apptStartdate->format('F d, Y'),
            'appt_starttime' => $apptStartdate->format('h i A'),
            'patient_name' => $patient->firstname  ?: '',
            'patient_email' => $patient->email  ?: '',
            'patient_phone' => $patient->cellphone . ', ' . $patient->workphone . ', ' . $patient->homephone,
            'patient_ssn' => $patient->lastfourssn ?: '',
            'patient_address' => ($patient->addressline1 ? $patient->addressline1 . ', ': '')  . ($patient->addressline2 ? $patient->addressline2 . ', ': '') . ($patient->city ? $patient->city . ', ': '') . ($patient->state ? $patient->state . ', ': '') . ($patient->zip ? $patient->zip : ''),
            'patient_dob' => ($patient->birthdate && $patient->birthdate != '0000-00-00 00:00:00') ? $patientDob->format('F d, Y') : '',
            'insurance_carrier' => '',
            'subscriber_name' => '',
            'subscriber_id' => '',
            'subscriber_birthdate' => '',
            'insurance_group_no' => '',
            'subscriber_relation' => '',
        ];

        if ($patientInsurance != null) {
            $subscriberDob = new DateTime($patientInsurance->subscriber_birthdate);
            $appt['insurance_carrier'] =  $patientInsurance->insurance_carrier ?: '';
            $appt['subscriber_name'] = $patientInsurance->subscriber_name ?: '';
            $appt['subscriber_id'] = $patientInsurance->subscriber_id ?: '';
            $appt['subscriber_birthdate'] = ($patientInsurance->subscriber_birthdate && $patientInsurance->subscriber_birthdate != '0000-00-00 00:00:00')? $subscriberDob->format('F d, Y') : '';
            $appt['insurance_group_no'] = $patientInsurance->insurance_group_no ?: '';
            $appt['subscriber_relation'] = $patientInsurance->subscriber_relation ?: '';
        }
        
        if ($location->email && $location->email != '') {
            $this->sendProviderMail($appt, $location);
        }

        if ($patient->email && $patient->email != '') {
            $this->sendPatientMail($appt, $patient);
        }
    }

    public function sendPatientMail($appt, $patient){
        if($patient->email == null || $patient->email == ''){
            return ;
        }
        try {
            $mailToPatient = Mail::send('emails.appt-confirmation-patient', ['appt' => $appt], function ($m) use ($patient) {
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to($patient->email, $patient->lastname . ', ' . $patient->firstname)->subject('Appointment has been scheduled');
            });
        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in sending Appointment Request email not sent to patient '. $patient->email;
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        }
        return ;
    }

    public function sendProviderMail($appt, $location){
        if($location->email == null || $location->email == ''){
            return ;
        }
        try {
            $mailToProvider = Mail::send('emails.appt-confirmation-provider', ['appt' => $appt], function ($m) use ($location) {
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to('asd', $location->name)->subject('Request for Appointment');
            });
        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in sending Appointment Request email not sent to patient '. $location->email;
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        }
        
    }
}
