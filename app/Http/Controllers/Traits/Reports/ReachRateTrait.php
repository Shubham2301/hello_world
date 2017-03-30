<?php

namespace myocuhub\Http\Controllers\Traits\Reports;

use Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Models\ContactHistory;
use myocuhub\Services\CareConsoleService;
use myocuhub\Patients;
use myocuhub\User;
use Datetime;
use DateInterval;
use Auth;
use myocuhub\Facades\Helper;

trait ReachRateTrait
{
    protected $startDate;
    protected $endDate;
    private $CareConsoleService;

    public function __construct(CareConsoleService $CareConsoleService)
    {
        $this->CareConsoleService = $CareConsoleService;
    }

    public function generateReport()
    {
        $networkID = Auth::user()->userNetwork->first()->network_id;

        $results = array(array());

        $careconsole_data = Careconsole::getReachRateData($networkID, $this->getStartDate(), $this->getEndDate());

        $patient_count = 0;

        foreach ($careconsole_data as $careconsole) {
            $history = array();
            $history['archived'] = 0;
            $history['unarchived'] = 0;
            $history['move_to_recall'] = 0;
            $history['back_to_console'] = 0;

            $results[$patient_count] = $this->fillPatientDetail($careconsole, 'patient_data');
            $results[$patient_count]['request_received'] = Helper::formatDate($careconsole->created_at, config('constants.date_format'));
            foreach ($careconsole->contactHistory as $contactHistory) {
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
                        if (!isset($results[$patient_count]['not_reached']) && !isset($results[$patient_count]['reached'])) {
                            $results[$patient_count]['pending_stage_change'] = $contactHistory->days_in_current_stage;
                            $date = new DateTime($contactHistory->contact_activity_date);
                            $date = $date->sub(new DateInterval('P'.$contactHistory->days_in_current_stage.'D'));
                            $results[$patient_count]['request_received'] = $date->format('m/d/Y');
                        }
                        $results[$patient_count]['contact_attempts'] = isset($results[$patient_count]['contact_attempts']) ? $results[$patient_count]['contact_attempts'] + 1 : 1;
                        $results[$patient_count]['days_in_contact_status'] = $this->getDefaultPendingDays($contactHistory);
                        break;
                    case 'patient-notes':
                    case 'requested-data':

                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                        $results[$patient_count]['reached'] = isset($results[$patient_count]['reached']) ? $results[$patient_count]['reached'] + 1 : 1;
                        $results[$patient_count]['reached_stage_change'] = $contactHistory->days_in_prev_stage;

                        $existingRelationship = 0;
                        if ($contactHistory->appointments) {
                            $existingRelationship = $contactHistory->appointments->existing_relationship;
                        }

                        if (isset($contactHistory->appointments)) {
                            $results[$patient_count] += $this->fillPatientDetail($contactHistory, 'appointment_data');
                            $results[$patient_count] = array_merge($results[$patient_count], $this->fillPatientDetail($contactHistory, 'appointment_data'));
                        }

                        if (isset($contactHistory->appointments) && (Helper::formatDate($contactHistory->appointments->start_datetime, 'Ymd') >= Helper::formatDate($this->getEndDate(), 'Ymd'))) {
                            $results[$patient_count]['appointment_scheduled'] = ($existingRelationship == 1) ? config('reports.appointment_status.scheduled_appointment_existing_relationship') : config('reports.appointment_status.scheduled_appointment_non_existing_relationship') ;
                        } else {
                            $results[$patient_count]['appointment_scheduled'] = ($existingRelationship == 1) ? config('reports.appointment_status.past_appointment_existing_relationship') : config('reports.appointment_status.past_appointment_non_existing_relationship') ;
                        }

                        $results[$patient_count]['scheduled_on'] = Helper::formatDate($contactHistory->contact_activity_date, config('constants.date_format'));
                        if (isset($results[$patient_count]['appointment_completed']) && $results[$patient_count]['appointment_completed'] == 0) {
                            $results[$patient_count]['already_rescheduled'] = 1;
                        }
                        if (isset($results[$patient_count]['appointment_completed'])) {
                            $results[$patient_count]['show_stage_change'] = $contactHistory->days_in_prev_stage;
                        }
                        break;
                    case 'previously-scheduled':
                        $results[$patient_count]['reached'] = isset($results[$patient_count]['reached']) ? $results[$patient_count]['reached'] + 1 : 1;
                        $results[$patient_count]['reached_stage_change'] = $contactHistory->days_in_prev_stage;

                        $existingRelationship = 0;
                        if ($contactHistory->appointments) {
                            $existingRelationship = $contactHistory->appointments->existing_relationship;
                        }

                        if (isset($contactHistory->appointments)) {
                            $results[$patient_count] += $this->fillPatientDetail($contactHistory, 'appointment_data');
                            $results[$patient_count] = array_merge($results[$patient_count], $this->fillPatientDetail($contactHistory, 'appointment_data'));
                        }

                        if (isset($contactHistory->appointments) && (Helper::formatDate($contactHistory->appointments->start_datetime, 'Ymd') >= Helper::formatDate($this->getEndDate(), 'Ymd'))) {
                            $results[$patient_count]['previously_appointment_scheduled'] = ($existingRelationship == 1) ? config('reports.appointment_status.scheduled_appointment_existing_relationship') : config('reports.appointment_status.scheduled_appointment_non_existing_relationship') ;
                        } else {
                            $results[$patient_count]['previously_appointment_scheduled'] = ($existingRelationship == 1) ? config('reports.appointment_status.past_appointment_existing_relationship') : config('reports.appointment_status.past_appointment_non_existing_relationship') ;
                        }

                        $results[$patient_count]['scheduled_on'] = Helper::formatDate($contactHistory->contact_activity_date, config('constants.date_format'));
                        if (isset($results[$patient_count]['appointment_completed']) && $results[$patient_count]['appointment_completed'] == 0) {
                            $results[$patient_count]['already_rescheduled'] = 1;
                        }
                        if (isset($results[$patient_count]['appointment_completed'])) {
                            $results[$patient_count]['show_stage_change'] = $contactHistory->days_in_prev_stage;
                        }
                        break;                        
                    case 'move-to-console':
                        if (($history['back_to_console'] == $history['move_to_recall'] && $history['back_to_console'] != 0) || $history['back_to_console'] < $history['move_to_recall']) {
                            $patient_count++;
                            $results[$patient_count]['repeat_count'] = 1;
                            $results[$patient_count] +=  array_merge($results[$patient_count], $this->fillPatientDetail($careconsole, 'patient_data'));
                        }
                        $results[$patient_count]['request_received'] = Helper::formatDate($contactHistory->contact_activity_date, config('constants.date_format'));
                        $history['back_to_console']++;
                        break;
                    case 'recall-later':
                        $history['move_to_recall']++;
                        break;
                    case 'unarchive':
                        if (($history['unarchived'] == $history['archived'] && $history['unarchived'] != 0) || $history['unarchived'] < $history['archived']) {
                            $patient_count++;
                            $results[$patient_count]['repeat_count'] = 1;
                            $results[$patient_count] +=  array_merge($results[$patient_count], $this->fillPatientDetail($careconsole, 'patient_data'));
                        }
                        $results[$patient_count]['request_received'] = Helper::formatDate($contactHistory->contact_activity_date, config('constants.date_format'));
                        $history['unarchived']++;
                        break;
                    case 'archive':
                        $history['archived']++;
                        $results[$patient_count]['days_in_stage_before_archive'] = $contactHistory->days_in_current_stage;
                        break;
                    case 'kept-appointment':
                        $results[$patient_count]['appointment_completed'] = config('reports.appointment_completed.show');

                        $results[$patient_count]['days_in_appointment_completed'] = $this->getDefaultPendingDays($contactHistory);
                        $results[$patient_count]['days_in_exam_report'] = $this->getDefaultPendingDays($contactHistory);

                        if (isset($contactHistory->appointments)) {
                            $appt_date = new DateTime($contactHistory->appointments->start_datetime);
                            $action_date = new DateTime($contactHistory->contact_activity_date);
                            $interval = $action_date->diff($appt_date);
                            $date_diff = $interval->format('%a');

                            if ($date_diff >= 0) {
                                $results[$patient_count]['appt_scheduled_stage_change'] = $date_diff;
                            }
                            $results[$patient_count] +=  array_merge($results[$patient_count], $this->fillPatientDetail($contactHistory, 'appointment_data'));
                        }
                        break;
                    case 'no-show':
                    case 'cancelled':
                        $results[$patient_count]['appointment_completed'] = config('reports.appointment_completed.no_show');

                        $results[$patient_count]['days_in_appointment_completed'] = $this->getDefaultPendingDays($contactHistory);

                        if (isset($contactHistory->appointments)) {
                            $results[$patient_count] +=  array_merge($results[$patient_count], $this->fillPatientDetail($contactHistory, 'appointment_data'));
                        }
                        break;
                    case 'data-received':
                        $results[$patient_count]['reports'] = 1;
                        $results[$patient_count]['show_stage_change'] = $contactHistory->days_in_prev_stage;
                        $results[$patient_count]['days_in_exam_report'] = $this->getDefaultPendingDays($contactHistory);
                        if (isset($contactHistory->appointments)) {
                            $results[$patient_count]['scheduled_for'] = Helper::formatDate($contactHistory->appointments->start_datetime, config('constants.date_format'));
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
                }

                if ($contactHistory->actionResult) {
                    switch ($contactHistory->actionResult->name) {
                        case 'mark-as-priority':
                            break;
                        case 'already-seen-by-outside-dr':
                        case 'patient-declined-services':
                        case 'other-reasons-for-declining':
                        case 'no-need-to-schedule':
                        case 'no-insurance':
                            $history['archived']++;
                            $results[$patient_count]['reached'] = isset($results[$patient_count]['reached']) ? $results[$patient_count]['reached'] + 1 : 1;
                            $results[$patient_count] +=  array_merge($results[$patient_count], $this->fillPatientDetail($contactHistory, 'archive_data'));
                            break;
                        case 'unable-to-reach':
                            $results[$patient_count]['not_reached'] = isset($results[$patient_count]['not_reached']) ? $results[$patient_count]['not_reached'] + 1 : 1;
                            $results[$patient_count]['unable_to_reach'] = isset($results[$patient_count]['unable_to_reach']) ? $results[$patient_count]['unable_to_reach'] + 1 : 1;
                            break;
                        case 'left-message-with-3rd-party':
                            $results[$patient_count]['not_reached'] = isset($results[$patient_count]['not_reached']) ? $results[$patient_count]['not_reached'] + 1 : 1;
                            $results[$patient_count]['left_message_with_3rd_party'] = isset($results[$patient_count]['left_message_with_3rd_party']) ? $results[$patient_count]['left_message_with_3rd_party'] + 1 : 1;
                            break;
                        case 'left-voice-mail-message':
                            $results[$patient_count]['not_reached'] = isset($results[$patient_count]['not_reached']) ? $results[$patient_count]['not_reached'] + 1 : 1;
                            $results[$patient_count]['left_voice_mail_message'] = isset($results[$patient_count]['left_voice_mail_message']) ? $results[$patient_count]['left_voice_mail_message'] + 1 : 1;
                            break;
                        case 'hold-for-future':
                            $results[$patient_count]['not_reached'] = isset($results[$patient_count]['not_reached']) ? $results[$patient_count]['not_reached'] + 1 : 1;
                            $results[$patient_count]['hold_for_future'] = isset($results[$patient_count]['hold_for_future']) ? $results[$patient_count]['hold_for_future'] + 1 : 1;
                            break;
                        case 'unaware-of-diagnosis':
                            $results[$patient_count]['not_reached'] = isset($results[$patient_count]['not_reached']) ? $results[$patient_count]['not_reached'] + 1 : 1;
                            $results[$patient_count]['unaware_of_diagnosis'] = isset($results[$patient_count]['unaware_of_diagnosis']) ? $results[$patient_count]['unaware_of_diagnosis'] + 1 : 1;
                            break;  
                        case 'would-not-validate-dob':
                            $results[$patient_count]['not_reached'] = isset($results[$patient_count]['not_reached']) ? $results[$patient_count]['not_reached'] + 1 : 1;
                            $results[$patient_count]['would_not_validate_dob'] = isset($results[$patient_count]['would_not_validate_dob']) ? $results[$patient_count]['would_not_validate_dob'] + 1 : 1;
                            break;                                                       
                        case 'incorrect-data':
                            $results[$patient_count]['not_reached'] = isset($results[$patient_count]['not_reached']) ? $results[$patient_count]['not_reached'] + 1 : 1;
                            $results[$patient_count]['incorrect_data'] = isset($results[$patient_count]['incorrect_data']) ? $results[$patient_count]['incorrect_data'] + 1 : 1;
                            break;
                        case 'closed':
                            $results[$patient_count]['archived'] = config('reports.archive.closed');
                            $results[$patient_count]['archive_reason'] = 'Marked Closed';
                            $results[$patient_count]['archive_date'] = Helper::formatDate($contactHistory->contact_activity_date, config('constants.date_format'));
                            break;
                        case 'incomplete':
                            $results[$patient_count]['archived'] = config('reports.archive.incomplete');
                            $results[$patient_count]['archive_reason'] = 'Marked Incomplete';
                            $results[$patient_count]['archive_date'] = Helper::formatDate($contactHistory->contact_activity_date, config('constants.date_format'));
                            break;
                        default:
                    }
                }
            }
            $patient_count++;
        }

        return $results;
    }

