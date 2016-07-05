<?php

namespace myocuhub\Jobs\PatientEngagement;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;
use myocuhub\Patient;

class ConfirmAppointmentPatientMail extends PatientEngagement implements ShouldQueue
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
        $this->setType('mail');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
