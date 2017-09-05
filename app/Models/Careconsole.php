<?php

namespace myocuhub\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use myocuhub\Models\ContactHistory;

class Careconsole extends Model
{
    protected $table = "careconsole";

    /**
     * @return mixed
     */
    public function patient()
    {
        return $this->belongsTo('myocuhub\Patient');
    }

    /**
     * @return mixed
     */
    public function importHistory()
    {
        return $this->belongsTo('myocuhub\Models\ImportHistory', 'import_id');
    }

    /**
     * @return mixed
     */
    public function contactHistory()
    {
        return $this->hasMany('myocuhub\Models\ContactHistory', 'console_id');
    }

    /**
     * @return mixed
     */
    public function activeContactHistory()
    {
        return $this->hasMany('myocuhub\Models\ContactHistory', 'console_id')->whereNull('archived');
    }

    /**
     * @return mixed
     */
    public function latestContactHistory()
    {
        return $this->hasOne('myocuhub\Models\ContactHistory', 'console_id')->orderBy('updated_at', 'desc');
    }

    public function scopeNetworkCheck($query, $network_id)
    {
        $query->whereHas('importHistory', function ($query) use ($network_id) {
            $query->where('network_id', $network_id);
        });

        return $query;
    }

    public function scopeConsoleActivityRange($query, $start_date, $end_date)
    {
        $query->whereHas('contactHistory', function ($sub_query) use ($start_date, $end_date) {
            $sub_query->activityRange($start_date, $end_date);
        });

        return $query;
    }

