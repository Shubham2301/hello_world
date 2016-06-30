<?php

namespace myocuhub\Jobs\PatientEngagement;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use myocuhub\Events\PatientEngagementFailure;
use myocuhub\Events\PatientEngagementSuccess;
use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;

class PostAppointmentPatientMail extends PatientEngagement implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public function __construct(Appointment $appt)
    {
        parent::__construct($appt);
        $this->setType('mail');
    }

    public function handle()
    {
        
        $patient = $this->getPatient();

        $to = [
            'name' => $patient->firstname . ' ' . $patient->lastname,
            'email' => $patient->email
        ];

        $content = $this->getContent();
        $message_config = 'constants.message_views.post_appointment_patient';
        $subject = config("$message_config.subject");

        try {
            $mail = Mail::send(config("$message_config.view"), ['content' => $content, 'to' => $to], function ($m) use ($to, $subject) {
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to($to['email'], $to['name'])->subject($subject);
            });
            if($mail->getStatusCode() == "200"){
                $engaged = new PatientEngagementSuccess();
                $engaged->setAction('Ocuhub Scheduler sent Post Appointment mail to : ' . $to['name'] . ' ' . $to['email']);
                event($engaged);
            }
        } catch (Exception $e) {
            Log::error($e);
            $engaged = new PatientEngagementFailure();
            $engaged->setAction('Ocuhub Scheduler sent Post Appointment mail to : ' . $to['name'] . ' ' . $to['email']);
            $engaged->setDescription($e->getMessage());
            event($engaged);
        }
    }
}
