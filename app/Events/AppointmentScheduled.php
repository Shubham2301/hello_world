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

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request, Appointment $appointment)
    {
        $this->request = $request;
        $this->appointment = $appointment;
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

}
