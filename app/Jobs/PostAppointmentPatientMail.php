<?php

namespace myocuhub\Jobs;

use Exception;
use myocuhub\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostAppointmentPatientMail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $appt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appt)
    {
        $appt = $this->appt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendMail($appt);
    }

    public function sendMail($appt){
        
        if(Validator::make($appt, ['email' => 'email'])->fails()){
            $this->recordFailedStatus();
        }

        try {
            $mailToProvider = Mail::send('patient-engagement.emails.post-appt', ['appt' => $appt], function ($m) use ($appt) {
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to($appt['email'], $appt['name'])->subject($appt['subject']);
            });
        } catch (Exception $e) {
            Log::error($e);
            $this->recordFailedStatus();
        }

    }

    public function recordFailedStatus(){
        
    }
}
