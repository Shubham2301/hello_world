<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Careconsole extends Model {
	protected $table = "careconsole";

	/**
	 * @return mixed
	 */
	public function patient() {
		return $this->hasOne('myocuhub\Patient');
	}

	/**
	 * @return mixed
	 */
	public function importHistory() {
		return $this->hasOne('myocuhub\Models\ImportHistory');
	}

	/**
	 * @return mixed
	 */
	public function contactHistory() {
		return $this->hasMany('myocuhub\Models\ContactHistory');
	}

	/**
	 * @return mixed
	 */
	public function referralHistory() {
		return $this->hasMany('myocuhub\Models\ReferralHistory');
	}

	/**
	 * @return mixed
	 */
	public function appointment() {
		return $this->hasOne('myocuhub\Models\Appointments');
	}

	/**
	 * @param $networkID
	 */
	public static function getArchivedPatients($networkID) {
		return self::whereNotNull('archived_date')
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
			->get(['*', 'careconsole.id', 'careconsole.created_at']);
	}
    
    /**
	 * @param $networkID
	 */
	public static function getRecallPatients($networkID) {
		return self::whereNotNull('recall_date')
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
			->get(['*', 'careconsole.id', 'careconsole.created_at']);
	}
    
	/**
	 * @param $networkID
	 */
	public static function getPriorityPatients($networkID) {
		return self::where('priority', 1)
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->whereNull('archived_date')
			->whereNull('recall_date')
			->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
			->get(['*', 'careconsole.id', 'careconsole.created_at']);
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 */
	public static function getStagePatients($networkID, $stageID) {
		return self::where('stage_id', $stageID)
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->whereNull('archived_date')
			->whereNull('recall_date')
			->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
			->get(['*', 'careconsole.id', 'careconsole.created_at']);
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 */
	public static function getStageCount($networkID, $stageID) {
		return self::where('stage_id', $stageID)
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->whereNull('archived_date')
			->whereNull('recall_date')
			->count();
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 */
	public static function getContactAttemptedCount($networkID, $stageID) {
		return self::where('stage_id', $stageID)
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->whereExists(function ($query) {
				$query->select(DB::raw(1))
				->from('contact_history')
				->whereRaw('contact_history.console_id = careconsole.id');
			})
			->whereNull('archived_date')
			->whereNull('recall_date')
			->count();
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 */
	public static function getContactPendingCount($networkID, $stageID) {
		return self::where('stage_id', $stageID)
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->join('contact_history', 'careconsole.id', '=', 'contact_history.console_id', 'left outer')
			->whereNull('archived_date')
			->whereNull('recall_date')
			->whereNull('console_id')
			->count();
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */
	public static function getAppointmentScheduledCount($networkID, $stageID) {
		$sqlResult = DB::select("select count(*) as count from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	left join `import_history`
            	on `import_history`.`id` = `careconsole`.`import_id`
            	where `import_history`.`network_id` = $networkID and
            	`stage_id` = $stageID and
            	`careconsole`.`archived_date` is null and
            	`careconsole`.`recall_date` is null and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) > 1");

		return $sqlResult[0]->count;
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */
	public static function getAppointmentTomorrowCount($networkID, $stageID) {
		$sqlResult = DB::select("select count(*) as count from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	left join `import_history`
            	on `import_history`.`id` = `careconsole`.`import_id`
            	where `import_history`.`network_id` = $networkID and
            	`stage_id` = $stageID and
            	`careconsole`.`archived_date` is null and
            	`careconsole`.`recall_date` is null and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) = 1");

		return $sqlResult[0]->count;
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 */
	public static function getPastAppointmentCount($networkID, $stageID) {
		$sqlResult = DB::select("select count(*) as count from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	left join `import_history`
            	on `import_history`.`id` = `careconsole`.`import_id`
            	where `import_history`.`network_id` = $networkID and
            	`stage_id` = $stageID and
            	`careconsole`.`archived_date` is null and
            	`careconsole`.`recall_date` is null and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) < 0");

		return $sqlResult[0]->count;
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 * @return null
	 */
	public static function getPendingInformationCount($networkID, $stageID) {
		return;
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 * @param $statusName
	 * @return null
	 */
	public static function getAppointmentStatusCount($networkID, $stageID, $statusName) {
		return self::where('stage_id', $stageID)
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->leftjoin('appointments', 'careconsole.appointment_id', '=', 'appointments.id')
			->leftjoin('kpis', 'appointments.appointment_status', '=', 'kpis.id')
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
	public static function getStageWaitingCount($networkID, $stageID) {

		$sqlResult = DB::select("select count(*) as count from `careconsole`
            	left join `import_history`
            	on `import_history`.`id` = `careconsole`.`import_id`
            	where `import_history`.`network_id` = $networkID and
            	`stage_id` = $stageID and
            	`careconsole`.`archived_date` is null and
            	`careconsole`.`recall_date` is null and
            	datediff(CURRENT_TIMESTAMP, `careconsole`.`stage_updated_at`) < 5");

		return $sqlResult[0]->count;
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */
	public static function getStageOverdueCount($networkID, $stageID) {

		$sqlResult = DB::select("select count(*) as count from `careconsole`
            	left join `import_history`
            	on `import_history`.`id` = `careconsole`.`import_id`
            	where `stage_id` = $stageID and
            	`import_history`.`network_id` = $networkID and
            	`careconsole`.`archived_date` is null and
            	`careconsole`.`recall_date` is null and
            	datediff(CURRENT_TIMESTAMP, `careconsole`.`stage_updated_at`) > 4");

		return $sqlResult[0]->count;
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 */
	public static function getContactAttemptedPatients($networkID, $stageID) {
		return self::where('stage_id', $stageID)
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->whereExists(function ($query) {
				$query->select(DB::raw(1))
				->from('contact_history')
				->whereRaw('contact_history.console_id = careconsole.id');
			})
			->whereNull('archived_date')
			->whereNull('recall_date')
			->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
			->get(['*', 'careconsole.id', 'careconsole.created_at']);
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 */
	public static function getContactPendingPatients($networkID, $stageID) {
		return self::where('stage_id', $stageID)
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->leftjoin('contact_history', 'careconsole.id', '=', 'contact_history.console_id')
			->whereNull('console_id')
			->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
			->whereNull('archived_date')
			->whereNull('recall_date')
			->get(['*', 'careconsole.id', 'careconsole.created_at']);
	}

	/**
	 * @param $networkID
	 * @param $stageID
	 * @return mixed
	 */
	public static function getAppointmentScheduledPatients($networkID, $stageID) {
		$sqlResult = DB::select("select *, `careconsole`.id, `careconsole`.created_at from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	left join `patients`
            	on `careconsole`.`patient_id` = `patients`.`id`
            	where `stage_id` = $stageID and
            	`careconsole`.`archived_date` is null and
            	`careconsole`.`recall_date` is null and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) > 1");

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
	public static function getAppointmentTomorrowPatients($networkID, $stageID) {
		$sqlResult = DB::select("select *, `careconsole`.id, `careconsole`.created_at from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	left join `patients`
            	on `careconsole`.`patient_id` = `patients`.`id`
            	where `stage_id` = $stageID and
            	`careconsole`.`archived_date` is null and
            	`careconsole`.`recall_date` is null and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) = 1");

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
	public static function getPastAppointmentPatients($networkID, $stageID) {
		$sqlResult = DB::select("select *, `careconsole`.id, `careconsole`.created_at from `careconsole`
            	left join `appointments`
            	on `careconsole`.`appointment_id` = `appointments`.`id`
            	left join `patients`
            	on `careconsole`.`patient_id` = `patients`.`id`
            	where `stage_id` = $stageID and
            	`careconsole`.`archived_date` is null and
            	`careconsole`.`recall_date` is null and
            	datediff(`start_datetime`, CURRENT_TIMESTAMP) < 0");

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
	public static function getAppointmentStatusPatients($networkID, $stageID, $statusName) {
		return self::where('stage_id', $stageID)
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', $networkID)
			->leftjoin('appointments', 'careconsole.appointment_id', '=', 'appointments.id')
			->leftjoin('kpis', 'appointments.appointment_status', '=', 'kpis.id')
			->where('kpis.name', '=', $statusName)
			->whereNull('archived_date')
			->whereNull('recall_date')
			->leftjoin('patients', 'careconsole.patient_id', '=', 'patients.id')
			->get(['*', 'careconsole.id', 'careconsole.created_at']);
	}

}
