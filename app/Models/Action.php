<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model {

	public function actionResults() {
		return $this->hasMany('myocuhub\Models\ActionActionResult')
		            ->leftJoin('action_results', 'action_action_result.action_result_id', '=', 'action_results.id');
	}
}
