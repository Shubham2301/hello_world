<?php

namespace myocuhub\Http\Controllers\Reports;

use Auth;
use Datetime;
use Illuminate\Http\Request;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Http\Controllers\Traits\Reports\CallCenterTrait;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;
use myocuhub\Network;
use myocuhub\User;

class CallCenterController extends ReportController
{

    use CallCenterTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!policy(new ReportController)->accessCallCenterReport()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/referraltype');
        }


        $networks = Network::all()->sortBy("name");
        foreach ($networks as $network) {
            $networkData[$network->id] = $network->name;
        }
        reset($networkData);

        $data['call-center'] = true;
        return view('reports.call_center_report.index')->with('data', $data)->with('networkData', $networkData);
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
        $network_id = $request->network_id;

        $report_data = $this->generateReport($network_id);
        return $report_data;
    }

}
