<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{


    public static function getPatients($filters)
    {
       return self::where(function ($query) use ($filters) {
            foreach($filters as $filter) {
                $query->where(function ($query) use ($filter) {
                    switch($filter['type']){
                        case 'name' :
                            $query->where('firstname', $filter['value'])
                                ->orWhere('middlename', $filter['value'])
                                ->orWhere('lastname', $filter['value']);
                            break;

                        case 'ssn' :
                            $query->where('lastfourssn', $filter['value']);
                            break;

                        case 'all' :
                            $query->where('firstname', $filter['value'])
                                ->orWhere('middlename', $filter['value'])
                                ->orWhere('lastname', $filter['value'])
                                ->orWhere('lastfourssn', $filter['value']);

                            break;
                    }
                            });
                }
            })
            ->get();

    }




}
