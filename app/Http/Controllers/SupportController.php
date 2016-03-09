<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class SupportController extends Controller
{
    public function techSupportIndex()
    {
        $data = array();
        return view('support.support')->with('data', $data);
    }

    public function contactusIndex()
    {
        $data = array();
        return view('support.contactus')->with('data', $data);
    }

    public function investorsIndex()
    {
        $data = array();
        return view('support.investors')->with('data', $data);
    }

    public function privacyIndex()
    {
        $data = array();
        return view('support.privacy')->with('data', $data);
    }

    public function sitemapIndex()
    {
        $data = array();
        return view('support.sitemap')->with('data', $data);
    }

    public function termsIndex()
    {
        $data = array();
        return view('support.terms')->with('data', $data);
    }

}
