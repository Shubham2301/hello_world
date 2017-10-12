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

    public static function getPracticeByNetwork($networkList, $exclude_manually_created = true)
    {
        $query = self::query();
        if ($exclude_manually_created) {
            $query->whereNull('manually_created');
        }
        foreach ($networkList as $networkID) {
            $query->whereHas('practiceNetwork', function ($sub_query) use ($networkID) {
                $sub_query->where('network_id', $networkID);
            });
        }

        return $query->orderBy('name')->get();
    }

    protected static function getPracticeBillingInformation($practice_id)
    {
        $query = self::query();
        $query->where('id', $practice_id);
        $query->with(['locations', 'practiceNetwork.network']);
        $query->with(['appointment' => function ($sub_query) {
            $sub_query->whereHas('patient', function ($sub_sub_query) {
                $sub_sub_query->excludeTestPatient();
            });
            $sub_query->whereNotNull('enable_writeback');
            $sub_query->orderBy('id');
            $sub_query->first();
        }]);
        $query->with(['practiceUsers' => function ($sub_query) {
            $sub_query->whereHas('user', function ($sub_sub_query) {
                $sub_sub_query->where('active', '1');
                $sub_sub_query->where('usertype_id', '1');
            });
        }, 'practiceUsers.user']);
        $query->withCount(['appointment' => function ($sub_query) {
            $sub_query->whereHas('patient', function ($sub_sub_query) {
                $sub_sub_query->excludeTestPatient();
            });
            $sub_query->whereNotNull('enable_writeback');
        }]);

        return $query->first();
    }
}
