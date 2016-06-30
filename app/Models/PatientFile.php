<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PatientFile extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'patient_files';

  public static function getfiles($patientID){
     return self::where('patient_id', $patientID)
            ->get();
    }
}

