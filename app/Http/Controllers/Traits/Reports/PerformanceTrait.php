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

trait PerformanceTrait
{

    protected $startDate;
    protected $endDate;

    public function generateReport($network) {

        $timelineGraph = array();

        $reportResult = array();

        $userCount = User::getCareCoordinatorCount($network);

        $contactList = ContactHistory::getBillingReportData($network, $this->getStartDate(), $this->getEndDate());

        foreach($contactList as $contact) {

            $activityDate = Helper::formatDate($contact->contact_activity_date, 'Ymd');

            if(!isset($timelineGraph[$activityDate])) {
                $timelineGraph[$activityDate] = $this->graphArray();
                $timelineGraph[$activityDate]['date'] = Helper::formatDate($contact->contact_activity_date, 'Y-m-d');
            }

            switch ($contact->action->name) {
                    case 'request-patient-email':
                    case 'request-patient-phone':
                    case 'request-patient-sms':
                    case 'contact-attempted-by-phone':
                    case 'contact-attempted-by-email':
                    case 'contact-attempted-by-mail':
                    case 'contact-attempted-by-other':
                        $timelineGraph[$activityDate]['contactAttempted']++;
                        break;
                    case 'patient-notes':
                    case 'requested-data':
                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                        $timelineGraph[$activityDate]['reached']++;
                        $timelineGraph[$activityDate]['appointmentScheduled']++;
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
                        $timelineGraph[$activityDate]['keptAppointment']++;
                        $timelineGraph[$activityDate]['reportsDue']++;
                        break;
                    case 'no-show':
                    case 'cancelled':
                        $timelineGraph[$activityDate]['missedAppointment']++;
                        break;
                    case 'data-received':
                        $timelineGraph[$activityDate]['reportsReceived']++;
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

            if($contact->actionResult) {
                switch ($contact->actionResult->name) {
                    case 'mark-as-priority':
                        break;
                    case 'already-seen-by-outside-dr':
                    case 'patient-declined-services':
                    case 'other-reasons-for-declining':
                    case 'no-need-to-schedule':
                    case 'no-insurance':
                        $timelineGraph[$activityDate]['reached']++;
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
                        $timelineGraph[$activityDate]['dropped']++;
                        break;
                    default:
                        break;

                }
            }
        }

        $reportResult['timelineGraph'] = $timelineGraph;
        $reportResult['graphType'] = $this->graphType();

        $totalNetworkPatient = CareConsole::getTotalPatientCount($network);
        $totalNotScheduledPatient = CareConsole::getStageCount($network, 1);

        $overAllGraph = array();
        $overAllGraph['total_patient'] = $totalNetworkPatient;
        $overAllGraph['pending_patient'] = $totalNotScheduledPatient;
        $overAllGraph['completed_patient'] = $totalNetworkPatient - $totalNotScheduledPatient;

        $reportResult['overAllGraph'] = $overAllGraph;
        $reportResult['userCount'] = $userCount > 0 ? $userCount : 1;

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

    public function graphArray() {

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

    public function graphType() {

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
}
