<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeNetwork extends Model {
	protected $table = 'practice_network';
	protected $fillable = ['network_id','practice_id'];

	/**
     * @return mixed
     */
	public function practice()
    {
        return $this->belongsTo('myocuhub\Models\Practice');
    }
}
