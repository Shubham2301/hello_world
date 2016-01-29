<?php

namespace myocuhub\Services\KPI;

use myocuhub\Models\Careconsole;

class KPIService
{

    public function __construct()
    {

    }

    public function getCount($kpiName, $networkID)
    {
        switch ($kpiName) {
            case 'contact-attempted':
                $count = $this->getContactAttemptedCount($networkID);
                break;
            case 'contact-pending':
                $count = $this->getContactPendingCount($networkID);
                break;
            default:
                $count = -1;
                break;
        }
        return $count;
    }

    protected function getContactAttemptedCount($networkID)
    {
        return Careconsole::where('contact_id', '!=', 'NULL')->count();
    }

    protected function getContactPendingCount($networkID)
    {
        return Careconsole::where('contact_id', 'NULL')->count();
    }
}
