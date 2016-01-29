<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $fillable = ['id', 'name', 'email', 'phone', 'addressline1', 'addressline2', 'city', 'state', 'zip', 'country'];

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
    public function careconsoleStages()
    {
        return $this->hasMany('myocuhub\Models\NetworkStage')
                    ->leftJoin('careconsole_stages', 'network_stage.stage_id', '=', 'careconsole_stages.id')->orderBy('stage_order');
    }
}
