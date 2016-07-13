<?php

namespace myocuhub\Events\Patient;

use myocuhub\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PatientRecordCreation extends Event
{
    use SerializesModels;
  
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $action = 'New Patient Record Created' ;
        $discription = 'New Patient Record Created for Patient id '.$data['patient_id']. ' with template id '.$data['template_id'];
        $this->setAction($action);
        $this->setDescription($discription);
        $this->setIp($data['ip']);
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
