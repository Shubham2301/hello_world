<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class EngagementPreference extends Model
{
    protected $fillable = [
        'patient_id',
        'type',
        'language',
        'contact_phone_preference',
    ];
}
