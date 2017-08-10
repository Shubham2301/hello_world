<?php

namespace myocuhub\Http\Controllers;

ini_set('max_execution_time', 3600);

use Auth;
use Event;
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
use myocuhub\Models\PracticeUser;
use myocuhub\Models\PracticePatient;
use myocuhub\Models\ReferralHistory;
use myocuhub\Models\Timezone;
use myocuhub\Network;
use myocuhub\Patient;
use myocuhub\User;

class BulkImportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (session('user-level') == 1) {
            $multiple_network = true;
            $data['multiple_network'] = $multiple_network;
            if ($multiple_network) {
                $userNetworks = Network::all();
                $networks = array();
                foreach ($userNetworks as $userNetwork) {
                    $networks[$userNetwork->id] = $userNetwork->name;
                }
                $data['networks'] = $networks;
            }
        } elseif (session('user-level') == 2) {
            $data['multiple_network'] = false;
            $data['id'] = $user->userNetwork->first()->network_id;
            $data['name'] = $user->userNetwork->first()->network->name;
        } elseif (session('user-level') > 2 && sizeof($user->userNetwork) > 1) {
            $user = Auth::user();
            $userNetworks = $user->userNetwork;
            $networks = array();
            $data['multiple_network'] = true;
            foreach ($userNetworks as $userNetwork) {
                $networks[$userNetwork->network_id] = $userNetwork->network->name;
            }
            $data['networks'] = $networks;
        } else {
            $data['multiple_network'] = false;
            $data['id'] = $user->userNetwork->first()->network_id;
            $data['name'] = $user->userNetwork->first()->network->name;
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
        $template = $request->template ?: '-1';
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
            'timezone',
        ];

        if ($request->hasFile('patient_xlsx')) {
            $i = 0;
            $importHistory = new ImportHistory;
            $importHistory->network_id = $networkID;
            $importHistory->type = config('constants.import_type.bulk_import');
            $importHistory->save();

            $excels = Excel::filter('chunk')->load($request->file('patient_xlsx'), function ($reader) {
                $reader->setDateColumns(array('birthdate', 'subscriber_dob'));
                $reader->formatDates(true, 'Y-m-d');
            })->chunk(250, function ($results) use (&$old_patients, &$i, &$format, &$new_patients, $importHistory, $request, $template, &$import_result) {
                foreach ($results as $data) {
                    $patients = [];
                    $today = date("Y-m-d");
                    $data['subscriber_dob'] = $data['subscriber_dob'] == $today ? null : $data['subscriber_dob'];
                    $data['birthdate'] = $data['birthdate'] == $today ? null : $data['birthdate'];
                    if (array_filter($data->toArray())) {
                        if ($i == 0) {
                            if (!(count(array_intersect_key(array_flip($format), $data->toArray())) === count($format))) {
                                $import_result['exception'] = "Incorrect .xlsx format";
                                return;
                            }
                        }
                        $patients['firstname'] = isset($data['first_name']) ? $data['first_name'] : '';
                        $patients['lastname'] = isset($data['last_name']) ? $data['last_name'] : '';
                        $patients['lastfourssn'] = isset($data['ssn_last_digits']) ? $data['ssn_last_digits'] : null;
                        $patients['birthdate'] = (isset($data['birthdate']) && $data['birthdate']) ? Helper::formatDate($data['birthdate'], config('constants.db_date_format')) : null;
                        $language = config('patient_engagement.language.' . strtolower($data['language']));
                        $patients['preferredlanguage'] = isset($language) ? $language : '';
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
                        $patients['state'] = isset($data['state']) ? $data['state'] : '';
                        $patients['zip'] = isset($data['zip']) ? $data['zip'] : '';
                        $patients['gender'] = isset($data['gender']) ? Helper::getGenderIndex($data['gender']) : '';
                        $patients['special_request'] = isset($data['special_request']) ? $data['special_request'] : '';
                        $patients['pcp'] = isset($data['primary_care_physician']) ? $data['primary_care_physician'] : '';
                        $timezone = isset($data['timezone']) ? Timezone::where('abbr', strtoupper(trim($data['timezone'])))->first() : null;
                        $patients['timezone_id'] = $timezone ? $timezone['id'] : null;

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
                                'subscriber_birthdate' => (isset($data['subscriber_dob']) && $data['subscriber_dob']) ? Helper::formatDate($data['subscriber_dob'], config('constants.db_date_format')) : null,
                                'insurance_group_no' => $data['group_no'],
                                'subscriber_relation' => $data['relation_to_patient'],
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

                            if (session('user-level') == 3) {
                                $userID = Auth::user()->id;
                                $practiceUser = PracticeUser::where('user_id', $userID)->first();
                                if ($practiceUser) {
                                    $practicePatient = new PracticePatient;
                                    $practicePatient->patient_id = $patient->id;
                                    $practicePatient->practice_id = $practiceUser['practice_id'];
                                    $practicePatient->save();
                                }
                            }

                            if ($template != '-1') {
                                if ($patient->email != '') {
                                    dispatch((new ImportPatientMail($patient, $template))->onQueue('email'));
                                }
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

    public function downloadBulkImportFormat(Request $request)
    {
        $name = 'Ocuhub Patient Import Format.xlsx';
        $path = base_path() . '/public/formats/patient-xlxs-import.xlsx';
        $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        return response()->download($path, $name, $headers);
    }
}
