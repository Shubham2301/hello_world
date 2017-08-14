<?php

namespace myocuhub\Listeners;

use DateTime;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Log;
use myocuhub\Events\PracticeFirstAppointment;
use myocuhub\Facades\SES;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\Services\MandrillService\MandrillService;
use myocuhub\User;

class SendFirstAppointmentNotification
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
     * @param  PracticeFirstAppointment  $event
     * @return void
     */
    public function handle(PracticeFirstAppointment $event)
    {
        $appointment = $event->getAppointment();
        $appt = [];


        $practice = Practice::find($appointment->practice_id);
        $loggedInUser = Auth::user();
        $network = $loggedInUser->userNetwork->first()->network;
        $patient = Patient::find($appointment->patient_id);
        $provider = User::find($appointment->provider_id);
        $location = PracticeLocation::find($appointment->location_id);
        $patientInsurance = PatientInsurance::where('patient_id', $appointment->patient_id)->first();
        $apptStartdate = new DateTime($appointment->start_datetime);
        $patientDob = new DateTime($patient->birthdate);

        $appt = [
            'user_name' => $loggedInUser->name ?: '',
            'user_network' => $network->name ?: '',
            'user_email' => $loggedInUser->email ?: '',
            'user_phone' => $loggedInUser->cellphone ?: '',
            'appt_type' => '',
            'provider_name' => $provider ? $provider->title . ' ' . $provider->firstname . ' ' . $provider->lastname : 'N/A',
            'location_name' => $location ? $location->locationname : '',
            'location_address' => $location ? ($location->addressline1 ?: '') . ', ' . ($location->city ?: '') . ', ' . ($location->state ?: '') . ', ' . ($location->zip ?: '') : '',
            'practice_name' => $practice ? $practice->name : '',
            'practice_phone' => $location ? $location->phone : '',
            'appt_startdate' => $apptStartdate->format('F d, Y'),
            'appt_starttime' => $apptStartdate->format('h i A'),
            'patient_id' => $patient->id ?: '',
            'patient_name' => $patient->title . ' ' . $patient->firstname . ' ' . $patient->lastname,
            'patient_email' => $patient->email ?: '',
            'patient_phone' => $patient->cellphone . ', ' . $patient->workphone . ', ' . $patient->homephone,
            'patient_ssn' => $patient->lastfourssn ?: '',
            'patient_address' => ($patient->addressline1 ? $patient->addressline1 . ', ' : '') . ($patient->addressline2 ? $patient->addressline2 . ', ' : '') . ($patient->city ? $patient->city . ', ' : '') . ($patient->state ? $patient->state . ', ' : '') . ($patient->zip ? $patient->zip : ''),
            'patient_dob' => ($patient->birthdate && $patient->birthdate != '0000-00-00 00:00:00') ? $patientDob->format('F d, Y') : '',
            'insurance_carrier' => '',
            'subscriber_name' => '',
            'subscriber_id' => '',
            'subscriber_birthdate' => '',
            'insurance_group_no' => '',
            'subscriber_relation' => '',
            'patient_pcp' => $patient->pcp ?: '',
        ];

        if ($patientInsurance != null) {
            $subscriberDob = new DateTime($patientInsurance->subscriber_birthdate);
            $appt['insurance_carrier'] = $patientInsurance->insurance_carrier ?: '';
            $appt['subscriber_name'] = $patientInsurance->subscriber_name ?: '';
            $appt['subscriber_id'] = $patientInsurance->subscriber_id ?: '';
            $appt['subscriber_birthdate'] = ($patientInsurance->subscriber_birthdate && $patientInsurance->subscriber_birthdate != '0000-00-00 00:00:00') ? $subscriberDob->format('F d, Y') : '';
            $appt['insurance_group_no'] = $patientInsurance->insurance_group_no ?: '';
            $appt['subscriber_relation'] = $patientInsurance->subscriber_relation ?: '';
        }

        $this->sendNotificationMail($appt);
    }

    public function sendNotificationMail($appt)
    {
        $attr = [
            'from' => [
                'name' => config('constants.support.ses.email.display_name'),
                'email' => config('constants.support.ses.email.id'),
            ],
            'to' => [
                'name' => config('constants.message_views.first_appointment_notification.name'),
                'email' => config('constants.message_views.first_appointment_notification.email'),
            ],
            'subject' => config('constants.message_views.first_appointment_notification.subject') . ' at ' . $appt['practice_name'],
            'body' => '',
            'view' => config('constants.message_views.first_appointment_notification.view'),
            'appt' => $appt,
            'attachments' => [],
        ];

        try {
            return SES::send($attr);
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
