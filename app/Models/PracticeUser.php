<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PracticeUser extends Model
{
    protected $table = 'practice_user';
	protected $fillable = ['practice_id', 'user_id'];

    public function practice()
    {
        return $this->belongsTo('myocuhub\Models\Practice')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('myocuhub\User');
    }

    public static function getPracticeUsersList($practice_id) {
        $practice_user_list =  self::where('practice_id', $practice_id)->get(['user_id'])->toArray();
        $user_list = array();
        foreach ($practice_user_list as $practice_user) {
            $user_list[] = $practice_user['user_id'];
        }
        return $user_list;
    }
}

