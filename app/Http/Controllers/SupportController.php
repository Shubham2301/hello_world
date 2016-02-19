<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class SupportController extends Controller
{
    $data = array();
    public function techSupportIndex()
    {
        return view('support.support')->with('data', $data);
    }

    public function contactusIndex()
    {
        return view('support.contactus')->with('data', $data);
    }

    public function investorsIndex()
    {
        return view('support.investors')->with('data', $data);
    }

    public function privacyIndex()
    {
        return view('support.privacy')->with('data', $data);
    }

    public function sitemapIndex()
    {
        return view('support.sitemap')->with('data', $data);
    }

    public function termsIndex()
    {
        return view('support.terms')->with('data', $data);
    }

}
