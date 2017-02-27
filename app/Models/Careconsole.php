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
    public static function getBucketPatientsExcelDataModel($networkID, $bucketName)
    {
        $query = self::query();
        switch ($bucketName) {
            case 'archived':
              $query->whereNotNull('archived_date');
              break;
            case 'recall':
              $query->whereNotNull('recall_date');
              break;
            case 'priority':
              $query->where('priority', '1');
              break;
            default:
              break;
        }
        $query->whereHas('importHistory', function ($query) use ($networkID) {
            $query->where('network_id', $networkID);
          })
          ->has('patient')
          ->with(['patient', 'appointment']);
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
}
