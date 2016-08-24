<?php

namespace myocuhub\Http\Controllers;

use DateTime;
use Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\AuditLog;
use myocuhub\Models\ImpersonationAudit;
use myocuhub\Network;
use myocuhub\Services\Reports\Reports;
use myocuhub\User;

class AuditReportController extends Controller
{

    public function __construct()
    {
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
        return view('admin.reporting.audit')->with('data', $data);
    }

    public function getReports(Request $request)
    {
        $networkId = $request->input('network_id') ?: null;

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $reportType = $request->report_type;

        if ($reportType == 'audit_report') {
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

    public function downloadAsXSLS(Request $request)
    {
        $fileName = 'Impersonation Report';

        if ($request->report_type === 'audit_report') {
            $fileName = 'Audit Report';
        }
        $logs = $this->getReports($request);
        $logs = json_decode($logs, true);
        $data = [];
        foreach ($logs as $log) {
            $data[] = [
                'Date' => $log['date'],
                'User Name' => $log['user_name'],
                'Network Name' => $log['network_name'],
                'Action' => $log['action'],

            ];
        }
        $fileType = 'xlsx';
        Excel::create($fileName, function ($excel) use ($data) {
            $excel->sheet('Audits', function ($sheet) use ($data) {
                $sheet->setWidth(array(
                    'A'     =>  35,
                    'B'     =>  35,
                    'C'     =>  35,
                    'D'     =>  55
                ));

                $sheet->setPageMargin(0.25);
                $sheet->fromArray($data);
                $sheet->cell('A1:D1', function ($cells) {
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
