<?php

namespace myocuhub\Services;

use Auth;
use DateTime;
use Helper;
use myocuhub\Models\Action;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\Kpi;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Models\ReferralHistory;
use myocuhub\Patient;
use myocuhub\Services\KPI\KPIService;
use myocuhub\User;

class CareConsoleService
{
    private $KPIService;
    private $ActionService;
    private $pageNum;
    private $countChunk;
    /**
     * @param KPIService $KPIService
     */
    public function __construct(KPIService $KPIService, ActionService $ActionService)
    {
        $this->KPIService = $KPIService;
        $this->ActionService = $ActionService;
    }

    /**
     * @param $kpiName
     * @param $networkID
     * @param $stageID
     * @return mixed
     */

    public function getControls($stageID)
    {
        $networkID = Auth::user()->userNetwork->first()->network_id;
        $llKpiGroup = CareconsoleStage::find($stageID)->llKpiGroup;
        $stage = CareconsoleStage::find($stageID);
        $kpis = CareconsoleStage::find($stageID)->kpi;
        $controls = [];
        $i = 0;
        foreach ($llKpiGroup as $group) {
            $controls[$i]['group_name'] = $group->group_name;
            $controls[$i]['group_display_name'] = $group->group_display_name;
            $controls[$i]['type'] = $group->type;
            $controls[$i]['stage_id'] = $stageID;
            $controls[$i]['stage_system_name'] = $stage->name;
            $controls[$i]['stage_display_name'] = $stage->display_name;
            $options = CareconsoleStage::llKpiByGroup($group->group_name, $stageID);
            $j = 0;
            foreach ($options as $option) {
                $controls[$i]['options'][$j]['name'] = $option->name;
                $controls[$i]['options'][$j]['display_name'] = $option->display_name;
                $controls[$i]['options'][$j]['color_indicator'] = $option->color_indicator;
                $controls[$i]['options'][$j]['description'] = $option->description;
                $controls[$i]['options'][$j]['count'] = 0;
                if ($controls[$i]['type'] == 2) {
                    $controls[$i]['options'][$j]['kpi_name'] = '';
                    if (isset($kpis[$j])) {
                        $controls[$i]['options'][$j]['kpi_name'] = $kpis[$j]->name;
                        $count = $this->KPIService->getCount($kpis[$j]->name, $networkID, $stageID);
                        $controls[$i]['options'][$j]['count'] = $count['precise_count'];
                    }
                }
                $j++;
            }
            $i++;
        }
        return $controls;
    }

    /**
     * @param $stageID
     * @return mixed
     */
    public function getActions($stageID)
    {
        $actions = CareconsoleStage::find($stageID)->actions;
        $actionsData = [];
        $i = 0;
        foreach ($actions as $action) {
            if (($action->id == 35 || $action->id == 36 || $action->id == 37) && session('network-id') == 1) {
                continue;
            }
            $actionsData[$i]['id'] = $action->id;
            $actionsData[$i]['stage_id'] = $action->stage_id;
            $actionsData[$i]['name'] = $action->name;
            $actionsData[$i]['display_name'] = $action->display_name;
            $actionsData[$i]['action_results'] = Action::find($action->id)->actionResults;
            $i++;
        }
        return $actionsData;
    }

