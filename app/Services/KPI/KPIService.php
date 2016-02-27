<?php

namespace myocuhub\Services\KPI;

use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
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
			case 'waiting-for-report':
				$count = Careconsole::getStageWaitingCount($networkID, $stageID, $kpiName);
				break;
			case 'reports-overdue':
				$count = Careconsole::getStageOverdueCount($networkID, $stageID, $kpiName);
				break;
			case 'pending-information':
			case 'cancelled':
			case 'no-show':
				$count = Careconsole::getAppointmentStatusCount($networkID, $stageID, $kpiName);
				break;
			case 'ready-to-be-completed':
				$count = Careconsole::getStageWaitingCount($networkID, $stageID, $kpiName);
				break;
			case 'overdue':
				$count = Careconsole::getStageOverdueCount($networkID, $stageID, $kpiName);
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
				$patients = Careconsole::getAppointmentStatusPatients($networkID, $stageID,$kpiName);
			case 'no-show':
				$patients = Careconsole::getAppointmentStatusPatients($networkID, $stageID,$kpiName);
			case 'waiting-for-report':
				$patients = Careconsole::getAppointmentStatusPatients($networkID, $stageID,$kpiName);
			case 'reports-overdue':
				$patients = Careconsole::getAppointmentStatusPatients($networkID, $stageID,$kpiName);
			case 'ready-to-be-completed':
				$patients = Careconsole::getAppointmentStatusPatients($networkID, $stageID,$kpiName);
			case 'overdue':
				$patients = Careconsole::getAppointmentStatusPatients($networkID, $stageID, $kpiName);
				break;
			default:
				$patients = [];
				break;
		}
		return $patients;
	}

	/**
	 * @param $networkID
	 * @param $bucketID
	 * @return mixed
	 */
	public function getBucketPatients($networkID, $bucketID) {

		$bucketName = CareconsoleStage::find($bucketID)->name;
		$patients = [];

		switch ($bucketName) {
			case 'archived':
				$patients = Careconsole::getArchivedPatients($networkID);
				break;
			case 'priority':
				$patients = Careconsole::getPriorityPatients($networkID);
				break;
		}

		return $patients;
	}

}
