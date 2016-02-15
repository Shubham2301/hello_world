<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeUser extends Model
{
    protected $table = 'practice_user';

    public function practice()
    {
        return $this->hasOne(Practice::class, 'id', 'practice_id');
    }

}
