<?php

namespace myocuhub\Jobs\PatientEngagement;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use myocuhub\Facades\Sms;
use myocuhub\Jobs\Job;
use myocuhub\Patient;

class RequestAppointmentPatientSMS extends PatientEngagement implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $message;

    public function __construct(Patient $patient, $message)
    {
        $this->setPatient($patient);
        $this->setStage('request_appointment');
        $this->setType('sms');
        $this->message = $message;
    }

    public function handle()
    {
        $patient = $this->getPatient();
        $attr['phone'] = $patient->getPhone();
        $attr['name'] = $patient->firstname.' '.$patient->lastname;
        $attr['message'] = $this->message;
        $this->sendRequest($attr);
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
