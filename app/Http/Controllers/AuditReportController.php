<?php

namespace myocuhub\Http\Controllers;

use DateTime;
use Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\AuditLog;
use myocuhub\Models\FPCWritebackAudit;
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
                    'name' => $log->user_fname ?: '' . ' ' . $log->user_lname ?: '',
                    'id' => $log->user_id ?: '',
                    'network_name' => $log->network_name ?: '',
                    'network_id' => $log->network_id ?: '',
                    'action' => $log->action ?: '',
                    'date' => $log->created_at ? $date->format('F j Y, g:i a') : ''
                ];
            }
            return json_encode($audit);
        } elseif ($reportType == 'impersonation_report') {
            $logs = ImpersonationAudit::ImpersonationReport($networkId, $startDate, $endDate);
            $impersonationaudit = [];

            foreach ($logs as $log) {
                $date = new Datetime($log->created_at);
                $impersonated_user = User::find($log->impersonated_id);

                $impersonationaudit[] = [
                    'name' => $log->user_fname ?: '' . ' ' . $log->user_lname ?: '',
                    'id' => $log->user_id ?: '',
                    'network_name' => $log->network_name ?: '',
                    'network_id' => $log->network_id ?: '',
                    'action' => 'Impersonated '.$impersonated_user->name ?: ''.' '.$impersonated_user->lname ?: '',
                    'date' => $log->created_at ? $date->format('F j Y, g:i a') : ''
                ];
            }
            return json_encode($impersonationaudit);
        } else {
            $logs = FPCWritebackAudit::WritebackReport($networkId, $startDate, $endDate);

            $FPCWriteback = [];

            foreach ($logs as $log) {
                $date = new Datetime($log->created_at);
                $network = Network::find($networkId);

                $FPCWriteback[] = [
                    'name' => $log->patient->getName('print_format'),
                    'id' => $log->patient->id ?: '',
                    'network_name' => isset($log->patient->careConsole->importHistory->network) ? $log->patient->careConsole->importHistory->network->name : '',
                    'network_id' => isset($log->patient->careConsole->importHistory->network) ? $log->patient->careConsole->importHistory->network->id : '',
                    'action' => 'Appointment date - ' .$log->appointments->start_datetime . ', Scheduled with - ' . $log->provider->getName('print_format'),
                    'date' => $log->created_at ? $date->format('F j Y, g:i a') : ''
                ];
            }

            return json_encode($FPCWriteback);
        }
    }

    public function getNetworkScope()
    {
    }

    public function downloadAsXSLS(Request $request)
    {
        $fileName = 'Audit Report';

        if ($request->report_type === 'audit_report') {
            $fileName = 'Audit Report';
        } elseif ($request->report_type == 'impersonation_report') {
            $fileName = 'Impersonation Report';
        } else {
            $fileName = '4PC Writeback Report';
        }

        $logs = $this->getReports($request);
        $logs = json_decode($logs, true);
        $data = [];
        foreach ($logs as $log) {
            $data[] = [
                'Date' => $log['date'],
                'Name' => $log['name'],
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
