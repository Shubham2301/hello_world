<?php

namespace myocuhub\Services;

use DateTime;
use myocuhub\Models\Action;
use myocuhub\Models\ActionResult;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\Kpi;
use myocuhub\Models\ReferralHistory;
use myocuhub\User;

class ActionService
{

	public function __construct()
	{

	}

	/**
     * @param $kpiName
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
	public function userAction($actionID, $actionResultID, $recallDate, $notes, $consoleID, $manualAppointmentData)
	{
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
		$contact->contact_activity_date = $contactDate->format('Y-m-d H:i:s');
		$contact->save();
		switch ($actionName) {
			case 'contact-attempted-by-phone':
			case 'contact-attempted-by-email':
			case 'contact-attempted-by-mail':
			case 'contact-attempted-by-other':
			case 'patient-notes':
			case 'requested-data':
				break;
			case 'manually-schedule':
				$console = Careconsole::find($consoleID);
				$appointment_date = new DateTime($manualAppointmentData['appointment_date']);
				$appointment = new Appointment;
				$appointment->patient_id = $console->patient_id;
				$appointment->network_id = session('network-id');
				$appointment->start_datetime = $appointment_date->format('Y-m-d H:i:s');
				$appointment->end_datetime = $appointment_date->format('Y-m-d H:i:s');

				if ($manualAppointmentData['practice_id'] != '0' && $manualAppointmentData['practice_id'] != '') {
					$appointment->practice_id = $manualAppointmentData['practice_id'];
				}

				if ($manualAppointmentData['provider_id'] != '0' && $manualAppointmentData['provider_id'] != '') {
					$appointment->provider_id = $manualAppointmentData['provider_id'];
				}

				if ($manualAppointmentData['location_id'] != '0' && $manualAppointmentData['location_id'] != '') {
					$appointment->location_id = $manualAppointmentData['location_id'];
				}

				if ($manualAppointmentData['appointment_type'] != '') {
					$appointment->appointmenttype = $manualAppointmentData['appointment_type'];
				}

				$provider = User::find($appointment->provider_id);
				$scheduledTo = ' ';
				if($provider){
					$scheduledTo = $provider->title . ' ' . $provider->lastname . ', ' . $provider->firstname;
				}
				$newNote = $scheduledTo . '</br>' . $appointment->start_datetime . '</br>' . $appointment->appointmenttype;
				$updatedNote = $newNote.'</br></br>'. $notes;
				$appointment->notes = $updatedNote;

				$contact->notes = $updatedNote;
				$contact->save();
				$appointment->save();
				if ($appointment) {
					$console->appointment_id = $appointment->id;
					$console->stage_id = 2;
					$console->recall_date = null;
					$console->archived_date = null;
					$date = new DateTime();
					$console->stage_updated_at = $date->format('Y-m-d H:i:s');
					$console->update();
				}
				break;
			case 'manually-reschedule':
				$console = Careconsole::find($consoleID);
				$appointment_date = new DateTime($manualAppointmentData['appointment_date']);
				$appointment = Appointment::find($console->appointment_id);
				$appointment->patient_id = $console->patient_id;
				$appointment->network_id = session('network-id');
				$appointment->start_datetime = $appointment_date->format('Y-m-d H:i:s');
				$appointment->end_datetime = $appointment_date->format('Y-m-d H:i:s');
				if ($manualAppointmentData['practice_id'] != '0' && $manualAppointmentData['practice_id'] != '') {
					$appointment->practice_id = $manualAppointmentData['practice_id'];
				}

				if ($manualAppointmentData['provider_id'] != '0' && $manualAppointmentData['provider_id'] != '') {
					$appointment->provider_id = $manualAppointmentData['provider_id'];
				}

				if ($manualAppointmentData['location_id'] != '0' && $manualAppointmentData['location_id'] != '') {
					$appointment->location_id = $manualAppointmentData['location_id'];
				}

				if ($manualAppointmentData['appointment_type'] != '') {
					$appointment->appointmenttype = $manualAppointmentData['appointment_type'];
				}

				$provider = User::find($appointment->provider_id);
				$scheduledTo = ' ';
				if($provider){
					$scheduledTo = $provider->title . ' ' . $provider->lastname . ', ' . $provider->firstname;
				}
				
				$newNote = $scheduledTo . '</br>' . $appointment->start_datetime . '</br>' . $appointment->appointmenttype;
				$updatedNote = $newNote.'</br>'. $notes;
				$appointment->notes = $updatedNote;

				$contact->notes = $updatedNote;
				$contact->save();
				$appointment->save();
				if ($appointment) {
					$console->appointment_id = $appointment->id;
					$console->stage_id = 2;
					$console->recall_date = null;
					$console->archived_date = null;
					$date = new DateTime();
					$console->stage_updated_at = $date->format('Y-m-d H:i:s');
					$console->update();
				}
				break;
			case 'move-to-console':
				$console = Careconsole::find($consoleID);
				$referralHistory = new ReferralHistory;
				$referralHistory->save();
				$console->referral_id = $referralHistory->id;
				$console->recall_date = null;
				$console->archived_date = null;
				$date = new DateTime();
				$console->stage_id = 1;
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
				$console->save();
				$this->archiveContactHistory($consoleID);
				break;
			case 'recall-later':
				$console = Careconsole::find($consoleID);
				$date = new DateTime($recallDate);
				$console->recall_date = $date->format('Y-m-d H:i:s');
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
				$console->save();
				$this->archiveContactHistory($consoleID);
				break;
			case 'unarchive':
				$console = Careconsole::find($consoleID);
				$date = new DateTime();
				$console->archived_date = null;
				$console->stage_id = 1;
				$console->appointment_id = null;
				$console->referral_id = null;
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
				$console->save();
				$referralHistory = new ReferralHistory;
				$referralHistory->save();
				$console->referral_id = $referralHistory->id;
				$console->save();
				$this->archiveContactHistory($consoleID);
				break;
			case 'archive':
				$console = Careconsole::find($consoleID);
				$date = new DateTime();
				$console->archived_date = $date->format('Y-m-d H:i:s');
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
				$console->save();
				$this->archiveContactHistory($consoleID);
				break;
			case 'kept-appointment':
				$console = Careconsole::find($consoleID);
				$appointment = Appointment::find($console->appointment_id);
				$kpi = Kpi::where('name', 'waiting-for-report')->first();
				$appointment->appointment_status = $kpi['id'];
				$appointment->save();
				$console->stage_id = 4;
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
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
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
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
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
				$console->save();
				break;
			case 'data-received':
				$console = Careconsole::find($consoleID);
				$console->stage_id = 5;
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
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
				$console->recall_date = $date->format('Y-m-d H:i:s');
				$date = new DateTime();
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
				$console->save();
				$this->archiveContactHistory($consoleID);
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
				$console->archived_date = $date->format('Y-m-d H:i:s');
				$console->stage_updated_at = $date->format('Y-m-d H:i:s');
				$console->save();
				$this->archiveContactHistory($consoleID);
				break;
			default:
				break;
		}
		return $contact->id;
	}

	public function getContactActions($consoleID)
	{
		$contactsData = ContactHistory::getContactHistory($consoleID);
		$console = Careconsole::find($consoleID);
		$actions = [];
		$date = new \DateTime();
		$i = 0;
		foreach ($contactsData as $contact) {
			if ($contact['contact_activity_date'] != 0) {
				$date = new \DateTime($contact['contact_activity_date']);
			}

			$actions[$i]['date'] = $date->format('j F Y');
			$actions[$i]['name'] = $contact['display_name'];

			if ($contact['name'] == 'unarchive' || $contact['name'] == 'move-to-console') {
				$actions[$i]['name'] = 'entered into console';
			}

			$actions[$i]['notes'] = $contact['notes'];
			$i++;
		}

		$date = new \DateTime();
		if ($console->entered_console_at != 0) {
			$date = new \DateTime($console->entered_console_at);
		}

		$actions[$i]['date'] = $date->format('j F Y');
		$actions[$i]['name'] = 'entered into console';
		$actions[$i]['notes'] = '-';
		return $actions;
	}

	public function archiveContactHistory($consoleID)
	{

		$contactHistory = ContactHistory::where('console_id', $consoleID)->get();

		foreach ($contactHistory as $history) {
			$history->archived = 1;
			$history->save();
		}

	}

}
