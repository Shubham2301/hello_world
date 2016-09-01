<?php

namespace myocuhub\Jobs\PatientEngagement;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use myocuhub\Events\PatientEngagementFailure;
use myocuhub\Events\PatientEngagementSuccess;
use myocuhub\Facades\Sms;
use myocuhub\Jobs\Job;
use Mandrill;
use myocuhub\Models\Appointment;
use myocuhub\Models\MessageTemplate;
use myocuhub\Patient;
use myocuhub\Services\MandrillService\MandrillService;
use myocuhub\Services\ICalService;

class PatientEngagement extends Job{

	protected $appt;
    protected $type;
    protected $stage;
    protected $patient;

    public function __construct()
    {

    }

    public function sendSMS($phone, $message)
    {
        try {
            $message = Sms::send($phone, $message);
            $this->successfulResponse([
                    'at' => $phone
                ]);
        } catch (Exception $e) {
            Log::error($e);
            $this->failedResponse([
                    'at' => $phone,
                    'error' => $e->getMessage()
                ]);
        }
    }

    public function sendEmail($attr)
    {
        if(Validator::make($attr['to'], ['email' => 'email'])->fails()){
            return false;
        }

        try {
            $mailToProvider = Mail::send($attr['view'], ['attr' => $attr['attr']], function ($m) use ($attr) {
                $m->from($attr['from']['email'], $attr['from']['name']);
                $m->to($attr['to']['email'], $attr['to']['name'])->subject($attr['subject']);
            });
            $this->successfulResponse([
                    'at' => $attr['to']['email']
                ]);
            return true;
        } catch (Exception $e) {
            Log::error($e);
            $this->failedResponse([
                    'at' => $attr['to']['email'],
                    'error' => $e->getMessage()
                ]);
        }
        return false;
    }

    public function sendTemplate($attr){
        $response = (new MandrillService)->sendTemplate($attr);
        if($response) {
            $this->successfulResponse([
                    'at' => $attr['to']['email']
                ]);
        } else {
            $this->failedResponse([
                    'at' => $attr['to']['email'],
                    'error' => $e->getMessage()
                ]);
        }
        return $response;
    }

    public function failedResponse($attr){
        event(new PatientEngagementFailure([
                'action' =>  'Application Exception in engaging patient : ' . $this->getPatient()->getName() . ' by ' . $this->getType() . ' on ' . $this->getStage() . ' at ' . $attr['at'],
                'description' => $attr['error']
                ]));
    }

    public function successfulResponse($attr){
        event(new PatientEngagementSuccess([
                'action' =>  'Engaged patient : ' . $this->getPatient()->getName() . ' by ' . $this->getType() . ' on ' . $this->getStage() . ' at ' . $attr['at'],
                ]));
    }

    public function getContent(){
        return MessageTemplate::getTemplate($this->getType(), $this->getStage(), $this->getPatient()->network()->id);
    }

    public function getAppt()
    {
        return $this->appt;
    }

    protected function setAppt($appt)
    {
        $this->appt = $appt;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    protected function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getStage()
    {
        return $this->stage;
    }

    protected function setStage($stage)
    {
        $this->stage = $stage;

        return $this;
    }

    public function getPatient()
    {
        return $this->patient;
    }

    protected function setPatient(Patient $patient)
    {
        $this->patient = $patient;

        return $this;
    }

    public function createICal($attr){
        return (new ICalService($attr));
    }
}
