<?php

namespace myocuhub\Http\Controllers;

ini_set('max_execution_time', 3600);

use Auth;
use Event;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Facades\Helper;
use myocuhub\Jobs\PatientEngagement\ImportPatientMail;
use myocuhub\Models\Careconsole;
use myocuhub\Models\EngagementPreference;
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
        $template = $request->template;
        $new_patients = 0;
        $old_patients = 0;

        $import_result = [];
        $import_result['total'] = 0;
        $import_result['patients_added'] = 0;
        $import_result['already_exist'] = 0;
        $import_result['exception'] = '';
        
        $format = [
            'first_name',
            'middle_name',
            'last_name',
            'birthdate',
            'ssn_last_digits',
            'cellphone',
            'homephone',
            'workphone',
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
            'insurance_carrier',
            'subscriber_name',
            'subscriber_dob',
            'subscriber_id',
            'group_no',
            'relation_to_patient',
            'priority',
            'special_request',
            'language',
            'preferred_method_of_contact',
            'primary_care_physician',
        ];

        if ($request->hasFile('patient_xlsx')) {
            $i = 0;
            $importHistory = new ImportHistory;
            $importHistory->network_id = $networkID;
            $importHistory->save();

            // $excels = Excel::load($request->file('patient_xlsx'))->get();
            $excels = Excel::filter('chunk')->load($request->file('patient_xlsx'), function($reader){
                    $reader->setDateColumns(array('birthdate'));
                    $reader->formatDates(true, 'Y-m-d');
            })->chunk(250, function ($results) use (&$old_patients, &$i, &$format, &$new_patients, $importHistory, $request, $template, &$import_result) {
                foreach ($results as $data) {
                    $patients = [];
                    if (array_filter($data->toArray())) {
                        if($i == 0){
                            if(!(count(array_intersect_key(array_flip($format), $data->toArray())) === count($format))){
                                $import_result['exception'] = 'Incorrect .xlsx format';
                                return ;
                            }
                        }
						$patients['firstname'] = isset($data['first_name']) ? $data['first_name'] : '';
						$patients['lastname'] = isset($data['last_name']) ? $data['last_name'] : '';
                        $patients['lastfourssn'] = isset($data['ssn_last_digits']) ? $data['ssn_last_digits'] : null ;
						$patients['birthdate'] = (isset($data['birthdate'])&& $data['birthdate']) ? date('Y-m-d', strtotime($data['birthdate'])) : '0000-00-00 00:00:00';
                        $patients['preferredlanguage'] = isset($data['language']) ? $data['language'] : '';
                        $patient = Patient::where($patients)->first();

                        if ($patient) {
                            $old_patients = $old_patients + 1;
                            continue;
                        }
						$patients['middlename'] = isset($data['middle_name']) ? $data['middle_name'] : '';

                        $patients['cellphone'] = isset($data['cellphone']) ? $data['cellphone'] : '';
                        $patients['homephone'] = isset($data['homephone']) ? $data['homephone'] : '';
                        $patients['workphone'] = isset($data['workphone']) ? $data['workphone'] : '';
                        $patients['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? $data['email'] : '';
                        $patients['addressline1'] = isset($data['address_1']) ? $data['address_1'] : '';
                        $patients['addressline2'] = isset($data['address_2']) ? $data['address_2'] : '';
                        $patients['city'] = isset($data['city']) ? $data['city'] : '';
                        $patients['zip'] = isset($data['zip']) ? $data['zip'] : '';
                        $patients['gender'] = isset($data['gender']) ? Helper::getGenderIndex($data['gender']) : '';
                        $patients['special_request'] = isset($data['special_request']) ? $data['special_request'] : '';
                        $patients['pcp'] = isset($data['primary_care_physician']) ? $data['primary_care_physician'] : '';

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
                            $careconsole->priority = (isset($data['priority']) && $data['priority'] == '1') ? 1 : null;

                            if ($referralHistory != null) {
                                $careconsole->referral_id = $referralHistory->id;
                            }

                            $insuranceCarrier = PatientInsurance::create([
                                    'patient_id' => $patient->id,
                                    'insurance_carrier' => $data['insurance_carrier'],
                                    'subscriber_name' => $data['subscriber_name'],
                                    'subscriber_id' => $data['subscriber_id'],
                                    'subscriber_birthdate' => $data['subscriber_dob'],
                                    'insurance_group_no' => $data['group_no'],
                                    'subscriber_relation' => $data['relation_to_patient']
                                ]);

                            $engagementPreference = EngagementPreference::create([
                                    'patient_id' => $patient->id,
                                    'type' => config('patient_engagement.type.' . strtolower($data['preferred_method_of_contact'])),
                                    'language' => config('patient_engagement.language.' . strtolower($data['language'])),
                                ]);

                            $date = new \DateTime();
                            $careconsole->stage_updated_at = $date->format('Y-m-d H:i:s');
                            $careconsole->entered_console_at = $date->format('Y-m-d H:i:s');
                            $careconsole->save();

                            if ($template != '-1') {
                                dispatch((new ImportPatientMail($patient, $template))->onQueue('email'));
                            }

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

    public function downloadBulkImportFormat(Request $request){
        $name = 'Ocuhub Patient Import Format.xlsx';
        $path = base_path() . '/public/formats/patient-xlxs-import.xlsx';
        $headers = [ 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ];
        return response()->download($path, $name, $headers);
    }
}
