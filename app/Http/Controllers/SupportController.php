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

    public function contactusIndex()
    {
        return view('support.contactus');
    }

    public function investorsIndex()
    {
        return view('support.investors');
    }

    public function privacyIndex()
    {
        return view('support.privacy');
    }

    public function sitemapIndex()
    {
        return view('support.sitemap');
    }

    public function termsIndex()
    {
        return view('support.terms');
    }

}
