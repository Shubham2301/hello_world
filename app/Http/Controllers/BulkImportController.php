<?php

namespace myocuhub\Http\Controllers;

ini_set('max_execution_time', 3600);

use Auth;
use Event;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ImportHistory;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\PracticeLocation;
use myocuhub\Models\ReferralHistory;
use myocuhub\Network;
use myocuhub\Patient;
use myocuhub\User;

class BulkImportController extends Controller
{

    public function index()
    {

        if (session('user-level') == 1) {
            $super_admin = true;

            $data['super_admin'] = $super_admin;
            if ($super_admin) {
                $data['networks'] = Network::all();
            }

        } else {

            $userID = Auth::user()->id;
            $network = User::getNetwork($userID);
            $data['id'] = $network->network_id;
            $data['name'] = $network->name;
            $data['super_admin'] = false;

        }

        return view('layouts.import')->with('network', $data);
    }

    public function getLocations(Request $request)
    {

        $practice_id = $request->input('practice_id');
        $locations = PracticeLocation::where('practice_id', $practice_id)->get();
        $data = [];
        $i = 0;
        foreach ($locations as $location) {
            $data[$i]['id'] = $location->id;
            $data[$i]['name'] = $location->locationname;
            $i++;
        }

        return json_encode($data);
    }

    public function importPatientsXlsx(Request $request)
    {
        $networkID = $request->network_id;
        $new_patients = 0;
        $old_patients = 0;

        $import_result = [];
        $import_result['total'] = 0;
        $import_result['patients_added'] = 0;
        $import_result['already_exist'] = 0;
        $import_result['exception'] = '';

        $format = [
            'patient_name',
            'birthdate',
            'ssn_last_digits',
            'phone_number',
            'email',
            'address_1',
            'address_2',
            'city',
            'zip',
            'gender',
            'referred_by',
            'source',
            'disease_type',
            'severity',
            'insurance_type',
            'language',
        ];

        if ($request->hasFile('patient_xlsx')) {
            $i = 0;
            $importHistory = new ImportHistory;
            $importHistory->network_id = $networkID;
            $importHistory->save();

            // $excels = Excel::load($request->file('patient_xlsx'))->get();
            $excels = Excel::filter('chunk')->load($request->file('patient_xlsx'))->chunk(250, function ($results) use (&$old_patients, &$i, &$format, &$new_patients, $importHistory, $request) {
                foreach ($results as $data) {
                    $patients = [];

                    if (array_filter($data->toArray())) {

                        if($i == 0){
                            if(!(count(array_intersect_key(array_flip($format), $data->toArray())) === count($format))){
                                $import_result['exception'] = 'Incorrect .xlsx format';
                                return json_encode($import_result);
                            }
                        }

                        $name = explode(' ', $data['patient_name']);
                        $patients['firstname'] = isset($name[0]) ? $name[0] : '';
                        $patients['lastname'] = isset($name[1]) ? $name[1] : '';
                        $patients['lastfourssn'] = isset($data['ssn_last_digits']) ? $data['ssn_last_digits'] : null ;
                        $patients['birthdate'] = isset($data['birthdate']) ? date('Y-m-d', strtotime($data['birthdate'])) : '0000-00-00 00:00:00';
                        $patients['language'] = isset($data['language']) ? $data['language'] : '';
                        $patient = Patient::where($patients)->first();

                        if ($patient) {
                            $old_patients = $old_patients + 1;
                            continue;
                        }

                        $patients['cellphone'] = isset($data['phone_number']) ? $data['phone_number'] : '';
                        $patients['email'] = isset($data['email']) ? $data['email'] : '';
                        $patients['addressline1'] = isset($data['address_1']) ? $data['address_1'] : '';
                        $patients['addressline2'] = isset($data['address_2']) ? $data['address_2'] : '';
                        $patients['city'] = isset($data['city']) ? $data['city'] : '';
                        $patients['zip'] = isset($data['zip']) ? $data['zip'] : '';
                        $patients['gender'] = isset($data['gender']) ? $data['gender'] : '';

                        $referralHistory = null;

                        if ($data['referred_by'] != '' || $data['source'] != '' || $data['disease_type'] != '' || $data['severity'] != '') {
                            $referralHistory = new ReferralHistory;
                            $referralHistory->referred_by_provider = isset($data['referred_by']) ? $data['referred_by'] : '';
                            $referralHistory->referred_by_practice = isset($data['source']) ? $data['source'] : '';
                            $referralHistory->disease_type = isset($data['disease_type']) ? $data['disease_type'] : '';
                            $referralHistory->severity = isset($data['severity']) ? $data['severity'] : '';
                            $referralHistory->save();
                        }

                        if (!$patient) {
                            $patient = Patient::create($patients);
                            $new_patients = $new_patients + 1;
                            $careconsole = new Careconsole;
                            $careconsole->import_id = $importHistory->id;
                            $careconsole->patient_id = $patient->id;
                            $careconsole->stage_id = 1;
                            $careconsole->priority = (isset($data['priority']) && array_key_exists('priority', $data))? $data['priority'] : null;

                            if ($referralHistory != null) {
                                $careconsole->referral_id = $referralHistory->id;
                            }

                            $insuranceCarrier = new PatientInsurance;
                            $insuranceCarrier->insurance_carrier = $data['insurance_type'];
                            $insuranceCarrier->patient_id = $patient->id;
                            $insuranceCarrier->save();

                            $date = new \DateTime();
                            $careconsole->stage_updated_at = $date->format('Y-m-d H:i:s');
                            $careconsole->entered_console_at = $date->format('Y-m-d H:i:s');
                            $careconsole->save();
                            $action = "new patient ($patient->id) created and added to console ($careconsole->id) ";
                            $description = '';
                            $filename = basename(__FILE__);
                            $ip = $request->getClientIp();
                            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
                        }
                        $i++;
                    }
                }

            });

            $action = 'Bulk import Patients';
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

            $import_result['total'] = $i;
            $import_result['patients_added'] = $new_patients;
            $import_result['already_exist'] = $old_patients;

            return json_encode($import_result);
        }
        return "try again";
    }

}
