<?php

namespace myocuhub\Http\Controllers\Traits\Reports\AccountingReports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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

        $result = array();
        $result['Payer Overview Data'] = self::getPayerOverviewData($filter);
        $result['Payer Patient Data'] = self::getPayerPatientData($filter);

        self::exportPayerReportData($network_id, $result);
    }

    private function getPayerOverviewData($filter)
    {
        $report_fields = ReportField::where('report_name', 'accounting_payer_billing')->get(['name', 'display_name'])->toArray();

        $result = Careconsole::getPayerReportInfo($filter, $report_fields);

        return $result;
    }

    private function getPayerPatientData($filter)
    {
        $report_fields = ReportField::where('report_name', 'accounting_payer_billing_patient_detail')->get(['name', 'display_name'])->toArray();

        $careconsole_results = Careconsole::getPayerReportPatientInfo($filter);

        $result = array();
        foreach ($careconsole_results as $careconsole) {
            $result_row = array();
            foreach ($report_fields as $field) {
                $result_row[$field['display_name']] = self::getFieldValue($careconsole, $field['name']);
            }

            $result[] = $result_row;
        }

        return $result;
    }

    private function exportPayerReportData($network_id, $result)
    {
        $file_name = 'Payer Billing Export';
        $network = Network::find($network_id);
        $file_name .= ' (' . $network->name . ')';

        $excel = Excel::create($file_name, function ($excel) use ($result) {
            foreach ($result as $key => $sheet_data) {
                $excel->sheet($key, function ($sheet) use ($sheet_data) {
                    $sheet->setWidth([]);
                    $sheet->setPageMargin(0.25);
                    $sheet->fromArray($sheet_data);
                    $sheet->cell('A1:Z1', function ($cells) {
                        $cells->setFont(array(
                            'family'     => 'Calibri',
                            'size'       => '11',
                            'bold'       =>  true
                        ));
                    });
                });
            }
        });

        $excel->export('xlsx');
    }
}
