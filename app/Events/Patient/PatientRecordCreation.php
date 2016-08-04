<?php

namespace myocuhub\Events\Patient;

use myocuhub\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PatientRecordCreation extends Event
{
    use SerializesModels;

    private $contactHistoryID;
    private $patientID;
    private $templateID;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $action = 'New Patient Record Created' ;
        $discription = 'New Patient Record Created for Patient id ' . $data['patient_id'] . ' with template id ' . $data['template_id'];
        $this->setAction($action);
        $this->setDescription($discription);
        $this->setIp($data['ip']);
        $this->setPatientID($data['patient_id']);
        $this->setTemplateID($data['template_id']);

        $this->setContactHistoryID($data['contact_history_id']);

    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    public function getContactHistoryID()
    {
        return $this->contactHistoryID;
    }

    public function setContactHistoryID($id){
        $this->contactHistoryID = $id;
    }

    /**
     * Gets the value of patientID.
     *
     * @return mixed
     */
    public function getPatientID()
    {
        return $this->patientID;
    }

    /**
     * Sets the value of patientID.
     *
     * @param mixed $patientID the patient
     *
     * @return self
     */
    private function setPatientID($patientID)
    {
        $this->patientID = $patientID;

        return $this;
    }

    /**
     * Gets the value of template_id.
     *
     * @return mixed
     */
    public function getTemplateID()
    {
        return $this->template_id;
    }

    /**
     * Sets the value of template_id.
     *
     * @param mixed $template_id the template id
     *
     * @return self
     */
    private function setTemplateID($template_id)
    {
        $this->template_id = $template_id;

        return $this;
    }
}
