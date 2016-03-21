<?php

namespace myocuhub\Http\Controllers\Appointment;

use Auth;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use myocuhub\Facades\WebScheduling4PC;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\User;

class AppointmentController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	function __construct() {
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
		$locationID = $request->input('location_id');
		$referraltype_id = $request->input('referraltype_id');
		$action = $request->input('action');

		$data = [];
		$data['provider_name'] = User::find($provider_id)->name;
		$data['practice_name'] = Practice::find($practice_id)->name;
		$data['referraltype_id'] = $referraltype_id;
		$data['appointment_date'] = $appointment_date;
		$data['appointment_time'] = $appointment_time;
		$data['practice_id'] = $practice_id;
		$data['provider_id'] = $provider_id;
		$data['appointment_type_name'] = $appointment_type_name;
		$data['appointment_type_id'] = $appointment_type_id;
		$data['location'] = $location;
		$data['location_id'] = $locationID;
		$data['action'] = $action;
		$data['patient_id'] = $patient_id;
		$patient = Patient::find($patient_id);
		$data['patient_name'] = $patient->firstname . ' ' . $patient->lastname;
		$data['schedule-patient'] = true;

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

		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$networkID = $network->id;
		$patientID = $request->input('patient_id');
		$providerID = $request->input('provider_id');
		$locationID = $request->input('location_id');
		$practiceID = $request->input('practice_id');
		$appointmentType = $request->input('appointment_type');
		$appointmentTypeKey = $request->input('appointment_type_key');
		$appointmentTime = $request->input('appointment_time');
		$patient = Patient::find($patientID);

		$apptInfo['LocKey'] = 3839;
		$apptInfo['AcctKey'] = 8042;
		$apptInfo['ApptTypeKey'] = $appointmentTypeKey;
		$startime = new DateTime($appointmentTime);
		$apptInfo['ApptStartDateTime'] = $startime->format('m/d/Y H:m');
		$apptInfo['PatientData']['Title'] = $patient->title;
		$apptInfo['PatientData']['FirstName'] = $patient->firstname;
		$apptInfo['PatientData']['LastName'] = $patient->lastname;
		$apptInfo['PatientData']['Address1'] = $patient->addressline1;
		$apptInfo['PatientData']['Address2'] = $patient->addressline2;
		$apptInfo['PatientData']['City'] = $patient->city;
		$apptInfo['PatientData']['State'] = $patient->state;
		$apptInfo['PatientData']['Zip'] = $patient->zip;
		$apptInfo['PatientData']['Country'] = $patient->country;
		$apptInfo['PatientData']['HomePhone'] = ($patient->homephone) ? $patient->homephone : '';
		$apptInfo['PatientData']['WorkPhone'] = ($patient->workphone) ? $patient->workphone : '';
		$apptInfo['PatientData']['CellPhone'] = $patient->cellphone;
		$apptInfo['PatientData']['Email'] = $patient->email;
		$birthdate = new DateTime($patient->birthdate);
		$apptInfo['PatientData']['DOB'] = $birthdate->format('Y-m-d') . 'T00:00:00';

		$apptInfo['PatientData']['PreferredLanguage'] = $patient->preferredlanguage;
		$apptInfo['PatientData']['Gender'] = $patient->gender;
		$apptInfo['PatientData']['L4DSSN'] = $patient->lastfourssn;
//        $apptInfo['PatientData']['InsuranceCarrier'] = $patient->insurancecarrier;
		$apptInfo['PatientData']['InsuranceCarrier'] = 1;

		$apptInfo['PatientData']['OtherInsurance'] = '';
		$apptInfo['PatientData']['SubscriberName'] = '';
		$birthdate = new DateTime($patient->birthdate);
		$apptInfo['PatientData']['SubscriberDOB'] = $birthdate->format('Y-m-d') . 'T00:00:00';
		$apptInfo['PatientData']['SubscriberID'] = '';
		$apptInfo['PatientData']['GroupNum'] = '';
		$apptInfo['PatientData']['RelationshipToPatient'] = '';
		$apptInfo['PatientData']['CustomerServiceNumForInsCarrier'] = '';
		$apptInfo['PatientData']['ReferredBy'] = '';
		$apptInfo['PatientData']['NotesBox'] = '';
		$apptInfo['PatientData']['ReferredBy2'] = '';
		$apptInfo['PatientData']['ReferredBy3'] = '';
		$apptInfo['PatientData']['ReferredBy4'] = '';
		$apptInfo['PatientData']['ReferredBy5'] = '';
		$apptInfo['PatientData']['IsPatKnown'] = ($patient->fpc_id) ? '1' : '0';

