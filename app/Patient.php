<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{

    protected $fillable = ['title','firstname','lastname','workphone','email','addressline1','addressline2','city',
                           'zip','lastfourssn','birthdate','gender','insurancecarrier'];
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
                        case 'email' :
                            $query->where('email', $filter['value']);
                            break;
                        case 'phone' :
                            $query->Where('cellphone','LIKE','%'.$filter['value'].'%');
                            break;
                        case 'address' :
                            $query->Where('city','LIKE','%'.$filter['value'].'%')
                               ->orWhere('addressline1','LIKE','%'.$filter['value'].'%')
                               ->orWhere('addressline2','LIKE','%'.$filter['value'].'%')
                               ->orWhere('country','LIKE','%'.$filter['value'].'%');
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
