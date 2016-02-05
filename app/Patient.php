<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model {

	protected $fillable = ['title', 'firstname', 'lastname', 'workphone', 'homephone', 'cellphone', 'email', 'addressline1', 'addressline2', 'city',
		'zip', 'lastfourssn', 'birthdate', 'gender', 'insurancecarrier', 'country', 'preferredlanguage'];
	public static function getPatients($filters) {
		return self::where(function ($query) use ($filters) {
			foreach ($filters as $filter) {
				$query->where(function ($query) use ($filter) {
					switch ($filter['type']) {
						case 'name':
							$query->where('firstname', $filter['value'])
							->orWhere('middlename', $filter['value'])
							->orWhere('lastname', $filter['value']);
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
							$query->where('firstname', $filter['value'])
							->orWhere('middlename', $filter['value'])
							->orWhere('lastname', $filter['value'])
							->orWhere('lastfourssn', $filter['value'])
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
		})
			->get();

	}

}
