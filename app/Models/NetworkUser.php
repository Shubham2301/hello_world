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
                       ->get(['users.name AS user_name', 'users.sesemail AS direct_address', 'users.ses_username', 'provider_types.name AS provider_type', 'practices.name AS practice_name', 'userlevels.name AS userlevel_name', 'usertypes.name AS usertypes_name','users.active AS user_status', 'users.email AS user_email', 'users.created_at AS created_at', 'users.updated_at AS updated_at', 'users.npi', 'users.acc_key', 'networks.name AS network_name']);
    }

    public static function updateUserNetworks($user_id, $network_update_list)
    {
        $user_networks = self::where('user_id', $user_id)->get(['network_id'])->toArray();
        $network_list = array();
        foreach ($user_networks as $network) {
            $network_list[] = $network['network_id'];
        }

        $removed_network = array_diff($network_list, $network_update_list);

        self::removeUserNetworks($user_id, $removed_network);

        foreach ($network_update_list as $network_id) {
            self::firstOrCreate(['user_id' => $user_id, 'network_id' => $network_id]);
        }

        return true;
    }

    public static function removeUserNetworks($user_id, $remove_network_list)
    {
        self::where('user_id', $user_id)
            ->whereIn('network_id', $remove_network_list)
            ->delete();
    }
}
