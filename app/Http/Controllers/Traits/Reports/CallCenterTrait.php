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

    public function generateReport($filter) {

        $user = Auth::user();
        $network = $user->network;

        $results = array();
        $graphData = array();
        $i = 0;

        $userData = User::getCareConsoledata($network->network_id, $this->getStartDate(), $this->getEndDate());

        foreach($userData as $user) {
            $userReportData = array();
            $userReportData['name'] = $user->name;
            $userReportData['id'] = $user->id;
            $userReportData['email'] = 0;
            $userReportData['phone'] = 0;
            $userReportData['sms'] = 0;
            $userReportData['total'] = 0;

            foreach($user->contactHistory as $ContactHistory) {
                $activityDate = Helper::formatDate($ContactHistory->contact_activity_date, config('constants.date_format'));
                switch ($ContactHistory->action->name) {
                    case 'request-patient-email':
                        $userReportData['email']++;
                        $userReportData['total']++;
                        break;
                    case 'request-patient-phone':
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
                        if(isset($ContactHistory->actionResult) && $ContactHistory->actionResult->name != 'incoming-call') {
                            $userReportData['phone']++;
                            $userReportData['total']++;
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

    public function getGraphData ($patientData) {

        $graphData = array();

        $overviewData = array();

        $comparisonData = $this->graphArray();

        foreach ($patientData as $patient) {
            $lastContactType = 'phone';
            foreach ($patient->contactHistory as $contactHistory) {

                $activityDate = Helper::formatDate($contactHistory->contact_activity_date, 'Ymd');

                if(!isset($overviewData[$activityDate])) {
                    $overviewData[$activityDate] = $this->graphArray();
                    $overviewData[$activityDate]['date'] = Helper::formatDate($contactHistory->contact_activity_date, 'Y-m-d');
                }

                switch ($contactHistory->action->name) {
                    case 'request-patient-email':
                        $overviewData[$activityDate]['attempt']['email']++;
                        $overviewData[$activityDate]['attempt']['all']++;
                        $comparisonData['attempt']['email']++;
                        $lastContactType = 'email';
                        break;
                    case 'request-patient-phone':
                        $overviewData[$activityDate]['attempt']['phone']++;
                        $overviewData[$activityDate]['attempt']['all']++;
                        $comparisonData['attempt']['phone']++;
                        $lastContactType = 'phone';
                        break;
                    case 'request-patient-sms':
                        $overviewData[$activityDate]['attempt']['sms']++;
                        $overviewData[$activityDate]['attempt']['all']++;
                        $comparisonData['attempt']['sms']++;
                        $lastContactType = 'sms';
                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                        if(isset($contactHistory->actionResult) && $contactHistory->actionResult->name == 'incoming-call') {
                            $overviewData[$activityDate]['scheduled'][$lastContactType]++;
                            $overviewData[$activityDate]['scheduled']['all']++;
                            $comparisonData['scheduled'][$lastContactType]++;
                        }
                        else {
                            $overviewData[$activityDate]['scheduled']['phone']++;
                            $overviewData[$activityDate]['attempt']['phone']++;
                            $overviewData[$activityDate]['scheduled']['all']++;
                            $overviewData[$activityDate]['attempt']['all']++;
                            $comparisonData['scheduled']['phone']++;
                            $comparisonData['attempt']['phone']++;
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

    public function setStartDate($startDate)
    {
        $this->startDate = Helper::formatDate($startDate, config('constants.db_date_format'));
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = Helper::formatDate($endDate, config('constants.db_date_format'));
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function graphArray() {

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
