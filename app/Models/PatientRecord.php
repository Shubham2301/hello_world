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
            ->select(array('id', 'display_name', 'print_view'));
	}

	/**
     * @return mixed
     */
    public function patient()
    {
        return $this->belongsTo('myocuhub\Patient');
    }


    public function getCreatedAtAttribute($value)
    {
        return Helper::formatDate($value, config('constants.date_time_format.date_only'));
    }

    public static function getPatientRecords ($start_date, $end_date, $network_id, $web_form_id) {
    	return self::query()
    		->whereHas('patient.careConsole.importHistory', function ($subquery) use ($network_id) {
    			$subquery->where('network_id', $network_id);
    		})
    		->where('created_at', '>=', $start_date)
    		->where('created_at', '<=', $end_date)
    		->where('web_form_template_id', $web_form_id)
    		->get();
    }
}
