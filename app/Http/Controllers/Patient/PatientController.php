<?php

namespace myocuhub\Http\Controllers\Patient;

use Auth;
use DateTime;
use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Careconsole;
use myocuhub\Models\Ccda;
use myocuhub\Models\ImportHistory;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticePatient;
use myocuhub\Models\PracticeUser;
use myocuhub\Models\ReferralHistory;
use myocuhub\Patient;
use myocuhub\User;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = array();
        $data['admin'] = false;
        $data['schedule-patient'] = true;
        if ($request->has('referraltype_id')) {
            $data['referraltype_id'] = $request->input('referraltype_id');
        }
        if ($request->has('action')) {
            $data['action'] = $request->input('action');
        }
        $practicedata = Practice::all()->lists('name', 'id')->toArray();
        return view('patient.index')->with('data', $data)->with(['practice_data'=> $practicedata]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = array();
        $gender = array();
        $gender['Male'] = 'Male';
        $gender['Female'] = 'Female';
        $language = array();
        $language['English'] = 'English';
        $language['Spanish'] = 'Spanish';
        $data = Patient::getColumnNames();
        $data['admin'] = true;
        $data['back_btn'] = 'back_to_select_patient_btn';
        $data['url'] = '/administration/patients/add';
        $data['referred_by_provider'] = null;
        $data['referred_by_practice'] = null;
        $data['disease_type'] = null;
        $data['severity'] = null;
        $data['insurance_type'] = null;
        if ($request->has('referraltype_id')) {
            $data['referraltype_id'] = $request->input('referraltype_id');
            $data['admin'] = false;
        }
        if ($request->has('action')) {
            $data['action'] = $request->input('action');
        }
        return view('patient.admin')->with('data', $data)->with('gender', $gender)->with('language', $language);
    }

    public function createByAdmin()
    {
        $gender = array();
        $gender['Male'] = 'Male';
        $gender['Female'] = 'Female';
        $language = array();
        $language['English'] = 'English';
        $language['Spanish'] = 'Spanish';
        $data = array();
        $data = Patient::getColumnNames();
        $data['admin'] = true;
        $data['back_btn'] = 'back_to_admin_patient_btn';
        $data['url'] = '/administration/patients/add';
        $data['referraltype_id'] = -1;
        $data['referred_by_provider'] = null;
        $data['referred_by_practice'] = null;
        $data['disease_type'] = null;
        $data['severity'] = null;
        $data['insurance_type'] = null;
        return view('patient.admin')->with('data', $data)->with('gender', $gender)->with('language', $language);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userID = Auth::user()->id;
        $network = User::getNetwork($userID);
        $networkID = $network->network_id;
        $data = $request->all();
        unset($data['_token']);
        $referraltypeID = 0;
        $action = '';
        if ($request->has('referraltype_id')) {
            $referraltypeID = $request->input('referraltype_id');
            $action = $request->input('action');
            unset($data['referraltype_id']);
            unset($data['action']);
        }
        unset($data['referred_by_provider']);
        unset($data['referred_by_practice']);
        unset($data['disease_type']);
        unset($data['severity']);
        unset($data['insurance_type']);

        $patient = Patient::where($data)->first();
        if (!$patient) {
            $patient = new Patient;
            $patient->firstname = $request->input('firstname');
            $patient->lastname = $request->input('lastname');
            $patient->middlename = $request->input('middlename');
            $patient->email = $request->input('email');
            $patient->gender = $request->input('gender');
            $patient->lastfourssn = $request->input('lastfourssn');
            $patient->addressline1 = $request->input('addressline1');
            $patient->addressline2 = $request->input('addressline2');
            $patient->city = $request->input('city');
            $patient->zip = $request->input('zip');
            $patientDob = new Datetime($request->input('birthdate'));
            $patient->birthdate = ($request->input('birthdate') == '')? null : $patientDob->format('Y-m-d H:i:s');
            $patient->preferredlanguage = $request->input('preferredlanguage');
            $patient->cellphone = $request->input('cellphone');
            $patient->homephone = $request->input('homephone');
            $patient->workphone = $request->input('workphone');
            $patient->state = $request->input('state');
            $patient->save();

            $importHistory = new ImportHistory;
            $importHistory->network_id = $networkID;
            $importHistory->save();

            $referralHistory = new ReferralHistory;
            $referralHistory->referred_by_provider = $request->input('referred_by_provider');
            $referralHistory->referred_by_practice = $request->input('referred_by_practice');
            $referralHistory->disease_type = $request->input('disease_type');
            $referralHistory->severity = $request->input('severity');
            $referralHistory->network_id = $networkID;
            $referralHistory->save();

            $careconsole = new Careconsole;
            $careconsole->import_id = $importHistory->id;
            $careconsole->patient_id = $patient->id;
            $careconsole->stage_id = 1;
            $date = new DateTime();
            $careconsole->stage_updated_at = $date->format('Y-m-d H:i:s');
            $careconsole->entered_console_at = $date->format('Y-m-d H:i:s');
            if ($referralHistory != null) {
                $careconsole->referral_id = $referralHistory->id;
            }
            $careconsole->save();

            $insuranceCarrier = new PatientInsurance;
            $insuranceCarrier->insurance_carrier = $request->input('insurance_type');
            $insuranceCarrier->patient_id = $patient->id;
            $insuranceCarrier->save();

            if (session('user-level') == 3) {
                $practiceUser= PracticeUser::where('user_id', $userID)->first();
                if ($practiceUser) {
                    $practicePatient = new PracticePatient;
                    $practicePatient->patient_id = $patient->id;
                    $practicePatient->practice_id = $practiceUser['practice_id'];
                    $practicePatient->save();
                }
            }


            $action = "new patient ($patient->id) created and added to console ($careconsole->id) ";
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        } else {
            $request->session()->put('success', 'patient already exists');
        }
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
    public function show(Request $request)
    {
        $id = $request->input('id');
        
        $patientData = [];

        $response = [
            'result' => false,
            'patient_data' => $patientData
        ];
        try {
            $patient = Patient::findOrFail($id);
        } catch (Exception $e) {
            return json_encode($response);
        }
        $careconsole = Careconsole::where('patient_id', '=', $id)->first();
        $insurance = PatientInsurance::where('patient_id', '=', $id)->first(['insurance_carrier']);
        $previousProvider = Patient::getPreviousProvider($id);

        $patientData['referred_to_practice_user'] = '';
        $patientData['referred_to_practice'] = '';

        if ($previousProvider['id'] !== null) {
            $patientData['referred_to_practice_user'] = $previousProvider['title'] . ' ' . $previousProvider['lastname'] . ', ' . $previousProvider['firstname'];
            $patientData['referred_to_practice'] = $previousProvider['name'];
        }

        $referral_history = (isset($careconsole->referral_id)) ? ReferralHistory::find($careconsole->referral_id) : null;
        $patientData['referred_by_practice'] = '';
        $patientData['referred_by_provider'] = '';

        if ($referral_history) {
            try {
                if ($referral_history->referred_by_practice) {
                    $patientData['referred_by_practice'] = $referral_history->referred_by_practice;
                }
                if ($referral_history->referred_by_provider) {
                    $patientData['referred_by_provider'] = $referral_history->referred_by_provider;
                }
            } catch (Exception $e) {
            }
        }

        if (isset($insurance)) {
            $patientData['insurance'] = $insurance->insurance_carrier  ?: '';
        }

        $patientData['firstname'] = $patient->firstname ?: '';
        $patientData['lastname'] = $patient->lastname  ?: '';
        $patientData['email'] = $patient->email  ?: '-';
        $patientData['lastfourssn'] = $patient->lastfourssn  ?: '-';
        $patientData['addressline1'] = $patient->addressline1  ?: '';
        $patientData['addressline2'] = $patient->addressline2  ?: '';
        $patientData['city'] = $patient->city ?: '';
        $patientData['id'] = $patient->id ?: '';
        $patientData['cellphone'] = $patient->cellphone ?: '';
        $patientData['workphone'] = $patient->workphone ?: '';
        $patientData['homephone'] = $patient->homephone ?: '';
        $birthdate = new DateTime($patient->birthdate);
        $patientData['birthdate'] = ($patient->birthdate && (bool)strtotime($patient->birthdate))? $birthdate->format('F j Y') : '-';

        $ccda = Ccda::where('patient_id', $id)->orderBy('created_at', 'desc')->first();
        $patientData['ccda'] = true;
        if (!($ccda)) {
            $patientData['ccda_date'] = (new DateTime())->format('F j Y');
        } else {
            $patientData['ccda_date'] = (new DateTime($ccda->created_at))->format('F j Y');
        }

        $validated4PCData = $this->validate4PCData($id);
        $patientData['count_validated4pc_data'] = sizeof($validated4PCData);
        $patientData['validated4pc_data'] = view('patient.field_model_4pc')->with('fields_4PC', $validated4PCData)->render();
        $response = [
            'result' => true,
            'patient_data' => $patientData
        ];
        return json_encode($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gender = array();
        $gender['Male'] = 'Male';
        $gender['Female'] = 'Female';
        $language = array();
        $language['English'] = 'English';
        $language['Spanish'] = 'Spanish';
        $data = array();
        $data = Patient::find($id);
        if (!$data) {
            $data['url'] = '/administration/patients/add';
            $data = Patient::getColumnNames();
        }
        $dob = new DateTime($data['birthdate']);
        $data['birthdate'] = ($data['birthdate'] && $data['birthdate'] != '0000-00-00 00:00:00') ? $dob->format('m/d/Y') : '';
        $data['admin'] = true;
        $data['back_btn'] = 'back_to_admin_patient_btn';
        $data['url'] = '/administration/patients/update/' . $id;
        $data['referraltype_id'] = -1;
        $data['referred_by_provider'] = null;
        $data['referred_by_practice'] = null;
        $data['disease_type'] = null;
        $data['severity'] = null;
        $data['insurance_type'] = null;
        $careconsole = Careconsole::where('patient_id', '=', $id)->first();
        if ($careconsole) {
            $referralHistory = ReferralHistory::find($careconsole->referral_id);
            if ($referralHistory) {
                $data['referred_by_provider'] = $referralHistory->referred_by_provider;
                $data['referred_by_practice'] = $referralHistory->referred_by_practice;
                $data['disease_type'] = $referralHistory->disease_type;
                $data['severity'] = $referralHistory->severity;
            }
        }

        $insuranceCarrier =  PatientInsurance::where('patient_id', $id)->orderBy('updated_at', 'desc')->first();
        if ($insuranceCarrier) {
            $data['insurance_type'] = $insuranceCarrier->insurance_carrier;
        }

        return view('patient.admin')->with('data', $data)->with('gender', $gender)->with('language', $language);
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
        $patient = Patient::find($id);
        if ($patient) {
            $patient->firstname = $request->firstname;
            $patient->lastname = $request->lastname;
            $patient->middlename = $request->middlename;
            $patient->cellphone = $request->cellphone;
            $patient->homephone = $request->homephone;
            $patient->workphone = $request->workphone;
            $patient->email = $request->email;
            $patient->preferredlanguage = $request->preferredlanguage;
            $patient->gender = $request->gender;
            $patient->lastfourssn = $request->lastfourssn;
            $patient->addressline1 = $request->addressline1;
            $patient->addressline2 = $request->addressline2;
            $patient->city = $request->city;
            $patient->state = $request->state;
            $patient->zip = $request->zip;

            $dob = new DateTime($request->birthdate);
            $patient->birthdate = $dob->format('Y-m-d 00:00:00');

            $patient->save();

            $careconsole = Careconsole::where('patient_id', '=', $id)->first();
            if ($careconsole) {
                $referralHistory = ReferralHistory::find($careconsole->referral_id);
                if ($referralHistory == null) {
                    $referralHistory = new ReferralHistory;
                    $referralHistory->save();
                    $careconsole->referral_id = $referralHistory->id;
                    $careconsole->save();
                }

                $referralHistory->referred_by_provider = $request->referred_by_provider;
                $referralHistory->referred_by_practice = $request->referred_by_practice;
                $referralHistory->disease_type = $request->input('disease_type');
                $referralHistory->severity = $request->input('severity');
                $referralHistory->network_id = session('network-id');
                $referralHistory->save();
            }

            $insuranceCarrier = PatientInsurance::where('patient_id', '=', $id)->orderBy('updated_at', 'desc')->first();
            if ($insuranceCarrier) {
                $insuranceCarrier->insurance_carrier = $request->input('insurance_type');
                $insuranceCarrier->save();
            }

            $action = 'update patient of id =' . $id;
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        }
        if (!$request->has('action')) {
            return redirect('/administration/patients');
        }

        $path = 'patients?referraltype_id=' . $request->input('referraltype_id') . '&action=' . $request->input('action').'&patient_id='.$id;
        return redirect($path);
    }
    public function destroy(Request $request)
    {
        if (!$request->input() || $request->input() === '' || sizeof($request->input()) < 1) {
            return;
        }
        $patient = Patient::whereIn('id', $request->input())->delete();
        $action = 'delete'.sizeof($request->input()) . 'patients';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        return ;
    }

    public function search(Request $request)
    {
        $filters = json_decode($request->input('data'), true);

        $patients = Patient::getPatients($filters);

        $data = [];
        $i = 0;
        foreach ($patients as $patient) {
            $data[$i]['id'] = $patient->id;
            $data[$i]['fname'] = $patient->firstname;
            $data[$i]['lname'] = $patient->lastname;
            $data[$i]['email'] = $patient->email;
            $data[$i]['phone'] = $patient->cellphone;
            $data[$i]['lastfourssn'] = $patient->lastfourssn;
            $data[$i]['addressline1'] = ($patient->addressline1 != null && $patient->addressline1 != '-') ? $patient->addressline1 : '';
            $data[$i]['addressline2'] = ($patient->addressline2 != null && $patient->addressline2 != '-') ? $patient->addressline2 : '';
            $data[$i]['city'] = $patient->city;
            $birthdate = new DateTime($patient->birthdate);
            $data[$i]['birthdate'] = ($patient->birthdate && (bool)strtotime($patient->birthdate))? $birthdate->format('F j Y') : '-';
            $i++;
        }
        $data[0]['total'] = $patients->total();
        $data[0]['lastpage'] = $patients->lastPage();

        return json_encode($data);
    }

    public function administration(Request $request)
    {
        $data = array();
        $practicedata = array();
        $data['admin'] = true;
        $data['patient_active'] = true;
        $practicedata = Practice::all()->lists('name', 'id')->toArray();
        return view('patient.admin')->with('data', $data)->with('practice_data', $practicedata);
    }

    public function editFromReferral(Request $request)
    {
        $id = $request->input('patient_id');
        $gender = array();
        $gender['Male'] = 'Male';
        $gender['Female'] = 'Female';
        $language = array();
        $language['English'] = 'English';
        $language['Spanish'] = 'Spanish';
        $data = array();
        $data = Patient::find($id);
        if (!$data) {
            $data['url'] = '/administration/patients/add';
            $data = Patient::getColumnNames();
        }
        $dob = new DateTime($data['birthdate']);
        $data['birthdate'] = $dob->format('m/d/Y');
        $data['admin'] = false;
        $data['back_btn'] = 'back_to_select_patient_btn';
        $data['url'] = '/administration/patients/update/' . $id;
        $data['referraltype_id'] = $request->input('referraltype_id');
        $data['action'] = $request->input('action');
        $data['patient_id'] = $id;
        $data['referred_by_provider'] = null;
        $data['referred_by_practice'] = null;
        $data['disease_type'] = null;
        $data['severity'] = null;
        $data['insurance_type'] = null;

        $careconsole = Careconsole::where('patient_id', '=', $id)->first();
        if ($careconsole) {
            $referralHistory = ReferralHistory::find($careconsole->referral_id);
            if ($referralHistory) {
                $data['referred_by_provider'] = $referralHistory->referred_by_provider;
                $data['referred_by_practice'] = $referralHistory->referred_by_practice;
                $data['disease_type'] = $referralHistory->disease_type;
                $data['severity'] = $referralHistory->severity;
            }
        }

        $insuranceCarrier =  PatientInsurance::where('patient_id', $id)->orderBy('updated_at', 'desc')->first();
        if ($insuranceCarrier) {
            $data['insurance_type'] = $insuranceCarrier->insurance_carrier;
        }

        return view('patient.admin')->with('data', $data)->with('gender', $gender)->with('language', $language);
    }

    public function saveReferredbyDetails(Request $request)
    {
        $referredByPractice = $request->referred_by_practice;
        $referredByProvider = $request->referred_by_provider;
        $patientID = $request->patient_id;

        $referralHistory = new ReferralHistory;
        $referralHistory->referred_by_provider = $referredByProvider;
        $referralHistory->referred_by_practice = $referredByPractice;
        $referralHistory->network_id = session('network-id');
        $referralHistory->save();

        $careconsole = Careconsole::where('patient_id', '=', $patientID)->first();

        if ($careconsole && $referralHistory != null) {
            $careconsole->referral_id = $referralHistory->id;
            $careconsole->save();
        }
        return $patientID;
    }

    public function validate4PCData($patientId)
    {
        $patient = Patient::find($patientId);
        $tempFields = config('constants.4pcMandatory_fields');
        $fields = $tempFields;

        foreach ($tempFields as $key => $field) {
            if ($field['type'] == 'field_date') {
                ($patient[$field['field_name']] && (bool)strtotime($patient[$field['field_name']])) ? array_forget($fields, $key):'';
            } elseif ($patient[$field['field_name']]) {
                array_forget($fields, $key);
            }
        }
        return $fields;
    }

    public function update4PCRequiredData(Request $request)
    {
        $data = $request->all();
        $patientID =  $request->patientId;
        unset($data['patientId']);
        $updatePatient = Patient::where('id', $patientID)->update($data);
        $request->request->add(['id'=> $patientID]);
        return $this->show($request);
    }
}
