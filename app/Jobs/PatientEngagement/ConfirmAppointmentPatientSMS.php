<?php

namespace myocuhub\Jobs\PatientEngagement;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;
use myocuhub\Patient;

class ConfirmAppointmentPatientSMS extends PatientEngagement implements ShouldQueue
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
        $this->setType('sms');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(policy($this->patient)->validPhone($this->patient)) {
            $phone = $this->patient->getPhone();
            $template = MessageTemplate::getTemplate($this->getType(), $this->getStage(), session('network-id'));
            $message = MessageTemplate::prepareMessage($attr, $template);
            sendSMS($phone, $message);
        }
    }

    public function sendSMS($phone, $message)
    {
        try {
            $message = Sms::send($phone, $message);
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
