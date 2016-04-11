<?php

namespace myocuhub\Services\Reports;

use Datetime;
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

}
