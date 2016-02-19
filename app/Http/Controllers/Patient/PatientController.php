<?php

namespace myocuhub\Http\Controllers\Patient;

use Auth;
use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Careconsole;
use myocuhub\Models\Ccda;
use myocuhub\Models\ImportHistory;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\ReferralHistory;
use myocuhub\Patient;
use myocuhub\User;

class PatientController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {

		$data = array();
		$data['admin'] = false;
		if ($request->has('referraltype_id')) {
			$data['referraltype_id'] = $request->input('referraltype_id');
		}
		if ($request->has('action')) {
			$data['action'] = $request->input('action');
		}
		$practicedata = Practice::all()->lists('name', 'id')->toArray();
		return view('patient.index')->with('data', $data)->with('practice_data', $practicedata);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request) {

		$data = array();
		$data = Patient::find(1)->toArray();
		$data = array_fill_keys(array_keys($data), null);
		$data['admin'] = true;
		$data['back_btn'] = 'back_to_select_patient_btn';
		$data['url'] = '/administration/patients/add';
		if ($request->has('referraltype_id')) {
			$data['referraltype_id'] = $request->input('referraltype_id');
			$data['admin'] = false;
		}
		if ($request->has('action')) {
			$data['action'] = $request->input('action');
		}
		return view('patient.admin')->with('data', $data);
	}

	public function createByAdmin() {
		$data = array();
		$data = Patient::find(1)->toArray();
		$data = array_fill_keys(array_keys($data), null);
		$data['admin'] = true;
		$data['back_btn'] = 'back_to_admin_patient_btn';
		$data['url'] = '/administration/patients/add';
		$data['referraltype_id'] = -1;
		return view('patient.admin')->with('data', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$networkID = $network->network_id;

		$patient = new Patient;
		$patient->firstname = $request->input('firstname');
		$patient->lastname = $request->input('lastname');
		$patient->email = $request->input('email');
		$patient->gender = $request->input('gender');
		$patient->lastfourssn = $request->input('lastfourssn');
		$patient->addressline1 = $request->input('addressline1');
		$patient->addressline2 = $request->input('addressline2');
		$patient->city = $request->input('city');
		$patient->zip = $request->input('zip');
		$patient->birthdate = $request->input('birthdate');
		$patient->preferredlanguage = $request->input('preferredlanguage');
		$patient->cellphone = $request->input('cellphone');
		$patient->state = $request->input('state');
		$patient->save();

		$importHistory = new ImportHistory;
		$importHistory->network_id = $networkID;
		$importHistory->save();

		$careconsole = new Careconsole;
		$careconsole->import_id = $importHistory->id;
		$careconsole->patient_id = $patient->id;
		$careconsole->stage_id = 1;
		$careconsole->save();

		$action = "new patient($patient->id) created and added to console($careconsole->id)";
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		if (!$request->has('action')) {
			return redirect('/administration/patients');
		}

		$path = 'providers?referraltype_id=' . $request->input('referraltype_id') . '&action=' . $request->input('action') . '&patient_id=' . $patient->id;
		return redirect($path);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request) {
		$id = $request->input('id');

		$patientData = [];

		$patient = Patient::find($id);

//		$patient->birthdate = date("d F Y", strtotime($patient->birthdate));
		$careconsole = Careconsole::where('patient_id', '=', $id)->first();
		$insurance = PatientInsurance::where('patient_id', '=', $id)->first(['insurance_carrier']);
		if (isset($careconsole->referral_id)) {
			$referral_history = ReferralHistory::find($careconsole->referral_id);
		}

		if (isset($referral_history)) {
			$referred_to_practice = Practice::find($referral_history->referred_to_practice_id);
			$patientData['referred_to_practice'] = $referred_to_practice->name;
			$referred_to_practice_user = User::find($referral_history->referred_to_practice_user_id);
			$patientData['referred_to_practice_user'] = $referred_to_practice_user->name;
			$patientData['referred_by_practice'] = $referral_history->referred_by_practice;
			$patientData['referred_by_provider'] = $referral_history->referred_by_provider;
		} else {
			$patientData['referred_to_practice'] = '';
			$patientData['referred_to_practice_user'] = '';
			$patientData['referred_by_practice'] = '';
			$patientData['referred_by_provider'] = '';
		}
		if (isset($insurance)) {
			$patientData['insurance'] = $insurance->insurance_carrier;
		} else {
			$patientData['insurance'] = '';
		}

		$patientData['firstname'] = $patient->firstname;
		$patientData['lastname'] = $patient->lastname;
		$patientData['email'] = $patient->email;
		$patientData['lastfourssn'] = $patient->lastfourssn;
		$patientData['addressline1'] = $patient->addressline1;
		$patientData['addressline2'] = $patient->addressline2;
		$patientData['city'] = $patient->city;
		$patientData['id'] = $patient->id;
		$patientData['cellphone'] = $patient->cellphone;
		$patientData['birthdate'] = date("d F Y", strtotime($patient->birthdate));

		$ccda = Ccda::where('patient_id', $id)->first();
		if (!($ccda)) {
			$patientData['ccda'] = '0';
		}

		return json_encode($patientData);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {

		$data = array();
		$data = Patient::find($id);
		if (!$data) {
			$data['url'] = '/administration/patients/add';
			$data = array_fill_keys(array_keys($data), null);
		}
		$data['admin'] = true;
		$data['back_btn'] = 'back_to_admin_patient_btn';
		$data['url'] = '/administration/patients/update/' . $id;
		$data['referraltype_id'] = -1;

		return view('patient.admin')->with('data', $data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$patient = Patient::find($id);
		$patient->update($request->input());
		$action = 'update patient of id =' . $id;
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
		return redirect('/administration/patients');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id) {
		$patient = Patient::where('id', $id)->delete();
		$action = 'delete patient of id =' . $id;
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
	}

	public function search(Request $request) {

		$filters = json_decode($request->input('data'), true);

		$patients = Patient::getPatients($filters)->paginate(5);
		$data = [];

		$data[0]['total'] = $patients->total();
		$data[0]['lastpage'] = $patients->lastPage();
		$data[0]['currentPage'] = $patients->currentPage();
		$i = 0;
		foreach ($patients as $patient) {
			$data[$i]['id'] = $patient->id;
			$data[$i]['fname'] = $patient->firstname;
			$data[$i]['lname'] = $patient->lastname;
			$data[$i]['email'] = $patient->email;
			$data[$i]['phone'] = $patient->cellphone;
			$data[$i]['lastfourssn'] = $patient->lastfourssn;
			$data[$i]['addressline1'] = $patient->addressline1;
			$data[$i]['addressline2'] = $patient->addressline2;
			$data[$i]['city'] = $patient->city;
			$data[$i]['birthdate'] = date('Y-m-d', strtotime($patient->birthdate));
			$i++;
		}

		return json_encode($data);
	}

	public function administration(Request $request) {
		$data = array();
		$practicedata = array();
		$data['admin'] = true;
		$data['patient_active'] = true;
		$practicedata = Practice::all()->lists('name', 'id')->toArray();
		return view('patient.admin')->with('data', $data)->with('practice_data', $practicedata);
	}
}
