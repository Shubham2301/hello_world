<?php

namespace myocuhub\Http\Controllers\Patient;

use Auth;
use DateTime;
use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Careconsole;
use myocuhub\Models\Ccda;
use myocuhub\Models\ImportHistory;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticePatient;
use myocuhub\Models\PracticeUser;
use myocuhub\Models\ReferralHistory;
use myocuhub\Patient;
use myocuhub\User;

class PatientFilesController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request)
	{
		dd($request->all());
	}

	public function getFileUploadView()
	{

	}

}
