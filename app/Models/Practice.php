<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Practice extends Model {
	protected $fillable = ['name', '	email'];

	public function locations() {
		// TODO : optimize
		return $this->hasMany('myocuhub\Models\PracticeLocation');
	}

}
