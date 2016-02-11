<?php

namespace myocuhub\Services\KPI;

use myocuhub\Models\Careconsole;
use myocuhub\Models\KPI;

class KPIService {

	public function __construct() {

	}

	/**
	 * @param $kpiName
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */
	public function getCount($kpiName, $networkID, $stageID) {
		switch ($kpiName) {
			case 'contact-attempted':
				$count = Careconsole::getContactAttemptedCount($networkID, $stageID);
				break;
			case 'contact-pending':
				$count = Careconsole::getContactPendingCount($networkID, $stageID);
				break;
			case 'appointment-scheduled':
				$count = Careconsole::getAppointmentScheduledCount($networkID, $stageID);
				break;
			case 'appointment-tomorrow':
				$count = Careconsole::getAppointmentTomorrowCount($networkID, $stageID);
				break;
			case 'past-appointment':
				$count = Careconsole::getPastAppointmentCount($networkID, $stageID);
				break;
			case 'pending-information':
			case 'cancelled':
			case 'no-show':
			case 'waiting-for-report':
			case 'reports-overdue':
			case 'ready-to-be-completed':
			case 'overdue':
				$count = Careconsole::getAppointmentStatusCount($networkID, $stageID, $kpiName);
				break;
			default:
				$count = -1;
				break;
		}
		return $count;
	}
	/**
	 * @param $kpiName
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */
	public function getPatients($kpiName, $networkID, $stageID) {
		switch ($kpiName) {
			case 'contact-attempted':
				$patients = Careconsole::getContactAttemptedPatients($networkID, $stageID);
				break;
			case 'contact-pending':
				$patients = Careconsole::getContactPendingPatients($networkID, $stageID);
				break;
			case 'appointment-scheduled':
				$patients = Careconsole::getAppointmentScheduledPatients($networkID, $stageID);
				break;
			case 'appointment-tomorrow':
				$patients = Careconsole::getAppointmentTomorrowPatients($networkID, $stageID);
				break;
			case 'past-appointment':
				$patients = Careconsole::getPastAppointmentPatients($networkID, $stageID);
				break;
			case 'pending-information':
			case 'cancelled':
			case 'no-show':
			case 'waiting-for-report':
			case 'reports-overdue':
			case 'ready-to-be-completed':
			case 'overdue':
				$patients = Careconsole::getAppointmentStatusPatients($networkID, $stageID, $kpiName);
				break;
			default:
				$patients = [];
				break;
		}
		return $patients;
	}

}
