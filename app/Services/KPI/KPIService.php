<?php

namespace myocuhub\Services\KPI;

use myocuhub\Models\Careconsole;

class KPIService
{

    public function __construct()
    {

    }

    public function getCount($kpiName, $networkID, $stageID)
    {
        switch ($kpiName) {
            case 'contact-attempted':
                $count = Careconsole::getContactAttemptedCount($networkID, $stageID);
                break;
            case 'contact-pending':
                $count = Careconsole::getContactPendingCount($networkID, $stageID);
                break;
            case 'appointment-scheduled':
                $count = Careconsole::getAppointmentScheduledCount($networkID, $stageID);
                break;
            case 'appointment-tomorrow':
                $count = Careconsole::getAppointmentTomorrowCount($networkID, $stageID);
                break;
            case 'past-appointment':
                $count = Careconsole::getPastAppointmentCount($networkID, $stageID);
                break;
            case 'pending-information':
                $count = Careconsole::getPendingInformationCount($networkID, $stageID);
                break;
            case 'cancelled':
                $count = Careconsole::getCancelledCount($networkID, $stageID);
                break;
            case 'no-show':
                $count = Careconsole::getNoShowCount($networkID, $stageID);
                break;
            case 'waiting-for-report':
                $count = Careconsole::getWaitingForReportCount($networkID, $stageID);
                break;
            case 'reports-overdue':
                $count = Careconsole::getReportsOverdueCount($networkID, $stageID);
                break;
            case 'ready-to-be-completed':
                $count = Careconsole::getReadyToBeCompletedCount($networkID, $stageID);
                break;
            case 'overdue':
                $count = Careconsole::getOverdueCount($networkID, $stageID);
                break;
            default:
                $count = -1;
                break;
        }
        return $count;
    }

}
