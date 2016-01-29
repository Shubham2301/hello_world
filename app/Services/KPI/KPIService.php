<?php

namespace myocuhub\Services\KPI;

use myocuhub\Models\Careconsole;

class KPIService
{

    public function __construct()
    {

    }

    /**
     * @param $kpiName
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
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
    /**
     * @param $kpiName
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public function getPatients($kpiName, $networkID, $stageID)
    {
        switch ($kpiName) {
            case 'contact-attempted':
                $patients = Careconsole::getContactAttemptedPatients($networkID, $stageID);
                break;
            case 'contact-pending':
                $patients = Careconsole::getContactPendingPatients($networkID, $stageID);
                break;
            case 'appointment-scheduled':
                $patients = Careconsole::getAppointmentScheduledPatients($networkID, $stageID);
                break;
            case 'appointment-tomorrow':
                $patients = Careconsole::getAppointmentTomorrowPatients($networkID, $stageID);
                break;
            case 'past-appointment':
                $patients = Careconsole::getPastAppointmentPatients($networkID, $stageID);
                break;
            case 'pending-information':
                $patients = Careconsole::getPendingInformationPatients($networkID, $stageID);
                break;
            case 'cancelled':
                $patients = Careconsole::getCancelledPatients($networkID, $stageID);
                break;
            case 'no-show':
                $patients = Careconsole::getNoShowPatients($networkID, $stageID);
                break;
            case 'waiting-for-report':
                $patients = Careconsole::getWaitingForReportPatients($networkID, $stageID);
                break;
            case 'reports-overdue':
                $patients = Careconsole::getReportsOverduePatients($networkID, $stageID);
                break;
            case 'ready-to-be-completed':
                $patients = Careconsole::getReadyToBeCompletedPatients($networkID, $stageID);
                break;
            case 'overdue':
                $patients = Careconsole::getOverduePatients($networkID, $stageID);
                break;
            default:
                $patients = [];
                break;
        }
        return $count;
    }

}
