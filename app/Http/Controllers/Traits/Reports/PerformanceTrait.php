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
use myocuhub\Models\ContactHistory;
use myocuhub\Models\CareConsole;
use myocuhub\Models\GoalNetwork;
use myocuhub\Models\Goal;
use myocuhub\Services\CareConsoleService;

trait PerformanceTrait
{
    protected $startDate;
    protected $endDate;
    private $CareConsoleService;

    public function __construct(CareConsoleService $CareConsoleService)
    {
        $this->CareConsoleService = $CareConsoleService;
    }

    public function generateReport($network, $filterType)
    {
        $timelineGraph = array();

        $reportResult = array();

        $drillDownData = [
            'avgContact' => array(),
            'avgReached' => array(),
            'avgScheduled' => array(),
            'scheduled_vs_dropped' => array(),
            'keptAppointment_vs_missed' => array(),
            'receivedReport_vs_pending' => array(),
        ];

        $reportAggregationData = [
            'Users' => 0,
            'Contact attempts' => 0,
            'Reached' => 0,
            'Scheduled' => 0,
            'Dropped' => 0,
            'Kept Appointment' => 0,
            'Missed Appointment' => 0,
            'Reports Received' => 0,
            'Reports Pending' => 0,
        ];

        $userCount = User::getCareCoordinatorCount($network);

        $contactList = ContactHistory::getPerformanceReportData($network, $this->getStartDate(), $this->getEndDate());

        foreach ($contactList as $contact) {
            $activityDate = Helper::formatDate($contact->contact_activity_date, 'Ymd');

            if (!isset($timelineGraph[$activityDate])) {
                $timelineGraph[$activityDate] = $this->graphArray();
                $timelineGraph[$activityDate]['date'] = Helper::formatDate($contact->contact_activity_date, config('constants.date_format'));
            }

            switch ($contact->action->name) {
                    case 'request-patient-email':
                    case 'request-patient-phone':
                    case 'request-patient-sms':
                    case 'contact-attempted-by-phone':
                    case 'contact-attempted-by-email':
                    case 'contact-attempted-by-mail':
                    case 'contact-attempted-by-other':
                        $reportAggregationData['Contact attempts']++;
                        $timelineGraph[$activityDate]['contactAttempted']++;
                        $drillDownData['avgContact'][] = [$this->fillDetailData($contact, 'contactDate'), $this->fillDetailData($contact, 'userName'), $this->fillDetailData($contact, 'patientName'), $this->fillDetailData($contact, 'actionResultName'), $this->fillDetailData($contact, 'actionName')];
                        break;
                    case 'patient-notes':
                    case 'requested-data':
                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                    case 'previously-scheduled':
                        $reportAggregationData['Scheduled']++;
                        $timelineGraph[$activityDate]['appointmentScheduled']++;
                        $drillDownData['avgScheduled'][] = [
                            $this->fillDetailData($contact, 'contactDate'),
                            $this->fillDetailData($contact, 'userName'),
                            $this->fillDetailData($contact, 'patientName'),
                            $this->fillDetailData($contact, 'appointmentDate')
                        ];
                        $drillDownData['scheduled_vs_dropped'][] = [
                            $this->fillDetailData($contact, 'contactDate'),
                            $this->fillDetailData($contact, 'userName'),
                            $this->fillDetailData($contact, 'patientName'),
                            $this->fillDetailData($contact, 'actionName')
                        ];

                        if (!isset($contact->actionResult->name) || $contact->actionResult->name != 'incoming-call') {
                            $reportAggregationData['Reached']++;
                            $reportAggregationData['Contact attempts']++;
                            $timelineGraph[$activityDate]['reached']++;
                            $timelineGraph[$activityDate]['contactAttempted']++;
                            $drillDownData['avgContact'][] = [
                                $this->fillDetailData($contact, 'contactDate'),
                                $this->fillDetailData($contact, 'userName'),
                                $this->fillDetailData($contact, 'patientName'),
                                $this->fillDetailData($contact, 'actionResultName'),
                                $this->fillDetailData($contact, 'actionName')
                            ];
                            $drillDownData['avgReached'][] = [
                                $this->fillDetailData($contact, 'contactDate'),
                                $this->fillDetailData($contact, 'userName'),
                                $this->fillDetailData($contact, 'patientName'),
                                $this->fillDetailData($contact, 'actionName')
                            ];
                        }
                        break;
                    case 'move-to-console':
                        break;
                    case 'recall-later':
                        break;
                    case 'unarchive':
                        break;
                    case 'archive':
                        break;
                    case 'kept-appointment':
                        $reportAggregationData['Reports Pending']++;
                        $timelineGraph[$activityDate]['reportsDue']++;
                        $drillDownData['receivedReport_vs_pending'][] = [
                            $this->fillDetailData($contact, 'appointmentDate'),
                            $this->fillDetailData($contact, 'userName'),
                            $this->fillDetailData($contact, 'patientName'),
                            $this->fillDetailData($contact, 'practiceName'),
                            $this->fillDetailData($contact, 'actionName')
                        ];
                        break;
                    case 'no-show':
                    case 'cancelled':
                        break;
                    case 'data-received':
                        $reportAggregationData['Reports Received']++;
                        $timelineGraph[$activityDate]['reportsReceived']++;
                        $drillDownData['receivedReport_vs_pending'][] = [
                            $this->fillDetailData($contact, 'appointmentDate'),
                            $this->fillDetailData($contact, 'userName'),
                            $this->fillDetailData($contact, 'patientName'),
                            $this->fillDetailData($contact, 'practiceName'),
                            $this->fillDetailData($contact, 'actionName')
                        ];
                        break;
                    case 'mark-as-priority':
                        break;
                    case 'remove-priority':
                        break;
                    case 'annual-exam':
                        break;
                    case 'refer-to-specialist':
                    case 'highrisk-contact-pcp':
                    default:
                        break;
                }

            if ($contact->actionResult) {
                switch ($contact->actionResult->name) {
                    case 'mark-as-priority':
                        break;
                    case 'already-seen-by-outside-dr':
                    case 'patient-declined-services':
                    case 'other-reasons-for-declining':
                    case 'no-need-to-schedule':
                    case 'no-insurance':
                        $reportAggregationData['Reached']++;
                        $reportAggregationData['Dropped']++;
                        $timelineGraph[$activityDate]['reached']++;
                        $timelineGraph[$activityDate]['dropped']++;
                        $drillDownData['avgReached'][] = [
                            $this->fillDetailData($contact, 'contactDate'),
                            $this->fillDetailData($contact, 'userName'),
                            $this->fillDetailData($contact, 'patientName'),
                            $this->fillDetailData($contact, 'actionResultName')
                        ];
                        $drillDownData['scheduled_vs_dropped'][] = [
                            $this->fillDetailData($contact, 'contactDate'),
                            $this->fillDetailData($contact, 'userName'),
                            $this->fillDetailData($contact, 'patientName'),
                            $this->fillDetailData($contact, 'actionResultName')
                        ];
                        break;
                    case 'unable-to-reach':
                        break;
                    case 'hold-for-future':
                        break;
                    case 'incorrect-data':
                        break;
                    case 'success':
                        break;
                    case 'dropout':
                        $reportAggregationData['Dropped']++;
                        $timelineGraph[$activityDate]['dropped']++;
                        $drillDownData['scheduled_vs_dropped'][] = [
                            $this->fillDetailData($contact, 'contactDate'),
                            $this->fillDetailData($contact, 'userName'),
                            $this->fillDetailData($contact, 'patientName'),
                            $this->fillDetailData($contact, 'actionName')
                        ];
                        break;
                    case 'incoming-call':
                        break;
                    case 'outgoing-call':
                        break;
                    default:
                        break;

                }
            }
        }

        $apptContactList = ContactHistory::getPerformanceReportAppointmentData($network, $this->getStartDate(), $this->getEndDate());

        foreach ($apptContactList as $apptContact) {
            $activityDate = Helper::formatDate($apptContact->contact_activity_date, 'Ymd');

            if (!isset($timelineGraph[$activityDate])) {
                $timelineGraph[$activityDate] = $this->graphArray();
                $timelineGraph[$activityDate]['date'] = Helper::formatDate($apptContact->contact_activity_date, config('constants.date_format'));
            }

            switch ($apptContact->action->name) {
                    case 'request-patient-email':
                    case 'request-patient-phone':
                    case 'request-patient-sms':
                    case 'contact-attempted-by-phone':
                    case 'contact-attempted-by-email':
                    case 'contact-attempted-by-mail':
                    case 'contact-attempted-by-other':
                        break;
                    case 'patient-notes':
                    case 'requested-data':
                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                    case 'previously-scheduled':
                        break;
                    case 'move-to-console':
                        break;
                    case 'recall-later':
                        break;
                    case 'unarchive':
                        break;
                    case 'archive':
                        break;
                    case 'kept-appointment':
                        $reportAggregationData['Kept Appointment']++;
                        $timelineGraph[$activityDate]['keptAppointment']++;
                        $drillDownData['keptAppointment_vs_missed'][] = [
                            $this->fillDetailData($apptContact, 'appointmentDate'),
                            $this->fillDetailData($apptContact, 'userName'),
                            $this->fillDetailData($apptContact, 'patientName'),
                            $this->fillDetailData($apptContact, 'actionName'),
                            $this->fillDetailData($apptContact, 'appointementType')
                        ];
                        break;
                    case 'no-show':
                    case 'cancelled':
                        $reportAggregationData['Missed Appointment']++;
                        $timelineGraph[$activityDate]['missedAppointment']++;
                        $drillDownData['keptAppointment_vs_missed'][] = [
                            $this->fillDetailData($apptContact, 'appointmentDate'),
                            $this->fillDetailData($apptContact, 'userName'),
                            $this->fillDetailData($apptContact, 'patientName'),
                            $this->fillDetailData($apptContact, 'actionName'),
                            $this->fillDetailData($apptContact, 'appointementType')
                        ];
                        break;
                    case 'data-received':
                        break;
                    case 'mark-as-priority':
                        break;
                    case 'remove-priority':
                        break;
                    case 'annual-exam':
                        break;
                    case 'refer-to-specialist':
                    case 'highrisk-contact-pcp':
                    default:
                        break;
            }
        }

        $reportResult['timelineGraph'] = $timelineGraph;
        $reportResult['graphType'] = $this->graphType();

        $graphGoal = array();
        $Goals = Goal::all();
        foreach ($Goals as $goal) {
            $networkGraphGoal = GoalNetwork::where('goal_id', $goal->id)->where('network_id', $network)->first();
            switch ($goal->name) {
                case 'avg_contact_attempted_per_day_per_user':
                    $graphGoal['avgContact'] = $networkGraphGoal ? $networkGraphGoal->value : 0;
                    break;
                case 'avg_reached_per_day_per_user':
                    $graphGoal['avgReached'] = $networkGraphGoal ? $networkGraphGoal->value : 0;
                    break;
                case 'avg_scheduled_per_day_per_user':
                    $graphGoal['avgScheduled'] = $networkGraphGoal ? $networkGraphGoal->value : 0;
                    break;
                default:
            }
        }

        $reportResult['graph_goal'] = $graphGoal;
        $totalNetworkPatient = CareConsole::getTotalPatientCount($network);
        $totalNotScheduledPatient = CareConsole::getStageCount($network, 1);

        $overAllGraph = array();
        $overAllGraph['total_patient'] = $totalNetworkPatient;
        $overAllGraph['pending_patient'] = $totalNotScheduledPatient;
        $overAllGraph['completed_patient'] = $totalNetworkPatient - $totalNotScheduledPatient;

        $reportResult['overAllGraph'] = $overAllGraph;
        $reportResult['userCount'] = $userCount > 0 ? $userCount : 1;

        $reportAggregationData['Users'] = $userCount;
        $reportResult['reportAggregationData'] = $reportAggregationData;

        if ($filterType != '') {
            $reportResult['drilldown'] = [
                'columns' => $this->drillDownDataColumns($filterType),
                'data' => $drillDownData[$filterType],
            ];
        }
        return $reportResult;
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

    public function graphArray()
    {
        $graph = array();
        $graph = [
            'contactAttempted' => 0,
            'reached' => 0,
            'appointmentScheduled' => 0,
            'dropped' => 0,
            'keptAppointment' => 0,
            'missedAppointment' => 0,
            'reportsReceived' => 0,
            'reportsDue' => 0,
            'date' => '',
        ];

        return $graph;
    }

    public function graphType()
    {
        $graph = array();
        $graph = [
            'goalGraph' => ['avgContact','avgReached','avgScheduled'],
            'compareGraph' => ['scheduled_vs_dropped','keptAppointment_vs_missed','receivedReport_vs_pending'],
            'graphColumn' => [
                'avgContact' => ['Date', 'Contact per User', 'Goal'],
                'avgReached' => ['Date', 'Reached per User', 'Goal'],
                'avgScheduled' => ['Date', 'Scheduled per User', 'Goal'],
                'scheduled_vs_dropped' => ['Date', 'Scheduled per User', 'Dropped per User'],
                'keptAppointment_vs_missed' => ['Date', 'Kept Appointment per User', 'Missed Appointment per User'],
                'receivedReport_vs_pending' => ['Date', 'Received Reports per User', 'Reports Pending per User'],
            ]
        ];

        return $graph;
    }

    public function drillDownDataColumns($drilldownType)
    {
        $dataColumns = array();

        switch ($drilldownType) {
            case 'avgContact':
                $dataColumns = ['Attempt Date', 'User', 'Patient', 'Result', 'Type of Contact'];
                break;
            case 'avgReached':
                $dataColumns = ['Date', 'User', 'Patient', 'Result'];
                break;
            case 'avgScheduled':
                $dataColumns = ['Scheduled Date', 'User', 'Patient', 'Appointment Date'];
                break;
            case 'scheduled_vs_dropped':
                $dataColumns = ['Date', 'User', 'Patient', 'Result'];
                break;
            case 'keptAppointment_vs_missed':
                $dataColumns = ['Appointment Date', 'User', 'Patient', 'Result', 'Type of Appointment'];
                break;
            case 'receivedReport_vs_pending':
                $dataColumns = ['Appointment Date', 'User', 'Patient', 'Practice', 'Type'];
                break;
            default:
        }

        return $dataColumns;
    }

    public function fillDetailData($requestData, $option = null)
    {
        $result = '';

        switch ($option) {
            case 'contactDate':
                $result = Helper::formatDate($requestData->contact_activity_date, config('constants.date_format'));
                break;
            case 'userName':
                $result = $requestData->users->name;
                break;
            case 'patientName':
                $result = $this->CareConsoleService->getPatientFieldValue($requestData->careconsole->patient, 'print-name');
                break;
            case 'practiceName':
                $result = (isset($requestData->appointments) && isset($requestData->appointments->practice)) ? $requestData->appointments->practice->name : '-';
                break;
            case 'appointementType':
                $result = isset($requestData->appointments) ? $requestData->appointments->appointmenttype : '-';
                break;
            case 'appointmentDate':
                $result = isset($requestData->appointments) ? Helper::formatDate($requestData->appointments->start_datetime, config('constants.date_format')) : '-';
                break;
            case 'typeOfContact':
                break;
            case 'actionName':
                $result = $requestData->action->display_name;
                break;
            case 'actionResultName':
                $result = $requestData->actionResult ? $requestData->actionResult->display_name : '-';
                break;
            default:
        }

        return $result;
    }
}
