<?php

namespace myocuhub\Http\Controllers\Reports;

use myocuhub\Http\Controllers\Traits\Reports\PerformanceTrait;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\User;
use Datetime;
use myocuhub\Facades\Helper;
use myocuhub\Network;

class PerformanceController extends ReportController
{

    use PerformanceTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!policy(new ReportController)->accessPerformanceReport()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/referraltype');
        }

        $networks = Network::all()->sortBy("name");
        foreach ($networks as $network) {
            $networkData[$network->id] = $network->name;
        }

        $data['performance-report'] = true;
        return view('reports.performance.index')->with('data', $data)->with('networkData', $networkData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->setStartDate($request->start_date);
        $this->setEndDate($request->end_date);
        $network = $request->network;

        $report_data = $this->generateReport($network);
        return $report_data;
    }

}
