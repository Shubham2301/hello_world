<?php

namespace myocuhub\Http\Controllers\Reports;

use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ReportField;
use myocuhub\Network;
use myocuhub\Patient;
use myocuhub\User;

class NetworkStateActivityController extends ReportController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        if (!policy(new ReportController)->accessNetworkStateActivityReport()) {
            $request->session()->flash('failure', 'Unauthorized Access. Please contact your administrator.');
            return redirect('/referraltype');
        }

        $networks = Network::all()->sortBy("name");
        foreach ($networks as $network) {
            $networkData[$network->id] = $network->name;
        }
        reset($networkData);

        $networkState = Network::networkStateList();

        $data['network-state-activity'] = true;
        return view('reports.network_state_activity.index')->with('data', $data)->with('networkData', $networkData)->with('networkState', $networkState);
    }

    public function getReportData(Request $request)
    {
        $report_filter['start_date'] = Helper::formatDate($request->start_date, config('constants.report_date_format.start_date_time'));
        $report_filter['end_date'] = Helper::formatDate($request->end_date, config('constants.report_date_format.end_date_time'));
        $report_filter['network_id'] = $request->network_id;
        $report_filter['state_list'] = $request->state_list;

        $report_fields = ReportField::where('report_name', 'network_state_export')->get(['name', 'display_name']);

        $report_data['Overview'] = Careconsole::getNetworkStateActivityData($report_filter, $report_fields->toArray());
        $report_data['Patient List'] = Careconsole::getNetworkStatePatientList($report_filter);

        self::exportData($report_data, $report_filter);

        return true;
    }

    public function exportData($report_data, $report_filter)
    {
        $file_name = 'Network State Export - ';
        $file_name .= Network::find($report_filter['network_id'])->name;
        if (!empty($report_filter['state_list'])) {
            $file_name .= ' (' . implode(',', $report_filter['state_list']) . ')';
        }

        $fileType = 'xlsx';
        
        $excel = Excel::create($file_name, function ($excel) use ($report_data) {
            foreach ($report_data as $key => $sheet_data) {
                $excel->sheet($key, function ($sheet) use ($sheet_data) {
                $sheet->setWidth([]);
                $sheet->setPageMargin(0.25);
                $sheet->fromArray($sheet_data);
                $sheet->cell('A1:Z1', function ($cells) {
                        $cells->setFont(array(
                            'family'     => 'Calibri',
                            'size'       => '11',
                            'bold'       =>  true
                        ));
                    });
                });
            }
        });

        $excel->export($fileType);
    }
}
