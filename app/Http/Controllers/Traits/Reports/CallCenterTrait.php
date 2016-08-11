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

trait CallCenterTrait
{

    protected $startDate;
    protected $endDate;

    public function generateReport() {

        $user = Auth::user();
        $network = $user->network;

        $results = array();
        $graphData = array();
        $i = 0;

        $userData = User::getCareConsoledata($network->network_id, $this->getStartDate(), $this->getEndDate());

        foreach($userData as $user) {
            $results['user'][$user->id]['name'] = $user->name;
            foreach($user->contactHistory as $ContactHistory) {
                $activityDate = Helper::formatDate($ContactHistory->contact_activity_date, config('constants.date_format'));
                switch ($ContactHistory->action->name) {
                    case 'request-patient-email':
                        break;
                    case 'request-patient-phone':
                        break;
                    case 'request-patient-sms':
                        break;
                    case 'contact-attempted-by-phone':
                        $results['user'][$user->id]['phone'] = isset($results['user'][$user->id]['phone']) ? $results['user'][$user->id]['phone'] + 1 : 1;
                        $graphData[$activityDate]['phone'] = isset($graphData[$activityDate]['phone']) ? $graphData[$activityDate]['phone'] + 1 : 1;
                        break;
                    case 'contact-attempted-by-email':
                        $results['user'][$user->id]['email'] = isset($results['user'][$user->id]['email']) ? $results['user'][$user->id]['email'] + 1 : 1;
                        $graphData[$activityDate]['email'] = isset($graphData[$activityDate]['email']) ? $graphData[$activityDate]['email'] + 1 : 1;
                        break;
                    case 'contact-attempted-by-mail':
                        $results['user'][$user->id]['mail'] = isset($results['user'][$user->id]['mail']) ? $results['user'][$user->id]['mail'] + 1 : 1;
                        $graphData[$activityDate]['mail'] = isset($graphData[$activityDate]['mail']) ? $graphData[$activityDate]['mail'] + 1 : 1;
                        break;
                    case 'contact-attempted-by-other':
                        $results['user'][$user->id]['other'] = isset($results['user'][$user->id]['other']) ? $results['user'][$user->id]['other'] + 1 : 1;
                        $graphData[$activityDate]['other'] = isset($graphData[$activityDate]['other']) ? $graphData[$activityDate]['other'] + 1 : 1;
                        break;
                    case 'patient-notes':
                    case 'requested-data':

                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                    case 'move-to-console':
                    case 'recall-later':
                    case 'unarchive':
                    case 'archive':
                    case 'kept-appointment':
                    case 'no-show':
                    case 'cancelled':
                    case 'data-received':
                    case 'mark-as-priority':
                    case 'remove-priority':
                    case 'annual-exam':
                    case 'refer-to-specialist':
                    case 'highrisk-contact-pcp':
                    default:
                        break;
                }

                if($ContactHistory->actionResult) {
                    switch ($ContactHistory->actionResult->name) {
                        case 'mark-as-priority':
                        case 'already-seen-by-outside-dr':
                        case 'patient-declined-services':
                        case 'other-reasons-for-declining':
                        case 'no-need-to-schedule':
                        case 'no-insurance':
                        case 'unable-to-reach':
                        case 'hold-for-future':
                        case 'success':
                        case 'dropout':
                        default:
                            break;
                    }
                }
            }

        }
        $results['graphData'] = $graphData;
        $results['graphColumn'] = config('reports.call_center_report.graph_legends');

        return $results;

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
}
