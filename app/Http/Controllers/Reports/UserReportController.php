<?php

namespace myocuhub\Http\Controllers\Reports;

use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Reports\ReportController;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Models\NetworkUser;
use myocuhub\Network;
use myocuhub\User;

class UserReportController extends ReportController
{

//    use UserReportTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!policy(new ReportController)->accessUserReport()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/referraltype');
        }

        $data['user-report'] = true;
        return view('reports.user_report.index')->with('data', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $report_data = $this->generateReport();
        return $report_data;
    }

    public function generateReport() {
        $reportData = [];
        $networkData = [];
        $networks = Network::all();
        $networkData[] = [
            'name' => 'Ocuhub',
            'activeNetworkUser' => User::where('level', 1)->where('active', 1)->count(),
            'inactiveNetworkUser' => User::where('level', 1)->where('active', 0)->count()
        ];
        foreach ($networks as $network) {
            $networkData[$network->id]['name'] = $network->name;
            $networkData[$network->id]['activeNetworkUser'] = NetworkUser::where('network_id', $network->id)->whereHas('user', function($query) { $query->where('active', 1);})->count();
            $networkData[$network->id]['inactiveNetworkUser'] = NetworkUser::where('network_id', $network->id)->whereHas('user', function($query) { $query->where('active', 0);})->count();
        }

        $reportData['total_active_user'] = User::where('active', 1)->count();
        $reportData['total_inactive_user'] = User::where('active', 0)->count();

        $reportData['networkData'] = $networkData;
        return $reportData;
    }

    public function getNetworkData (Request $request) {

        $networkID = $request->network_id;
        $networkData = [];
        $networkUser = NetworkUser::where('network_id', $networkID)->get();
        foreach ($networkUser as $user) {
            $networkData[] = [
                'Name' => $user->user->name,
                'Email' => $user->user->email,
                'Status' => $user->user->active == 1 ? 'Active' : 'Deleted'
            ];
        }
        usort($networkData, 'self::cmp');
        return $networkData;

    }

    public function generateReportExcel(Request $request)
    {
        $networkID = $request->network_id;
        $networkName = Network::find($networkID)->name;

        $fileName = $networkName . ' - User List';

        $reportData = $this->getNetworkData($request);

        $data = [];
        foreach ($reportData as $dataRow) {
            $rowData = [];
            foreach ($dataRow as $key => $value) {
                $rowData[$key] = $value;
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

    private static function cmp($a, $b)
    {
        return strcasecmp  ($a["Name"], $b["Name"]);
    }

}
