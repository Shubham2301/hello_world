<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralHistory extends Model
{
    protected $table = "referral_history";

    public function careConsole()
    {
        return $this->hasOne('myocuhub\Models\Careconsole', 'referral_id');
    }

}
