<?php

namespace myocuhub\Http\Controllers\Reports;

use myocuhub\Facades\Helper;
use myocuhub\Network;
use myocuhub\Models\NetworkUser;
use myocuhub\Http\Controllers\Reports\ReportController;
use Illuminate\Http\Request;
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

        $networkID = ($request->network_id ) ? $request->network_id :null;
        $networkData = [];   
        $networkUser = NetworkUser::networkUserData($networkID);
        foreach ($networkUser as $user) {
            $networkData[] = [
                'Name' => $user->user_name,
                'Email' => $user->user_email,
                'Type' => ($user->usertypes_name) ? $user->usertypes_name : '-',
                'Provider Type' => ($user->provider_type) ? $user->provider_type : '-',
                'NPI' => ($user->npi) ? $user->npi : '-',
                '4PC Account Key' => ($user->acc_key) ? $user->acc_key : '-',
                'Level' => ($user->userlevel_name) ? $user->userlevel_name : '-',
                'Organization' => ($user->practice_name) ? $user->practice_name : $user->network_name,
                'Direct Address' => ($user->direct_address) ? $user->direct_address : '-',
                'Username' => ($user->ses_username) ? $user->ses_username : '-',
                'Status' => $user->user_status == 1 ? 'Active' : 'Deleted'
            ];
        }    
        usort($networkData, 'self::cmp');
        return $networkData;

    }

    public function generateReportExcel(Request $request)
    {
        $networkID = ($request->network_id) ? $request->network_id : null;
        $networkName = ($request->network_id ) ?  Network::find($networkID)->name : 'Ocuhub' ;

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

        $export = Helper::exportExcel($data, $fileName, $request->getClientIp());
    }

    private static function cmp($a, $b)
    {
        return strcasecmp  ($a["Name"], $b["Name"]);
    }

}
