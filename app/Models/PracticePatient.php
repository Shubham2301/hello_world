<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class PracticePatient extends Model
{
    protected $table = 'practice_patient';
    protected $fillable = ['patient_id','practice_id','location_id'];
}
