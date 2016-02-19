<?php

namespace myocuhub\Services;

use myocuhub\Models\Action;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\KPI;
use myocuhub\Models\Menu;
use myocuhub\User;
use myocuhub\Permission;
use myocuhub\Role;

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
		$actionName = Action::find($actionID)->name;

		$contact = new ContactHistory;
		$contact->action_id = $actionID;
		$contact->post_action_id = $postActionID;
		$contact->notes = $notes;
		$contact->console_id = $consoleID;
		$contact->contact_activity_date = $date;
		$contact->save();
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
				$console = CareConsole::find($consoleID);
				$console->archived = 1;
				$console->save();
				break;
			case 'kept-appointment':
				$console = CareConsole::find($consoleID);
				if ($console->stage_id == 2) {
					$appointment = Appointment::find($console->appointment_id);
					$kpi = KPI::where('name', 'pending-information')->first();
					$appointment->appointment_status = $kpi['id'];
					$appointment->save();
					$console->stage_id = 3;
					$console->save();
				} else if ($console->stage_id == 3) {
					$appointment = Appointment::find($console->appointment_id);
					$kpi = KPI::where('name', 'waiting-for-report')->first();
					$appointment->appointment_status = $kpi['id'];
					$appointment->save();
					$console->stage_id = 4;
					$console->save();
				}
				break;
			case 'no-show':
				$console = CareConsole::find($consoleID);

				if ($console->stage_id == 2) {
					$appointment = Appointment::find($console->appointment_id);
					$kpi = KPI::where('name', 'no-show')->first();
					$appointment->appointment_status = $kpi['id'];
					$appointment->save();
					$console->stage_id = 3;
					$console->save();
				} else if ($console->stage_id == 3) {
					$appointment = Appointment::find($console->appointment_id);
					$kpi = KPI::where('name', 'no-show')->first();
					$appointment->appointment_status = $kpi['id'];
					$appointment->save();
					$console->archived = 1;
					$console->save();
				}
				break;
			case 'cancelled':
				$console = CareConsole::find($consoleID);
				if ($console->stage_id == 2) {
					$appointment = Appointment::find($console->appointment_id);
					$kpi = KPI::where('name', 'cancelled')->first();
					$appointment->appointment_status = $kpi['id'];
					$appointment->save();
					$console->stage_id = 3;
					$console->save();
				} else if ($console->stage_id == 3) {
					$appointment = Appointment::find($console->appointment_id);
					$kpi = KPI::where('name', 'cancelled')->first();
					$appointment->appointment_status = $kpi['id'];
					$appointment->save();
					$console->archived = 1;
					$console->save();
				}
				break;
			case 'data-received':
				$console = CareConsole::find($consoleID);
				$console->stage_id = 5;
				$console->save();
				$appointment = Appointment::find($console->appointment_id);
				$kpi = KPI::where('name', 'ready-to-be-completed')->first();
				$appointment->appointment_status = $kpi['id'];
				$appointment->save();
				break;
			case 'annual-exam':
				break;
			case 'refer-to-specialist':
				break;
			case 'highrisk-contact-pcp':
				break;
		}
	}

	public function renderForUser($userId, $menuId=0, $level=0){
		$menus = Menu::all();
		$user = User::find($userId);
		return "hello";

		// $returnMenus[] = array();

		foreach ($menus as $menu) {		
			$func = function($menu) use ($user) {
						return $user->hasRole($menu->permission->roles);
				};

			// If there is no roles associated or the roles on menu and user matches
			if(!$menu->permission) || $func($menu)){
				$returnMenus[] = $menu;
			}
		}
		return $returnMenus;
	}

}
