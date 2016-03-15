<?php

namespace myocuhub\Services\PatientCare;

use SoapClient;

class WriteBack4PC extends PatientCare {

	public function __construct() {
		self::$url = 'https://www.4patientcare.net/4pcdn/writeback4pc.asmx';
		self::$wsdl = 'https://www.4patientcare.net/4pcdn/writeback4pc.asmx?WSDL';
		self::$ProviderApptScheduleAction = 'http://writeback4pc.4PatientCare.net/OcuHub_ApptSchedule';
		self::$host = 'www.4patientcare.net';
	}

	public static function ProviderApptSchedule($input) {

		$input['AccessID'] = self::getAccessID();
		$input['SecurityCode'] = self::getSecurityCode();

		$client = new SoapClient(self::$wsdl, array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
		$response = $client->__soapCall("OcuHub_ApptSchedule", array($input), array('soapaction' => self::$ProviderApptScheduleAction, 'uri' => self::$host));
		dd($response->OcuHub_ApptScheduleResult);
		return $response;
	}

	public static function OcuhubWriteback($input) {

	}

}
