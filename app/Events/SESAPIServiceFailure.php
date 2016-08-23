<?php

namespace myocuhub\Events;

use Illuminate\Queue\SerializesModels;
use myocuhub\Events\Event;

class SESAPIServiceFailure extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($attr)
    {
        $attr['action'] = 'SES Service Exception for action ' . $attr['actionName'];
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
