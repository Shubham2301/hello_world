<?php

namespace myocuhub\Http\Controllers\Appointment;

use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Practice;
use myocuhub\Patient;
use myocuhub\Services\FourPatientCare\FourPatientCare;
use myocuhub\User;

class AppointmentController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	private $fourPatientCare;

	function __construct(FourPatientCare $fourPatientCare) {
		$this->fourPatientCare = $fourPatientCare;
	}

	public function index(Request $request) {
		$provider_id = $request->input('provider_id');
		$practice_id = $request->input('practice_id');
		$patient_id = $request->input('patient_id');
		$appointment_date = $request->input('appointment_date');
		$appointment_time = $request->input('appointment_time');
		$appointment_type_name = $request->input('appointment_type_name');
		$appointment_type_id = $request->input('appointment_type_id');
		$location = $request->input('location');
		$referraltype_id = $request->input('referraltype_id');
		$action = $request->input('action');

		$data = [];
		$data['provider_name'] = User::find($provider_id)->name;
		$data['practice_name'] = Practice::find($practice_id)->name;
		$data['referraltype_id'] = $referraltype_id;
		$data['appointment_date'] = $appointment_date;
		$data['appointment_time'] = $appointment_time;
		$data['appointment_type_name'] = $appointment_type_name;
		$data['appointment_type_id'] = $appointment_type_id;
		$data['location'] = $location;
		$data['action'] = $action;
        $data['patient_id'] = $patient_id;
		$patient = Patient::find($patient_id);
		$data['patient_name'] = $patient->firstname . ' ' . $patient->lastname;

		return view('appointment.index')->with('data', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
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

	public function schedule(Request $request) {
		$apptInfo = array();

		$patientID = $request->input('patient_id');
		$providerID = $request->input('provider_id');
		$locationID = $request->input('location_id');
		$appointmentType = $request->input('appointment_type');
		$appointmentTime = $request->input('appointment_time');
		$patient = Patient::find($patientID);

		$apptInfo['LocKey'] = 3839;
		$apptInfo['AcctKey'] = 8042;
		$apptInfo['ApptTypeKey'] = $appointmentType;
		$apptInfo['ApptStartDateTime'] = $appointmentTime;
//        $apptInfo['PatientData']['Title'] = $patient->title;
		$apptInfo['PatientData']['FirstName'] = $patient->firstname;
		$apptInfo['PatientData']['LastName'] = $patient->lastname;
//        $apptInfo['PatientData']['Address1'] = $patient->addressline1;
		//        $apptInfo['PatientData']['Address2'] = $patient->addressline2;
		//        $apptInfo['PatientData']['City'] = $patient->city;
		//        $apptInfo['PatientData']['State'] = $patient->state;
		//        $apptInfo['PatientData']['Zip'] = $patient->zip;
		//        $apptInfo['PatientData']['Country'] = $patient->country;
		//        $apptInfo['PatientData']['HomePhone'] = $patient->homephone;
		$apptInfo['PatientData']['HomePhone'] = '9876543219';
//        $apptInfo['PatientData']['WorkPhone'] = $patient->workphone;
		//        $apptInfo['PatientData']['CellPhone'] = $patient->cellphone;
		//        $apptInfo['PatientData']['Email'] = $patient->email;
		//$apptInfo['PatientData']['DOB'] = $patient->birthdate; // convert to MM/DD/YYYY HH:MM 24
		$apptInfo['PatientData']['DOB'] = '2010-10-10'; // convert to MM/DD/YYYY HH:MM 24

		$apptInfo['PatientData']['PreferredLanguage'] = $patient->preferredlanguage;
		$apptInfo['PatientData']['Gender'] = $patient->gender;
		$apptInfo['PatientData']['L4dssn'] = $patient->lastfourssn;
//        $apptInfo['PatientData']['InsuranceCarrier'] = $patient->insurancecarrier;
		$apptInfo['PatientData']['InsuranceCarrier'] = 100;

//        $apptInfo['PatientData']['OtherInsurance'] = '';
		//        $apptInfo['PatientData']['SubscriberName'] = '';
		$apptInfo['PatientData']['SubscriberDOB'] = '2010-10-10';
//        $apptInfo['PatientData']['SubscriberID'] = '';
		//        $apptInfo['PatientData']['GroupNum'] = '';
		//        $apptInfo['PatientData']['RelationshipToPatient'] = '';
		//        $apptInfo['PatientData']['CustomerServiceNumForInsCarrier'] = '';
		$apptInfo['PatientData']['ReferredBy'] = '';
		$apptInfo['PatientData']['IsPatKnown'] = '1';

		$apptResult = $this->fourPatientCare->requestApptInsert($apptInfo);

		return $apptResult;
	}
}
