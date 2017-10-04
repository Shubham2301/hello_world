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

        if (!empty($network_list)) {
            $practice_list = PracticeNetwork::where('network_id', $network_list)->has('practice')->get();
        } else {
            $practice_list = PracticeNetwork::has('practice')->get();
        }
        
        $report_data = self::getProviderReportInfo($practice_list);

        self::exportProviderReportData($network_list, $report_data);
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
            $practice_data[$field['display_name']] = self::getPracticeFieldValue($practice, $field['name']);
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

    private function getPracticeFieldValue($practice, $field_name)
    {
        switch ($field_name) {
            case 'practice_name':
                return $practice->name;
                break;
            case 'practice_first_appointment_date':
                $first_appointment = $practice->appointment->first();
                if ($first_appointment) {
                    return $first_appointment->start_datetime;
                } else {
                    return '';
                }
                break;
            case 'practice_state':
                $state = array();
                $locations = $practice->locations;
                foreach ($locations as $location) {
                    $state[] = trim(strtoupper($location->state));
                }
                $state = array_unique($state);
                return implode('; ', $state);
                break;
            case 'location_names':
                $location_names = array();
                $locations = $practice->locations;
                foreach ($locations as $location) {
                    $location_names[] = trim($location->locationname);
                }
                return implode('; ', $location_names);
                break;
            case 'loc':
                $loc = array();
                $locations = $practice->locations;
                foreach ($locations as $location) {
                    $loc[] = trim(strtoupper($location->location_code));
                }
                $loc = array_unique($loc);
                return implode('; ', $loc);
                break;
            case 'location_count':
                return $practice->locations->count();
                break;
            case 'practice_networks':
                $network = array();
                $network_list = $practice->practiceNetwork;
                foreach ($network_list as $network_info) {
                    $network[] = trim($network_info->network->name);
                }
                return implode('; ', $network);
                break;
            case 'provider_count':
                return $practice->practiceUsers->count();
                break;
            case 'provider_names':
                $provider_name = array();
                $providers = $practice->practiceUsers;
                foreach ($providers as $provider) {
                    $provider_name[] = trim($provider->user->getName('print_format'));
                }
                return implode('; ', $provider_name);
                break;
            case 'appointment_count':
                return $practice->appointment_count;
                break;
            case 'manually_added':
                if ($practice->manually_created) {
                    return 'Yes';
                } else {
                    return 'No';
                }
                break;
            default:
                return '';
                break;
        }
    }
}
