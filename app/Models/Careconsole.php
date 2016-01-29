<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Careconsole extends Model
{
    protected $table = "careconsole";

    public function patient()
    {
        return $this->hasOne('myocuhub\Patient');
    }
    public function importHistory()
    {
        return $this->hasOne('myocuhub\Models\ImportHistory');
    }
    public function contactHistory()
    {
        return $this->hasMany('myocuhub\Models\ContactHistory');
    }
    public function referralHistory()
    {
        return $this->hasMany('myocuhub\Models\ReferralHistory');
    }
    public function appointment()
    {
        return $this->hasOne('myocuhub\Models\Appointments');
    }
    public static function getContactAttemptedCount($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->where('contact_id', '!=', 'NULL')
            ->count();
    }
    public static function getContactPendingCount($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->where('contact_id', 'NULL')
            ->count();
    }

    public static function getAppointmentScheduledCount($networkID, $stageID)
    {
        $sqlResult = DB::select("select count(*) as count from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	where `stage_id` = $stageID and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) > 1");

        return $sqlResult[0]->count;
    }
    public static function getAppointmentTomorrowCount($networkID, $stageID)
    {
        $sqlResult = DB::select("select count(*) as count from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	where `stage_id` = $stageID and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) = 1");

        return $sqlResult[0]->count;
    }
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
}
