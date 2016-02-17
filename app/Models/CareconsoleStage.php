<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class CareconsoleStage extends Model {
	public function kpi() {
		return $this->hasMany('myocuhub\Models\KpiStage', 'stage_id')
		            ->leftJoin('kpis', 'kpi_stage.kpi_id', '=', 'kpis.id');
	}
	public function llKpiGroup() {
		return $this->hasMany('myocuhub\Models\LlKpiStage', 'stage_id')
		            ->leftJoin('ll_kpis', 'll_kpi_stage.ll_kpi_id', '=', 'll_kpis.id')
		            ->groupBy('ll_kpis.group_name');
	}
	public static function llKpiByGroup($groupName, $stageID) {
		return self::where('careconsole_stages.id', $stageID)
			->leftJoin('ll_kpi_stage', 'careconsole_stages.id', '=', 'll_kpi_stage.stage_id')
			->leftJoin('ll_kpis', 'll_kpi_stage.ll_kpi_id', '=', 'll_kpis.id')
			->where('ll_kpis.group_name', $groupName)
			->get();
	}
	public function actions() {
		return $this->hasMany('myocuhub\Models\StageAction', 'stage_id')
		            ->leftJoin('actions', 'stage_action.action_id', '=', 'actions.id');
	}
}
