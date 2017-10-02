<?php

namespace myocuhub\Http\Controllers\Traits\Reports\AccountingReports;

use Illuminate\Http\Request;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ReportField;
use myocuhub\Network;

trait PayerBilling
{
    protected function getPayerBilling(Request $request)
    {
        if (!policy(new ReportController)->accessAccoutingReport()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/referraltype');
        }

        self::setStartDate($request->start_date);
        self::setEndDate($request->end_date);

        self::getPayerReportInfo($request->network_id);
    }

    private function getPayerReportInfo($network_id)
    {
        $filter = array();
        $filter['start_date'] = self::getStartDate();
        $filter['end_date'] = self::getEndDate();
        $filter['network_id'] = $network_id;

        $report_fields = ReportField::where('report_name', 'accounting_payer_billing')->get(['name', 'display_name'])->toArray();

        $result = Careconsole::getPayerReportInfo($filter, $report_fields);

        self::exportPayerReportData($network_id, $result);
    }

    private function exportPayerReportData($network_id, $result)
    {
        $file_name = 'Payer Billing Export';
        $network = Network::find($network_id);
        $file_name .= ' (' . $network->name . ')';

        $export = Helper::exportExcel($result, $file_name, '127.0.0.1');
    }
}
