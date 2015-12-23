<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    public function referralTypes()
    {
        return $this->hasManyThrough('myocuhub\ReferralType' ,'myocuhub\NetworkReferraltype', 'network_id', 'id');
    }
}
