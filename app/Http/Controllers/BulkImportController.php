<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;



class BulkImportController extends Controller
{

    public function index(){

       // $Practicedata =  \::where('collegeid','=',Session::get('user')->collegeid)->lists('classname', 'id');
        $practicedata = Practice::all()->lists('name','id');
        return view('patient.import')->with('data',$practicedata);
    }

    public function getLocations(Request $request){

        $practice_id = $request->input('practice_id');
        $locations = PracticeLocation::where('practice_id', $practice_id)->get();
        $data = [];
        $i=0;
        foreach ($locations as $location)
        {
            $data[$i]['id']= $location->id;
            $data[$i]['name']= $location->locationname;
            $i++;
        }

        return json_encode($data);

        //dd($practice_id);

    }

    public function importPatientsCsv(Request $request){
        if ($request->hasFile('photo')) {

        }
        dd($request->input());


    }



}
