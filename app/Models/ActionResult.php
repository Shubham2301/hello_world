<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class ActionResult extends Model
{
    public function scopeActionResultCheck($query, $action_results)
    {
        foreach ($action_results as $index => $action_result_name) {
            if ($index == 0) {
                $query->where('name', $action_result_name);
            } else {
                $query->orWhere('name', $action_result_name);
            }
        }
        
        return $query;
    }
}
