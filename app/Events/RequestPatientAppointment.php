<?php

namespace myocuhub\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use myocuhub\Events\Event;
use myocuhub\Models\EngagementPreference;

class RequestPatientAppointment extends Event
{
    use SerializesModels;

    private $action;
    private $patientID;
    private $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($patientID, $actions = [], $message = '')
    {
        $this->_setPatientID($patientID);
        $this->_setAction($actions);
        $this->_setMessage($message);
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

    /**
     * Gets the value of action.
     *
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the value of action.
     *
     * @param mixed $action the action
     *
     * @return self
     */
    private function _setAction($action)
    {
        /**
         * If $action is [] then select default engagement preference and add to action
         */
        $this->action = $action;

        return $this;
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
    private function _setPatientID($patientID)
    {
        $this->patientID = $patientID;

        return $this;
    }

    /**
     * Gets the value of message.
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the value of message.
     *
     * @param mixed $message the message
     *
     * @return self
     */
    private function _setMessage($message)
    {
        /**
         * If message is '' then select default message template from MessageTemplate for network
         */
        $this->message = $message;

        return $this;
    }
}
