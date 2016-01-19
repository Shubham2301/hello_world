<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use Maatwebsite\Excel\Facades\Excel;

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
            $excels = Excel::load($request->file('patient_csv'))->get();
            $patientdata = [];
            foreach ($excels as $sheet) {
                $title = $sheet->getTitle();
                switch($title)
                {
                    case 'Users':
                        foreach ($sheet as $data) {
                            echo $data->type.'</br>';
                        }
                        echo '</br>';
                        break;
                    case 'Accounts':
                        foreach ($sheet as $data) {
                            if (array_filter($data->toArray())) {
                                echo $data->practice_name.'</br>';
                            }

                        }
                        echo '</br>';
                        break;
                    case 'Patients':
                        foreach ($sheet as $data) {
                            if (array_filter($data->toArray())) {
                                echo $data->patient_name.'</br>';
                            }

                        }
                        echo '</br>';
                        break;
                }

            }
        }
    }
}
