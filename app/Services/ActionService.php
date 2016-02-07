<?php

namespace myocuhub\Services;

use myocuhub\Models\Actions;
use myocuhub\Models\Careconsole;

class ActionService {

	public function __construct() {

	}

	/**
	 * @param $kpiName
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */
	public function userAction($actionID, $postActionID, $date, $notes, $consoleID) {
		$actionName = Actions::find($actionID)->name;

		$contact = new ContactHistory;
		$contact->action_id = $actionID;
		$contact->post_action_id = $postActionID;
		$contact->notes = $actionID;
		$contact->save();
		$contact->id;
		switch ($actionName) {
			case 'contact-attempted-by-phone':
			case 'contact-attempted-by-email':
			case 'contact-attempted-by-mail':
			case 'contact-attempted-by-other':
			case 'patient-notes':
			case 'requested-data':
				break;
			case 'archive':
			case 'already-seen-by-outside-dr':
			case 'patient-declined-services':
			case 'other-reasons-for-declining':
			case 'no-need-to-schedule':
			case 'no-insurance':
				break;
			case 'kept-appointment':
			case 'no-show':
			case 'cancelled':
				$contact = new ContactHistory;
				$contact->action_id = $actionID;
				$contact->post_action_id = $postActionID;
				$contact->notes = $actionID;
				$contact->save();
				$contact->id;
				$console = CareConsole::find($consoleID);
				if ($console->stage_id == 2) {
					$console->stage_id = 3;
					$console->save();
				} else if ($console->stage_id == 3) {
					$console->stage_id = 4;
					$console->save();
				}
				break;
			case 'data-received':
				$console = CareConsole::find($consoleID);
				if ($console->stage_id == 2) {
					$console->stage_id = 3;
					$console->save();
				} else if ($console->stage_id == 3) {
					$console->stage_id = 4;
					$console->save();
				}
				break;
			case 'requested-data':
				break;
			case 'requested-data':
				break;
			case 'requested-data':
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
