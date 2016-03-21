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

		$providers = User::get4PCProviderNPIs();
		$schedules = [];
		foreach ($providers as $provider) {

			$input = [];
			$input['NPI'] = $provider->npi;
			$input['Start'] = $startDate->format('Y-m-d');
			$input['DaysForward'] = 14;

			$schedule = WriteBack4PC::ProviderApptSchedule($input);
			if (sizeof($schedule['OcuHub_ApptScheduleResult']) == 0) {
				$schedules[] = [
					'npi' => $provider->npi,
					'schedule' => $schedule['OcuHub_ApptScheduleResult']['ApptDetail'],
				];
			}
		}

		$writeBackResult = WriteBack4PC::OcuhubAppointmentWriteback($schedules);

	}

}