    /**
     * @param $stageID
     * @param $kpiName
     * @param $sortField
     * @param $sortOrder
     * @return mixed
     */
    public function getPatientListing($stageID, $kpiName = '', $sortField = '', $sortOrder = '', $llimit = -1, $ulimit = -1)
    {
        $user = Auth::user();
        $userID = $user->id;
        $networkID = $user->userNetwork->first()->network_id;
        if ($sortField == '') {
            $sortField = 'days-pending';
        }
        if ($sortOrder == '') {
            $sortOrder = 'SORT_ASC';
        }

        $headerData = [];
        $patientsData = [];
        $listing = [];
        $i = 0;
        $fields = [];

        if ($kpiName !== '' && isset($stageID)) {
            $patients = $this->KPIService->getPatients($kpiName, $networkID, $stageID);
        } elseif (isset($stageID)) {
            $patients = Careconsole::getStagePatients($networkID, $stageID);
        }

        $headers = CareconsoleStage::find($stageID)->patientFields;

        foreach ($headers as $header) {
            $headerData[$i]['display_name'] = $header['display_name'];
            $headerData[$i]['name'] = $header['name'];
            $headerData[$i]['width'] = $header['width'];
            if ($header['name'] == $sortField) {
                $headerData[$i]['sort_order'] = $sortOrder;
            }
            array_push($fields, $header['name']);
            $i++;
        }
        $i = 0;
        foreach ($patients as $patient) {
            $patientsData[$i]['console_id'] = $patient['id'];
            $patientsData[$i]['patient_id'] = $patient['patient_id'];
            $patientsData[$i]['priority'] = $patient['priority'];
            $patientsData[$i]['patient_name'] = $this->getPatientFieldValue($patient, 'full-name');
            $patientsData[$i]['patient_email'] = $this->getPatientFieldValue($patient, 'email');
            $patientsData[$i]['patient_phone'] = $this->getPatientFieldValue($patient, 'phone');
            foreach ($fields as $field) {
                $patientsData[$i][$field] = $this->getPatientFieldValue($patient, $field);
            }
            $i++;
        }

        if ($sortField != '' && in_array($sortField, $fields)) {
            $sortParams = [$sortField => $sortOrder];
            $patientsData = $this->arrayMsort($patientsData, $sortParams);
        }

        if ($ulimit != -1) {
            $patientsData = Careconsole::filterPatientByDaysPendings($llimit, $ulimit, $patientsData);
        }

        $patientsData = $this->paginateResults($patientsData);

        $listing['patients'] = $patientsData;

        $listing['headers'] = $headerData;
        $listing['lastpage'] = $this->countChunk;

        return $listing;
    }

    /**
     * @param $stageID
     * @return mixed
     */
    public function getBucketPatientsListing($stageID, $sortField = '', $sortOrder = '')
    {
        $user = Auth::user();
        $userID = $user->id;
        $networkID = $user->userNetwork->first()->network_id;

        $headers = CareconsoleStage::find($stageID)->patientFields;
        $patients = $this->KPIService->getBucketPatients($networkID, $stageID);

        if ($sortField == '') {
            $sortField = 'full-name';
        }
        if ($sortOrder == '') {
            $sortOrder = 'SORT_ASC';
        }

        $headerData = [];
        $patientsData = [];
        $listing = [];
        $i = 0;
        $fields = [];

        foreach ($headers as $header) {
            $headerData[$i]['display_name'] = $header['display_name'];
            $headerData[$i]['name'] = $header['name'];
            $headerData[$i]['width'] = $header['width'];
            if ($header['name'] == $sortField) {
                $headerData[$i]['sort_order'] = $sortOrder;
            }
            array_push($fields, $header['name']);
            $i++;
        }

        $i = 0;
        foreach ($patients as $patient) {
            $patientsData[$i]['console_id'] = $patient['id'];
            $patientsData[$i]['patient_id'] = $patient['patient_id'];
            $patientsData[$i]['priority'] = $patient['priority'];
            $patientsData[$i]['patient_name'] = $this->getPatientFieldValue($patient, 'full-name');
            $patientsData[$i]['patient_email'] = $this->getPatientFieldValue($patient, 'email');
            $patientsData[$i]['patient_phone'] = $this->getPatientFieldValue($patient, 'phone');
            foreach ($fields as $field) {
                $patientsData[$i][$field] = $this->getPatientFieldValue($patient, $field);
            }
            $i++;
        }
        if ($sortField != '' && in_array($sortField, $fields)) {
            $sortParams = [$sortField => $sortOrder];
            $patientsData = $this->arrayMsort($patientsData, $sortParams);
        }

        $patientsData = $this->paginateResults($patientsData);

        $listing['patients'] = $patientsData;
        $listing['headers'] = $headerData;
        $listing['lastpage'] = $this->countChunk;

        return $listing;
    }

