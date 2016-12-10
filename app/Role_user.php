<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Role_user extends Model
{
    protected $table = 'role_user';
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('myocuhub\User');
    }
}

