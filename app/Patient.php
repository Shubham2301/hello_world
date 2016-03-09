<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model {

	protected $fillable = ['title', 'firstname', 'lastname', 'workphone', 'homephone', 'cellphone', 'email', 'addressline1', 'addressline2', 'city',
		'zip', 'lastfourssn', 'birthdate', 'gender', 'insurancecarrier', 'country', 'preferredlanguage', 'state'];
	public static function getPatients($filters) {

		$query = self::where(function ($query) use ($filters) {
			foreach ($filters as $filter) {
				$query->where(function ($query) use ($filter) {
					switch ($filter['type']) {
						case 'name':
							$query->where('firstname', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('middlename', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('lastname', 'LIKE', '%' . $filter['value'] . '%');
							break;

						case 'ssn':
							$query->where('lastfourssn', $filter['value']);
							break;
						case 'email':
							$query->where('email', 'LIKE', '%' . $filter['value'] . '%');
							break;
						case 'phone':
							$query->where('cellphone', 'LIKE', '%' . $filter['value'] . '%');
							break;
						case 'address':
							$query->where('city', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('addressline1', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('addressline2', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('country', 'LIKE', '%' . $filter['value'] . '%');
							break;

						case 'all':
							$query->where('firstname', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('middlename', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('lastname', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('lastfourssn', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('city', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('addressline1', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('addressline2', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('country', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('cellphone', 'LIKE', '%' . $filter['value'] . '%')
							->orWhere('email', 'LIKE', '%' . $filter['value'] . '%');

							break;
					}
				});
			}
		});

		if (session('user-level') == 1) {
			return $query
				->orderBy('lastname', 'asc')
				->paginate(5);
		} else {
			return $query
				->leftjoin('careconsole', 'patients.id', '=', 'careconsole.patient_id')
				->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
				->where('import_history.network_id', session('network-id'))
				->orderBy('lastname', 'asc')
				->paginate(5);
		}

	}

	public static function getPatientsByName($name) {
		return self::query()
			->leftjoin('careconsole', 'patients.id', '=', 'careconsole.patient_id')
			->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
			->where('import_history.network_id', session('network-id'))
			->where(function ($query) use ($name) {
				$query->where('firstname', 'LIKE', '%' . $name . '%')
				->orWhere('middlename', 'LIKE', '%' . $name . '%')
				->orWhere('lastname', 'LIKE', '%' . $name . '%');
			})
			->get(['*', 'patients.id']);
	}
	public static function getPreviousProvider($patientID) {
		return self::where('patients.id', $patientID)
			->leftjoin('appointments', 'patients.id', '=', 'appointments.patient_id')
			->orderBy('start_datetime', 'DESC')
			->leftjoin('users', 'appointments.provider_id', '=', 'users.id')
			->leftjoin('practices', 'appointments.practice_id', '=', 'practices.id')
			->first();
	}

	public static function getColumnNames() {
		$patients = \Schema::getColumnListing('patients');
		$dummy_array = array_fill_keys(array_keys($patients), null);
		return array_combine($patients, $dummy_array);
	}

	public static function getPreviousProvidersList($patientID) {
		return self::where('patients.id', $patientID)
			->leftjoin('appointments', 'patients.id', '=', 'appointments.patient_id')
			->orderBy('start_datetime', 'DESC')
			->leftjoin('users', 'appointments.provider_id', '=', 'users.id')
			->leftjoin('practices', 'appointments.practice_id', '=', 'practices.id')
			->leftjoin('practice_location', 'appointments.location_id', '=', 'practice_location.id')
			->groupBy('users.id')
			->get();
	}

}
