<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\AuditLog;
use myocuhub\Models\ImpersonationAudit;
use myocuhub\Network;
use myocuhub\User;
use myocuhub\Services\Reports\Reports;
use DateTime;
use Event;
use myocuhub\Events\MakeAuditEntry;

class AuditReportController extends Controller
{

    public function __construct() {
		$this->middleware('role:admin_report,1');
	}


    public function index(Request $request)
    {

        $networks = Network::all();

        $data['networks'] = [];
        foreach ($networks as $network) {
            $data['networks'][] = ['id' => $network->id, 'name' => $network->name];
        }

        $action = 'Audit Reports Accessed';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

        $data['audit_report'] = true;
        return view('reporting.audit')->with('data', $data);
    }

    public function getReports(Request $request)
    {

        $networkId = $request->input('network_id') ?: null;

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $reportType = $request->report_type;

        if($reportType == 'audit_report') {

            $logs = AuditLog::reports($networkId, $startDate, $endDate);

            $audit = [];

            foreach ($logs as $log) {

                $date = new Datetime($log->created_at);

                $audit[] = [
                    'user_name' => $log->user_fname ?: '' . ' ' . $log->user_lname ?: '',
                    'user_id' => $log->user_id ?: '',
                    'network_name' => $log->network_name ?: '',
                    'network_id' => $log->network_id ?: '',
                    'action' => $log->action ?: '',
                    'date' => $log->created_at ? $date->format('F j Y, g:i a') : ''
                ];

            }
            return json_encode($audit);
        } else {

            $logs = ImpersonationAudit::ImpersonationReport($networkId, $startDate, $endDate);
            $impersonationaudit = [];

            foreach ($logs as $log) {

                $date = new Datetime($log->created_at);
                $impersonated_user = User::find($log->impersonated_id);

                $impersonationaudit[] = [
                    'user_name' => $log->user_fname ?: '' . ' ' . $log->user_lname ?: '',
                    'user_id' => $log->user_id ?: '',
                    'network_name' => $log->network_name ?: '',
                    'network_id' => $log->network_id ?: '',
                    'action' => 'Impersonated '.$impersonated_user->name ?: ''.' '.$impersonated_user->lname ?: '',
                    'date' => $log->created_at ? $date->format('F j Y, g:i a') : ''
                ];
            }
            return json_encode($impersonationaudit);
        }
    }

    public function getNetworkScope()
    {

    }
}
