<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Careconsole extends Model
{
    protected $table = "careconsole";

    /**
     * @return mixed
     */
    public function patient()
    {
        return $this->hasOne('myocuhub\Patient');
    }

    /**
     * @return mixed
     */
    public function importHistory()
    {
        return $this->hasOne('myocuhub\Models\ImportHistory');
    }

    /**
     * @return mixed
     */
    public function contactHistory()
    {
        return $this->hasMany('myocuhub\Models\ContactHistory');
    }

    /**
     * @return mixed
     */
    public function referralHistory()
    {
        return $this->hasMany('myocuhub\Models\ReferralHistory');
    }

    /**
     * @return mixed
     */
    public function appointment()
    {
        return $this->hasOne('myocuhub\Models\Appointments');
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getContactAttemptedCount($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->where('contact_id', '!=', 'NULL')
            ->count();
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getContactPendingCount($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->where('contact_id', 'NULL')
            ->count();
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public static function getAppointmentScheduledCount($networkID, $stageID)
    {
        $sqlResult = DB::select("select count(*) as count from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	where `stage_id` = $stageID and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) > 1");

        return $sqlResult[0]->count;
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public static function getAppointmentTomorrowCount($networkID, $stageID)
    {
        $sqlResult = DB::select("select count(*) as count from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	where `stage_id` = $stageID and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) = 1");

        return $sqlResult[0]->count;
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getPastAppointmentCount($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('appointments', 'careconsole.appointment_id', '=', 'appointments.id')
            ->where('start_datetime', '>', 'CURRENT_TIMESTAMP')
            ->count();
    }

    public static function getPendingInformationCount($networkID, $stageID)
    {
        return;
    }

    public static function getCancelledCount($networkID, $stageID)
    {
        return;
    }

    public static function getNoShowCount($networkID, $stageID)
    {
        return;
    }

    public static function getWaitingForReportCount($networkID, $stageID)
    {
        return;
    }

    public static function getReportsOverdueCount($networkID, $stageID)
    {
        return;
    }

    public static function getReadyToBeCompletedCount($networkID, $stageID)
    {
        return;
    }

    public static function getOverdueCount($networkID, $stageID)
    {
        return;
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getContactAttemptedPatients($networkID, $stageID)
    {
        self::where('stage_id', $stageID)
            ->where('contact_id', '!=', 'NULL')
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')->get();
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getContactPendingPatients($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->where('contact_id', 'NULL')
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')->get();
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public static function getAppointmentScheduledPatients($networkID, $stageID)
    {
        $sqlResult = DB::select("select * from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	left join `patients`
            	on `careconsole`.`patient_id` = `patients`.`id`
            	where `stage_id` = $stageID and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) > 1");

        return $sqlResult;
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public static function getAppointmentTomorrowPatients($networkID, $stageID)
    {
        $sqlResult = DB::select("select * from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	left join `patients`
            	on `careconsole`.`patient_id` = `patients`.`id`
            	where `stage_id` = $stageID and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) = 1");

        return $sqlResult;
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getPastAppointmentPatients($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('appointments', 'careconsole.appointment_id', '=', 'appointments.id')
            ->where('start_datetime', '>', 'CURRENT_TIMESTAMP')
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')->get();
    }

    public static function getPendingInformationPatients($networkID, $stageID)
    {
        return;
    }

    public static function getCancelledPatients($networkID, $stageID)
    {
        return;
    }

    public static function getNoShowPatients($networkID, $stageID)
    {
        return;
    }

    public static function getWaitingForReportPatients($networkID, $stageID)
    {
        return;
    }

    public static function getReportsOverduePatients($networkID, $stageID)
    {
        return;
    }

    public static function getReadyToBeCompletedPatients($networkID, $stageID)
    {
        return;
    }

    public static function getOverduePatients($networkID, $stageID)
    {
        return;
    }

}