		$appointment = new Appointment;
		$appointment->provider_id = $providerID;
		$appointment->practice_id = $practiceID;
		$appointment->location_id = $locationID;
		$appointment->patient_id = $patientID;
		$appointment->network_id = $networkID;
		$appointment->appointmenttype_key = $appointmentTypeKey;
		$appointment->appointmenttype = $appointmentType;
		$date = new DateTime($appointmentTime);
		$appointment->start_datetime = $date->format('Y-m-d H:m:s');
		$apptResult = WebScheduling4PC::requestApptInsert($apptInfo);
		$result = '';
		if ($apptResult['RequestApptInsertResult']['ApptKey'] != -1) {
			$appointment->fpc_id = $apptResult['RequestApptInsertResult']['ApptKey'];
			$result = 'Appointment Scheduled Successfully';
		} else {
			$result = 'Appointment could not be scheduled with 4PC at this moment. Please try again or contact our support team.';
			return $result;
		}

		$appointment->save();

		$practice = Practice::find($appointment->practice_id);
		$appt['practice_name'] = $practice->name;
		$appt['appt_type'] = $appointmentType;
		$provider = User::find($appointment->provider_id);
		$appt['provider_name'] = $provider->title . ' ' . $provider->lastname . ', ' . $provider->firstname;
		$location = PracticeLocation::find($appointment->location_id);
		$appt['location_name'] = $location->locationname;
		$appt['location_address'] = $location->addressline1 . ', ' . $location->addressline2 . ', ' . $location->city . ', ' . $location->state . ', ' . $location->zip;
		$appt['practice_phone'] = $location->phone;
		$date = new DateTime($appointment->start_datetime);
		$appt['appt_startdate'] = $date->format('F d, Y');
		$appt['appt_starttime'] = $date->format('h m A');
		$appt['patient_name'] = $patient->title . ' ' . $patient->lastname . ', ' . $patient->firstname;
		$appt['patient_email'] = $patient->email;
		$appt['patient_phone'] = $patient->cellphone;
		$appt['patient_ssn'] = $patient->lastfourssn;
		$appt['patient_address'] = $patient->addressline1 . ', ' . $patient->addressline2 . ', ' . $patient->city . ', ' . $patient->state . ', ' . $patient->zip;
		$appt['patient_dob'] = $date->format('F d, Y');

		$mailToPatient = Mail::send('emails.appt-confirmation-patient', ['appt' => $appt], function ($m) use ($patient) {
			$m->from('support@ocuhub.com', 'Ocuhub');
			$m->to($patient->email, $patient->lastname . ', ' . $patient->firstname)->subject('Patient Appointment has been scheduled');
		});

		$mailToProvider = Mail::send('emails.appt-confirmation-provider', ['appt' => $appt], function ($m) use ($provider) {
			$m->from('support@ocuhub.com', 'Ocuhub');
			$m->to($provider->email, $provider->lastname . ', ' . $provider->firstname)->subject('Provider has been scheduled');
		});

		$careconsole = Careconsole::where('patient_id', $patientID)
			->orderBy('created_at', 'desc')
			->first();

		if ($careconsole != NULL) {
			$careconsole->appointment_id = $appointment->id;
			$careconsole->stage_id = 2;
			$careconsole->recall_date = null;
			$careconsole->archived_date = null;
			$date = new DateTime();
			$careconsole->stage_updated_at = $date->format('Y-m-d H:m:s');
			$careconsole->update();

			$provider = User::find($providerID);
			$scheduledTo = $provider->title . ' ' . $provider->lastname . ', ' . $provider->firstname;
			$notes = $scheduledTo . '</br>' . $appointment->start_datetime . '</br>' . $appointmentType;

			$contactDate = new DateTime();
			$contactHistory = new ContactHistory;
			$contactHistory->action_id = 9;
			$contactHistory->action_result_id = 14;
			$contactHistory->notes = $notes;
			$contactHistory->console_id = $careconsole->id;
			$contactHistory->contact_activity_date = $contactDate->format('Y-m-d H:m:s');
			$contactHistory->save();
		}

		return $result;
	}
}
