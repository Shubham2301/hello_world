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
	 * @param $filterType
	 * @param $filterValue
	 * @return mixed
	 */
	public function getCount($kpiName, $networkID, $stageID, $filterType = '', $filterValue = '') {
        $count = array();

        $stageName = CareconsoleStage::find($stageID)->name;

        $count['precise_count'] = Careconsole::consoleStagePatientQuery($networkID, $stageName)
        						->consoleKpiPatientQuery($kpiName)
        						->consolepatientListFilter($filterType, $filterValue)
        						->count();

        $count['abbreviated_count'] = $this->getAbbreviationCount($count['precise_count']);
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
				$patients = Careconsole::getAppointmentStatusPatients($networkID, $stageID, $kpiName);
				break;
			case 'waiting-for-report':
				$patients = Careconsole::getStageWaitingPatients($networkID, $stageID);
				break;
			case 'reports-overdue':
				$patients = Careconsole::getStageOverduePatients($networkID, $stageID);
				break;
			case 'ready-to-be-completed':
				$patients = Careconsole::getStageWaitingPatients($networkID, $stageID);
				break;
			case 'overdue':
				$patients = Careconsole::getStageOverduePatients($networkID, $stageID);
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
			case 'recall':
				$patients = Careconsole::getRecallPatients($networkID);
				break;
		}
		return $patients;
	}

    /**
	 * @param $count
	 * @return shortcount
	 */
    public function getAbbreviationCount ($count) {

        if ($count < 1000)
            return $count;
        else {
            $count = $count/1000;
            $count = number_format((float)$count, 0, '.', '').'K';
            return $count;
        }
    }

}
