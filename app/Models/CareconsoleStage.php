<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class CareconsoleStage extends Model
{
    public function kpi()
    {
        return $this->hasMany('myocuhub\Models\KpiStage', 'stage_id')
                    ->leftJoin('kpis', 'kpi_stage.kpi_id', '=', 'kpis.id');
    }
    public function actions()
    {
        return $this->hasMany('myocuhub\Models\StageAction', 'stage_id')
                    ->leftJoin('actions', 'stage_action.action_id', '=', 'actions.id');
    }
}
