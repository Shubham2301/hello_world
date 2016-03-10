<?php

namespace myocuhub\Services;

use DateTime;
use myocuhub\Models\Action;
use myocuhub\Models\ActionResult;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\Kpi;

class ActionService {

	public function __construct() {

	}

	/**
	 * @param $kpiName
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */
	public function userAction($actionID, $actionResultID, $recallDate, $notes, $consoleID) {
		$actionName = Action::find($actionID)->name;
		if ($actionResultID == '-1') {
			$actionResultID = 14;
			$actionResultName = 'patient-notes';
		}
		$contactDate = new DateTime();

		$actionResultName = ActionResult::find($actionResultID)->name;

		$contact = new ContactHistory;
		$contact->action_id = $actionID;
		$contact->action_result_id = $actionResultID;
		$contact->notes = $notes;
		$contact->console_id = $consoleID;
		$contact->contact_activity_date = $contactDate->format('Y-m-d H:m:s');
		$contact->save();
		switch ($actionName) {
			case 'contact-attempted-by-phone':
			case 'contact-attempted-by-email':
			case 'contact-attempted-by-mail':
			case 'contact-attempted-by-other':
			case 'patient-notes':
			case 'requested-data':
				break;
			case 'move-to-console':
				$console = Careconsole::find($consoleID);
				$date = new DateTime($recallDate);
				$console->recall_date = null;
				$date = new DateTime();
				$console->stage_id = 1;
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				$contactshistory = ContactHistory::where('console_id', $consoleID )->get();
				if($contactshistory)
				{
					foreach($contactshistory as $history){
						$history->archived = 1;
						$history->save();
					}
				}
				break;
			case 'recall-later':
				$console = Careconsole::find($consoleID);
				$date = new DateTime($recallDate);
				$console->recall_date = $date->format('Y-m-d H:m:s');
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				$contactshistory = ContactHistory::where('console_id', $consoleID )->get();
				if($contactshistory)
				{
					foreach($contactshistory as $history){
						$history->archived = 1;
						$history->save();
					}
				}
				break;    
			case 'unarchive':
				$console = Careconsole::find($consoleID);
				$date = new DateTime();
				$console->archived_date = null;
				$console->stage_id = 1;
				$console->appointment_id = null;
				$console->referral_id = null;
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				break;
			case 'archive':
				$console = Careconsole::find($consoleID);
				$date = new DateTime();
				$console->archived_date = $date->format('Y-m-d H:m:s');
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				$contactshistory = ContactHistory::where('console_id', $consoleID )->get();
				if($contactshistory)
				{
					foreach($contactshistory as $history){
						$history->archived = 1;
						$history->save();
					}
				}
				break;
			case 'kept-appointment':
				$console = Careconsole::find($consoleID);
				$appointment = Appointment::find($console->appointment_id);
				$kpi = Kpi::where('name', 'waiting-for-report')->first();
				$appointment->appointment_status = $kpi['id'];
				$appointment->save();
				$console->stage_id = 4;
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				break;
			case 'no-show':
				$console = Careconsole::find($consoleID);
				$appointment = Appointment::find($console->appointment_id);
				$kpi = Kpi::where('name', 'no-show')->first();
				$appointment->appointment_status = $kpi['id'];
				$appointment->save();
				$console->stage_id = 3;
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				break;
			case 'cancelled':
				$console = Careconsole::find($consoleID);
				$appointment = Appointment::find($console->appointment_id);
				$kpi = Kpi::where('name', 'cancelled')->first();
				$appointment->appointment_status = $kpi['id'];
				$appointment->save();
				$console->stage_id = 3;
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				break;
			case 'data-received':
				$console = Careconsole::find($consoleID);
				$console->stage_id = 5;
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				$appointment = Appointment::find($console->appointment_id);
				$kpi = Kpi::where('name', 'ready-to-be-completed')->first();
				$appointment->appointment_status = $kpi['id'];
				$appointment->save();
				break;
			case 'mark-as-priority':
				$console = Careconsole::find($consoleID);
				$console->priority = 1;
				$console->save();
				break;
			case 'remove-priority':
				$console = Careconsole::find($consoleID);
				$console->priority = null;
				$console->save();
				break;
			case 'annual-exam':
				$console = Careconsole::find($consoleID);
				$date = new DateTime($recallDate);
				$console->recall_date = $date->format('Y-m-d H:m:s');
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				$contactshistory = ContactHistory::where('console_id', $consoleID )->get();
				if($contactshistory)
				{
					foreach($contactshistory as $history){
						$history->archived = 1;
						$history->save();
					}
				}
				break;
			case 'refer-to-specialist':
			case 'highrisk-contact-pcp':
			default:
				break;
		}
		switch ($actionResultName) {
			case 'mark-as-priority':
				$console = Careconsole::find($consoleID);
				$console->priority = 1;
				$console->save();
				break;
			case 'already-seen-by-outside-dr':
			case 'patient-declined-services':
			case 'other-reasons-for-declining':
			case 'no-need-to-schedule':
			case 'no-insurance':
				$console = Careconsole::find($consoleID);
				$date = new DateTime();
				$console->archived_date = $date->format('Y-m-d H:m:s');
				$console->stage_updated_at = $date->format('Y-m-d H:m:s');
				$console->save();
				$contactshistory = ContactHistory::where('console_id', $consoleID )->get();
				if($contactshistory)
				{
					foreach($contactshistory as $history){
						$history->archived = 1;
						$history->save();
					}
				}
				break;
			default:
				break;
		}
		return $contact->id;
	}

	public function getContactActions($consoleID){
		$contactsData = ContactHistory::getContactHistory($consoleID);
		$i=0;
		$actions = [];
		foreach($contactsData as $contact){
			$date = new \DateTime($contact['contact_activity_date']);
			$actions[$i]['date']=$date->format('j F Y');
			$actions[$i]['name']=$contact['display_name'];
			$actions[$i]['notes']=$contact['notes'];
			$i++;
		}

		return $actions;

	}

}
