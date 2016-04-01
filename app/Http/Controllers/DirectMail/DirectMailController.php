<?php

namespace myocuhub\Http\Controllers\DirectMail;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\ImpersonationAudit;
use myocuhub\Services\SES\SESConnect;
use myocuhub\User;

class DirectMailController extends Controller {
	private $sesConnect;

	public function __construct(SESConnect $sesConnect) {
		$this->sesConnect = $sesConnect;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {

		if (session('impersonation-logged-in') != '' && session('impersonation-logged-in') != Auth::user()->id) {
			$audit = new ImpersonationAudit;

			$audit->user_impersonated_id = session('impersonation-id');
			$audit->logged_in_user_id = Auth::user()->id;
			$audit->action = 'END IMPERSONATION';

			session(['impersonation-logged-in' => '']);
			session(['impersonation-id' => '']);
			session(['impersonation-name' => '']);

			$audit->save();
		}

		$ses = $this->sesConnect->getDirectMail();

		if (!$ses['direct_mail_str']) {
			$request->session()->flash('no_direct_mail', 'You do not have a direct mail access. If you feel this is in error, please contact the OcuHub administrator for assistance.');
		}
		$data = array();
		$data['direct-mail'] = true;

		$impersonation = $this->sesConnect->getImpersonationScope();

		return view('directmail.index')->with('ses', $ses)->with('data', $data)->with('impersonation', $impersonation);

	}

	public function beginImpersonate(Request $request) {

		if (!$this->sesConnect->checkScope($request->impersonateuser)) {
			$request->session()->flash('no_direct_mail', 'You do not have access rights to impersonate this user.');
		} else {

			$audit = new ImpersonationAudit;

			session(['impersonation-id' => $request->impersonateuser]);
			session(['impersonation-logged-in' => Auth::user()->id]);
			session(['impersonation-name' => User::find($request->impersonateuser)->name]);

			$audit->user_impersonated_id = $request->impersonateuser;
			$audit->logged_in_user_id = Auth::user()->id;
			$audit->action = 'BEGIN IMPERSONATION';

			$audit->save();

		}

		return redirect('directmail');

	}

	public function endImpersonate() {

		$audit = new ImpersonationAudit;

		$audit->user_impersonated_id = session('impersonation-id');
		$audit->logged_in_user_id = Auth::user()->id;
		$audit->action = 'END IMPERSONATION';

		session(['impersonation-logged-in' => '']);
		session(['impersonation-id' => '']);
		session(['impersonation-name' => '']);

		$audit->save();

		return redirect('directmail');
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
	public function show($id) {
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
