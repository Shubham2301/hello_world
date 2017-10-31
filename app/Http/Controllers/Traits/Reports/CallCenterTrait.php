<?php

namespace myocuhub\Http\Controllers\Traits\Reports;

use Auth;
use DateInterval;
use Datetime;
use Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\Facades\Helper;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ReportField;
use myocuhub\User;

trait CallCenterTrait
{
    protected $startDate;
    protected $endDate;
    protected $graph_format = [
                'contact_attempts' => 0,
                'appointment_scheduled_incoming' => 0,
                'appointment_scheduled_outgoing' => 0,
                'date' => '',
            ];

    public function generateReport($network_id)
    {
        $call_center_data = array();
        $overview_graph_data = array();

        $carecoordinator_data = User::getCareConsoledata($network_id, $this->getStartDate(), $this->getEndDate());

        foreach ($carecoordinator_data as $user) {
            $user_data = array();
            $user_data['user_name'] = $user->getName('print_format');
            $user_data['user_id'] = $user->id;
            $user_data['contact_attempts'] = 0;
            $user_data['appointment_scheduled_incoming'] = 0;
            $user_data['appointment_scheduled_outgoing'] = 0;

            foreach ($user->contactHistory as $contactHistory) {
                $activityDate = Helper::formatDate($contactHistory->contact_activity_date, 'Ymd');

                if (!isset($overview_graph_data[$activityDate])) {
                    $overview_graph_data[$activityDate] = $this->graph_format;
                    $overview_graph_data[$activityDate]['date'] = Helper::formatDate($contactHistory->contact_activity_date, 'Y-m-d');
                }

                switch ($contactHistory->action->name) {
                    case 'request-patient-email':
                    case 'contact-attempted-by-email':
                    case 'contact-attempted-by-phone':
                    case 'request-patient-sms':
                        $overview_graph_data[$activityDate]['contact_attempts']++;
                        $user_data['contact_attempts']++;
                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                    case 'previously-scheduled':
                        if (!isset($contactHistory->actionResult) || $contactHistory->actionResult->name != 'incoming-call') {
                            if ($contactHistory->appointments) {
                                $user_data['appointment_scheduled_outgoing']++;
                                $overview_graph_data[$activityDate]['appointment_scheduled_outgoing']++;
                            }
                        } else {
                            if ($contactHistory->appointments) {
                                $user_data['appointment_scheduled_incoming']++;
                                $overview_graph_data[$activityDate]['appointment_scheduled_incoming']++;
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
            $call_center_data[] = $user_data;
        }

        usort($call_center_data, 'self::cmp');
        ksort($overview_graph_data);

        $report_data = [
            'call_center_data' => $call_center_data,
            'overview_graph_data' => $overview_graph_data
        ];

        return json_encode($report_data);
    }

    private static function cmp($a, $b)
    {
        return $a["user_name"] > $b["user_name"];
    }

    public function exportUserData(Request $request)
    {
        $this->setStartDate($request->start_date);
        $this->setEndDate($request->end_date);
        $network_id = $request->network_id;
        $user_id = $request->user_id;

        $user_data = User::getCareConsoledata($network_id, $this->getStartDate(), $this->getEndDate(), $user_id)->first();

        $report_fields = ReportField::where('report_name', 'call_center_export')->get(['name', 'display_name'])->toArray();

        $export_data = array();
        foreach ($user_data->contactHistory as $contactHistory) {
            $export_row_data = array();

            switch ($contactHistory->action->name) {
                case 'request-patient-email':
                case 'contact-attempted-by-email':
                case 'contact-attempted-by-phone':
                case 'request-patient-sms':
                    foreach ($report_fields as $field) {
                        $export_row_data[$field['display_name']] = self::getFieldValue($contactHistory, $field['name']);
                    }
                    $export_data[] = $export_row_data;
                    break;
                case 'schedule':
                case 'reschedule':
                case 'manually-schedule':
                case 'manually-reschedule':
                case 'previously-scheduled':
                    if ($contactHistory->appointments) {
                        foreach ($report_fields as $field) {
                            $export_row_data[$field['display_name']] = self::getFieldValue($contactHistory, $field['name']);
                        }
                        $export_data[] = $export_row_data;
                    }
                    break;
            }
        }

        $user = User::find($user_id);
        $file_name = 'Call Center Export - ' . $user->getName('print_format'); 

        $export = Helper::exportExcel($export_data, $file_name, '127.0.0.1');
    }
}
