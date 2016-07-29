<?php

namespace myocuhub\Jobs\PatientEngagement;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Jobs\Job;
use myocuhub\Patient;

class RequestAppointmentPatientMail extends PatientEngagement implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $message;

    public function __construct(Patient $patient, $message)
    {
        $this->setPatient($patient);
        $this->setStage('request_appointment');
        $this->setType('email');
        $this->message = $message;
    }

    public function handle()
    {

        $user = Auth::user();

        $attr = [
            'email' => $this->getPatient()->email,
            'name' => $this->getPatient()->getName(),
            'sent_by_name' => $user->name,
            'sent_by_email'=> $user->email,
            'network_id' => session('network-id'),
            'message' => $this->message,
        ];

        if($attr['message'] == ''){
            return;
        }

        $this->sendRequest($attr);

    }

    private function sendRequest($attr){

        if(Validator::make($attr, ['email' => 'email'])->fails()){
            return false;
        }

        try {
            $mailToProvider = Mail::send('emails.appt-request-patient', ['data' => $attr], function ($m) use ($attr) {
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to($attr['email'], $attr['name'])->subject('Request for Appointment');
            });
            return true;
        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in sending Appointment Request email to patient '. $data['email'];
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return false;
        }
        return true;
    }
}
