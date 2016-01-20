<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeLocation extends Model
{
    protected $table = 'practice_location';
    protected $fillable = ['practice_id','locationname','phone','addressline1','addressline2','city','state','zip'];
}
