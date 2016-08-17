<?php

namespace myocuhub\Http\Controllers\Reports;

use myocuhub\Http\Controllers\Traits\Reports\CallCenterTrait;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;
use myocuhub\User;
use Datetime;
use myocuhub\Facades\Helper;

class CallCenterController extends Controller
{

    use CallCenterTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('reports.call_center_report.index');
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

        $report_data = $this->generateReport();

        return $report_data;
    }

}
