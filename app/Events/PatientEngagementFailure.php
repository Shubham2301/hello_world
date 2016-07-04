<?php

namespace myocuhub\Events;

use myocuhub\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PatientEngagementFailure extends Event
{
    use SerializesModels;

    public function __construct()
    {
        
    }

}