    /**
     * @return mixed
     */
    public function latestArchiveContactHistory()
    {
        return $this->hasOne('myocuhub\Models\ContactHistory', 'console_id')->whereHas('actionResult', function ($q) {
            $q->where('name', 'patient-declined-services');
            $q->orwhere('name', 'other-reasons-for-declining');
            $q->orwhere('name', 'already-seen-by-outside-dr');
            $q->orwhere('name', 'no-need-to-schedule');
            $q->orwhere('name', 'no-insurance');
            $q->orwhere('name', 'closed');
            $q->orwhere('name', 'incomplete');
        })->orderBy('updated_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function referralHistory()
    {
        return $this->belongsTo('myocuhub\Models\ReferralHistory', 'referral_id');
    }

    /**
     * @return mixed
     */
    public function appointment()
    {
        return $this->belongsTo('myocuhub\Models\Appointment');
    }

    /**
     * @return mixed
     */
    public function stage()
    {
        return $this->hasOne('myocuhub\Models\CareconsoleStage', 'id', 'stage_id');
    }

    /**
     * @param $networkID
     */
    public static function getArchivedPatients($networkID)
    {
        return self::whereNotNull('archived_date')
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->get(['*', 'careconsole.id', 'careconsole.created_at']);
    }

    /**
     * @param $networkID
     */
    public static function getBucketPatientsExcelData($networkID, $bucketName, $kpiName)
    {
        $query = self::query();

        $query->consoleStagePatientQuery($networkID, $bucketName);
        $query->consoleKpiPatientQuery($kpiName);

        $query->with(['patient', 'appointment']);
        return $query->get();
    }

    /**
     * @param $networkID
     */
    public static function getRecallPatients($networkID)
    {
        return self::whereNotNull('recall_date')
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->get(['*', 'careconsole.id', 'careconsole.created_at']);
    }

    /**
     * @param $networkID
     */
    public static function getPriorityPatients($networkID)
    {
        return self::where('priority', 1)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
           ->get(['*', 'careconsole.id', 'careconsole.created_at']);
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getStagePatients($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->get(['*', 'careconsole.id', 'careconsole.created_at']);
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getStageCount($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->where('import_history.network_id', $networkID)
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->count();
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getContactAttemptedCount($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('contact_history')
                    ->whereRaw('contact_history.console_id = careconsole.id')
                    ->whereNull('contact_history.archived');
            })
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->count();
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getContactPendingCount($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->leftjoin('contact_history', 'careconsole.id', '=', 'contact_history.console_id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('contact_history')
                    ->whereRaw('contact_history.console_id = careconsole.id')
                    ->whereNull('contact_history.archived');
            })
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->groupBy('patient_id')
            ->get()
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
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                left join `patients`
                on `appointments`.`patient_id` = `patients`.`id`
                where `import_history`.`network_id` = $networkID and
                `stage_id` = $stageID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(`start_datetime`, CURRENT_TIMESTAMP) > 1 and
                `patients`.`deleted_at` is null");

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
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                left join `patients`
                on `appointments`.`patient_id` = `patients`.`id`
                where `import_history`.`network_id` = $networkID and
                `stage_id` = $stageID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(`start_datetime`, CURRENT_TIMESTAMP) = 1 and
                `patients`.`deleted_at` is null");

        return $sqlResult[0]->count;
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getPastAppointmentCount($networkID, $stageID)
    {
        $sqlResult = DB::select("select count(*) as count from `careconsole`
                left join `appointments`
                on `careconsole`.`appointment_id` = `appointments`.`id`
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                left join `patients`
                on `appointments`.`patient_id` = `patients`.`id`
                where `import_history`.`network_id` = $networkID and
                `stage_id` = $stageID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(`start_datetime`, CURRENT_TIMESTAMP) <= 0 and
                `patients`.`deleted_at` is null");

        return $sqlResult[0]->count;
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return null
     */
    public static function getPendingInformationCount($networkID, $stageID)
    {
        return;
    }

    /**
     * @param $networkID
     * @param $stageID
     * @param $statusName
     * @return null
     */
    public static function getAppointmentStatusCount($networkID, $stageID, $statusName)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->leftjoin('appointments', 'careconsole.appointment_id', '=', 'appointments.id')
            ->leftjoin('kpis', 'appointments.appointment_status', '=', 'kpis.id')
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->where('kpis.name', '=', $statusName)
            ->count();
        return;
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public static function getStageWaitingCount($networkID, $stageID)
    {
        $sqlResult = DB::select("select count(*) as count from `careconsole`
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                left join `patients`
                on `careconsole`.`patient_id` = `patients`.`id`
                where `import_history`.`network_id` = $networkID and
                `stage_id` = $stageID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(CURRENT_TIMESTAMP, `careconsole`.`stage_updated_at`) < 5 and
                `patients`.`deleted_at` is null");

        return $sqlResult[0]->count;
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public static function getStageOverdueCount($networkID, $stageID)
    {
        $sqlResult = DB::select("select count(*) as count from `careconsole`
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                left join `patients`
                on `careconsole`.`patient_id` = `patients`.`id`
                where `stage_id` = $stageID and
                `import_history`.`network_id` = $networkID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(CURRENT_TIMESTAMP, `careconsole`.`stage_updated_at`) > 4 and
                `patients`.`deleted_at` is null");

        return $sqlResult[0]->count;
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getContactAttemptedPatients($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('contact_history')
                    ->whereRaw('contact_history.console_id = careconsole.id')
                    ->whereNull('contact_history.archived');
            })
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->get(['*', 'careconsole.id', 'careconsole.created_at']);
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getContactPendingPatients($networkID, $stageID)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->leftjoin('contact_history', 'careconsole.id', '=', 'contact_history.console_id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('contact_history')
                    ->whereRaw('contact_history.console_id = careconsole.id')
                    ->whereNull('contact_history.archived');
            })
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->groupBy('patient_id')
           ->get(['*', 'careconsole.id', 'careconsole.created_at']);
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public static function getAppointmentScheduledPatients($networkID, $stageID)
    {
        $sqlResult = DB::select("select *, `careconsole`.id, `careconsole`.created_at from `careconsole`
                left join `appointments`
                on `careconsole`.`appointment_id` = `appointments`.`id`
                left join `patients`
                on `careconsole`.`patient_id` = `patients`.`id`
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                where `import_history`.`network_id` = $networkID and
                `stage_id` = $stageID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(`start_datetime`, CURRENT_TIMESTAMP) > 1 and
                `patients`.`deleted_at` is null");

        $results = array();
        $i = 0;
        foreach ($sqlResult as $result) {
            $results[$i] = get_object_vars($result);
            $i++;
        }
        return $results;
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public static function getAppointmentTomorrowPatients($networkID, $stageID)
    {
        $sqlResult = DB::select("select *, `careconsole`.id, `careconsole`.created_at from `careconsole`
                left join `appointments`
                on `careconsole`.`appointment_id` = `appointments`.`id`
                left join `patients`
                on `careconsole`.`patient_id` = `patients`.`id`
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                where `import_history`.`network_id` = $networkID and
                `stage_id` = $stageID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(`start_datetime`, CURRENT_TIMESTAMP) = 1 and
                `patients`.`deleted_at` is null");

        $results = array();
        $i = 0;
        foreach ($sqlResult as $result) {
            $results[$i] = get_object_vars($result);
            $i++;
        }
        return $results;
    }

    /**
     * @param $networkID
     * @param $stageID
     */
    public static function getPastAppointmentPatients($networkID, $stageID)
    {
        $sqlResult = DB::select("select *, `careconsole`.id, `careconsole`.created_at from `careconsole`
                left join `appointments`
                on `careconsole`.`appointment_id` = `appointments`.`id`
                left join `patients`
                on `careconsole`.`patient_id` = `patients`.`id`
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                where `import_history`.`network_id` = $networkID and
                `stage_id` = $stageID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(`start_datetime`, CURRENT_TIMESTAMP) <= 0 and
                `patients`.`deleted_at` is null");

        $results = array();
        $i = 0;
        foreach ($sqlResult as $result) {
            $results[$i] = get_object_vars($result);
            $i++;
        }
        return $results;
    }

    /**
     * @param $networkID
     * @param $stageID
     * @param $statusName
     * @return null
     */
    public static function getStageWaitingPatients($networkID, $stageID)
    {
        $sqlResult = DB::select("select *, `careconsole`.id, `careconsole`.created_at from `careconsole`
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                left join `patients`
                on `careconsole`.`patient_id` = `patients`.`id`
                where `import_history`.`network_id` = $networkID and
                `stage_id` = $stageID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(CURRENT_TIMESTAMP, `careconsole`.`stage_updated_at`) < 5 and
                `patients`.`deleted_at` is null");

        $results = array();
        $i = 0;
        foreach ($sqlResult as $result) {
            $results[$i] = get_object_vars($result);
            $i++;
        }
        return $results;
    }

    /**
     * @param $networkID
     * @param $stageID
     * @return mixed
     */
    public static function getStageOverduePatients($networkID, $stageID)
    {
        $sqlResult = DB::select("select *, `careconsole`.id, `careconsole`.created_at from `careconsole`
                left join `import_history`
                on `import_history`.`id` = `careconsole`.`import_id`
                left join `patients`
                on `careconsole`.`patient_id` = `patients`.`id`
                where `stage_id` = $stageID and
                `import_history`.`network_id` = $networkID and
                `careconsole`.`archived_date` is null and
                `careconsole`.`recall_date` is null and
                datediff(CURRENT_TIMESTAMP, `careconsole`.`stage_updated_at`) > 4 and
                `patients`.`deleted_at` is null");

        $results = array();
        $i = 0;
        foreach ($sqlResult as $result) {
            $results[$i] = get_object_vars($result);
            $i++;
        }
        return $results;
    }

    public static function getAppointmentStatusPatients($networkID, $stageID, $statusName)
    {
        return self::where('stage_id', $stageID)
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->leftjoin('appointments', 'careconsole.appointment_id', '=', 'appointments.id')
            ->leftjoin('kpis', 'appointments.appointment_status', '=', 'kpis.id')
            ->whereNull('deleted_at')
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->where('kpis.name', '=', $statusName)
            ->get(['*', 'careconsole.id', 'careconsole.created_at']);
        return;
    }
    /**
     * @param $lowerlimit
     * @param $upperlimit
     * @param $patientdata
     * @return mixed
     */
    public static function filterPatientByDaysPendings($lowerlimit, $upperlimit, $patientdata)
    {
        $data = [];
        $i = 0;
        foreach ($patientdata as $patient) {
            if ($patient['days-pending'] >= $lowerlimit && $patient['days-pending'] < $upperlimit) {
                $data[$i] = $patient;
                $i++;
            }
        }
        return $data;
    }

    /**
     * @param $networkID
     */
    public static function getRecallPatientsToMove($networkID)
    {
        return self::whereNotNull('recall_date')
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', $networkID)
            ->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
            ->whereNull('deleted_at')
            ->whereRaw('datediff(`recall_date`, CURRENT_TIMESTAMP) <= 1')
           ->get(['*', 'careconsole.id', 'careconsole.created_at']);
    }


    public static function getReachRateData($networkID, $startDate, $endDate)
    {
        return self::where(function ($subquery) use ($startDate, $endDate) {
            $subquery->whereHas('contactHistory', function ($query) use ($startDate, $endDate) {
                $query->whereNotNull('user_id');
                $query->where('contact_activity_date', '>=', $startDate);
                $query->where('contact_activity_date', '<=', $endDate);
            })
                ->orWhereHas('importHistory', function ($query) use ($startDate, $endDate) {
                    $query->where('created_at', '>=', $startDate);
                    $query->where('created_at', '<=', $endDate);
                });
        })
            ->whereHas('importHistory', function ($query) use ($networkID, $startDate, $endDate) {
                $query->where('network_id', $networkID);
            })
            ->has('patient')
            ->with(['contactHistory' => function ($query) use ($startDate, $endDate) {
                $query->whereNotNull('user_id');
                $query->where('contact_activity_date', '>=', $startDate);
                $query->where('contact_activity_date', '<=', $endDate);
            }, 'contactHistory.action', 'contactHistory.actionResult', 'contactHistory.currentStage', 'contactHistory.previousStage', 'contactHistory.appointments', 'contactHistory.appointments.provider', 'contactHistory.appointments.practice', 'contactHistory.appointments.practiceLocation'])
            ->withCount(['importHistory' => function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                $query->where('created_at', '<=', $endDate);
            }])
            ->with('patient')
            ->with('referralHistory')
            ->get();
    }

    public static function getCallCenterReportData($networkID, $startDate, $endDate, $userID = null)
    {
        return self::where(function ($subquery) use ($startDate, $endDate) {
            $subquery->whereHas('contactHistory', function ($query) use ($startDate, $endDate) {
                $query->whereNotNull('user_id');
                $query->where('contact_activity_date', '>=', $startDate);
                $query->where('contact_activity_date', '<=', $endDate);
            });
        })
            ->whereHas('importHistory', function ($query) use ($networkID) {
                $query->where('network_id', $networkID);
            })
            ->has('patient')
            ->with(['contactHistory' => function ($query) use ($startDate, $endDate, $userID) {
                $query->whereNotNull('user_id');
                $query->where('contact_activity_date', '>=', $startDate);
                $query->where('contact_activity_date', '<=', $endDate);
                if ($userID) {
                    $query->where('user_id', $userID);
                }
                $query->whereHas('action', function ($q) {
                    $q->where('name', 'schedule');
                    $q->orwhere('name', 'reschedule');
                    $q->orwhere('name', 'manually-reschedule');
                    $q->orwhere('name', 'manually-schedule');
                    $q->orwhere('name', 'previously-scheduled');
                    $q->orwhere('name', 'request-patient-email');
                    $q->orwhere('name', 'request-patient-phone');
                    $q->orwhere('name', 'request-patient-sms');
                    $q->orwhere('name', 'contact-attempted-by-phone');
                    $q->orwhere('name', 'contact-attempted-by-email');
                });
            }, 'contactHistory.action', 'contactHistory.actionResult'])
            ->get();
    }

    public static function getTotalPatientCount($networkID)
    {
        return self::whereHas('importHistory', function ($query) use ($networkID) {
            $query->where('network_id', $networkID);
        })
            ->has('patient')
            ->count();
    }

    /**
     * @param $networkID
     */
    public static function getConsolePatientsData($networkID, $stageName, $kpiName, $filterType, $filterValue, $sortField, $sortOrder)
    {
        $query = self::query();
        
        $query->consoleStagePatientQuery($networkID, $stageName);

        $query->consoleKpiPatientQuery($kpiName);

        if ($filterValue != '') {
            $query->consolepatientListFilter($filterType, $filterValue);
        }

        $query->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id');
        $query->leftjoin('appointments', 'careconsole.appointment_id', '=', 'appointments.id');
        $query->leftjoin('careconsole_stages', 'careconsole.stage_id', '=', 'careconsole_stages.id');

        $query->with('activeContactHistory');
        $query->with('appointment.provider');
        $query->with('appointment.practice');
        $query->with('appointment.practiceLocation');
        $query->with('latestArchiveContactHistory');

        $query->consoleSortQuery($sortField, $sortOrder);

        return $query->select('careconsole.*', 'patients.firstname', 'patients.middlename', 'patients.lastname', 'patients.email', 'patients.cellphone', 'patients.homephone', 'patients.workphone', 'patients.pcp', 'patients.state', 'patients.special_request', 'patients.addressline1', 'patients.addressline2', 'patients.birthdate', 'appointments.practice_id', 'appointments.provider_id', 'appointments.location_id', 'appointments.start_datetime', 'appointments.appointmenttype', 'careconsole_stages.display_name')->get();
    }

    public static function scopeConsoleStagePatientQuery($query, $networkID, $stageName)
    {
        $query->whereHas('importHistory', function ($query) use ($networkID) {
            $query->where('network_id', $networkID);
        })
          ->has('patient');
        switch ($stageName) {
            case 'archived':
              $query->whereNotNull('archived_date');
              break;
            case 'recall':
              $query->whereNotNull('recall_date');
              break;
            case 'priority':
              $query->where('priority', '1');
              $query->whereNull('archived_date');
              $query->whereNull('recall_date');
              break;
            default:
              $query->whereHas('stage', function ($subquery) use ($stageName) {
                  $subquery->where('name', $stageName);
              });
              $query->whereNull('archived_date');
              $query->whereNull('recall_date');
              break;
        }
        return $query;
    }

    public static function scopeConsoleKpiPatientQuery($query, $kpiName)
    {
        switch ($kpiName) {
            case 'contact-attempted':
              $query->has('activeContactHistory');
              break;
            case 'contact-pending':
            $query->has('activeContactHistory', '=', '0');
              break;
            case 'appointment-scheduled':
              $query->whereHas('appointment', function ($subquery) {
                  $subquery->where('start_datetime', '>', date('Y-m-d', strtotime(' +1 day')));
              });
              break;
            case 'appointment-tomorrow':
              $query->whereHas('appointment', function ($subquery) {
                  $subquery->where('start_datetime', '=', date('Y-m-d', strtotime(' +1 day')));
              });
              break;
            case 'past-appointment':
              $query->whereHas('appointment', function ($subquery) {
                  $subquery->where('start_datetime', '<=', date('Y-m-d'));
              });
              break;
            case 'cancelled':
            case 'no-show':
              $query->whereHas('appointment.appointmentStatus', function ($subquery) use ($kpiName) {
                  $subquery->where('name', $kpiName);
              });
              break;
            case 'waiting-for-report':
            case 'ready-to-be-completed':
              $query->where('stage_updated_at', '>=', date('Y-m-d', strtotime(' -5 day')));
              break;
            case 'reports-overdue':
            case 'overdue':
              $query->where('stage_updated_at', '<', date('Y-m-d', strtotime(' -5 day')));
              break;
            default:
              break;
        }

        return $query;
    }

    public static function scopeConsolePatientListFilter($query, $filterType, $filterValue)
    {
        switch ($filterType) {
          case 'full-name':
            $query->whereHas('patient', function ($subquery) use ($filterValue) {
                $subquery->where('firstname', 'LIKE', '%' . $filterValue . '%')
                      ->orWhere('middlename', 'LIKE', '%' . $filterValue . '%')
                      ->orWhere('lastname', 'LIKE', '%' . $filterValue . '%');
            });
            break;
          case 'phone':
            $query->whereHas('patient', function ($subquery) use ($filterValue) {
                $subquery->where('cellphone', 'LIKE', '%' . $filterValue . '%')
                      ->orWhere('workphone', 'LIKE', '%' . $filterValue . '%')
                      ->orWhere('homephone', 'LIKE', '%' . $filterValue . '%');
            });
            break;
          case 'last-scheduled-to':
          case 'scheduled-to':
            $query->whereHas('appointment', function ($subquery) use ($filterValue) {
                $subquery->whereHas('provider', function ($subquery) use ($filterValue) {
                    $subquery->where('firstname', 'LIKE', '%' . $filterValue . '%')
                          ->orwhere('middlename', 'LIKE', '%' . $filterValue . '%')
                          ->orwhere('lastname', 'LIKE', '%' . $filterValue . '%');
                });
                $subquery->orWhereHas('practice', function ($subquery) use ($filterValue) {
                    $subquery->where('name', 'LIKE', '%' . $filterValue . '%');
                });
                $subquery->orWhereHas('practiceLocation', function ($subquery) use ($filterValue) {
                    $subquery->where('locationname', 'LIKE', '%' . $filterValue . '%');
                });
            });
            break;
          case 'contact-attempts':
            $query->has('activeContactHistory', '=', $filterValue);
            break;
          case 'appointment-type':
            $query->whereHas('appointment', function ($subquery) use ($filterValue) {
                $subquery->where('appointmenttype', 'LIKE', '%' . $filterValue . '%');
            });
            break;
          case 'days-pending':
            $startDate = date('Y-m-d', strtotime(date('Y-m-d') . ' -' . ($filterValue) . ' day'));
            $query->where('stage_updated_at', 'LIKE', $startDate . '%');
            break;
          case 'special-request':
            $query->whereHas('patient', function ($subquery) use ($filterValue) {
                $subquery->where('special_request', 'LIKE', '%' . $filterValue . '%');
            });
            break;
          case 'state':
            $query->whereHas('patient', function ($subquery) use ($filterValue) {
                $subquery->where('state', 'LIKE', '%' . $filterValue . '%');
            });
            break;
          case 'archive-reason':
            $query->whereHas('contactHistory', function ($subquery) use ($filterValue) {
                $subquery->whereHas('actionResult', function ($subquery) use ($filterValue) {
                    $subquery->where('name', 'LIKE', '%' . $filterValue . '%');
                })->orderBy('created_at', 'desc')->limit(1);
                $subquery->orWhereHas('action', function ($subquery) use ($filterValue) {
                    $subquery->where('name', 'LIKE', '%' . $filterValue . '%');
                })->orderBy('created_at', 'desc')->limit(1);
            });
            break;
          case 'current-stage':
            $query->whereHas('stage', function ($subquery) use ($filterValue) {
                $subquery->where('name', 'LIKE', '%' . $filterValue . '%');
            });
            break;
          default:
        }

        return $query;
    }

    public static function scopeConsoleSortQuery($query, $sortVariable, $sortType)
    {
        if ($sortType == 'SORT_ASC') { // temporary
            $sortType = 'asc';
        } else {
            $sortType = 'desc';
        }
        
        switch ($sortVariable) {
        case 'days-pending':
            $sortType = $sortType == 'asc' ? 'desc' : 'asc';
            $query->orderBy('stage_updated_at', $sortType);
            break;
        case 'full-name':
            $query->orderBy('lastname', $sortType);
            $query->orderBy('firstname', $sortType);
            $query->orderBy('middlename', $sortType);
            break;
        case 'state':
            $query->orderBy('state', $sortType);
            break;
        case 'request-received':
            $query->orderBy('careconsole.created_at', $sortType);
            break;
        case 'special-request':
            $query->orderBy('special_request', $sortType);
            break;
        case 'contact-attempts':
            break;
        case 'last-scheduled-to':
        case 'scheduled-to':
            break;
        case 'last-appointment-date':
        case 'appointment-date':
            $query->orderBy('start_datetime', $sortType);
            break;
        case 'appointment-type':
            $query->orderBy('appointmenttype', $sortType);
            break;
        case 'archived-at':
            $query->orderBy('archived_date', $sortType);
            break;
        case 'recall-at':
            $query->orderBy('recall_date', $sortType);
            break;
        case 'current-stage':
            $query->orderBy('careconsole_stages.display_name', $sortType);
            break;
        default:
        }
        return $query;
    }


    public static function getNetworkStateActivityData($filter, $field_list)
    {
        $result = array();
        foreach ($field_list as $field_value) {
            $result[$field_value['display_name']] = self::networkStateActivityFieldValue($field_value['name'], $filter);
        }
        return $result;
    }

    public static function networkStateActivityFieldValue($field, $filter)
    {
        switch ($field) {
        case 'total_member':
            $query = self::query();
            $query->networkCheck($filter['network_id']);
            $query->whereHas('patient', function ($sub_query) use ($filter) {
                if ($filter['state_list']) {
                    $sub_query->statePatients($filter['state_list']);
                }
            });
            return $query->count();
            break;
        case 'member_called':
            $query = self::query();
            $query->networkCheck($filter['network_id']);
            $query->whereHas('patient', function ($sub_query) use ($filter) {
                if ($filter['state_list']) {
                    $sub_query->statePatients($filter['state_list']);
                }
            });
            $query->whereHas('contactHistory', function ($sub_query) use ($filter) {
                $sub_query->activityRange($filter['start_date'], $filter['end_date']);
                $sub_query->whereHas('action', function ($sub_sub_query) {
                    $sub_sub_query->actionCheck(['request-patient-email', 'request-patient-phone', 'request-patient-sms', 'contact-attempted-by-phone', 'contact-attempted-by-email', 'contact-attempted-by-other', 'contact-attempted-by-mail', 'schedule', 'manually-schedule', 'previously-scheduled']);
                });
            });
            return $query->count();
            break;
        case 'member_reached':
            $query = self::query();
            $query->networkCheck($filter['network_id']);
            $query->whereHas('patient', function ($sub_query) use ($filter) {
                if ($filter['state_list']) {
                    $sub_query->statePatients($filter['state_list']);
                }
            });
            $query->whereHas('contactHistory', function ($sub_query) use ($filter) {
                $sub_query->activityRange($filter['start_date'], $filter['end_date']);
                $sub_query->where(function($sub_sub_query) {
                    $sub_sub_query->whereHas('action', function ($sub_sub_sub_query) {
                    $sub_sub_sub_query->actionCheck(['schedule', 'manually-schedule', 'previously-scheduled']);
                    });
                    $sub_sub_query->orWhereHas('actionResult', function ($sub_sub_sub_query) {
                        $sub_sub_sub_query->actionResultCheck(['recall-later', 'patient-declined-services', 'already-seen-by-outside-dr', 'no-need-to-schedule', 'no-insurance', 'would-not-validate-dob', 'unaware-of-diagnosis', 'other-reasons-for-declining']);
                    });
                });
            });
            return $query->count();
            break;
        case 'members_archived':
            $query = self::query();
            $query->networkCheck($filter['network_id']);
            $query->whereHas('patient', function ($sub_query) use ($filter) {
                if ($filter['state_list']) {
                    $sub_query->statePatients($filter['state_list']);
                }
            });
            $query->whereHas('contactHistory', function ($sub_query) use ($filter) {
                $sub_query->activityRange($filter['start_date'], $filter['end_date']);
                $sub_query->where(function($sub_sub_query) {
                    $sub_sub_query->whereHas('action', function ($sub_sub_sub_query) {
                    $sub_sub_sub_query->actionCheck(['archive']);
                    });
                    $sub_sub_query->orWhereHas('actionResult', function ($sub_sub_sub_query) {
                        $sub_sub_sub_query->actionResultCheck(['recall-later', 'patient-declined-services', 'already-seen-by-outside-dr', 'no-need-to-schedule', 'no-insurance', 'would-not-validate-dob', 'unaware-of-diagnosis', 'other-reasons-for-declining', 'closed', 'incomplete']);
                    });
                });
            });
            return $query->count();
            break;
        case 'in_network_past_appointments':
            $query = ContactHistory::query();
            $query->whereHas('careconsole', function ($sub_query) use ($filter) {
                $sub_query->networkCheck($filter['network_id']);
                $sub_query->whereHas('patient', function ($sub_sub_query) use ($filter) {
                    if ($filter['state_list']) {
                        $sub_sub_query->statePatients($filter['state_list']);
                    }
                });
            });
            $query->activityRange($filter['start_date'], $filter['end_date']);
            $query->whereHas('appointments', function ($sub_query) use ($filter) {
                $sub_query->where('start_datetime', '<=', $filter['end_date']);
            });
            $query->whereHas('appointments.practice', function ($sub_query) {
                $sub_query->whereNull('manually_created');
            });
            $query->whereHas('action', function ($sub_query) {
                $sub_query->actionCheck(['schedule', 'manually-schedule', 'previously-scheduled', 'reschedule', 'manually-reschedule']);
            });
            return $query->count();
            break;
        case 'out_network_past_appointments':
            $query = ContactHistory::query();
            $query->whereHas('careconsole', function ($sub_query) use ($filter) {
                $sub_query->networkCheck($filter['network_id']);
                $sub_query->whereHas('patient', function ($sub_sub_query) use ($filter) {
                    if ($filter['state_list']) {
                        $sub_sub_query->statePatients($filter['state_list']);
                    }
                });
            });
            $query->activityRange($filter['start_date'], $filter['end_date']);
            $query->whereHas('appointments', function ($sub_query) use ($filter) {
                $sub_query->where('start_datetime', '<=', $filter['end_date']);
            });
            $query->whereHas('appointments.practice', function ($sub_query) {
                $sub_query->where('manually_created', '1');
            });
            $query->whereHas('action', function ($sub_query) {
                $sub_query->actionCheck(['schedule', 'manually-schedule', 'previously-scheduled', 'reschedule', 'manually-reschedule']);
            });
            return $query->count();
            break;
        case 'in_network_future_appointment':
            $query = ContactHistory::query();
            $query->whereHas('careconsole', function ($sub_query) use ($filter) {
                $sub_query->networkCheck($filter['network_id']);
                $sub_query->whereHas('patient', function ($sub_sub_query) use ($filter) {
                    if ($filter['state_list']) {
                        $sub_sub_query->statePatients($filter['state_list']);
                    }
                });
            });
            $query->activityRange($filter['start_date'], $filter['end_date']);
            $query->whereHas('appointments', function ($sub_query) use ($filter) {
                $sub_query->where('start_datetime', '>', $filter['end_date']);
            });
            $query->whereHas('appointments.practice', function ($sub_query) {
                $sub_query->whereNull('manually_created');
            });
            $query->whereHas('action', function ($sub_query) {
                $sub_query->actionCheck(['schedule', 'manually-schedule', 'previously-scheduled', 'reschedule', 'manually-reschedule']);
            });
            return $query->count();
            break;
        case 'out_network_future_appointment':
            $query = ContactHistory::query();
            $query->whereHas('careconsole', function ($sub_query) use ($filter) {
                $sub_query->networkCheck($filter['network_id']);
                $sub_query->whereHas('patient', function ($sub_sub_query) use ($filter) {
                    if ($filter['state_list']) {
                        $sub_sub_query->statePatients($filter['state_list']);
                    }
                });
            });
            $query->activityRange($filter['start_date'], $filter['end_date']);
            $query->whereHas('appointments', function ($sub_query) use ($filter) {
                $sub_query->where('start_datetime', '>', $filter['end_date']);
            });
            $query->whereHas('appointments.practice', function ($sub_query) {
                $sub_query->where('manually_created', '1');
            });
            $query->whereHas('action', function ($sub_query) {
                $sub_query->actionCheck(['schedule', 'manually-schedule', 'previously-scheduled', 'reschedule', 'manually-reschedule']);
            });
            return $query->count();
            break;
        case 'appointments_kept':
            $query = ContactHistory::query();
            $query->whereHas('careconsole', function ($sub_query) use ($filter) {
                $sub_query->networkCheck($filter['network_id']);
                $sub_query->whereHas('patient', function ($sub_sub_query) use ($filter) {
                    if ($filter['state_list']) {
                        $sub_sub_query->statePatients($filter['state_list']);
                    }
                });
            });
            $query->activityRange($filter['start_date'], $filter['end_date']);
            $query->whereHas('action', function ($sub_query) {
                $sub_query->actionCheck(['kept-appointment']);
            });
            return $query->count();
            break;
        case 'appointments_rescheduled':
            $query = ContactHistory::query();
            $query->whereHas('careconsole', function ($sub_query) use ($filter) {
                $sub_query->networkCheck($filter['network_id']);
                $sub_query->whereHas('patient', function ($sub_sub_query) use ($filter) {
                    if ($filter['state_list']) {
                        $sub_sub_query->statePatients($filter['state_list']);
                    }
                });
            });
            $query->activityRange($filter['start_date'], $filter['end_date']);
            $query->whereHas('action', function ($sub_query) {
                $sub_query->actionCheck(['manually-reschedule']);
            });
            return $query->count();
            break;
        default:
        }
    }
}
