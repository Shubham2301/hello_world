<?php

namespace myocuhub\Http\Controllers\Traits\Reports;

use Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Models\ContactHistory;
use myocuhub\Patients;
use myocuhub\User;
use Datetime;
use DateInterval;
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
            $history['move_to_recall'] = 0;
            $history['back_to_console'] = 0;

            foreach ($careconsole->contactHistory as $contactHistory) {

                $results[$patient_count]['patient_name'] = $careconsole->patient->getName();
                $results[$patient_count]['patient_id'] = $careconsole->patient->id;
                $results[$patient_count]['pcp_name'] = $careconsole->patient->pcp;
                $results[$patient_count]['referred_by_practice'] = $careconsole->referralHistory->referred_by_practice;

                if($careconsole->import_history_count != 0) {
                    $results[$patient_count]['patient_type'] = config('reports.patient_type.new');
                }
                else {
                    $results[$patient_count]['patient_type'] = config('reports.patient_type.old');
                }


                switch ($contactHistory->action->name) {
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
                        if(!isset($results[$patient_count]['not_reached']) && !isset($results[$patient_count]['reached'])) {
                            $results[$patient_count]['pending_stage_change'] = $contactHistory->days_in_current_stage;
                            $date = new DateTime($contactHistory->contact_activity_date);
                            $date = $date->sub(new DateInterval('P'.$contactHistory->days_in_current_stage.'D'));
                            $results[$patient_count]['request_received'] = $date->format('Y-m-d');
                        }
                        $results[$patient_count]['contact_attempts'] = isset($results[$patient_count]['contact_attempts']) ? $results[$patient_count]['contact_attempts'] + 1 : 1;
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
                        if(isset($contactHistory->appointments)) {
                            $scheduled_for = new DateTime($contactHistory->appointments->start_datetime);
                            $results[$patient_count]['scheduled_to_practice'] = $contactHistory->appointments->provider->name;
                            $results[$patient_count]['scheduled_to_provider'] = $contactHistory->appointments->practice->name;
                            $results[$patient_count]['scheduled_for'] = $scheduled_for->format('Y-m-d');
                            $results[$patient_count]['appointment_type'] = $contactHistory->appointments->appointmenttype;
                        }
                        $scheduled_on = new DateTime($contactHistory->contact_activity_date);
                        $results[$patient_count]['scheduled_on'] = $scheduled_on->format('Y-m-d');
                        break;
                    case 'move-to-console':
                        if(($history['back_to_console'] == $history['move_to_recall'] && $history['back_to_console'] != 0) || $history['back_to_console'] < $history['move_to_recall']) {
                            $patient_count++;
                            $results[$patient_count]['repeat_count'] = 1;
                            $results[$patient_count]['patient_type'] = $results[$patient_count - 1]['patient_type'];
                            $results[$patient_count]['patient_name'] = $careconsole->patient->firstname.' '.$careconsole->patient->lastname;
                            $results[$patient_count]['patient_id'] = $careconsole->patient->id;

                            if($careconsole->import_history_count != 0) {
                                $results[$patient_count]['patient_type'] = config('reports.patient_type.new');
                            }
                            else {
                                $results[$patient_count]['patient_type'] = config('reports.patient_type.old');
                            }

                        }
                        $history['back_to_console']++;
                        break;
                    case 'recall-later':
                        $history['move_to_recall']++;
                        break;
                    case 'unarchive':
                        if(($history['unarchived'] == $history['archived'] && $history['unarchived'] != 0) || $history['unarchived'] < $history['archived']) {
                            $patient_count++;
                            $results[$patient_count]['repeat_count'] = 1;
                            $results[$patient_count]['patient_type'] = $results[$patient_count - 1]['patient_type'];
                            $results[$patient_count]['patient_name'] = $careconsole->patient->getName();
                            $results[$patient_count]['patient_id'] = $careconsole->patient->id;

                            if($careconsole->import_history_count != 0) {
                                $results[$patient_count]['patient_type'] = config('reports.patient_type.new');
                            }
                            else {
                                $results[$patient_count]['patient_type'] = config('reports.patient_type.old');
                            }

                        }
                        $history['unarchived']++;
                        break;
                    case 'archive':
                        $history['archived']++;
                        $results[$patient_count]['days_in_stage_before_archive'] = $contactHistory->days_in_current_stage;
                        break;
                    case 'kept-appointment':
                        $results[$patient_count]['appointment_completed'] = config('reports.appointment_completed.show');
                        if(isset($contactHistory->appointments)) {
                            $appt_date = new DateTime($contactHistory->appointments->start_datetime);
                            $action_date = new DateTime($contactHistory->contact_activity_date);
                            $interval = $action_date->diff($appt_date);
                            $date_diff = $interval->format('%a');

                            if ($date_diff >= 0) {
                                $results[$patient_count]['appt_scheduled_stage_change'] = $date_diff;
                            }
                            $results[$patient_count]['scheduled_to_practice'] = $contactHistory->appointments->provider->name;
                            $results[$patient_count]['scheduled_to_provider'] = $contactHistory->appointments->practice->name;

                            $results[$patient_count]['scheduled_for'] = $appt_date->format('Y-m-d');
                            $results[$patient_count]['appointment_type'] = $contactHistory->appointments->appointmenttype;
                        }
                        break;
                    case 'no-show':
                        $results[$patient_count]['appointment_completed'] = config('reports.appointment_completed.no_show');
                        if(isset($contactHistory->appointments)) {
                            $scheduled_for = new DateTime($contactHistory->appointments->start_datetime);
                            $results[$patient_count]['scheduled_to_practice'] = $contactHistory->appointments->provider->name;
                            $results[$patient_count]['scheduled_to_provider'] = $contactHistory->appointments->practice->name;
                            $results[$patient_count]['scheduled_for'] = $scheduled_for->format('Y-m-d');
                            $results[$patient_count]['appointment_type'] = $contactHistory->appointments->appointmenttype;
                        }
                        break;
                    case 'cancelled':
                        $results[$patient_count]['appointment_completed'] = config('reports.appointment_completed.no_show');
                        if(isset($contactHistory->appointments)) {
                            $scheduled_for = new DateTime($contactHistory->appointments->start_datetime);
                            $results[$patient_count]['scheduled_to_practice'] = $contactHistory->appointments->provider->name;
                            $results[$patient_count]['scheduled_to_provider'] = $contactHistory->appointments->practice->name;
                            $results[$patient_count]['scheduled_for'] = $scheduled_for->format('Y-m-d');
                            $results[$patient_count]['appointment_type'] = $contactHistory->appointments->appointmenttype;
                        }
                        break;
                    case 'data-received':
                        $results[$patient_count]['reports'] = 1;
                        $results[$patient_count]['show_stage_change'] = $contactHistory->days_in_prev_stage;
                        if(isset($contactHistory->appointments)) {
                            $scheduled_for = new DateTime($contactHistory->appointments->start_datetime);
                            $results[$patient_count]['scheduled_for'] = $scheduled_for->format('Y-m-d');
                        }
                        break;
                    case 'mark-as-priority':
                        break;
                    case 'remove-priority':
                        break;
                    case 'annual-exam':
                        $history['move_to_recall']++;
                        break;
                    case 'refer-to-specialist':
                    case 'highrisk-contact-pcp':
                    default:
                        break;
                }

                if($contactHistory->actionResult) {
                    switch ($contactHistory->actionResult->name) {
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

        $referredBy = array();
        $i = 0;

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
            'exam_report' => 0,
            'reports' => 0,
            'no_reports' => 0,
            'config' => config('reports'),
            'referred_by_practice' => array(),
        );

        foreach($results as $key => $result) {

            if(array_key_exists('referred_by_practice', $result)) {
                $referredBy[$i] = $result['referred_by_practice'];
                $i++;
            }

            if($filter != '') {
                if(array_key_exists('referred_by_practice', $result) ? $result['referred_by_practice'] != $filter : true) {
                    continue;
                }
            }
            $reportMetrics['patient_count']++;
            $result['patient_type'] == config('reports.patient_type.new') ? $reportMetrics['new_patient']++ : $reportMetrics['existing_patients']++;
            if(array_key_exists('archived', $result)) {
                $reportMetrics['completed']++;
                $result['archived'] == config('reports.archive.success') ? $reportMetrics['success']++ : $reportMetrics['dropout']++;
            } else {
                $reportMetrics['active_patient']++;
            }

            if(array_key_exists('repeat_count', $result)) {
                $reportMetrics['repeat_count']++;
            }

            if(array_key_exists('reached', $result)) {
                $reportMetrics['reached']++;
                $reportMetrics['contact_attempted']++;

                if(!(array_key_exists('appointment_scheduled', $result))) {
                    $reportMetrics['not_scheduled']++;
                }
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
                if($result['patient_type'] == config('reports.patient_type.new') || array_key_exists('repeat_count', $result)) {
                    $reportMetrics['pending_patient']++;
                }
            }

            if(array_key_exists('appointment_scheduled', $result)) {
                $reportMetrics['appointment_scheduled']++;
            }

            if(array_key_exists('appointment_completed', $result)) {
                $reportMetrics['appointment_completed']++;
                $result['appointment_completed'] == config('reports.appointment_completed.show') ? $reportMetrics['show']++ : $reportMetrics['no_show']++;
                if($result['appointment_completed'] == config('reports.appointment_completed.show') && !(array_key_exists('reports', $result))) {
                    $reportMetrics['no_reports']++;
                    $reportMetrics['exam_report']++;
                }

            }

            if(array_key_exists('reports', $result)) {
                $reportMetrics['reports']++;
                $reportMetrics['exam_report']++;
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

        $reportMetrics['contact_attempted_days'] = $daysInStage['pending']['numOfDays'] != 0 ? ceil($daysInStage['pending']['sumOfDays']/$daysInStage['pending']['numOfDays']) : '-';

        $reportMetrics['reached_days'] = $daysInStage['contact_attempted']['numOfDays'] != 0 ? ceil($daysInStage['contact_attempted']['sumOfDays']/$daysInStage['contact_attempted']['numOfDays']) : '-';

        $reportMetrics['appointment_completed_days'] = $daysInStage['reached']['numOfDays'] != 0 ? ceil($daysInStage['reached']['sumOfDays']/$daysInStage['reached']['numOfDays']) : '-';

        $reportMetrics['show_days'] = $daysInStage['appt_completed']['numOfDays'] != 0 ? ceil($daysInStage['appt_completed']['sumOfDays']/$daysInStage['appt_completed']['numOfDays']) : '-';

        $reportMetrics['report_data'] = array_values($results);

        foreach($referredBy as $key => $referral) {
            if($referral == null || $referral == '') {
                unset($referredBy[$key]);
            }
        }

        $countReferred = array_count_values($referredBy);

        $reportMetrics['referred_by_practice'] = $countReferred;

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
