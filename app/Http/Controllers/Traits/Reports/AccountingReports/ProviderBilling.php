<?php

namespace myocuhub\Http\Controllers\Traits\Reports\AccountingReports;

use Illuminate\Http\Request;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeNetwork;
use myocuhub\Models\ReportField;
use myocuhub\Network;

trait ProviderBilling
{
    private $export_column_width = [
        'A'     =>  20,
        'B'     =>  15,
        'C'     =>  5,
        'D'     =>  5,
        'E'     =>  15,
        'F'     =>  10,
        'G'     =>  5,
        'H'     =>  20,
        'I'     =>  30,
        'J'     =>  5,
        'K'     =>  30,
        'L'     => 10,
        'M'     => 10,
        'N'     => 10
    ];

    protected function getProviderBilling(Request $request)
    {
        if (!policy(new ReportController)->accessAccoutingReport()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/referraltype');
        }

        $network_list = array();

        switch ($request->network_id) {
            case 'all':
                break;
            default:
                $network_list[] = $request->network_id;
                break;
        }

        $practice_list = self::getPracticeList($network_list);
        
        $report_data = self::getProviderReportInfo($practice_list);

        self::exportProviderReportData($network_list, $report_data);
    }

    private function getPracticeList($network_list)
    {
        if (!empty($network_list)) {
            $practice_list = PracticeNetwork::where('network_id', $network_list)->with(['practice' => function ($sub_query) {
                $sub_query->withTrashed();
            }])->get();
        } else {
            $practice_list = PracticeNetwork::with(['practice' => function ($sub_query) {
                $sub_query->withTrashed();
            }])->get();
        }

        $practice_list = $practice_list->sortBy(function ($practice_network) {
            return $practice_network->practice->name;
        });

        $practice_list = $practice_list->unique(function ($practice_network) {
            return $practice_network->practice_id;
        });

        return $practice_list;
    }

    private function getProviderReportInfo($practice_list)
    {
        $report_data = array();
        foreach ($practice_list as $practice) {
            $report_data[]= self::getPracticeData($practice->practice_id);
        }
        
        return $report_data;
    }

    private function getPracticeData($practice_id)
    {
        $practice_data = array();
        
        $practice = Practice::getPracticeBillingInformation($practice_id);

        $report_fields = ReportField::where('report_name', 'accounting_provider_billing')->get(['name', 'display_name'])->toArray();
        
        foreach ($report_fields as $field) {
            $practice_data[$field['display_name']] = self::getFieldValue($practice, $field['name']);
        }

        return $practice_data;
    }

    private function exportProviderReportData($network_list, $report_data)
    {
        $file_name = 'Provider Billing Export';
        if (empty($network_list)) {
            $file_name .= ' (All Network)';
        } else {
            $network = Network::find($network_list[0]);
            $file_name .= ' (' . $network->name . ')';
        }

        $export = Helper::exportExcel($report_data, $file_name, '127.0.0.1', $this->export_column_width);
    }
}
