<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkUser extends Model
{
	protected $table = 'network_user';
	protected $fillable = ['network_id', 'user_id'];

	public function user() {
        return $this->belongsTo('myocuhub\User');
    }

    public function network() {
        return $this->belongsTo('myocuhub\Network');
    }

}
