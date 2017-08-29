<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use myocuhub\Models\PracticeUser;

class PracticeNetwork extends Model
{
    protected $table = 'practice_network';
    protected $fillable = ['network_id','practice_id'];

    /**
     * @return mixed
     */
    public function practice()
    {
        return $this->belongsTo('myocuhub\Models\Practice');
    }

    public function network()
    {
        return $this->belongsTo('myocuhub\Network');
    }

    public static function updatePracticeNetworks($practice_id, $network_update_list)
    {
        $practice_networks = self::where('practice_id', $practice_id)->get(['network_id'])->toArray();
        $network_list = array();
        foreach ($practice_networks as $network) {
            $network_list[] = $network['network_id'];
        }

        $removed_network = array_diff($network_list, $network_update_list);

        self::removePracticeNetworks($practice_id, $removed_network);

        foreach ($network_update_list as $network_id) {
            self::firstOrCreate(['practice_id' => $practice_id, 'network_id' => $network_id]);
        }

        return true;
    }

    public static function removePracticeNetworks($practice_id, $remove_network_list)
    {
        self::where('practice_id', $practice_id)
            ->whereIn('network_id', $remove_network_list)
            ->delete();

        $user_list = PracticeUser::getPracticeUsersList($practice_id);

        foreach ($user_list as $user_id) {
            NetworkUser::removeUserNetworks($user_id, $remove_network_list);
        }
    }
}
