<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Ccda extends Model
{

    protected $table = 'ccda';

	protected $fillable = [
		'ccdablob',
		'patient_id'
	];


}
