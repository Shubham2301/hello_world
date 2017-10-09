<?php

namespace myocuhub\Jobs\PatientEngagement;

use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
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

        $patient = Patient::find($appointment->patient_id);

        if ($patient == null) {
            return false;
        }

        if ($patient->email == null || $patient->email == '') {
            return false;
        }

        $practice = Practice::find($appointment->practice_id);
        $loggedInUser = Auth::user();
        $network = $loggedInUser->userNetwork->first()->network;
        $patient = Patient::find($appointment->patient_id);
        $provider = User::find($appointment->provider_id);
        $location = PracticeLocation::find($appointment->location_id);
        $appointmentType = $appointment->appointmenttype;
        $appointmentTypeKey = $appointment->appointmenttype_key;
        $apptStartdate = new DateTime($appointment->start_datetime);
        $patientDob = new DateTime($patient->birthdate);

        $practiceName = $practice->name ?: '';
        $description = 'Thank you for your recent appointment request. Please remember final confirmation of the appointment will come from the practice. ' . (($location->latitude && $location->longitude) ? '\n\nTo ensure you arrive at the appointment easily, you can use the link below: \nhttp://maps.google.com?q=' . $location->latitude . ',' . $location->longitude : '') . '\n\nThank you. \n\nTo cancel or reschedule this appointment please email at '. $loggedInUser->email ?: 'support@illumacc.com';
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
                'content' => ($location->addressline1 ?: '') . ', ' . ($location->city ?: '') . ', ' . ($location->state ?: '') . ', ' . ($location->zip ?: ''),
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

        $patient_careconsole = Careconsole::where('patient_id', $patient->id)->with('importHistory')->first();
        $patient_network = $patient_careconsole->importHistory->network_id;

        $config_template_path = 'network.patient_appointment_email_template.' . $patient_network;
        $config_subject_path = 'network.patient_appointment_email_subject.' . $patient_network;

        $network_email_template = config($config_template_path, 'Email to patients');
        $network_email_subject = config($config_subject_path, 'Appointment has been scheduled');

        $template = (new MandrillService)->templateInfo($network_email_template);

        if ($loggedInUser->email) {
            $vars[] = [
                'name' => 'USEREMAIL',
                'content' => $loggedInUser->email,
            ];
        }

        if ($loggedInUser->cellphone) {
            $vars[] = [
                'name' => 'USERPHONE',
                'content' => $loggedInUser->cellphone,
            ];
        }

        if (Auth::check() && session('user-level') == 2) {
            $vars[] = [
                'name' => 'NETWORKLOGO',
                'content' => config('constants.production_url') . '/images/networks/network_' . $loggedInUser->userNetwork->first()->network_id . '.png',
            ];
        }

        if ($location->latitude) {
            $vars[] = [
                'name' => 'SHOWINMAP',
                'content' => config('constants.google_map_url').'/'.$location->latitude.','.$location->longitude,
            ];
        }

        $iCal = $this->createICal([
            'event_name' => $appointmentType ? $appointmentType.' Appointment Confirmation' : 'Appointment Confirmation',

            'date_start' => new DateTime($appointment->start_datetime),

            'date_end' => (new DateTime($appointment->start_datetime))->modify("+30 minutes"),

            'user_name' => $loggedInUser->name,

            'user_email' => $loggedInUser->email,

            'provider_name' => $provider->title . ' ' . $provider->firstname . ' ' . $provider->lastname,

            'provider_email' => $provider->email,

            'location_email' => $location->email,

            'patient_name' => $patient->getName(),

            'patient_email' => $patient->email,

            'address' => config('constants.google_map_url').'/'.$location->latitude.','.$location->longitude,

            'timezone' => ($patient->timezone)?$patient->timezone->utc:config('constants.default_timezone'),

            'summary' => ($practice->name ?: '') . ', ' . ($location->addressline1 ?: '') . ', ' . ($location->city ?: '') . ', ' . ($location->state ?: '') . ', ' . ($location->zip ?: '') . ', ' .($location->phone ?: ''),

            'description' => $description,

        ]);

        if ($iCal) {
            $vars[] = [
                 'name' => 'CALENDER',
                 'content' => $iCal->googleCalenderLink(),
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
            'subject' => $network_email_subject,
            'template' => $template['slug'],
            'vars' => $vars,
            'attachments' => [
                 [
                     'content' => base64_encode($iCal->getICAL()),
                     'type' => "text/calendar",
                     'name' => 'appointment.ics',
                 ]
            ]
        ];

        $this->sendTemplate($attr);
    }
}
