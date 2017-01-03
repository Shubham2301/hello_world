<?php

namespace myocuhub\Jobs\Onboarding;

use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use myocuhub\Jobs\Job;
use myocuhub\Models\Practice;
use myocuhub\Services\MandrillService\MandrillService;

class SendPracticeOnboardingEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $practice;
    protected $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Practice $practice, $message)
    {
        $this->practice = $practice;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailDate = new DateTime();
        $template = (new MandrillService)->templateInfo('contact-patient-template');
        $attr = [
                'from' => [
                    'name' => config('constants.support.email_name'),
                    'email' => config('constants.support.email_id'),
                ],
                'to' => [
                    'name' => $this->practice->name,
                    'email' => $this->practice->email,
                ],
                'subject' => 'Add Location information to your practice',
                'template' => $template['slug'],
                'vars' => [
                        [
                            'name' => 'patientname',
                            'content' => $this->practice->name
                        ],
                        [
                            'name' => 'contactmessage',
                            'content' => $this->message
                        ],
                        [
                            'name' => 'sendername',
                            'content' => config('constants.support.email_name')
                        ],
                        [
                            'name' => 'senderemail',
                            'content' => config('constants.support.email_id')
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

        $template = (new MandrillService)->sendTemplate($attr);
    }
}
