<?php

namespace myocuhub\Services\FourPatientCare;

use myocuhub\Services\ArrayToXML\ArrayToXML;
use myocuhub\Services\ArrayToXML\XMLToArray;

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
        $input['@attributes'] = ['xmlns' => 'http://WebScheduling.4PatientCare.Com/'];
        $input['AccessID']= $this->accessID;
        $input['SecurityCode']= $this->securityCode;
        //$soapInput = array('xmlns' =>'http://WebScheduling.4PatientCare.Com/', '_' => $input);
        $soapRequest = ArrayToXML::createXML('GetApptTypes', $input);
        //$soapRequest = new SoapVar($soapRequest, XSD_STRING);
        $soapRequest = $soapRequest->saveXML();

        $soapRequest = new \SoapVar($soapRequest, XSD_STRING);
//        $soapRequest = '';
        //dd($soapRequest);
        $client = new \SoapClient($this->wsdl, array('trace' => 1,  'exceptions' => 1, 'encoding' => 'UTF-8'));

        $response = $client->__soapCall("GetApptTypes", array($input), array('soapaction' => $this->getApptTypesAction, 'uri' => $this->host));

        dd($client->__getLastRequest());
        dd($this->objectToArray($response));
    }

    public function getOpenApptSlots($input){
        $input['@attributes'] = ['xmlns' => 'http://WebScheduling.4PatientCare.Com/'];
        $input['AccessID'] = $this->accessID;
        $input['SecurityCode'] = $this->securityCode;
        $soapInput['soap:Body']['GetOpenApptSlots']= $input;

        $soapInput['@attributes'] = [
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xmlns:xsd' => 'http://www.w3.org/2001/XMLSchema',
            'xmlns:soap' => 'http://schemas.xmlsoap.org/soap/envelope/'
        ];

        $soapRequest = ArrayToXML::createXML('soap:Envelope', $soapInput);
        $soapRequest = $soapRequest->saveXML();

        $client = new \SoapClient($this->wsdl);
        $response = $client->__soapCall("GetOpenApptSlots", array($soapRequest),
                    array('soapaction' => $this->getApptTypesAction,
                          'uri'        => $this->host));

        $reponse = $this->objectToArray($response);
        dd($response);
    }

    public function requestApptInsert($input){
        //$input['@attributes'] = ['xmlns' => 'http://WebScheduling.4PatientCare.Com/'];
        $input['AccessID'] = $this->accessID;
        $input['SecurityCode'] = $this->securityCode;

        $soapRequest = ArrayToXML::createXML('RequestApptInsert', $input);
        //dd($soapRequest);
        $soapRequest = $soapRequest->saveXML();
        dd($soapRequest);

        $client = new \SoapClient($this->url, array("trace" => 1, "exception" => 0));

        $result = $client->__soapCall("RequestApptInsert", [ "RequestApptInsert" => $input ], NULL, NULL);

        die();
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
