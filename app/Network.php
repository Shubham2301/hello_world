<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    public function referralTypes()
    {
        // TODO : optimize
        return $this->hasMany('myocuhub\NetworkReferraltype')
            ->leftJoin('referraltypes', 'network_referraltype.referraltype_id', '=', 'referraltypes.id');
    }
    public function newReferralTypes()
    {
        // TODO : optimize
        return $this->hasMany('myocuhub\NetworkReferraltype')
            ->leftJoin('referraltypes', 'network_referraltype.referraltype_id', '=', 'referraltypes.id');
    }
}
