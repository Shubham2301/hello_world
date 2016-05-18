<?php

namespace myocuhub\Listeners;

use Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use myocuhub\Events\AppointmentScheduled;
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
        
        $patientInsurance = PatientInsurance::where('patient_id', $request->input('patient_id'));

        $practice = Practice::find($appointment->practice_id);
        $appt['practice_name'] = $practice->name;

        $network = User::getNetwork($loggedInUser->id);
        $loggedInUser = Auth::user();
        $patient = Patient::find();
        $provider = User::find($appointment->provider_id);
        $location = PracticeLocation::find($appointment->location_id);
        $apptStartdate = new DateTime($appointment->start_datetime);
        $patientDob = new DateTime($patient->birthdate);
        $subscriberDob = new DateTime($patientInsurance->subscriber_birthdate);

        $appt = [
            'user_name' => $loggedInUser->name,
            'user_network' => $network->name,
            'user_email' => $loggedInUser->email,
            'user_phone' => $loggedInUser->cellphone,
            'appt_type' => $appointmentType,
            'provider_name' => $provider->firstname,
            'location_name' => $location->locationname,
            'location_address' => $location->addressline1 . ', ' . $location->addressline2 . ', ' . $location->city . ', ' . $location->state . ', ' . $location->zip,
            'practice_phone' => $location->phone,
            'appt_startdate' => $apptStartdate->format('F d, Y'),
            'appt_starttime' => $apptStartdate->format('h i A'),
            'patient_name' => $patient->firstname,
            'patient_email' => $patient->email,
            'patient_phone' => $patient->cellphone . ', ' . $patient->workphone . ', ' . $patient->homephone,
            'patient_ssn' => $patient->lastfourssn,
            'patient_address' => $patient->addressline1 . ', ' . $patient->addressline2 . ', ' . $patient->city . ', ' . $patient->state . ', ' . $patient->zip,
            'patient_dob' => $patientDob->format('F d, Y'),
            'insurance_carrier' => $patientInsurance->insurance_carrier,
            'subscriber_name' => $patientInsurance->subscriber_name,
            'subscriber_id' => $patientInsurance->subscriber_id,
            'subscriber_birthdate' => $subscriberDob->format('F d, Y'),
            'insurance_group_no' => $patientInsurance->insurance_group_no,
            'subscriber_relation' => $patientInsurance->subscriber_relation,
        ]

        if ($location->email && $location->email != '') {
            $this->sendPatientMail($appt, $patient);
        }

        if ($patient->email && $patient->email != '') {
            $this->sendPatientMail($appt, $patient);
        }
    }

    public function sendPatientMail($appt, $patient){
        try {
            $mailToPatient = Mail::send('emails.appt-confirmation-patient', ['appt' => $appt], function ($m) use ($patient) {
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to($patient->email, $patient->lastname . ', ' . $patient->firstname)->subject('Appointment has been scheduled');
            });
        } catch (Exception $e) {
            $action = 'Appointment Request email not sent to patient '. $patient->email;
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        }
        
    }

    public function sendProviderMail($appt, $location){
        try {
            $mailToProvider = Mail::send('emails.appt-confirmation-provider', ['appt' => $appt], function ($m) use ($location) {
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to($location->email, $location->name)->subject('Request for Appointment');
            });
        } catch (Exception $e) {
            $action = 'Appointment Request email not sent to patient '. $location->email;
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        }
        
    }
}
