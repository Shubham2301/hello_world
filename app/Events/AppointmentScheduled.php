<?php

namespace myocuhub\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use myocuhub\Events\Event;
use myocuhub\Models\Appointment;

class AppointmentScheduled extends Event
{
    use SerializesModels;

    private $request;
    private $appointment;
    private $patientEmailStatus;
    private $providerEmailStatus;
    private $FPCRequestStatus;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request, Appointment $appointment)
    {
        $this->request = $request;
        $this->appointment = $appointment;
        $this->patientEmailStatus = false;
        $this->providerEmailStatus = false;
        //$this->FPCRequestStatus = false;
    }

    /**
     * Gets the value of request.
     *
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Gets the value of appointment.
     *
     * @return mixed
     */
    public function getAppointment()
    {
        return $this->appointment;
    }


    /**
     * Gets the value of patientEmailStatus.
     *
     * @return mixed
     */
    public function getPatientEmailStatus()
    {
        return $this->patientEmailStatus;
    }

    /**
     * Sets the value of patientEmailStatus.
     *
     * @param mixed $patientEmailStatus the patient email status
     *
     * @return self
     */
    public function _setPatientEmailStatus($patientEmailStatus)
    {
        $this->patientEmailStatus = $patientEmailStatus;

        return $this;
    }

    /**
     * Gets the value of ProviderEmailStatus.
     *
     * @return mixed
     */
    public function getProviderEmailStatus()
    {
        return $this->providerEmailStatus;
    }

    /**
     * Sets the value of ProviderEmailStatus.
     *
     * @param mixed $ProviderEmailStatus the provider email status
     *
     * @return self
     */
    public function _setProviderEmailStatus($providerEmailStatus)
    {
        $this->providerEmailStatus = $providerEmailStatus;

        return $this;
    }

    /**
     * Gets the value of FPCRequestStatus.
     *
     * @return mixed
     */
    public function getFPCRequestStatus()
    {
        return $this->FPCRequestStatus;
    }

    /**
     * Sets the value of FPCRequestStatus.
     *
     * @param mixed $FPCRequestStatus the crequest status
     *
     * @return self
     */
    public function _setFPCRequestStatus($FPCRequestStatus)
    {
        $this->FPCRequestStatus = $FPCRequestStatus;

        return $this;
    }
}
