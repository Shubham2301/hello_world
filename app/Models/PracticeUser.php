<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PracticeUser extends Model
{
    protected $table = 'practice_user';
	protected $fillable = ['practice_id', 'user_id'];

    public function practice()
    {
        return $this->belongsTo('myocuhub\Models\Practice')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('myocuhub\User');
    }
}

