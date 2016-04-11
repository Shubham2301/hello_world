<?php

namespace myocuhub\Services\PatientCare;

use Event;
use myocuhub\Events\MakeAuditEntry;
use SoapClient;
use SoapFault;

class WebScheduling4PC extends PatientCare
{

    public function __construct()
    {

        self::$url = 'http://www.4patientcare.ws/v5dn/partnerwebscheduling.asmx';
        self::$wsdl = 'http://www.4patientcare.ws/v5dn/partnerwebscheduling.asmx?WSDL';
        self::$getApptTypesAction = 'http://WebScheduling.4PatientCare.Com/GetApptTypes';
        self::$getOpenApptSlotsAction = 'http://WebScheduling.4PatientCare.Com/GetOpenApptSlots';
        self::$requestApptInsertAction = 'http://WebScheduling.4PatientCare.Com/RequestApptInsert';
        self::$getInsListAction = 'http://WebScheduling.4PatientCare.Com/GetInsList';
        self::$host = 'www.4patientcare.ws';

    }

    /**
     *
     * getApptTypes() retrieves a list of appointment types for a specific provider at a specific location.
     *
     * @param $input
     * @return mixed
     */
    public static function getApptTypes($input)
    {

        $input['AccessID'] = self::getAccessID();
        $input['SecurityCode'] = self::getSecurityCode();

        $client = new SoapClient(self::$wsdl, array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));

        try {
            $response = $client->__soapCall("GetApptTypes", array($input), array('soapaction' => self::$getApptTypesAction, 'uri' => self::$host));
        } catch (SoapFault $e) {
            $result = $e->faultstring;
            $action = 'Attempt to getApptTypes with 4PC failed : SoapFault ';
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return;
        }

        return $response;
    }

    /**
     *
     * getInsList() retrieves a list of accepted insurance types for a specific provider at a specific location.
     *
     * @param $input
     * @return mixed
     */
    public static function getInsList($input)
    {

        $input['AccessID'] = self::getAccessID();
        $input['SecurityCode'] = self::getSecurityCode();

        $client = new SoapClient(self::$wsdl, array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
        try {
            $response = $client->__soapCall("GetInsList", array($input), array('soapaction' => self::$getInsListAction, 'uri' => self::$host));
        } catch (SoapFault $e) {
            $result = $e->faultstring;
            $action = 'Attempt to getInsList with 4PC failed : SoapFault ';
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return;
        }

        return $response;
    }

    /**
     *
     * getOpenApptSlots() retrieves a list of available time slots on a given day for a specific provider at a specific location.
     *
     * @param $input
     * @return mixed
     */
    public static function getOpenApptSlots($input)
    {

        $input['AccessID'] = self::getAccessID();
        $input['SecurityCode'] = self::getSecurityCode();

        $client = new SoapClient(self::$wsdl, array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
        try {
            $response = $client->__soapCall("GetOpenApptSlots", array($input), array('soapaction' => self::$getOpenApptSlotsAction, 'uri' => self::$host));
        } catch (SoapFault $e) {
            $result = $e->faultstring;
            $action = 'Attempt to getOpenApptSlots with 4PC failed : SoapFault ';
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return;
        }

        return $response->GetOpenApptSlotsResult;
    }

    /**
     *
     * requestApptInsert() schedules an appointment with a 4PC register practice and provider
     *
     * In case of successfull scheduling 4PC returns the 4PC appointment ID and a status message.
     * Sends a -1 instead.
     *
     * @param $input
     */
    public static function requestApptInsert($input)
    {

        $input['AccessID'] = self::getAccessID();
        $input['SecurityCode'] = self::getSecurityCode();
        $client = new SoapClient(self::$wsdl, array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));

        try {
            $response = $client->__soapCall("RequestApptInsert", array($input), array('soapaction' => self::$requestApptInsertAction, 'uri' => self::$host));
        } catch (SoapFault $e) {
            $result = $e->faultstring;
            $action = 'Attempt to requestApptInsert with 4PC failed : SoapFault ';
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return;
        }

        return $response;

    }

}