    public function renderResult($results, $filter)
    {
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

        $reportMetrics = array(
            'patient_count' => 0,
            'new_patient' => 0,
            'existing_patients' => 0,
            'completed' => 0,
            'closed' => 0,
            'incomplete' => 0,
            'active_patient' => 0,
            'pending_patient' => 0,
            'repeat_count' => 0,
            'contact_attempted' => 0,
            'reached' => 0,
            'not_reached' => 0,
            'not_reached_attempts' => 0,
            'unable_to_reach' => 0,
            'unable_to_reach_attempts' => 0,
            'left_message_with_3rd_party' => 0,
            'left_message_with_3rd_party_attempts' => 0,
            'left_voice_mail_message' => 0,
            'left_voice_mail_message_attempts' => 0,
            'hold_for_future' => 0,
            'hold_for_future_attempts' => 0,
            'would_not_validate_dob' =>0,
            'would_not_validate_dob_attempts' => 0,
            'unaware_of_diagnosis' => 0,
            'unaware_of_diagnosis_attempts' => 0, 
            'incorrect_data' => 0,
            'incorrect_data_attempts' => 0,
            'appointment_scheduled_existing_relationship' => 0,
            'appointment_scheduled_non_existing_relationship' => 0,
            'past_appointment_existing_relationship' => 0,
            'past_appointment_non_existing_relationship' => 0,
            'previously_appointment_scheduled_existing_relationship' => 0,
            'previously_appointment_scheduled_non_existing_relationship' => 0,
            'previously_past_appointment_existing_relationship' => 0,
            'previously_past_appointment_non_existing_relationship' => 0,            
            'not_scheduled' => 0,
            'no_need_to_schedule' => 0,
            'patient_declined_service' => 0,
            'already_seen_by_outside_dr' => 0,
            'no_insurance' => 0,
            'other_reason_for_declining' => 0,
            'not_scheduled_closed' => 0,
            'not_scheduled_incomplete' => 0,
            'appointment_completed' => 0,
            'show' => 0,
            'no_show' => 0,
            'cancelled' => 0,
            'already_rescheduled' => 0,
            'pending_reschedule' => 0,
            'exam_report' => 0,
            'reports' => 0,
            'no_reports' => 0,
            'config' => config('reports'),
            'referred_by_practice' => array(),
        );

        foreach ($results as $key => $result) {
            if (empty($result)) {
                continue;
            }

            if (array_key_exists('referred_by_practice', $result) && $result['referred_by_practice'] != '-') {
                $referredBy[strtolower(trim($result['referred_by_practice']))] = $result['referred_by_practice'];
            }

            if ($filter != '') {
                if (array_key_exists('referred_by_practice', $result) ? strtolower(trim($result['referred_by_practice'])) != $filter : true) {
                    continue;
                }
            }
            $reportMetrics['patient_count']++;

            if (isset($result['patient_type'])) {
                $result['patient_type'] == config('reports.patient_type.new') ? $reportMetrics['new_patient']++ : $reportMetrics['existing_patients']++;
            }

            if (array_key_exists('archived', $result)) {
                $reportMetrics['completed']++;
                $result['archived'] == config('reports.archive.closed') ? $reportMetrics['closed']++ : $reportMetrics['incomplete']++;
            } else {
                $reportMetrics['active_patient']++;
            }

            if (array_key_exists('repeat_count', $result)) {
                $reportMetrics['repeat_count']++;
            }

            if (array_key_exists('reached', $result)) {
                $reportMetrics['reached']++;
                $reportMetrics['contact_attempted']++;

                if (!(array_key_exists('appointment_scheduled', $result)) && !(array_key_exists('previously_appointment_scheduled', $result))) {
                    $reportMetrics['not_scheduled']++;
                    if (array_key_exists('archive_reason', $result)) {
                        switch ($result['archive_reason']) {
                            case 'Already seen by outside doctor':
                                $reportMetrics['already_seen_by_outside_dr']++;
                                break;
                            case 'Patient declined service':
                                $reportMetrics['patient_declined_service']++;
                                break;
                            case 'Other reasons for declining':
                                $reportMetrics['other_reason_for_declining']++;
                                break;
                            case 'No need to schedule':
                                $reportMetrics['no_need_to_schedule']++;
                                break;
                            case 'No insurance':
                                $reportMetrics['no_insurance']++;
                                break;
                            case 'Marked Closed':
                                $reportMetrics['not_scheduled_closed']++;
                                break;
                            case 'Marked Incomplete':
                                $reportMetrics['not_scheduled_incomplete']++;
                                break;
                        }
                    }
                }
            } elseif (array_key_exists('not_reached', $result)) {
                $reportMetrics['not_reached']++;
                $reportMetrics['not_reached_attempts'] += $result['not_reached'];
                $reportMetrics['contact_attempted']++;
                if (array_key_exists('unable_to_reach', $result)) {
                    $reportMetrics['unable_to_reach']++;
                    $reportMetrics['unable_to_reach_attempts'] += $result['unable_to_reach'];
                }
                if (array_key_exists('left_message_with_3rd_party', $result)) {
                    $reportMetrics['left_message_with_3rd_party']++;
                    $reportMetrics['left_message_with_3rd_party_attempts'] += $result['left_message_with_3rd_party'];
                }
                if (array_key_exists('left_voice_mail_message', $result)) {
                    $reportMetrics['left_voice_mail_message']++;
                    $reportMetrics['left_voice_mail_message_attempts'] += $result['left_voice_mail_message'];
                }
                if (array_key_exists('hold_for_future', $result)) {
                    $reportMetrics['hold_for_future']++;
                    $reportMetrics['hold_for_future_attempts'] += $result['hold_for_future'];
                }
                if (array_key_exists('would_not_validate_dob', $result)) {
                    $reportMetrics['would_not_validate_dob']++;
                    $reportMetrics['would_not_validate_dob_attempts'] += $result['would_not_validate_dob'];
                }
                if (array_key_exists('unaware_of_diagnosis', $result)) {
                    $reportMetrics['unaware_of_diagnosis']++;
                    $reportMetrics['unaware_of_diagnosis_attempts'] += $result['unaware_of_diagnosis'];
                }                                
                if (array_key_exists('incorrect_data', $result)) {
                    $reportMetrics['incorrect_data']++;
                    $reportMetrics['incorrect_data_attempts'] += $result['incorrect_data'];
                }
            } else {
                if ($result['patient_type'] == config('reports.patient_type.new') || array_key_exists('repeat_count', $result)) {
                    $reportMetrics['pending_patient']++;
                }
            }

            if (array_key_exists('appointment_scheduled', $result)) {
                switch ($result['appointment_scheduled']) {
                    case config('reports.appointment_status.scheduled_appointment_existing_relationship'):
                               $reportMetrics['appointment_scheduled_existing_relationship']++;
                               break;
                    case config('reports.appointment_status.scheduled_appointment_non_existing_relationship'):
                               $reportMetrics['appointment_scheduled_non_existing_relationship']++;
                               break;
                    case config('reports.appointment_status.past_appointment_existing_relationship'):
                               $reportMetrics['past_appointment_existing_relationship']++;
                               break;
                    case config('reports.appointment_status.past_appointment_non_existing_relationship'):
                               $reportMetrics['past_appointment_non_existing_relationship']++;
                               break;
                }
            }

            if (array_key_exists('previously_appointment_scheduled', $result)) {
                switch ($result['previously_appointment_scheduled']) {
                    case config('reports.appointment_status.scheduled_appointment_existing_relationship'):
                               $reportMetrics['previously_appointment_scheduled_existing_relationship']++;
                               break;
                    case config('reports.appointment_status.scheduled_appointment_non_existing_relationship'):
                               $reportMetrics['previously_appointment_scheduled_non_existing_relationship']++;
                               break;
                    case config('reports.appointment_status.past_appointment_existing_relationship'):
                               $reportMetrics['previously_past_appointment_existing_relationship']++;
                               break;
                    case config('reports.appointment_status.past_appointment_non_existing_relationship'):
                               $reportMetrics['previously_past_appointment_non_existing_relationship']++;
                               break;
                }
            }            

            if (array_key_exists('appointment_completed', $result)) {
                $reportMetrics['appointment_completed']++;
                $result['appointment_completed'] == config('reports.appointment_completed.show') ? $reportMetrics['show']++ : $reportMetrics['no_show']++;
                if ($result['appointment_completed'] == config('reports.appointment_completed.show') && !(array_key_exists('reports', $result))) {
                    $reportMetrics['no_reports']++;
                    $reportMetrics['exam_report']++;
                }
                if ($result['appointment_completed'] == config('reports.appointment_completed.no_show')) {
                    isset($result['already_rescheduled']) ? $reportMetrics['already_rescheduled']++ : $reportMetrics['pending_reschedule']++ ;
                }
            }

            if (array_key_exists('reports', $result)) {
                $reportMetrics['reports']++;
                $reportMetrics['exam_report']++;
            }

            if (array_key_exists('pending_stage_change', $result)) {
                $daysInStage['pending']['sumOfDays'] += $result['pending_stage_change'];
                $daysInStage['pending']['numOfDays']++;
            }
            if (array_key_exists('reached_stage_change', $result)) {
                $daysInStage['contact_attempted']['sumOfDays'] += $result['reached_stage_change'];
                $daysInStage['contact_attempted']['numOfDays']++;
            }
            if (array_key_exists('appt_scheduled_stage_change', $result)) {
                $daysInStage['reached']['sumOfDays'] += $result['appt_scheduled_stage_change'];
                $daysInStage['reached']['numOfDays']++;
            }
            if (array_key_exists('show_stage_change', $result)) {
                $daysInStage['appt_completed']['sumOfDays'] += $result['show_stage_change'];
                $daysInStage['appt_completed']['numOfDays']++;
            }
        }

        $reportMetrics['contact_attempted_days'] = $daysInStage['pending']['numOfDays'] != 0 ? ceil($daysInStage['pending']['sumOfDays']/$daysInStage['pending']['numOfDays']) : '-';

        $reportMetrics['reached_days'] = $daysInStage['contact_attempted']['numOfDays'] != 0 ? ceil($daysInStage['contact_attempted']['sumOfDays']/$daysInStage['contact_attempted']['numOfDays']) : '-';

        $reportMetrics['appointment_completed_days'] = $daysInStage['reached']['numOfDays'] != 0 ? ceil($daysInStage['reached']['sumOfDays']/$daysInStage['reached']['numOfDays']) : '-';

        $reportMetrics['show_days'] = $daysInStage['appt_completed']['numOfDays'] != 0 ? ceil($daysInStage['appt_completed']['sumOfDays']/$daysInStage['appt_completed']['numOfDays']) : '-';

        $reportMetrics['report_data'] = array_values($results);

        foreach ($referredBy as $key => $referral) {
            if ($referral == null || $referral == '') {
                unset($referredBy[$key]);
            }
        }
        asort($referredBy);
        $referredBy['-'] = 'not referred by';

        $reportMetrics['referred_by_practice'] = $referredBy;

        return $reportMetrics;
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

    public function fillPatientDetail($requestData, $option = null)
    {
        $patientInfo = array();

        switch ($option) {

            case 'patient_data':
                $patientInfo['patient_name'] = $this->CareConsoleService->getPatientFieldValue($requestData->patient, 'print-name');
                $patientInfo['patient_id'] = $requestData->patient->id;
                $patientInfo['pcp_name'] = $this->CareConsoleService->getPatientFieldValue($requestData->patient, 'pcp');
                $patientInfo['dob'] = $this->CareConsoleService->getPatientFieldValue($requestData->patient, 'dob');
                $referredByData = $this->CareConsoleService->getPatientFieldValue($requestData, 'referred-by-practice');
                $patientInfo['referred_by_practice'] = strtolower(trim($referredByData));
                if ($requestData->import_history_count != 0) {
                    $patientInfo['patient_type'] = config('reports.patient_type.new');
                } else {
                    $patientInfo['patient_type'] = config('reports.patient_type.old');
                }
                break;
            case 'appointment_data':
                $patientInfo['scheduled_to_provider'] = isset($requestData->appointments->provider) ? $requestData->appointments->provider->name : '-';
                $patientInfo['scheduled_to_practice'] = isset($requestData->appointments->practice) ? $requestData->appointments->practice->name : '-';
                $patientInfo['scheduled_to_practice_location'] = isset($requestData->appointments->practiceLocation) ? $requestData->appointments->practiceLocation->locationname : '-';
                $patientInfo['scheduled_for'] = Helper::formatDate($requestData->appointments->start_datetime, config('constants.date_format'));
                $patientInfo['appointment_type'] = $requestData->appointments->appointmenttype;
                $patientInfo['existing_relationship'] = $requestData->appointments->existing_relationship;
                break;
            case 'archive_data':
                $patientInfo['archived'] = config('reports.archive.incomplete');
                $patientInfo['archive_date'] = Helper::formatDate($requestData->contact_activity_date, config('constants.date_format'));
                switch ($requestData->actionResult->name) {
                    case 'already-seen-by-outside-dr':
                        $patientInfo['archive_reason'] = 'Already seen by outside doctor';
                        break;
                    case 'patient-declined-services':
                        $patientInfo['archive_reason'] = 'Patient declined service';
                        break;
                    case 'other-reasons-for-declining':
                        $patientInfo['archive_reason'] = 'Other reasons for declining';
                        break;
                    case 'no-need-to-schedule':
                        $patientInfo['archive_reason'] = 'No need to schedule';
                        break;
                    case 'no-insurance':
                        $patientInfo['archive_reason'] = 'No insurance';
                        break;
                }
                break;
            default:
        }
        return $patientInfo;
    }

    public function getDefaultPendingDays($contactHistory)
    {
        $archiveActions = ['recall-later', 'archive', 'annual-exam'];
        $archiveActionResults = ['closed', 'incomplete', 'already-seen-by-outside-dr', 'patient-declined-services', 'other-reasons-for-declining', 'no-need-to-schedule', 'no-insurance'];
        $action_date = new DateTime($contactHistory->contact_activity_date);
        $end_date = new DateTime($this->getEndDate());
        if (in_array($contactHistory->action->name, $archiveActions)) {
            return 0;
        }
        if ($contactHistory->actionResult && in_array($contactHistory->actionResult->name, $archiveActionResults)) {
            return 0;
        }
        $interval = $end_date->diff($action_date);
        return $interval->format('%a');
    }

    public function renderExcelData($results, $filter, $exportField)
    {
        $data = [];
        foreach ($results as $key => $result) {
            if (empty($result)) {
                continue;
            }
            if ($filter != '') {
                if (array_key_exists('referred_by_practice', $result) ? strtolower(trim($result['referred_by_practice'])) != $filter : true) {
                    continue;
                }
            }

            switch ($exportField) {
                case 'not_reached':
                    if (!array_key_exists('reached', $result) && array_key_exists('not_reached', $result)) {
                        if (array_key_exists('unable_to_reach', $result)) {
                            $rowData = [];
                            $rowData['Name'] = $result['patient_name'] ?: '-';
                            $rowData['Request Received'] = $result['request_received'] ?: '-';
                            $rowData['Action'] = 'Unable to reach';
                            $rowData['Attempts'] = $result['unable_to_reach'] ?: '-';
                            $data[] = $rowData;
                        }
                        if (array_key_exists('left_message_with_3rd_party', $result)) {
                            $rowData = [];
                            $rowData['Name'] = $result['patient_name'] ?: '-';
                            $rowData['Request Received'] = $result['request_received'] ?: '-';
                            $rowData['Action'] = 'Left Message with 3rd Party';
                            $rowData['Attempts'] = $result['left_message_with_3rd_party'] ?: '-';
                            $data[] = $rowData;
                        }
                        if (array_key_exists('left_voice_mail_message', $result)) {
                            $rowData = [];
                            $rowData['Name'] = $result['patient_name'] ?: '-';
                            $rowData['Request Received'] = $result['request_received'] ?: '-';
                            $rowData['Action'] = 'Left voice mail message';
                            $rowData['Attempts'] = $result['left_voice_mail_message'] ?: '-';
                            $data[] = $rowData;
                        }
                        if (array_key_exists('hold_for_future', $result)) {
                            $rowData = [];
                            $rowData['Name'] = $result['patient_name'] ?: '-';
                            $rowData['Request Received'] = $result['request_received'] ?: '-';
                            $rowData['Action'] = 'Hold for future';
                            $rowData['Attempts'] = $result['hold_for_future'] ?: '-';
                            $data[] = $rowData;
                        }
                        if (array_key_exists('would_not_validate_dob', $result)) {
                            $rowData = [];
                            $rowData['Name'] = $result['patient_name'] ?: '-';
                            $rowData['Request Received'] = $result['request_received'] ?: '-';
                            $rowData['Action'] = 'Would not validate DOB';
                            $rowData['Attempts'] = $result['would_not_validate_dob'] ?: '-';
                            $data[] = $rowData;
                        }
                        if (array_key_exists('unaware_of_diagnosis', $result)) {
                            $rowData = [];
                            $rowData['Name'] = $result['patient_name'] ?: '-';
                            $rowData['Request Received'] = $result['request_received'] ?: '-';
                            $rowData['Action'] = 'Unaware of diagnosis';
                            $rowData['Attempts'] = $result['unaware_of_diagnosis'] ?: '-';
                            $data[] = $rowData;
                        }                                                
                        if (array_key_exists('incorrect_data', $result)) {
                            $rowData = [];
                            $rowData['Name'] = $result['patient_name'] ?: '-';
                            $rowData['Request Received'] = $result['request_received'] ?: '-';
                            $rowData['Action'] = 'Incorrect Data';
                            $rowData['Attempts'] = $result['incorrect_data'] ?: '-';
                            $data[] = $rowData;
                        }
                    }
                    break;
                case 'not_scheduled':
                        if (array_key_exists('reached', $result) && !(array_key_exists('appointment_scheduled', $result)) && !(array_key_exists('previously_appointment_scheduled', $result))) {
                            $rowData = [];
                            $rowData['Name'] = $result['patient_name'] ?: '-';
                            $rowData['Request Received'] = $result['request_received'] ?: '-';
                            $rowData['Action'] = $result['archive_reason'] ?: '-';
                            $data[] = $rowData;
                        }
                    break;
                case 'no_show':
                        if (array_key_exists('appointment_completed', $result)) {
                            if ($result['appointment_completed'] != config('reports.appointment_completed.show')) {
                                $rowData = [];
                                $rowData['Name'] = $result['patient_name'] ?: '-';
                                $rowData['Scheduled to practice'] = $result['scheduled_to_practice'] ?: '-';
                                $rowData['Scheduled to practice location'] = $result['scheduled_to_practice_location'] ?: '-';
                                $rowData['Scheduled to provider'] = $result['scheduled_to_provider'] ?: '-';
                                $rowData['Scheduled for'] = $result['scheduled_for'] ?: '-';
                                $rowData['Appointment type'] = $result['appointment_type'] ?: '-';

                                $data[] = $rowData;
                            }
                        }
                    break;
                case 'no_reports':
                        if (array_key_exists('appointment_completed', $result)) {
                            if ($result['appointment_completed'] == config('reports.appointment_completed.show') && !(array_key_exists('reports', $result))) {
                                $rowData = [];
                                $rowData['Name'] = $result['patient_name'] ?: '-';
                                $rowData['PCP'] = $result['pcp_name'] ?: '-';
                                $rowData['Scheduled for'] = $result['scheduled_for'] ?: '-';
                                $rowData['Days Pending'] = (isset($result['days_in_stage_before_archive']) && $result['days_in_stage_before_archive'] >= 0) ? $result['days_in_stage_before_archive'] : $result['days_in_exam_report'];
                                $data[] = $rowData;
                            }
                        }
                    break;
                default:
            }
        }
        return $data;
    }
}
