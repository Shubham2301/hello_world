<?php

namespace myocuhub\Http\Controllers\Reports;

use Auth;
use Datetime;
use Illuminate\Http\Request;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Http\Controllers\Traits\Reports\AccountingReports\ProviderBilling;
use myocuhub\Network;

class AccountingReportController extends ReportController
{
    use ProviderBilling;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        if (!policy(new ReportController)->accessAccoutingReport()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/referraltype');
        }

        $networks = Network::all()->sortBy("name");
        foreach ($networks as $network) {
            $networkData[$network->id] = $network->name;
        }

        $data['accounting_reports'] = true;
        return view('reports.accounting_reports.index')->with('data', $data)->with('networkData', $networkData);
    }

}
