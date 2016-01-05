<?php

namespace myocuhub\Http\Controllers\Practice;

use Illuminate\Http\Request;
use myocuhub\Models\Practice;
use myocuhub\User;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class PracticeController extends Controller
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
        if ($request->has('patient_id')) {
            $data['patient_id'] = $request->input('patient_id');
        }

        return view('practice.index')->with('data', $data);
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
    public function show($id)
    {
        $id = 1;

        $practice = Practice::find($id);
        
        return json_encode($practice);
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
        //search quar
        $providers = User::practiceUser($filters);
        $data = [];
        $i = 0;

        foreach($providers as $provider){
            $data[$i]['id'] = $provider->id;
            $data[$i]['name'] = $provider->name;
            $data[$i]['email'] = $provider->email;
            $data[$i]['locations'] = $provider->locationname;
            $data[$i]['doctor_name'] = $provider->firstname.' '.$provider->lastname;
            $data[$i]['user_id'] = $provider->user_id;
            $data[$i]['user_phone'] = $provider->phone;
            $data[$i]['zipcode'] = $provider->zip;
            $i++;
        }

        return json_encode($data);    
    }
}
