<?php

namespace myocuhub\Jobs\PatientEngagement;

use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\Services\MandrillService\MandrillService;
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

        $vars = [
            [
                'name' => 'USERNAME',
                'content' => $loggedInUser->name ?: '',
            ],
            [
                'name' => 'USERNETWORK',
                'content' => $network->name ?: '',
            ],
            [
                'name' => 'APPTTYPE',
                'content' => $appointmentType ?: '',
            ],
            [
                'name' => 'PROVIDERNAME',
                'content' => $provider->title . ' ' . $provider->firstname . ' ' . $provider->lastname,
            ],
            [
                'name' => 'LOCATIONNAME',
                'content' => $location->locationname ?: '',
            ],
            [
                'name' => 'LOCATIONADDRESS',
                'content' => ($location->addressline1 ?: '') . ', ' . ($location->addressline2 ?: '') . ', ' . ($location->city ?: '') . ', ' . ($location->state ?: '') . ', ' . ($location->zip ?: ''),
            ],
            [
                'name' => 'PRACTICENAME',
                'content' => $practice->name ?: '',
            ],
            [
                'name' => 'PRACTICEPHONE',
                'content' => $location->phone ?: '',
            ],
            [
                'name' => 'APPTSTARTDATE',
                'content' => $apptStartdate->format('F d, Y'),
            ],
            [
                'name' => 'APPTSTARTTIME',
                'content' => $apptStartdate->format('h i A'),
            ],
            [
                'name' => 'PATIENTID',
                'content' => $patient->id ?: '',
            ],
            [
                'name' => 'PATIENTNAME',
                'content' => $patient->title . ' ' . $patient->firstname . ' ' . $patient->lastname,
            ],
            [
                'name' => 'PATIENTEMAIL',
                'content' => $patient->email ?: '',
            ],
        ];

        $template = (new MandrillService)->templateInfo('Email to patients');

        if ($location->special_instructions) {
            $vars[] = [
                'name' => 'SPECIALINSTRUCTIONS',
                'content' => $location->special_instructions,
            ];
        }

        if ($loggedInUser->email) {
            $vars[] = [
                'name' => 'USEREMAIL',
                'content' => $loggedInUser->email,
            ];
        }

        if ($loggedInUser->cellphone) {
            $vars[] = [
                'name' => 'USEREPHONE',
                'content' => $loggedInUser->cellphone,
            ];
        }

        if (Auth::check() && session('user-level') == 2) {
            $vars[] = [
                'name' => 'NETWORKLOGO',
                'content' => config('constants.production_url') . '/images/networks/network_' . Auth::user()->getNetwork(Auth::user()->id)->id . '.png',
            ];
        }

        $attr = [
            'from' => [
                'name' => config('constants.support.email_name'),
                'email' => config('constants.support.email_id'),
            ],
            'to' => [
                'name' => $patient->getName(),
                'email' => $patient->email,
            ],
            'subject' => 'Appointment has been scheduled',
            'template' => $template['slug'],
            'vars' => $vars,
        ];

        $this->sendTemplate($attr);

    }
}
