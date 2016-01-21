<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class SupportController extends Controller
{
    public function techSupportIndex()
    {
        return view('support.support');
    }
}
