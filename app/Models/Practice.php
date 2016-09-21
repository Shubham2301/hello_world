<?php

namespace myocuhub\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    protected $fillable = ['name', 'email'];

    public function locations()
    {
        return $this->hasMany('myocuhub\Models\PracticeLocation');
    }

    public function practiceUsers()
    {
        return $this->hasMany('myocuhub\Models\PracticeUser');
    }
    public function practiceNetwork()
    {
        return $this->hasMany('myocuhub\Models\PracticeNetwork');
    }

    public static function getPracticeByUserID($userID)
    {
        return  self::query()
            ->rightjoin('practice_user', 'practices.id', '=', 'practice_user.practice_id')
            ->where('user_id', $userID)
            ->get(['practices.id', 'practices.name', 'practices.email']);
    }
}
