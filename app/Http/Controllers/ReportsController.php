<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Services\Reports\Reports;

class ReportsController extends Controller
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
        return view('reports.index')->with('data', $data);
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
        $reportData = array();
//        $dates = $request->dateFilter;
        $this->Reports->setStartDate($request->dateFilter['start_date']);
        $this->Reports->setEndDate($request->dateFilter['end_date']);
        $this->Reports->setFilters($request->filters);
//        dd($request->end_date);
//        $filters = [
//            "type" => "real-time",
//            "status_of_patients" => "none",
//            "disease_type" => "none",
//            "severity_scale" => "none",
//            "incomming_referrals" => [
//                "appointment_type" => "none",
//                "referred_by" => [
//                    "type" => "none",
//                    "name" => "none",
//                ],
//            ],
//            "patient_demographics" => [
//                "age" => "none",
//                "gender" => "none",
//                "insurance_type" => "none",
//            ],
//            "referred_to" => [
//                "type" => "none",
//                "name" => "none",
//            ],
//        ];

        $data = $this->Reports->getReportingData($request->filters);

//        $reportData['appointment_type'][0] = ["name" => "Type 1", "count" => 3];
//        $reportData['appointment_type'][1] = ["name" => "Type 2", "count" => 1];
//
//        $reportData['age_demographics']['category1'] = ["count" => 2, "name" => "<35"];
//        $reportData['age_demographics']['category2'] = ["count" => 3, "name" => "35-44"];
//        $reportData['age_demographics']['category3'] = ["count" => 3, "name" => "45-54"];
//
//        $reportData['gender_demographics'] = ["male" => 75, "female" => 25];
//
//        $reportData['insurance_demographics'][0] = ["name" => "Medicare", "count" => 3];
//        $reportData['insurance_demographics'][1] = ["name" => "Medicade", "count" => 1];
//
//        $reportData['disease_type'][0] = ["name" => "Dental"];
//        $reportData['disease_type'][0]['severity'][0] = ["type" => "Severe", "count" => 1];
////        $reportData['disease_type'][1] = ["name" => "Glaucoma", "severity" => [["type" => "Moderate", "count" => 2], ["type" => "Severe", "count" => 1]]];
//
//        $reportData['referred_by']['type'] = "practice";
//        $reportData['referred_by']['total'] = 4;
//        $reportData['referred_by']['data'] = [["name" => "United Eye", "count" => 3], ["name" => "UCLI", "count" => 1]];
//
//        $reportData['referred_to']['type'] = "practice";
//        $reportData['referred_to']['total'] = 4;
//        $reportData['referred_to']['data'] = [["name" => "Test practice 1", "count" => 3, "id" => "23049"], ["name" => "Practice 2", "count" => 1, "id " => "23067"]];
//
//        $reportData['status_of_patients'][0] = ["name" => "Pending Contact", "count" => 3, "id" => "pending_contact", "percent" => 50];
//        $reportData['status_of_patients'][1] = ["name" => "Contact Attempted", "count" => 0, "id" => "contact_attempted", "percent" => 0];
//        $reportData['status_of_patients'][2] = ["name" => "Pending Contact", "count" => 3, "id" => "pending_contact", "percent" => 50];
//        $reportData['status_of_patients'][3] = ["name" => "Contact Attempted", "count" => 0, "id" => "contact_attempted", "percent" => 0];
//        $reportData['status_of_patients'][4] = ["name" => "Pending Contact", "count" => 3, "id" => "pending_contact", "percent" => 50];
//        $reportData['status_of_patients'][5] = ["name" => "Contact Attempted", "count" => 0, "id" => "contact_attempted", "percent" => 0];
//
//        return json_encode($reportData);
        return $data;
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
