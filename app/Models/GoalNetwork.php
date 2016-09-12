<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class GoalNetwork extends Model {
    protected $table = 'goal_network';

    public function goal() {
        return $this->belongsTo('myocuhub\Models\Goal');
    }

    public function network() {
        return $this->belongsTo('myocuhub\Models\Network');
    }
}
