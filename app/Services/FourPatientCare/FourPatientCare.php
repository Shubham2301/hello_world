<?php

namespace myocuhub\Services\FourPatientCare;

use myocuhub\Services\ArrayToXML\ArrayToXML;

class FourPatientCare
{
    protected $accessID;
    protected $securityCode;
    protected $url;

    public function __construct(){
        $this->accessID  = 2;
        $this->securityCode  = 5763432;
        $this->url = 'http://www.4patientcare.ws/v5dn/partnerwebscheduling.asmx';
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
        //$input['@attributes'] = ['xmlns' => 'http://WebScheduling.4PatientCare.Com/'];
        $input['AccessID'] = $this->accessID;
        $input['SecurityCode'] = $this->securityCode;

        $soapRequest = ArrayToXML::createXML('RequestApptInsert', $input);
        //dd($soapRequest);
        $soapRequest = $soapRequest->saveXML();
        dd($soapRequest);
//        $client = new \SoapClient($this->url, array("trace" => 1, "exception" => 0));
//
//        $result = $client->__soapCall("RequestApptInsert", [ "RequestApptInsert" => $input ], NULL, NULL);
//
//        die();
//        ///////////
//
//        $client = new Client([
//    // Base URI is used with relative requests
//    'base_uri' => $this->url,
//    // You can set any number of default request options.
//    'timeout'  => 120,
//]);
//        $request = new Request('PUT', 'http://httpbin.org/put');
//
//        $request = $client->post('', array('Content-Type' => 'text/xml; charset=UTF8'), $soapRequest, array('timeout' => 120));
//        dd($request);
//        $request->send()->xml();

        /*
            Make API call to 4PC
            SOAP request payload : $soapRequest
            SOAP response payload : $soapResponse
        */
    }
}
