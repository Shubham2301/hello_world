<?php

namespace myocuhub\Http\Controllers\Reports;

use myocuhub\Http\Controllers\Traits\Reports\ReachRateTrait;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Models\ContactHistory;
use myocuhub\User;
use Datetime;
use myocuhub\Services\CareConsoleService;
use myocuhub\Facades\Helper;
use Maatwebsite\Excel\Facades\Excel;

class ReachRateController extends ReportController
{
    use ReachRateTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!policy(new ReportController)->accessReachReport()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/referraltype');
        }
        $data['reach_report'] = true;
        return view('reports.reach_rate_report.index')->with('data', $data);
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

        $report_data = $this->generateReport();

        usort($report_data, 'self::cmp');

        $result = $this->renderResult($report_data, $filter);

        return($result);
    }

    private static function cmp($a, $b)
    {
        return strcasecmp($a["patient_name"], $b["patient_name"]);
    }

    public function generateReportExcel(Request $request)
    {
        $this->setStartDate($request->start_date);
        $this->setEndDate($request->end_date);
        $filter = $request->filter_option;
        $exportField = $request->export_field;

        $report_data = $this->generateReport();

        usort($report_data, 'self::cmp');

        $data = $this->renderExcelData($report_data, $filter, $exportField);

        switch ($exportField) {
            case 'not_reached':
                $fileName = 'Reach rate report - Not reached data';
                break;
            case 'not_scheduled':
                $fileName = 'Reach rate report - Not scheduled data';
                break;
            case 'no_show':
                $fileName = 'Reach rate report - No show data';
            case 'no_reports':
                $fileName = 'Reach rate report - No reports';
                break;
        }
        $fileType = 'xlsx';
        Excel::create($fileName, function ($excel) use ($data) {
            $excel->sheet('Audits', function ($sheet) use ($data) {
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
