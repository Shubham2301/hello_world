<?php

namespace myocuhub\Http\Controllers\Traits\Reports\AccountingReports;

use Illuminate\Http\Request;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Models\Appointment;
use myocuhub\Models\Practice;
use myocuhub\Models\ReportField;
use myocuhub\Network;

trait PracticeAppointments
{
    protected function getPracticeAppointments(Request $request)
    {
        if (!policy(new ReportController)->accessAccoutingReport()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/referraltype');
        }

        self::getAppointentsInfo($request->practice_id, $request->network_id);
    }

    protected function getAppointentsInfo($practice_id, $network_id)
    {
        $appointments = Appointment::getPracticeAppointmentInformation($practice_id, $network_id);

        $report_fields = ReportField::where('report_name', 'practice_appointment_export')->get(['name', 'display_name'])->toArray();

        $report_data = self::getReportData($appointments, $report_fields);

        self::exportResults($practice_id, $network_id, $report_data);
    }

    protected function getReportData($appointments, $report_fields)
    {
        $report_data = array();

        foreach ($appointments as $appointment) {
            $report_row_data = array();
            foreach ($report_fields as $field) {
                $report_row_data[$field['display_name']] = self::getFieldValue($appointment, $field['name']);
            }
            $report_data[] = $report_row_data;
        }

        return $report_data;
    }

    protected function exportResults($practice_id, $network_id, $report_data)
    {
        if ($practice_id != 'all') {
            $file_name = 'Practice appointment information';

            $practice = Practice::find($practice_id);
            $file_name .= ' (' . $practice->name .')';
        } else {
            $file_name = 'Network appointment information';
            
            $network = Network::find($network_id);
            $file_name .= ' (' . $network->name .')';
        }
        $export = Helper::exportExcel($report_data, $file_name, '127.0.0.1');
    }

    
}
