<?php

namespace myocuhub\Services;

use Auth;
use myocuhub\Models\Action;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\Kpi;
use myocuhub\Models\Practice;
use myocuhub\Services\KPI\KPIService;
use myocuhub\User;

class CareConsoleService {

	private $KPIService;
	public function __construct(KPIService $KPIService) {
		$this->KPIService = $KPIService;
	}

	/**
	 * @param $kpiName
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */

	public function getControls($stageID) {
		$llKpiGroup = CareconsoleStage::find($stageID)->llKpiGroup;
		$controls = [];
		$i = 0;
		foreach ($llKpiGroup as $group) {
			$controls[$i]['group_name'] = $group->group_name;
			$controls[$i]['group_display_name'] = $group->group_display_name;
			$controls[$i]['type'] = $group->type;
			$options = CareconsoleStage::llKpiByGroup($group->group_name, $stageID);
			$j = 0;
			foreach ($options as $option) {
				$controls[$i]['options'][$j]['name'] = $option->name;
				$controls[$i]['options'][$j]['display_name'] = $option->display_name;
				$controls[$i]['options'][$j]['color_indicator'] = $option->color_indicator;
				$controls[$i]['options'][$j]['description'] = $option->description;
				$controls[$i]['options'][$j]['count'] = 0;
				$j++;
			}
			$i++;
		}
	}

	public function getActions($stageID) {
		$actions = CareconsoleStage::find($stageID)->actions;
		$actionsData = [];
		$i = 0;
		foreach ($actions as $action) {
			$actionsData[$i]['id'] = $action->id;
			$actionsData[$i]['stage_id'] = $action->stage_id;
			$actionsData[$i]['name'] = $action->name;
			$actionsData[$i]['display_name'] = $action->display_name;
			$actionsData[$i]['action_results'] = Action::find($action->id)->actionResults;
			$i++;
		}
		return $actionsData;
	}

	public function getPatientListing($stageID, $kpiName) {
		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$networkID = $network->network_id;

		$headerData = [];
		$patientsData = [];
		$listing = [];
		$i = 0;
		$fields = [];

		if ($kpiName !== '' && isset($stageID)) {
			$patients = $this->KPIService->getPatients($kpiName, $networkID, $stageID);
		} else if (isset($stageID)) {
			$patients = Careconsole::getStagePatients($networkID, $stageID);
		}

		$headers = CareconsoleStage::find($stageID)->patientFields;

		foreach ($headers as $header) {
			$headerData[$i]['display_name'] = $header['display_name'];
			$headerData[$i]['name'] = $header['name'];
			$headerData[$i]['width'] = $header['width'];
			array_push($fields, $header['name']);
			$i++;
		}
		foreach ($patients as $patient) {
			$patientsData[$i]['console_id'] = $patient['id'];
			$patientsData[$i]['patient_id'] = $patient['patient_id'];
			foreach ($fields as $field) {
				$patientsData[$i][$field] = $this->getPatientFieldValue($patient, $field);
			}
			$i++;
		}
		$listing['patients'] = $patientsData;
		$listing['headers'] = $headerData;

		return $listing;
	}

	public function getPatientFieldValue($patient, $field) {
		$dateFormat = 'F j Y, g:i a';
		switch ($field) {
			case 'full-name':
				return $patient['lastname'] . ', ' . $patient['firstname'];
				break;
			case 'phone':
				return $patient['cellphone'];
				break;
			case 'request-received':
				$date = new \DateTime($patient['created_at']);
				return $date->format($dateFormat);
				break;
			case 'contact-attempts':
				return ContactHistory::where('console_id', $patient['id'])->count();
				break;
			case 'appointment-date':
				$appointment = Appointment::find($patient['appointment_id']);
				$date = new \DateTime($appointment->start_datetime);
				return $date->format($dateFormat);
				break;
			case 'appointment-type':
				$appointment = Appointment::find($patient['appointment_id']);
				return $appointment->appointmenttype;
				break;
			case 'days-pending':
				return date_diff(new \DateTime($patient['created_at']), new \DateTime(), true)->d;
				break;
			case 'scheduled-to':
				$appointment = Appointment::find($patient['appointment_id']);
				$provider = User::find($appointment->provider_id);
				$provider = $provider->title . ' ' . $provider->lastname . ', ' . $provider->firstname;
				$practice = Practice::find($appointment->practice_id);
				$practice = $practice->name;
				return $provider . ' from ' . $practice;
				break;
			case 'last-scheduled-to':
				return '-';
				break;
			default:
				return '-';
				break;
		}
	}

}
