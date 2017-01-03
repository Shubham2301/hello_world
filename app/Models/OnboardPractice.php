<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnboardPractice extends Model
{
    use SoftDeletes;

    protected $table = 'onboard_practice';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'practice_id',
        'token',
        'practice_form_data',
    ];

    /**
     * @return mixed
     */
    public function practice()
    {
        return $this->belongsTo('myocuhub\Models\Practice');
    }
}
