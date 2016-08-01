<?php

namespace myocuhub\Http\Controllers\Traits\Reports;

use Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Models\ContactHistory;
use myocuhub\User;
use Datetime;
use Auth;

trait ReachRateTrait
{

    protected $startDate;
    protected $endDate;

    public function generateReport()
    {
        $userID = Auth::user()->id;
        $network = User::getNetwork($userID);
        $networkID = $network->network_id;

        $results = array(array());

        $careconsole_data = Careconsole::getReachRateData($networkID, $this->getStartDate(), $this->getEndDate());

        $patient_count = 0;

        foreach ($careconsole_data as $careconsole) {

            $history = array();
            $history['archived'] = 0;
            $history['unarchived'] = 0;

            if($careconsole->import_history_count != 0)
                $results[$patient_count]['patient_type'] = config('reports.patient_type.new');
            else
                $results[$patient_count]['patient_type'] = config('reports.patient_type.old');

            foreach ($careconsole->contactHistory as $contact_history) {

                switch ($contact_history->action->name) {
                    case 'request-patient-email':
                        break;
                    case 'request-patient-phone':
                        break;
                    case 'request-patient-sms':
                        break;
                    case 'contact-attempted-by-phone':
                    case 'contact-attempted-by-email':
                    case 'contact-attempted-by-mail':
                    case 'contact-attempted-by-other':
                        if(!isset($results[$patient_count]['not_reached']) && !isset($results[$patient_count]['reached']))
                            $results[$patient_count]['pending_stage_change'] = $contact_history->days_in_current_stage;
                        break;
                    case 'patient-notes':
                    case 'requested-data':

                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                        $results[$patient_count]['appointment_scheduled'] = isset($results[$patient_count]['appointment_scheduled']) ? $results[$patient_count]['appointment_scheduled'] + 1 : 1;
                        $results[$patient_count]['reached'] = isset($results[$patient_count]['reached']) ? $results[$patient_count]['reached'] + 1 : 1;
                        $results[$patient_count]['reached_stage_change'] = $contact_history->days_in_prev_stage;
                        break;
                    case 'move-to-console':
                        break;
                    case 'recall-later':
                        break;
                    case 'unarchive':
                        if(($history['unarchived'] == $history['archived'] && $history['unarchived'] != 0) || $history['unarchived'] < $history['archived']) {
                            $patient_count++;
                            $results[$patient_count]['repeat_count'] = 1;
                            $results[$patient_count]['patient_type'] = $results[$patient_count - 1]['patient_type'];
                        }
                        $history['unarchived']++;
                        break;
                    case 'archive':
                        $history['archived']++;
                        break;
                    case 'kept-appointment':
                        $results[$patient_count]['appointment_completed'] = config('reports.appointment_completed.show');
                        if(isset($contact_history->appointments)) {
                            $appt_date = new DateTime($contact_history->appointments->start_datetime);
                            $action_date = new DateTime($contact_history->contact_activity_date);
                            $interval = $action_date->diff($appt_date);
                            $date_diff = $interval->format('%a');
                            $results[$patient_count]['appt_scheduled_stage_change'] = $date_diff;
                        }
                        break;
                    case 'no-show':
                        $results[$patient_count]['appointment_completed'] = config('reports.appointment_completed.no_show');
                        break;
                    case 'cancelled':
                        $results[$patient_count]['appointment_completed'] = config('reports.appointment_completed.no_show');
                        break;
                    case 'data-received':
                        $results[$patient_count]['reports'] = 1;
                        $results[$patient_count]['show_stage_change'] = $contact_history->days_in_prev_stage;
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

                if($contact_history->actionResult) {
                    switch ($contact_history->actionResult->name) {
                        case 'mark-as-priority':
                            break;
                        case 'already-seen-by-outside-dr':
                        case 'patient-declined-services':
                        case 'other-reasons-for-declining':
                        case 'no-need-to-schedule':
                        case 'no-insurance':
                            $history['archived']++;
                            $results[$patient_count]['archived'] = config('reports.archive.dropout');
                            $results[$patient_count]['reached'] = isset($results[$patient_count]['reached']) ? $results[$patient_count]['reached'] + 1 : 1;
                            break;
                        case 'unable-to-reach':
                            $results[$patient_count]['not_reached'] = isset($results[$patient_count]['not_reached']) ? $results[$patient_count]['not_reached'] + 1 : 1;
                            $results[$patient_count]['unable_to_reach'] = isset($results[$patient_count]['unable_to_reach']) ? $results[$patient_count]['unable_to_reach'] + 1 : 1;
                            break;
                        case 'hold-for-future':
                            $results[$patient_count]['not_reached'] = isset($results[$patient_count]['not_reached']) ? $results[$patient_count]['not_reached'] + 1 : 1;
                            $results[$patient_count]['hold_for_future'] = isset($results[$patient_count]['hold_for_future']) ? $results[$patient_count]['hold_for_future'] + 1 : 1;
                            break;
                        case 'success':
                            $results[$patient_count]['archived'] = config('reports.archive.success');
                            break;
                        case 'dropout':
                            $results[$patient_count]['archived'] = config('reports.archive.dropout');
                            break;
                        default:
                            break;
                    }
                }
            }
            $patient_count++;
        }

        return $results;
    }

    public function renderResult($results, $filter) {

        $daysInStage = [
            'pending' => [
                'sumOfDays' => 0,
                'numOfDays' => 0,
            ],
            'contact_attempted' => [
                'sumOfDays' => 0,
                'numOfDays' => 0,
            ],
            'reached' => [
                'sumOfDays' => 0,
                'numOfDays' => 0,
            ],
            'appt_completed' => [
                'sumOfDays' => 0,
                'numOfDays' => 0,
            ]
        ];


        $reportMetrics = array(
            'patient_count' => 0,
            'new_patient' => 0,
            'existing_patients' => 0,
            'completed' => 0,
            'success' => 0,
            'dropout' => 0,
            'active_patient' => 0,
            'pending_patient' => 0,
            'repeat_count' => 0,
            'contact_attempted' => 0,
            'reached' => 0,
            'not_reached' => 0,
            'not_reached_attempts' => 0,
            'unable_to_reach' => 0,
            'unable_to_reach_attempts' => 0,
            'hold_for_future' => 0,
            'hold_for_future_attempts' => 0,
            'appointment_scheduled' => 0,
            'not_scheduled' => 0,
            'appointment_completed' => 0,
            'show' => 0,
            'no_show' => 0,
            'cancelled' => 0,
            'reports' => 0,
            'no_reports' => 0,
            'filter_name' => ''
        );

        foreach($results as $result) {

            switch($filter) {
                case 'new_patient':
                    $reportMetrics['filter_name'] = 'New Patients';
                    if($result['patient_type'] != config('reports.patient_type.new'))
                        continue 2;
                    break;
                case 'existing_patients':
                    $reportMetrics['filter_name'] = 'Existing Patients';
                    if($result['patient_type'] != config('reports.patient_type.old'))
                        continue 2;
                    break;
                case 'completed':
                    $reportMetrics['filter_name'] = 'Completed Patients';
                    if(!array_key_exists('archived', $result))
                        continue 2;
                    break;
                case 'success':
                    $reportMetrics['filter_name'] = 'Successful Patients';
                    if(!(array_key_exists('archived', $result) && $result['archived'] == config('reports.archive.success')))
                        continue 2;
                    break;
                case 'dropout':
                    $reportMetrics['filter_name'] = 'Dropout Patients';
                    if(!(array_key_exists('archived', $result) && $result['archived'] == config('reports.archive.dropout')))
                        continue 2;
                    break;
                case 'active_patient':
                    $reportMetrics['filter_name'] = 'Active Patients';
                    if(array_key_exists('archived', $result))
                        continue 2;
                    break;
                default:
            }

            $reportMetrics['patient_count']++;
            $result['patient_type'] == config('reports.patient_type.new') ? $reportMetrics['new_patient']++ : $reportMetrics['existing_patients']++;
            if(array_key_exists('archived', $result)) {
                $reportMetrics['completed']++;
                $result['archived'] == config('reports.archive.success') ? $reportMetrics['success']++ : $reportMetrics['dropout']++;
            } else {
                $reportMetrics['active_patient']++;
            }
            if(array_key_exists('repeat_count', $result))
                $reportMetrics['repeat_count']++;
            if(array_key_exists('reached', $result)) {
                $reportMetrics['reached']++;
                $reportMetrics['contact_attempted']++;
            } else if(array_key_exists('not_reached', $result)) {
                $reportMetrics['not_reached']++;
                $reportMetrics['not_reached_attempts'] += $result['not_reached'];
                $reportMetrics['contact_attempted']++;
                if (array_key_exists('unable_to_reach', $result)) {
                    $reportMetrics['unable_to_reach']++;
                    $reportMetrics['unable_to_reach_attempts'] += $result['unable_to_reach'];
                }
                if(array_key_exists('hold_for_future', $result)) {
                    $reportMetrics['hold_for_future']++;
                    $reportMetrics['hold_for_future_attempts'] += $result['hold_for_future'];
                }
            } else {
                if($result['patient_type'] == config('reports.patient_type.new') || array_key_exists('repeat_count', $result))
                    $reportMetrics['pending_patient']++;
            }
            if(array_key_exists('appointment_scheduled', $result)) {
                $reportMetrics['appointment_scheduled']++;
            }
            if(array_key_exists('appointment_completed', $result)) {
                $reportMetrics['appointment_completed']++;
                $result['appointment_completed'] == config('reports.appointment_completed.show') ? $reportMetrics['show']++ : $reportMetrics['no_show']++;
            }
            if(array_key_exists('reports', $result)) {
                $reportMetrics['reports']++;
            }

            if(array_key_exists('pending_stage_change', $result)) {
                $daysInStage['pending']['sumOfDays'] += $result['pending_stage_change'];
                $daysInStage['pending']['numOfDays']++;
            }
            if(array_key_exists('reached_stage_change', $result)) {
                $daysInStage['contact_attempted']['sumOfDays'] += $result['reached_stage_change'];
                $daysInStage['contact_attempted']['numOfDays']++;
            }
            if(array_key_exists('appt_scheduled_stage_change', $result)) {
                $daysInStage['reached']['sumOfDays'] += $result['appt_scheduled_stage_change'];
                $daysInStage['reached']['numOfDays']++;
            }
            if(array_key_exists('show_stage_change', $result)) {
                $daysInStage['appt_completed']['sumOfDays'] += $result['show_stage_change'];
                $daysInStage['appt_completed']['numOfDays']++;
            }

        }

        $reportMetrics['not_scheduled'] = $reportMetrics['reached'] - $reportMetrics['appointment_scheduled'];
        $reportMetrics['no_reports'] = $reportMetrics['show'] - $reportMetrics['reports'];

        $reportMetrics['contact_attempted_days'] = $daysInStage['pending']['numOfDays'] != 0 ? ceil($daysInStage['pending']['sumOfDays']/$daysInStage['pending']['numOfDays']) : '-';

        $reportMetrics['reached_days'] = $daysInStage['contact_attempted']['numOfDays'] != 0 ? ceil($daysInStage['contact_attempted']['sumOfDays']/$daysInStage['contact_attempted']['numOfDays']) : '-';

        $reportMetrics['appointment_completed_days'] = $daysInStage['reached']['numOfDays'] != 0 ? ceil($daysInStage['reached']['sumOfDays']/$daysInStage['reached']['numOfDays']) : '-';

        $reportMetrics['show_days'] = $daysInStage['appt_completed']['numOfDays'] != 0 ? ceil($daysInStage['appt_completed']['sumOfDays']/$daysInStage['appt_completed']['numOfDays']) : '-';

        return $reportMetrics;
    }


    public function setStartDate($startDate)
    {
        $date = new Datetime($startDate);
        $this->startDate = $date->format('Y-m-d 00:00:00');
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setEndDate($endDate)
    {
        $date = new Datetime($endDate);
        $date->modify("+1 days");
        $this->endDate = $date->format('Y-m-d 00:00:00');
    }

    public function getEndDate()
    {
        return $this->endDate;
    }
}
