<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class ReferraltypesPatientfiletypes extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'referraltypes_patientfiletypes';


   public static function getFilesForReferral($referralTypeID = 1){

	   return self::where('referraltype_id', $referralTypeID)
		   ->leftjoin('patientfiletypes', 'referraltypes_patientfiletypes.patientfiletype_id', '=', 'patientfiletypes.id')
		   ->get();
   }

}
