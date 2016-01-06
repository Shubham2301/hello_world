<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    
    public function locations()
    {
        // TODO : optimize
        return $this->hasMany('myocuhub\Models\PracticeLocation');
    }




}
