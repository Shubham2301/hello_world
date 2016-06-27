<?php

namespace myocuhub\Jobs;

use Exception;
use myocuhub\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostAppointmentPatientSMS extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $appt;

    public function __construct($appt)
    {
        $appt = $this->appt;
    }

    public function handle()
    {
        $this->sendSMS($appt);
    }

    public function sendSMS($appt){
        
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
