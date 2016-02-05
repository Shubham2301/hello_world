<?php

namespace myocuhub\Http\Controllers\Patient;

use Illuminate\Http\Request;
use myocuhub\Patient;
use myocuhub\Models\Practice;
use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

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

        if ($request->has('referraltype_id')) {
            $data['referraltype_id'] = $request->input('referraltype_id');
        }
        if ($request->has('action')) {
            $data['action'] = $request->input('action');
        }
        $practicedata = Practice::all()->lists('name', 'id')->toArray();
        array_unshift($practicedata, "0");
        $practicedata['0']="Select Practice";
        return view('patient.index')->with('data', $data)->with('practice_data', $practicedata);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $data = array();

        if($request->has('referraltype_id')){
            $data['referraltype_id'] = $request->input('referraltype_id');
        }
        if($request->has('action')){
            $data['action'] = $request->input('action');
        }
        return view('patient.admin')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patient = new Patient;
        $patient->firstname = $request->input('patient_fname');
        $patient->lastname = $request->input('patient_lname');
        $patient->email = $request->input('email');
        $patient->gender = $request->input('gender');
        $patient->lastfourssn = $request->input('last_4_ssn');
        $patient->addressline1 = $request->input('address_1');
        $patient->addressline2 = $request->input('address_2');
        $patient->city = $request->input('city');
        $patient->zip = $request->input('zip');
        $patient->birthdate = $request->input('dob');
        $patient->preferredlanguage = $request->input('preferredlanguage');
        $patient->cellphone = $request->input('phone');
        $patient->save();
        $path = 'providers?referraltype_id='.$request->input('referraltype_id').'&action='.$request->input('action').'&patient_id='.$patient->id;
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
        
        $patient = Patient::find($id);
        
        $patient->birthdate = date("d F Y", strtotime($patient->birthdate));

        return json_encode($patient);
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

    public function search(Request $request)
    {

        $filters = json_decode($request->input('data'),true);

        $patients = Patient::getPatients($filters);

        $data = [];
        $i = 0;
        foreach($patients as $patient){
            $data[$i]['id'] = $patient->id;
            $data[$i]['fname'] = $patient->firstname;
            $data[$i]['lname'] = $patient->lastname;
            $data[$i]['email'] = $patient->email;
            $data[$i]['phone'] = $patient->cellphone;
            $data[$i]['lastfourssn'] = $patient->lastfourssn;
            $data[$i]['addressline1'] = $patient->addressline1;
            $data[$i]['addressline2'] = $patient->addressline2;
            $data[$i]['city'] = $patient->city;
            $data[$i]['birthdate'] = date('Y-m-d', strtotime($patient->birthdate));
            $i++;
        }

       return json_encode($data);
    }

    public function administration(Request $request)
    {

    }

}
