<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {

	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['provider_id', 'practice_id', 'location_id', 'patient_id', 'network_id', 'appointmenttype_key', 'appointmenttype', 'start_datetime'];

	public static function schedule($attributes){

		$appointment = Appointment::create($attributes);

        if ($appointment) {
            return $appointment;
        } else {
        	return false;
        }
	}

	public function setFPCID($apptID){
		$this->fpc_id = $apptID;
		$this->save();
	}
}