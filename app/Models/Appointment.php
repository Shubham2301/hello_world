<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {
	//

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