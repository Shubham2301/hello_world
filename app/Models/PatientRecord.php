<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'patient_records';
	protected $fillable = ['web_form_template_id', 'patient_id', 'content'];

	public function template(){
		return $this->hasOne('myocuhub\Models\WebFormTemplate');
	}
}
