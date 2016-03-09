<?php

namespace myocuhub\Http\Controllers\CareConsole;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Action;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
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
	}

	public function index() {
		// TODO: add task moveRecallPatientsToConsoleAsPending() on nightly CRON
		$this->CareConsoleService->moveRecallPatientsToConsoleAsPending();
		$overview = $this->getOverviewData();
		return view('careconsole.index')->with('overview', $overview);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
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
		return $overview;
	}

	/**
	 * @param Request $request
	 */
	public function getDrilldownData(Request $request) {
		$stageID = $request->stage;
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
		$drilldown['listing'] = view('careconsole.listing')->with('listing', $listing)->with('actions', $actions)->render();

		return json_encode($drilldown);
	}

	/**
	 * @param Request $request
	 */
	public function action(Request $request) {
		$actionID = $request->action_id;
		$actionResultID = $request->action_result_id;
		$recallDate = $request->recall_date;
		$notes = $request->notes;
		$consoleID = $request->console_id;

		$contactHistoryID = $this->ActionService->userAction($actionID, $actionResultID, $recallDate, $notes, $consoleID);
		return json_encode($contactHistoryID);
	}

	/**
	 * @param Request $request
	 */
	public function searchPatients(Request $request) {
		$networkID = User::getNetwork(Auth::user()->id)->network_id;
		$patients = Patient::getPatientsByName($request->name,$networkID);
		$i = 0;
		$results = [];

		foreach ($patients as $patient) {
			$console = Careconsole::where('patient_id', $patient->id)->first();
			if ($console) {
				$results[$i]['archived_date'] = null;
				$results[$i]['recall_date'] = null;
				if($console->archived_date)
					$results[$i]['archived_date'] =$console->archived_date;
				if($console->recall_date)
					$results[$i]['recall_date'] =$console->recall_date;

				$patient['appointment_id'] = $console->appointment_id;
				$results[$i]['id'] = $patient->id;
				$results[$i]['console_id'] = $console->id;
				$results[$i]['stage_id'] = $console->stage_id;
				$results[$i]['name'] = $patient->lastname . ', ' . $patient->firstname;
				$results[$i]['stage_name'] = CareconsoleStage::find($console->stage_id)->display_name;
				$results[$i]['stage_color'] = CareconsoleStage::find($console->stage_id)->color_indicator;
				if($console->recall_date)
					$results[$i]['actions'] = $this->CareConsoleService->getActions(8);
				else if($console->archived_date)
					$results[$i]['actions'] = $this->CareConsoleService->getActions(6);
				else
					$results[$i]['actions'] = $this->CareConsoleService->getActions($console->stage_id);
				$results[$i]['scheduled_to'] = '-';
				$results[$i]['appointment_date'] = '-';
				if ($patient['appointment_id']) {
					$appointment = Appointment::find($patient['appointment_id']);
					$provider = User::find($appointment->provider_id);
					$results[$i]['scheduled_to'] = $provider->lastname . ', ' . $provider->firstname;
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
		$listing = $this->CareConsoleService->getBucketPatientsListing($bucketID);
		$actions = $this->CareConsoleService->getActions($bucketID);

		$drilldown['actions'] = (sizeof($actions) === 0) ? [] : $actions;
		$drilldown['listing'] = view('careconsole.listing')->with('listing', $listing)->with('actions', $actions)->render();
		return json_encode($drilldown);
	}
	public function getPatientRecords(Request $request) {
		$consoleID = $request->consoleID;
		$console = Careconsole::find($consoleID);
		$patient = Patient::find($console->patient_id);
		$data = [];
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
}