    /**
     * @param $patient
     * @param $field
     * @return mixed
     */
    public function getPatientFieldValue($patient, $field)
    {
        $dateFormat = 'F j Y, g:i a';
        switch ($field) {
            case 'archived-at':
                $date = new \DateTime($patient['archived_date']);
                return $date->format($dateFormat);
                break;
            case 'current-stage':
                return CareconsoleStage::find($patient['stage_id'])->display_name;
                break;
            case 'recall-at':
                $date = new \DateTime($patient['recall_date']);
                return $date->format($dateFormat);
                break;
            case 'first-name':
                return $patient['firstname'];
                break;
            case 'last-name':
                return $patient['lastname'];
                break;
            case 'patient-name':
                return $patient['firstname'].' '.$patient['lastname'];
                break;        
            case 'full-name':
                return $patient['lastname'] . ', ' . $patient['firstname'] . ' ' . $patient['middlename'];
                break;
            case 'print-name':
                return $patient['firstname'] . ' ' . $patient['middlename'] . ' ' . $patient['lastname'];
                break;
            case 'phone':
                $patient = Patient::find($patient['patient_id']);
                if ($patient) {
                    return $patient->getPhone();
                }
                return false;
                break;
            case 'cellphone':
                return $patient['cellphone'] ?: '';
                break;
            case 'homephone':
                return $patient['homephone'] ?: '';
                break;
            case 'workphone':
                return $patient['workphone'] ?: '';
                break;
            case 'request-received':
                $date = new \DateTime($patient['created_at']);
                return $date->format($dateFormat);
                break;
            case 'contact-attempts':
                return ContactHistory::where('console_id', $patient['id'])
                    ->whereNull('archived')
                    ->count();
                break;
            case 'appointment-date':
                $appointment = Appointment::find($patient['appointment_id']);
                $date = new \DateTime($appointment->start_datetime);
                return $date->format($dateFormat);
                break;
            case 'appointment-type':
                $appointment = Appointment::find($patient['appointment_id']);
                return $appointment->appointmenttype;
                break;
            case 'days-pending':
                return date_diff(new \DateTime($patient['stage_updated_at']), new \DateTime(), true)->days;
                break;
            case 'provider-name':
                $appointment = Appointment::find($patient['appointment_id']);
                $provider = User::find($appointment->provider_id);
                if (!$provider) {
                    $provider = '-';
                } else {
                    $provider = $provider->title . ' ' . $provider->lastname . ', ' . $provider->firstname ;
                }  
                return $provider;
                break;
            case 'practice-name':
                $appointment = Appointment::find($patient['appointment_id']);
                $practice = Practice::withTrashed()->find($appointment->practice_id);
                $practiceName = $practice ? $practice->name : '-';
                return $practiceName;
                break;
            case 'location-name':
                $appointment = Appointment::find($patient['appointment_id']);
                $practiceLocation = PracticeLocation::find($appointment->location_id);
                $locationInfo = $practiceLocation ? $practiceLocation->locationname : '-'; 
                return $locationInfo;
                break;                                                                                       
            case 'scheduled-to':
                $appointment = Appointment::find($patient['appointment_id']);
                $provider = User::find($appointment->provider_id);
                if (!$provider) {
                    $provider = '';
                } else {
                    $provider = $provider->title . ' ' . $provider->lastname . ', ' . $provider->firstname;
                    if (trim($provider) != '') {
                        $provider .= ' from ';
                    } else {
                        $provider = trim($provider);
                    }
                }
                $practice = Practice::withTrashed()->find($appointment->practice_id);
                $practiceName = $practice ? $practice->name : '';
                $practiceLocation = PracticeLocation::find($appointment->location_id);
                $locationInfo = $practiceLocation ? ' at ' . $practiceLocation->locationname : '';
                return $provider . $practiceName . $locationInfo;
                break;
            case 'last-scheduled-to':
                $previousProvider = Patient::getPreviousProvider($patient['patient_id']);
                if ($previousProvider['id'] === null) {
                    return '-';
                }
                $lastScheduledTo = '';
                $lastScheduledTo .= $previousProvider['title'] . ' ' . $previousProvider['firstname'] . ' ' . $previousProvider['lastname'];

                if (trim($lastScheduledTo) != '') {
                    $lastScheduledTo .= ' from ';
                } else {
                    $lastScheduledTo = trim($lastScheduledTo);
                }
                
                $lastScheduledTo .= $previousProvider['name'];
                $lastScheduledTo .= $previousProvider['locationname'] ? ' at ' . $previousProvider['locationname'] : '';
                return $lastScheduledTo;
                break;
            case 'last-appointment-date':
                $previousProvider = Patient::getPreviousProvider($patient['patient_id']);
                if ($previousProvider['id'] === null) {
                    return '-';
                }
                $lastAppointmentDate = Helper::formatDate($previousProvider['start_datetime'], config('constants.date_time')) ?: '-';

                return $lastAppointmentDate;
                break;
            case 'email':
                return $patient['email'] ?: '';
                break;
            case 'special-request':
                return $patient['special_request'] ?: '';
                break;
            case 'pcp':
                return $patient['pcp'] ?: '';
                break;
            case 'dob':
                return Helper::formatDate($patient['birthdate'], config('constants.date_format')) ?: '';
                break;
            case 'address':
                $address = '';
                $address = $patient['addressline1'];
                $address = ($address != '') ? $address . ' ' . $patient['addressline2'] : $patient['addressline2'];
                return ($address != '') ? $address : '-';
                break;
            case 'address-line-1':
                return $patient['addressline1'] ?: '';
                break;
            case 'address-line-2':
                return $patient['addressline2'] ?: '';
                break;
            case 'insurance-carrier':
                $insurance = PatientInsurance::where('patient_id', $patient['id'])->first();
                $insuranceCarrier = '';
                if (isset($insurance)) {
                    $insuranceCarrier = $insurance->insurance_carrier;
                }
                return $insuranceCarrier;
                break;
            case 'subscriber-birthdate':
                $insurance = PatientInsurance::where('patient_id', $patient['id'])->first();
                $subscriber_birthdate = '';
                if (isset($insurance)) {
                    $subscriber_birthdate = $insurance->subscriber_birthdate ? Helper::formatDate($insurance->subscriber_birthdate, config('constants.date_format')) : '';
                }
                return $subscriber_birthdate;
                break;
            case 'group-number':
                $insurance = PatientInsurance::where('patient_id', $patient['id'])->first();
                $group_no = '';
                if (isset($insurance)) {
                    $group_no = $insurance->insurance_group_no ?: '';
                }
                return $group_no;
                break;
            case 'subscriber-name':
                $insurance = PatientInsurance::where('patient_id', $patient['id'])->first();
                $subscriber_name = '';
                if (isset($insurance)) {
                    $subscriber_name = $insurance->subscriber_name ?: '';
                }
                return $subscriber_name;
                break;
            case 'subscriber-id':
                $insurance = PatientInsurance::where('patient_id', $patient['id'])->first();
                $subscriber_id = '';
                if (isset($insurance)) {
                    $subscriber_id = $insurance->subscriber_id ?: '';
                }
                return $subscriber_id;
                break;
            case 'relation-to-patient':
                $insurance = PatientInsurance::where('patient_id', $patient['id'])->first();
                $subscriber_relation = '';
                if (isset($insurance)) {
                    $subscriber_relation = $insurance->subscriber_relation ?: '';
                }
                return $subscriber_relation;
                break;
            case 'referral-history':
                $referralHistory = ReferralHistory::find($patient['referral_id']);
                $referredBy = '';
                if (isset($referralHistory->referred_by_provider)) {
                    $referredBy = $referralHistory->referred_by_provider;
                }
                if (isset($referralHistory->referred_by_practice)) {
                    $referredBy = ($referredBy != '') ? $referredBy . ' ' . $referralHistory->referred_by_practice : $referralHistory->referred_by_practice;
                }
                return $referredBy ?: '';
                break;
            case 'referred-by-practice':
                $referralHistory = ReferralHistory::find($patient['referral_id']);
                return (isset($referralHistory) && isset($referralHistory->referred_by_practice) && ($referralHistory->referred_by_practice != '')) ? $referralHistory->referred_by_practice : '';
                break;
            case 'referred-by-provider':
                $referralHistory = ReferralHistory::find($patient['referral_id']);
                return (isset($referralHistory) && isset($referralHistory->referred_by_provider) && ($referralHistory->referred_by_provider != '')) ? $referralHistory->referred_by_provider : '';
                break;
            case 'archive-reason':
                $archiveReason = ContactHistory::where('console_id', $patient->id)
                    ->whereHas('actionResult', function ($q) {
                        $q->where('name', 'patient-declined-services');
                        $q->orwhere('name', 'other-reasons-for-declining');
                        $q->orwhere('name', 'already-seen-by-outside-dr');
                        $q->orwhere('name', 'no-need-to-schedule');
                        $q->orwhere('name', 'no-insurance');
                        $q->orwhere('name', 'closed');
                        $q->orwhere('name', 'incomplete');
                    })
                    ->orderBy('id', 'desc')
                    ->first();
                return $archiveReason ? $archiveReason->actionResult->display_name : '-';
                break;
            case 'archive-note':
                $archiveNote = ContactHistory::where('console_id', $patient->id)
                    ->whereHas('actionResult', function ($q) {
                        $q->where('name', 'patient-declined-services');
                        $q->orwhere('name', 'other-reasons-for-declining');
                        $q->orwhere('name', 'already-seen-by-outside-dr');
                        $q->orwhere('name', 'no-need-to-schedule');
                        $q->orwhere('name', 'no-insurance');
                        $q->orwhere('name', 'closed');
                        $q->orwhere('name', 'incomplete');
                    })
                    ->orderBy('id', 'desc')
                    ->first();
                return $archiveNote ? str_replace("</br>", " ", $archiveNote->notes) : '-';
                break;
            case 'priority-note':
                $priorityNote = ContactHistory::where('console_id', $patient->id)
                    ->where(function ($subquery) {
                        $subquery->whereHas('actionResult', function ($q) {
                                $q->where('name', 'mark-as-priority');
                            })
                            ->orWhereHas('action', function ($q) {
                                $q->where('name', 'mark-as-priority');
                            });
                    })
                    ->orderBy('id', 'desc')
                    ->first();
                return $priorityNote ? str_replace("</br>", " ", $priorityNote->notes) : '-';
                break;
            case 'recall-note':
                $recallNote = ContactHistory::where('console_id', $patient->id)
                    ->where(function ($subquery) {
                        $subquery->whereHas('actionResult', function ($q) {
                                $q->where('name', 'recall-later');
                            })
                            ->orWhereHas('action', function ($q) {
                                $q->where('name', 'recall-later');
                                $q->orWhere('name', 'annual-exam');
                            });
                    })
                    ->orderBy('id', 'desc')
                    ->first();
                return $recallNote ? str_replace("</br>", " ", $recallNote->notes) : '-';
                break;
            case 'contact-activity-date':
                $date = new \DateTime($patient->contact_activity_date);
                return $date->format($dateFormat);
                break;
            case 'stage_updated_date':
                $date = Helper::formatDate($patient['stage_updated_at'], config('constants.date_time_format.date_only')) ?: '-';
                return $date;
                break;
            case 'last_action':
                $last_contact_history = ContactHistory::where('console_id', $patient->id)
                    ->with('action')
                    ->orderBy('id', 'desc')
                    ->first();
                return $last_contact_history ? $last_contact_history->action->display_name : '-';
                break;
            case 'last_action_note':
                $last_contact_history = ContactHistory::where('console_id', $patient->id)
                    ->orderBy('id', 'desc')
                    ->first();
                return $last_contact_history ? strip_tags($last_contact_history->notes) : '-';
                break;
            default:
                return '-';
                break;
        }
    }

