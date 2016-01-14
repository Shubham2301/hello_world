<?php

namespace myocuhub\Http\Controllers\Appointment;

use Illuminate\Http\Request;
use myocuhub\User;
use myocuhub\Patient;
use myocuhub\Models\Practice;

use myocuhub\Services\FourPatientCare\FourPatientCare;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $fourPatientCare;

    function __construct(FourPatientCare $fourPatientCare)
    {
        $this->fourPatientCare = $fourPatientCare;
    }

    public function index(Request $request)
    {
        $provider_id = $request->input('provider_id');
        $practice_id = $request->input('practice_id');
        $patient_id = $request->input('patient_id');
        
        $data = [];
        $data['provider_name'] = User::find($provider_id)->name;
        $data['practice_name'] = Practice::find($practice_id)->name;
        $patient = Patient::find($patient_id);
        $data['patient_name'] = $patient->firstname.' '.$patient->lastname;
        
        return view('appointment.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function schedule(Request $request){
        $apptInfo = array();

        $patientID = $request->input('patient_id');
        $providerID = $request->input('provider_id');
        $locationID = $request->input('location_id');
        $AppointmentType = $request->input('appointment_type');
        $AppointmentTime = $request->input('appointment_time');
        $patient = Patient::find($patientID);

        $apptInfo['LocKey'] = $providerID;
        $apptInfo['AcctKey'] = $locationID;
        $apptInfo['ApptTypeKey'] = $AppointmentType;
        $apptInfo['ApptStartDateTime'] = $AppointmentTime;
        $apptInfo['PatientData']['Title'] = $patient->title;
        $apptInfo['PatientData']['FirstName'] = $patient->firstname;
        $apptInfo['PatientData']['LastName'] = $patient->lastname;
        $apptInfo['PatientData']['Address1'] = $patient->addressline1;
        $apptInfo['PatientData']['Address2'] = $patient->addressline2;
        $apptInfo['PatientData']['City'] = $patient->city;
        $apptInfo['PatientData']['State'] = $patient->state;
        $apptInfo['PatientData']['Zip'] = $patient->zip;
        $apptInfo['PatientData']['Country'] = $patient->country;
        $apptInfo['PatientData']['HomePhone'] = $patient->homephone;
        $apptInfo['PatientData']['WorkPhone'] = $patient->workphone;
        $apptInfo['PatientData']['CellPhone'] = $patient->cellphone;
        $apptInfo['PatientData']['Email'] = $patient->email;
        $apptInfo['PatientData']['DOB'] = $patient->birthdate;
        $apptInfo['PatientData']['PreferredLanguage'] = $patient->preferredlanguage;
        $apptInfo['PatientData']['Gender'] = $patient->gender;
        $apptInfo['PatientData']['L4dssn'] = $patient->lastfourssn;
        $apptInfo['PatientData']['InsuranceCarrier'] = $patient->insurancecarrier;
        
        /*
        
        Add once data is available.

        $apptInfo['PatientData']['OtherInsurance'] = $patient->lastname;
        $apptInfo['PatientData']['SubscriberName'] = $patient->lastname;
        $apptInfo['PatientData']['SubscriberDOB'] = $patient->lastname;
        $apptInfo['PatientData']['SubscriberID'] = $patient->lastname;
        $apptInfo['PatientData']['GroupNum'] = $patient->lastname;
        $apptInfo['PatientData']['RelationshipToPatient'] = $patient->lastname;
        $apptInfo['PatientData']['CustomerServiceNumForInsCarrier'] = $patient->lastname;
        $apptInfo['PatientData']['ReferredBy'] = $patient->lastname;

        */

        $apptResult = $this->fourPatientCare->requestApptInsert($apptInfo);

        return $apptResult;
    }
}
