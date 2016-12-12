<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Referraltype extends Model
{
	public function NetworkReferraltype()
    {
        return $this->hasMany(NetworkReferraltype::class);
    }
    //protected $table = 'referraltypes';
}
