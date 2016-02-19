<?php

namespace myocuhub\Services;

use myocuhub\Models\Action;
use myocuhub\Models\Appointment;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\Kpi;

class CareConsoleService {

	public function __construct() {
	}

	/**
	 * @param $kpiName
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */

	public function formatActions($actions) {
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

	public function PatientListingData($headers, $patients) {
		$headerData = [];
		$patientsData = [];
		$listing = [];
		$i = 0;
		$fields = [];
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
