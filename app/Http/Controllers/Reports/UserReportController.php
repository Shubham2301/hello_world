<?php

namespace myocuhub\Http\Controllers\Reports;

use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Reports\ReportController;
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


}
