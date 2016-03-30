<?php

namespace myocuhub\Http\Controllers\DirectMail;

use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Services\SES\SESConnect;

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
		$ses = $this->sesConnect->getDirectMail();

		if (!$ses['direct_mail_str']) {
			$request->session()->flash('no_direct_mail', 'You do not have a direct mail access. If you feel this is in error, please contact the OcuHub administrator for assistance.');
		}
		$data = array();
		$data['direct-mail'] = true;

		return view('directmail.index')->with('ses', $ses)->with('data', $data);

	}
    
    public function beginImpersonate(Request $request) {
        
        session(['impersonation-id' => $request->id]);
        
        $this->index();

	}
    
    public function endImpersonate(Request $request) {
        
        session(['impersonation-id' => '']);
        
        $this->index();
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
