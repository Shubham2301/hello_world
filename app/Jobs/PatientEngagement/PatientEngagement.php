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

    public function __construct(Appointment $appt)
    {
        $this->appt = $appt;
        $this->stage = 'post_appointment';
        $this->patient = Patient::find($this->appt->patient_id);
    }

    public function getContent(){
        return MessageTemplate::getTemplate($this->getType(), $this->getStage(), $this->getPatient()->network()->id);
    }

    /**
     * Gets the value of appt.
     *
     * @return mixed
     */
    public function getAppt()
    {
        return $this->appt;
    }

    /**
     * Sets the value of appt.
     *
     * @param mixed $appt the appt
     *
     * @return self
     */
    protected function setAppt($appt)
    {
        $this->appt = $appt;

        return $this;
    }

    /**
     * Gets the value of type.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param mixed $type the type
     *
     * @return self
     */
    protected function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the value of stage.
     *
     * @return mixed
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * Sets the value of stage.
     *
     * @param mixed $stage the stage
     *
     * @return self
     */
    protected function setStage($stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * Gets the value of patient.
     *
     * @return mixed
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * Sets the value of patient.
     *
     * @param mixed $patient the patient
     *
     * @return self
     */
    protected function setPatient(Patient $patient)
    {
        $this->patient = $patient;

        return $this;
    }
}