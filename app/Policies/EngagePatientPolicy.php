<?php

namespace myocuhub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class EngagePatientPolicy
{
    use HandlesAuthorization;

    protected $patient;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(Patient $patient, $type)
    {
        $this->patient = $patient;
    }

    public function authorized(){
        return $this->patient->canBeEngaged($type, $stage);
    }


}
