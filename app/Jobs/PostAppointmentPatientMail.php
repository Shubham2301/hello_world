<?php

namespace myocuhub\Jobs;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;
use myocuhub\Patient;

class PostAppointmentPatientMail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $appt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Appointment $appt)
    {
        $this->appt = $appt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $appt = $this->appt;
        
        $patient = Patient::find($appt->patient_id);

        $to = [
            'name' => $patient->firstname . ' ' . $patient->lastname,
            'email' => $patient->email
        ];

        try {
            $mail = Mail::send('emails.master', ['appt' => $appt, 'to' => $to], function ($m) use ($appt, $to) {
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to($to['email'], $to['name'])->subject($appt['subject']);
            });
            dd($mail);
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
            $this->recordFailedStatus();
        }
    }

    public function recordFailedStatus(){
        
    }
}
