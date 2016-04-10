<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Services\Reports\Reports;

class ReportingController extends Controller
{
    private $Reports;

    public function __construct(Reports $Reports)
    {
        $this->Reports = $Reports;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['reports'] = true;
        return view('reporting.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->Reports->setStartDate($request->start_date);
        $this->Reports->setEndDate($request->end_date);

        $data['total_referred'] = $this->Reports->getTotalReferred();
        $data['to_be_called'] = $this->Reports->getPendingToBeCalled();
        $data['scheduled'] = $this->Reports->getReferredTo();
        $data['referred_by'] = $this->Reports->getReferredBy();
        $data['appointment_status'] = $this->Reports->getAppointmentStatus();

        return json_encode($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
