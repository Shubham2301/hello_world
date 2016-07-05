<?php

namespace myocuhub\Jobs\PatientEngagement;

use myocuhub\Jobs\Job;
use myocuhub\Models\Appointment;
use myocuhub\Models\MessageTemplate;
use myocuhub\Patient;

class PatientEngagement extends Job{

	protected $appt;
    protected $type;
    protected $stage;
    protected $patient;

    public function __construct()
    {
        
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
}