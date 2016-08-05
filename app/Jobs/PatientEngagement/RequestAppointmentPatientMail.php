<?php

namespace myocuhub\Jobs\PatientEngagement;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Jobs\Job;
use myocuhub\Patient;
use myocuhub\Services\MandrillService\MandrillService;

class RequestAppointmentPatientMail extends PatientEngagement implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $message;

    public function __construct(Patient $patient, $message)
    {
        $this->setPatient($patient);
        $this->setStage('request_appointment');
        $this->setType('email');
        $this->message = $message;
    }

    public function handle()
    {

        $user = Auth::user();

        if($this->message == ''){
            return;
        }

        $template = (new MandrillService)->templateInfo('contact-patient-template');

        $attr = [
            'from' => [
                'name' => config('constants.support.email_name'),
                'email' => config('constants.support.email_id'),
            ],
            'to' => [
                'name' => $this->getPatient()->getName(),
                'email' => $this->getPatient()->email,
            ],
            'subject' => 'Request for Appointment',
            'template' => $template['slug'],
            'vars' => [
                    [
                        'name' => 'patientname',
                        'content' => $this->getPatient()->getName()
                    ],
                    [
                        'name' => 'contactmessage',
                        'content' => $this->message
                    ],
                    [
                        'name' => 'sendername',
                        'content' => $user->name
                    ],
                    [
                        'name' => 'senderemail',
                        'content' => $user->email
                    ]
            ]
        ];

        $this->sendTemplate($attr);
    }
}
