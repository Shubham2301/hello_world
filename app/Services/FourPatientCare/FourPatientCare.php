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

        $client = new CustomSoapClient($this->wsdl , array('trace' => 1,  'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
        $response = $client->__soapCall("GetApptTypes", array($input), array('soapaction' => $this->getApptTypesAction, 'uri' => $this->host));

        return json_encode($response);
    }

    public function getOpenApptSlots($input){

        $input['AccessID']= $this->accessID;
        $input['SecurityCode']= $this->securityCode;

        $client = new CustomSoapClient($this->wsdl , array('trace' => 1,  'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
        $response = $client->__soapCall("GetOpenApptSlots", array($input), array('soapaction' => $this->getOpenApptSlotsAction, 'uri' => $this->host));

        return json_encode($response);
    }

    public function requestApptInsert($input){

        $input['AccessID']= $this->accessID;
        $input['SecurityCode']= $this->securityCode;

        $client = new CustomSoapClient($this->wsdl , array('trace' => 1,  'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
        $response = $client->__soapCall("RequestApptInsert", array($input), array('soapaction' => $this->requestApptInsertAction, 'uri' => $this->host));

        return json_encode($response);
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

class CustomSoapClient extends \SoapClient {

    function __doRequest( $request, $location, $action, $version, $one_way = 0 ) {

        $namespace = 'http://WebScheduling.4PatientCare.Com/';

        $request = str_replace( '<ns1:', '<', $request);
        $request = str_replace( '</ns1:', '</', $request );

        $request = str_replace( 'SOAP-ENV', 'soap', $request );
        $request = str_replace( '<soap:Envelope', '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"', $request );

        $request = str_replace( ' xmlns:ns1="' . $namespace . '"', '', $request );

        $request = str_replace( '<GetApptTypes>', '<GetApptTypes xmlns="' . $namespace . '">', $request );
        $request = str_replace( '<GetOpenApptSlots>', '<GetOpenApptSlots  xmlns="' . $namespace . '">', $request );
        $request = str_replace( '<RequestApptInsert>', '<RequestApptInsert xmlns="' . $namespace . '">', $request );
        //dd($request);
        return parent::__doRequest( $request, $location, $action, $version, $one_way = 0 );

    }

}
