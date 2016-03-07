<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeUser extends Model
{
    protected $table = 'practice_user';
	protected $fillable = ['practice_id', 'user_id'];

    public function practice()
    {
        return $this->hasOne(Practice::class, 'id', 'practice_id');
    }

}
