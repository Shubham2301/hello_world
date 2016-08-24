<?php

namespace myocuhub\Http\Controllers\Reports;

use myocuhub\Http\Controllers\Traits\Reports\CallCenterTrait;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;
use myocuhub\User;
use Datetime;
use myocuhub\Facades\Helper;

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

        $data['call-center'] = true;
        return view('reports.call_center_report.index')->with('data', $data);
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
        $filter = $request->filter_option;

        $report_data = $this->generateReport($filter);
        return $report_data;
    }

}
