<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class External_scheduling extends Model
{
    protected $fillable = ['practice_id', 'external_link'];
    protected $primaryKey = 'practice_id';

     public function practice()
    {
        return $this->belongsTo('myocuhub\Models\Practice');
    }
}
