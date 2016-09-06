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

    public $message, $subject;

    public function __construct(Patient $patient, $message, $subject)
    {
        $this->setPatient($patient);
        $this->setStage('request_for_appointment');
        $this->setType('email');
        $this->message = $message;
        $this->subject = $subject;
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
            'subject' => $this->subject,
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
            ],
            'attachments' => [],
        ];

        $this->sendTemplate($attr);
    }
}
