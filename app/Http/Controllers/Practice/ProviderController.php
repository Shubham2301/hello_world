<?php

namespace myocuhub\Http\Controllers\Practice;

use Illuminate\Http\Request;
use myocuhub\Models\Practice;
use myocuhub\User;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class ProviderController extends Controller
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

        return view('provider.index')->with('data', $data);
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
        $data = array();
        $provider_id= $request->input('provider_id');
        $practice_id =$request->input('practice_id');

        $provider = User::find($provider_id);
        $practice_name = Practice::find($practice_id)->name;
        $practice_locations =Practice::find($practice_id)->locations;

        $data['practice_name'] = $practice_name;
        $data['practice_id'] = $practice_id;
        $data['provider'] = $provider;
        $data['locations'] = $practice_locations;
        return json_encode($data);
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
           if(!$provider->id || !$provider->user_id) //TODO : check for providers based on entity_type instead of manual check in controller
               continue;
           $data[$i]['provider_id'] = $provider->user_id;
           $data[$i]['practice_id'] = $provider->id;
           $data[$i]['provider_name'] = $provider->firstname.' '.$provider->lastname;
           $data[$i]['practice_name'] = $provider->name;
           $i++;
       }

       return json_encode($data);
   }
}
