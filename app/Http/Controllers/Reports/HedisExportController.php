<?php

namespace myocuhub\Http\Controllers\Reports;

use Auth;
use Carbon\Carbon;
use Datetime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Facades\Hedis;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Models\NetworkWebForm;
use myocuhub\Models\PatientRecord;
use myocuhub\Models\WebFormTemplate;
use myocuhub\Network;
use myocuhub\User;

class HedisExportController extends ReportController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!policy(new ReportController)->accessHedisExport()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/home');
        }

        $networks = Network::all()->sortBy("name");
        foreach ($networks as $network) {
            $networkData[$network->id] = $network->name;
        }

        $data['hedis_export'] = true;
        return view('reports.hedis_export.index')->with('data', $data)->with('networkData', $networkData);
    }

    public function generate(Request $request)
    {
        $network_id = $request->network_id;
        $export = Hedis::index($network_id);
        return $export;
    }

    public function export($network_id)
    {
        $patient_list = array();
        $patient_list = Hedis::getNetworkPatientList($network_id);

        $patient_file_data = Hedis::generateFileData($patient_list);

        $current_date_time = Carbon::now()->toDateString();
        $file_name = 'illuma-'.$current_date_time;

        $export = Helper::exportExcel($patient_file_data, $file_name, '127.0.0.1', array(), 'csv');
        return '1';
    }
}
