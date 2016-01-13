<?php

namespace myocuhub\Services\FourPatientCare;

use myocuhub\Services\ArrayToXML\ArrayToXML;

class FourPatientCare
{
    protected $accessID;
    protected $securityCode;

    public function __construct(){
        $this->accessID  = 2;
        $this->securityCode  = 5763432;
    }

    public function getApptTypes($input){
        $input['@attributes'] = ['xmlns' => 'http://WebScheduling.4PatientCare.Com/'];
        $input['AccessID'] = $this->accessID;
        $input['SecurityCode'] = $this->securityCode;

        $soapRequest = ArrayToXML::createXML('RequestApptInsert', $input);
        dd($soapRequest);

        /*
            Make API call to 4PC
            SOAP request payload : $soapRequest
            SOAP response payload : $soapResponse
        */
    }

    public function getOpenApptSlots($input){
        $input['@attributes'] = ['xmlns' => 'http://WebScheduling.4PatientCare.Com/'];
        $input['AccessID'] = $this->accessID;
        $input['SecurityCode'] = $this->securityCode;

        $soapRequest = ArrayToXML::createXML('RequestApptInsert', $input);
        dd($soapRequest);

        /*
            Make API call to 4PC
            SOAP request payload : $soapRequest
            SOAP response payload : $soapResponse
        */
    }

    public function requestApptInsert($input){
        $input['@attributes'] = ['xmlns' => 'http://WebScheduling.4PatientCare.Com/'];
        $input['AccessID'] = $this->accessID;
        $input['SecurityCode'] = $this->securityCode;

        $soapRequest = ArrayToXML::createXML('RequestApptInsert', $input);
        dd($soapRequest);

        /*
            Make API call to 4PC
            SOAP request payload : $soapRequest
            SOAP response payload : $soapResponse
        */
    }
}
