<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Careconsole extends Model
{
    protected $table = "careconsole";

    public function patient()
    {
        return $this->hasOne('myocuhub\Patient');
    }
    public function importHistory()
    {
        return $this->hasOne('myocuhub\Models\ImportHistory');
    }
    public function contactHistory()
    {
        return $this->hasMany('myocuhub\Models\ContactHistory');
    }
    public function referralHistory()
    {
        return $this->hasMany('myocuhub\Models\ReferralHistory');
    }
    public function appointment()
    {
        return $this->hasOne('myocuhub\Models\Appointments');
    }
}
