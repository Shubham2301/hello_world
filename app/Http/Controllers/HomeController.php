<?php

namespace myocuhub\Http\Controllers;


use myocuhub\NetworkReferraltype;
use myocuhub\Network;
use myocuhub\ReferralType;
use Illuminate\Http\Request;
use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: change network_id : 1 to network id  of logged in user
         $referralType = Network::find(1)->referralTypes;
         $referralTypeList=ReferralType::all();
        return view('home');
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
    public function show()
    {
        $referralType = Network::find(1)->referralTypes;
        $referralTypeList = ReferralType::all();
        $data = [];
        $data[0] = $referralType;
        $data[1] = $referralTypeList;
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
    
    // Functions for Referral Type Tiles
    
    public function removeReferral(Request $request){
        
        // TODO: change network_id : 1 to network id  of logged in user
        
        $id = $request->input('id');
        
        NetworkReferraltype::where('network_id', 1) 
            ->where('referraltype_id', $id)
            ->delete();
        return;
    }
    
    public function addReferral(Request $request){

        $id = $request->input('id');
        $newref = new NetworkReferraltype;
        $newref->network_id=1;
        $newref->referraltype_id=$id;
        $newref->save();
        return;
    }
}
