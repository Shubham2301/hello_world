<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class PatientInsurance extends Model
{
    protected $table = 'patient_insurance';

    protected $fillable = [
    	'patient_id',
        'insurance_carrier', 
        'subscriber_name', 
        'subscriber_id', 
        'subscriber_birthdate', 
        'insurance_group_no', 
        'subscriber_relation',
    ];

}
