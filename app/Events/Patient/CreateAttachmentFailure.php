<?php

namespace myocuhub\Events\Patient;

use myocuhub\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateAttachmentFailure extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($attr)
    {
        $attr['action'] = 'Application Exception in fetching Files for  patient ID '.$attr['patientID'];
        parent::__construct($attr);
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
}
