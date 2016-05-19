<?php

namespace myocuhub\Http\Controllers\Appointment;

use Auth;
use DateTime;
use Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use myocuhub\Events\AppointmentScheduled;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Facades\WebScheduling4PC;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Models\ReferralHistory;
use myocuhub\Patient;
use myocuhub\User;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
    }

    public function schedule(Request $request)
    {
        $apptStatus = [
            'result' => false,
        ];
        
        $userID = Auth::user()->id;
        $network = User::getNetwork($userID);
        $networkID = $network->id;
        $patientID = $request->input('patient_id');
        $providerID = $request->input('provider_id');
        $locationID = $request->input('location_id');
        $practiceID = $request->input('practice_id');
        $appointmentType = $request->input('appointment_type');
        $appointmentTypeKey = $request->input('appointment_type_key');
        $appointmentDateTime = new DateTime($request->input('appointment_time'));
    
        $appointment = Appointment::schedule([
            'provider_id' => $providerID,
            'practice_id' => $practiceID,
            'location_id' => $locationID,
            'patient_id' => $patientID,
            'network_id' => $networkID,
            'appointmenttype_key' => $appointmentTypeKey,
            'appointmenttype' => $appointmentType,
            'start_datetime' => $appointmentDateTime->format('Y-m-d H:i:s')
        ]);

        if (!$appointment) {
            return $apptStatus;
        } else {
           $status = event(new AppointmentScheduled($request, $appointment));
        }

        $apptStatus['result'] = true;

        $careconsole = Careconsole::where('patient_id', $patientID)->first();

        /**
         * Consider not having all patients in the Console and
         * only managing those patients who pay for the service.
         */

        if ($careconsole != null) {

            $careconsole->appointment_id = $appointment->id;
            $careconsole->stage_id = 2;
            $careconsole->recall_date = null;
            $careconsole->archived_date = null;
            $date = new DateTime();
            $careconsole->stage_updated_at = $date->format('Y-m-d H:i:s');
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
            $contactHistory->contact_activity_date = $contactDate->format('Y-m-d H:i:s');
            $contactHistory->save();

            $referralHistory = ReferralHistory::find($careconsole->referral_id);
            if ($referralHistory == null) {
                $referralHistory = new ReferralHistory;
                $referralHistory->save();
                $careconsole->referral_id = $referralHistory->id;
                $careconsole->save();
            }

            $referralHistory->referred_to_practice_id = $appointment->practice_id;
            $referralHistory->referred_to_location_id = $appointment->location_id;
            $referralHistory->referred_to_practice_user_id = $appointment->provider_id;

            $referralHistory->save();
        }

        return $apptStatus;
    }

	public function partnerWebSchedule($apptInfo, $request)
    {

        $apptResult = WebScheduling4PC::requestApptInsert($apptInfo);

        $result = '';

        if ($apptResult != null) {
            if ($apptResult->RequestApptInsertResult->ApptKey != -1) {

                $result = 'Appointment Scheduled Successfully';
                $action = 'Appointment Scheduled for Provider = ' . $apptInfo['AcctKey'] . ' Location = ' . $apptInfo['LocKey'] . ' on Date ' . $apptInfo['ApptStartDateTime'] . 'for Patient = ' . $apptInfo['PatientData']['FirstName'] . ' ' . $apptInfo['PatientData']['FirstName'];
                $description = '';
                $filename = basename(__FILE__);
                $ip = '';

                Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

                return $apptResult->RequestApptInsertResult->ApptKey;

            } else {
                $result = $apptResult->RequestApptInsertResult->Result;
				$action = 'Attempt to Request Appointment with 4PC failed for Provider = ' . $request->input('provider_id') . ' Location = ' . $request->input('location_id') . ' for Date ' . $request->input('appointment_time') . ' ';
                $description = '';
                $filename = basename(__FILE__);
                $ip = '';
                Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

                return false;
            }
        }

        return false;
    }

    public function index(Request $request)
    {
        $provider_id = $request->input('provider_id');
        $practice_id = $request->input('practice_id');
        $patient_id = $request->input('patient_id');
        $appointment_date = $request->input('appointment_date');
        $appointment_time = $request->input('appointment_time');
        $appointment_type_name = $request->input('appointment_type_name');
        $appointment_type_id = $request->input('appointment_type_id');
        $location = $request->input('location');
        $locationID = $request->input('location_id');
        $locationKey = $request->input('location_code');
        $providerKey = $request->input('provider_acc_key');
        $referraltype_id = $request->input('referraltype_id');
        $action = $request->input('action');

        $data = [];
        $data['location_code'] = $locationKey;
        $data['provider_acc_key'] = $providerKey;
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

        $patientInsurance = PatientInsurance::where('patient_id', $patient_id)->first();
        if (sizeof($patientInsurance) == 0) {
            $patientInsurance = new PatientInsurance;
            $patientInsurance->patient_id = $patient_id;
        } else {
            $patientInsurance = PatientInsurance::find($patientInsurance->id);
        }
        $patientInsurance->insurance_carrier = ($request->input('insurance_carrier') != '') ? $request->input('insurance_carrier') : $patientInsurance->insurance_carrier;
        $patientInsurance->insurance_carrier_fpc_key = ($request->input('insurance_carrier_key') != '') ? $request->input('insurance_carrier_key') : $patientInsurance->insurance_carrier_fpc_key;
        $patientInsurance->subscriber_name = ($request->input('subscriber_name') != '') ? $request->input('subscriber_name') : $patientInsurance->subscriber_name;
        $subscriberDOB = new Datetime($request->input('subscriber_dob'));
        $patientInsurance->subscriber_birthdate = ($request->input('subscriber_dob') != '') ? $subscriberDOB->format('Y-m-d') . ' 00:00:00' : $patientInsurance->subscriber_birthdate;
        $patientInsurance->subscriber_id = ($request->input('subscriber_id') != '') ? $request->input('subscriber_id') : $patientInsurance->subscriber_id;
        $patientInsurance->insurance_group_no = ($request->input('insurance_group') != '') ? $request->input('insurance_group') : $patientInsurance->insurance_group_no;
        $patientInsurance->subscriber_relation = ($request->input('subscriber_relation') != '') ? $request->input('subscriber_relation') : $patientInsurance->subscriber_relation;
        $patientInsurance->save();
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

}
