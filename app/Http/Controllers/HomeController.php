<?php

namespace myocuhub\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Network;
use myocuhub\NetworkReferraltype;
use myocuhub\ReferralType;
use myocuhub\User;

class HomeController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$referralType = Network::find($network->network_id)->referralTypes;
		$referralTypeList = ReferralType::all();
		return view('home');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show() {
		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$referralType = Network::find($network->network_id)->referralTypes;
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
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}

	// Functions for Referral Type Tiles

	public function removeReferral(Request $request) {

		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$id = $request->input('id');

		NetworkReferraltype::where('network_id', $network->network_id)
			->where('referraltype_id', $id)
			->delete();
		return;
	}

	public function addReferral(Request $request) {
		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$id = $request->input('id');
		$newref = new NetworkReferraltype;
		$newref->network_id = $network->network_id;
		$newref->referraltype_id = $id;
		$newref->save();
		return;
	}
}
