<?php

namespace myocuhub\Models;
use Helper;

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
        return $this->belongsTo('myocuhub\Models\WebFormTemplate', 'web_form_template_id')
                    ->select(array('id', 'display_name'));
	}


    public function getCreatedAtAttribute($value)
    {
        return Helper::formatDate($value, config('constants.date_time_format.date_only'));
    }

}
