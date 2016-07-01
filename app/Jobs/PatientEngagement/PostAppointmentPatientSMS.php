<?php

namespace myocuhub\Jobs\PatientEngagement;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use myocuhub\Events\PatientEngagementFailure;
use myocuhub\Events\PatientEngagementSuccess;
use myocuhub\Facades\Sms;
use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;

class PostAppointmentPatientSMS extends PatientEngagement implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public function __construct(Appointment $appt)
    {
        parent::__construct($appt);
        $this->setType('sms');
    }

    public function handle()
    {
        $patient = $this->getPatient();
        $to = [
            'name' => $patient->getName(),
            'phone' => $patient->cellphone
        ];

        $content = $this->getContent();
        $message = Sms::prepare('sms.master', $content);

        try {
            Sms::send($to['phone'], $message);
            $engaged = new PatientEngagementSuccess();
            $engaged->setAction('Ocuhub Scheduler sent Post Appointment SMS to : ' . $to['name'] . ' at '.$to['phone']);
            event($engaged);
        } catch (Exception $e) {
            Log::error($e);
            $engaged = new PatientEngagementFailure();
            $engaged->setAction('Ocuhub Scheduler failed to send Post Appointment SMS to : ' . $to['name'] . ' at '.$to['phone']);
            $engaged->setDescription($e->getMessage());
            event($engaged);
        }
    }
}
