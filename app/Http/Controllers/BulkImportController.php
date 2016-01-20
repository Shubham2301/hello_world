<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticePatient;
use myocuhub\Models\PracticeLocation;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Patient;
use myocuhub\User;

class BulkImportController extends Controller
{

    public function index()
    {
        $practicedata = Practice::all()->lists('name', 'id');
        return view('patient.import')->with('data', $practicedata);
    }

    public function getLocations(Request $request)
    {

        $practice_id = $request->input('practice_id');
        $locations = PracticeLocation::where('practice_id', $practice_id)->get();
        $data = [];
        $i=0;
        foreach ($locations as $location) {
            $data[$i]['id']= $location->id;
            $data[$i]['name']= $location->locationname;
            $i++;
        }

        return json_encode($data);

        //dd($practice_id);

    }

    public function importPatientsCsv(Request $request)
    {
        if ($request->hasFile('patient_csv')) {
            $i=0;
            $excels = Excel::load($request->file('patient_csv'))->get();
            //dd($excels);
            foreach ($excels as $sheet) {
                $title = $sheet->getTitle();
                switch($title)
                {
                    case 'Users':
                        foreach ($sheet as $data) {
                            $users = [];
                            if (array_filter($data->toArray())) {
                                $users['name']      = $data['name'];
                                $users['email']     = $data['contact_email'];
                                $users['cellphone'] = $data['phone_number'];
                                $users['cellphone'] = $data['cell_number'];
                                //npi cannot be null
                                $users['npi']       = '1234';//$data['npi'];
                                $users['state']     = $data['state'];
                                $users['address1']  = $data['address_1'];
                                $users['address2']  = $data['address_2'];
                                $users['city']      = $data['city'];
                                $users['zip']       = $data['zip_code'];
                                $user = User::firstOrCreate($users);
                                //map user with organization
                            }
                        }
                        break;
                    case 'Accounts':
                        foreach ($sheet as $data) {
                            $practices = [];
                            $locations = [];
                            if (array_filter($data->toArray())) {
                                $practices['name']              = $data['practice_name'];
                                //$practices['email']           = '';
                                $practice = Practice::firstOrCreate($practices);
                                $locations['practice_id']       = $practice->id;
                                $locations['locationname']      = $data['location_name'];
                                $locations['phone']             = $data['phone_number'];
                                $locations['addressline1']      = $data['address_1'];
                                $locations['addressline2']      = $data['address_2'];
                                $locations['city']              = $data['city'];
                                $locations['state']             = $data['state'];
                                $locations['zip']               = $data['zip'];
                                $location = PracticeLocation::firstOrCreate($locations);

                            }

                        }
                        break;
                    case 'Patients':

                        foreach ($sheet as $data) {
                            $patients = [];
                            if (array_filter($data->toArray())) {
                                $patients['title']              = 'Mr';
                                $patients['firstname']          = $data['patient_name'];
                                $patients['workphone']          = $data['phone_number'];
                                $patients['email']              = $data['email'];
                                $patients['addressline1']       = $data['address_1'];
                                $patients['addressline2']       = $data['address_2'];
                                $patients['city']               = $data['city'];
                                $patients['zip']                = $data['zip'];
                                $patients['lastfourssn']        = $data['ssn_last_digits'];
                                $patients['birthdate']          = $data['birthdate'];
                                $patients['gender']             = $data['gender'];
                                $patients['insurancecarrier']   = $data['insurance_type'];
                                $patient = Patient::firstOrCreate($patients);
                                $PracticePatient = [];
                                $PracticePatient['patient_id'] = $patient->id ;
                                $PracticePatient['practice_id'] = $request->practice_id;
                                $PracticePatient['location_id'] = $request->location_id;
                                $pp = PracticePatient::firstOrCreate($PracticePatient);
                                $i++;

                            }
                        }
                        //$request->session()->put('success','you have imported '.$i.' patients');
                        break;
                }

            }
            return "You have imported ".$i." patients";
        }
        return "try again";

    }
}
