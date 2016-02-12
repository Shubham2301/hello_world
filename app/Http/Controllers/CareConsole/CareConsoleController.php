<?php

namespace myocuhub\Http\Controllers\CareConsole;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Action;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Network;
use myocuhub\Patient;
use myocuhub\Services\ActionService;
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

	public function __construct(KPIService $KPIService, ActionService $ActionService) {
		$this->KPIService = $KPIService;
		$this->ActionService = $ActionService;
	}

	public function index() {
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

	public function getDrilldownData(Request $request) {
		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$networkID = $network->network_id;
		$stageID = $request->stage;
		$kpiName = $request->kpi;
		$patients = [];
		$patientsData = [];
		$actions = [];

		if ($kpiName !== '' && isset($stageID)) {
			$patients = $this->KPIService->getPatients($kpiName, $networkID, $stageID);
		} else if (isset($stageID)) {
			$patients = CareConsole::getStagePatients($networkID, $stageID);
		}
		$i = 0;
		if (sizeof($patients) === 0) {
			return json_encode([]);
		}

		foreach ($patients as $patient) {
			$patientsData[$i]['console_id'] = $patient['id'];
			$patientsData[$i]['patient_id'] = $patient['patient_id'];
			$patientsData[$i]['name'] = $patient['firstname'] . ' ' . $patient['lastname'];
			$patientsData[$i]['phone'] = $patient['cellphone'];
			$patientsData[$i]['appointment_date'] = '-';
			$patientsData[$i]['scheduled_to'] = '-';
			$patientsData[$i]['request_received'] = '-';
			$i++;
		}

		$actions = CareconsoleStage::find($stageID)->actions;
		$i = 0;
		foreach ($actions as $action) {
			$actionsData[$i]['id'] = $action->id;
			$actionsData[$i]['stage_id'] = $action->stage_id;
			$actionsData[$i]['name'] = $action->name;
			$actionsData[$i]['display_name'] = $action->display_name;
			$actionsData[$i]['action_results'] = Action::find($action->id)->actionResults;
			$i++;
		}

		$drilldown['actions'] = $actionsData;
		$drilldown['patients'] = $patientsData;

		return json_encode($drilldown);
	}

	public function action(Request $request) {
		$actionID = $request->action_id;
		$actionResultID = $request->action_result_id;
		$date = 'CURRENT_TIMESTAMP';
		$notes = $request->notes;
		$consoleID = $request->console_id;
		$contactHistoryID = $this->ActionService->userAction($actionID, $actionResultID, $date, $notes, $consoleID);
		return json_encode($contactHistoryID);
	}

	public function searchPatients(Request $request) {
		$patients = Patient::getPatientsByName($request->name);
		$i = 0;
		$results = [];
		foreach ($patients as $patient) {
			$console = CareConsole::where('patient_id', $patient->id)->first();
			$results[$i]['id'] = $patient->id;
			$results[$i]['stage_name'] = CareconsoleStage::find($console->stage_id)->display_name;
			$i++;
		}
		return json_encode($results);
	}

}
