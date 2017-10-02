<?php

namespace myocuhub;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Auth;
use Illuminate\Support\Facades\DB;
use myocuhub\Http\Controllers\Traits\TwoFactorAuthenticatable;
use myocuhub\Models\NetworkUser;
use myocuhub\Models\PracticeUser;
use myocuhub\Models\ProviderType;
use myocuhub\Models\UserLevel;

class User extends Model implements
    AuthenticatableContract,
AuthorizableContract,
CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, TwoFactorAuthenticatable;

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
        'address1', 'address2', 'city', 'zip', 'usertype_id', 'level', 'acc_key', 'menu_id', 'active'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function userNetwork()
    {
        return $this->hasMany(NetworkUser::class);
    }

    public function userPractice()
    {
        return $this->hasOne(PracticeUser::class);
    }

    public function userRoles()
    {
        return $this->hasMany(Role_user::class);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !!$role->intersect($this->roles)->count();
    }

    public function administrationAccess()
    {
        return ($this->hasRole('user-admin') || $this->hasRole('patient-admin') || $this->hasRole('practice-admin') || $this->isSuperAdmin());
    }

    public function assign($role)
    {
        if (is_string($role)) {
            return $this->roles()->save(
                Role::whereName($role)->firstOrFail()
            );
        }

        return $this->roles()->save($role);
    }
    public function UserLevel()
    {
        return $this->belongsTo(Models\UserLevel::class);
    }

    public function checkUserLevel($level)
    {
        $userlevel = UserLevel::find($this->level);
        return $userlevel->name == $level;
    }

    public function usertype()
    {
        return $this->belongsTo(Usertype::class);
    }

    public function network()
    {
        return $this->hasOne(NetworkUser::class, 'user_id');
    }

    public function providerType()
    {
        return $this->belongsTo(ProviderType::class);
    }

    public function providerTypeName()
    {
        return $this->providerType ? $this->providerType->name : self::notSet();
    }

    public static function providers($filters, $network = null)
    {
        $query = self::query()
            ->leftjoin('practice_user', 'users.id', '=', 'practice_user.user_id')
            ->leftjoin('practices', 'practice_user.practice_id', '=', 'practices.id')
            ->leftjoin('practice_location', 'practice_user.practice_id', '=', 'practice_location.practice_id')
            ->where('usertype_id', 1)
            ->where('active', '1')
            ->whereNull('practices.deleted_at')
            ->whereNull('practice_location.deleted_at')
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
                            case 'specialty':
                                $query->where('speciality', 'NOT LIKE', '');
                                break;
                            case 'provider_types':
                                $query->where('provider_type_id', null);
                                foreach ($filter['value'] as $type) {
                                    $query->orWhere('provider_type_id', $type);
                                }
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
                                    ->orwhere('practice_location.zip', $filter['value']);
                                break;

                        }
                    });
                }
            })
            ->groupBy('users.id');

        return $query
            ->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
            ->where('network_user.network_id', $network)
            ->get(['*', 'practices.id']);
    }

    public function contactHistory()
    {
        return $this->hasMany('myocuhub\Models\ContactHistory', 'user_id');
    }

    public static function practiceUserById($practice_id, $network_id = null)
    {
        $query = self::query()
            ->leftjoin('practice_user', 'users.id', '=', 'practice_user.user_id')
            ->leftjoin('practices', 'practice_user.practice_id', '=', 'practices.id')
            ->where('practice_id', $practice_id)
            ->where('active', '1');
        if ($network_id) {
            $query->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
                ->where('network_id', $network_id);
        }
        return $query->get(['*', 'users.*']);
    }

    public static function networkUserById($network_id)
    {
        return self::query()
            ->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
            ->leftjoin('networks', 'network_user.network_id', '=', 'networks.id')
            ->where('network_id', $network_id)
            ->get(['users.id', 'users.name', 'users.sesemail']);
    }

    public static function getPractice($userID)
    {
        return self::query()
            ->leftjoin('practice_user', 'users.id', '=', 'practice_user.user_id')
            ->leftjoin('practices', 'practice_user.practice_id', '=', 'practices.id')
            ->where('user_id', $userID)
            ->first();
    }

    public static function getUsersByName($search_val, $filter = null)
    {
        return self::query()
            ->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
            ->where('network_user.network_id', session('network-id'))
            ->where(function ($query) use ($search_val, $filter) {
                if (!$filter) {
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
                } else {
                    $query->where('firstname', 'LIKE', '%' . $search_val . '%')
                        ->orwhere('middlename', 'LIKE', '%' . $search_val . '%')
                        ->orwhere('lastname', 'LIKE', '%' . $search_val . '%');
                }
            });
    }

    public static function get4PCProviderNPIs()
    {
        return self::whereNotNull('npi')
            ->whereNotNull('acc_key')
            ->where('usertype_id', 1)
            ->where('active', '1')
            ->get(['id', 'npi']);
    }

    public static function practiceProvidersById($practice_id, $network_id = null)
    {
        $query = self::query();

        $query->whereHas('userPractice', function ($sub_query) use ($practice_id) {
            $sub_query->where('practice_id', $practice_id);
            $sub_query->whereHas('practice', function ($sub_sub_query) {
                $sub_sub_query->where('active', '1');
            });
        });

        if ($network_id) {
            $query->whereHas('network', function ($sub_query) use ($network_id) {
                $sub_query->where('network_id', $network_id);
            });
        } else {
            $user = Auth::user();
            $query->whereHas('network', function ($sub_query) use ($user) {
                foreach ($user->userNetwork as $network) {
                    $sub_query->orWhere('network_id', $network->network_id);
                }
            });
        }

        $query->where('active', '1');
        $query->where('usertype_id', 1);

        return $query->get();
    }

    public static function networkProvidersById($network_id)
    {
        return self::query()
            ->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
            ->leftjoin('networks', 'network_user.network_id', '=', 'networks.id')
            ->where('network_id', $network_id)
            ->where('active', '1')
            ->where('usertype_id', 1)
            ->get(['users.id', 'users.name', 'users.sesemail']);
    }

    public function isSuperAdmin()
    {
        return $this->level == 1;
    }

    public function getName($type = 'system_format')
    {
        if ($type == 'print_format') {
            return trim($this->title . ' ' . $this->firstname . ' ' . $this->lastname);
        }
        return $this->lastname . ', ' . $this->firstname;
    }

    public static function getCareConsoledata($networkID, $startDate, $endDate)
    {
        return self::whereHas('userNetwork', function ($query) use ($networkID) {
            $query->where('network_id', $networkID);
        })
            ->whereHas('userRoles', function ($query) {
                $query->where('role_id', 12);
            })
            ->with(['contactHistory' => function ($sub_query) use ($startDate, $endDate) {
                $sub_query->whereNotNull('user_id');
                $sub_query->activityRange($startDate, $endDate);
                $sub_query->has('careconsole.patient');
                $sub_query->whereHas('action', function ($sub_sub_query) {
                    $sub_sub_query->actionCheck(['schedule', 'manually-schedule', 'previously-scheduled', 'reschedule', 'manually-reschedule', 'request-patient-email', 'request-patient-phone', 'request-patient-sms', 'contact-attempted-by-phone', 'contact-attempted-by-email']);
                });
            },
                'contactHistory.action',
                'contactHistory.actionResult',
                'contactHistory.currentStage',
                'contactHistory.previousStage',
                'contactHistory.appointments',
            ])
            ->get();
    }

    public static function getCareCoordinatorCount($networkID)
    {
        return self::whereHas('userNetwork', function ($query) use ($networkID) {
            $query->where('network_id', $networkID);
        })
            ->whereHas('userRoles', function ($query) {
                $query->where('role_id', 12);
            })
            ->count();
    }
}
