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
        $result['scheduled_seen'] = Careconsole::query()
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
        $result['scheduled_not_seen'] = Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->where('stage_id', 2)
            ->count();

        $result['appointment_not_needed'] = Careconsole::query()
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
        $result['appointment_declined'] = Careconsole::query()
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
        $result['patients_ran_through'] = Careconsole::query()
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->where('careconsole.created_at', '>=', $this->getStartDate())
            ->where('careconsole.created_at', '<=', $this->getEndDate())
            ->where('stage_id', 5)
            ->whereNotNull('careconsole.archived_date')
            ->count();

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
                    left join (select console_id ,COUNT(*) as count from contact_history group by console_id order by count desc) as `contact_attempts` on `contact_attempts`.`console_id` = `careconsole`.`id`
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
            $queryFilters .= " and `careconsole`.`archived_date` IS NULL ";
        } elseif ($filters['type'] == 'historical') {
            $queryFilters .= " and `careconsole`.`created_at` >= $startDate careconsole_created_at <= $endDate ";
        }

        if ($filters['status_of_patients'] != 'none') {
            switch ($filters['status_of_patients']) {
                case 'pending_contact':
                    $queryFilters .= " and `careconsole`.`stage_id` = 1 and (`contact_attempts`.`count` = 0 or `contact_attempts`.`count` = null) ";
                    break;
                case 'contact_attempted':
                    $queryFilters .= " and `careconsole`.`stage_id` = 1 and not (`contact_attempts`.`count` = 0 or `contact_attempts`.`count` = null) ";
                    break;
                case 'appointment_scheduled':
                    $queryFilters .= " and `careconsole`.`stage_id` = 2 ";
                    break;
                case 'kept_appointment':
                    $queryFilters .= " and `careconsole`.`stage_id` = 4 ";
                    break;
                case 'cancelled':
                    $queryFilters .= " and `careconsole`.`stage_id` = 3 and h.`postAction` = 21";
                    break;
                case 'no_show':
                    $queryFilters .= " and `careconsole`.`stage_id`` = 3 and h.`postAction` = 18";
                    break;
                case 'finalization':
                    $queryFilters .= " and `careconsole`.`stage_id`` = 5 ";
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
                    $queryFilters .= ' and `practices`.`id` = ' . $filters['referred_to']['id'];
                    break;
                case 'practice_user':
                    $queryFilters .= ' and `users`.`id` = ' . $filters['referred_to']['id'];
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
        if ($query && $query != '') {
            $result = DB::select(DB::raw($query));
        }
        return $result;
    }

    public function getReportingData($filters)
    {
        $query = $this->buildReportsQuery($filters);
        $results = $this->execReportsQuery($query);
        return $results;
    }

}
