<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use myocuhub\Models\Practice;
use myocuhub\User;

class PracticeLocation extends Model
{
    protected $table = 'practice_location';
    protected $fillable = ['practice_id','locationname','phone','addressline1','addressline2','city','state','zip'];

    public function practice()
    {
        return $this->belongsTo(Practice::class);
    }

    public static function getNearByLocations ($lat, $lng, $range = 10, $providerTypes)
    	{
    	$query = self::query();
    	if (session('user-level') != 1) {
    		$query->whereHas('practice.practiceNetwork', function ($subquery) {
    			$subquery->where('network_id', session('network-id'));
    		});
        }
    	$query->whereHas('practice.practiceUsers', function ($subquery) use ($providerTypes) {
        		$subquery->whereHas('user', function ($subquery) use ($providerTypes) {
        			$subquery->where('active', 1);
        			$subquery->where('usertype_id', 1);
        			if($providerTypes) {
	        			$subquery->where(function ($innerQuery) use ($providerTypes) {
			                $innerQuery->where('provider_type_id', null);
			                foreach ($providerTypes as $type) {
			                    $innerQuery->orWhere('provider_type_id', $type);
			                }
			            });
        			}
        		});
        	})
            ->select(DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( latitude ) ) ) ) AS distance'))
            ->having('distance', '<=', $range)
            ->orderBy('distance', 'ASC');

        $query->with(['practice.practiceUsers' => function ($subquery) use ($providerTypes) {
        	$subquery->whereHas('user', function ($subquery) use ($providerTypes) {
        		$subquery->where('active', 1);
        		$subquery->where('usertype_id', 1);
        		if($providerTypes) {
	        		$subquery->where(function ($innerQuery) use ($providerTypes) {
			            $innerQuery->where('provider_type_id', null);
			            foreach ($providerTypes as $type) {
			                $innerQuery->orWhere('provider_type_id', $type);
			            }
			        });
        		}
        	});
        }]);

        return $query->get();


    }
}
