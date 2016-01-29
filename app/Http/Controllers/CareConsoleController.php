<?php

namespace myocuhub\Http\Controllers;

use myocuhub\Http\Controllers\Controller;

class CareConsoleController extends Controller
{
    public function index()
    {
        return view('careconsole.index');
    }
}
