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

    public static function pastAppointmentsForEngagement(){
        $format = 'Y-m-d H:i:s';
        $appointments = self::where('appointments.active', '1')
            ->where('appointments.start_datetime', '>' , date($format, strtotime('today')))
            ->where('appointments.start_datetime', '<' , date($format, strtotime('tomorrow')))
            ->leftjoin('engagement_preferences', 'appointments.patient_id', '=', 'engagement_preferences.patient_id')
            ->get([
                    'appointments.id as id',
                    'engagement_preferences.type as patient_preference',
                ]);
    }
}