<?php

namespace myocuhub\Jobs\Onboarding;

use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use myocuhub\Jobs\Job;
use myocuhub\Models\Practice;
use myocuhub\Services\MandrillService\MandrillService;

class SendOnboardingNotificationEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $practice;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Practice $practice)
    {
        $this->practice = $practice;
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
                    'name' => config('constants.onboarding_notification.email_name'),
                    'email' => config('constants.onboarding_notification.email_id'),
                ],
                'subject' => 'Practice Location Added',
                'template' => $template['slug'],
                'vars' => [
                        [
                            'name' => 'patientname',
                            'content' => config('constants.onboarding_notification.email_name'),
                        ],
                        [
                            'name' => 'contactmessage',
                            'content' => $this->practice->name . ' has added the location using the onboarding forms. Please review using the super admin account to activate the practice locations.'
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
