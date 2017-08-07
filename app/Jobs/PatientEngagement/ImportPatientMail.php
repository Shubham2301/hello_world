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

class ImportPatientMail extends PatientEngagement implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $template;

    public function __construct(Patient $patient, $template)
    {
        $this->setPatient($patient);
        $this->setStage('post_import');
        $this->setType('email');
        $this->template = $template;
    }

    public function handle()
    {
        $patient = $this->getPatient();
        $template = (new MandrillService)->templateInfo($this->template);
        $attr = [
            'from' => [
                'name' => config('constants.support.email_name'),
                'email' => config('constants.support.email_id'),
            ],
            'to' => [
                'name' => $patient->getName(),
                'email' => $patient->email,
            ],

            'subject' => '',
            'template' => $template['slug'],
            'vars' => [],
            'attachments' => []
        ];
        $this->sendTemplate($attr);
    }

}
