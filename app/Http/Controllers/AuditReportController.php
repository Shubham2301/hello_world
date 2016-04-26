<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\AuditLog;
use myocuhub\Network;
use myocuhub\Services\Reports\Reports;
use DateTime;

class AuditReportController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {

        $networks = Network::all();

        $data['networks'] = [];
        foreach ($networks as $network) {
            $data['networks'][] = ['id' => $network->id, 'name' => $network->name];
        }

        return view('reporting.audit')->with('data', $data);
    }

    public function getReports(Request $request)
    {
        $networkId = $request->input('network_id') ?: null;
        $logs = AuditLog::reports($networkId);

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

    }

    public function getNetworkScope()
    {

    }
}
