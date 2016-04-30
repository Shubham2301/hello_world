<?php

namespace myocuhub\Events;

use myocuhub\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use myocuhub\Models\AuditLog;
use Auth;

class MakeAuditEntry extends Event
{
    use SerializesModels;

    public $audit;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($action, $description = NULL, $filename = NULL, $ip = NULL, $userId = NULL)
    {
        $audit = new AuditLog;

         if($user = Auth::user()){
            $audit->user_id = $user->id;
         }
         else{
            $audit->user_id = $userId;
         }
        $audit->action = $action;
        $audit->description = $description;
        $audit->filename = $filename;
        $audit->ip = $ip;

        $audit->save();
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
