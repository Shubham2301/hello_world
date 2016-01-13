<?php

namespace myocuhub\Services\FourPatientCare;

class FourPatientCare
{
    protected $accessID;
    protected $securityCode;

    public function __construct(){
        $this->accessID  = 2;
        $this->securityCode  = 5763432;
    }

    public function getApptTypes($input){
        $output = array();

        return $output;
    }

    public function getOpenApptSlots($input){
        $output = array();

        return $output;
    }

    public function requestApptInsert($input){
        $output = array();



        return $output;
    }
}
