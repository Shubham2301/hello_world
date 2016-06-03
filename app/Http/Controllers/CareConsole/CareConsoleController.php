<?php

namespace myocuhub\Http\Controllers\CareConsole;

use Auth;
use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Action;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Models\MessageTemplate;
use myocuhub\Models\Practice;
use myocuhub\Network;
use myocuhub\Patient;
use myocuhub\Services\ActionService;
use myocuhub\Services\CareConsoleService;
use myocuhub\Services\KPI\KPIService;
use myocuhub\User;

class CareConsoleController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	private $KPIService;
	private $ActionService;
	private $CareConsoleService;

	public function __construct(KPIService $KPIService, ActionService $ActionService, CareConsoleService $CareConsoleService) {
		$this->KPIService = $KPIService;
		$this->ActionService = $ActionService;
		$this->CareConsoleService = $CareConsoleService;
		$this->middleware('role:care-console,0');
	}

	public function index(Request $request) {
		
		$action = 'Accessed Careconsole';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		$this->CareConsoleService->moveRecallPatientsToConsoleAsPending();
		$overview = $this->getOverviewData();
		return view('careconsole.index')->with('overview', $overview);
	}

	/**
	 * @return mixed
	 */
	public function getOverviewData() {
		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$networkID = $network->network_id;
		$careconsoleStages = Network::find($networkID)->careconsoleStages;
		$overview = array();
		$i = 0;
		foreach ($careconsoleStages as $stage) {
			$overview['stages'][$i]['id'] = $stage->stage_id;
			$overview['stages'][$i]['name'] = $stage->name;
			$overview['stages'][$i]['display_name'] = $stage->display_name;
			$overview['stages'][$i]['color_indicator'] = $stage->color_indicator;
			$overview['stages'][$i]['description'] = $stage->description;
			$overview['stages'][$i]['abbr'] = $stage->abbr;

			$kpis = CareconsoleStage::find($stage->stage_id)->kpi;
			$j = 0;
			foreach ($kpis as $kpi) {
				$overview['stages'][$i]['kpis'][$j]['id'] = $kpi->id;
				$overview['stages'][$i]['kpis'][$j]['name'] = $kpi->name;
				$overview['stages'][$i]['kpis'][$j]['display_name'] = $kpi->display_name;
				$overview['stages'][$i]['kpis'][$j]['color_indicator'] = $kpi->color_indicator;
				$overview['stages'][$i]['kpis'][$j]['description'] = $kpi->description;
				$overview['stages'][$i]['kpis'][$j]['count'] = $this->KPIService->getCount($kpi->name, $networkID, $stage->stage_id);
				$j++;
			}
			$overview['stages'][$i]['kpi_count'] = $j;
			$i++;
		}

		$overview['network_practices'] = Network::find(session('network-id'))->practices;
		$overview['appointment_types'] = $this->getAppointmentTypes();
		
		$overview['request_for_appointment']['email'] = MessageTemplate::getTemplate('email', 'request_for_appointment', $networkID);
		$overview['request_for_appointment']['phone'] = MessageTemplate::getTemplate('phone', 'request_for_appointment', $networkID);
		$overview['request_for_appointment']['sms'] = MessageTemplate::getTemplate('sms', 'request_for_appointment', $networkID);

		return $overview;
	}

	/**
	 * @param Request $request
	 */
	public function getDrilldownData(Request $request) {
		$stageID = $request->stage;
		if($stageID == '-1') {
			$stageID = 1;
		}
		$kpiName = $request->kpi;
		$sortParams = [];
		$sortField = $request->sort_field;
		$sortOrder = $request->sort_order;
		$lower_limit = $request->lower_limit;
		$upper_limit = $request->upper_limit;

		$listing = $this->CareConsoleService->getPatientListing($stageID, $kpiName, $sortField, $sortOrder);
		if ($upper_limit != -1) {
			$listing = $this->CareConsoleService->getPatientListing($stageID, $kpiName, $sortField, $sortOrder, $lower_limit, $upper_limit);
		}

		$actions = $this->CareConsoleService->getActions($stageID);
		$controls = $this->CareConsoleService->getControls($stageID);
		$drilldown['controls'] = (sizeof($controls) === 0) ? '' : view('careconsole.controls')->with('controls', $controls)->render();
		$drilldown['actions'] = (sizeof($actions) === 0) ? [] : $actions;
		$drilldown['listing_header'] = view('careconsole.listing_header')->with('listing', $listing)->render();
		$drilldown['listing_content'] = view('careconsole.listing_patient')->with('listing', $listing)->with('actions', $actions)->render();
		$drilldown['lastpage'] = $listing['lastpage'];
		return json_encode($drilldown);
	}

	/**
	 * @param Request $request
	 */
	public function action(Request $request) {
		$actionID = $request->action_id;
		$actionResultID = $request->action_result_id;
		$recallDate = $request->recall_date;
		$manualAppointmentData = [];
		$manualAppointmentData['appointment_date'] = $request->manual_appointment_date;
		$manualAppointmentData['practice_id'] = $request->manual_appointment_practice;
		$manualAppointmentData['location_id'] = $request->manual_appointment_location;
		$manualAppointmentData['provider_id'] = $request->manual_appointment_provider;
		$manualAppointmentData['appointment_type'] = $request->manual_appointment_appointment_type;
		$manualAppointmentData['custom_appointment_type'] = $request->custom_appointment_type;
		$manualAppointmentData['referredby_practice'] = $request->manual_referredby_practice;
		$manualAppointmentData['referredby_provider'] = $request->manual_referredby_provider;
		$notes = $request->notes;
		$consoleID = $request->console_id;
		$message = $request->request_message;
		$contactHistoryID = $this->ActionService->userAction($actionID, $actionResultID, $recallDate, $notes, $message, $consoleID, $manualAppointmentData);
		$stage = CareConsole::find($consoleID)->stage;
		$patientStage['id'] = $stage->id;
		$patientStage['name'] = $stage->display_name;

		
		$actionName = Action::find($actionID)->display_name;
		$action = "Performed Action : '$actionName' on Console Entry : $consoleID in the Careconsole";
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return json_encode($patientStage);
	}

	/**
	 * @param Request $request
	 */
	public function searchPatients(Request $request) {

		if (session('user-level') == 1) {
			$practices = Patient::where('firstname', 'LIKE', '%' . $tosearchdata['value'] . '%')
				->orWhere('middlename', 'LIKE', '%' . $tosearchdata['value'] . '%')
				->orWhere('lastname', 'LIKE', '%' . $tosearchdata['value'] . '%')
				->get(['*', 'patients.id']);
		} else {
			$patients = Patient::getPatientsByName($request->name, session('network-id'));
		}
		$i = 0;
		$results = [];

		foreach ($patients as $patient) {
			$console = Careconsole::where('patient_id', $patient->id)->first();
			if ($console) {
				$results[$i]['archived_date'] = null;
				$results[$i]['recall_date'] = null;
				if ($console->archived_date) {
					$results[$i]['archived_date'] = $console->archived_date;
				}

				if ($console->recall_date) {
					$results[$i]['recall_date'] = $console->recall_date;
				}

				$patient['appointment_id'] = $console->appointment_id;
				$results[$i]['id'] = $patient->id;
				$results[$i]['console_id'] = $console->id;
				$results[$i]['stage_id'] = $console->stage_id;
				$results[$i]['name'] = $patient->lastname . ', ' . $patient->firstname;
				$results[$i]['stage_name'] = CareconsoleStage::find($console->stage_id)->display_name;
				$results[$i]['stage_color'] = CareconsoleStage::find($console->stage_id)->color_indicator;
				if ($console->recall_date) {
					$results[$i]['actions'] = $this->CareConsoleService->getActions(8);
				} else if ($console->archived_date) {
					$results[$i]['actions'] = $this->CareConsoleService->getActions(6);
				} else {
					$results[$i]['actions'] = $this->CareConsoleService->getActions($console->stage_id);
				}

				$results[$i]['scheduled_to'] = '-';
				$results[$i]['appointment_date'] = '-';
				if ($patient['appointment_id']) {
					$appointment = Appointment::find($patient['appointment_id']);
					$provider = User::find($appointment->provider_id);
					$results[$i]['scheduled_to'] = ($provider) ? $provider->lastname . ', ' . $provider->firstname : '-';
					$results[$i]['appointment_date'] = $this->CareConsoleService->getPatientFieldValue($patient, 'appointment-date');
				}
				$results[$i]['days_pending'] = $this->CareConsoleService->getPatientFieldValue($console, 'days-pending');
				$results[$i]['last_scheduled_to'] = $this->CareConsoleService->getPatientFieldValue($console, 'last-scheduled-to');
				$results[$i]['contact_attempts'] = $this->CareConsoleService->getPatientFieldValue($console, 'contact-attempts');
				$i++;
			}
		}
		return json_encode($results);
	}

	/**
	 * @param Request $request
	 */
	public function getBucketPatients(Request $request) {
		$bucketName = $request->bucket;
		$bucket = CareconsoleStage::where('name', $bucketName)->first();
		$bucketID = $bucket->id;
		$sortField = $request->sort_field;
		$sortOrder = $request->sort_order;
		$listing = $this->CareConsoleService->getBucketPatientsListing($bucketID, $sortField, $sortOrder);
		$actions = $this->CareConsoleService->getActions($bucketID);
		$drilldown['actions'] = (sizeof($actions) === 0) ? [] : $actions;
		$drilldown['listing_header'] = view('careconsole.listing_header')->with('listing', $listing)->render();
		$drilldown['listing_content'] = view('careconsole.listing_patient')->with('listing', $listing)->with('actions', $actions)->render();
		$drilldown['lastpage'] = $listing['lastpage'];
		return json_encode($drilldown);
	}
	public function getPatientRecords(Request $request) {
		$data = [];
		$consoleID = $request->consoleID;
		$console = Careconsole::find($consoleID);
		$patient = Patient::find($console->patient_id);
		$data['patient_id'] = $console->patient_id;
		$data['name'] = $patient->lastname . ', ' . $patient->firstname;
		$data['phone'] = $patient->cellphone;
		$data['actions'] = $this->CareConsoleService->getActions($console->stage_id);
		$data['stageid'] = $console->stage_id;
		$data['priority'] = $console->priority;

		$appointment = Appointment::find($console->appointment_id);
		$provider = null;
		$data['appointment_type'] = '-';
		if ($appointment) {
			$provider = User::find($appointment->provider_id);
			$data['appointment_type'] = $appointment->appointmenttype;
		}

		$data['scheduled_to'] = ($provider) ? $provider->title . ' ' . $provider->lastname . ', ' . $provider->firstname : '-';

		$data['appointment_date'] = ($console->appointment_id) ? $this->CareConsoleService->getPatientFieldValue($console, 'appointment-date') : '-';

		$data['contacts_attempt'] = $this->ActionService->getContactActions($consoleID);

		return json_encode($data);
	}

	public function practiceProviders(Request $request) {
		$practiceID = $request->practiceID;
		$practiceUsers = User::practiceProvidersById($practiceID);
		$i = 0;
		$practiceData = [];
		foreach ($practiceUsers as $user) {
			$practiceData['provider'][$i]['id'] = $user->user_id;
			$practiceData['provider'][$i]['name'] = $user->lastname . ', ' . $user->firstname;
			$i++;
		}
		$practiceData['locations'] = [];

		if(Practice::find($practiceID)){
			$practiceData['locations'] = Practice::find($practiceID)->locations;
		}
		return json_encode($practiceData);
	}

	public function getAppointmentTypes(){
		return config('constants.appointment_types');
	}
}
