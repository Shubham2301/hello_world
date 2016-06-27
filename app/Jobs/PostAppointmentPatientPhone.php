<?php

namespace myocuhub\Jobs;

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
        $this->makeCall($appt);
    }

    public function makeCall($appt){
        //code here
    }
}
