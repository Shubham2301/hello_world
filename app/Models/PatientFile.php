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
            ->leftjoin('patientfiletypes', 'patient_files.patientfiletype_id', '=', 'patientfiletypes.id')
		 ->select(DB::raw('patient_files.*, patientfiletypes.*, patient_files.name as filename,patient_files.updated_at as date'))
            ->get();
    }
}

