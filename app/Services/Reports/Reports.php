<?php

namespace myocuhub\Services\Reports;

use Datetime;
use Illuminate\Support\Facades\DB;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;

class Reports
{

    protected $startDate;

    public function setStartDate($startDate)
    {
        $date = new Datetime($startDate);
        $this->startDate = $date->format('Y-m-d 00:00:00');
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    protected $endDate;

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

    protected $filters;

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function __construct()
    {

    }

    public function getTotalReferred()
    {

        return Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->where('import_history.network_id', session('network-id'))
            ->count();
    }

    public function getPendingToBeCalled()
    {

        return Careconsole::where('stage_id', 1)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->join('contact_history', 'careconsole.id', '=', 'contact_history.console_id', 'left outer')
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->where('import_history.network_id', session('network-id'))
            ->where(function ($query) {
                $query->whereNull('console_id')
                    ->orWhere('archived', 1);
            })
            ->groupBy('patient_id')
            ->get()->count();
    }

    public function getContactStatistics()
    {

        $contact['phone']['count'] = 0;

        /**
         * Contacted by phone
         */
        $actionId = 1;
        $contactHistory = ContactHistory::where('action_id', $actionId)
            ->where('contact_history.created_at', '>=', $this->getStartDate())
            ->where('contact_history.created_at', '<=', $this->getEndDate())
            ->leftjoin('careconsole', 'contact_history.console_id', '=', 'careconsole.id')
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->count();

        foreach ($contactHistory as $contact) {

        }
        return;
    }

    public function getReferredTo()
    {

        $history = Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->whereNotNull('careconsole.referral_id')
            ->leftjoin('referral_history', 'careconsole.referral_id', '=', 'referral_history.id')
            ->whereNotNull('referral_history.referred_to_practice_id')
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->leftjoin('practices', 'referral_history.referred_to_practice_id', '=', 'practices.id')
            ->get(['practices.name']);

        $practices = [];

        foreach ($history as $key) {
            $practices[] = $key['name'];
        }
        $result['total'] = sizeof($practices);
        $result['practices'] = array_count_values($practices);

        return $result;
    }

    public function getAppointmentStatus()
    {
        $result = [];
        $result['scheduled_seen'][0] = Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->whereNotNull('careconsole.archived_date')
            ->where(function ($query) {
                $query->where('stage_id', 4)
                    ->orWhere('stage_id', 5);
            })
            ->count();
        $result['scheduled_seen'][1] = 'Seen by IEG doctor';

        $result['scheduled_not_seen'][0] = Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->where('stage_id', 2)
            ->count();
        $result['scheduled_not_seen'][1] = 'Scheduled but not seen yet';

        $result['appointment_not_needed'][0] = Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->leftjoin('contact_history', 'careconsole.id', '=', 'contact_history.console_id')
            ->where(function ($query) {
                $query->where('action_result_id', 16)
                    ->orWhere('action_result_id', 15);
            })
            ->count();
        $result['appointment_not_needed'][1] = 'Appointment not needed';

        $result['appointment_declined'][0] = Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->leftjoin('contact_history', 'careconsole.id', '=', 'contact_history.console_id')
            ->where(function ($query) {
                $query->where('action_result_id', 10)
                    ->orWhere('action_result_id', 9);
            })
            ->count();
        $result['appointment_declined'][1] = 'Declined Appointment';

        $result['patients_ran_through'][0] = Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->where('stage_id', 5)
            ->whereNotNull('careconsole.archived_date')
            ->count();
        $result['patients_ran_through'][1] = 'Established patient ran through';

        return $result;
    }
    public function getReferredBy()
    {

        $history = Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->leftjoin('referral_history', 'careconsole.referral_id', '=', 'referral_history.id')
            ->whereNotNull('referral_history.referred_by_practice')
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->get(['referral_history.referred_by_practice']);

        $practices = [];

        foreach ($history as $key) {
            $practices[] = ($key['referred_by_practice'] == '') ? 'Other' : $key['referred_by_practice'];
        }

        $result['total'] = sizeof($practices);
        $result['practices'] = array_count_values($practices);

        if (isset($result['practices']['Other'])) {
            $other = $result['practices']['Other'];
            unset($result['practices']['Other']);
            $result['practices']['Other'] = $other;
        }

        return $result;
    }

    public function initStatusOfPatients()
    {
        $statuses = [];

        $statuses['pending_contact'] = 0;
        $statuses['contact_attempted'] = 0;
        $statuses['appointment_scheduled'] = 0;
        $statuses['cancelled'] = 0;
        $statuses['no_show'] = 0;
        $statuses['exam_report'] = 0;
        $statuses['finalization'] = 0;

        return $statuses;
    }

    public function getAgeBreakdown($age)
    {

        if ($age >= 65) {
            return 'category5';
        } else if ($age >= 55 && $age <= 64) {
            return 'category4';
        } else if ($age >= 45 && $age <= 54) {
            return 'category3';
        } else if ($age >= 35 && $age <= 44) {
            return 'category2';
        } else if ($age < 35) {
            return 'category1';
        }

    }

    public function initAgeBreakdown()
    {

        $ages = [];
        $result = [];

        $ages[] = ['name' => '<35', 'category' => 'category1'];
        $ages[] = ['name' => '35-44', 'category' => 'category2'];
        $ages[] = ['name' => '45-54', 'category' => 'category3'];
        $ages[] = ['name' => '55-64', 'category' => 'category4'];
        $ages[] = ['name' => '65>', 'category' => 'category5'];

        foreach ($ages as $age) {
            $result[$age['category']]['count'] = 0;
            $result[$age['category']]['name'] = $age['name'];
        }

        return $result;
    }

    public function getReportingData($filters)
    {

        $query = $this->buildReportsQuery($filters);
        $results = $this->execReportsQuery($query);

        $networkData = [];

        if (sizeof($results) == 0) {
            return json_encode($networkData);
        }

        $age = $this->initAgeBreakdown();
        $statusOfPatients = $this->initStatusOfPatients();
        $referredToPractice = [];
        $referredToPracticeUser = [];
        $referredToPracticeName = [];
        $referredToPracticeUserName = [];
        $appointmentType = [];
        $referredByDoctor = [];
        $referredByHospital = [];
        $insuranceTypes = [];
        $severities = [];
        $diseases = [];

        $gender = [];
        $gender['male'] = 0;
        $gender['female'] = 0;

        foreach ($results as $result) {

            switch ($result->stage_id) {
                case 1:
                    if ($result->contact_attempts == 0 || $result->contact_attempts == null) {
                        $statusOfPatients['pending_contact']++;
                    } elseif ($result->contact_attempts > 0) {
                        $statusOfPatients['contact_attempted']++;
                    }
                    break;
                case 2:
                    $statusOfPatients['appointment_scheduled']++;
                    break;
                case 3:
                    if ($result->appointment_status == 8) {
                        $statusOfPatients['no_show']++;
                    } elseif ($result->appointment_status == 7) {
                        $statusOfPatients['cancelled']++;
                    }
                    break;
                case 4:
                    $statusOfPatients['exam_report']++;
                    break;
                case 5:
                    $statusOfPatients['finalization']++;
                    break;
                default:
                    break;
            }

            if ($result->referred_to_practice_id != null) {
                $referredToPractice[] = $result->referred_to_practice_id;
                $referredToPracticeName[$result->referred_to_practice_id] = $result->referred_to_practice;
            }
            if ($result->referred_to_provider_id != null) {
                $referredToPracticeUser[] = $result->referred_to_provider_id;
                $referredToPracticeUserName[$result->referred_to_provider_id] = $result->referred_to_provider;
            }

            if ($result->gender == 'Male' || $result->gender == 'M') {
                $gender['male']++;
            } else if ($result->gender == 'Female' || $result->gender == 'F') {
                $gender['female']++;
            }

            $category = $this->getAgeBreakdown($result->patient_age);
            $age[$category]['count']++;

            if ($result->appointmenttype != '' && $result->appointmenttype != null) {
                $appointmentType[] = $result->appointmenttype;
            }

            if ($result->disease_type == null or $result->disease_type == '') {
                $result->disease_type = 'NA';
            }

            if ($result->severity == null or $result->severity == '') {
                $result->severity = 'NA';
            }

            $severities[$result->disease_type][] = $result->severity;
            $diseases[] = $result->disease_type;

            if ($result->referred_by_provider != '' && $result->referred_by_provider != null) {
                $referredByDoctor[] = $result->referred_by_provider;
            }

            if ($result->referred_by_practice != '' && $result->referred_by_practice != null) {
                $referredByHospital[] = $result->referred_by_practice;
            }

            if ($result->insurance_carrier != '' && $result->insurance_carrier != null) {
                $insuranceTypes[] = $result->insurance_carrier;
            }

        }

        if ($filters['referred_to']['type'] == 'practice_user' || $filters['referred_to']['type'] == 'practice') {
            $networkData['referred_to'] = $this->formatReferredTo('practice_user', $referredToPracticeUser, $referredToPracticeUserName);
        } else if ($filters['referred_to']['type'] == 'none') {
            $networkData['referred_to'] = $this->formatReferredTo('practice', $referredToPractice, $referredToPracticeName);
        }
        if ($filters['incomming_referrals']['referred_by']['type'] == 'practice_user' || $filters['incomming_referrals']['referred_by']['type'] == 'practice') {
            $networkData['referred_by'] = $this->formatReferredBy('practice_user', $referredByDoctor);
        } else if ($filters['incomming_referrals']['referred_by']['type'] == 'none') {
            $networkData['referred_by'] = $this->formatReferredBy('practice', $referredByHospital);
        }

        $networkData['status_of_patients'] = $this->formatStatusOfPatients($statusOfPatients);
        $networkData['appointment_type'] = $this->formatAppointmentType($appointmentType);
        $networkData['disease_type'] = $this->formatDiseaseType($severities, $diseases);
        $networkData['age_demographics'] = $age;
        $networkData['insurance_demographics'] = $this->formatInsuranceType($insuranceTypes);
        $networkData['gender_demographics']['male'] = round($gender['male'] * 100 / sizeof($results), 2);
        $networkData['gender_demographics']['female'] = round($gender['female'] * 100 / sizeof($results), 2);
        $networkData['appointment_status'] = $this->getAppointmentStatus();

        return json_encode($networkData);
    }

    public function formatReferredBy($type, $referredBy)
    {

        $result = array();
        $result['type'] = $type;
        $countPractice = array_count_values($referredBy);
        $referredBy = array_unique($referredBy);
        $i = 0;
        $total = 0;
        foreach ($referredBy as $referred) {
            if ($referred == '') {
                continue;
            }

            $result['data'][$i]['name'] = $referred;
            $result['data'][$i]['count'] = $countPractice[$referred];
            $total += $countPractice[$referred];
            $i++;
        }
        $result['total'] = $total;
        return $result;

    }

    public function formatAppointmentType($appointmentTypes)
    {

        $result = array();
        $countTypes = array_count_values($appointmentTypes);
        $appointmentTypes = array_unique($appointmentTypes);
        $i = 0;
        $total = 0;
        foreach ($appointmentTypes as $type) {
            if ($type == '') {
                continue;
            }

            $result[$i]['name'] = $type;
            $result[$i]['count'] = $countTypes[$type];
            $total += $countTypes[$type];
            $i++;
        }
        return $result;
    }

    public function formatDiseaseType($severities, $diseases)
    {

        $result = array();
        $diseases = array_unique($diseases);
        $i = 0;
        $temp = array();
        foreach ($diseases as $disease) {
            $result[$i]['name'] = $disease;

            $countSeverities = array_count_values($severities[$disease]);
            $temp = array_unique($severities[$disease]);
            $j = 0;
            foreach ($temp as $severity) {
                $result[$i]['severity'][$j]['type'] = $severity;
                $result[$i]['severity'][$j]['count'] = $countSeverities[$severity];
                $j++;
            }
            $i++;
        }

        return $result;
    }

    public function formatStatusOfPatients($resultArray)
    {
        $result = array();

        $total = array_sum($resultArray);

        $statuses[] = ['id' => 'pending_contact', 'name' => 'Pending Contact'];
        $statuses[] = ['id' => 'contact_attempted', 'name' => 'Contact Attempted'];
        $statuses[] = ['id' => 'appointment_scheduled', 'name' => 'Appointment Scheduled '];
        $statuses[] = ['id' => 'cancelled', 'name' => 'Cancelled'];
        $statuses[] = ['id' => 'no_show', 'name' => 'No Show'];
        $statuses[] = ['id' => 'exam_report', 'name' => 'Exam Report'];
        $statuses[] = ['id' => 'finalization', 'name' => 'Finalization'];

        $i = 0;

        foreach ($statuses as $status) {
            $result[$i]['id'] = $status['id'];
            $result[$i]['name'] = $status['name'];
            $result[$i]['count'] = $resultArray[$status['id']];
            $result[$i++]['percent'] = round(($resultArray[$status['id']] * 100) / $total, 2);
        }

        return $result;
    }

    public function formatInsuranceType($insuranceTypes)
    {

        $result = array();
        $countTypes = array_count_values($insuranceTypes);
        $insuranceTypes = array_unique($insuranceTypes);
        $i = 0;
        $total = 0;
        foreach ($insuranceTypes as $type) {
            if ($type == '') {
                continue;
            }

            $result[$i]['name'] = $type;
            $result[$i]['count'] = $countTypes[$type];
            $total += $countTypes[$type];
            $i++;
        }
        return $result;

    }

    public function formatReferredTo($type, $referredTo, $referredToName)
    {

        $result = array();
        $result['type'] = $type;
        $countPractice = array_count_values($referredTo);
        $referredTo = array_unique($referredTo);
        $i = 0;
        $total = 0;
        foreach ($referredTo as $referred) {
            if ($referred == 0) {
                continue;
            }

            $result['data'][$i]['id'] = $referred;
            $result['data'][$i]['name'] = $referredToName[$referred];
            $result['data'][$i]['count'] = $countPractice[$referred];
            $total += $countPractice[$referred];
            $i++;
        }
        $result['total'] = $total;
        return $result;
    }

    public function buildReportsQuery($filters)
    {
        $queryFilters = $this->buildQueryFilters($filters);
        $query = "Select
                    `careconsole`.`created_at` as careconsole_created_at,
                    `careconsole`.`stage_id`,
                    `careconsole`.`stage_updated_at`,
                    `careconsole`.`archived_date`,
                    `careconsole`.`recall_date`,
                    `appointments`.`start_datetime` as appointment_starttime,
                    `appointments`.`appointment_status`,
                    `appointments`.`appointmenttype`,
                    `referral_history`.`referred_by_practice`,
                    `referral_history`.`referred_by_provider`,
                    `referral_history`.`disease_type`,
                    `referral_history`.`severity`,
                    `patients`.`gender`,
                    `patient_insurance`.`insurance_carrier`,
                    TIMESTAMPDIFF(YEAR, `patients`.`birthdate`, CURDATE()) as patient_age,
                    `practices`.`name` as `referred_to_practice`,
                    `practices`.`id` as `referred_to_practice_id`,
                    `contact_attempts`.`count` as contact_attempts,
                    `users`.`name` as `referred_to_provider`,
                    `users`.`id` as `referred_to_provider_id`
                    from `careconsole`
                    left join `import_history` on `careconsole`.`import_id` = `import_history`.`id`
                    left join `appointments` on `careconsole`.`appointment_id` = `appointments`.`id`
                    left join `referral_history` on `careconsole`.`referral_id` = `referral_history`.`id`
                    left join `patients` on `careconsole`.`patient_id` = `patients`.`id`
                    left join `practices` on `appointments`.`practice_id` = `practices`.`id`
                    left join `users` on `appointments`.`provider_id` = `users`.`id`
                    left join (select console_id, archived, COUNT(*) as count from contact_history where archived is null group by console_id order by count desc) as `contact_attempts` on `contact_attempts`.`console_id` = `careconsole`.`id`
                    left join `patient_insurance` on `patient_insurance`.`patient_id` = `careconsole`.`patient_id`
                    $queryFilters";

        return $query;
    }

    public function buildQueryFilters($filters)
    {

        $networkID = session('network-id');
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();

        $queryFilters = " where `import_history`.`network_id` = $networkID ";

        if ($filters['type'] == 'real-time') {
            $queryFilters .= " and `careconsole`.`archived_date` IS NULL and `careconsole`.`recall_date` IS NULL ";
        } elseif ($filters['type'] == 'historical') {
            $queryFilters .= " and `careconsole`.`created_at` >= '$startDate' and `careconsole`.`created_at` <= '$endDate' ";
        }

        if ($filters['status_of_patients'] != 'none') {
            switch ($filters['status_of_patients']) {
                case 'pending_contact':
                    $queryFilters .= " and `careconsole`.`stage_id` = 1 and (`contact_attempts`.`count` = 0 or `contact_attempts`.`count` is null) ";
                    break;
                case 'contact_attempted':
                    $queryFilters .= " and `careconsole`.`stage_id` = 1 and not (`contact_attempts`.`count` = 0 or `contact_attempts`.`count` is null) ";
                    break;
                case 'appointment_scheduled':
                    $queryFilters .= " and `careconsole`.`stage_id` = 2 ";
                    break;
                case 'kept_appointment':
                    $queryFilters .= " and `careconsole`.`stage_id` = 4 ";
                    break;
                case 'cancelled':
                    $queryFilters .= " and `careconsole`.`stage_id` = 3 and `appointments`.`appointment_status` = 7";
                    break;
                case 'no_show':
                    $queryFilters .= " and `careconsole`.`stage_id` = 3 and `appointments`.`appointment_status` = 8";
                    break;
                case 'finalization':
                    $queryFilters .= " and `careconsole`.`stage_id` = 5 ";
                    break;
            }
        }

        if ($filters['patient_demographics']['gender'] != 'none') {
            switch ($filters['patient_demographics']['gender']) {
                case 'male':
                    $queryFilters .= " and (`patients`.`gender` = 'M' or `patients`.`gender` = 'Male') ";
                    break;
                case 'female':
                    $queryFilters .= " and (`patients`.`gender` = 'F' or `patients`.`gender` = 'Female') ";
            }
        }

        if ($filters['referred_to']['type'] != 'none') {
            switch ($filters['referred_to']['type']) {
                case 'practice':
                    $queryFilters .= ' and `practices`.`id` = ' . $filters['referred_to']['name'];
                    break;
                case 'practice_user':
                    $queryFilters .= ' and `users`.`id` = ' . $filters['referred_to']['name'];
                    break;
            }
        }

        if ($filters['incomming_referrals']['referred_by']['type'] != 'none') {
            switch ($filters['incomming_referrals']['referred_by']['type']) {
                case 'practice':
                    $queryFilters .= ' and `referral_history`.`referred_by_practice` = "' . $filters['incomming_referrals']['referred_by']['name'] . '"';
                    break;
                case 'practice_user':
                    $queryFilters .= ' and `referral_history`.`referred_by_provider` = "' . $filters['incomming_referrals']['referred_by']['name'] . '"';
                    break;
            }
        }

        if ($filters['incomming_referrals']['appointment_type'] != 'none') {
            $queryFilters .= ' and `appointments`.`appointmenttype` = "' . $filters['incomming_referrals']['appointment_type'] . '"';
        }

        if ($filters['patient_demographics']['insurance_type'] != 'none') {
            $queryFilters .= ' and `patient_insurance`.`insurance_carrier` = "' . $filters['patient_demographics']['insurance_type'] . '"';
        }

        if ($filters['disease_type'] != 'none') {
            $queryFilters .= ' and `referral_history`.`disease_type` = "' . $filters['disease_type'] . '"';
            if ($filters['severity_scale'] != 'none') {
                $queryFilters .= ' and `referral_history`.`severity` = "' . $filters['severity_scale'] . '"';
            }
        }

        return $queryFilters;
    }

    public function execReportsQuery($query)
    {
        //dd($query);
        if ($query && $query != '') {
            $result = DB::select(DB::raw($query));
        }
        return $result;
    }

}
