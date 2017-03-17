<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkUser extends Model
{
    protected $table = 'network_user';
    protected $fillable = ['network_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('myocuhub\User');
    }

    public function network()
    {
        return $this->belongsTo('myocuhub\Network');
    }

    public static function networkUserData($networkID)
    {
        return NetworkUser::query()
                       ->where(function ($query) use ($networkID) {
                           if ($networkID) {
                               $query->where('network_id', $networkID);
                           }
                       })
                       ->leftjoin('users', 'network_user.user_id', '=', 'users.id')
                       ->leftjoin('provider_types', 'users.provider_type_id', '=', 'provider_types.id')
                       ->leftjoin('practice_user', 'users.id', '=', 'practice_user.user_id')
                       ->leftjoin('practices', 'practice_user.practice_id', '=', 'practices.id')
                       ->leftjoin('userlevels', 'users.level', '=', 'userlevels.id')
                       ->leftjoin('usertypes', 'users.usertype_id', '=', 'usertypes.id')
                       ->leftjoin('networks', 'networks.id', '=', 'network_user.network_id')
                       ->get(['users.name AS user_name', 'users.sesemail AS direct_address', 'users.ses_username', 'provider_types.name AS provider_type', 'practices.name AS practice_name', 'userlevels.name AS userlevel_name', 'usertypes.name AS usertypes_name','users.active AS user_status', 'users.email AS user_email', 'users.npi', 'networks.name AS network_name']);
    }
}