    /**
     * @param $array
     * @param $cols
     * @return mixed
     */
    public function arrayMsort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            $isDate = false;

            if (isset($array[0][$col])) {
                $isDate = Helper::validateDate($array[0][$col]);
            }
            foreach ($array as $k => $row) {
                if ($isDate) {
                    $colarr[$col]['_' . $k] = strtotime($row[$col]);
                } else {
                    $colarr[$col]['_' . $k] = strtolower($row[$col]);
                }
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k])) {
                    $ret[$k] = $array[$k];
                }

                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;
    }

    /**
     */

    public function moveRecallPatientsToConsoleAsPending()
    {
        $networkID = Auth::user()->userNetwork->first()->network_id;
        $patients = Careconsole::getRecallPatientsToMove($networkID);
        foreach ($patients as $patient) {
            $console = Careconsole::find($patient['id']);
            $this->ActionService->userAction(34, '-1', null, 'Moved patient to Contact Pending from Recall', '', $console->id, '');
        }
    }

    public function setPage($pageNum)
    {
        $pageNum = (int) $pageNum;
        $pageNum = $pageNum - 1;

        if (!$pageNum || $pageNum < 0) {
            $pageNum = 0;
        }

        $this->pageNum = $pageNum;
    }

    public function getPage()
    {
        $pageNum = $this->pageNum;

        if (!$pageNum || $pageNum < 0) {
            return 0;
        }

        return $this->pageNum;
    }

    public function paginateResults($data)
    {
        $defaultPage = config('constants.default_careconsole_paginate');
        $page = $this->getPage();
        $Chunks = array_chunk($data, $defaultPage);
        $countChunk = sizeof($Chunks);

        $this->countChunk = $countChunk;

        if (isset($Chunks[$page])) {
            return $Chunks[$page];
        }

        return [];
    }
}
