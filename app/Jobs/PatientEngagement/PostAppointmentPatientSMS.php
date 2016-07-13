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
        $this->setAppt($appt);
        $this->setPatient(Patient::find($this->appt->patient_id));
        $this->setStage('post_appointment');
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

        $this->sendSMS($to['phone'], $message);
    }
}
