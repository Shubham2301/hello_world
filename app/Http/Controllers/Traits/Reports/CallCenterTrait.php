<?php

namespace myocuhub\Http\Controllers\Traits\Reports;

use Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\User;
use Datetime;
use DateInterval;
use Auth;
use myocuhub\Facades\Helper;
use myocuhub\Models\Careconsole;

trait CallCenterTrait
{
    protected $startDate;
    protected $endDate;

    public function generateReport($filter)
    {
        $user = Auth::user();
        $network = $user->network;

        $results = array();
        $graphData = array();
        $i = 0;

        $userData = User::getCareConsoledata($network->network_id, $this->getStartDate(), $this->getEndDate());

        foreach ($userData as $user) {
            $userReportData = array();
            $userReportData['name'] = $user->name;
            $userReportData['id'] = $user->id;
            $userReportData['email'] = 0;
            $userReportData['phone'] = 0;
            $userReportData['sms'] = 0;
            $userReportData['appointment_scheduled'] = 0;
            $userReportData['total'] = 0;

            foreach ($user->contactHistory as $contactHistory) {
                $activityDate = Helper::formatDate($contactHistory->contact_activity_date, config('constants.date_format'));
                switch ($contactHistory->action->name) {
                    case 'request-patient-email':
                    case 'contact-attempted-by-email':
                        $userReportData['email']++;
                        $userReportData['total']++;
                        break;
                    case 'request-patient-phone':
                    case 'contact-attempted-by-phone':
                        $userReportData['phone']++;
                        $userReportData['total']++;
                        break;
                    case 'request-patient-sms':
                        $userReportData['sms']++;
                        $userReportData['total']++;
                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                    case 'previously-scheduled':
                        if (!isset($contactHistory->actionResult) || $contactHistory->actionResult->name != 'incoming-call') {
                            $userReportData['phone']++;
                            $userReportData['total']++;
                        }

                        if ($contactHistory->appointments) {
                            $userReportData['appointment_scheduled']++;
                        }
                        break;
                    default:
                        break;
                }
            }
            $results['user'][$user->id] = $userReportData;
        }

        $patientData = Careconsole::getCallCenterReportData($network->network_id, $this->getStartDate(), $this->getEndDate(), $filter['userID']);

        $results['graphData'] = $this->getGraphData($patientData);
        $results['graphColumn'] = config('reports.call_center_report.graph_legends');
        return $results;
    }

    public function getGraphData($patientData)
    {
        $graphData = array();

        $overviewData = array();

        $comparisonData = $this->graphArray();

        foreach ($patientData as $patient) {
            $lastContactType = 'phone';
            foreach ($patient->contactHistory as $contactHistory) {
                $activityDate = Helper::formatDate($contactHistory->contact_activity_date, 'Ymd');

                if (!isset($overviewData[$activityDate])) {
                    $overviewData[$activityDate] = $this->graphArray();
                    $overviewData[$activityDate]['date'] = Helper::formatDate($contactHistory->contact_activity_date, 'Y-m-d');
                }

                switch ($contactHistory->action->name) {
                    case 'request-patient-email':
                    case 'contact-attempted-by-email':
                        $overviewData[$activityDate]['attempt']['email']++;
                        $overviewData[$activityDate]['attempt']['all']++;
                        $comparisonData['attempt']['email']++;
                        $comparisonData['attempt']['all']++;
                        $lastContactType = 'email';
                        break;
                    case 'request-patient-phone':
                    case 'contact-attempted-by-phone':
                        $overviewData[$activityDate]['attempt']['phone']++;
                        $overviewData[$activityDate]['attempt']['all']++;
                        $comparisonData['attempt']['phone']++;
                        $comparisonData['attempt']['all']++;
                        $lastContactType = 'phone';
                        break;
                    case 'request-patient-sms':
                        $overviewData[$activityDate]['attempt']['sms']++;
                        $overviewData[$activityDate]['attempt']['all']++;
                        $comparisonData['attempt']['sms']++;
                        $comparisonData['attempt']['all']++;
                        $lastContactType = 'sms';
                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                    case 'previously-scheduled':
                        if (isset($contactHistory->actionResult) && $contactHistory->actionResult->name == 'incoming-call') {
                            if ($contactHistory->appointments) {
                                $overviewData[$activityDate]['scheduled'][$lastContactType]++;
                                $overviewData[$activityDate]['scheduled']['all']++;
                                $comparisonData['scheduled'][$lastContactType]++;
                                $comparisonData['scheduled']['all']++;
                            }
                        } else {
                            $overviewData[$activityDate]['attempt']['phone']++;
                            $overviewData[$activityDate]['attempt']['all']++;
                            $comparisonData['attempt']['phone']++;
                            $comparisonData['attempt']['all']++;
                            if ($contactHistory->appointments) {
                                $overviewData[$activityDate]['scheduled']['phone']++;
                                $overviewData[$activityDate]['scheduled']['all']++;
                                $comparisonData['scheduled']['phone']++;
                                $comparisonData['scheduled']['all']++;
                            }
                        }
                        $lastContactType = 'phone';
                        break;
                    default:
                        break;
                }
            }
        }

        ksort($overviewData);

        $graphData['overview'] = $overviewData;
        $graphData['comparison'] = $comparisonData;

        return $graphData;
    }

    public function graphArray()
    {
        $graph = array();
        $graph = [
                'attempt' => [
                    'phone' => 0,
                    'sms' => 0,
                    'email' => 0,
                    'all' => 0,
                ],
                'scheduled' => [
                    'phone' => 0,
                    'sms' => 0,
                    'email' => 0,
                    'all' => 0,
                ],
                'date' => '',
            ];
        return $graph;
    }
}
