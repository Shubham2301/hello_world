<?php

namespace myocuhub\Http\Controllers\Reports;

use myocuhub\Http\Controllers\Traits\Reports\PerformanceTrait;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\User;
use Datetime;
use Maatwebsite\Excel\Facades\Excel;
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
        $filter = $request->filter_option;
        $network = $request->network;

        $report_data = $this->generateReport($network, $filter['filterType']);
        return $report_data;
    }

    public function generateReportExcel(Request $request)
    {
        $filter = $request->filter_option;
        $fileName = 'Performance Report: ' . $filter['filterHeader'];

        $reportData = $this->show($request);
        $reportData = $reportData['drilldown'];
        $data = [];
        foreach ($reportData['data'] as $dataRow) {
            $rowData = [];
            foreach ($dataRow as $key => $value) {
                $rowData[$reportData['columns'][$key]] = $value;
            }
            $data[] = $rowData;
        }
        $fileType = 'xlsx';
        Excel::create($fileName, function ($excel) use ($data) {
            $excel->sheet('Audits', function ($sheet) use ($data) {
                $sheet->setWidth(array(
                    'A'     =>  35,
                    'B'     =>  35,
                    'C'     =>  35,
                    'D'     =>  35,
                    'E'     =>  35,
                    'F'     =>  35,
                ));

                $sheet->setPageMargin(0.25);
                $sheet->fromArray($data);
                $sheet->cell('A1:F1', function ($cells) {
                    $cells->setFont(array(
                        'family'     => 'Calibri',
                        'size'       => '11',
                        'bold'       =>  true
                    ));
                });
            });
        })->export($fileType);
    }

}
