<?php

namespace myocuhub\Http\Controllers\Appointment;

use DateTime;
use myocuhub\Facades\WriteBack4PC;
use myocuhub\Http\Controllers\Controller;
use myocuhub\User;

class WriteBackController extends Controller {
	function __construct() {

	}

	public function index() {

		$startDate = new DateTime();

		$providers = User::getProviderNPIs();

		$input = [];
		$input['NPI'] = '991234567';
		$input['Start'] = $startDate->format('Y-m-d');
		$input['DaysForward'] = 14;

		$schedule = WriteBack4PC::ProviderApptSchedule($input);

		
		
		if (sizeof($schedule)) {

		}
	}

}
