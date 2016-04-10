<?php

namespace myocuhub\Http\Controllers;

use Auth;
use Event;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ImportHistory;
use myocuhub\Models\NetworkUser;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Models\PracticeNetwork;
use myocuhub\Models\PracticeUser;
use myocuhub\Models\referralHistory;
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
        //        $userID = Auth::user()->id;
        //        $network = User::getNetwork($userID);
        $networkID = $request->network_id;
        $new_patients = 0;
        $old_patients = 0;

        if ($request->hasFile('patient_xlsx')) {
            $i = 0;
            $excels = Excel::load($request->file('patient_xlsx'))->get();
            $importHistory = new ImportHistory;
            $importHistory->network_id = $networkID;
            $importHistory->save();
            foreach ($excels as $sheet) {
                $title = $sheet->getTitle();
                switch ($title) {
                    case 'Users':
                        foreach ($sheet as $data) {
                            $users = [];
                            if (array_filter($data->toArray())) {
                                $users['name'] = $data['name'];
                                $users['firstname'] = $data['name'];
                                $users['email'] = $data['contact_email'];
                                $users['cellphone'] = (int) $data['phone_number'];
                                $users['cellphone'] = (int) $data['cell_number'];
                                //npi cannot be null
                                $users['npi'] = '1234';
                                if ($data['npi']) {
                                    $users['npi'] = (int) $data['npi'];
                                }

                                $users['state'] = $data['state'];
                                $users['address1'] = $data['address_1'];
                                $users['address2'] = $data['address_2'];
                                $users['city'] = $data['city'];
                                $users['zip'] = (int) $data['zip_code'];
                                $users['level'] = 3;

                                $user = User::where($users)->first();
                                $checkemail = User::where('email', 'LIKE', '%' . $users['email'] . '%')->first();
                                if (!$user) {

                                    $users['password'] = \Hash::make('ocuhub');
                                    if ($checkemail) {
                                        $user = $checkemail;
                                    } else {
                                        $user = User::create($users);
                                    }

                                }
                                //map user with organization
                                if ($data['practice_name']) {
                                    $practice_id = Practice::where('name', 'LIKE', '%' . $data['practice_name'] . '%')->first()->id;
                                    if ($practice_id) {
                                        $userdata = [];
                                        $userdata['practice_id'] = $practice_id;
                                        $userdata['user_id'] = $user->id;
                                        $practice_user = PracticeUser::firstOrCreate($userdata);
                                        unset($userdata['practice_id']);
                                        $userdata['network_id'] = $networkID;
                                        $network_user = NetworkUser::firstOrCreate($userdata);
                                    }
                                }
                            }
                        }
                        break;
                    case 'Accounts':
                        foreach ($sheet as $data) {
                            $practices = [];
                            $locations = [];
                            if (array_filter($data->toArray())) {
                                $practices['name'] = $data['practice_name'];
                                //$practices['email']           = '';

                                $practice = Practice::where($practices)->first();
                                if (!$practice) {
                                    $practice = Practice::create($practices);
                                }

                                $locations['practice_id'] = $practice->id;
                                $locations['locationname'] = $data['location_name'];
                                $locations['phone'] = (int) $data['phone_number'];
                                $locations['addressline1'] = $data['address_1'];
                                $locations['addressline2'] = $data['address_2'];
                                $locations['city'] = $data['city'];
                                $locations['state'] = $data['state'];
                                $locations['zip'] = (int) $data['zip'];

                                $location = PracticeLocation::where($locations);
                                if (!$location) {
                                    $location = PracticeLocation::create($locations);
                                }

                                $practicedata = [];
                                $practicedata['network_id'] = $networkID;
                                $practicedata['practice_id'] = $practice->id;
                                $practice_network = PracticeNetwork::firstOrCreate($practicedata);
                            }

                        }
                        break;
                    case 'Patients':

                        foreach ($sheet as $data) {
                            $patients = [];
                            if (array_filter($data->toArray())) {
                                $name = explode(' ', $data['patient_name']);
                                $patients['firstname'] = $name[0];
                                $patients['lastname'] = $name[1];
                                $patients['cellphone'] = (int) $data['phone_number'];
                                $patients['email'] = $data['email'];
                                $patients['addressline1'] = $data['address_1'];
                                $patients['addressline2'] = $data['address_2'];
                                $patients['city'] = $data['city'];
                                $patients['zip'] = (int) $data['zip'];
                                $patients['lastfourssn'] = (int) $data['ssn_last_digits'];
                                $patients['birthdate'] = date('Y-m-d', strtotime($data['birthdate']));
                                $patients['gender'] = $data['gender'];

                                $referralHistory = new ReferralHistory;
                                $referralHistory->referred_by_provider = $data['referred_by'];
                                $referralHistory->referred_by_practice = $data['source'];
                                $referralHistory->disease_type = $data['disease_type'];
                                $referralHistory->severity = $data['severity'];
                                $referralHistory->save();

                                $insuranceCarrier = new PatientInsurance;
                                $insuranceCarrier->insurance_carrier = $data['insurance_type'];

                                $patient = Patient::where($patients)->first();

                                if (!$patient) {
                                    $patient = Patient::create($patients);
                                    $new_patients = $new_patients + 1;
                                    $careconsole = new Careconsole;
                                    $careconsole->import_id = $importHistory->id;
                                    $careconsole->patient_id = $patient->id;
                                    $careconsole->stage_id = 1;
                                    if (!$referralHistory) {
                                        $careconsole->referral_id = $referralHistory->id;
                                    }
                                    $insuranceCarrier->patient_id = $patient->id;
                                    $insurancecarrier->save();
                                    $date = new \DateTime();
                                    $careconsole->stage_updated_at = $date->format('Y-m-d H:m:s');
                                    $careconsole->entered_console_at = $date->format('Y-m-d H:m:s');
                                    $careconsole->save();
                                    $action = "new patient ($patient->id) created and added to console ($careconsole->id) ";
                                    $description = '';
                                    $filename = basename(__FILE__);
                                    $ip = $request->getClientIp();
                                    Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
                                } else {
                                    $old_patients = $old_patients + 1;
                                }
                                $i++;
                            }

                        }
                        break;
                }
            }

            $action = 'Bulk import Patients';
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

            $patients = [];
            $patients['total'] = $i;
            $patients['patients_added'] = $new_patients;
            $patients['already_exist'] = $old_patients;
            return json_encode($patients);
        }
        return "try again";
    }

    public function fakeExport()
    {

        $faker = Faker::create();
        $patient = [];
        for ($i = 0; $i < 10000; $i++) {
            $patient[$i]['Patient Name'] = "Raul Test";
            $patient[$i]['Email'] = $faker->email;
            $patient[$i]['Birthdate'] = $faker->date;
            $patient[$i]['SSN Last Digits'] = 9876;
            $patient[$i]['Address 1'] = $faker->streetAddress;
            $patient[$i]['Address 2'] = '';
            $patient[$i]['City'] = '';
            $patient[$i]['State'] = '';
            $patient[$i]['zip'] = '';
            $patient[$i]['Phone Number'] = '';
            $patient[$i]['Gender'] = 'M';
            $patient[$i]['Referred By'] = '';
            $patient[$i]['Disease Type'] = '';
            $patient[$i]['Severity'] = '';
            $patient[$i]['Insurance Type'] = '';
            $patient[$i]['Priority'] = '';
        }

        $create = Excel::create('stress_test_bulk_import', function ($excel) use ($patient) {
            $excel->sheet('Patients', function ($sheet) use ($patient) {
                $sheet->fromArray($patient);
            });
        })
            ->download('xlsx');

    }
}
