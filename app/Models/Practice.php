<?php

namespace myocuhub\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Practice extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'email', 'manually_created'];

    public function locations()
    {
        return $this->hasMany('myocuhub\Models\PracticeLocation');
    }

    public function practiceUsers()
    {
        return $this->hasMany('myocuhub\Models\PracticeUser');
    }

    public function appointment()
    {
        return $this->hasMany('myocuhub\Models\Appointment');
    }

    public function practiceNetwork()
    {
        return $this->hasMany('myocuhub\Models\PracticeNetwork');
    }

    public static function getPracticeByUserID($userID, $filter = null)
    {
        $query = self::query();
        if (!$filter) {
            $query->withTrashed();
        }
        $query
            ->rightjoin('practice_user', 'practices.id', '=', 'practice_user.practice_id')
            ->where('user_id', $userID);
        return $query->get(['practices.id', 'practices.name', 'practices.email']);
    }

    public static function getPracticeByNetwork($networkList)
    {
        $query = self::query();
        $query->whereNull('manually_created');
        foreach ($networkList as $networkID) {
            $query->whereHas('practiceNetwork', function ($query) use ($networkID) {
                $query->where('network_id', $networkID);
            });
        }
        return $query->get(['practices.id', 'practices.name']);
    }
}
