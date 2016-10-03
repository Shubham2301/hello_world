<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use myocuhub\Models\PatientRecord;

class ContactHistory extends Model
{
    protected $table = "contact_history";

    public function careconsole() {
        return $this->belongsTo('myocuhub\Models\Careconsole', 'console_id');
    }

    public function action() {
        return $this->belongsTo('myocuhub\Models\Action');
    }

    public function actionResult() {
        return $this->belongsTo('myocuhub\Models\ActionResult');
    }

    public function currentStage() {
        return $this->belongsTo('myocuhub\Models\CareconsoleStage', 'current_stage');
    }

    public function previousStage() {
        return $this->belongsTo('myocuhub\Models\CareconsoleStage', 'previous_stage');
    }

    public function appointments() {
        return $this->belongsTo('myocuhub\Models\Appointment', 'appointment_id');
    }

    public function users() {
        return $this->belongsTo('myocuhub\User', 'user_id');
    }

    public static function getContactHistory($consoleID){
        return self::where('console_id', $consoleID)
            ->leftjoin('actions', 'contact_history.action_id', '=', 'actions.id')
            ->leftjoin('action_results', 'contact_history.action_result_id', '=', 'action_results.id')
            ->leftjoin('careconsole', 'contact_history.console_id', '=', 'careconsole.id')
            ->select(DB::raw('contact_history.*, actions.*, careconsole.*, action_results.name as result_name, action_results.display_name as result_display_name, action_results.id as result_id, contact_history.id as contact_history_id'))
            ->orderBy('contact_history.id','decs')
            ->get();

    }

    public static function getAverageDaysInStage($stage_id, $startDate, $endDate) {
        return self::whereNotNull('user_id')
            ->where('previous_stage', $stage_id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->avg('days_in_prev_stage');
    }

    public function record(){
        return $this->hasOne(PatientRecord::class);
    }

    public static function getPerformanceReportData($networkID, $startDate, $endDate) {

        return self::where('contact_activity_date', '>=', $startDate)
            ->where('contact_activity_date', '<=', $endDate)
            ->whereNotNull('user_id')
            ->whereHas('careconsole.importHistory', function ($query) use ($networkID) {
                $query->where('network_id', $networkID);
            })
            ->has('careconsole.patient')
            ->with('action')
            ->with('actionResult')
            ->with('users')
            ->with('careconsole.patient')
            ->with('appointments')
            ->get();

    }

    public static function getPerformanceReportAppointmentData($networkID, $startDate, $endDate) {

        return self::whereHas('appointments', function ($query) use ($startDate, $endDate) {
                $query->where('start_datetime', '>=', $startDate);
                $query->where('start_datetime', '<=', $endDate);
            })
            ->whereNotNull('user_id')
            ->whereHas('careconsole.importHistory', function ($query) use ($networkID) {
                $query->where('network_id', $networkID);
            })
            ->has('careconsole.patient')
            ->with('action')
            ->with('actionResult')
            ->with('users')
            ->with('careconsole.patient')
            ->with('appointments')
            ->get();

    }
}
