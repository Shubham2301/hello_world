<?php

namespace myocuhub\Jobs\PatientEngagement;

use myocuhub\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostAppointmentPatientPhone.php extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $appt;

    public function __construct($appt)
    {
        $appt = $this->appt;
    }

    public function handle()
    {
        //code here
    }

}