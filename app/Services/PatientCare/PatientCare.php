<?php

namespace myocuhub\Services\PatientCare;

class PatientCare {

	private static $accessID = 2;
	private static $securityCode = 5763432;
	protected static $url;
	protected static $wsdl;
	protected static $host;
	protected static $getApptTypesAction;
	protected static $getOpenApptSlotsAction;
	protected static $requestApptInsertAction;
	protected static $getInsListAction;
	protected static $ProviderApptScheduleAction;

	public function __construct() {

	}

	public static function getAccessID() {
		return self::$accessID;
	}

	public static function getSecurityCode() {
		return self::$securityCode;
	}

	public static function getApptTypes($input) {
		//
	}

	public static function getInsList($input) {
		//
	}

	public static function getOpenApptSlots($input) {
		//
	}

	public static function requestApptInsert($input) {
		//
	}

	public static function ProviderApptSchedule($input) {
		//
	}

	public static function OcuhubWriteback($input) {
		//
	}
}
