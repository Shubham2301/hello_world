<?php

namespace myocuhub\Jobs\PatientEngagement;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use myocuhub\Events\PatientEngagementFailure;
use myocuhub\Events\PatientEngagementSuccess;
use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;
use myocuhub\Patient;
use myocuhub\Services\MandrillService\MandrillService;
use DateTime;

class PostAppointmentPatientMail extends PatientEngagement implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public function __construct(Appointment $appt)
    {
        $this->setAppt($appt);
        $this->setPatient(Patient::find($this->appt->patient_id));
        $this->setStage('post_appointment');
        $this->setType('email');
    }

    public function handle()
    {
        $patient = $this->getPatient();

        $message = $this->getContent();
        $subject = $this->getContent('subject');

        if ($message == '') {
            return;
        }

        if ($patient->email == '') {
            return;
        }


        $mailDate = new DateTime();
        $template = (new MandrillService)->templateInfo('contact-patient-template');

        $attr = [
            'from' => [
                'name' => config('constants.support.email_name'),
                'email' => config('constants.support.email_id'),
            ],
            'to' => [
                'name' => $patient->getName('print_format'),
                'email' => $patient->email,
            ],
            'subject' => $subject,
            'template' => $template['slug'],
            'vars' => [
                    [
                        'name' => 'patientname',
                        'content' => $patient->getName('print_format'),
                    ],
                    [
                        'name' => 'contactmessage',
                        'content' => $message,
                    ],
                    [
                        'name' => 'sendername',
                        'content' => config('constants.support.email_name'),
                    ],
                    [
                        'name' => 'senderemail',
                        'content' => config('constants.support.email_id'),
                    ],
                    [
                        'name' => 'maildate',
                        'content' => $mailDate->format('D F d, Y'),
                    ],
                    [
                        'name' => 'mailtime',
                        'content' => $mailDate->format('g:i a'),
                    ]
            ],
            'attachments' => [],
        ];

        $this->sendTemplate($attr);
    }
}
