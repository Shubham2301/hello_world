<?php

namespace myocuhub\Jobs\PatientEngagement;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use myocuhub\Jobs\Job;
use myocuhub\Patient;

class RequestAppointmentPatientSMS extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $message;

    public function __construct(Patient $patient)
    {
        $this->setPatient($patient);
        $this->setStage('request_appointment');
        $this->setType('sms');
        $this->message = $message;
    }

    public function handle()
    {
    
    }

    public function sendRequest($attr){
        try {
            $message = Sms::send($attr['phone'], $attr['message']);
            return true;
        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in sending Appointment Request SMS to patient '. $attr['name'].' on '. $attr['phone'];
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return false;
        }
        return true;
    }
}
