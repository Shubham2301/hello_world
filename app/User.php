<?php

namespace myocuhub;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use myocuhub\Models\UserLevel;
use myocuhub\User;

class User extends Model implements AuthenticatableContract,
AuthorizableContract,
CanResetPasswordContract {
	use Authenticatable, Authorizable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'sesemail',
		'password', 'firstname', 'lastname', 'npi', 'cellphone', 'state',
		'address1', 'address2', 'city', 'zip'];
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function roles() {
		return $this->belongsToMany(Role::class);
	}

	public function hasRole($role) {
		if (is_string($role)) {
			return $this->roles->contains('name', $role);
		}

		return !!$role->intersect($this->roles)->count();

		//     foreach ($role as $r) {
		//         if($this->hasRole($r->name))
		//         {
		//             return true;
		//         }
		//     }
		//     return false;

	}

	public function assign($role) {
		if (is_string($role)) {
			return $this->roles()->save(
				Role::whereName($role)->firstOrFail()
			);
		}

		return $this->roles()->save($role);
	}
	public function UserLevel() {
		return $this->belongsTo(Models\UserLevel::class);
	}

	public function usertype() {
		return $this->belongsTo(Usertype::class);
	}

	public static function providers($filters) {

		$query = self::query()
			->leftjoin('practice_user', 'users.id', '=', 'practice_user.user_id')
			->leftjoin('practices', 'practice_user.practice_id', '=', 'practices.id')
			->leftjoin('practice_location', 'practice_user.practice_id', '=', 'practice_location.practice_id')
			->where('level', 3)
			->where(function ($query) use ($filters) {
				foreach ($filters as $filter) {
					$query->where(function ($query) use ($filter) {
						switch ($filter['type']) {
							case 'pratice_name':
								$query->where('practices.name', 'LIKE', '%' . $filter['value'] . '%');
								break;
							case 'location':
								$query->where('practice_location.city', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('practice_location.state', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('addressline1', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('addressline2', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('locationname', 'LIKE', '%' . $filter['value'] . '%');
								break;
							case 'zip':
								$query->where('practice_location.zip', $filter['value']);
								break;
							case 'provider_name':
								$query->where('firstname', 'LIKE', '%' . $filter['value'] . '%')
								->orwhere('users.name', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('middlename', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('lastname', 'LIKE', '%' . $filter['value'] . '%');
								break;
							case 'all':
								$query->where('practices.name', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('practice_location.city', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('practice_location.state', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('addressline1', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('addressline2', 'LIKE', '%' . $filter['value'] . '%')
								->orwhere('practice_location.zip', $filter['value'])
								->orwhere('firstname', 'LIKE', '%' . $filter['value'] . '%')
								->orwhere('users.name', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('middlename', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('lastname', 'LIKE', '%' . $filter['value'] . '%')
								->orWhere('locationname', 'LIKE', '%' . $filter['value'] . '%')
								->where('practice_location.zip', $filter['value']);
								break;

						}
					});
				}
			})
			->groupBy('users.id');

		if (session('user-level') == 1) {
			return $query
				->leftjoin('practice_network', 'practices.id', '=', 'practice_network.practice_id')
				->get(['*', 'practices.id']);
		} else {
			return $query
				->leftjoin('practice_network', 'practices.id', '=', 'practice_network.practice_id')
				->where('practice_network.network_id', session('network-id'))
				->get(['*', 'practices.id']);
		}
	}

	public static function practiceUserById($practice_id) {
		return self::query()
			->leftjoin('practice_user', 'users.id', '=', 'practice_user.user_id')
			->leftjoin('practices', 'practice_user.practice_id', '=', 'practices.id')
			->where('practice_id', $practice_id)
			->get();

	}
	public static function getNetwork($userID) {
		return self::query()
			->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
			->leftjoin('networks', 'network_user.network_id', '=', 'networks.id')
			->where('user_id', $userID)
			->first();
	}

	public static function getPractice($userID) {
		return self::query()
			->leftjoin('practice_user', 'users.id', '=', 'practice_user.user_id')
			->leftjoin('practices', 'practice_user.practice_id', '=', 'practices.id')
			->where('user_id', $userID)
			->first();
	}

	public static function getUsersByName($search_val) {

		return self::query()
			->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
			->where('network_user.network_id', session('network-id'))
			->where(function ($query) use ($search_val) {
				$query->where(function ($query) use ($search_val) {
					$query->where('firstname', 'LIKE', '%' . $search_val . '%')
					->where('active', '=', '1');
				})
				->orWhere(function ($query) use ($search_val) {
					$query->where('middlename', 'LIKE', '%' . $search_val . '%')
					->where('active', '=', '1');
				})
				->orWhere(function ($query) use ($search_val) {
					$query->where('lastname', 'LIKE', '%' . $search_val . '%')
					->where('active', '=', '1');
				});
			});
	}

	public static function get4PCProviderNPIs() {

		return self::whereNotNull('npi')
			->whereNotNull('acc_key')
			->where('level', 3)
			->get(['id', 'npi']);
	}
}
