<?php

namespace myocuhub\Services\FourPatientCare;

class FourPatientCare
{
    protected $accessID;
    protected $securityCode;
    protected $url;
    protected $host;
    protected $getApptTypesAction;

    public function __construct(){
        $this->accessID  = 2;
        $this->securityCode  = 5763432;
        $this->url = 'http://www.4patientcare.ws/v5dn/partnerwebscheduling.asmx';
        $this->wsdl = 'http://www.4patientcare.ws/v5dn/partnerwebscheduling.asmx?WSDL';
        $this->getApptTypesAction = 'http://WebScheduling.4PatientCare.Com/GetApptTypes';
        $this->getOpenApptSlotsAction = 'http://WebScheduling.4PatientCare.Com/GetOpenApptSlots';
        $this->requestApptInsertAction = 'http://WebScheduling.4PatientCare.Com/RequestApptInsert';
        $this->host = 'www.4patientcare.ws';
    }

    public function getApptTypes($input){

        $input['AccessID']= $this->accessID;
        $input['SecurityCode']= $this->securityCode;
        $client = new \SoapClient($this->wsdl , array('trace' => 1,  'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
        $response = $client->__soapCall("GetApptTypes", array($input), array('soapaction' => $this->getApptTypesAction, 'uri' => $this->host));

        return $response;
    }

    public function getOpenApptSlots($input){

        $input['AccessID']= $this->accessID;
        $input['SecurityCode']= $this->securityCode;


        $client = new \SoapClient($this->wsdl , array('trace' => 1,  'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
        $response = $client->__soapCall("GetOpenApptSlots", array($input), array('soapaction' => $this->getOpenApptSlotsAction, 'uri' => $this->host));
        return $response->GetOpenApptSlotsResult;
    }

    public function requestApptInsert($input){

        $input['AccessID']= $this->accessID;
        $input['SecurityCode']= $this->securityCode;
        //dd($input);
//        $client = new \SoapClient($this->wsdl , array('trace' => 1,  'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
//        $response = $client->__soapCall("RequestApptInsert", array($input), array('soapaction' => $this->requestApptInsertAction, 'uri' => $this->host));
//        dd($client->__getLastResponse());
//        return json_encode($response);
    }


    public function objectToArray($object){
      $result = array();
      foreach ($object as $key => $val) {
        switch(true) {
            case is_object($val):
             $result[$key] = $this->objectToArray($val);
             break;
          case is_array($val):
             $result[$key] = $this->objectToArray($val);
             break;
          default:
            $result[$key] = $val;
        }
      }
      return $result;
    }
}
