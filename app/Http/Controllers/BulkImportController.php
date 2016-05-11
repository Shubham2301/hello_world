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

        if ($request->hasFile('patient_xlsx')) {
            $i = 0;
            $importHistory = new ImportHistory;
            $importHistory->network_id = $networkID;
            $importHistory->save();

            // $excels = Excel::load($request->file('patient_xlsx'))->get();
            $excels = Excel::filter('chunk')->load($request->file('patient_xlsx'))->chunk(250, function ($results) use (&$old_patients, &$i, &$new_patients, $importHistory, $request) {
                foreach ($results as $data) {
                    $patients = [];
                    if (array_filter($data->toArray())) {
                        $name = explode(' ', $data['patient_name']);
                        $patients['firstname'] = $name[0];
                        $patients['lastname'] = $name[1];
                        $patients['lastfourssn'] = $data['ssn_last_digits'];
                        $patients['birthdate'] = date('Y-m-d', strtotime($data['birthdate']));

                        // Based upon above four fields verify duplicate
                        $patient = Patient::where($patients)->first();

                        if ($patient) {
                            $old_patients = $old_patients + 1;
                            continue;
                        }

                        $patients['cellphone'] = (float)$data['phone_number'];
                        $patients['email'] = $data['email'];
                        $patients['addressline1'] = $data['address_1'];
                        $patients['addressline2'] = $data['address_2'];
                        $patients['city'] = $data['city'];
                        $patients['zip'] = $data['zip'];
                        $patients['gender'] = $data['gender'];

                        $referralHistory = null;

                        if ($data['referred_by'] != '' || $data['source'] != '' || $data['disease_type'] != '' || $data['severity'] != '') {
                            $referralHistory = new ReferralHistory;
                            $referralHistory->referred_by_provider = $data['referred_by'];
                            $referralHistory->referred_by_practice = $data['source'];
                            $referralHistory->disease_type = $data['disease_type'];
                            $referralHistory->severity = $data['severity'];
                            $referralHistory->save();
                        }

                        if (!$patient) {
                            $patient = Patient::create($patients);
                            $new_patients = $new_patients + 1;
                            $careconsole = new Careconsole;
                            $careconsole->import_id = $importHistory->id;
                            $careconsole->patient_id = $patient->id;
                            $careconsole->stage_id = 1;

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
            $patient[$i]['Zip'] = '';
            $patient[$i]['Phone Number'] = '';
            $patient[$i]['Gender'] = 'M';
            $patient[$i]['Referred By'] = '';
            $patient[$i]['Source'] = '';
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
