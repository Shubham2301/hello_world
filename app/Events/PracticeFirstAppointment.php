<?php

namespace myocuhub\Events;

use Illuminate\Queue\SerializesModels;
use myocuhub\Events\Event;
use myocuhub\Models\Appointment;

class PracticeFirstAppointment extends Event
{
    use SerializesModels;

    private $appointment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
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
