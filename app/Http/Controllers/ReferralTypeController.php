<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Http\Requests;
use myocuhub\Network;
use myocuhub\NetworkReferraltype;
use myocuhub\ReferralType;

class ReferralTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isSuperAdmin()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }
        $data = array();
		$data['schedule-patient'] = true;
        return view('home')->with('data', $data);
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
        if (session('user-level') == 1) {
			$referralType = ReferralType::all();
		} else {
			$referralType = Network::find(session('network-id'))->referralTypes;
		}
		$referralTypeList = ReferralType::all();
		$data = [];
		$data[0] = $referralType;
		$data[1] = $referralTypeList;
		$data['user_level'] = session('user-level');
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

	public function removeReferral(Request $request) {
		if (session('user-level') > 1) {
            $id = $request->input('id');
			NetworkReferraltype::where('network_id', session('network-id'))
				->where('referraltype_id', $id)
				->delete();
		}

		return;
	}

	public function addReferral(Request $request) {

		if (session('user-level') > 1) {
			$id = $request->input('id');
			$newref = new NetworkReferraltype;
			$newref->network_id = session('network-id');
			$newref->referraltype_id = $id;
			$newref->save();
		}
		return;
	}
}
