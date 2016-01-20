<?php

namespace myocuhub\Http\Controllers;


use myocuhub\NetworkReferraltype;
use myocuhub\Network;
use myocuhub\ReferralType;
use Illuminate\Http\Request;
use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class CareConsoleController extends Controller
{
    public function index()
    {
        return view('careconsole.index');
    }
}
