<?php

namespace myocuhub\Jobs\PatientEngagement;

use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\User;

class ConfirmAppointmentPatientMail extends PatientEngagement implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Patient $patient, Appointment $appt)
    {
        //dd('asdasd');
	$this->setPatient($patient);
        $this->setAppt($appt);
        $this->setStage('confirm_appointment');
        $this->setType('email');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	$appointment = $this->getAppt();
        $appt = [];
	
        $practice = Practice::find($appointment->practice_id);
        $loggedInUser = Auth::user();
        $network = User::getNetwork($loggedInUser->id);
        $patient = Patient::find($appointment->patient_id);
        $provider = User::find($appointment->provider_id);
        $location = PracticeLocation::find($appointment->location_id);
        $appointmentType = $appointment->appointmenttype;
        $appointmentTypeKey = $appointment->appointmenttype_key;
        $apptStartdate = new DateTime($appointment->start_datetime);
        $patientDob = new DateTime($patient->birthdate);

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
            'practice_phone' => $location->phone  ?: '',
            'appt_startdate' => $apptStartdate->format('F d, Y'),
            'appt_starttime' => $apptStartdate->format('h i A'),
            'patient_id' => $patient->id  ?: '',
            'patient_name' => $patient->title.' '.$patient->firstname.' '.$patient->lastname,
            'patient_email' => $patient->email  ?: '',
        ];

        $attr = [
            'from' => [
                'name' => config('constants.support.email_name'),
                'email' => config('constants.support.email_id'),
            ],
            'to' => [
                'name' => $patient->getName(),
                'email' => $patient->email,
            ],
            'subject' => config('constants.message_views.request_appointment_patient.subject'),
            'body' =>'',
            'view' => config('constants.message_views.request_appointment_patient.view'),
            'attr' => $appt,
        ];
        $this->sendEmail($attr);
    }
}
