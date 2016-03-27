<?php

namespace myocuhub\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Role_user;
use myocuhub\User;

class HomeController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$userID = Auth::user()->id;
		$user = User::find($userID);
		if (isset($user->menu_id)) {
			if ($user->menu_id == 1) {
				return redirect('/directmail');
			} elseif ($user->menu_id == 2) {
				return redirect('/file_exchange');
			} elseif ($user->menu_id == 4) {
				return redirect('/referraltype');
			} elseif ($user->menu_id == 6) {
				return redirect('/careconsole');
			} elseif ($user->menu_id == 7) {
				return redirect('/administration/practices');
			}

		}
		$roles = Role_user::where('user_id', '=', $userID)->get();
		foreach ($roles as $role) {
			if ($role->role_id == 12) {
				return redirect('/careconsole');
				break;
			}
		}
		return redirect('/referraltype');
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
		//
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
}
