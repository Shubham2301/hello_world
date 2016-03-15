<?php

namespace myocuhub\Services\PatientCare;

use SoapClient;

class WebScheduling4PC extends PatientCare {

	public function __construct() {

		self::$url = 'http://www.4patientcare.ws/v5dn/partnerwebscheduling.asmx';
		self::$wsdl = 'http://www.4patientcare.ws/v5dn/partnerwebscheduling.asmx?WSDL';
		self::$getApptTypesAction = 'http://WebScheduling.4PatientCare.Com/GetApptTypes';
		self::$getOpenApptSlotsAction = 'http://WebScheduling.4PatientCare.Com/GetOpenApptSlots';
		self::$requestApptInsertAction = 'http://WebScheduling.4PatientCare.Com/RequestApptInsert';
		self::$host = 'www.4patientcare.ws';

	}

	public static function getApptTypes($input) {

		$input['AccessID'] = self::getAccessID();
		$input['SecurityCode'] = self::getSecurityCode();

		$client = new SoapClient(self::$wsdl, array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
		$response = $client->__soapCall("GetApptTypes", array($input), array('soapaction' => self::$getApptTypesAction, 'uri' => self::$host));

		return $response;
	}

	public static function getOpenApptSlots($input) {

		$input['AccessID'] = self::getAccessID();
		$input['SecurityCode'] = self::getSecurityCode();

		$client = new SoapClient(self::$wsdl, array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
		$response = $client->__soapCall("GetOpenApptSlots", array($input), array('soapaction' => self::$getOpenApptSlotsAction, 'uri' => self::$host));

		return $response->GetOpenApptSlotsResult;
	}

	public static function requestApptInsert($input) {

		$input['AccessID'] = self::getAccessID();
		$input['SecurityCode'] = self::getSecurityCode();

		//        $client = new SoapClient(self::wsdl , array('trace' => 1,  'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
		//        $response = $client->__soapCall("RequestApptInsert", array($input), array('soapaction' => self::requestApptInsertAction, 'uri' => self::host));
		//
		//        return json_encode($response);
	}

}
