<?php

namespace myocuhub\Jobs;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use myocuhub\Facades\Sms;
use myocuhub\Jobs\Job;

class PostAppointmentPatientSMS extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $appt;

    public function __construct($appt)
    {
        $this->appt = $appt;
    }

    public function handle()
    {
        $appt = $this->appt;

        try {
            $message = Sms::prepare($appt, 'patient-engagement.sms.post-appt');
            Sms::send($to, $message);
        } catch (Exception $e) {
            Log::error($e);
            $this->recordFailedStatus();
        }
    }

    public function recordFailedStatus(){
        
    }
}
