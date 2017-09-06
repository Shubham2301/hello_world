<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    public function actionResults()
    {
        return $this->hasMany('myocuhub\Models\ActionActionResult')
                    ->leftJoin('action_results', 'action_action_result.action_result_id', '=', 'action_results.id')
                    ->orderBy('action_results.display_name');
    }

    public function scopeActionCheck($query, $actions)
    {
        foreach ($actions as $index => $action_name) {
            if ($index == 0) {
                $query->where('name', $action_name);
            } else {
                $query->orWhere('name', $action_name);
            }
        }

        return $query;
    }
}
