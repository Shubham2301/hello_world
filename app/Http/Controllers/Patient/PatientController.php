<?php

namespace myocuhub\Http\Controllers\Patient;

use Illuminate\Http\Request;
use myocuhub\Patient;

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

        return view('patient.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $type = $request->input('type');
        $value = $request->input('value');

        $filters = [['type' => $type, 'value' => $value]];
        /*
        TODO:
            recieve request in JSON format and add multiple filters in $filters
            search patients on multiple filters are the same time.

            sample array:
                $filters = [
                    ['type' => 'name', 'value' => 'Abhishek'],
                    ['type' => 'ssn', 'value' => '5151']
                ];
        */

        $patients = Patient::where(function ($query) use ($filters) {
                foreach($filters as $filter) {
                    switch($filter['type']){
                        case 'name' :
                            $query->where('firstname', $filter['value'])
                            ->orWhere('middlename', $filter['value'])
                            ->orWhere('lastname', $filter['value']);
                            break;
                        case 'ssn' :
                            $query->where('lastfourssn', $filter['value']);
                            break;
                        case 'all' :
                            $query->where('firstname', $filter['value'])
                            ->orWhere('middlename', $filter['value'])
                            ->orWhere('lastname', $filter['value'])
                            ->orWhere('lastfourssn', $filter['value']);
                            break;
                    }
                }
            })
            ->get();

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
            $data[$i]['birthdate'] = $patient->birthdate;
            $i++;
        }

        return json_encode($data);
    }
}
