<?php

namespace myocuhub\Services\PatientCare;

class PatientCare
{

    protected $network;
    protected $startDate;
    protected $endDate;
    protected $filters;

    public function __construct()
    {

    }

    public static function getTotalReferred()
    {
        return;
    }

    public static function getContactStatistics()
    {

        $contact['phone']['count'] = 0;
        $contact['phone']['data'] = [];

        /**
         * Contacted by phone
         */
        $actionId = 1;
        $contactHistory = ContactHistory::where('action_id', $actionId)
            ->where('created_date', '>=', $startDate)
            ->where('created_date', '<=', $endDate)
            ->leftjoin('careconsole', 'contact_history.console_id', 'careconsole.id')
            ->leftjoin('import_history', 'careconsole.import_id', 'import_history.id');

        return;
    }

    public static function getReferredTo()
    {
        return;
    }

    public static function getAppointmentStatus()
    {
        return;
    }

    public static function getReferredBy()
    {
        return;
    }

}
